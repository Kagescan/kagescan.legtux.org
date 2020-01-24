<?php
// ce fichier a été supprimé le 23 11 2019 à 19:56. RIP MA VIE
?>
<!DOCTYPE html>
<html lang="fr">
  <head></head>
  <body class="grey darken-4">
    <?php include("../res/private/header.html"); ?>
    <script src="/res/materialize.min.js"></script>
    <script>
	  var globals = {
		pageType: 3,
	  	currentPage: 0,
		linksInner: "Liens"
	  };
    </script>
    <script src="/scan/main.js"></script>
    <link rel="stylesheet" href="/res/materialize.min.css">
    <link rel="stylesheet" href="/scan/main.css"/>
    <main id="tagMain"  class="animations ">
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
          <div class="col s6 m1"><a onclick="toggle('mangaThumb',this)" href="#!">
			  <i class='material-icons'>view_carousel</i>
		  </a></div>
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
					// TODO: make it php
					?>
    				<div data-i="0"> <img src="/scan/kagerou-days/1/1.webp"/> </div>
    				<div data-i="1"> <img src="/scan/kagerou-days/1/2.webp"/> </div>
    				<div data-i="2"> <img src="/scan/kagerou-days/1/3.webp"/> </div>
    				<div data-i="3"> <img src="/scan/kagerou-days/1/4.webp"/> </div>
    				<div data-i="4"> <img src="/scan/kagerou-days/1/5.webp"/> </div>
    			</div>
    		</div>
    	</div>
    </main>
  </body>
</html>
