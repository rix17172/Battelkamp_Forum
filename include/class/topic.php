<?php
if(!defined("in_forum"))exit;

if(!class_exists("Forum")){
	require_once first.'include/class/forum.php';
}

Class topic extends Forum{
	
	public function get_topic_data($t){
		if(empty($t))return false;
		$sql_array = array(
				"SELECT * FROM ".topic_title." WHERE id=",
				"?".$t,
				);


		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		if(empty($row['id']))return false;
		else{
			if(!$this->access_topic($row['f_id'])) return false;
			return $row;
		}
	}
	
	public function may_answer($f_id){
		$sql_array = array(
				"SELECT `id` FROM ".forum_access." WHERE f_id=",
				"?".$f_id,
				"AND g_id=",
				"?".$this->user->data['gruppe_id'],
				"AND a_id=",
				"?".$this->access["svar_topic"],
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		return (empty($row['id'])) ? false : true;
	}
	
	public function get_topic($title_id){
		$topic_count = 1;
		
		$sql_array = array(
				"SELECT * FROM ".topic_title." WHERE id=",
				"?".$title_id,
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		require_once first.'include/function/bb.php';
		
		$this->style->set_for("topic", array(
				"id"       => $row['id'],
				"title"    => $row['title'],
				"from"     => $row['user_name'],
				"grup"     => $this->user->get_grup_name_by_userid($row['user_id']),
				"post"     => $this->user->get_post_num_by_id($row['user_id']),
				"count"    => $topic_count,
				"mess"     => bb($row['message']),
				"user_id"  => $row['user_id'],
				"is_user"  => $row['is_user'] == 1 ? true : false,
				"is_title" => "true",
				));
		$topic_count++;
		
		$sql_array = array(
				"SELECT * FROM ".topic_message." WHERE t_id=",
				"?".$row['id'],
				);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("topic", array(
					"id"       => $row['id'],
					"title"    => false,
					"from"     => $row['username'],
					"grup"     => $this->user->get_grup_name_by_userid($row['u_id']),
					"post"     => $this->user->get_post_num_by_id($row['u_id']),
					"count"    => $topic_count,
					"mess"     => bb($row['message']),
					"user_id"  => $row['u_id'],
					"is_user"  => $row['is_user'] == 1 ? true : false,
					"is_title" => "false",
			));
			$topic_count++;
		}
		
		
		$this->style->set_if("my_id", $this->user->data['id']);
	}
	
	public function save_visit($t_id){
		$befor = $this->has_befor_visit($t_id);
		$is_user = ($this->user->data['is_user']) ? 1 : 0;
		if($befor){
			$this->db->get_sql_query("UPDATE ".last_visist." SET time=".time()." WHERE id='".$befor."'");
		}else{
			$sql_array = array(
					"INSERT INTO ".last_visist." (is_user,u_id,t_id,time) VALUES (",
					"?".$is_user,
					",",
					"?".$this->user->data['id'],
					",",
					"?".$t_id,
					",'".time()."')",
					);
			$this->db->get_sql_query($this->db->clean_sql($sql_array));
		}
	}
	
    public function may_change_topic($id,$is_title=false){
    	$may_change = false;
    	$f_id       = 0;
    	if($is_title){
    		$sql_array = array(
    				"SELECT * FROM ".topic_title." WHERE id=",
    				"?".$id,
    				);
    		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    		$row = $this->db->return_array($sql);
    		$this->db->free_result($sql);
    		if(!empty($row['f_id']))$f_id = $row['f_id'];
    		else return false;
    		
    		if($row['is_user'] == 1 && $this->user->data['is_user'] && $row['user_id'] == $this->user->data['id'])$may_change = true;
    	}else{
    		$sql_array = array(
    				"SELECT * FROM ".topic_message." WHERE id=",
    				"?".$id,
    				);
    		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    		$row = $this->db->return_array($sql);
    		$this->db->free_result($sql);
    		
    		if(empty($row['id']))return false;
    		$data_title = $this->get_topic_data($row['t_id']);
    		
    		if($row['is_user'] == 1 && $this->user->data['is_user'] && $row['u_id'] == $this->user->data['id'])$may_change = true;
    	}
    	
    	return $may_change;
    	
    }
    
    public function may_report_topic($t, $is_title = false){
    	if(!$is_title){
    		$sql_array = array(
    				"SELECT `t_id` FROM ".topic_message." WHERE id=",
    				"?".$t,
    				);
    		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    		$row = $this->db->return_array($sql);
    		$this->db->free_result($sql);
    		if(empty($row['t_id']))return false;
    		$t = $row['t_id'];
    	}
    	$topic_data = $this->get_topic_data($t);
    	if(!$topic_data)return false;
    	else return true;
    }
    
    public function get_topic_massage_($id){
    	$sql_array = array(
    			"SELECT `message` FROM ".topic_message." WHERE id=",
    			"?".$id,
    			);
    	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    	$row = $this->db->return_array($sql);
    	$this->db->free_result($sql);
    	
    	return (empty($row['message'])) ? false : $row['message'];
    }
    
    public function get_over_topic($id){
    	$sql_array = array(
    			"SELECT `t_id` FROM ".topic_message." WHERE id=",
    			"?".$id,
    			);

    	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    	$row = $this->db->return_array($sql);
    	$this->db->free_result($sql);
    	return (empty($row['t_id'])) ? false : $row['t_id'];
    }
    
    public function get_num_topic($head_id,$this_id){
    	if(empty($head_id) || empty($this_id))return 1;
    	$sql_array = array(
    			"SELECT `id` FROM ".topic_message." WHERE t_id=",
    			"?".$head_id,
    			);
    	$count = 1;
    	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    	while($row = $this->db->return_array($sql))if($row['id'] == $this_id)return $count+1;
    	else $count++;
    	
    	return $count;
    }
    
    public function get_post_user_id($id){
    	$sql_array = array(
    			"SELECT `u_id` FROM ".topic_message." WHERE id=",
    			"?".$id,
    			);
    	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    	$row = $this->db->return_array($sql);
    	$this->db->free_result($sql);
    	return (empty($row['u_id'])) ? false : $row['u_id'];
    }
	
	private function has_befor_visit($t_id){
		$is_user = ($this->user->data['is_user']) ? 1 : 0;
		$sql_array = array(
				"SELECT `id` FROM ".last_visist." WHERE is_user = '".$is_user."' AND u_id=",
				"?".$this->user->data['id'],
				" AND t_id=",
				"?".$t_id,
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		return (empty($row['id'])) ? false : $row['id'];
	}
}