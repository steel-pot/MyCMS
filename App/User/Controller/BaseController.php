<?php
namespace User\Controller; ;
use Fw\Controller;
use User\Model\Login;
class BaseController extends Controller{ 	
		var $layout="Public/Layout.html";
	  public function init()
	  {
	  	$login=new Login(); 
	  	if(!$login->checkLogin())
	  	{ 
	  		 $this->error('请登陆后再试',url('User/Login','Index'));
	  	}
	  	$this->userinfo=$login->getUserinfo();
	  } 
}