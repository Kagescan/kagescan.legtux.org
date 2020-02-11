// helpers
function exchangeActiveClass(query, target){
	const bef = document.querySelector(`${query}.active`);
	if (bef) {
		bef.classList.remove("active");
	}
	target.classList.add("active");
}
function cleanButIgnore( parent, toIgnore ){
	while (parent.childNodes.length > 1) {
		if (parent.lastChild != toIgnore) {
			parent.removeChild(parent.lastChild);
		} else if (parent.firstChild != toIgnore) {
			parent.removeChild(parent.firstChild);
		}
	}
}

// main
var app = {

	// init
	start: function() {
		app.elem = document.getElementById("app");
		app.title = document.getElementById("title");
		app.list = document.getElementById("list");
		app.chapterEdit = document.getElementById("chapterEdit");
		app.volumeEdit = document.getElementById("volumeEdit");
		app.defaultEditorMsg = document.getElementById("defaultEditorMsg");
		app.sendToServerPopup = document.getElementById("sendToServerPopup");
		app.databaseDiffContainer = document.getElementById("databaseDiffContainer");
		app.databaseRaw = document.getElementById("databaseRaw");
		app.saveBtn = document.getElementById("saveBtn");

		app.initElements();
		app.applyDefaultDisplay();
	},

	initElements: function(){
		const title = document.createElement("h3");
		title.innerText = db.mangaName;
		app.defaultEditorMsg.prepend(title);

		// freaking dirty but might be the best solution...
		app.databaseBeforeEdit = JSON.parse( JSON.stringify(db) );
	},

	// general
	applyDefaultDisplay: function(isInit) {
		if (app.elem.classList.contains("hide")) {
			app.elem.classList.remove("hide");
			app.sendToServerPopup.classList.add("hide");
			app.saveBtn.classList.remove("hide");
		}

		app.saveLocalValuesFromEditor();
		app.generateVolumeChoice();
		app.displayDefaultEditorMsg();

		document.querySelector("button[name='goToMenu']").style.display = "none";
	},

	// Events
	volumeBtnOnClick: function() {
		app.saveLocalValuesFromEditor();
		if (! this.classList.contains("active")){
			this.classList.add("active");
			app.generateVolumeEditor(this);
			app.generateChapterChoice(this);
		}
	},
	chapterBtnOnClick: function() {
		app.saveLocalValuesFromEditor();
		if (! this.classList.contains("active")){
			exchangeActiveClass("#list a", this);
			app.generateChapterEditor(this);
			if (this.innerText == "Create Chapter") {
				app.generateChapterChoice(this);
			}
		}
	},

	// Choice section
	generateVolumeChoice: function() {
		app.list.innerHTML = "";

		for (var i = 0; i <= db.volumes.length; i++) {
			app.addChoiceBtn( (btn) => {
				btn.innerText = (i == db.volumes.length)? "Create Volume": db.volumes[i].name;
				btn.onclick = app.volumeBtnOnClick;
				btn.href = `#${i}`;
				btn.dataset.volumeIndex = i;
			} );
		}
	},
	generateChapterChoice: function( parentVolumeBtn ) {
		if (parentVolumeBtn.dataset.volumeIndex) {
			cleanButIgnore( app.list, parentVolumeBtn );
		} else {
			cleanButIgnore( app.list, app.list.firstChild);
		}

		const volumeIndex = app.list.firstChild.dataset.volumeIndex;
		const chapterList = db.volumes[ volumeIndex ].chapters;
		for (var i = 0; i <= chapterList.length; i++) {
			app.addChoiceBtn( (btn) => {
				btn.innerText = (i == chapterList.length)? "Create Chapter": chapterList[i].name;
				btn.onclick = app.chapterBtnOnClick;
				btn.href = `#${i}`;
				btn.dataset.chapterIndex = i;
			} );
		}
		if (! document.querySelector("#list a.active")) {
			document.querySelector(`#list a[href="#${chapterList.length - 1}"]`).classList.add("active");
		}
	},
	addChoiceBtn: function(callback) {
		const choice = document.createElement("a");
		choice.classList.add("col", "s12");
		if (typeof(callback) == "function") {
			callback(choice);
		}
		app.list.append(choice);
	},

	// Editor section
	displayDefaultEditorMsg: function() {
		exchangeActiveClass("#editContainer>div", app.defaultEditorMsg);
	},
	generateVolumeEditor: function( parentVolumeBtn ) {
		document.querySelector("button[name='goToMenu']").style.display = "block";
		exchangeActiveClass("#editContainer>div", app.volumeEdit);

		let volumeInfo = db.volumes[ parentVolumeBtn.dataset.volumeIndex ];
		if (typeof(volumeInfo) !== "object") {
			volumeInfo = {
				"name": "Untitled Volume",
				"id": `vol${db.volumes.length + 1}`,
				"coverArt": "# URL to the cover image #",
				"summary": "The summary for the new volume",
				"chapters": []
			};
			db.volumes.push(volumeInfo);
		}

		for (const key in volumeInfo) {
			const input = document.querySelector(`#volumeEdit [name="${key}"]`);
			if (input) {
				input.value = volumeInfo[key];
			}
		}
	},
	generateChapterEditor: function( parentChapterBtn ) {
		exchangeActiveClass("#editContainer>div", app.chapterEdit);

		const volumeIndex = parentChapterBtn.parentNode.firstChild.dataset.volumeIndex;
		if (typeof(db.volumes[volumeIndex]) !== "object") {
			throw `Cannot access to the volume [index = ${volumeIndex}] from the local database !`;
		}
		const chapterIndex = parentChapterBtn.dataset.chapterIndex;
		let chapterInfo = db.volumes[volumeIndex].chapters[chapterIndex];
		if (typeof(chapterInfo) !== "object") {
			chapterInfo = {
				"name": "Untitled Chapter",
				"id": "The folder name that points to the chapter's pages",
				"previewImg": "# URL to the preview image #",
				"summary": "The summary for the new chapter"
			};

			db.volumes[volumeIndex].chapters.push(chapterInfo);
		}

		for (const key in chapterInfo) {
			const input = document.querySelector(`#chapterEdit [name="${key}"]`);
			if (input) {
				input.value = chapterInfo[key];
			}
		}
	},

	// Save functions
	saveLocalValuesFromEditor: function() {
		const currentActiveEditor = document.querySelector("#editContainer>div.active");
		const activeListElement = document.querySelector("#list .active");
		if (activeListElement) {
			const activeDataset = activeListElement.dataset;
			let arrayToRead;

			switch (currentActiveEditor.id) {
			case "volumeEdit":
				arrayToRead = db.volumes[ activeDataset.volumeIndex ];
				break;
			case "chapterEdit":
				const volumeIndex = app.list.firstChild.dataset.volumeIndex;
				const chapterIndex = activeDataset.chapterIndex;
				arrayToRead = db.volumes[volumeIndex].chapters[chapterIndex];
				break;
			}

			if (arrayToRead) {
				for (const key in arrayToRead) {
					const input = document.querySelector(`#${currentActiveEditor.id} [name="${key}"]`);
					if (input) {
						arrayToRead[key] = input.value;
					}
				}
			}
		}
	},
	showPopupThatSendLocalValuesToServer : function() {
		app.saveLocalValuesFromEditor();
		app.databaseRaw.value = JSON.stringify(db);
		app.displayDatabaseDiff();

		if (app.sendToServerPopup.classList.contains("hide")) {
			app.sendToServerPopup.classList.remove("hide");
			app.elem.classList.add("hide");
			app.saveBtn.classList.add("hide");
		}
	},
	copyDatabaseRawTexatera : function() {
		app.databaseRaw.select();
		document.execCommand("copy");
	},

	// diff functions (might hurt your eyes)
	displayDatabaseDiff: function(){
		// reset
		app.databaseDiffContainer.innerHTML = "";
		const changes = app.createDiffBetween2Objects( app.databaseBeforeEdit.volumes, db.volumes);
		const   infos = document.createElement("div");
		changes.id = "databaseDiff";
		  infos.id = "showSelectedDiff";
		changes.classList.add("col", "m6");
		  infos.classList.add("col", "m6", "grey", "lighten-3");
		app.databaseDiffContainer.append( changes, infos );

		// stuff
		const allVolumeElems = document.querySelectorAll("#databaseDiff>li > ul");
		for (const volElem of allVolumeElems) {

			const volumeTitle = document.createElement("b");
			const volumeInfos = db.volumes[volElem.dataset.index];
			volumeTitle.innerText = volumeInfos.id;
			volElem.before(volumeTitle);

			const allChapElems = volElem.querySelectorAll(`[data-index="chapters"] ul`);
			if (allChapElems) {
				for (chapElem of allChapElems) {
					const chapTitle = document.createElement("b");
					chapTitle.innerText = volumeInfos.chapters[ chapElem.dataset.index ].name;
					chapElem.before(chapTitle);
				}
			}
		}
	},
	showLongChange: function(newValue, oldValue) {
		const target = document.getElementById("showSelectedDiff");
		target.innerHTML = "";
		if (oldValue) {
			target.innerHTML = `
				<h3>Original Value</h3>
				<em class="red-text">${oldValue.replace(/\n/g, "<br>")}</em>
			`;
		}
		target.innerHTML += `
			<h3>New Value</h3>
			<em class="green-text">${(newValue) ? newValue.replace(/\n/g, "<br>") : "[deleted]"}</em>
		`;
	},
	createDiffBetween2Objects: function(old, now) {
		const retval = document.createElement("ul");
		retval.classList.add("browser-default");
		// TODO: Support delete type

		for (const i in now) {
			const li = document.createElement("li");
			if (old[i]) {
				li.classList.add("orange-text");
				if (typeof(old[i]) == "object") {
					const eval = app.createDiffBetween2Objects(old[i], now[i]);
					if (eval.childNodes.length > 0){
						eval.dataset.index = i;
						li.append (eval);
					}
				} else if (old[i] != now[i]) {
					li.innerText = i;
					if (now[i].length + old[i].length > 50) {
						li.innerHTML += " <i class='material-icons'>info</i>";
						li.addEventListener("mouseover", function(){
							exchangeActiveClass("#databaseDiff ", li);
							app.showLongChange(now[i], old[i]);
						});
					} else {
						li.innerHTML += ` :
							<span class="red-text">  ${old[i]}</span> ->
							<span class="green-text">${now[i]}</span>`;
					}
				}
			} else if (now[i]) { // Old[i] empty and now[i] not empty -> value created
				li.classList.add("green-text");
				if (typeof(now[i]) == "object") {
					const eval = app.createDiffBetween2Objects({}, now[i]);
					if (eval.childNodes.length > 0){
						eval.dataset.index = i;
						li.append (eval);
					}
				} else {
					li.innerText = i;
					if (now[i].length > 50) {
						li.innerHTML += " <i class='material-icons'>info</i>";
						li.addEventListener("mouseover", function(){
							exchangeActiveClass("#databaseDiff ", li);
							app.showLongChange(now[i]);
						});
					} else {
						li.innerText += ` : ${now[i]}`;
					}
				}
			}
			if (li.childNodes.length > 0) {
				retval.append(li);
			}
		}
		return retval;
	}
}
document.addEventListener('DOMContentLoaded', function() {
	app.start();
});
