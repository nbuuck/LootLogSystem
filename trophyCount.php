<?php

require("./functions.php");

head();

$h = new HttpQueryString();
$q = "SELECT LOOTWIN.CharacterName, COUNT(LOOTWIN.CharacterName) AS TrophyCount,ClassCode FROM LOOTWIN INNER JOIN PLAYER ON LOOTWIN.CharacterName=PLAYER.CharacterName WHERE LootItemID=47242";

if($h->get("c") != "")
{
	$q .= " AND LOOTWIN.CharacterName LIKE '%" . cleanSQLString($h->get("c")) . "$'";	
}

$q .= " GROUP BY LOOTWIN.CharacterName ORDER BY LOOTWIN.CharacterName;";

$c = getConnection();
$r = mysql_query($q,$c);

echo "<h2>Trophy Count";
if($h->get("c") != "")
{
	echo " for " . $h->get("c");	
}
else
{
	echo "s"; // Makes this plural since we're viewing counts for all raiders.
}
echo "</h2>\n";

echo "<table class=\"default\" border=\"1\" width=\"200\" id=\"trophyCounts\">\n";
echo "<tr><th>Raider</th><th>#</th></tr>\n";

while($row = mysql_fetch_array($r))
{
	echo "\t<tr><td><img src=\"./images/class/" . $row['ClassCode'] . ".gif\"></img>&nbsp;<a href=\"./lootLog.php?c=" . $row['CharacterName'] . "\">" . $row['CharacterName'] . "</a></td><td>" . $row['TrophyCount'] . "</td></tr>\n";
}

echo "</table>\n";

echo "<script type=\"text/javascript\">";
echo "var tbl = document.getElementById('trophyCounts');";
echo "if(tbl.rows.length == 1){tbl.style.display='none';document.body.innerHTML+='<b>No trophies have been won yet.</b><br />';}";
echo "registerShortcut(\"M\".charCodeAt(0), \"btnMainMenu\");\n";
echo "registerShortcut(\"m\".charCodeAt(0), \"btnMainMenu\");\n";
echo "</script>";

echo <<<EOF
<br /><a href="./index.html" id="btnMainMenu" class="smallNavigation"><u><b>M</b></u>enu</a>
EOF;



mysql_close($c);

tail();

?>