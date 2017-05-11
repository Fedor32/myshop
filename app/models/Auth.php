<?php 
/**
 * 	КЛАСС РЕГИСТРАЦИИ-АУНТЕФИКАЦИИ ПОЛЬЗОВАТЕЛЕЙ
 */
 class Auth extends Model
 {
 	
 	public static $roles = array("0"=>"Гость","1"=>"Пользователь","5"=>"Менеджер","8"=>"Администратор","9"=>"SuperAdmin");

 	public $id;
 	public $login;
 	public $email;
 	public $pass;
 	public $role;
 	public $last_visit;
 	public $status;
 	public $phone;
 	public $adres;
 	public $fio;

 	function __construct($id = 0)
 	{
 		parent::__construct();
 		$this->table = 'users';
 		$this->id = $id;
 		$res = parent::getID($id);
 		if($res)
 			foreach ($res as $k => $v) {
 				$this->$k = $v;
 			}
 	}

 	public function login($email,$pass)
 	{
 		$res = self::$db->selectRow("SELECT * FROM $this->table WHERE status=1 AND email={?} AND pass={?}",array($email,MD5($pass)));
 		if($res) {
 			$_SESSION['CID'] = $res['id'];
 			return true;
 		}
 		return false;
 	}

 	public function logout()
 	{
 		unset($_SESSION['CID']);
 	}

 } 
 ?>