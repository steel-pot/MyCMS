<?
date_default_timezone_set('PRC');
define('DS',DIRECTORY_SEPARATOR);

ini_set('error_log','php_error.log');
error_reporting(E_ALL);
ini_set('display_errors','On');	

set_time_limit(0);


require('Model.php');
require('Helper.php');
require('Http.php');


Model::$DB_CONFIG=array(
				'MYSQL_HOST' => 'localhost',
				'MYSQL_PORT' => '3306',
				'MYSQL_USER' => 'plan',
				'MYSQL_DB'   => 'plan',
				'MYSQL_PASS' => 'sMQOEfoQQTXKVfHi',
				'MYSQL_CHARSET' => 'utf8',
		);
function err($err)
{
	die($err);
}
function helper()
{
	return Helper::getSington();
}
function disconn()
{
	if(!empty($GLOBALS['mysql_instances']))
	foreach ($GLOBALS['mysql_instances'] as $key => $value) {
		unset($GLOBALS['mysql_instances'][$key]);
	}
}
function outlog($log)
{	
	$log=date('Y-m-d H:i:s').'  '.(!is_array($log)?$log:var_export($log,true))."\r\n";
	echo $log;
	if(!file_exists('log'))mkdir('log');	
	file_put_contents('log/'.date('Ymd').'.log',$log ,FILE_APPEND);
}