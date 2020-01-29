/*! Kagescan Manga Engine 2.0.0
 * @license MIT Copyright (C) 2017-2019 ShinProg / Kagescan */

/*
	Sorry if the code is a bit spaghetti... I will clean it in the next release !
	some part of this code is not secure against XSS injections (especially window.history.href changes ! )
*/

// HELPERS //
	var loadErrorCallback = function(error) { // display an alert message
		alert( 'Attention :\n'
		 +'KageScan est incapable de charger la base de donnée du manga spécifié. La navigation risque de ne pas fonctionner correctement.\n'
		 +'Veuillez re-essayer et si le problème persisite, contactez un administrateur.\n'
		 +'Informations sur l\'erreur :\n'
		 + error
		);
	};
	function loadJSON(location, callbackOk, callbackNo) { //helper to load Json
		var xobj = new XMLHttpRequest();
		xobj.overrideMimeType("application/json");
		xobj.open('GET', location, true);
		xobj.onreadystatechange = function () {
			if (xobj.readyState == 4) {
				if (xobj.status == "200")
					callbackOk(JSON.parse(xobj.responseText));
				else
					callbackNo(xobj.responseText);
			}
		};
		xobj.send(null);
	}
	const isVerticalMode = () => document.getElementById('tagMain').classList.contains("vertical");

// CORE //
	// chapter selection page display
	function displayDescription(e) {
		toChange = document.querySelector(`#${e.parentNode.dataset.volid} .chapDescription`);
		toChange.innerHTML = `<img class='responsive-img' src='${e.dataset.previewimg}'/>`;
	}
	// chapter content display
	function thumbClick(e) {
		const me = e.target || e.srcElement;
		setIndex( parseInt(me.parentNode.dataset.i) );
	}
	function move(dir=true) { // True : next slide, false : previous slide
		globals.currentPage += (dir) ? 1: -1;
		setIndex();
	}
	function setIndex(index=undefined) {
		const mangaPages = document.getElementById('mangaPages');

		if (typeof(index) == "number") {
			globals.currentPage = index;
		}
		if (globals.currentPage >= mangaPages.children.length) {
			return window.location.href = globals.linkNext;
		} else if (globals.currentPage < 0){
			return window.location.href = globals.linkPrev;
		}

		// scroll to view
		if (isVerticalMode()) {
			const mangaView = document.getElementById("mangaView");
			const currentPage = mangaPages.children[globals.currentPage];
			const mangaStickyRect = document.getElementById("mangaSticky").getClientRects()[0];

		    window.scrollTo(0, mangaView.offsetTop + currentPage.offsetTop - mangaStickyRect.height);
			globals.notUserScroll = true;
		} else {
			mangaPages.style.transform = `translateX(${-100*globals.currentPage}%)`;
		}
		updateMangaSticky();
	}
	function updateMangaSticky(){
		// update the thumb container style
		const thumbContainer = document.getElementById("mangaThumb");
		document.querySelector("#mangaThumb .active").classList.remove("active");
		thumbContainer.children[globals.currentPage].classList.add("active");

		// centers the selected thumbnail
		if (! thumbContainer.classList.contains("hide")) {
			thumbContainer.scrollTo(thumbContainer.children[globals.currentPage].offsetLeft - thumbContainer.offsetLeft - thumbContainer.getClientRects()[0].width*0.5 - 10, 0); // center the view of thumbnails
		}
		document.getElementById("pageSelect").value = globals.currentPage;

		// replace url
		window.history.replaceState({id: globals.currentPage}, globals.currentPage, `./#${globals.currentPage+1}`);
	}
	function toggle(id, button=-1) {
	    document.getElementById(id).classList.toggle("hide");
	    if (button!=-1) button.classList.toggle("selected");
	}
	function saveSettings() {
	    const save = document.cookie;
	    const verticalMode = document.getElementById("verticalMode").checked?1:0;
	    const noAnimations = document.getElementById("noAnimations").checked?0:1;
	    const previousSize = document.getElementById("previousSize").value;
	    if (getCookie("manga_verticalMode") !== verticalMode) setCookie("manga_verticalMode",verticalMode, 100);
	    if (getCookie("manga_noAnimations") !== noAnimations) setCookie("manga_noAnimations",noAnimations, 100);
	    if (getCookie("manga_previousSize") !== previousSize) setCookie("manga_previousSize",previousSize, 100);
	    if (save != document.cookie)
	        document.location.reload(true);
	    else
	        toggle("mangaSettings", document.getElementById("toggleSettings"));
	}

// MAIN //

	document.addEventListener('DOMContentLoaded', function() {
		// Materialize init
		document.getElementById("scrollSpyLinks").innerHTML = globals.linksInner;
		document.getElementById("navLinkManga").style.fontWeight = "bold";
		document.getElementById("navLinkManga").classList.add("red-text","text-darken-4");
		M.Sidenav.init( document.querySelectorAll('.sidenav') );

		// The variable pageType was previously defined by the php code
		if (globals.pageType == "reader") { // Read chapter part
			/* VARS */
			const mangaContainer = document.getElementById("mangaPages");
			const thumbContainer = document.getElementById("mangaThumb");
			const pageSelect = document.getElementById("pageSelect");
			let inner = "";

			/* PAGE GENERATION + EVENTS */
			// add event for navigation clics
			document.getElementById("mangaView").addEventListener("click", function(e) {
				move((e.target.id=="prevClickTrigger")? 0:1);
			});
			// don't make the kagescan bar fixed.
			document.getElementById("stickyNav").style = "";

			// generate thumb bar + his event and the pageSelect elem
			thumbContainer.innerHTML = mangaContainer.innerHTML;
			for (var i = 0; i < thumbContainer.children.length; i++) {
				thumbContainer.children[i].addEventListener('click', thumbClick);
				inner += `<option value='${i}'>Page ${i+1}/${thumbContainer.children.length}</option>`;
			}
			thumbContainer.children[0].classList.add("active");
			pageSelect.innerHTML = inner;
			pageSelect.addEventListener("change", function() {
				setIndex(parseInt(pageSelect.value));
			});
			// page generation, second part
			loadJSON("../manga.json", generatePage, loadErrorCallback);

		} else if (globals.pageType=="chapterSelect") { // Select chapter part
			M.Carousel.init( document.querySelectorAll('#scan_chapterSelect .carousel'), {
				noWrap: true,
				indicators: true,
				shift: 20,
				padding: 0,
				dist: -50,
				onCycleTo: function(e) {
					const target = document.getElementById(e.href.split("#")[1]);
					document.getElementById("chapterList_move").style.transform = `translateY(-${target.offsetTop}px)`;
				}
			});
			document.getElementById("chapterList_move").style.transform = `translateY(0px)`; //default
		}
	});
	function generatePage(db) {
		/* Will generate the chapter pages*/
		const chapterSelect = document.getElementById("chapterSelect");
		let inner = "";
		let chapIdBefore = "";
		let obtainedChapterInfo = false;
		globals.linkNext = "../"; // default value if the chapter is the last

		db.volumes.forEach(function(vol) {
			inner += `<option value='#' disabled>${vol.name}</option>`;
			vol.chapters.forEach(function(infos, i) {
				inner += `<option value='${infos.id}'>Chapitre ${infos.id} : ${infos.name}</option>`;
				if (infos.id == globals.chapId){
					globals.chapterInfos = infos;
					globals.linkPrev = `../${chapIdBefore}`;
					obtainedChapterInfo = true;
				} else if (obtainedChapterInfo && globals.linkNext=="../"){
					globals.linkNext = `../${infos.id}`;
				}
				chapIdBefore = infos.id;
			});
		});
		chapterSelect.innerHTML = inner;
		if (typeof(globals.chapterInfos) == "object") {
			const mangaDescription = document.getElementById("mangaDescription");
			const h1 = document.createElement("h1");
			h1.textContent = `${db.mangaName} chapitre ${globals.chapterInfos.id} : ${globals.chapterInfos.name}`;
			const p = document.createElement("p");
			for (const text of globals.chapterInfos.summary.split("\n")) {
				p.append(document.createTextNode(text), document.createElement("br"));
			}
			mangaDescription.append(h1, p);
			chapterSelect.value = globals.chapterInfos.id;
			document.getElementById("linkNext").href = globals.linkNext;
			document.getElementById("linkPrev").href = globals.linkPrev;
		} else {
			return alert("Erreur critique !!\n" +
			`Kagescan est incapable de charger les données du chapitre demandé (${globals.chapId})\n`+
			"Si vous pensez que cette erreur ne devrait pas avoir lieu, merci de contacter l'administrateur du site.");
		}

		chapterSelect.addEventListener("change", function() {
			window.location.href = `../${chapterSelect.value}`;
		});

		window.addEventListener("keydown", function(e) {
			switch (e.code) {
				case 'KeyA': window.location.href = globals.linkPrev; break;
				case 'KeyD': window.location.href = globals.linkNext; break;
				case 'ArrowLeft': return move(0);
				case 'ArrowRight': return move(1);
				default: break;
			}
		}, true);

		window.addEventListener("scroll", function(event) {
			// important code :
			if (globals.pageType == "reader"){
				const mangaSticky = document.getElementById("mangaSticky");
				const mangaDescription = document.getElementById("mangaDescription");
				const scrollbarTop = (globals.scrollbarTop) ? globals.scrollbarTop : mangaSticky.offsetTop;
				if (window.scrollY > scrollbarTop) {
					if (mangaSticky.style.position != "fixed"){
						mangaSticky.style.position = "fixed";
						mangaDescription.style.paddingTop = `${mangaSticky.getClientRects()[0].height}px`;
						globals.scrollbarTop = scrollbarTop;
					}
				} else {
					mangaDescription.style.paddingTop = "";
					mangaSticky.style = "";
				}
			}

			if (isVerticalMode()) {
				// restricts the function calls over the time
				// handmade scrollspy
				const mangaPages = document.getElementById("mangaPages");
				const mangaView = document.getElementById("mangaView");
				const mangaStickyRect = document.getElementById("mangaSticky").getClientRects()[0];
				let pageIndex = -1;
				for (let i = mangaPages.children.length - 1; i >= 0 ; i--) {
					if (window.scrollY + 10 >= mangaView.offsetTop + mangaPages.children[i].offsetTop - mangaStickyRect.height) {
						pageIndex = i;
						break;
					}
				}

				if (pageIndex >= 0) {
					globals.currentPage = pageIndex;
					if (globals.notUserScroll){
						if (globals.updateTimeout){
							clearTimeout(globals.updateTimeout);
						}
						globals.updateTimeout = setTimeout(function(){
							updateMangaSticky();
							globals.notUserScroll = false;
						}, 100);
					} else {
						updateMangaSticky();
					}
				}
			}
		});
		window.onpopstate = function(){ //use the object setter instead of the addEventListener function to call it.
			const urlHashMatch = document.location.hash.match(/#(\d+)/);
			if (urlHashMatch && urlHashMatch.length == 2) {
				setIndex( parseInt(urlHashMatch[1]) -1 );
			}
		};
		window.onpopstate();
	};
	//window.onload = (event) => { Pace.stop(); };
