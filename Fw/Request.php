<?php
namespace Fw;
class Request
{
	private static $sington;
	public static function getSington()
	{
		if(empty(Request::$sington))Request::$sington=new Request();
		return Request::$sington;
	}
	public function get($key,$default=null,$trim=false)
	{
		return isset($_GET[$key])?($trim?trim($_GET[$key]):$_GET[$key]):$default;
	}	
	public function post($key,$default=null,$trim=false)
	{
		return isset($_POST[$key])?($trim?trim($_POST[$key]):$_POST[$key]):$default;
	}
	public function arg($key,$default=null,$trim=false)
	{
		$value=$this->get($key,$default,$trim);
		if(empty($value))$valu=$this->post($key,$default,$trim);
		return $value;
	}
	public function isPost()
	{
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
	public function isAjax()
	{
		return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest";
	}

	public function ip()
	{
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$ip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
	}

	public function host()
	{
		return $_SERVER['HTTP_HOST'];
	}
}