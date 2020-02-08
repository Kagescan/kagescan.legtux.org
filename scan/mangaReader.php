<?php
/*$manga = $_GET["manga"];*/
// ce fichier a été supprimé le 23 11 2019 à 19:56. RIP MA VIE

$root = "/scan/";
$manga = $_GET["manga"];
$chapterId = $_GET["chapter"];


if (!empty($_POST["changeValue"])){
	$params = array("verticalMode", "noAnimations", "previousSize", "margin70");
	foreach ($params as $i => $param) {
		$_COOKIE[$param] = empty($_POST[$param]) ? 0:$_POST[$param];
		setCookie($param, $_COOKIE[$param], time()+3600*24*30, $root);
	}
}

$mainTagClass = "";
$mainTagClass .= ($_COOKIE["noAnimations"] == 0) ? "animations " : "";
$mainTagClass .= ($_COOKIE["verticalMode"] == 0) ? "" : "vertical ";
$mainTagClass .= ($_COOKIE["margin70"] == 0) ? "" : "margin70";

$pagesList = glob("$manga/$chapterId/*.{webp,png,jpg,jpeg}", GLOB_BRACE);
$pageCount = count($pagesList);
if ( $pageCount <= 0 || empty($chapterId)){
	die("404 : the chapter you are looking for don't exist, has been moved or don't have any pages !");
}
sort($pagesList, SORT_NATURAL);

$dbFile = file_get_contents("$manga/manga.json");
if ($dbFile){
	$db = json_decode($dbFile, true);

	$selectChapterElemContent = "";
	$chapBeforeValue = "";
	$globals_chapterInfos = "";
	$globals_linkPrev = "../";
	$globals_linkNext = "../";
	foreach ($db['volumes'] as $i => $volume) {
		if ($volume["name"] && $volume["chapters"]){
			$selectChapterElemContent .= "<option value='#' disabled>". $volume["name"] ."</option>";
			foreach ($volume["chapters"] as $j => $chapter) {
				if ($chapter["id"] && $chapter["name"]){
					$selectChapterElemContent .= "<option value='".$chapter["id"]."' ";
					if ($chapter["id"] == $chapterId) {
						$globals_chapterInfos = json_encode($chapter);
						$chaptersInfosDecoded = $chapter;
						$globals_linkPrev .= $chapBeforeValue;
						$selectChapterElemContent .= "selected";
					} else if ($globals_chapterInfos !== "" && $globals_linkNext === "../") {
						$globals_linkNext .= $chapter["id"];
					}
					$selectChapterElemContent .= ">Chapitre ".$chapter["id"]." : ".$chapter["name"]."</option>";
					$chapBeforeValue = $chapter["id"];
				} else {
					die("Invalid database : volumes $i chapter $j -> [name] or [id] is undefined !");
				}
			}
		} else {
			die("Invalid database : volume $i -> [name] or [chapters] is undefined !");
		}
	}
} else {
	die("??? : the manga you are looking for seems to exist, but the manga engine couldn't read the datapable !");
}

?>
<!DOCTYPE html>
<html lang="fr" <?php if ($_COOKIE["noAnimations"] != 0) echo "style='scroll-behavior: initial'";?> >
<head>
	<script src="/res/materialize.min.js"></script>
	<script>
	var globals = {
		mangaId: '<?php echo $manga; ?>',
		chapId: '<?php echo $chapterId; ?>',
		linkNext: "<?php echo $globals_linkNext; ?>",
		linkPrev: "<?php echo $globals_linkPrev; ?>",
		chapterInfos: <?php echo $globals_chapterInfos; ?>,
		pageType: "reader",
		linksInner: <?php echo "['Chapitre $chapterId', '".$db["mangaName"]."', 'Mangas']" ?>,
		currentPage: 0
	};
	</script>
	<script src="/scan/main.js"></script>
	<link rel="stylesheet" href="/res/materialize.min.css">
	<link rel="stylesheet" href="/scan/main.css"/>
	<meta name="viewport" content="width=device-width">
</head>

<body class="grey darken-4">
	<?php include("../res/private/header.html"); ?>
	<main id="tagMain"  class="<?php echo $mainTagClass; ?>">
		<div id="mangaSticky" class="grey darken-4">
			<div id="mangaNav" class="row container blue-grey darken-4">
				<div class="col s3 m1"><a id="linkPrev" href="<?php echo $globals_linkPrev?>">
					<i class='material-icons'>arrow_back</i>
				</a></div>
				<div class="col s6 m3">
					<select class="browser-default" id="chapterSelect" autocomplete="off"><?php echo $selectChapterElemContent; ?></select>
				</div>
				<div class="col s3 m1"><a id="linkNext" href="<?php echo $globals_linkNext?>">
					<i class='material-icons'>arrow_forward</i>
				</a></div>
				<div class="col s6 m1">
					<i onclick="toggle('mangaThumb', this)" class='material-icons'>view_carousel</i>
				</div>
				<div class="col s6 m1">
					<i onclick="toggle('mangaSettings', this)" class='material-icons' id="settingsButton">settings</i>
				</div>
				<div class="col s3 m1">
					<i onclick="move(false);" class='material-icons'>navigate_before</i>
				</div>
				<div class="col s6 m3">
					<select class="browser-default" id="pageSelect"></select>
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
				<?php
				echo "<h1 class='center'>". $db["mangaName"];
				if ($chaptersInfosDecoded) {
					echo " Chapitre ".$chaptersInfosDecoded["id"]." : ".$chaptersInfosDecoded["name"]. "</h1>";
					echo "<p class='summary'>".str_replace("\n", "<br>", $chaptersInfosDecoded["summary"])."</p><br>";
				} else {
					echo "</h1>";
				}
				?>

				<p><a href="/discord/tchat.php" style="padding: 10px; border: 2px solid #888; border-radius: 3px; color: #888;">Merci de signaler tout problème (technique, ordre des pages, fautes d'orthographe) à notre équipe en cliquant ici!</a></p>
				<br>
				<hr>
				<br>
			</div>
			<div id="mangaView">
				<div id="prevClickTrigger" style="width: <?php echo $_COOKIE['previousSize']?>%"></div>
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


	<div id="mangaSettings" class="hide">
		<form action="?" method="post">
			<div class="row">
				<h2 class="center">Paramètres</h2>
				<p><b>La modification des paramètres entrainera un rechargement de la page !</b><br>
				Les paramètres sont sauvegardés sur votre ordinateur durant un mois, par le biais de cookies dont seul Kagescan et vous en détient l'accès.</p>

				<em>- Pour avoir plus de contrôle dans votre navigation -</em>
				<div class="switch">Mode Vertical : <label><input type="checkbox" name="verticalMode" value="1" <?php if ($_COOKIE["verticalMode"] != 0) echo "checked"; ?> > <span class="lever"></span></label></div><br>

				<em>- Pour se concentrer sur la lecture -</em>
				<div class="switch">Supprimer les animations : <label><input type="checkbox" name="noAnimations" value="1" <?php if ($_COOKIE["noAnimations"] != 0) echo "checked"; ?>> <span class="lever"></span></label></div><br>

				<em>- Pour une lecture plus agréable sur un PC / grand écran (mode vertical uniquement) -</em>
				<div class="switch">Activer une marge de 70% : <label><input type="checkbox" name="margin70" value="1" <?php if ($_COOKIE["margin70"] != 0) echo "checked"; ?>> <span class="lever"></span></label></div><br>

				<em>- Pour une navigation plus confortable sur téléphone -</em>
				Taille de la zone de clic pour la page arrière (mode horizontal uniquement)<br>
				<input type="range" min="0" max="100" name="previousSize" value="<?php echo $_COOKIE["previousSize"]?>" style="margin: 0;">
				<div class="row"><?php for ($i=1; $i <= 12; $i++) echo "<div class='col s1'>".($i*8)."%</div>";?></div>
			</div>
			<div class="row">
				<input type="submit" class="btn red darken-4  " value="Sauvegarder" name="changeValue" />
				<input type="button" class="btn red darken-4 right" value="Annuler" onclick="toggle('mangaSettings', document.getElementById('settingsButton'))">
			</div>
		</form>
	</div>
</body>
</html>
