<?php
namespace Fw;
class Session
{
	private static $sington;
	public static function getSington()
	{
		if(empty(Session::$sington))Session::$sington=new Session();
		return Session::$sington;
	}
	public function write($key,$val)
	{
		global $session_prefix;
		$_SESSION[$session_prefix.$key]=$val;
	} 
	public function read($key)
	{
		global $session_prefix;
		return isset($_SESSION[$session_prefix.$key])?$_SESSION[$session_prefix.$key]:null;
	} 
	public function delete($key)
	{
		global $session_prefix;
		unset($_SESSION[$session_prefix.$key]);
	}
	public function clean()
	{
 		session_destroy();
	}
}