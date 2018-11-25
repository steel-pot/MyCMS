<?php
$uri=$_SERVER['REQUEST_URI'];
$url=$uri;
if(strpos($uri,'.')===false)
{
	//if(empty($uri)||$uri=='/')$uri.='index';
	if(substr($uri,-1)!='/')$uri.='/';
	$uri=$uri.'index.html';
}
//获取最后一节
$arr=explode('/',$uri);
$file=$arr[count($arr)-1];

$dir=str_replace($file,'',$uri);
$dir=dirname(__FILE__).$dir;
$dir=str_replace("/","\\",$dir);
 
if(!file_exists($dir))
{
	mkdir($dir,0777,true);
} 

$file=explode('?',$file);
$file=$file[0];

$post=null;
if($_SERVER['REQUEST_METHOD']=="POST")
{
	$post=$_POST;
}

$html=request("http://pic.dibaqu.com".$url,$post);

if(empty($post))
file_put_contents($dir.$file,$html);

echo $html;
function request($url = '',$post=null) {   
	  $ch = curl_init();
	//设置抓取的url
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36');
	
	curl_setopt($ch, CURLOPT_HEADER,0);  //不要header
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //302
	curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt'); 
	curl_setopt($ch, CURLOPT_SSLVERSION, 1);
	//设置获取的信息以文件流的形式返回，而不是直接输出。
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   
	if(!empty($post))
	{
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	//执行命令
	$data = curl_exec($ch);
	//echo curl_error($ch);
	//关闭URL请求
	curl_close($ch);
	//显示获得的数据
	return $data;
}