<?php
if(!defined("in_forum"))exit;
//this page is freky. if wee are not in admin have wee only one function else a class!.

$admin_access = array(
		"admin_tool" => '1',
		'front_page' => '2',
		'forum'      => '3',
		'user'       => '4',
		'admin'      => '5',
		);

function may_visit_admin($gruppe_id = null, $IsZerro = false){
	global $db,$user,$admin_access;
	if($IsZerro)$gruppe_id = "0";
	elseif(!$gruppe_id)$gruppe_id = $user->data['gruppe_id'];
	$sql_array = array(
			"SELECT `id` FROM ".admin_access." WHERE a_id=",
			"?".$admin_access['admin_tool'],
			"AND g_id=",
			"?".$gruppe_id,
			);
	$sql = $db->get_sql_query($db->clean_sql($sql_array));
	$row = $db->return_array($sql);
	$db->free_result($sql);
	return empty($row['id']) ? false : true;
}

if(defined("in_admin")){
	class Admin{
		private $access;
		private $db;
		private $user;
		private $setting;
		private $style;
		
        function __construct(){
        	global $admin_access,$db,$user,$setting,$style;
        	$this->access  = $admin_access;
        	$this->db      = $db;
        	$this->user    = $user;
        	$this->setting = $setting;
        	$this->style   = $style;
        }
        
        function may_see_this_tool($page = null,$GrupId = null){
        	if($page == null){
        		if(empty($_GET['page']))return false;
        		else $page = $_GET['page'];
        	}
        	if(!$GrupId)$GrupId = $this->user->data['gruppe_id'];
        	
        	if(empty($this->access[$page]))return false;
        	
        	$sql_array = array(
        			"SELECT `id` FROM ".admin_access." WHERE g_id=",
        			"?".$GrupId,
        			"AND a_id=",
        			"?".$this->access[$page],
        			);
        	
        	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
        	$row = $this->db->return_array($sql);
        	$this->db->free_result($sql);
        	
        	return (empty($row['id'])) ? false : true;
        }
        
        function get_first_tool_i_may_see(){
        	$access = $this->access;
        	$go_too = null;
        	unset($access['admin_tool']);
        	foreach ($access as $tag => $value){
        		$new_page = $this->may_see_this_tool($tag);
        		if($new_page){
        			$go_too = $tag;
        			break;
        		}
        	}
        	
           return $go_too;
        	
        }
        
        function is_ther_update(){
        	$file_too_load = "http://battelkamp.dk/update/update.php?my_vision=".urlencode($this->setting->data['VERISION']);
        	$file_value = file_get_contents($file_too_load);
        	if(empty($file_value))return false;
        	
        	$xml =new SimpleXMLElement( $file_value );
        	
            $this->style->set_if("update", ($xml->is_update == "yes"));
            $this->style->set_if("UpdateError", ($xml->is_update == "error"));
        }
        
        
        function GetState(){

        	//get number of users 
        	$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".user."` WHERE `status`='".UserValid."'");
        	$this->style->set("UserCount", $this->db->return_from_count($sql));
        	$this->db->free_result($sql);
        	
        	//get number og geust
        	$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".geaust."`");
        	$this->style->set("GeustCount", $this->db->return_from_count($sql));
        	$this->db->free_result($sql);
        	
        	//get forum
        	$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".forum."`");
        	$this->style->set("ForumCount", $this->db->return_from_count($sql));
        	$this->db->free_result($sql);
        	
        	//get katolori
        	$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".katolori."`");
        	$this->style->set("KatCount", $this->db->return_from_count($sql));
        	$this->db->free_result($sql);
        	
        }
        
        function createBred($f_id){
        	$bred = array();
        	
        	global $lang;
        	$l = $lang;
        	$lang = $l->load_file(array("bred.php"));
        	
        	if($f_id != 0){
        		//vi starter med at finde navnet på denne katolori ;)
        		$sql_array = array(
        				"SELECT `id`,`name`,`place` FROM `".forum."` WHERE `id`=",
        				"?".$f_id,
        		);
        		
        		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
        		$row = $this->db->return_array($sql);
        		$this->db->free_result($sql);
        		
        		$bred[] = array(
        				'url' => '?page=forum&amp;forum='.$row['id'],
        				'name' => $row['name'],
        		);
        		
        		$f_id = $row['place'];
        		
        		$isForum = false;
        		$forset  = true;
        		
        		while ($forset){
        			if($isForum){
        				$sql_array = array(
        					    "SELECT `id`,`name`,`place` FROM `".forum."` WHERE `id`=",
        						"?".$f_id,	
        				);
        				$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
        				$row = $this->db->return_array($sql);
        				$this->db->free_result($sql);
        				
        				$bred[] = array(
        						'url'  => '?page=forum&amp;forum='.$f_id,
        						'name' => $row['name'],
        				);
        				
        				$f_id = $row['place'];
        				$isForum = false;
        			}else{
        				$sql_array = array(
        						"SELECT `id`,`place` FROM `".katolori."` WHERE `id`=",
        						"?".$f_id,
        				);
        				
        				$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
        				$row = $this->db->return_array($sql);
        				$this->db->free_result($sql);
        				
        				$isForum = true;
        				$f_id = $row['place'];
        				
        				if($row['place'] == 0){
        					$forset = false;
        				}
        			}
        		}
        	}
        	
        	$bred[] = array(
        			'url'  => '?page=forum',
        			'name' => $lang['front'],
        	);
        	
        	if(!empty($bred)){
        		for($i=count($bred)-1;$i>-1;$i--)$this->style->set_for("bred", $bred[$i]);
        	}
        }
	}
}