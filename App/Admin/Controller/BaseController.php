<?php 
namespace Admin\Controller; 
use Fw\Controller;
/**
 * 
 */
class BaseController extends Controller
{
	 var $layout="Public/Layout.html";
	 public function init()
	 {
		 
	 	if(file_exists(MAIN_DIR.DS.'App'.DS.'Config.dat'))
	 	{
	 		$this->config=json_decode(file_get_contents(MAIN_DIR.DS.'App'.DS.'Config.dat'),true);
	 	}else{
	 		$this->config=array();
	 	}
		if(!isset($_SESSION['adminuser'])||!isset($_SESSION['adminpass'])||$_SESSION['adminuser']!=$this->config['adminuser']||$_SESSION['adminpass']!=$this->config['adminpass'])
		{
			header('Location:'.url('Admin/Login','Index'));
			exit;
		}
	 }
}

 ?>