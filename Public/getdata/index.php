<?php 

//$url='http://m.baifa001.com'; 
//$mycurl->createCurl($url);
 
//登陆
//$url='http://m.baifa001.com/tools/ssc_ajax.ashx?A=Login&S=baifa&U=wuyywww';
//$mycurl->setPost(http_build_query(['Action'=>'Login','Password'=>'4e0a4117c53bc806e33084ed6c843cb6','SourceName'=>'MB','Type'=>'Hash','UserName'=>'wuyywww']));
//$mycurl->createCurl($url);
//echo $mycurl;

require('Core/Init.php');
$tab=new Model('game_list');
$rows=$tab->findAll();
$tab=null;
while (true) {
	getData();
	disconn();
	echo "wait 5  \r\n";
	sleep(5);	
	//header('Location:/getdata');
	//echo "<script>location.reload();</script>";
	 
}


function handleData($row,$dat,$type)
{ 
	$tabPlan=new Model('game_plan');
	$tabTranche=new Model('game_tranche');
	
	$IssueNo=$dat['IssueNo'];
	$LotteryOpen=$dat['LotteryOpen'];
	$game_type=$row['game_type'];
	$gid=$row['gid'];  
	//分期检查
	$rowTranche=$tabTranche->find(array('IssueNo'=>$IssueNo,'type'=>$type,'gid'=>$gid));
	$lastIssueNo='';
	if($rowTranche)
	{
		$lastIssueNo=$tabTranche->find(array('title'=>$rowTranche['title'],'type'=>$type,'gid'=>$gid),'id DESC','IssueNo');
		$title=$rowTranche['title'];
		$plan=$rowTranche['plan'];
	}else{
		//如果不存在，创建新的分期	
		$plan=mt_rand(0,1);$plan=mt_rand(0,1);
		$title=getTitle($IssueNo,$game_type); 
		
		$id=$tabTranche->create(array('title'=>$title,'IssueNo'=>$IssueNo,'type'=>$type,'gid'=>$gid,'plan'=>$plan));
		$rowTranche=$tabTranche->find(array('id'=>$id));
		
		$tabTranche->create(array('title'=>$title,'IssueNo'=>strval($IssueNo+1),'type'=>$type,'gid'=>$gid,'plan'=>$plan));
		$tabTranche->create(array('title'=>$title,'IssueNo'=>strval($IssueNo+2),'type'=>$type,'gid'=>$gid,'plan'=>$plan));
		
		$tabPlan->delete(array('IssueNo'=>$IssueNo,'type'=>$type,'gid'=>$gid));
		$tabPlan->delete(array('IssueNo'=>strval($IssueNo+1),'type'=>$type,'gid'=>$gid));
		$tabPlan->delete(array('IssueNo'=>strval($IssueNo+2),'type'=>$type,'gid'=>$gid));
	} 
	
	//创建计划
	$rowPlan=$tabPlan->find(array('IssueNo'=>$IssueNo,'type'=>$type,'gid'=>$gid));		
	if(!$rowPlan)
	{
		$tabPlan->create(array('title'=>$title,'gid'=>$gid,'IssueNo'=>$IssueNo,'type'=>$type,'Multiple'=>mt_rand(3,5),'plan'=>$plan));
		$rowPlan=$tabPlan->find(array('IssueNo'=>$IssueNo,'type'=>$type,'gid'=>$gid));
	}
	//开奖
	$num=kj($row['type'],$LotteryOpen,$type); 
	//file_put_contents('llll.log',$IssueNo.'-------'.$game_type.'----'.$LotteryOpen.'---'.$type.'---'.$num,FILE_APPEND);
	$result=$num==$rowPlan['plan']?1:2;
	$tabPlan->update(array('id'=>$rowPlan['id']),array('result'=>$result));
	
	//创建下一个计划
	//1如果中奖，创建新的三期计划,删除大于当前计划的同期
	
	$newIssueNo=strval($IssueNo+1);
	
	if($result==1)
	{
		$newIssueNo1=strval($IssueNo+2);
		$newIssueNo2=strval($IssueNo+3);
		//$tabTranche->delete(array('id>:id AND type=:type AND gid=:gid AND title=:title',':id'=>$rowTranche['id'],':type'=>$type,':gid'=>$gid,':title'=>$title));
		//创建新的三期计划
		$plan=mt_rand(0,1);$plan=mt_rand(0,1);
		$title=getTitle($newIssueNo,$game_type); 
		$tabTranche->delete(array('type'=>$type,'gid'=>$gid,'IssueNo'=>$newIssueNo));
		$tabTranche->create(array('title'=>$title,'IssueNo'=>$newIssueNo,'type'=>$type,'gid'=>$gid,'plan'=>$plan));
		
		$tabTranche->delete(array('type'=>$type,'gid'=>$gid,'IssueNo'=>$newIssueNo1));
		$tabTranche->create(array('title'=>$title,'IssueNo'=>$newIssueNo1,'type'=>$type,'gid'=>$gid,'plan'=>$plan));
		
		$tabTranche->delete(array('type'=>$type,'gid'=>$gid,'IssueNo'=>$newIssueNo2));
		$tabTranche->create(array('title'=>$title,'IssueNo'=>$newIssueNo2,'type'=>$type,'gid'=>$gid,'plan'=>$plan));
	} 
	//创建新计划
	$tabPlan->create(array('title'=>$title,'gid'=>$gid,'IssueNo'=>$newIssueNo,'type'=>$type,'Multiple'=>mt_rand(3,5),'plan'=>$plan));  
}
function getData()
{
	$tab=new Model('game_open');
	
	global $rows;
	foreach($rows as $row)
	{
		$data=getBaifa($row['gid']);
		$data=json_decode($data,true);
		if($data['Code']!=1)
		{
			outlog($data['StrCode']);
			exit;
		}	 
		
		$BackData=$data['BackData'];
		$l=count($BackData);
		for($i=$l-1;$i>-1;$i--)
		{
			$dat=$BackData[$i];
			if(!$tab->find(array('gid'=>$row['gid'],'IssueNo'=>$dat['IssueNo'])))
			{
				$tab->create(array('gid'=>$row['gid'],'IssueNo'=>$dat['IssueNo'],'LotteryOpen'=>$dat['LotteryOpen'],'OpenTime'=>$dat['OpenTime']));
				handleData($row,$dat,0);
				handleData($row,$dat,1);
			}			
		} 
	}
}

function getTitle($IssueNo,$game_type)
{
	$IssueNo=trim($IssueNo); 
	$num=getNum($IssueNo, $game_type);
	$c=getLen($game_type);
	$num='1000000'.$num;
	$num=substr($num,-$c);	
	
	$num1=strval($num+2);
	$num1='1000000'.$num1;
	$num1=substr($num1,-$c);	
	return $num.'-'.($num1).'期';
}

function kj($game_type,$LotteryOpen,$type)
{
	$num=getCNum($LotteryOpen,$game_type);
	return checkRs($num,$game_type,$type); 
} 
 

function getLen($game_type)
{
	return in_array($game_type,array('CQSSC','PK10','BJK3'))?3:(in_array($game_type,array('DFSSC','DFPK10','BJK3'))?4:2);
}
function getNum($IssueNo,$game_type)
{ 
	$c=getLen($game_type);
	return substr($IssueNo,-$c); 
} 

function getCNum($data,$type)
{
	$data=explode(',',$data);
	$num=0;	
	switch($type)
	{
		case 0://合值	
			foreach($data as $f)
			{
				$num+=$f;
			}				
		break;
		case 1://万位
			$num=$data[0];
		break; 
		case 2://冠军
			$num=$data[0];
		break;
	}
	return $num;
}

function checkRs($num,$type,$typePlan)
{
	switch($type)
	{
		case 0:
			if($typePlan==0) //0 大小   1 单双   0大单 1小双
			{
				$num=$num<11?1:0;  //小于11 小	
			}else{
				$num=$num%2!=0?0:1; //不能被2整除  单   
			}
			
		break;
		case 1:
			if($typePlan==0)
			{
				$num=$num<5?1:0;	 //小于5 小	
			}else{
				$num=$num%2!=0?0:1;  
			}
		break;
		case 2:
			if($typePlan==0)
			{
				$num=$num<6?1:0;	
			}else{
				$num=$num%2!=0?0:1;
			}
		break;
	}
	return $num;
}



function getBaifa($gid)
{
	$url='http://m.baifa001.com/tools/ssc_ajax.ashx?A=GetLotteryOpen&S=baifa&U=wuyywww';
	$ch = curl_init(); 
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_COOKIEFILE =>'cookie.txt', 
		CURLOPT_COOKIEJAR => 'cookie.txt',
		CURLOPT_HTTPHEADER => array(
			'Host: m.baifa001.com',
			'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0',
			'Accept: */*',
			'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
			'Accept-Encoding: gzip, deflate',
			'content-type: application/x-www-form-urlencoded',
			'origin: http://m.baifa001.com',
			'Content-Length: 73',
			'Connection: keep-alive',
			'Cookie: C_SessionId=18c637c0-3362-4b3f-90de-d67a4414a445; IVK=b325349d',
			'Pragma: no-cache',
			'Cache-Control: no-cache'
		),
	));
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'Action=GetLotteryOpen&LotteryCode='.$gid.'&IssueNo=0&DataNum=10&SourceName=MB');
	$rs = curl_exec($ch);
	curl_close($ch);
	return $rs; 
}