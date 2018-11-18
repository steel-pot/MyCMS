<?php
namespace Admin\Controller; 
use Fw\Controller;
class LoginController extends Controller
{
	public function Index()
	{
		
		if(request()->isPost())
		{
			if(!\Libs\ValidateCode::checkCode(request()->post('vercode')))
			{ 
				echo '验证码错误！';
				exit;
			}	
			if(file_exists(MAIN_DIR.DS.'App'.DS.'Config.dat')) 
			{
				$config=json_decode(file_get_contents(MAIN_DIR.DS.'App'.DS.'Config.dat'),true);
			}else{
				echo '系统错误，请与管理员联系！';
				exit;
			}
			if(request()->post('username')!=$config['adminuser']||md5(md5(request()->post('password')))!=$config['adminpass'])
			{
				echo '用户名或者密码错误！';
				exit;
			}
			$_SESSION['adminuser']=$config['adminuser'];
			$_SESSION['adminpass']=$config['adminpass'];
			echo '1';
			exit;
		}
		
	}

	public function code()
	{
		$_vc = new \Libs\ValidateCode();  //实例化一个对象
		$_vc->doimg(); 
	}
	public function LogOut()
	{
		@session_destroy();
		header('Location:'.url('Admin/Login','Index'));
	}
}