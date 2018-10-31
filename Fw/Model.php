<?php
namespace Fw;
class Model{
	public static $DB_CONFIG;
	public $page;
	public $table_name;
	
	private $sql = array();
	
	public function __construct($table_name = null){if($table_name)$this->table_name = $table_name;}
	public function findAll($conditions = array(), $sort = null, $fields = '*', $limit = null){
		$sort = !empty($sort) ? ' ORDER BY '.$sort : '';
		$conditions = $this->_where($conditions);
		$sql = ' FROM '.$this->table_name.$conditions["_where"];
		if(is_array($limit)){
			$total = $this->query('SELECT COUNT(*) as M_COUNTER '.$sql, $conditions["_bindParams"]);
			if(!isset($total[0]['M_COUNTER']) || $total[0]['M_COUNTER'] == 0)return array();
			
			$limit = $limit + array(1, 10, 10);
			$limit = $this->pager($limit[0], $limit[1], $limit[2], $total[0]['M_COUNTER']);
			$limit = empty($limit) ? '' : ' LIMIT '.$limit['offset'].','.$limit['limit'];			
		}else{
			$limit = !empty($limit) ? ' LIMIT '.$limit : '';
		}
		return $this->query('SELECT '. $fields . $sql . $sort . $limit, $conditions["_bindParams"]);
	}
	
	public function find($conditions = array(), $sort = null, $fields = '*'){
		$res = $this->findAll($conditions, $sort, $fields, 1);
		return !empty($res) ? array_pop($res) : false;
	}
	
	public function update($conditions, $row){
		$values = array();
		foreach ($row as $k=>$v){
			$values[":M_UPDATE_".$k] = $v;
			$setstr[] = "`{$k}` = ".":M_UPDATE_".$k;
		}
		$conditions = $this->_where( $conditions );
		return $this->execute("UPDATE ".$this->table_name." SET ".implode(', ', $setstr).$conditions["_where"], $conditions["_bindParams"] + $values);
	}
	public function incr($conditions, $field, $optval = 1){
		$conditions = $this->_where( $conditions );
		return $this->execute("UPDATE ".$this->table_name." SET `{$field}` = `{$field}` + :M_INCR_VAL ".$conditions["_where"], $conditions["_bindParams"] + array(":M_INCR_VAL" => $optval));
	}
	public function decr($conditions, $field, $optval = 1){return $this->incr($conditions, $field, - $optval);}
	
	public function delete($conditions){
		$conditions = $this->_where( $conditions );
		return $this->execute("DELETE FROM ".$this->table_name.$conditions["_where"], $conditions["_bindParams"]);
	}
	
	public function create($row){
		$values = array();
		foreach($row as $k=>$v){
			$keys[] = "`{$k}`"; $values[":".$k] = $v; $marks[] = ":".$k;
		}
		$this->execute("INSERT INTO ".$this->table_name." (".implode(', ', $keys).") VALUES (".implode(', ', $marks).")", $values);
		return $this->dbInstance(self::$DB_CONFIG, 'master')->lastInsertId();
	}
	
	public function findCount($conditions){
		$conditions = $this->_where( $conditions );
		$count = $this->query("SELECT COUNT(*) AS M_COUNTER FROM ".$this->table_name.$conditions["_where"], $conditions["_bindParams"]);
		return isset($count[0]['M_COUNTER']) && $count[0]['M_COUNTER'] ? $count[0]['M_COUNTER'] : 0;
	}
	
	public function dumpSql(){return $this->sql;}
	
	public function pager($page, $pageSize = 10, $scope = 10, $total){
		$this->page = null;
		if($total > $pageSize){
			$total_page = ceil($total / $pageSize);
			$page = min(intval(max($page, 1)), $total_page);
			$this->page = array(
				'total_count' => $total, 
				'page_size'   => $pageSize,
				'total_page'  => $total_page,
				'first_page'  => 1,
				'prev_page'   => ( ( 1 == $page ) ? 1 : ($page - 1) ),
				'next_page'   => ( ( $page == $total_page ) ? $total_page : ($page + 1)),
				'last_page'   => $total_page,
				'current_page'=> $page,
				'all_pages'   => array(),
				'offset'      => ($page - 1) * $pageSize,
				'limit'       => $pageSize,
			);
			$scope = (int)$scope;
			if($total_page <= $scope ){
				$this->page['all_pages'] = range(1, $total_page);
			}elseif( $page <= $scope/2) {
				$this->page['all_pages'] = range(1, $scope);
			}elseif( $page <= $total_page - $scope/2 ){
				$right = $page + (int)($scope/2);
				$this->page['all_pages'] = range($right-$scope+1, $right);
			}else{
				$this->page['all_pages'] = range($total_page-$scope+1, $total_page);
			}
		}
		return $this->page;
	}
	
	public function query($sql, $params = array()){return $this->execute($sql, $params, true);}
	public function execute($sql, $params = array(), $readonly = false){
		$this->sql[] = $sql;
		if($readonly && !empty($GLOBALS['mysql']['MYSQL_SLAVE'])){
			$slave_key = array_rand($GLOBALS['mysql']['MYSQL_SLAVE']);
			$sth = $this->dbInstance($GLOBALS['mysql']['MYSQL_SLAVE'][$slave_key], 'slave_'.$slave_key)->prepare($sql);
		}else{
			$sth = $this->dbInstance(self::$DB_CONFIG, 'master')->prepare($sql);
		}
		
		if(is_array($params) && !empty($params)){
			foreach($params as $k => &$v){
				if(is_int($v)){
					$data_type = PDO::PARAM_INT;
				}elseif(is_bool($v)){
					$data_type = PDO::PARAM_BOOL;
				}elseif(is_null($v)){
					$data_type = PDO::PARAM_NULL;
				}else{
					$data_type = PDO::PARAM_STR;
				}
				$sth->bindParam($k, $v, $data_type);
			}
		}
		if($sth->execute())return $readonly ? $sth->fetchAll(PDO::FETCH_ASSOC) : $sth->rowCount();
		$err = $sth->errorInfo();
		err('Database SQL: "' . $sql. '", ErrorInfo: '. $err[2], 1);
	}
	
	public function dbInstance($db_config, $db_config_key, $force_replace = false){
		if($force_replace || empty($GLOBALS['mysql_instances'][$db_config_key])){
			try {
				if(!class_exists("PDO") || !in_array("mysql",PDO::getAvailableDrivers(), true)){
					err('Database Err: PDO or PDO_MYSQL doesn\'t exist!');
				}
				$GLOBALS['mysql_instances'][$db_config_key] = new PDO('mysql:dbname='.$db_config['MYSQL_DB'].';host='.$db_config['MYSQL_HOST'].';port='.$db_config['MYSQL_PORT'], $db_config['MYSQL_USER'], $db_config['MYSQL_PASS'], array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \''.$db_config['MYSQL_CHARSET'].'\''));
			}catch(PDOException $e){err('Database Err: '.$e->getMessage());}
		}
		return $GLOBALS['mysql_instances'][$db_config_key];
	}
	
	private function _where($conditions){
		$result = array( "_where" => " ","_bindParams" => array());
		if(is_array($conditions) && !empty($conditions)){
			$fieldss = array(); $sql = null; $join = array();
			if(isset($conditions[0]) && $sql = $conditions[0]) unset($conditions[0]);
			foreach( $conditions as $key => $condition ){
				if(substr($key, 0, 1) != ":"){
					unset($conditions[$key]);
					$conditions[":".$key] = $condition;
				}
				$join[] = "`{$key}` = :{$key}";
			}
			if(!$sql) $sql = join(" AND ",$join);
			$result["_where"] = " WHERE ". $sql;
			$result["_bindParams"] = $conditions;
		}
		return $result;
	}
}
