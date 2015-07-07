<?php

include "getLinks.php";

$site = "http://sibsr.ru";
$counter = 3;

$links = getLists($site, $counter);

// 0 - intralinks 
// 1 - css links
// 2 - js links

foreach ($links[0] as $link) {
	$source = getSourceCode($link);
	$crcLink[$link] = crc32($source);
}

// foreach ($links[1] as $link) {
// 	$source = getSourceCode($link);
// 	$crcCSS[$link] = crc32($source);
// }

// foreach ($links[1] as $link) {
// 	$source = getSourceCode($link);
// 	$crcJS[$link] = crc32($source);
// }

foreach ($crcJS as $site => $crc) {
	echo "crc:\t".$crc."\t for site: $site\n";
}