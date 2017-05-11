<?php 
//	Обработка AJAX запросов
//	
session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/.env"; // НАСТРОЙКИ ПРОГРАММЫ
//
// ищем пользователя
$res = (isset($_SESSION['CID'])) ? $_SESSION['CID'] : 0;
$user = new Auth($res);
unset($res);

// Обработка форм
// 
if((isset($_POST['form'])) && ($_POST['validform'] == $_SESSION['validform'])){
	switch ($_POST['form']) {
		case 'login': // Попытка авторизации
			echo ($user->login($_POST['login'],$_POST['pass'])) ? 1 : 0;
			break;
	}
}


 ?>