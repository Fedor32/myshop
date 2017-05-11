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
	public function superadmin() {if($this->user->role < 9) return $this->index();
		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);
		$tmp->set('users',Model::getAll('users'));
		$tmp->set('mysection',array('pagename'=>'Панель Суперадминистратора','pagerem'=>''));
		$tmp->set('content',$tmp->view('admin/admin_super'));
        $page = $tmp->view('admin/main');
        return $page;
	}

	//
	// Объявления
	public function ads(){if($this->user->role < 5) return $this->index();

		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);
		$tmp->set('mysection',array('pagename'=>'Мои объявления','pagerem'=>''));
		$tmp->set('content',$tmp->view('admin/admin_ads'));
        $page = $tmp->view('admin/main');
        return $page;

	}

	//
	// Статьи
	public function articles(){if($this->user->role < 5) return $this->index();

		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);
		$tmp->set('mysection',array('pagename'=>'Мои статьи','pagerem'=>''));
		$tmp->set('content',$tmp->view('admin/admin_articles'));
        $page = $tmp->view('admin/main');
        return $page;
	}

	//
	// лендинг
	public function landing(){if($this->user->role < 5) return $this->index();

		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);
		$tmp->set('mysection',array('pagename'=>'Моя страничка','pagerem'=>'(Лендинг)'));
		$tmp->set('content',$tmp->view('admin/admin_landing'));
        $page = $tmp->view('admin/main');
        return $page;
	}

	//
	// Услуги
	public function service(){if($this->user->role < 5) return $this->index();

		$tmp = new Template($this->action,$this->params);

		$tmp->set('user',$this->user);

		print_pre($this->user);

		$tmp->set('mysection',array('pagename'=>'Мои услуги','pagerem'=>''));
		$tmp->set('content',$tmp->view('admin/admin_service'));
        $page = $tmp->view('admin/main');
        return $page;
	}

	//
	//	М А Г А З И Н
		public function shop(){if($this->user->role < 5) return $this->index();

		$tmp = new Template($this->action,$this->params);
		if($this->user->shop > 0) {

			$tmp->set('user',$this->user);
			$shop = new Shop($this->user->shop );
			$tmp->set('shop',$shop);
			switch ($this->params[0]) {
				case 'cat':
					$tmp->set('mysection',array('pagename'=>$shop->title,'pagerem'=>'Категории'));
					$tmp->set('content',$tmp->view('admin/admin_shopcat'));
					break;
			}


		} else {
			$tmp->set('mysection',array('pagename'=>'','pagerem'=>''));
			$tmp->set('user',$this->user);
			$tmp->set('content',$tmp->view('admin/admin_shoperror'));
		}

        $page = $tmp->view('admin/main');
        return $page;
	}
}
/**
* 
*/

 ?>
