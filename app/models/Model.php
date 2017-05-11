<?php 
/**
* 
*/
class Model
{
	public $table;

    public static $db;

	function __construct()
	{
		self::$db = DataBase::getDB();
	}

	public static function getAll($table)
	{
		return self::$db->select("SELECT * FROM $table");
	}

	public function getID($id)
	{
		return self::$db->selectRow("SELECT * FROM $this->table WHERE id=$id");
	}

	public static function getUserSection($user)
	{
		return self::$db->select("SELECT sections.* FROM user_section INNER JOIN sections ON user_section.section = sections.id WHERE user_section.user = {?} ORDER BY section",array($user));
	}
}
 ?>