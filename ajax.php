<?php

require("./functions.php");

$h = new HttpQueryString();
$a = $h->get("a");

if(trim($a) == "")
{
	echo "ERROR";
}
else
{
	switch($a)
	{
		case "deleteLootWin":
			deleteLootWin($h);
			break;
		case "getItemName":
			getItemName($h);
			break;
		case "addRaider":
			addRaider($h);
			break;
		case "deleteRaider":
			deleteRaider($h);
			break;
		default:
			echo "ERROR";
	}
}

function deleteLootWin($h)
{

    if(!isLoggedIn()){ die; }

    $winID = $h->get("lootWinID");
    if(trim($winID) == "")
    {
            echo "ERROR";
    }
    $q = "DELETE FROM LOOTWIN ";
    $q .= "WHERE LootWinID=" . $winID;
    $c = getConnection();
    $r = mysql_query($q,$c);

    if(!$r){ echo "ERROR"; }else{ echo $winID; }

    mysql_close($c);
}
function getItemName($h)
{
	if(!$h->get("id")){die("ERROR");}
	$id=$h->get("id");
	
	$url = "http://www.wowarmory.com/item-info.xml?i=$id";
	$agent = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3";
	
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt ($ch, CURLOPT_USERAGENT,  $agent); // If we don't do this, we'll get all of the HTML, etc.
	  
	$item = curl_exec($ch);
	curl_close($ch);
	
	echo $item;
}
function addRaider($h)
{
    if(!isLoggedIn()){ die; }
    if(!$h->get("n") || !$h->get("c")){die("ERROR");}
    if(playerInDatabase($h->get("n"))){ die("AJAX_ERROR_PLAYER_EXISTS"); }
    $c = getConnection();
    $q = "INSERT INTO PLAYER VALUES ('" . $h->get("n") . "',0," . $h->get("c") . ");";
    $r = mysql_query($q,$c);
    if(!$r){ echo "AJAX_ERROR_DB"; echo mysql_error();} else { echo "0"; }
}
function deleteRaider($h)
{
    $c = getConnection();
    if(!isLoggedIn()){ die; }
    if(!$h->get("c")){ die("ERROR"); }
    $q = "DELETE FROM LOOTWIN WHERE CharacterName='" . $h->get("c") . "';";
    $r = mysql_query($q,$c);
    if(!$r){ die("ERROR"); }
    $q = "DELETE FROM PLAYER WHERE CharacterName='" . $h->get("c") . "';";
    $r = mysql_query($q,$c);
    if(!$r){ die("ERROR"); } 
    die("0");
}
?>