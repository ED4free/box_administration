function addRow(path, blogName, uploadDate, size) {
    var list = document.getElementById("the-list");
    var new_tr = document.createElement("tr");
    
    var th_elem = document.createElement("th");
    th_elem["scope"] = "row";
    th_elem["className"] = "check-column";
    
    var input_elem = document.createElement("input");
    input_elem["type"] = "checkbox";
    input_elem["name"] = "blog[]";
    input_elem["value"] = path;
    
    th_elem.appendChild(input_elem);
    new_tr.appendChild(th_elem);
    
    var td_elem = [];
    
    td_elem.push(document.createElement("td"));
    td_elem[0]["className"] = "blogTitle column-blogTitle has-row-actions column-primary";
    td_elem[0]["data-colname"] = "Titre";
    td_elem[0].innerHTML = blogName;
    
    var div_elem = document.createElement("div");
    div_elem["className"] = "row-actions";
    
    var span_elem = document.createElement("span");
    span_elem["className"] = "trash";
    
    var a_elem = document.createElement("a");
    a_elem["href"] = "edbox_upload_download.php?actions=remove&amp;blog=" + path;
    a_elem.innerHTML = "Retirer du partage";
    
    span_elem.appendChild(a_elem);
    div_elem.appendChild(span_elem);
    td_elem[0].appendChild(div_elem);
    
    var button_elem = [];
    button_elem.push(document.createElement("button"));
    button_elem[0]["type"] = "button";
    button_elem[0]["className"] = "toggle-row";
    
    var span_elem2 = document.createElement("span");
    span_elem2["className"] = "screen-reader-text";
    span_elem2.innerHTML = "Afficher plus de détails";
    
    button_elem[0].appendChild(span_elem2);
    td_elem[0].appendChild(button_elem[0]);
    new_tr.appendChild(td_elem[0]);
    
    button_elem.push(button_elem[0].cloneNode(true));
    td_elem[0].appendChild(button_elem[1]);
    
    td_elem.push(document.createElement("td"));
    td_elem[1]["className"] = "uploadDate column-uploadDate";
    td_elem[1]["data-colname"] = "Mise em ligne";
    td_elem[1].innerHTML = uploadDate;
    
    new_tr.appendChild(td_elem[1]);
    
    td_elem.push(document.createElement("td"));
    td_elem[2]["className"] = "size column-size";
    td_elem[2]["data-colname"] = "Taille";
    td_elem[2].innerHTML = size;
    
    new_tr.appendChild(td_elem[2]);
    list.appendChild(new_tr);
}
