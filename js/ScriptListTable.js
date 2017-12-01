function getList(id) {
    return (document.getElementById(id));
}

function removeNoItemsMessage(list, className) {
    var oldElems = list.getElementsByClassName(className);
    if (oldElems[0])
	oldElems[0].remove();
}

function createCheckBox(path, isCheck = false) {
    var th_elem = document.createElement("th");
    th_elem["scope"] = "row";
    th_elem["className"] = "check-column";
    
    var input_elem = document.createElement("input");
    input_elem["type"] = "checkbox";
    input_elem["name"] = "blog[]";
    input_elem["value"] = path;
    input_elem.checked = isCheck;
    
    th_elem.appendChild(input_elem);
    return (th_elem);
}

function createTitleTdElem(blogName, href, actionTitle) {
    var td_elem = document.createElement("td");
    
    td_elem["className"] = "blogTitle column-blogTitle has-row-actions column-primary";
    td_elem["data-colname"] = "Titre";
    td_elem.innerHTML = blogName;
    
    var div_elem = document.createElement("div");
    div_elem["className"] = "row-actions";
    
    var span_elem = document.createElement("span");
    span_elem["className"] = "trash";
    
    var a_elem = document.createElement("a");
    a_elem["href"] = href;
    a_elem.innerHTML = actionTitle;
    
    span_elem.appendChild(a_elem);
    div_elem.appendChild(span_elem);
    td_elem.appendChild(div_elem);
    
    var button_elem = [];
    button_elem.push(document.createElement("button"));
    button_elem[0]["type"] = "button";
    button_elem[0]["className"] = "toggle-row";
    
    var span_elem2 = document.createElement("span");
    span_elem2["className"] = "screen-reader-text";
    span_elem2.innerHTML = "Afficher plus de détails";
    
    button_elem[0].appendChild(span_elem2);
    td_elem.appendChild(button_elem[0]);
    button_elem.push(button_elem[0].cloneNode(true));
    td_elem.appendChild(button_elem[1]);
    return (td_elem);
}

function createColumn(columnId, columnName, columnText) {
    var td_elem = document.createElement("td");
    
    td_elem["className"] = columnId + " column-" + columnId;
    td_elem["data-colname"] = columnName;
    td_elem.innerHTML = columnText;
    return (td_elem);
}

function createTrElem() {
    return (document.createElement("tr"));
}

function addPersonnalBlogsRow(path, blogName, uploadDate, size) {
    var list = getList("the-list");
    var new_tr = createTrElem();

    removeNoItemsMessage(list, "no-items");
    new_tr.appendChild(createCheckBox(path));
    new_tr.appendChild(
	createTitleTdElem(
	    blogName,
	    "edbox_upload_download.php?actions=remove&blog=" + path + "/" + blogName + ".tar.gz",
	    "Retirer du partage"
	)
    );
    new_tr.appendChild(createColumn("uploadDate", "Mise en ligne", uploadDate));
    new_tr.appendChild(createColumn("size", "Taille", size));

    list.appendChild(new_tr);
}

function addTwinningsRow(path, blogName, uploadDate, schoolName, size) {
    var list = getList("the-list");
    var new_tr = createTrElem();

    removeNoItemsMessage(list, "no-items");
    new_tr.appendChild(createCheckBox(path));
    new_tr.appendChild(
	createTitleTdElem(
	    blogName,
	    "edbox_upload_download.php?actions=download&blog=" + path + "/" + blogName + ".tar.gz",
	    "Télécharger"
	)
    );
    new_tr.appendChild(createColumn("uploadDate", "Mise en ligne", uploadDate));
    new_tr.appendChild(createColumn("schoolName", "Provenance", schoolName));
    new_tr.appendChild(createColumn("size", "Taille", size));

    list.appendChild(new_tr);
}

function addSchoolNameRow(name, isTwin, schoolUid) {
    var list = getList("the-list");
    var new_tr = createTrElem();

    removeNoItemsMessage(list, "no-items");
    var cb = createCheckBox(schoolUid, isTwin).firstChild;
    cb.onclick = function() {
	if (cb.checked) {
	    twinVal.push(schoolUid);
	}
	else {
	    twinVal.splice(twinVal.indexOf(schoolUid), 1);
	}
	console.log(twinVal);
    };
    new_tr.appendChild(cb);
    new_tr.appendChild(
	createTitleTdElem(
	    name,
	    "",
	    ""
	)
    );
    list.appendChild(new_tr);
}
