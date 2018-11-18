<?php
define('MAIN_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR.'..');
define('I','/i');
$debug=true;
$moduleList=['Home','Admin','User','Common'];
$defaultModule='Home';
require(MAIN_DIR.'/Fw/Init.php');
