<?php
function loadJs($v='')
{
	global $m,$c,$a;
	if(file_exists(MAIN_DIR.DS.'Public'.DS.'i'.DS.$m.DS.'Js'.DS.$c.DS.$a.'.js'))
	{
		$rs='/i/'.$m.DS.'Js'.DS.$c.DS.$a.'.js?_v='.$v;
		return '<script src="'.$rs.'"></script>';
	}
	return '';
}