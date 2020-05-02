<?php

$manga = $_GET["manga"];

$dbFile = file_get_contents("$manga/manga.json");

if ($dbFile){
	$db = json_decode($dbFile, true);
} else {
	die("<br>The manga is not valid, or don't have a database file.");
}
if (! is_array($db['volumes'])) {
	die("<br>Invalid database for manga [$manga] : this manga don't contains any volumes");
}
if (! is_string($db["mangaName"])) {
	die("<br>Invalid database for manga [$manga] : value for mangaName is not valid or don't exist");
}

function generateVolumeList($db) {
	foreach ($db['volumes'] as $i => $volume) {
		if ($volume["name"] && $volume["chapters"]){
			$dataSummary = ($volume["summary"]) ? str_replace("\n", "<br>", $volume["summary"]) :"Pas de résumé...";
			$coverImg = "- Sans couverture -";
			$volumeName = htmlentities($volume["name"], ENT_QUOTES);
			if ($volume["coverArt"]) {
				$src = htmlentities($volume["coverArt"], ENT_QUOTES);
				$alt = $db["mangaName"]." ".$volume["name"];
				$coverImg = "<img src='$src' alt='$alt cover' class='volumeCoverImg' />";
			}
			?>
			<section>
				<div class="tabList">
					<ul>
						<li></li>
						<?php generateChapterList($volume["chapters"]) ?>
					</ul>
				</div>
				<div class="tabContent" data-coverImg="">
					<?php
					echo $coverImg;
					echo "<span class='content'>";
					echo "<h3>", $volumeName, "</h3>\n";
					echo $dataSummary;
					echo "</span>";
					?>
				</div>
			</section>
			<?php
		}
	}
}
function generateChapterList($allChapters) {
	foreach ($allChapters as $i => $chapter) {
		if ($chapter["id"] && $chapter["name"]){
			echo "<li>".$chapter["id"].". ".$chapter["name"]."</li>";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<script src="/res/materialize.min.js"></script>
	<script>
		var globals = {
			linksInner: ["<?php echo $manga; ?>", "Mangas"],
			pageType: "chapterSelect"
		};
	</script>
	<script src="/scan/main.js"></script>
	<link rel="stylesheet" href="/res/materialize.min.css">
	<link rel="stylesheet" href="/scan/main.css"/>
	<meta name="viewport" content="width=device-width">
</head>

<body id="chapterSelect" class="grey darken-4">
	<?php include("../res/private/header.html"); ?>
	<main id="tagMain" class="white-text container">
		<h1>Kagerou days</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

		<p></p>
		<h2>Liste des volumes</h2>
		<ul id="volumeNavigation">
		</ul>
	</main>
	<div id="volumeList" class="container">
		<?php
		generateVolumeList($db);
		?>
	</div>
</body>
</html>
