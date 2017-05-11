<?php 
/**
* 
*/
class AdminController extends Controller
{
	private $user;

	function __construct()
	{
		$id = (isset($_SESSION['CID'])) ? $_SESSION['CID'] : 0;
		$this->user = new Auth($id);
	}

	public function index() { if($this->user->role < 5) return $this->login();

		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);
		$tmp->set('mysection',array('pagename'=>'Панель администратора','pagerem'=>'Главная'));
		$tmp->set('content',$tmp->view('admin/dashboard'));
        $page = $tmp->view('admin/main');
        return $page;
	}
	//
	//	АВТОРИЗАЦИЯ
	public function login() { if($this->user->role > 4) return $this->index();
		$tmp = new Template();
		$tmp->set("site_name",SITENAME);
        $page = $tmp->view('admin/admin_login');
        return $page;
	}
	//
	// РАЗЛОГИВАНИЕ
	public function logout()
	{
		$this->user->logout();
		header("Location: ".MAINDIR.'/admin');

	}
	//
	// Панель суперадмина
	public function superadmin() {
		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);
		$tmp->set('users',Model::getAll('users'));
		$tmp->set('mysection',array('pagename'=>'Панель Суперадминистратора','pagerem'=>''));
		$tmp->set('content',$tmp->view('admin/admin_super'));
        $page = $tmp->view('admin/main');
        return $page;
	}

}
/**
* 
*/

 ?>
