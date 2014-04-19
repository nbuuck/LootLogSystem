<?php 

require("./functions.php");

$q = new HttpQueryString();
$t = "PLAYER"; // Table Name
$d = "USER"; // MySQL DB Name
$guild = "Reckless Abandon";
$realm = "Muradin";

$a = $q->get("a");
if(!$a || $a == "sync")
{
	syncFromArmory();
}
if($a == "prune")
{
	pruneDatabase();
}

function syncFromArmory()
{
	global $t,$d;
	
	$guild = getGuildXMLElement();
	
	#echo "Importing raider information from guild '" . $guild->guildInfo->guildHeader['name'] . "'.<br />";
	#echo "Establishing connection to MySQL server...";
	
	$c = getConnection();
	
	if ($c)
	{
		#echo "SUCCESS!\n<br />";
	}else{
		#echo "FAILED!\n<br />";
            header("Location: ./lootMaster.php?s=1");
	}
	
	foreach ($guild->guildInfo->guild->members->children() as $member)
	{
		if(!raidMemberInDatabase($member['name'],$c) && $member['level'] == "80")
		{
			#echo "Importing guild member '" . $member['name'] . "'...";
			$query = "INSERT INTO " . $d . "." . $t . " VALUES";
			$query .= "('" . $member['name'] . "',0," . $member['classId'] . ");";
			$result = mysql_query($query, $c);
			if($result)
			{
				#echo "Done!<br />";
			}else{
				#echo "Failed!<br />";
				//echo mysql_error() . "<br />\n";
                            header("Location: ./lootMaster.php?s=1");
			}
		}
	}
	
	mysql_close($c);
	#echo "Finished import.<br />";
        header("Location: ./lootMaster.php?s=0");

}

function pruneDatabase()
{
	
	// Checks for raiders in the databse that are no longer in the guild and 
	// prunes them from the database unless they're flagged as a non-guild raider
	// in the database.
	
	global $d,$t;
	
	echo "Pruning the database. Characters that have left or are not in the guild and are not flagged as external raiders will be removed!<br />\n";
	
	$guild = getGuildXMLElement();
	$members = $guild->guildInfo->guild->members->children();
	$l = "(";
	for($i = 0; $i < sizeof($members); $i++)
	{
		$l .= "'" . $members[$i]['name'] . "'";
		if($i < (sizeof($members) - 1))
		{
			$l .= ",";
		}
	}
	$l .= ")";
	$q = "DELETE FROM " . $d . "." . $t;
	$q .= " WHERE CharacterName NOT IN " . $l;
	$q .= " AND IsExternalRaider=0;";
	$c = getConnection();
	$r = mysql_query($q,$c);
	if(!$r)
	{
		die("Failed to prune database of non-guild, non-external raiders.");
	}
	else
	{
		echo "Pruning completed.";
	}
	
}

function raidMemberInDatabase($memberName, $connection)
{
	global $d,$t;
	$query = "SELECT COUNT(*) FROM " . $d . "." . $t . " WHERE CharacterName='" . $memberName . "';";
	$result = mysql_query($query, $connection);
	$counts = mysql_fetch_array($result);
	if($counts[0] == 0)
	{
		return false;
	}else{
		return true;
	}
	
}

function getGuildXMLElement()
{
	global $realm,$guild;
	$url = "http://www.wowarmory.com/guild-info.xml?r=$realm&gn=" . urlencode($guild);
        #print("Using URL: $url\n<br/>");
	$agent = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3";
	
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt ($ch, CURLOPT_USERAGENT,  $agent); // If we don't do this, we'll get all of the HTML, etc.
	  
	$guildXML = curl_exec($ch);
	curl_close($ch);
	return new SimpleXMLElement($guildXML);
}

?>
