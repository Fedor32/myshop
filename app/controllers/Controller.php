<?php 
/**
* 
*/
class Controller
{
	public function inset_view($container)
    {
        return $this->main_template;
    }

    /**
    * вывод
    */
    public function view($action = null,$params = array('',))
    {
        $this->action = (method_exists($this, $action)) ? $action : 'index'; // если такого модуля нет то грузим index
        $this->params = $params;
        return $this->$action();
    }

    public function index() {
        return 'В контроллере нет метода index';
    }

}
 ?>