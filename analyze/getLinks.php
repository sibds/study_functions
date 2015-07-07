<?php

function getSourceCode($link)
{
	// create curl resource
	$ch = curl_init();

	// set url
	curl_setopt($ch, CURLOPT_URL, $link);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	$code = curl_exec($ch);

	// close curl resource to free up system resources
	curl_close($ch);
	// source code
	// echo $code;
	return $code;	
}

function getUrls($string)
{
    #$regex = '/https?\:\/\/[^\" ]+/i';			# for http(s) links
    $regex = '/href="(https?\:\/)?\/[^\" ]+\"/i';			// http + some css
    preg_match_all($regex, $string, $matches);
    
    if (count($matches[0])>0)
    {
    	foreach($matches[0] as $url)
    	{
    		$url = substr($url, 6, -1);
    		$ext = substr($url, -3);

    		// filter for css and others
    		if (($ext !== "css") && ($ext !== "ico") && ($ext !== "jpg") && ($ext !== "png"))
    			$rez[] = $url;
    	}
    }
    if ($rez) 
        return ($rez);
    else
    	return 0;
}

function getCSSLinks($string)
{
    $regex = '/href="(https?\:\/)?\/[^\" ]+(css)\"/i';			// http + some css
    preg_match_all($regex, $string, $matches);
   
    if (count($matches[0])>0)
    {
    	foreach($matches[0] as $url)
    	{
    		$url = substr($url, 6, -1);
    		$ext = substr($url, -3);    		
    		$rez[] = $url;
    	}
    }
    if ($rez) 
        return ($rez);
    else
    	return 0;
}
	
function getJSLinks($string)
{
    $regex = '/src="(https?\:\/)?\/[^\" ]+(js)\"/i';			// http + some css
    preg_match_all($regex, $string, $matches);
   
    if (count($matches[0])>0)
    {
    	foreach($matches[0] as $url)
    	{
    		$url = substr($url, 5, -1);
    		$ext = substr($url, -3);    		
    		$rez[] = $url;
    	}
    }
    if ($rez) 
        return ($rez);
    else
    	return 0;
}

function getLists($site, $counter)
{
	echo "start working with ".$site."\n";

	$links[] = $site;
 	$intraLinks[] = $site;
 	$globalCSS = array();
 	$globalJS = array();

 	$i = 0;

 	while (($i <= $counter) && (count($intraLinks) > $i)) {
	 	$code = getSourceCode($intraLinks[$i]);
		// echo "working with ".$intraLinks[$i]."\n";	 	
	 	$urls = getUrls($code);
	 	$localCSS = getCSSLinks($code);
	 	$localJS = getJSLinks($code);

	 	if (count($urls)>0) {
		 	foreach($urls as $url){
				// если начинается на два слэша - внешний линк, дописываем http:
			 	if ($url[0] == "/" && $url[1] == "/") {
			 		$url = "http:".$url;
			 		if (!in_array($url, $links))		
			 			$links[] = $url;
			 	// если начинается на один слэш - внутренний линк, добавляем основную часть (домен)
			 	} elseif ($url[0] == "/") {
			 		$url = $site.$url;
			 		if (!in_array($url, $intraLinks))
			 			$intraLinks[] = $url;
			 	// в остальных случаях - обычная внешняя ссылка - добавляем в список
			 	} elseif (!in_array($url, $links))	
			 			$links[] = $url;

			 	/// сделать проверку на доменное имя - если то же, то ок
			}
	 	}

	 	if (count($localCSS)>0) {
		 	foreach($localCSS as $url){
				// если начинается на два слэша - внешний линк, дописываем http:
			 	if ($url[0] == "/" && $url[1] == "/") {
			 		$url = "http:".$url;
			 		if (!in_array($url, $globalCSS))		
			 			$globalCSS[] = $url;
			 	// если начинается на один слэш - внутренний линк, добавляем основную часть (домен)
			 	} elseif ($url[0] == "/") {
			 		$url = $site.$url;
			 		if (!in_array($url, $globalCSS))
			 			$globalCSS[] = $url;
			 	} elseif (!in_array($url, $globalCSS))	
			 			$globalCSS[] = $url;
			}
	 	}

	 	if (count($localJS)>0) {
		 	foreach($localJS as $url){
				// если начинается на два слэша - внешний линк, дописываем http:
			 	if ($url[0] == "/" && $url[1] == "/") {
			 		$url = "http:".$url;
			 		if (!in_array($url, $globalJS))		
			 			$globalJS[] = $url;
			 	// если начинается на один слэш - внутренний линк, добавляем основную часть (домен)
			 	} elseif ($url[0] == "/") {
			 		$url = $site.$url;
			 		if (!in_array($url, $globalJS))
			 			$globalJS[] = $url;
			 	} elseif (!in_array($url, $globalJS))	
			 			$globalJS[] = $url;
			}
	 	}

		$i += 1;
 	}
 	
 	return array($intraLinks, $globalCSS, $globalJS);
}

 // $site = "http://sibsr.ru";
 // $counter = 10;

// $links = getLists($site, $counter);

// echo "links: \n";
// foreach ($links as $l) {
// 	echo $l." \n";
// }

// echo "\nintraLinks: \n";
// foreach ($links[0] as $l) {
// 	echo $l." \n";
// }

// echo "\ncss: \n";
// foreach ($links[1] as $l) {
// 	echo $l." \n";
// }

// echo "\njs: \n";
// foreach ($links[2] as $l) {
// 	echo $l." \n";
// }

// foreach ($crc as $s => $c) {
// 	echo $s." => ".$c." \n";
// }
?>