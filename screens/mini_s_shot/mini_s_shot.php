<?php
/**
* функция для создания скрина
* @var $url string - адрес сайта
* @var $screen string - размер экрана, может принимать только ширину. И может принимать ширину и высоту - 1024x768
* @var $size integer - ширина масштабированной картинки
* @var $format string - может принимать два значения (JPEG|PNG), по умолчанию "JPEG"
*/
function getScreenShot($url, $screen, $size, $format = "jpeg"){
    $result = "http://mini.s-shot.ru/".$screen."/".$size."/".$format."/?".$url; // делаем запрос к сайту, который делает скрины
    $pic = file_get_contents($result); // получаем данные. Ответ от сайта
    file_put_contents("screen.".$format, $pic); // сохраняем полученную картинку
}

getScreenShot('http://yandex.ru', '1200X1080', '1200');
