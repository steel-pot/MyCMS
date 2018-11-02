<?php
namespace Admin\Controller; 
use Admin\Model\TabAdminGroup as TabGroup;
class PowerController extends BaseController
{
	public function Group()
	{
		$TabGroup=new TabGroup();
		$this->list=$TabGroup->findAll(null,'id DESC');
		$this->status=['正常','禁用'];	
	}
	public function GroupAdd()
	{
		if(request()->isPost())
		{
			$name=request()->post('name','',true);
			$remark=request()->post('remark','',true);
			$status=request()->post('status','0',true);
			$update_time=date('Y-m-d H:i:s');
			$TabGroup=new TabGroup();
			if(empty($name))$this->error('组名不能为空');
			if($TabGroup->findCount(['name'=>$name])>0)$this->error('组名重复');
			if($TabGroup->create(['name'=>$name,'remark'=>$remark,'status'=>$status,'update_time'=>$update_time]))
			{
				$this->success('添加组成功！',url('Admin/Power','Group'));
			}else{
				$this->error('添加组失败！');
			}
		}
	}
	public function GroupEdit()
	{

	}
	public function GroupDel()
	{

	}
	public function GroupPower()
	{

	}

	public function User()
	{

	}
	public function UserAdd()
	{

	}
	public function UserEdit()
	{

	}
	public function UserDel()
	{

	}	
}