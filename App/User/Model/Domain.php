<?php
namespace User\Model;
use Fw\Model;
class Domain
{
	public function getList($uid)
	{
		$tab=new Model('ct_domain');
		return $tab->findAll(['uid'=>$uid],'id DESC');
	}
	public function addDomain($uid,$name)
	{
		$tab=new Model('ct_domain');
		if($tab->findCount(['uid'=>$uid,'name'=>$name])>0)return '该域名已经存在！';
		$id=$tab->create(['uid'=>$uid,'name'=>$name]);
		$tab->update(['id'=>$id],['token'=>md5($id.'#$SDFSffdasdf')]);
		return '';
	}
	public function editDomain($uid,$newName,$token)
	{
		$tab=new Model('ct_domain');
		
		if($tab->findCount(['uid'=>$uid,'token'=>$token])<1)return '域名不存在';
		
		$row=$tab->find(['uid'=>$uid,'name'=>$newName]);
		if($row&&$row['token']!=$token)return '该域名已经存在！';
		$tab->update(['token'=>$token],['name'=>$newName]);
		return '';
	}
}