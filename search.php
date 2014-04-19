<?php

	header("Content-type: text/xml");

	$h = new HttpQueryString();
	if($h->get("q") == ""){ die(""); }
	
	$agent = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070319 Firefox/2.0.0.3";
	$url = "http://www.wowarmory.com/search.xml?searchType=items&searchQuery=" . $h->get("q");
	
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt ($ch, CURLOPT_USERAGENT,  $agent); // If we don't do this, we'll get all of the HTML, etc.
	  
	$resultXML = curl_exec($ch);
	curl_close($ch);
	
	echo $resultXML;

?>