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
 	<body class="container">
		<h1>Kagescan Database Editor</h1>
		<p>Manga : <?php echo $manga ?></p>
		<p>
			<button type="button" name="goToMenu" onclick="app.applyDefaultDisplay()" class="btn">Go back (Volume Selection)</button>
			<button type="button" class="btn right" name="save" disabled>Save</button>
		</p>
		<main id="app" style="margin-top: 20px;" class="row hide-on-small-only">
			<div class="col m6">
				<div class="row" id="list">
				</div>
			</div>
			<div class="col m6" id="editContainer">
				<div id="defaultEditorMsg" class="active">
					<p>Click on the volume to select it and edit his informations.<br>
					Click again to show and edit the chapters</p>
				</div>
				<div id="chapterEdit">
					Chapter edit
				</div>
				<div id="volumeEdit">
					Volume Edit
					<!--<h2 id="title"></h2>
					<input type="text" name="name">
					<input type="text" name="id">
					<input type="text" name="coverArt">

					<textarea name="summary" rows="8" cols="80">
					</textarea>-->
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

			app.applyDefaultDisplay();
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
			document.querySelector("button[name='goToMenu']").style.display = "initial";
			exchangeActiveClass("#editContainer>div", app.volumeEdit);
		},
		generateChapterEditor: function( parentChapterBtn) {
			exchangeActiveClass("#editContainer>div", app.chapterEdit);
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
