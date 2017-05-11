<?
/**
*   ШАБЛОНИЗАТОР
*/
class Template {
  
  private $data = array();  // Данные для вывода
  private $action;
  private $params;

  public function __construct($action='',$params=array()) {
    global $validform;
    $this->main_dir = MAINDIR;
    $this->validform = $validform;
    $this->action = $action;
    $this->params = $params;
//    $this->top_menu = $this->_prepareTopMenu();
  }

  /* Метод для добавления новых значений в данные для вывода */
  public function set($name, $value) {
    $this->data[$name] = $value;
  }

  /* Метод для удаления значений из данных для вывода */
  public function delete($name) {
    unset($this->data[$name]);
  }

  /* При обращении, например, к $this->title будет выводиться $this->data["title"] */
  public function __get($name) {
    if (isset($this->data[$name])) return $this->data[$name];
    return "";
  }

  /* Вывод tpl-файла, в который подставляются все данные для вывода */
  public function view($template) {
    $template = TEMPLATES.$template.".tpl";
    ob_start();
    include ($template);
    return ob_get_clean();
  }
}
?>