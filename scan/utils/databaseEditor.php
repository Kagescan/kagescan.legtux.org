<?php

$manga = "kagerou-days"; //TODO : add security

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Kagescan Database Editor</title>

		<link rel="stylesheet" href="databaseEditor.css">
		<link rel="stylesheet" href="../../res/materialize.min.css">

		<script src="databaseEditor_noServer.js" charset="utf-8"></script>
		<script src="databaseEditor.js" charset="utf-8"></script>
	</head>

	<body class="">
		<header class="container">
			<h1>Kagescan Database Editor</h1>
			<button type="button" class="btn right" id="saveBtn" onclick="app.showPopupThatSendLocalValuesToServer()">Save local changes to the real database</button>
		</header>
		<form class="hide container" id="sendToServerPopup" action="?" method="POST" >
			<h3>Send local changes to the server</h3>
			<p>Please check your edits before sending : </p>
			<div class="row" id="databaseDiffContainer">
				<ul class="col m6" id="databaseDiff">
					<li>... ?</li>
				</ul>
				<div class="col m6" id="showSelectedDiff">

				</div>
			</div>
			<p>Warning : You can't revert the sending operation. Make saves !</p>
			<div class="row valign-wrapper">
				<div class="col m3"><button class="btn" onclick="app.applyDefaultDisplay();">Go back</button></div>
				<div class="col m5">
					<label for="pass">Server password:</label>
					<input type="password" id="pass" name="password" required>
				</div>
				<div class="col m4 right-align"><input type="submit" name="saveDatabase" class="btn red" value="Send the local database to the server"></div>
			</div>

			<p>Or you can copy/paste your changes to the real file : (<a href="#!" onclick="app.copyDatabaseRawTexatera()">Click here to copy</a>)</p>
			<textarea id="databaseRaw" name="database">
			</textarea>
		</form>
		<main id="app" style="margin: 20px;" class="row hide-on-small-only valign-wrapper">
			<div class="col m4">
				<div class="row" id="listControlBtns">
					<button class="col s12 btn" name="removeActive" onclick="app.removeActive()" >Delete the selected entry</button>
					<button class="col s12 btn" name="goToMenu" onclick="app.applyDefaultDisplay()">Go back (Volume Selection)</button>
				</div>
				<div class="row" id="list">
				</div>
			</div>
			<div class="col m8" id="editContainer">
				<div id="defaultEditorMsg" class="active">
					<p>Click on the volume to select it and edit his informations.<br>
					Click again to show and edit the chapters</p>
				</div>
				<div id="chapterEdit">
					<h3>Edit Chapter</h3>
					<p> Chapter Name : <input type="text" name="name" oninput="document.querySelector('#list a.active').innerText = this.value;">
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

					<p> Volume Name : <input type="text" name="name" oninput="document.querySelector('#list a.active').innerText = this.value;">
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
