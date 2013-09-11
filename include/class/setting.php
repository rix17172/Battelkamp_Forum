<?php
if(!defined("in_forum"))exit;

class Setting{
   private $db;
   
   public $data = array();
   
   function __construct(){
   	   global $db;
   	   $this->db = $db;
   }
   
   public function get_setting(){
   	 $sql = "SELECT `tag`,`value` FROM `".setting."`";
     $sql = $this->db->get_sql_query($sql);
     while($row = $this->db->return_array($sql)){
     	$this->data[$row['tag']] = $row['value'];
     }
   }
   
   public function update_setting($tag,$value){
   	   $array = array(
   	  		'UPDATE '.setting.' SET value=',
   	  		"?".$value,
   	  		" WHERE tag='".$tag."'",
   	  	);
   	   $this->db->get_sql_query($this->db->clean_sql($array));
   	   $this->data[$tag] = $value;
   }
   
   public function __get($get){
   	if(empty($this->data[$get])){
   		return null;
   	}
   	
   	return $this->data[$get];
   }
   
   public function __set($key,$value){
      $this->update_setting($key,$value);
   }
   
}