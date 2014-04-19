<?php

require("./functions.php");

head();

$h = new HttpQueryString();
$u = "";
if($h->get("u") == "")
{
	$u = "./index.html";	
}
else
{
	$u = $h->get("u");		
}

if($h->get("m") != "")
{
	echo "<div align=\"center\"><h3>" . $h->get("m") . "</h3></div>";	
}

echo "<script type=\"\">\n";
echo "window.setTimeout(\"self.location='" . urldecode($u) . "';\",1500);";
echo "</script>\n";

tail();

?>