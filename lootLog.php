<?php

require("./functions.php");
$c = getConnection();
$h = new HttpQueryString();
$q = "SELECT * FROM LOOTWIN";
$q .= " INNER JOIN PLAYER ON LOOTWIN.CharacterName=PLAYER.CharacterName";

if($h->get("c") != "" || $h->get("d") != "")
{
	$q .= " WHERE";
}
$otherClauseSet = false;
if($h->get("c") != "")
{
	$q .= ($otherClauseSet)?" AND":"";
	$q .= " LOOTWIN.CharacterName LIKE '%" . cleanSQLString($h->get("c")) . "%'";
	$otherClauseSet = true;	
}
if($h->get("d") != "")
{
	$q .= ($otherClauseSet)?" AND":"";
	$q .= " LootWinDate='" . $h->get("d") . "'";
	$otherClauseSet = true;
}

$q .= " ORDER BY LootWinDate DESC,PLAYER.CharacterName,LootItemName";
$q .= ";";

$r = mysql_query($q,$c);

head();

echo "<h2>Loot Log";
if($h->get("c") != "")
{
	echo " for " . $h->get("c");
	echo "&nbsp<a href=\"./lootLog.php" . (($h->get("d"))?"?d=" . $h->get("d"):"") . "\">";
	echo "<img src=\"./images/redx.png\" height=\"20\" width=\"20\"></img>";
	echo "</a>";	
}
if($h->get("d") != "")
{
	echo " on " . $h->get("d");
	echo "&nbsp<a href=\"./lootLog.php" . (($h->get("c"))?"?c=" . $h->get("c"):"") . "\">";
	echo "<img src=\"./images/redx.png\" height=\"20\" width=\"20\"></img>";
	echo "</a>";
}
echo "</h2>";

echo "<table class=\"default\" border=\"1\" id=\"lootLogTable\">\n";
echo "<tr><th>Raider</th><th>Item</th><th>Date</th></tr>\n";

$previousDate = null;

if($r)
{
    while($w = mysql_fetch_array($r, MYSQL_ASSOC))
    {

        if($previousDate != null
                && $previousDate != $w['LootWinDate'])
        {
            echo "<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
        }

        echo "<tr id=\"LootWin" . $w['LootWinID'] . "\">\n";
        echo "<td class=\"clickable\">";
        if($w['ClassCode'] != "0")
        {
                echo "<img src=\"./images/class/" . $w['ClassCode'] . ".gif\"></img>&nbsp;";
        }
        if(!$h->get("c"))
        {
                echo "<a href=\"./lootLog.php?c=" . urlencode($w['CharacterName']) .
                (($h->get("d"))?"&d=" . $w['LootWinDate']:"") .
                "\">" . $w['CharacterName'] . "</a>";
        }
        else
        {
                echo $w['CharacterName'];
        }
        echo "</td>\n";
        echo "<td class=\"clickable\"><a href=\"http://www.wowhead.com/?item=" . urlencode($w['LootItemID']) . "\">" . $w['LootItemName'] . "</a></td>\n";
        echo "<td class=\"clickable\">";
        if($h->get("d") == "")
        {
                echo "<a href=\"./lootLog.php?d=" . $w['LootWinDate'] .
                        (($h->get("c") != "")?"&c=" . $w['CharacterName']:"") . "\">" .
                        $w['LootWinDate'] . "</a>";
        }
        else
        {
                echo $w['LootWinDate'];
        }
        echo "</td>\n";

        if(isLoggedIn())
        {
                echo "<td><input type=\"button\" value=\"Delete\" onClick=\"deleteLootWin(" . $w['LootWinID'] . ");\" /></td>\n";
        }

        echo "</tr>\n";

        $previousDate = $w['LootWinDate'];

    }
}

echo "</table>";

echo "<script type=\"text/javascript\">";
echo "var tbl = document.getElementById('lootLogTable');";
echo "if(tbl.rows.length == 1){tbl.style.display='none';document.body.innerHTML+='<b>No items have been won yet.</b><br />';}\n";
echo "registerShortcut(\"M\".charCodeAt(0), \"btnMainMenu\");";
echo "registerShortcut(\"m\".charCodeAt(0), \"btnMainMenu\");";
echo "</script>";

echo "<br />";
echo '<a href="./index.html" id="btnMainMenu" class="smallNavigation"><u><b>M</b></u>enu</a>';


tail();


?>
