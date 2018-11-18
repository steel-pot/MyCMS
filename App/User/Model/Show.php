<?php
namespace User\Model;
use Fw\Model;
class Show
{
	public function getList($uid,$did,$begin,$end)
	{
		$tab=new Model('ct_domain');
		if($tab->findCount(['id'=>$did,'uid'=>$uid])<1)return array();
		$tab=new Model('ct_domain_day');
	 
		return $tab->findAll(['did=:did AND day>=:begin AND day<=:end',':did'=>$did,':begin'=>$begin,':end'=>$end.' 23:59:59'],'id DESC');
	}
	 public function getFromList($uid,$did,$begin,$end)
	{ 
		$tab=new Model('ct_domain_click');	 
		return $tab->query('SELECT from_host,COUNT(1)c FROM ct_domain_click WHERE did=:did  AND created>=:begin AND created<=:end GROUP BY from_host ORDER BY c DESC',[':did'=>$did,':begin'=>$begin,':end'=>$end.' 23:59:59']);
	}
	
	public function getPageList($uid,$did,$begin,$end)
	{ 
		$tab=new Model('ct_domain_click');	 
		return $tab->query('SELECT page_url,COUNT(1)c FROM ct_domain_click WHERE did=:did  AND created>=:begin AND created<=:end GROUP BY page_url ORDER BY c DESC',[':did'=>$did,':begin'=>$begin,':end'=>$end.' 23:59:59']);
	}
}