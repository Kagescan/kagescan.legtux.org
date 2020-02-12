// helpers
function exchangeActiveClass(query, target) {
	const bef = document.querySelector(`${query}.active`);
	if (bef) {
		bef.classList.remove("active");
	}
	target.classList.add("active");
}
function cleanButIgnore( parent, toIgnore ) {
	while (parent.childNodes.length > 1) {
		if (parent.lastChild != toIgnore) {
			parent.removeChild(parent.lastChild);
		} else if (parent.firstChild != toIgnore) {
			parent.removeChild(parent.firstChild);
		}
	}
}

function getActivePositionFromHisParent(parent) {
	for (let i = 0; i < parent.childNodes.length; i++) {
		if (parent.childNodes[i].classList.contains("active")) {
			return i;
		}
	}
}

function prettyPrintDb(obj) {
	let retval = "";
	for (const vol of obj.volumes) {
		retval += `(${vol.id}) ${vol.name} : \n\t- Preview Image : ${vol.coverArt}\n\t- Summary : >>${vol.summary.replace(/\n/g, ">>")}\n`;
		for (const chap  of vol.chapters) {
			retval += `\t(${chap.id}) ${chap.name} : \n\t\t- Preview Image : ${chap.previewImg}\n\t\t- Summary : >>${chap.summary.replace(/\n/g, ">>")}\n`;
		}
		retval += `\n\n`;
	}
	// testing only
	app.databaseRaw.value = retval;
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
		app.listControlBtns = document.getElementById("listControlBtns");

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
	applyDefaultDisplay: function(doSaveLocalValues = true) {
		if (app.elem.classList.contains("hide")) {
			app.elem.classList.remove("hide");
			app.sendToServerPopup.classList.add("hide");
			app.saveBtn.classList.remove("hide");
		}
		if (doSaveLocalValues) {
			app.saveLocalValuesFromEditor();
		}
		app.generateVolumeChoice();
		app.displayDefaultEditorMsg();

		app.listControlBtns.style.display = "none";
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
	removeActive: function() {
		const activeIndex = getActivePositionFromHisParent(app.list);
		if (typeof(activeIndex) !== "number") {
			throw "Cannot find the index of the active element in the button list";
		}
		let targetElem = app.list.childNodes[activeIndex];
		let isVolume = typeof(targetElem.dataset.volumeIndex) == "string";
		if (isVolume || app.list.childNodes.length < 4) {
			if (! confirm("You are about to remove an entire volume. Are you sure ??")) {
				return "cancelled";
			}
		}

		if (targetElem.dataset.chapterIndex) {
			const volumeIndex = app.list.firstChild.dataset.volumeIndex;
			const chapterIndex = targetElem.dataset.chapterIndex;
			if (! db.volumes[volumeIndex]) {
				throw `Error : db.volumes[${volumeIndex}] is undefined`;
			}
			const chapterList = db.volumes[volumeIndex].chapters;
			chapterList.splice(chapterIndex, 1);
			let toUnderline = document.querySelector(`#list a[href="#${chapterList.length - 1}"][data-chapter-index]`);

			if (toUnderline) {
				app.generateChapterEditor(toUnderline);
				app.generateChapterChoice(toUnderline);
			} else {
				isVolume = true;
				targetElem = app.list.firstChild;
			}
		}
		if (isVolume) {
			const volumeIndex = targetElem.dataset.volumeIndex;
			db.volumes.splice(volumeIndex, 1);
			app.applyDefaultDisplay(false);
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
				if (i == chapterList.length) {
					btn.innerText = "Create Chapter"
				} else {
					btn.dataset.chapterIndex = i;
					btn.innerText = chapterList[i].name;
				}
				btn.href = `#${i}`;
				btn.onclick = app.chapterBtnOnClick;
			} );
		}
		if ( parentVolumeBtn.innerText == "Create Chapter" || !document.querySelector("#list a.active")) {
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
		app.listControlBtns.style.display = "block";
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

	displayDatabaseDiff: function(){
		// TBA
	}
}
document.addEventListener('DOMContentLoaded', function() {
	app.start();
});
