/*! Kagescan Manga Engine 2.0.0
 * @license MIT Copyright (C) 2017-2019 ShinProg / Kagescan */


// HELPERS //
	var loadErrorCallback = function(error) { // display an alert message
		alert(`Attention : KageScan est incapable de charger la base de donnée du manga spécifié. La navigation risque de ne pas fonctionner correctement.
	Veuillez re-essayer et si le problème persisite, contactez un administrateur.

	Informations sur l'erreur :
	${error}`);
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
// CORE //
	function displayDescription(e) {
		toChange = document.querySelector(`#${e.parentNode.dataset.volid} .chapDescription`);
		toChange.innerHTML = `<img class='responsive-img' src='${e.dataset.previewimg}'/>`;
	}
	function thumbClick(e) {
		const me = e.target || e.srcElement;
		globals.currentPage = parseInt(me.parentNode.dataset.i);
		setIndex();
	}
	function move(dir=true) { // True : next slide, false : previous slide
		const pages = document.getElementById('mangaPages');
		if (dir) { // Next
			if (globals.currentPage < pages.children.length-1) {
				globals.currentPage++;
			} else {
				return window.location.replace(document.getElementById('linkNext').href);
			}
		} else { // Previous
			if (globals.currentPage > 0) {
				 globals.currentPage--;
			} else {
				return window.location.replace(document.getElementById('linkPrev').href);
			}
		}
		setIndex();
	}
	function setIndex(index) { // move to the [index]th slide
		const pages = document.getElementById('mangaPages');
		const thumbContainer = document.getElementById("mangaThumb");

		// scroll to view
		if (document.getElementById('tagMain').classList.contains("vertical")) {
		    //console.log(pages.children[index].getClientRects()[0], pages.children[index].offsetTop)
		    //pages.offsetParent.offsetTop + pages.children[index].offsetTop - document.getElementById("mangaSticky").getClientRects()[0].height
			//window.scrollTo(0,
			//    );
		} else { //horizontal
			pages.style.transform = `translateX(${-100*globals.currentPage}%)`;
		}

		// update the thumb container style
		document.querySelector("#mangaThumb .active").classList.remove("active");
		thumbContainer.children[globals.currentPage].classList.add("active");

		// center the selected thumbnailglobals.currentPage
		if (! thumbContainer.classList.contains("hide")) {
			thumbContainer.scrollTo(thumbContainer.children[globals.currentPage].offsetLeft - thumbContainer.offsetLeft - thumbContainer.getClientRects()[0].width*0.5 - 10, 0); // center the view of thumbnails
		}
		document.getElementById("pageSelect").value = globals.currentPage
		globals.currentPage;
	}
	function changeChapter(i) { // redirect to ../i
		window.location.href = `../${encodeURIComponent(i)}`;
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
		document.getElementById("scrollSpyLinks").innerHTML = globals.linksInner;
		document.getElementById("navLinkManga").style.fontWeight = "bold";
		document.getElementById("navLinkManga").classList.add("red-text","text-darken-4");
		M.Sidenav.init( document.querySelectorAll('.sidenav') );
		// The variable pageType was previously defined
		if (globals.pageType == 3) { // Read chapter part
			document.getElementById("mangaView").addEventListener("click", function(e) {
				move((e.target.id=="prevClickTrigger")? 0:1);
			});
			// var
			const mangaContainer = document.getElementById("mangaPages");
			const thumbContainer = document.getElementById("mangaThumb");
			let inner = "";
			// page generation + events
			thumbContainer.innerHTML = mangaContainer.innerHTML;
			for (var i = 0; i < thumbContainer.children.length; i++) {
				thumbContainer.children[i].addEventListener('click', thumbClick);
				inner += `<option value='${i}'>Page ${i+1}/${thumbContainer.children.length}</option>`;
			}
			document.getElementById("pageSelect").innerHTML = inner;
			// init
			thumbContainer.children[0].classList.add("active");
			mangaContainer.dataset.i = thumbContainer.children[0].dataset.i;
			// page generation, second part
			loadJSON("../manga.json", generatePage, loadErrorCallback);
		} else if (globals.pageType==2) { // Select chapter part
			M.Carousel.init( document.querySelectorAll('#scan_chapterSelect .carousel'), {
				noWrap: true,
				indicators: true,
				shift: 20,
				padding: 0,
				dist: -50,
				onCycleTo: function(e) {
					let target = document.getElementById(e.href.split("#")[1]);
					document.getElementById("chapterList_move").style.transform = `translateY(-${target.offsetTop}px)`;
				}
			});
			document.getElementById("chapterList_move").style.transform = `translateY(0px)`; //default
		}
	});
	function generatePage(db) {
		/* Will generate the chapter pages*/
		let inner = "";
		db.volumes.forEach(function(vol) {
			inner += `<option value='#' disabled>${vol.name}</option>`;
			vol.chapters.forEach(function(infos, i) {
				inner += `<option value='${infos.id}'>Chapitre ${infos.id} : ${infos.name}</option>`;
			});
		});
		document.getElementById("chapterSelect").innerHTML = inner;
	}
	window.addEventListener("keydown", function(e) {
		switch (e.code) {
			case 'KeyA': window.location.replace(document.getElementById('linkPrev').href); break;
			case 'KeyD': window.location.replace(document.getElementById('linkNext').href); break;
			case 'ArrowLeft': return move(0);
			case 'ArrowRight': return move(1);
			default: break;
		}
	}, true);

	//window.onload = (event) => { Pace.stop(); };
