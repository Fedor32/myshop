<?php 
/**
* 
*/
class Routs
{
	public $controller;
	public $action;
	public $params=array();

	function __construct($path)
	{
	    $this->controller = 'MainController';	// Контроллер по умолчанию
		// 
		// Д О С Т У П Н Ы Е   К О Н Т Р О Л Л Е Р Ы
		// 
	    $controllers = array (
		    'admin' => 'AdminController',
		);
		// /////////////////////////////////////////////////////////////////////

		$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	    $uri_parts = explode('/', trim($url_path, ' /'));

	    $temp_controller = array_shift($uri_parts);// Получили имя контроллера
	    $this->action = array_shift($uri_parts);// Получили имя действия
	    // параметры
	    for ($i=0; $i < count($uri_parts); $i++) {$this->params[] = $uri_parts[$i];}

	    foreach ($controllers as $k => $v) {
	    	if($k == strtolower($temp_controller)) {
	    		$this->controller = $v;
	    		break;
	    	}
	    }

	}
}
?>