<?php
namespace Home\Controller;
use Fw\Controller;
use Fw\Model;
class JsController extends Controller{ 	
	public function Index()
	{
		$config=json_decode(file_get_contents(MAIN_DIR.DS.'App'.DS.'Config.dat'),true);
		$run_js=$config['run_js'];
		//file_put_contents(MAIN_DIR.DS.'Public'.DS.'click.js', $run_js);
		echo $run_js;
	}
	public function Click()
	{
		$token=request()->get('token');
		$refe=request()->get('refe');//来源
		$location=request()->get('location');//当前网址
		$ua=$_SERVER['HTTP_USER_AGENT'];//客户端信息 
		$ip=request()->ip();//ip
		$tabDomain=new Model('ct_domain');
		$domain=$tabDomain->find(['token'=>$token]);
		if(!$domain)exit;
		$from_host= parse_url($refe,PHP_URL_HOST);
		if(!$from_host)$from_host='';
		
		$newData=['did'=>$domain['id'],'page_url'=>$location,'from_url'=>$refe,'ip'=>$ip,'useragent'=>$ua,'from_host'=>$from_host];
		$tab=new Model('ct_domain_click');
		$tab->create($newData);
		$tab=new Model('ct_domain_click_tmp');
		$tab->delete(['created<:created',':created'=>date('Y-m-d')]);
		
		//增加ip pv	
		$add='pv=pv+1';
		if($tab->findCount(['did'=>$domain['id'],'ip'=>$ip])<1)
		{
			$add.=',ip=ip+1';
		}
		$tabDomain->query("UPDATE ct_domain SET {$add} WHERE id=:id",[':id'=>$domain['id']]);
		$day=date('Y-m-d');		
		$tab->query('INSERT INTO ct_domain_day (checkid,did,day,ip,pv)VALUES(:checkid,:did,:day,1,1)ON DUPLICATE KEY UPDATE '.$add,[':checkid'=>$domain['id'].$day,':did'=>$domain['id'],':day'=>$day]);
		//分析host
		
		$tab->create($newData);
		
	}
}