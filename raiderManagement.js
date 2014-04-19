function doAddRaiderSubmit()
{
	
	if(document.getElementById("txtCharacterName").value.trim() == "")
	{
		alert("Error: Please provide a character name.");
		return;
	}
	
	var url = "./ajax.php?a=addRaider&n=";
	url += document.getElementById("txtCharacterName").value + "&c=";
	url += document.getElementById("selClass").options[document.getElementById("selClass").selectedIndex].value;
	doHttpCall(url,"handleAddRaiderResponse",true,false);	
}
function handleAddRaiderResponse(responseText)
{
		if(responseText == "AJAX_ERROR_NONEXISTANT_CHAR")
		{
			alert("Error: That character doesn't exist.");
			return;
		}
		if(responseText == "AJAX_ERROR_PLAYER_EXISTS")
		{
			alert("Error: That character is already in the database.");
			return;
		}
		if(responseText == "AJAX_ERROR_DB")
		{
			alert("Error: There was a database error.");
			return;
		}
		if(responseText == "ERROR")
		{
			alert("Generic Error.");
		}
		if(responseText == "0")
		{
			//alert("Success!");
			window.open("./lootMaster.php?s=0","_self",true);
			return;
		}
		//alert("Unknown Error! " + responseText);
                window.open("./lootMaster.php?s=1","_self",true);
}
function deleteRaider(CharacterName)
{
	if(!confirm("Delete " + CharacterName + "?")){return;}
	var url = "./ajax.php?a=deleteRaider&c=" + CharacterName;
	doHttpCall(url,"handleDeleteRaiderResponse",true,false);
}
function handleDeleteRaiderResponse(responseText)
{
	if(responseText == "0")
	{
		window.open("./raidMembers.php","_self",false);
		return;
	}
	alert("Generic Error! " + responseText);
}