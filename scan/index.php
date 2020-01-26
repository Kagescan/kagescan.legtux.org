<?php
/*$manga = $_GET["manga"];*/
// ce fichier a été supprimé le 23 11 2019 à 19:56. RIP MA VIE
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<script src="/res/materialize.min.js"></script>
	<script>
	var globals = {
		pageType: "reader",
		currentPage: 0,
		linksInner: "Liens"
	};
	</script>
	<script src="/scan/main.js"></script>
	<link rel="stylesheet" href="/res/materialize.min.css">
	<link rel="stylesheet" href="/scan/main.css"/>
</head>

<body class="grey darken-4">
	<?php include("../res/private/header.html"); ?>
	<main id="tagMain"  class="animations vertical">
		<div id="mangaSticky" class="grey darken-4">
			<div id="mangaNav" class="row container blue-grey darken-4">
				<div class="col s3 m1"><a id="linkPrev" href="../">
					<i class='material-icons'>arrow_back</i>
				</a></div>
				<div class="col s6 m3">
					<select class="browser-default" id="chapterSelect"><option>Loading chapters...</option></select>
				</div>
				<div class="col s3 m1"><a id="linkNext" href="../2/">
					<i class='material-icons'>arrow_forward</i>
				</a></div>
				<div class="col s6 m1">
					<i onclick="toggle('mangaThumb',this)" class='material-icons'>view_carousel</i>
				</div>
				<div class="col s6 m1"><a href="#!">
					<i class='material-icons'>settings</i>
				</a></div>
				<div class="col s3 m1"><a>
					<i class='material-icons'>navigate_before</i>
				</a></div>
				<div class="col s6 m3">
					<select class="browser-default" id="pageSelect"><option>page</option></select>
				</div>
				<div class="col s3 m1">
					<i class='material-icons'>navigate_next</i>
				</div>
			</div>
			<div id="mangaThumb" class="hide">
			</div>
		</div>
		<div id="mangaContainer">
			<div id="mangaDescription" data-chapid="1">
				<h1>Kagerou Days Chapitre 1 : Artificial Enemy</h1>
				<p>Shintaro a pas de chance... RIp sa vie.<br>go dodo</p>
			</div>
			<div id="mangaView">
				<div id="prevClickTrigger"></div>
				<div id="mangaPages">
					<?php
					$root = "/scan/";
					$manga = "kagerou-days";
					$chapterId = "1";
					$pagesList = glob("$manga/$chapterId/*.{webp,png,jpg,jpeg}", GLOB_BRACE);
					sort($pagesList, SORT_NATURAL);
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
