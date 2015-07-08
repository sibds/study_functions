<?php

require 'vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$request  = $client->getMessageFactory()->createCaptureRequest();
$response = $client->getMessageFactory()->createResponse();

$top    = 0;
$left   = 0;
$width  = 1200;
$height = 1080;

$request->setMethod('GET');
$request->setUrl('http://yandex.ru');
$request->setCaptureFile(sprintf('%s/file.jpg', sys_get_temp_dir()));
$request->setCaptureDimensions($width, $height, $top, $left);

$client->send($request, $response);

echo $response;
#var_dump($response);

// If the capture dimensions were applied
// to the request, you will see an information
// notice in the debug log. This is useful for
// debugging captures and will always be present,
// even if debug mode is disabled.
#var_dump($client->getLog());