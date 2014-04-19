function invokeItemLookUp(){	var itemLookup = null;	try	{		itemLookup = window.open('itemLookUp.html','','directories=0,height=500,width=450,location=0,menubar=0,resizable=0,status=0,toolbar=0');	}	catch(err)	{		alert(err.description);		return;	}	itemLookup.focus();}function doSearchSubmit(){		var searchTerm = document.getElementById("itemDescription").value;	if(searchTerm.length < 3)	{		alert("Search term must be at least three characters.");		document.getElementById("itemDescription").focus();		return;	}	showSearchLoading();	var itemName = document.getElementById('itemDescription').value.trim();	itemName = itemName.replace(/\s+/g,"+");	var url = "search.php?q=" + escape(itemName);	doHttpCall(url,"searchResultHandler",true,true);	}function searchResultHandler(response){		document.getElementById("searchResults").display='none';		var table = document.getElementById("searchResults");	for(var i = 0; i < table.rows.length; i++)	{		table.deleteRow(i);	}	var items = response.getElementsByTagName("item");		if(items.length == 0)	{		alert("No items found.");		document.getElementById("itemDescription").focus();		hideSearchLoading();		return;	}		for(i = 0; i < items.length; i++)	{		var r = table.insertRow(table.rows.length);		r.insertCell(0);		r.insertCell(1);		r.cells[0].innerHTML="<a href=\"http://www.wowhead.com/?item=" + items[i].getAttribute("id") + "\">" + items[i].getAttribute("name") + "</a>";		r.cells[1].innerHTML="<a href=\"javascript:pickItem(" + items[i].getAttribute("id") + ",'" + items[i].getAttribute("name").replace(/'/g,"\\'") + "');\">" + items[i].getAttribute("id") + "</a>";		r.cells[0].className="clickable";		r.cells[1].className="clickable";	}		document.getElementById("searchResults").display='';	hideSearchLoading();	}function pickItem(itemID,itemName){	self.opener.document.getElementById("itemid").value=itemID;	self.opener.document.getElementById("itemname").value=itemName;	self.opener.document.getElementById("date").focus();	self.close();}function onItemIDBlur(){	var txtItemID = document.getElementById("itemid");	var digitExpression = /^\d{5}$/	if(digitExpression.test(txtItemID.value))	{		showItemNameLoading();		fillItemName(txtItemID.value);	}}function fillItemName(itemID){	var url = "./ajax.php?a=getItemName&id=" + itemID;	doHttpCall(url,"fillItemNameHandler",true,false);}function fillItemNameHandler(response){	var parser = new DOMParser();	var xml = parser.parseFromString(response, "text/xml");	if(!xml){ alert("Couldn't resolve item name"); return; }	var itemName = document.getElementById("itemname");	if(!xml.getElementsByTagName("item")[0])	{		alert("Item does not exist.");		document.getElementById("itemid").focus();		document.getElementById("itemid").select();		hideItemNameLoading();		return;	}	itemname.value = xml.getElementsByTagName("item")[0].getAttribute("name");	hideItemNameLoading();}function showItemNameLoading(){	document.getElementById("imgItemNameLoading").style.display='';}function hideItemNameLoading(){	document.getElementById("imgItemNameLoading").style.display='none';}function showSearchLoading(){	document.getElementById("btnSearch").disabled=true;	document.getElementById("imgSearchLoading").style.display='';}function hideSearchLoading(){	document.getElementById("btnSearch").disabled=false;	document.getElementById("imgSearchLoading").style.display='none';}function onKeyPress(key){    var keynum = null;    if(window.event)    {            keynum = window.event.keyCode;    }else{            if(!key){return;}            keynum = key.which;    }    if(keynum == 13)    {        doSearchSubmit();    }}