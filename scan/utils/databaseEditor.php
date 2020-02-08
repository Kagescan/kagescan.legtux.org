<?php

$manga = "kagerou-days"; //TODO : add security

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
 	<head>
 		<meta charset="utf-8">
 		<title>Kagescan Database Editor</title>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="/res/materialize.min.css">
 	</head>
 	<body class="">
		<header class="container">
			<h1>Kagescan Database Editor</h1>
			<button type="button" class="btn right" name="save" disabled>Save</button>
		</header>
		<main id="app" style="margin: 20px;" class="row hide-on-small-only valign-wrapper">
			<div class="col m4">
				<button type="button" name="goToMenu" onclick="app.applyDefaultDisplay()" class="btn right">Go back (Volume Selection)</button>
				<div class="row" id="list">
				</div>
			</div>
			<div class="col m8" id="editContainer">
				<div id="defaultEditorMsg" class="active">
					<button type="button" name="addVolume" class="btn right" disabled>Add a volume (tba)</button>
					<p>Click on the volume to select it and edit his informations.<br>
					Click again to show and edit the chapters</p>
				</div>
				<div id="chapterEdit">
					<h3>Edit Chapter</h3>
					<p> Chapter Name : <input type="text" name="name">
					</p>
					<p> Chapter ID : <input type="text" name="id">
					</p>
					<p> Preview page URL : <input type="text" name="previewImg">
					</p>
					<p> Chapter summary</p>
					<textarea name="summary" rows="8" cols="80">
					</textarea>
				</div>
				<div id="volumeEdit">
					<h3>Edit Volume</h3>
					<button type="button" name="addChapter" class="btn" disabled>Add a chapter (tba)</button>

					<p> Volume Name : <input type="text" name="name">
					</p>
					<p> Volume ID : <input type="text" name="id">
					</p>
					<p> Cover Art URL : <input type="text" name="coverArt">
					</p>
					<p> Volume summary</p>
					<textarea name="summary" rows="8" cols="80">
					</textarea>
				</div>
			</div>
		</main>
		<p class="hide-on-med-and-up">Your screen is too small !! Rotate your phone, enable desktop mode or use a computer/larger screen to use the database editor.</p>
 	</body>

<script>
	//vars
	var globals = {
		volume: -1,
		chapter: -1
	};

	// helpers
	function exchangeActiveClass(query, target){
		const bef = document.querySelector(`${query}.active`);
		if (bef) {
			bef.classList.remove("active");
		}
		target.classList.add("active");
	}
	function cleanButIgnore( parent, toIgnore ){
		while (parent.childNodes.length > 1) {
			if (parent.lastChild != toIgnore) {
				parent.removeChild(parent.lastChild);
			} else if (parent.firstChild != toIgnore) {
				parent.removeChild(parent.firstChild);
			}
		}
	}

	// main
	var app = {
		start: function() {
			app.elem = document.getElementById("app");
			app.title = document.getElementById("title");
			app.list = document.getElementById("list");
			app.chapterEdit = document.getElementById("chapterEdit");
			app.volumeEdit = document.getElementById("volumeEdit");
			app.defaultEditorMsg = document.getElementById("defaultEditorMsg");

			app.initElements();
			app.applyDefaultDisplay();
		},

		initElements: function(){
			const title = document.createElement("h3");
			title.innerText = db.mangaName;
			app.defaultEditorMsg.prepend(title);

		},

		// general
		applyDefaultDisplay: function() {
			app.generateVolumeChoice();
			app.displayDefaultEditorMsg();

			document.querySelector("button[name='goToMenu']").style.display = "none";
		},

		// Events
		volumeBtnOnClick: function() {
			if (! this.classList.contains("active")){
				this.classList.add("active");
				app.generateChapterChoice(this);
				app.generateVolumeEditor(this);
			}
		},
		chapterBtnOnClick: function() {
			if (! this.classList.contains("active")){
				exchangeActiveClass("#list a", this);
				app.generateChapterEditor(this);
			}
		},

		// Choice section
		generateVolumeChoice: function() {
			app.list.innerHTML = "";

			for (const i in db.volumes) {
				app.addChoiceBtn( (btn) => {
					btn.innerText = db.volumes[i].name;
					btn.href = `#${i}`;
					btn.onclick = app.volumeBtnOnClick;
					btn.dataset.volumeIndex = i;
				} );
			}
		},
		generateChapterChoice: function( parentVolumeBtn ) {
			cleanButIgnore( app.list, parentVolumeBtn );

			const chapterList = db.volumes[ parentVolumeBtn.dataset.volumeIndex ].chapters;
			for (const i in chapterList) {
				app.addChoiceBtn( (btn) => {
					btn.innerText = chapterList[i].name;
					btn.href = `#${i}`;
					btn.onclick = app.chapterBtnOnClick;
					btn.dataset.chapterIndex = i;
				} );
			}
		},
		addChoiceBtn: function(callback) {
			const choice = document.createElement("a");
			choice.classList.add("col", "s12");
			if (typeof(callback) == "function") {
				callback(choice);
			}
			app.list.append(choice);
		},

		// Editor section
		displayDefaultEditorMsg: function() {
			exchangeActiveClass("#editContainer>div", app.defaultEditorMsg);
		},
		generateVolumeEditor: function( parentVolumeBtn ) {
			document.querySelector("button[name='goToMenu']").style.display = "block";
			exchangeActiveClass("#editContainer>div", app.volumeEdit);
			console.log(parentVolumeBtn.dataset);


		},
		generateChapterEditor: function( parentChapterBtn) {
			exchangeActiveClass("#editContainer>div", app.chapterEdit);

			const volumeIndex = parentChapterBtn.parentNode.firstChild.dataset.volumeIndex;
			const chapterIndex = parentChapterBtn.dataset.chapterIndex;

			const chapterInfo = (volumeIndex)? db.volumes[volumeIndex].chapters[chapterIndex] :null;
			if (chapterInfo){
				for (var key in chapterInfo) {
					const input = document.querySelector(`#chapterEdit [name="${key}"]`);
					if (input) {
						input.value = chapterInfo[key];
					}
				}
			}
		}
	}
	document.addEventListener('DOMContentLoaded', function() {
		app.start();
	});
	var db = <?php include("../$manga/manga.json") ?>;
</script>

	<style>
		@font-face {
	      font-family: 'Material Icons';
	      font-style: normal;
	      font-weight: 400;
	      src: url(/res/icons.woff2) format('woff2');
	    }

	    .material-icons {
	      font-family: 'Material Icons';
	      font-weight: normal;
	      font-style: normal;
	      font-size: 24px;
	      line-height: 1;
	      letter-spacing: normal;
	      text-transform: none;
	      display: inline-block;
	      white-space: nowrap;
	      word-wrap: normal;
	      direction: ltr;
	      -moz-font-feature-settings: 'liga';
	      -moz-osx-font-smoothing: grayscale;
	    }
		html {font-family: sans-serif;}
	</style>
 </html>
