<?php

$manga = "kagerou-days"; //TODO : add security

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
 	<head>
 		<meta charset="utf-8">
 		<title>Kagescan Database Editor</title>

		<link rel="stylesheet" href="databaseEditor.css">
		<link rel="stylesheet" href="/res/materialize.min.css">

		<script>
			var db = <?php include("../$manga/manga.json") ?>;
		</script>
		<script src="databaseEditor.js" charset="utf-8"></script>
 	</head>

 	<body class="">
		<header class="container">
			<h1>Kagescan Database Editor</h1>
			<p>Local changes autosave status: <span id="autoSaveStatus">Not modified</span></p>
			<button type="button" class="btn right" name="save" disabled>Save local changes to the real database</button>
		</header>
		<form class="hide" id="sendToServerPopup">
			<h3>Send local changes to the server</h3>
			<p>Please check your edits before sending : </p>
			<ul id="databaseDiff">

			</ul>
			<input type="submit" name="submit" class="btn red" value="Send the local database to the server">
			Raw database :
			<textarea id="databaseRaw">
			</textarea>
		</form>
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
 </html>
