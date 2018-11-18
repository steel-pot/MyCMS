<?php
namespace User\Model;
use Fw\Model;
class Login{
	/*
		检查用户是否登陆		
	*/
	public function checkLogin()
	{

		$userinfo=session('userinfo');
		if($userinfo)
		{
			$tab=new Model('ct_user');
			$userinfo=$tab->find(['id'=>$userinfo['id'],'userpass'=>$userinfo['userpass']]);
			if($userinfo['status']!='0')session()->clean();
		}
		return $userinfo&&$userinfo['status']=='0';
	}
	public function getUserinfo()
	{
		return session('userinfo');
	}
	public function Logout()
	{
		session()->clean();		
	}
	public function LoginIn($username,$password)
	{
		$tab=new Model('ct_user');
		$userinfo=$tab->find(['username'=>$username,'userpass'=>md5(md5($password))]);
		if($userinfo&&$userinfo['status']=='0')
		{
			session('userinfo',$userinfo);
			return true;
		}
		return false;
	}

	public function regedit($username,$password)
	{

		$tab=new Model('ct_user');
		if($tab->findCount(['username'=>$username])>0)
		{ 
			return '用户已经存在';
		}
		$tab->create(['username'=>$username,'userpass'=>md5(md5($password))]); 
		return null;
	}
}