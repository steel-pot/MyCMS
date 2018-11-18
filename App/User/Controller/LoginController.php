<?php
namespace User\Controller; ;
use Fw\Controller;
use User\Model\Login;
class LoginController extends Controller{ 	
	public function Index()
	{			
		$Login=new Login();

		if($Login->checkLogin())
		{
			jump(url('User/Main','Index'));
		}
	} 
	public function LoginIn()
	{
		header('Content-Type:application/json; charset=utf-8'); 
		$username=request()->post('username');
		$password=request()->post('password'); 	
		 
		if(!\Libs\ValidateCode::checkCode(request()->post('vercode')))
		{ 
			echo json_encode(array('result'=>0,'info'=>'验证码错误！'));
			exit;
		}
		$Login=new Login();
		if($Login->LoginIn($username,$password))
		{
			echo json_encode(array('result'=>1,'info'=>'登陆成功'));
			exit;
		}
		echo json_encode(array('result'=>0,'info'=>'用户名或者密码错误！'));
		exit;
	}
	public function Logout()
	{	
		$Login=new Login();
		$Login->Logout();
		jump(url('User/Login','Index'));	
	}	
	public function Regedit()
	{
		$Login=new Login();
		if($Login->checkLogin())
		{
			jump(url('User/Main','Index'));
		}
	}  
	public function RegeditIn()
	{
		header('Content-Type:application/json; charset=utf-8'); 
		$username=request()->post('username');
		$password=request()->post('password'); 	
		$repass=request()->post('repass'); 	 

 		if(!\Libs\ValidateCode::checkCode(request()->post('vercode')))
		{ 
			echo json_encode(array('result'=>0,'info'=>'验证码错误！'));
			exit;
		} 
		if(empty($username))
		{
			echo json_encode(array('result'=>0,'info'=>'用户名不能为空'));
			exit;
		}
		if(empty($password))
		{
			echo json_encode(array('result'=>0,'info'=>'密码不能为空'));
			exit;
		}
		if($repass!=$password)
		{
			echo json_encode(array('result'=>0,'info'=>'两次输入的密码不一致'));
			exit;
		}
		$Login=new Login();
		 
		$rs=$Login->regedit($username,$password);
		 
		if(!empty($rs))
		{
			echo json_encode(array('result'=>0,'info'=>$rs));
			exit;
		}else{ 
			$Login->LoginIn($username,$password);
			echo json_encode(array('result'=>1,'info'=>'注册成功'));
			exit;
		}
	}
}