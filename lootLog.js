function deleteLootWin(lootWinID)
{
	var url = "./ajax.php?a=deleteLootWin&lootWinID=" + lootWinID;
	doHttpCall(url,"handleDeleteLootWinResponse",true,false);
}

function handleDeleteLootWinResponse(response)
{
	
	if(response == "ERROR")
	{
		alert("Couldn't delete the win. Please contact Nekrimog.");
		return;
	}
	
	var table = document.getElementById("lootLogTable");
	for(var i = 0; i < table.rows.length; i++)
	{
		if(table.rows[i].id == "LootWin" + response)
		{
			table.deleteRow(i);
			return;
		}
	}
}