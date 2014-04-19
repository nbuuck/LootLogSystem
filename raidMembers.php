<?php

require("./functions.php");

head();

echo "<h2>Reckless Abandon Raiders</h2>";

echo "<b>Note</b>: Only raiders that have won <i>something</i> during a raid will be shown in this list.<br /><br /><br />";

$c = getConnection();
if(!c){ die("Could not connect to database server."); }

$q = "SELECT * FROM PLAYER";
$q .= " WHERE CharacterName IN (SELECT DISTINCT CharacterName FROM USER.LOOTWIN)";
$q .= " AND CharacterName != '?'";
$q .= " ORDER BY CharacterName;";

$r = mysql_query($q,$c);
$members = array();
while($m = mysql_fetch_array($r, MYSQL_ASSOC))
{
	$members[sizeof($members)] = $m;
}

echo "<table class=\"default\" border=\"1\">\n";

for($i = 0; $i < 10; $i++)
{
	
	$count = 0;
	$max = ceil(sizeof($members)/10);
		
	echo "\t<tr>\n";
	
	while($count < $max)
	{
            
            if ($members[$i+10*$count]['CharacterName'] != "")
            {
                echo "\t\t<td class=\"clickable\">";
                if($members[$i+10*$count]['ClassCode'] != "0")
                {
                    echo "<img src=\"./images/class/" . $members[$i+10*$count]['ClassCode'] . ".gif\" />&nbsp;";
                }
                echo    "<a href=\"./lootLog.php?c=" .
                        urlencode($members[$i+10*$count]['CharacterName']) .
                        "\">" . $members[$i+10*$count]['CharacterName'] . "</a>";
            
            if(isLoggedIn() && $members[$i+10*$count]['CharacterName'] != "")
            {
                    echo '</td><td><a href="#" onclick="deleteRaider(\'' . $members[$i+10*$count]['CharacterName'] .
                    '\');"><img src="./images/redx.png" height="20" width="20" /></a>';
            }
            echo "</td>\n";
            }
            $count++;
	}
	
	echo "\t</tr>\n";
	
}

echo "</table>\n";
echo "<script type=\"text/javascript\">\n";
echo "registerShortcut(\"M\".charCodeAt(0), \"btnMainMenu\");\n";
echo "registerShortcut(\"m\".charCodeAt(0), \"btnMainMenu\");\n";
echo "</script>\n";

echo <<<EOF
<br />
<a href="./index.html" id="btnMainMenu" class="smallNavigation"><u><b>M</b></u>enu</a>
EOF;

mysql_close($c);

tail();

?>
