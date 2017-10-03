/** GLOBALS **/
var xhr = null;

/** CONTANTS **/
const POWEROFF_STATES_MESSAGES = {
    0 : "Préparation de la requète.",
    1 : "Envoie de la requète",
    2 : "Demande d'arrèt de la box en cours.",
    3 : "Arrèt en cours.",
    4 :
	[
	    "La box est éteinte.",
	    "Une erreur est survenue pendant l'arret de la box."
	]
};
const REBOOT_STATES_MESSAGES = {
    0 : "Préparation de la requète.",
    1 : "Envoie de la requète",
    2 : "Demande de redémarrage de la box en cours.",
    3 : "Arrèt en cours.",
    4 :
	[
	    "La box redémarre.",
	    "Une erreur est survenue durant le redémarrage de la box."
	]
};
const CONNECT_STATES_MESSAGES = {
    0 : "Préparation de la requète.",
    1 : "Connexion en cours...",
    2 : "Connexion en cours..",
    3 : "Connexion en cours.",
    4 :
	[
	    "Connexion réussi.",
	    "Une erreur est survenue pendant la connexion."
	]
};
const DISCONNECT_STATES_MESSAGES = {
    0 : "Préparation de la requète.",
    1 : "Déconnexion en cours...",
    2 : "Déconnexion en cours..",
    3 : "Déconnexion en cours.",
    4 :
	[
	    "Déconnexion réussi.",
	    "Une erreur est survenue pendant la déconnexion."
	]
};

/** POST FUNCTIOM **/
function printReporter(status, noticeStatus = "notice-success") {
    var divElem = document.getElementById("actionReporter");
    var paragraphElem = document.createElement("P");
    var paragraphText = document.createTextNode(status);

    if (!divElem)
	return;
    divElem.className = "notice " + noticeStatus;
    while (divElem.firstChild) {
	divElem.removeChild(divElem.firstChild);
    }
    divElem.appendChild(paragraphElem);
    paragraphElem.appendChild(paragraphText);
}

function postRequest(actions) {
    xhr.open('POST', 'edbox_actions.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(actions);
}

function getStateChangeCallbackFunction(statesMessages) {
    return (function(event) {
	if (this.readyState === XMLHttpRequest.UNSENT &&
	    statesMessages[XMLHttpRequest.UNSENT]) {
	    printReporter(statesMessages[XMLHttpRequest.UNSENT]);
	}
	else if (this.readyState === XMLHttpRequest.OPENED &&
		 statesMessages[XMLHttpRequest.OPENED]) {
	    printReporter(statesMessages[XMLHttpRequest.OPENED]);
	}
	else if (this.readyState === XMLHttpRequest.HEADERS_RECEIVED &&
		 statesMessages[XMLHttpRequest.HEADERS_RECEIVED]) {
	    printReporter(statesMessages[XMLHttpRequest.HEADERS_RECEIVED]);
	}
	else if (this.readyState === XMLHttpRequest.LOADING &&
		 statesMessages[XMLHttpRequest.LOADING]) {
	    printReporter(statesMessages[XMLHttpRequest.LOADING]);
	}
	else if (this.readyState === XMLHttpRequest.DONE &&
		 statesMessages[XMLHttpRequest.DONE]) {
	    console.log(this.status, ": ", this.response);
	    if (this.status === 200 || this.status === 0) {
		printReporter(statesMessages[XMLHttpRequest.DONE][0]);
		location.reload();
	    }
	    else
		printReporter(statesMessages[XMLHttpRequest.DONE][1], "notice-error");
	    xhr = null;
	}
    });
}

function doPost(statesMessages, actions) {
    if (xhr.readyState == XMLHttpRequest.UNSENT) {
	xhr.onreadystatechange = getStateChangeCallbackFunction(statesMessages);
	postRequest(actions);
    }
}

function printXhrError() {
    console.error("xhr already running.");
}

function verifyRequiredVar() {
    if (xhr) {
	printXhrError();
	return false;
    }
    xhr = getXMLHttpRequest();
    return true;
}

/** BUTTON CALLED FUNCTIONS **/
function postPoweroff() {
    var actions = "";
    
    if (!verifyRequiredVar())
	return;
    actions = "actions=poweroff";
    doPost(POWEROFF_STATES_MESSAGES, actions);
}

function postReboot() {
    var actions = "";
    
    if (!verifyRequiredVar())
	return;
    actions = "actions=reboot";
    doPost(REBOOT_STATES_MESSAGES, actions);
}

function postConnect() {
    var wifiEssid;
    var wifiPassword;
    var actions = "";
    
    if (!verifyRequiredVar()						||
	!(wifiEssid = document.getElementById("selected_essid"))	||
	wifiEssid.selectedIndex === -1					||
	!(wifiPassword = document.getElementById("wifi_password"))
       )
	return;
    wifiEssid = encodeURIComponent(wifiEssid.options[wifiEssid.selectedIndex].text);
    wifiPassword = encodeURIComponent(wifiPassword.value);
    actions = "actions=connect&essid=" + wifiEssid + "&password=" + wifiPassword;
    doPost(CONNECT_STATES_MESSAGES, actions);
}

function postDisconnect() {
    var actions = "";
    
    if (!verifyRequiredVar())
	return;
    actions = "actions=disconnect";
    doPost(DISCONNECT_STATES_MESSAGES, actions);
}
