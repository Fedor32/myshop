<?php 
/**
* 
*/
class MainController extends Controller
{
	public function index()
	{
		$tmp = new Template;

		$tmp->set('content',$tmp->view('homepage'));

        $page = $tmp->view('main');
        return $page;
	}

}
 ?>