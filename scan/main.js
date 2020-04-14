/*! Kagescan Manga Engine 2.0.0
 * @license MIT Copyright (C) 2017-2020 ShinProg / Kagescan */

// HELPERS //
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

// MAIN //

document.addEventListener('DOMContentLoaded', function() {
	// Materialize init
	let linksInner = "";
	for (var i = globals.linksInner.length-1; i >= 0; i--) {
		if (i != 0) {
			linksInner += `<li><a href="${"../".repeat(i)}">${globals.linksInner[i]}</a></li>`;
			linksInner += "<li><i class='material-icons'>navigate_next</i></li>";
		} else {
			linksInner += `<li><a href="#1" class="active">${globals.linksInner[i]}</a></li>`;
		}
	}
	document.getElementById("scrollSpyLinks").innerHTML = linksInner;
	document.getElementById("navLinkManga").style.fontWeight = "bold";
	document.getElementById("navLinkManga").classList.add("red-text","text-darken-4");
	M.Sidenav.init( document.querySelector('.sidenav') );


	if (globals.pageType == "reader") {
		// generate page and events;
		initReader();
		// go to the default page
		window.onpopstate();
	} else if (globals.pageType=="chapterSelect") { // Select chapter part

	}
});

function initReader() {
	/* VARS */
	const mangaContainer = document.getElementById("mangaPages");
	const thumbContainer = document.getElementById("mangaThumb");
	const chapterSelect = document.getElementById("chapterSelect");
	const pageSelect = document.getElementById("pageSelect");

	/* PAGE GENERATION */

	// don't make the kagescan bar fixed.
	document.getElementById("stickyNav").style = "";

	// generate thumb bar + his event and the pageSelect elem
	thumbContainer.innerHTML = mangaContainer.innerHTML;
	thumbContainer.children[0].classList.add("active");
	for (var i = 0; i < thumbContainer.children.length; i++) {
		thumbContainer.children[i].addEventListener('click', thumbClick);
		pageSelect.innerHTML += `<option value='${i}'>Page ${i+1}/${thumbContainer.children.length}</option>`;
	}

	pageSelect.addEventListener("change", function() {
		setIndex(parseInt(pageSelect.value));
	});
	chapterSelect.addEventListener("change", function() {
		window.location.href = `../${chapterSelect.value}`;
	});

	document.getElementById("mangaView").addEventListener("click", function(e) {
		move(e.target.id!="prevClickTrigger");
	});

	// Window events

	window.onpopstate = function(){ //use the object setter instead of the addEventListener function to call it.
		const urlHashMatch = document.location.hash.match(/#(\d+)/);
		if (urlHashMatch && urlHashMatch.length == 2) {
			setIndex( parseInt(urlHashMatch[1]) -1 );
		}
	};

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
		// set the navbar fixed:
		if (globals.pageType == "reader"){
			const mangaSticky = document.getElementById("mangaSticky");
			const mangaDescription = document.getElementById("mangaDescription");
			const scrollbarTop = document.getElementById("stickyNav").getClientRects()[0].height;

			if (window.scrollY > scrollbarTop) {
				if (mangaSticky.style.position != "fixed"){
					mangaSticky.style.position = "fixed";
					if (mangaSticky.getClientRects()[0].height<200){
						// the value of the client rects is sometimes wrong due to init.
						mangaDescription.style.paddingTop = `${mangaSticky.getClientRects()[0].height}px`;
					}
					globals.scrollbarTop = scrollbarTop;
				}
			} else {
				mangaDescription.style.paddingTop = "";
				mangaSticky.style = "";
			}
		}

		if (isVerticalMode()) {
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
					// restricts the function calls over the time
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
};
//window.onload = (event) => { Pace.stop(); };
