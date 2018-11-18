<?php
namespace Admin\Controller; 
use Fw\Model;
class MainController extends BaseController
{
	public function Index()
	{
		$this->layout=null;
		//$tab=new Model('game_list');
		//$this->rows=$tab->findAll();
	}

	public function Main()
	{
		
	}
	public function Plan()
	{
		
		$gid=request()->get('gid','1000');
		$type=request()->get('type','0');
		
		$tab=new Model('game_list');
		$this->gameData=$tab->find(array('gid'=>$gid));		
		
		$this->gid=$gid;
		$this->type=$type;
		$this->gameType=array('和值','万位','冠军');
		
	}
	public function PlanData()
	{
		$gid=request()->get('gid','1000');
		$type=request()->get('type','0');
		$page=request()->get('page','1');
		$limit=request()->get('limit','10');
		$limit=page($page,$limit);
		
		$tab=new Model('game_plan');
		$sql='SELECT plan.*,open.LotteryOpen FROM game_plan plan LEFT JOIN (SELECT * FROM game_open WHERE gid=:gid) open ON open.IssueNo=plan.IssueNo WHERE plan.gid=:gid AND plan.type=:type ORDER BY plan.id DESC LIMIT '.$limit;
		$rows=$tab->query($sql,array(':gid'=>$gid,':type'=>$type)); 
		$planList=$type==0?array('大','小'):array('单','双');
		$result=array('未开','中','挂');
		foreach($rows as $k=>$v)
		{
			$rows[$k]['plan']=$planList[$rows[$k]['plan']];
			$rows[$k]['result']=$result[$rows[$k]['result']];
		}
		$count=$tab->findCount(array('gid'=>$gid,'type'=>$type));
		$rs=array('code'=>0,'msg'=>'','count'=>$count,'data'=>$rows);
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode($rs); 
	}
	public function PlanAdd()
	{
		$title='';
		$msg='';
		$gid=request()->get('gid','1000');
		$type=request()->get('type','0');
		$tab=new Model('game_list');
		$this->gameData=$tab->find(array('gid'=>$gid));		
		
		if(request()->isPost())
		{
			$title=request()->post('title');
			$IssueNo=request()->post('IssueNo');
			$Multiple=request()->post('Multiple');
			$plan=request()->post('plan');
			$tab=new Model('game_plan');
			if(empty($title)&&empty($Multiple))
			{
				$msg='请输入完整数据！';
			}elseif(!empty($IssueNo)&& $tab->findCount(array('gid'=>$gid,'type'=>$type,'IssueNo'=>$IssueNo,'type'=>$type))>0){				  
				 $msg='数据已经存在！';
			}else{				
				$id=$tab->create(array('gid'=>$gid,'type'=>$type,'title'=>$title,'IssueNo'=>$IssueNo,'Multiple'=>$Multiple,'plan'=>$plan));
				$this->updatePlan($id,$gid,$IssueNo);
				$msg='添加成功，您可以继续提交！';
			}
		}
		$this->gid=$gid;
		$this->type=$type;
		$this->title=$title;
		$this->msg=$msg;
		$this->gameType=array('和值','万位','冠军');
	}
	public function PlanEdit()
	{
		$id=request()->get('id');
		$gid=request()->get('gid');
		$type=request()->get('type');
		$tab=new Model('game_list');
		$this->gameData=$tab->find(array('gid'=>$gid));		
		
		$tab=new Model('game_plan');
		
		if(request()->isPost())
		{
			$title=request()->post('title','',true);
			$IssueNo=request()->post('IssueNo','',true);
			$Multiple=request()->post('Multiple','',true);
			$plan=request()->post('plan','',true);
			$tab->update(array('id'=>$id),array('title'=>$title,'IssueNo'=>$IssueNo,'Multiple'=>$Multiple,'plan'=>$plan)); 
			$this->updatePlan($id,$gid,$IssueNo);
			exit; 
		}
		$this->row=$tab->find(array('id'=>$id));
		$this->gid=$gid;
		$this->type=$type;
		$this->gameType=array('和值','万位','冠军');
	}
	public function PlanDel()
	{
		$id=request()->get('id');
		$tab=new Model('game_plan');
		$tab->delete(array('id'=>$id));
	}
	//不管是添加还是修改都需要进行一次开奖
	private function updatePlan($id,$gid,$IssueNo)
	{
		$tab=new Model('game_open');
		$open=$tab->find(array('IssueNo'=>$IssueNo));		
		if(!$open)return ;
		$open=$open['LotteryOpen'];
		
		$tab=new Model('game_list');
		$game=$tab->find(array('gid'=>$gid));
		
		$tab=new Model('game_plan');
		$plan=$tab->find(array('id'=>$id));
		
		$num=0;
		switch($game['type'])
		{
			case 0://合值	
				foreach($open as $f)
				{
					$num+=$f;
				}

				if($plan['type']==0)
				{
					$num=$num<11?1:0;	
				}else{
					$num=$num%2!=0?0:1;
				}
			break;
			case 1://万位
				$num=$open[0];
				if($plan['type']==0)
				{
					$num=$num<5?1:0;	
				}else{
					$num=$num%2!=0?0:1;
				}
			break;
			case 2://冠军
				$num=$open[0];
				if($plan['type']==0)
				{
					$num=$num<6?1:0;	
				}else{
					$num=$num%2!=0?0:1;
				}
			break;
		}
		$result=$plan['plan']==$num?1:2;
		$tab->update(array('id'=>$id),array('result'=>$result)); 
		
	}
}