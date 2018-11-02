<?php
function err($msg)
{
	global $debug;
	if($debug)
	{
		echo $msg;		
	}else{
		error_log($msg);
	}
	if (ob_get_contents()) ob_end_clean();
	exit;
}

function request()
{
	return Fw\Request::getSington();
}

function _router()
{
	global $rewrite;

	if(empty($rewrite))return;

	if((!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == "https") || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)){
		$GLOBALS['http_scheme'] = 'https://';
	}else{
		$GLOBALS['http_scheme'] = 'http://';
	}

	if(!empty($rewrite)){
		foreach($rewrite as $rule => $mapper){
			if('/' == $rule)$rule = '/$';
			if(0!==stripos($rule, $GLOBALS['http_scheme']))
				$rule = $GLOBALS['http_scheme'].$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/\\') .'/'.$rule;
				$rule = '/'.str_ireplace(array('\\\\', $GLOBALS['http_scheme'], '/', '<', '>',  '.'), 
				array('', '', '\/', '(?P<', '>\w+)', '\.'), $rule).'/i';
			if(preg_match($rule, $GLOBALS['http_scheme'].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $matchs)){
				$route = explode("/", $mapper);
				if(isset($route[2])){
					list($_GET['m'], $_GET['c'], $_GET['a']) = $route;
				}else{
					list($_GET['c'], $_GET['a']) = $route;
				}
				foreach($matchs as $matchkey => $matchval){
					if(!is_int($matchkey))$_GET[$matchkey] = $matchval;
				}
				break;
			}
		}
	}

}

function _run()
{
	global $m,$c,$a,$moduleList;
	$m=isset($_GET['m'])?$_GET['m']:'Home';
	$c=isset($_GET['c'])?$_GET['c']:'Main';
	$a=isset($_GET['a'])?$_GET['a']:'Index';
	if(!in_array($m, $moduleList))
	{
		header('HTTP/1.0 404 Not Found');
		exit;
	}
	$Controller="{$m}\\Controller\\{$c}Controller";
	$Controller=new $Controller();
	$Controller->$a();	
	//自动执行
	if($Controller->_auto_display&&file_exists(MAIN_DIR.DS.'App'.DS.$m.DS.'View'.DS.$c.DS.$a.'.html'))$Controller->display();
}

function _loader($className)
{
	$className=str_replace("\\", DS, $className);
	$path=MAIN_DIR.DS.$className.'.php';
	if(file_exists($path))
	{
		require($path);
	}else{
		$path=MAIN_DIR.DS.'App'.DS.$className.'.php';
		if(file_exists($path))
		{
			require($path);
		}
	}
}

function url($c = 'main', $a = 'index', $param = array()){
	global $rewrite;
	if(is_array($c)){
		$param = $c;
		$c = $param['c']; unset($param['c']);
		$a = $param['a']; unset($param['a']);
	}
	$params = empty($param) ? '' : '&'.http_build_query($param);
	if(strpos($c, '/') !== false){
		list($m, $c) = explode('/', $c);
		$route = "$m/$c/$a";
		$url = $_SERVER["SCRIPT_NAME"]."?m=$m&c=$c&a=$a$params";
	}else{
		$m = '';
		$route = "$c/$a";
		$url = $_SERVER["SCRIPT_NAME"]."?c=$c&a=$a$params";
	}

	if(!empty($rewrite)){
		if(!isset($GLOBALS['url_array_instances'][$url])){
			foreach($rewrite as $rule => $mapper){
				$mapper = '/^'.str_ireplace(array('/', '<a>', '<c>', '<m>'),
					array('\/', '(?P<a>\w+)', '(?P<c>\w+)', '(?P<m>\w+)'), $mapper).'/i';
				if(preg_match($mapper, $route, $matchs)){
					$GLOBALS['url_array_instances'][$url] = str_ireplace(array('<a>', '<c>', '<m>'), array($a, $c, $m), $rule);
					if(!empty($param)){
						$_args = array();
						foreach($param as $argkey => $arg){
							$count = 0;
							$GLOBALS['url_array_instances'][$url] = str_ireplace('<'.$argkey.'>', $arg, $GLOBALS['url_array_instances'][$url], $count);
							if(!$count)$_args[$argkey] = $arg;
						}
						$GLOBALS['url_array_instances'][$url] = preg_replace('/<\w+>/', '', $GLOBALS['url_array_instances'][$url]).
							(!empty($_args) ? '?'.http_build_query($_args) : '');
					}
					
					if(0!==stripos($GLOBALS['url_array_instances'][$url], $GLOBALS['http_scheme'])) 
						$GLOBALS['url_array_instances'][$url] = $GLOBALS['http_scheme'].$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/\\') .'/'.$GLOBALS['url_array_instances'][$url];
					$rule = str_ireplace(array('<m>', '<c>', '<a>'), '', $rule);
					if(count($param) == preg_match_all('/<\w+>/is', $rule, $_match)){
						return $GLOBALS['url_array_instances'][$url];
					}
					//break;
				}
			}
			return isset($GLOBALS['url_array_instances'][$url]) ? $GLOBALS['url_array_instances'][$url] : $url;
		}
		return $GLOBALS['url_array_instances'][$url];
	}
	return $url;
}