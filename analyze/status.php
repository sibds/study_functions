<?php

include "getLinks.php";

function getInfo($link)
{
	// create curl resource
	$ch = curl_init();

	// set url
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	$code = curl_exec($ch);
	$r = curl_getinfo($ch);	
	// close curl resource to free up system resources
	curl_close($ch);
	return $r;			// total_time
}

function getStatusCode($link)
{
	// create curl resource
	$ch = curl_init();

	// set url
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	$code = curl_exec($ch);
	$r = curl_getinfo($ch);	
	// close curl resource to free up system resources
	curl_close($ch);
	return $r["http_code"];			// total_time
}

function getTotalTime($link)
{
	// create curl resource
	$ch = curl_init();

	// set url
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	$code = curl_exec($ch);
	$r = curl_getinfo($ch);	
	// close curl resource to free up system resources
	curl_close($ch);
	return $r["total_time"];	
}

function checkRedirect($link)
{
	// 1 -
	$http_code = getStatusCode($link);
	if ($http_code === 301)
	{
		echo "301 redirect";
		return true;
	}
	// 2 - html redirect
	$redirectString = "meta http-equiv=\"refresh\"";
	$source = getSourceCode($link);
	$pos = strpos($source, $redirectString);
	if ($pos !== false)
	{
		echo "http redirect";
		return true;
	}
	// JS redirect
	// google.js redirect fail

	// $redirectString = ".location";
	// $JSLinks = getJSLinks($source);
	// foreach ($JSLinks as $link) {
	// 	$jsCode = getSourceCode($link);
	// 	$pos = strpos($jsCode, $redirectString);
	// 		if ($pos !== false)
	// 		{
	// 			echo "js ".$link." redirect";
	// 			return true;
	// 		}
	// }

	return false;
}

$site = "http://sibsr.ru";
$links = getLists($site, 15);

// 0 - intralinks
// 1 - css
// 2 - js

// foreach ($links[0] as $link) {
// 	$statusCode[$link] = getStatusCode($link);
// 	echo $statusCode[$link]."\tfor link:\t".$link."\n";
// }

// foreach ($links[0] as $link) {
// 	$info[$link] = getInfo($link);
// 	echo $info[$link]["http_code"]."\tfor link:\t".$link."\n";
// }

foreach ($links[0] as $link) {
	$redirected = checkRedirect($link);
	if ($redirected)
		echo "link:\t".$link." redirected\n";
	else
		echo "link:\t".$link." NOT redirected\n";
}


// echo $string."\n";