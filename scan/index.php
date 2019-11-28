<?php
// ce fichier a été supprimé le 23 11 2019 à 19:56. RIP MA VIE
?>
<!DOCTYPE html>
<html lang="fr">
  <head></head>
  <body class="grey darken-4">
    <?php include("../res/private/header.html"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
      var linksInner = "Liens";
      var pageType = 3;
    </script>
    <script src="/scan-dev/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="/scan-dev/main.css"/>
    <main id="tagMain"  class="animations vertical">
      <div id="mangaSticky" class="grey darken-4">
        <div id="mangaNav" class="row container blue-grey darken-4">
          <div class="col s3 m1"><a id="linkPrev" href="../">prev</a></div>
          <div class="col s6 m3"><select class="browser-default" id="chapterSelect"><option>chap</option></select></div>
          <div class="col s3 m1"><a id="linkNext" href="../2/">next</a></div>

          <div class="col s6 m1">thumbs</div>
          <div class="col s6 m1">settings</div>

          <div class="col s3 m1">prev</div>
          <div class="col s6 m3"><select class="browser-default" id="pageSelect"><option>page</option></select></div>
          <div class="col s3 m1">next</div>
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
    				<div data-i="0"> <img src="https://kagescan.legtux.org/scan/1/1.jpg"/> </div>
    				<div data-i="1"> <img src="https://kagescan.legtux.org/scan/1/2.jpg"/> </div>
    				<div data-i="2"> <img src="https://kagescan.legtux.org/scan/1/3.jpg"/> </div>
    				<div data-i="3"> <img src="https://kagescan.legtux.org/scan/1/4.jpg"/> </div>
    				<div data-i="4"> <img src="https://kagescan.legtux.org/scan/1/5.jpg"/> </div>
    			</div>
    		</div>
    	</div>
    </main>
  </body>
</html>
