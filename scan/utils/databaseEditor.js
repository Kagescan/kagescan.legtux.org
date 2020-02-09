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

function createDiffBetween2Objects(old, now, retval) {
	// TODO: Support delete type
	if (old != now) {
		for (const i in now) {
			if (old[i]) {
				if (old[i] != now[i]){
					if (typeof(old[i]) == "object") {
						const eval = createDiffBetween2Objects(old[i], now[i], []);
						retval.push( {type: "node", index: i, content: eval});
					} else {
						retval.push({ type: "edit", index: i, content: [old[i], now[i] ] });
					}
				}
			} else {
				retval.push( {type: "new", index: i, content: now[i]} );
			}
		}
	}
	return retval;
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
		app.databaseDiff = document.getElementById("databaseDiff");
		app.databaseRaw = document.getElementById("databaseRaw");

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
			app.generateChapterChoice(this);
			app.generateVolumeEditor(this);
		}
	},
	chapterBtnOnClick: function() {
		app.saveLocalValuesFromEditor();
		if (! this.classList.contains("active")){
			exchangeActiveClass("#list a", this);
			app.generateChapterEditor(this);
		}
	},

	// Choice section
	generateVolumeChoice: function() {
		app.list.innerHTML = "";

		for (const i in db.volumes) {
			app.addChoiceBtn( (btn) => {
				btn.innerText = db.volumes[i].name;
				btn.href = `#${i}`;
				btn.onclick = app.volumeBtnOnClick;
				btn.dataset.volumeIndex = i;
			} );
		}
	},
	generateChapterChoice: function( parentVolumeBtn ) {
		cleanButIgnore( app.list, parentVolumeBtn );

		const volumeIndex = parentVolumeBtn.dataset.volumeIndex;
		const chapterList = db.volumes[ volumeIndex ].chapters;
		for (const i in chapterList) {
			app.addChoiceBtn( (btn) => {
				btn.innerText = chapterList[i].name;
				btn.href = `#${i}`;
				btn.onclick = app.chapterBtnOnClick;
				btn.dataset.chapterIndex = i;
			} );
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

		const volumeInfo = db.volumes[ parentVolumeBtn.dataset.volumeIndex ];
		if (volumeInfo) {
			for (const key in volumeInfo) {
				const input = document.querySelector(`#volumeEdit [name="${key}"]`);
				if (input) {
					input.value = volumeInfo[key];
				}
			}
		}

	},
	generateChapterEditor: function( parentChapterBtn) {
		exchangeActiveClass("#editContainer>div", app.chapterEdit);

		const volumeIndex = parentChapterBtn.parentNode.firstChild.dataset.volumeIndex;
		const chapterIndex = parentChapterBtn.dataset.chapterIndex;
		const chapterInfo = (volumeIndex)? db.volumes[volumeIndex].chapters[chapterIndex] :null;
		if (chapterInfo){
			for (const key in chapterInfo) {
				const input = document.querySelector(`#chapterEdit [name="${key}"]`);
				if (input) {
					input.value = chapterInfo[key];
				}
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
		const localDbString = JSON.stringify(db);
		app.databaseRaw.value = localDbString;

		if (app.sendToServerPopup.classList.contains("hide")) {
			app.sendToServerPopup.classList.remove("hide");
			app.elem.classList.add("hide");
		}
	},
	displayDatabaseDiff: function(){
	},
	copyDatabaseRawTexatera : function() {
		app.databaseRaw.select();
		document.execCommand("copy");
	}
}
document.addEventListener('DOMContentLoaded', function() {
	app.start();
});
