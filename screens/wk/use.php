<?php
 
require_once 'WebsiteToImage.php';
 
$websiteToImage = new WebsiteToImage();
$websiteToImage->setProgramPath('/usr/local/bin/wkhtmltoimage-i386')
               ->setOutputFile('nnn.jpg')
               ->setQuality(70)
               ->setUrl('http://yandex.ru')
               ->start();
 

