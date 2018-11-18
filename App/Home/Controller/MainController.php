<?php
namespace Home\Controller;
use Fw\Controller;
use Fw\Model;
class MainController extends Controller{ 	
	public function Index()
	{			
		 header('Location:/web/welcome/login.html');
	} 
	 
}