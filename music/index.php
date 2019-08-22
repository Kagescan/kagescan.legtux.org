
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Musiques Kagerou Project</title>
	<meta name="theme-color" content="#b71c1c">
    <meta name="description"               content="Écoutez toutes les musiques de la série Kagerou Project avec leurs traductions, gratuitement et légalement !" />
    <meta name="author"                    content="KageScan[Fr]" />
    <meta name="revisit-after"             content="30 days" />
    <meta name="keywords"                  content="kagerou, project, Fr, musiques, music, playlist, days, daze, mekakucity, actors, reload, kagescan, episode, wiki, vostfr, vf, record, ms, mikudb, hatsune miku, IA, IA project, musique, lost time memory, outer science, additional memory, children records" />
    <link rel="alternate" hreflang="fr"    href="https://kagescan.legtux.org/music" />
	<!--link rel="amphtml" href="https://kagescan.legtux.org/m/"-->

    <meta property="og:locale"             content="fr_FR" />
    <meta property="og:url"                content="https://kagescan.legtux.org/music" />
    <meta property="og:type"               content="website" />
    <meta property="og:title"              content="Musiques Kagerou Project - Kagescan" />
    <meta property="og:description"        content="Écoutez toutes les musiques de la série Kagerou Project avec leurs traductions, gratuitement et légalement !" />
    <meta property="og:image"              content="https://kagescan.legtux.org/res/img/banner1.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="1024" />
    <meta property="og:image:height" content="705" />

    <link rel="icon" type="image/png"      href="https://kagescan.legtux.org/res/img/ico.jpg" />
    <link rel="apple-touch-icon"           href="https://kagescan.legtux.org/res/img/ico.jpg" />
  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Raleway" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="/style.css">
  <style>
  	main{
		margin-top: 64px;
	}
	.info {
	   transition: all 0.5s;
	   padding-top: 0;
	}

	.info.pre-animation {
	   opacity:0;
	   padding-top: 20px;
	}
  </style>
</head>
<body class="red">
	<?php include($_SERVER['DOCUMENT_ROOT']."/res/private/header.html"); ?>

	<!-- Main page -->
	<main role="main" class="white">
		<section id="menu" class="scrollspy container">
			<h2>Musiques Kagerou Project.</h2>
			<div class="flow-text">
				<p>Kagerou project a été au départ une série de musiques, et suite à sa popularité, s'est vu adapté en divers supports : Animés, Mangas...</p>
				<p>Le projet est actuellement composé de trois albums "histoire" qui contiennent les chansons basiques de la série.</p>
				<p>Ensuite, il existe d'autres albums dits "de thèmes" car ils contiennent par exemple des OSTs, les génériques de l'animé/film ou bien des covers officiels.</p>
				<p>Enfin, d'autres albums sont encore en vente, comme des lives ou des extraits de futurs albums (l'album Children Records par exemple, en attendant sa parution dans Mekakucity Records)</p>
			</div>
			<p>Info : les liens vers les achats proviennent du site officiel de Kagerou Project (qui redirigent vers Amazon) ou bien de CDJapan, un vendeur agréé. Si vous souhaitez acheter des articles Kagerou Project dans l'esprit de contribuer à l'auteur, n'achetez que depuis Hachimaki, CDJapan ou les liens présents sur mekakushidan.com.<br> Certains de ces liens mènent donc à des pages écrites en japonais, nous nous excusons de ce dérangement.</p>
		</section>
		<section id="story" class="scrollspy container">
			<h2>Albums Principaux</h2>
			<p>L'histoire originale de la série est racontée dans les chansons des albums principaux ! Si vous avez aimé l'histoire des musiques, alors découvrez ses variantes via les autres médias (manga, roman, anime)...</p>
			<p>Info : nous avons bien traduit les musiques de l'album Mekakycity Reload mais nous attendons pour le moment un MV... Si cela met trop de temps, nous nous occuperons de publier notre travail.</p>
			<div class="albumContainer row">
				<div class="carousel col s12 l6">
				</div>
				<div class="info col s12 l6 blue-grey lighten-4">
					<p>Chargement des données...</p>
				</div>
			</div>
		</section>
		<section id="themes" class="scrollspy container">
			<h2>Albums Secondaires</h2>
			<p>Les albums secondaires contiennent les musiques issues d'autres médias. Par exemple, l'album Daze contient les openings/endings de l'animé tandis que l'album red contient l'opening du film.</p>

			<div class="albumContainer row">
				<div class="carousel col s12 l6">
				</div>
				<div class="info col s12 l6 blue-grey lighten-4">
					<p>Chargement des données...</p>
				</div>
			</div>
		</section>
		<section id="additional" class="scrollspy container">
			<h2>Autres Albums</h2>
			<p>Vous trouverez dans cette catégorie d'autres albums, moins connus et qui contiennent peu de musiques.</p>
			<p>On pourra par exemple trouver des enregistrement de concerts, ou des extraits d'albums. Par exemple, l'album Children Records contient deux musiques de l'album Mekakucity Records; pourtant, Mekakucity Records n'était pas encore sorti !</p>
			<p>Info : pour le moment, vous ne pourrez pas écouter les albums venant de cette catégorie directement depuis kagescan</p>

			<div class="albumContainer row">
				<div class="carousel col s12 l6">
				</div>
				<div class="info col s12 l6 blue-grey lighten-4">
					<p>Chargement des données...</p>
				</div>
			</div>
		</section>
	</main>

	<div id="popup" class="modal" style="display:none;">
	  <div class="modal-content" id="popupContent">
		  <p>Chargement des données...</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-close waves-effect waves-green btn-flat">Fermer</a>
	  </div>
	</div>

	<?php include($_SERVER['DOCUMENT_ROOT']."/res/private/footer.html"); ?>

  <!--  Scripts-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script>
		//DEFS
		var data;

		//HELPERS
		var loadErrorCallback = function(error){
			alert(`Erreur : impossible de charger le contenu de la page.
Veuillez re-essayer et si le problème persisite, contactez un administrateur.

Informations sur l'erreur :
${error}`);
		}
		function loadJSON(location, callbackOk, callbackNo) {
			var xobj = new XMLHttpRequest();
			xobj.overrideMimeType("application/json");
			xobj.open('GET', location, true);
			xobj.onreadystatechange = function () {
				if (xobj.readyState == 4) {
					if (xobj.status == "200")
						callbackOk(JSON.parse(xobj.responseText));
					else
						callbackNo(xobj.responseText);
				}
			};
			xobj.send(null);
		}
		var carouselChange = function(elem){
			let index = elem.dataset.i, section = elem.dataset.section;
			let infoContainer = document.querySelector(`#${section} .info`);
			infoContainer.classList.add('pre-animation');
			let albumData = data[section][index];
			let string = `
				<div class="center">
					<h3>${albumData.name}</h3>
					<p> Mis en vente le ${albumData.releaseDate}<br>
						<small>${albumData.jp} - <em>${albumData.trad}</em></small></p>
					<p>${albumData.description}</p>
				</div>
				<blockquote>${albumData.funFact}</blockquote>
				<ul class="collection">
					<a class="collection-item" href="${albumData.amazonLink}"><i class="material-icons">add_shopping_cart</i> Acheter l'album</a>
				`;
			if (section!="additional")
				string += `<a class="collection-item modal-trigger" href="#popup" data-i="${index}" data-section="${section}"><i class="material-icons">headset</i> Voir les titres et écouter directement sur kagescan</a>`;
			else if (albumData.trackList[0][0] != "?")
				string += `<a class="collection-item modal-trigger" href="#popup" data-i="${index}" data-section="${section}"><i class="material-icons">headset</i> Voir les titres</a>`;
			if (albumData.spotifyId.length>0)
				string += `<a class="collection-item" href="https://open.spotify.com/album/${albumData.spotifyId}"><i class="material-icons">album</i> Lien direct spotify</a>`;
			if (albumData.appleLink.length>0)
				string += `<a class="collection-item" href="${albumData.appleLink}"><i class="material-icons">phone_iphone</i> Acheter sur Apple Music</a>`;
			string += '</ul>';
			setTimeout(function(){
				infoContainer.innerHTML = string;
			    infoContainer.classList.remove('pre-animation')
			},500);
		}
		var loadModalContent = function(e, openedBy){
			let index = openedBy.dataset.i, section = openedBy.dataset.section;
			let albumData = data[section][index];
			let content = document.getElementById("popupContent");
			let string = `<h3>${albumData.name}</h3>
				<h4 id="popupMusic">Liste des musiques</h4>
				    <p>durée totale de l'album : ${albumData.totTime}</p>
					<table>
						<thead><tr>	<th scope="col">#</th>	<th scope="col">Title de la musique</th>	<th scope="col">Chanté par</th>	<th scope="col">Durée</th>	</tr></thead>
						<tbody>
				`;
				for (let e in albumData.trackList) {
					let track = albumData.trackList[e];
					string += `<tr><td>${track[0]}</td>	<td>${track[1]}</td>	<td>${track[2]}</td>	<td>${track[3]}</td>	</tr>`;
				}
				string += `
						</tbody>
					</table>
				`
			if (albumData.spotifyId.length > 0)
				string += ` <h4 id="popupSpotify">écouter depuis Spotify</h4>
				    <div class="center">
				        <iframe style="margin: auto;" src="https://open.spotify.com/embed/album/${albumData.spotifyId}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
				    </div>
				`
			if (albumData.youtubeId.length > 0)
				string += ` <h4 id="popupYoutube">écouter depuis Youtube</h4>
					<p>${albumData.youtubeComment}</p>
		    			<div class="video-container">
		    			    <iframe width="1280" height="720" src="https://www.youtube.com/embed/videoseries?list=${albumData.youtubeId}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		    			</div>
					<p></p>
				`;
			content.innerHTML = string;
		}
		//MAIN
		var generatePage = function(db){
			data = db.albums;
			for (let section in data){
				let container = document.querySelector(`#${section} .carousel`);
				let sectionData = data[section];
				for (let album in sectionData) {
					container.insertAdjacentHTML('beforeEnd', `<img class="carousel-item" data-i="${album}" data-section="${section}" src="${sectionData[album].albumArt}" alt="${sectionData[album].name} cover art"/>`);
				}
			}
    		let carousel = M.Carousel.init(
				document.querySelectorAll('.carousel'),
				{onCycleTo: carouselChange}
			);
			var modal = M.Modal.init(
				document.querySelectorAll('.modal'),
				{onOpenStart: loadModalContent}
			);
		}
		document.addEventListener('DOMContentLoaded', function() {
			//nav template configuration
			document.getElementById("scrollSpyLinks").innerHTML = `
				<li><a href="#menu">Menu</a></li>
				<li><a href="#story">Albums "Histoire"</a></li>
				<li><a href="#themes">Albums "Thèmes"</a></li>
				<li><a href="#additional">Albums additionnels</a></li>`;
			document.getElementById("navLinkMusic").style.fontWeight = "bold";
			document.getElementById("navLinkMusic").classList.add("red-text","text-darken-4");
			//materialize plugins
			let sidenav = M.Sidenav.init( document.querySelectorAll('.sidenav') );
			let scrollspy = M.ScrollSpy.init( document.querySelectorAll('.scrollspy'), {scrollOffset: 0} );
			loadJSON("db.json", generatePage, loadErrorCallback);
		});
	</script>

  </body>
</html>
