<?php
namespace User\Controller;
use User\Model\Main;
use User\Model\Domain;
use Fw\Model;
class MainController extends BaseController{ 	
	public function Index()
	{			
		 $this->layout=null;		 

		$Domain=new Domain();
		$this->Domains=$Domain->getList($this->userinfo['id']);
	}   
	public function Main()
	{
		//获取首页数据
		$this->rows=Main::getData($this->userinfo['id']);
		 
	}
	public function ChangePass()
	{
		 header('content-type:application/json;charset=utf-8');
		$oldpass=request()->post('oldpass');
		$newpass=request()->post('newpass');
		$renewpass=request()->post('renewpass');
		if(md5(md5($oldpass))!=$this->userinfo['userpass'])
		{
			echo json_encode(['result'=>0,'info'=>'旧密码错误！']);
			exit;
		}
		if(empty($newpass))
		{
			echo json_encode(['result'=>0,'info'=>'新密码不能为空！']);
			exit;
		}
		if($newpass!=$renewpass)
		{
			echo json_encode(['result'=>0,'info'=>'两次输入的新密码不一致']);
			exit;
		}
		$tab=new Model('ct_user');
		$tab->update(['id'=>$this->userinfo['id'],'userpass'=>md5(md5($oldpass))],['userpass'=>md5(md5($newpass))]);
			echo json_encode(['result'=>1,'info'=>'修改成功']);
			exit;
	}
}