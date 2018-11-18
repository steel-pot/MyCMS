<?php
namespace Common\Controller;
class CodeController {
	public function Index()
	{
		$_vc = new \Libs\ValidateCode();  //实例化一个对象
		$_vc->doimg(); 
	} 
} 