<?php


session_start();

$validform = md5(session_id()); // хеш страницы для защиты форм
$_SESSION['validform'] = $validform; // пишем его в сессию


include_once ".env"; // НАСТРОЙКИ ПРОГРАММЫ

$rout = new Routs($_SERVER['REQUEST_URI']); // загрузка данных роутера

$page = new $rout->controller;  // создание выбранного контроллера


if(! method_exists($page,$rout->action)) {
	array_unshift($rout->params, $rout->action);
	$rout->action = 'index';
}

echo $page->view($rout->action,$rout->params);

//echo preg_replace("|[\s]+|s", " ",$page->view($rout->action,$rout->params) ); // вывод готового шаблона

//print_pre($page);













function rus_dat($dt=''){ if(($dt == '')||($dt == '0000-00-00'))return ''; list($m1)=explode(' ',$dt); list($_11,$_12,$_13)=explode('-',$m1); $_5=$_13 .'.' .$_12 .'.' .$_11; return $_5; }

function print_pre($arr){echo'<pre>';print_r($arr);echo'</pre>';}

function small_product($path) {return '/small'.$path;}

function cut($string, $length){
$string = mb_substr($string, 0, $length,'UTF-8'); // обрезаем и работаем со всеми кодировками и указываем исходную кодировку
$position = mb_strrpos($string, ' ', 'UTF-8'); // определение позиции последнего пробела. Именно по нему и разделяем слова
$string = mb_substr($string, 0, $position, 'UTF-8'); // Обрезаем переменную по позиции
return $string.' ...';
}

unset($rout);
unset($page);

?>