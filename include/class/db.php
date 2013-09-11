<?php
if(!defined("in_forum"))exit;

Class Db{
	private static $head;

	private $sql_build = array();
	private $sql_build_id;
	
	public function conect_db($host,$user,$pass,$data){
		self::$head = mysql_connect($host, $user, $pass);
		if(self::$head && defined("show_error"))mysql_error(self::$head);
		mysql_select_db($data,self::$head);
		mysql_query("set names 'utf8'");
	}
	
	public function get_sql_query($sql){
		$sql_query = mysql_query($sql) OR die(mysql_error());
		return $sql_query;
	}
	
	public function return_array($sql){
		$d = mysql_fetch_array($sql);
		return $d;
	}
	
	public function return_from_count($sql){
		$d = mysql_result($sql,0);
		return $d;
	}
	
	public function free_result($sql){
		mysql_free_result($sql);
	}
	
	public function clean_sql($sql_array){
		$sql_builder = null;
		for($i=0;$i<count($sql_array);$i++){
			if(preg_match("/^\?$/is", $sql_array[$i])){
				$sql_builder .= "'0'";
			}elseif(preg_match("/^\?(.*?)$/is", $sql_array[$i],$reg)){
				$sql_builder .= "'".$this->clean($reg[1])."'";
			}else{
				$sql_builder .= $sql_array[$i];
			}
		}
		
		return $sql_builder;
	}
	
	public function last_inset_id(){
		return mysql_insert_id();
	}
	
	/**
	 * 
	 * @param string $table table name.
	 * @param array $data array whit keys = table name and value = that data you will insert :)
	 * @return function $Db->last_insert_id();
	 */
	public function Insert($table,$data){
		if(!is_array($data)){
			return false;
		}
		
		foreach ($data as $tag => $key){
			$data[$tag] = $this->clean($key);
		}
		
		$this->get_sql_query("INSERT INTO `".$table."` (`".implode("`,`", array_keys($data))."`) VALUES ('".implode("','", array_values($data))."')");
		return $this->last_inset_id();
	}
	
	public function Delete($table,$data){
		$use = "DELETE FROM `".$table."` WHERE ";
		$num = 0;
		foreach ($data AS $rowName => $rowValue){
			$use .= ($num != 0 ? 'AND ' : null)."`".$rowName."`='".$this->clean($rowValue)."' ";
			$num++;
		}
		$this->get_sql_query($use);
	}
	
	private function clean($tekst){
	   $clean = mysql_real_escape_string($tekst);
	   return $clean;
	}
	
}