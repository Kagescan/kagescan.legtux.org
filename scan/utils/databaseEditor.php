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
		Manga : <?php echo $manga ?>

		<button type="button" class="btn right" name="save" disabled>Save</button>

		<main id="app" style="margin-top: 20px;" class="row hide-on-small-only">
			<div class="col m6">
				<div class="row" id="list">
				</div>
			</div>
			<div class="col m6" id="editContainer">
				<div id="default" class="active">
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
			chapter: -1,
			getRoot: function(){
				if (globals.volume == -1) {
					return 0;
				} else {
					return (globals.chapter != -1) ? 2 : 1;
				}
			},
			goBack: function() {
				const r = globals.getRoot();
				if (r > 1) { // is chapter edit
					globals.chapter = -1;
				}
				if (r > 0) { // is volume edit
					globals.volume = -1;
				}
			}
		};

		// helpers
		function hideActive(query, target){
			const bef = document.querySelector(`${query}.active`);
			if (bef) {
				bef.classList.remove("active");
			}
			target.classList.add("active");
		}

		// main
		var app = {
			start: function(){
				app.elem = document.getElementById("app");
				app.title = document.getElementById("title");
				app.list = document.getElementById("list");
				app.chapterEdit = document.getElementById("chapterEdit");
				app.volumeEdit = document.getElementById("volumeEdit");
				app.default = document.getElementById("default");
				app.renderList();
			},

			renderList: function(){
				if (globals.getRoot() == 1) { // chapters edit
					app.list.innerHTML = "";

					const backBtn = document.createElement("a");
					backBtn.innerText = "Go back";
					backBtn.addEventListener("click", function(){
						console.log("back");
					});
					list.append(backBtn);

					//app.list;
					for (const i in db.volumes[globals.volume].chapters) {
						const chapter = db.volumes[globals.volume].chapters[i];
						const a = document.createElement("a");
						a.innerText = chapter.name;
						a.href = `#${i}`;
						a.addEventListener("click", function(){
							globals.chapter = i;
							app.updateEditor();
						});

						a.classList.add("col", "s12");
						if (i==0) a.classList.add("active");

						list.append(a);
						console.log(chapter);
					}
					console.log("hello");
				} else {
					for (const i in db.volumes) {
						const a = document.createElement("a");
						a.innerText = db.volumes[i].name;
						a.href = `#${i}`;
						a.addEventListener("click", function(){
							if (this.classList.contains("active")){
								globals.volume = i;
							} else {
								globals.volume = -1;
								hideActive("#list a", this );
							}
							app.updateEditor(this);
						});
						a.classList.add("col", "s12");
						app.list.append(a);
					}
				}
			},

			updateEditor: function(before){
				switch (globals.getRoot()) {
					case 0:
						hideActive("#editContainer>div", app.volumeEdit);
						console.log("top");
						break;
					case 1:
						console.log("volume");
						hideActive("#editContainer>div", app.chapterEdit);
						app.renderList();
						globals.chapter = 0;
						// don't break.
					case 2:
						console.log("chapter");
						break;
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
