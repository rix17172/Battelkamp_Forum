<?php
if(!defined("in_admin")){
	exit;
}

class AdminKatolori{
	
	private $db;
	
    function __construct(){
    	$this->db = new Db();
    }
    
    public function createKat($name,$place = false){
    	if(!$place)$place = 0;
    	$insert = array(
    			'name'  => $name,
    			'place' => $place,
    	);
    	
    	return $this->db->Insert(katolori, $insert);
    }
    
    public function changeKatName($id,$toName){
    	$sql_array = array(
    			"UPDATE `".katolori."` SET `name`=",
    			"?".$toName,
    			"WHERE id=",
    			"?".$id,
    	);
    	$this->db->get_sql_query($this->db->clean_sql($sql_array));
    }
    
    public function maySee($grupId,$katId){
    	$sql_array = array(
    			"SELECT `id` FROM `".katolori_access."` WHERE k_id=",
    			"?".$katId,
    			"AND g_id=",
    			"?".$grupId
    	);
    	
    	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    	$row = $this->db->return_array($sql);
    	$this->db->free_result($sql);
    	
    	return (!empty($row['id']));
    }
    
    public function setAccess($grupId,$katId){
    	$insert = array(
    			'k_id' => $katId,
    			'g_id' => $grupId,
    	);
    	
    	$this->db->Insert(katolori_access, $insert);
    }
    
    public function deleteAccess($grupId,$katId){
    	$sql_array = array(
    			"DELETE FROM `".katolori_access."` WHERE `k_id`=",
    			"?".$katId,
    			"AND `g_id`=",
    			"?".$grupId,
    	);
    	$this->db->get_sql_query($this->db->clean_sql($sql_array));
    }
    
    public function deleteKatolori($id){
        $sql_array = array(
        		"DELETE FROM `".katolori_access."` WHERE `k_id`=",
        		"?".$id,
        );	
        
        $this->db->get_sql_query($this->db->clean_sql($sql_array));
        
        $sql_array = array(
        		"DELETE FROM `".katolori."` WHERE `id`=",
        		"?".$id,
        );
        
        $this->db->get_sql_query($this->db->clean_sql($sql_array));
    }
    
    public function deleteAll($id){
    	
    	$sql_array = array(
    			"SELECT * FROM `".forum."` WHERE `place`=",
    			"?".$id,
    	);
    	
    	if(!class_exists("AdminForum")){
    		IncludeExsternPage(first."include/class/adminForum.php");
    	}
    	
    	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    	while($row = $this->db->return_array($sql)){
    		$forum = new AdminForum($row['id']);
    		$forum->deleteAllforum();
    	}
    	
    	//sidst men ikke mindst:::
    	$this->deleteKatolori($id);
    }
    
    public function moveForumsToAndDeleteKat($thisId,$toId){
    	$sql_array = array(
    			"UPDATE `".forum."` SET place=",
    			"?".$toId,
    			"WHERE place=",
    			"?".$thisId,
    	);
    	
    	$this->db->get_sql_query($this->db->clean_sql($sql_array));
    	$this->deleteKatolori($thisId);
    }	
}