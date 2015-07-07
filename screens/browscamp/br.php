<?php
class Site_screen_shots
{
  private $width;
  private $quality=1;
  private $qualityList=array("1"=>"png","2"=>"jpg","3"=>"jpg","4"=>"jpg");
  private $url;
  var $nameImg=NULL;
  var $dir=NULL;

  function width($width=800)
  {
    $widthArray=array("800", "832", "1024", "1280", "1600");
    if(in_array($width,$widthArray))
    {
      $this->width=$width;
    }
    else
    {
      $this->width=800;
    }
  }

  function quality($quality=1)
  {
    $quality=(int)$quality;
    if($quality>0&&$quality<5)
    {
      $this->quality=$quality;
    }
    else
    {
      $this->quality=1;
    }
  }


  function __construct($url=NULL)
  {
    if($url==NULL)
    throw new Exception("Вы не ввели url");

    $this->url=preg_match("#^http://.*#i",$url)?$url:"http://{$url}";
  }
  /**
   * создает скриншет, сохраняет его в указанную папку и возвращает ссылку на картинку
   * @return url
   */
  function screen_shots()
  {
   
    /* получаем страницу */
    $c=curl_init("http://www.browsrcamp.com/");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $result=curl_exec($c);
   
    /* находим все формы на странице */
    preg_match_all('#<input(.*?)>#is', $result, $matches);
    foreach($matches[1] as $input)
    {
      preg_match('#name=\"([^\"]+)\"#is', $input, $name);
      preg_match('#value=\"([^\"]+)\"#is', $input, $value);
      $post[$name[1]]=$value[1];
    }

/* ну думаю понятно что мы тут делаем */
    $post["url"]=$this->url;
    $post["width"]=$this->width;
    $post["quality"]=$this->quality;

    /* отправляем запрос */
    $c=curl_init("http://www.browsrcamp.com/");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_POSTFIELDS, $post);
    if(!$page=curl_exec($c))
    throw new Exception("Ошибка: BrowsrCamp был не в состоянии обрабатывать ваш запрос.");
    curl_close($c);

    if(strpos($page,"Error: BrowsrCamp wasn't able to handle your request."))
    throw new Exception("Ошибка: BrowsrCamp был не в состоянии обрабатывать ваш запрос.");

    /* вытаскиваем ссылку на картинку */
    $page=substr($page,strpos($page,"Click the image for the fullsize version."));
    $page=substr($page,strpos($page,"<a"));
    $page=substr($page,0,strpos($page,"</div>"));
    $page=substr($page,strpos($page,"http"));
    $page=substr($page,0,strpos($page,"\""));

    /* сохраняем картинку в нужную директорию */
    if($this->dir!=NULL)
    {
      if(!$img=file_get_contents($page))
      throw new Exception("Не удалось получить картинку.");

      if($this->nameImg==NULL)
      {
        $this->nameImg=str_replace("http://","",$this->url);
      }

      $urlImg=$this->dir."/".$this->nameImg.".".$this->qualityList[$this->quality];
      if(!file_put_contents($urlImg,$img))
      throw new Exception("Не удалось сохранить картинку на сервер :\"".$this->dir."/".$this->nameImg.".".$this->qualityList[$this->quality]."\" .");

      return $urlImg;
    }
    else
    {
      return $page;
    }
  }
}


$screenShots = new Site_screen_shots("http://google.ru");
$screenShots->dir="screen";
$screenShots->quality(2);
$screenShots->width(1600);
echo $screenShots->screen_shots();