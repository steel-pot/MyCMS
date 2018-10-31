<?php
/***********************************************************
框架占用了几个字段 m  c   a
$_GET中不要使用这几个字段
另占用了几个变量名
global $m $c $a $rewrite
Router.php是路由配置，不需要可以删除
Helper.php中有一些助手函数

如果index.php不放在public目录，注意目录安全问题
Libs 里面放php插件
************************************************************/
define('MAIN_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR.'..');
$debug=true;
//可访问模块列表
$moduleList=['Home','Admin'];
require(MAIN_DIR.'/fw/Init.php');
