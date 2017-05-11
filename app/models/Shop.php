<?php 
/**
* 
*/
class Shop extends Model
{
	

	public $id;
	public $status;
	public $title;
	public $logo;
	public $description;
	public $adress;
	public $phone;
	public $work_time;
	public $owner;
	public $map_position;

	function __construct($id)
	{
		parent::__construct();
		$this->table = 'shops';
 		$this->id = $id;
		$res = parent::getID($id);
 		if($res)
 			foreach ($res as $k => $v) {
 				$this->$k = $v;
 			}
	}
}
 ?>