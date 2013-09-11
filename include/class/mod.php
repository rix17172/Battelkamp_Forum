<?php
if(!defined("in_forum"))exit;

if(!class_exists("topic")){
	require_once 'include/class/topic.php';
	$topic = new topic();
}

class Mod extends topic{
  public function may_see_report(){
  	$sql_array = array(
  			"SELECT count(id) FROM ".forum_access." WHERE g_id=",
  			"?".$this->user->data['gruppe_id'],
  			" AND a_id=",
  			"?".$this->access['se_report'],
  			);
  	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
  	return ($this->db->return_from_count($sql) != 0) ? true : false;
  }
  
  public function count_report(){
       $count = 0;
  	   $sql = $this->db->get_sql_query("SELECT * FROM ".report);
       while($row = $this->db->return_array($sql)){
       	 if($row['is_title'] != 1){
       	 	$sql_array = array(
       	 			"SELECT `t_id` FROM ".topic_message." WHERE id=",
       	 			"?".$row['t_id'],
       	 			);
       	 	$sql_eks = $this->db->get_sql_query($this->db->clean_sql($sql_array));
       	 	$row_eks = $this->db->return_array($sql_eks);
       	 	$this->db->free_result($sql_eks);
       	 	$topic_id = $row_eks['t_id'];
       	 	if(empty($topic_id)){
       	 		echo "No topic id in '\$".__CLASS__."->".__FUNCTION__."()'";
       	 		exit;
       	 	}
       	 }else $topic_id = $row['t_id'];
       	 
       	 $sql_array = array(
       	 		'SELECT count(fa.id) FROM '.forum_access.' AS fa JOIN '.topic_title.' AS ta ON ta.id=',
       	 		"?".$topic_id,
       	 		"AND fa.f_id = ta.f_id AND fa.g_id = ",
       	 		"?".$this->user->data['gruppe_id'],
       	 		"AND fa.a_id=",
       	 		"?".$this->access['se_report'],
       	 		);
       	 
       	 $sql_count = $this->db->get_sql_query($this->db->clean_sql($sql_array));

       	 $count = $count+$this->db->return_from_count($sql_count);
       	 
       }
       return $count;
       
  }
  
  /**
   * SHOW Only what the user have access too
   */
  public function get_all_my_report(){
  	if(!function_exists("bb"))require_once first.'include/function/bb.php';
  	
  	
  	
  	$sql = $this->db->get_sql_query("SELECT rr.is_title,rr.t_id,ro.options,rr.report_reason,rr.id FROM ".report." AS rr JOIN ".report_op." AS ro ON ro.id = rr.report_op");
  	while($row = $this->db->return_array($sql)){
  		if($row['is_title'] != 1){
  			$topic_title = $this->get_topic_data($this->get_over_topic($row['t_id']));
  			$t_id = $topic_title['id'];
  		}else $t_id = $row['t_id'];
  		
  		
  		
  		$sql_array = array(
  				"SELECT tt.id,tt.title,tt.f_id,tt.message,tt.user_id FROM ".topic_title." AS tt JOIN ".forum_access." AS fa ON tt.id=",
  				"?".$t_id,
  				"AND fa.f_id=tt.f_id AND fa.g_id=",
  				"?".$this->user->data['gruppe_id'],
  				"AND a_id=",
  				"?".$this->access['se_report'],
  				);
  		
  		$report_sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
  		while($report = $this->db->return_array($report_sql)){
  			$this->style->set_for("report",array(
  					'grund' => $row['options'],
  					'topic' => ($row['is_title'] != 1) ? $this->get_topic_massage_($row['t_id']) : $report['message'],
  					"reason"      => $row['report_reason'],
  					"t_title"     => urlencode($report['title']),
  					"o_id"        => $report['id'],
  					"t_num"       => ($row['is_title'] != 1) ? $this->get_num_topic($this->get_over_topic($row['t_id']), $row['t_id']) : '1',
  					"may_warn"    => $this->may_give_warn($report['f_id']),
  					"user"        => ($row['is_title'] != 1) ? $this->get_post_user_id($row['t_id']) : $report['user_id'],
  					"may_deleate" => $this->may_deleate_report($report['f_id']),
  					"this_id"     => $row['id'],
  					));
  		}
  		
  	}
  	
  /*	$sql = $this->db->get_sql_query("SELECT rr.t_id,ro.options,rr.is_title,rr.report_reason,rr.id FROM ".report." AS rr JOIN ".report_op." AS ro ON ro.id = rr.report_op");
  	while($row = $this->db->return_array($sql)){
        
  		if($row['is_title'] != 1){
  			$t_head = $this->get_topic_data($this->get_over_topic($row['t_id']));
  			$t_id = $t_head['t_id'];
  		}else $t_id = $row['t_id'];
  		
  		$sql_array = array(
  				"SELECT * FROM ".forum_access." AS fa JOIN ".topic_title." AS tt ON tt.id=",
  				"?".$t_id,
  				"AND fa.g_id=",
  				"?".$this->user->data['gruppe_id'],
  				"AND fa.a_id=",
  				"?".$this->access['se_report'],
  		);
  		
  		$sql_2 = $this->db->get_sql_query($this->db->clean_sql($sql_array));
  		while($row_2 = $this->db->return_array($sql_2)){
  			if($row['is_title'] == 1){
  				$topi = $this->get_topic_data($row['t_id']);
  				$topic       = $topi['message'];
  				$topic_title = $topi['title'];
  				$topic_id    = $topi['id'];
  				$topic_num   = 1;
  				$forum_id    = $topi['f_id'];
  				$user        = $topi['user_name'];
  				$may_post    = $this->may_deleate_report($topi['f_id']);
  			}
  			else{
  				$topic = $this->get_topic_massage_($row['t_id']);
  				$topi = $this->get_topic_data($this->get_over_topic($row['t_id']));
  				$topic_title = $topi['title'];
  				$topic_id    = $topi['id'];
  				$topic_num   = $this->get_num_topic($topi['id'], $row['t_id']);
  				$forum_id    = $topi['f_id'];
  				$user        = $this->get_post_user_id($row['t_id']);
  				$may_post    = $this->may_deleate_report($topi['f_id']);
  			}
  			
  			$this->style->set_for("report",array(
  					"grund"       => $row['options'],
  					"topic"       => bb($topic),
  					"reason"      => $row['report_reason'],
  					"t_title"     => urlencode($topic_title),
  					"o_id"        => $topic_id,
  					"t_num"       => $topic_num,
  					"may_warn"    => $this->may_give_warn($forum_id),
  					"user"        => $user,
  					"may_deleate" => $may_post,
  					"this_id"     => $row['id'],
  					));
  		}
  		
  	}*/
  }
  
  function may_give_warn($forum_id){
     $sql_array = array(
     		"SELECT count(id) FROM ".forum_access." WHERE g_id=",
     		"?".$this->user->data['gruppe_id'],
     		"AND a_id=",
     		"?".$this->access['giv_warn'],
     		" AND f_id=",
     		"?".$forum_id,
     		);
     $sql_count = $this->db->get_sql_query($this->db->clean_sql($sql_array));
     return ($this->db->return_from_count($sql_count) != 0) ? true : false;	
  }
  function may_deleate_report($f_id){
  	$sql_array = array(
  			"SELECT count(id) FROM ".forum_access." WHERE g_id=",
  			"?".$this->user->data['gruppe_id'],
  			" AND a_id=",
  			"?".$this->access['slet_report'],
  			" AND f_id=",
  			"?".$f_id,
  			);
  	$sql_count = $this->db->get_sql_query($this->db->clean_sql($sql_array));
  	return ($this->db->return_from_count($sql_count) != 0) ? true : false;
  }
}