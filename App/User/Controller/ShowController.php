<?php
namespace User\Controller;
use User\Model\Main;
use User\Model\Show;
class ShowController extends BaseController{ 	
	public function Index()
	{
		$this->did=request()->get('did');
		$this->beginDate=request()->post('beginDate',date('Y-m-d',strtotime('-1 month')));
		$this->endDate=request()->post('endDate',date('Y-m-d'));
		$show=new Show();
		$this->rows=$show->getList($this->userinfo['id'],$this->did,$this->beginDate,$this->endDate);
	}
	public function From()
	{
		$this->did=request()->get('did');
		$this->beginDate=request()->post('beginDate',date('Y-m-d',strtotime('-1 month')));
		$this->endDate=request()->post('endDate',date('Y-m-d'));
		$show=new Show();
		$this->rows=$show->getFromList($this->userinfo['id'],$this->did,$this->beginDate,$this->endDate);
	}
	public function Page()
	{
		$this->did=request()->get('did');
		$this->beginDate=request()->post('beginDate',date('Y-m-d',strtotime('-1 month')));
		$this->endDate=request()->post('endDate',date('Y-m-d'));
		$show=new Show();
		$this->rows=$show->getPageList($this->userinfo['id'],$this->did,$this->beginDate,$this->endDate);
	}
	public function Qq()
	{
		
	}
	public function Phone()
	{
		
	}
}