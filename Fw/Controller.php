<?php
namespace Fw;
class Controller{
	public $layout;
	public $_auto_display = true;
	protected $_v;
	private $_data = array();
	public function init(){}
	public function __construct(){$this->init();}
	public function &__get($name){return $this->_data[$name];}
	public function __set($name, $value){$this->_data[$name] = $value;}
	
	public function display($tpl_name=null, $return = false){ 
		if(!$this->_v){ 
			global $m;
			$this->_v = new View(MAIN_DIR.DS.'App'.DS.$m.DS.'View', TMP_DIR);
		}
		$this->_v->assign(get_object_vars($this));
		$this->_v->assign($this->_data);
		if(empty($tpl_name))
		{
			global $c,$a;
			$tpl_name=$c.DS.$a.'.html';
		}
		if($this->layout){
			$this->_v->assign('__template_file', $tpl_name);
			$tpl_name = $this->layout;
		}
		$this->_auto_display = false;
		
		if($return){
			return $this->_v->render($tpl_name);
		}else{
			echo $this->_v->render($tpl_name);
		}
	}
}