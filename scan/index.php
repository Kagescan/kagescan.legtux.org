<?php
/*$manga = $_GET["manga"];*/
// ce fichier a été supprimé le 23 11 2019 à 19:56. RIP MA VIE

$root = "/scan/";
$manga = $_GET["manga"];
$chapterId = $_GET["chapter"];

$pagesList = glob("$manga/$chapterId/*.{webp,png,jpg,jpeg}", GLOB_BRACE);
$pageCount = count($pagesList);
if ( $pageCount <= 0 || empty($chapterId)){
	die("404 : the chapter you are looking for don't exist, has been moved or don't have any pages !");
}
sort($pagesList, SORT_NATURAL);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<script src="/res/materialize.min.js"></script>
	<script>
	var globals = {
		mangaId: <?php echo "'$manga'"; ?>,
		chapId: <?php echo "'$chapterId'"; ?>,
		pageType: "reader",
		currentPage: 0,
		linksInner: "Liens",
	};
	</script>
	<script src="/scan/main.js"></script>
	<link rel="stylesheet" href="/res/materialize.min.css">
	<link rel="stylesheet" href="/scan/main.css"/>
</head>

<body class="grey darken-4">
	<?php include("../res/private/header.html"); ?>
	<div id="mangaSettings" class="hide"><div>
		<i class="material-icons" onclick="saveSettings()">close</i>
		<h2 class="center">Paramètres</h2>

		<em>- Pour avoir plus de contrôle dans votre navigation -</em>
		<div class="switch">Mode Vertical : <label><input type="checkbox" id="verticalMode" value=""> <span class="lever"></span></label></div><br>

		<em>- Pour se concentrer sur la lecture -</em>
		<div class="switch">Activer les animations : <label><input type="checkbox" id="noAnimations" value=""> <span class="lever"></span></label></div><br>

		<em>- Pour une lecture plus agréable sur un PC / grand écran -</em>
		<div class="switch">Activer une marge de 70% : <label><input type="checkbox" id="margin70" value=""> <span class="lever"></span></label></div><br>

		<em>- Pour une navigation plus confortable sur téléphone -</em>
		Taille de la zone de clic pour la page arrière (mode horizontal uniquement)<br>
		<p class="range-field"><input type="range" min="0" max="100" id="previousSize" value=""></p>

		<p><b>La modification des paramètres entrainera un rechargement de la page !</b></p>
		<button type="button" name="button" class="btn right">Enregistrer</button>
		<br><br>
	</div></div>
	<main id="tagMain"  class="animations vertical">
		<div id="mangaSticky" class="grey darken-4">
			<div id="mangaNav" class="row container blue-grey darken-4">
				<div class="col s3 m1"><a id="linkPrev" href="#1">
					<i class='material-icons'>arrow_back</i>
				</a></div>
				<div class="col s6 m3">
					<select class="browser-default" id="chapterSelect"><option>Loading chapters...</option></select>
				</div>
				<div class="col s3 m1"><a id="linkNext" href="#<?php echo $pageCount;?>">
					<i class='material-icons'>arrow_forward</i>
				</a></div>
				<div class="col s6 m1">
					<i onclick="toggle('mangaThumb', this)" class='material-icons'>view_carousel</i>
				</div>
				<div class="col s6 m1">
					<i onclick="toggle('mangaSettings', this)" class='material-icons'>settings</i>
				</div>
				<div class="col s3 m1">
					<i onclick="move(false);" class='material-icons'>navigate_before</i>
				</div>
				<div class="col s6 m3">
					<select class="browser-default" id="pageSelect"><option>page</option></select>
				</div>
				<div class="col s3 m1">
					<i onclick="move();" class='material-icons'>navigate_next</i>
				</div>
			</div>
			<div id="mangaThumb" class="hide">
			</div>
		</div>
		<div id="mangaContainer">
			<div id="mangaDescription" class="grey-text text-lighten-2">

			</div>
			<div id="mangaView">
				<div id="prevClickTrigger"></div>
				<div id="mangaPages">
					<?php
					foreach ($pagesList as $key => $filename) {
						echo "<div data-i='$key'> <img src='$root$filename'/> </div>\n";
					}
					?>
				</div>
			</div>
		</div>
	</main>
</body>
</html>
