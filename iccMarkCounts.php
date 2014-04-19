<?php

require("./functions.php");

head();

/*
 * Protector's	52026	52029 (H)
 * Conqueror's	52027	52030 (H)
 * Vanquisher's	52025	52028 (H)
 */

$h = new HttpQueryString();

$q = "SELECT CharacterName,ClassCode";
$q .= " FROM PLAYER";
$q .= " ORDER BY CharacterName;";

$c = getConnection();
$r = mysql_query($q,$c);
$rows = array();

while ($character = mysql_fetch_array($r))
{
	$row = array();
	$row['CharacterName'] = $character['CharacterName'];
	$row['ClassCode'] = $character['ClassCode'];
	$row['NormalMarkCount'] = getMarkCount($row['CharacterName'],false,$c);
	$row['HeroicMarkCount'] = getMarkCount($row['CharacterName'],true,$c);
	array_push($rows,$row);	
}

echo "<h2>Mark Counts";
echo "</h2>\n";

echo "<table class=\"default\" border=\"1\" width=\"200\" id=\"trophyCounts\">\n";
echo "<tr><th>Raider</th><th>N</th><th>H</th></tr>\n";

foreach($rows as $row)
{
	if(!$row['NormalMarkCount'] && !$row['HeroicMarkCount']){ continue; }
	echo "\t<tr><td><img src=\"./images/class/" . $row['ClassCode'] . ".gif\"></img>";
	echo "&nbsp;<a href=\"./lootLog.php?c=" . $row['CharacterName'] . "\">" . $row['CharacterName'] . "</a></td>";
	echo "<td><div align=\"center\">" . ($row['NormalMarkCount'] ? $row['NormalMarkCount'] : "0") . "</div></td>";
	echo "<td><div align=\"center\">" . ($row['HeroicMarkCount'] ? $row['HeroicMarkCount'] : "0") . "</div></td>";
	echo "</tr>\n";
}

echo "</table>\n";

echo "<script type=\"text/javascript\">";
echo "var tbl = document.getElementById('trophyCounts');";
echo "if(tbl.rows.length == 1){tbl.style.display='none';document.body.innerHTML+='<b>No marks have been won yet.</b><br />';}";
echo "registerShortcut(\"M\".charCodeAt(0), \"btnMainMenu\");\n";
echo "registerShortcut(\"m\".charCodeAt(0), \"btnMainMenu\");\n";
echo "</script>";

echo <<<EOF
<br /><a href="./index.html" id="btnMainMenu" class="smallNavigation"><u><b>M</b></u>enu</a>
EOF;

mysql_close($c);

tail();

function getMarkCount($name, $isHeroic = false, $c)
{
	$ids = $isHeroic ? "52029,52030,52028" : "52026,52027,52025";
	$q = "SELECT COUNT(LOOTWIN.CharacterName) AS MarkCount";
	$q .= " FROM USER.LOOTWIN";
	$q .= " WHERE LOOTWIN.CharacterName='" . $name . "'";
	$q .= " AND LOOTWIN.LootItemID IN (" . $ids . ")";
	$q .= " GROUP BY LOOTWIN.CharacterName";
	$q .= " ORDER BY LOOTWIN.CharacterName";
	$r = mysql_query($q,$c);
	if(!$r)
	{
		return false;
	}
	$row = mysql_fetch_array($r);
	return $row['MarkCount'];
}

?>