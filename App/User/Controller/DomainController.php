<?php
namespace User\Controller;
use User\Model\Domain;
class DomainController extends BaseController
{
	public function Index()
	{
		$this->config=json_decode(file_get_contents(MAIN_DIR.DS.'App'.DS.'Config.dat'),true);
		$Domain=new Domain();
		$this->rows=$Domain->getList($this->userinfo['id']);
	}
	public function Add()
	{
		 header('Content-type: application/json;chartset:utf-8'); 
		$domain=request()->post('domain','',true);
		if(empty($domain))
		{
			$rs=['result'=>0,'info'=>'域名不能为空'];
		}else{
			$Domain=new Domain();
			$info=$Domain->addDomain($this->userinfo['id'],$domain);
			if(!empty($info))
			{
				$rs=['result'=>0,'info'=>$info];
			}else{
				$rs=['result'=>1,'info'=>'添加成功'];
			}
		}
		echo json_encode($rs);		
	}	
	public function Edit()
	{
		header('Content-type: application/json;chartset:utf-8'); 
		$domain=request()->post('domain','',true);
		$token=request()->post('token','',true);
		if(empty($domain))
		{
			$rs=['result'=>0,'info'=>'域名不能为空'];
		}else{
			$Domain=new Domain();
			$info=$Domain->editDomain($this->userinfo['id'],$domain,$token);
			if(!empty($info))
			{
				$rs=['result'=>0,'info'=>$info];
			}else{
				$rs=['result'=>1,'info'=>'添加成功'];
			}
		}
		echo json_encode($rs);		
	}
}