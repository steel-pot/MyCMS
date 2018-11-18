<?php
namespace Admin\Controller; 
use Fw\Model;
class ConfigController extends BaseController
{
	public function Index()
	{
		if(request()->isPost())
		{
			$pass=request()->post('adminpass');
			if(empty($pass))
			{
				$_POST['adminpass']=$this->config['adminpass'];
			}else{
				$_POST['adminpass']=md5(md5($pass));
			} 
			file_put_contents(MAIN_DIR.DS.'App'.DS.'Config.dat', json_encode($_POST));
			//清空缓存目录
			deleteDir(MAIN_DIR.DS.'Public'.DS.'web');	
			
			header('Location:'.url('Admin/Config','Index'));
			exit;
		}
	}
}
