var xhr = null;

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

function getDownloadStatus() {
    if (xhr)
	return;

    xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function(event) {
	if (this.readyState !== XMLHttpRequest.DONE) {
	    printReporter("Téléchargement en cours...");
	}
	else if (this.readyState === XMLHttpRequest.DONE) {
	    console.log(this.status, this.response);
	    if (this.status == 200) {
		printReporter("Téléchargement terminé! Vous allez être redirigé.");
		setTimeout(function() {
		    var stkUrl = document.URL;
		    var newUrl = stkUrl.substr(0, stkUrl.search("edbox_upload_download")) + "edit.php";
		    
		    document.location.href = newUrl;
		}, 2000);
	    }
	    else
		printReporter("Le téléchargement à échoué: " + this.response, "notice-error");
	    xhr = null;   
	}
    }

    xhr.open('POST', 'edbox_actions.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(window.location.search.substr(1));
}

function getUploadStatus(path) {
    if (xhr)
	return;

    xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function(event) {
	if (this.readyState !== XMLHttpRequest.DONE) {
	    printReporter("Synchronisation en cours...");
	}
	else if (this.readyState === XMLHttpRequest.DONE) {
	    console.log(this.status, this.response);
	    if (this.status == 200) {
		printReporter("Synchronisation terminé! Vous allez être redirigé.");
		var stkUrl = document.URL;
		var stkArgs = window.location.search;
		var newUrl = stkUrl.substr(0, stkUrl.search("edbox_upload_download")) + "post.php";
		var newArgs = "?post=" + stkArgs.substr(stkArgs.search("blog=")).substr(5) + "&action=edit";
		var response = JSON.parse(this.response);
		console.log(response);
		for (var uid in response) {
		    console.log(uid);
		    for (var blogs in response[uid]) {
			db.ref("schools/" + uid + "/" + blogs).set(response[uid][blogs]);
			console.log(response[uid][blogs]);
		    }
		}
		document.location.href = newUrl + newArgs;
	    }
	    else
		printReporter("La synchronisation a échoué: " + this.response, "notice-error");
	    xhr = null;   
	}	    
    }
    xhr.open('POST', 'edbox_actions.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(window.location.search.substr(1));
}

function getRemoveStatus() {
    if (xhr)
	return;

    xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function(event) {
	if (this.readyState !== XMLHttpRequest.DONE) {
	    printReporter("Effacement en cours...");
	}
	else if (this.readyState === XMLHttpRequest.DONE) {
	    console.log(this.status, this.response);
	    if (this.status == 200) {
		printReporter("Effacement terminé! Vous allez être redirigé.");
		var stkUrl = document.URL;
		var newUrl = stkUrl.substr(0, stkUrl.search("edbox_upload_download")) + "edbox_my_blogs.php";
		var paths = this.response.split(",");
		for (var ndx in paths) {
		    if (!paths[ndx].length)
			continue;
		    var path = paths[ndx].substr(0, paths[ndx].length - 7);
		    db.ref("schools/" + path).remove();
		}
		document.location.href = newUrl;
	    }
	    else
		printReporter("La synchronisation a échoué: " + this.response, "notice-error");
	    xhr = null;   
	}
    }

    xhr.open('POST', 'edbox_actions.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(window.location.search.substr(1));
}
