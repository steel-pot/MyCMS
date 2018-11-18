<?php
namespace User\Model;
use Fw\Model;
class Main{
	public static function getData($uid)
	{
		$today=date('Y-m-d');
		$last=date('Y-m-d',strtotime('-1 day'));
		$sql='SELECT dom.*,today.ip tip,today.pv tpv,last.ip lip,last.pv lpv FROM ct_domain dom LEFT JOIN (SELECT did,ip,pv FROM ct_domain_day WHERE day=:today)today ON today.did=dom.id LEFT JOIN (SELECT did,ip,pv FROM ct_domain_day WHERE day=:last)last ON last.did=dom.id WHERE dom.uid=:uid';
		$tab=new Model('');
		return $tab->query($sql,[':today'=>$today,':last'=>$last,':uid'=>$uid]);
	}
}