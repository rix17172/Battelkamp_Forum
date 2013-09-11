<?php
if(!defined("in_forum"))exit;
//topic.php and mod.php use this!
class Forum{
	public  $db;//vi skal bruge denne senere end denne class defor public
	private $setting;
	public  $style;
	public $user;
	public $access = array();
	
	function __construct(){
		global $db,$setting,$style,$user;
		$this->db      = $db;
		$this->setting = $setting;
		$this->style   = $style;
		$this->user    = $user;
		
		//sry but this is writet on danhis
		$this->access['se']          = 1;
		$this->access['se_topic']    = 2;
		$this->access['opret_topic'] = 3;
		$this->access['svar_topic']  = 4;
		$this->access['se_report']   = 5;
		$this->access['giv_warn']    = 6;
		$this->access['slet_report'] = 7;
	}
	
	function get_kat_and_forum($place = 0){
		$array = array();
		//så går vi igang
		$sql_array = array(
				'SELECT k.id,k.name FROM '.katolori.' AS k JOIN '.katolori_access.' AS a ON k.place=',
				"?".$place,
				' AND a.k_id = k.id AND a.g_id=',
				'?'.$this->user->data['gruppe_id'],
				);
		$num = 0;
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row_kat = $this->db->return_array($sql)){
			$forum = array();
			$forum_num = 0;
			$sqll = $this->db->get_sql_query('SELECT g.id,g.name,g.post_num,g.last_write,g.last_topic,g.last_is_title,g.last_id FROM '.forum." AS g JOIN ".forum_access." AS a ON g.place='".$row_kat['id']."' AND a.f_id = g.id AND a.a_id=".$this->access['se']." AND a.g_id=".$this->user->data['gruppe_id']);
			while($row_forum = $this->db->return_array($sqll)){
				$forum_num++;
				if($row_forum['last_topic'] != null && $row_forum['post_num'] != 0 && $this->access_topic($row_forum['id'])){
					if($row_forum['last_is_title'] != 1){
						                   if(!class_exists("topic"))require_once first.'include/class/topic.php';
                   $topic = new topic();
                   $topic_title = $topic->get_topic_data($topic->get_over_topic($row_forum['last_id']));
					$last = array(
						"t_id"  => $topic_title['id'],
						't_num' => $topic->get_num_topic($topic_title['id'], $row_forum['last_id']),
						);
					}else{
						$last = array(
								"t_id"  => $row_forum['last_id'],
								't_num' => 1,
								);
					}
				}else $last = array(
								"t_id"  => false,
								't_num' => false,
								);
				$forum[] = array(
						'sort'       => 'forum',
						'num'        => $forum_num,
						'id'         => $row_forum['id'],
						'name'       => $row_forum['name'],
						'url_name'   => urlencode($row_forum['name']),
						'read'       => ($row_forum['post_num'] != 0 ? ($this->has_visit_all_topic($row_forum['id']) ? 'yes' : 'no') : 'yes'),
						'may_see'    => $this->access_topic($row_forum['id']),
						'last_write' => (!empty($row_forum['last_write'])) ? $row_forum['last_write'] : false,
						'num_post'   => $row_forum['post_num'],
						'last_title' => $row_forum['last_topic'],
						'last_url'   => "topic.php?title=".urlencode($row_forum['last_topic'])."&amp;t=".$last['t_id']."#topic_".$last['t_num'],
						);
			}
			if($forum_num != 0){
				$array[] = array(
					'num'  => $num,
					'id'   => $row_kat['id'],
					'sort' => 'kat',
					'name' => $row_kat['name'],
					);
			$num++;
			for($i=0;$i<count($forum);$i++)$array[] = $forum[$i];
			}
		}
		
		
		$this->style->set_if("count_kf", count($array));
		for($i=0;$i<count($array);$i++)$this->style->set_for("kf", $array[$i]);
	}
	
	public function show_topic_title($f){
		if(!$this->access_topic($f))return false;
		
		$sql_array = array(
				"SELECT * FROM ".topic_title." WHERE f_id=",
				"?".$f,
				"ORDER BY last_post_time DESC"
				);
		
		$topic_num = 0;
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("topic_title", array(
					'id'          => $row["id"],
					'title'       => $row['title'],
					'clean_title' => urlencode($row['title']),
					'read'        => ($this->has_seen_post($row['id'])) ? 'yes' : 'no', 
					'last_write'  => $row['last_write_username'],
					));
			$topic_num++;
		}
		
		$this->style->set_if("topic_count", $topic_num);
	}
	
	public function access_forum($f){
		$sql_array = array(
				"SELECT `id` FROM ".forum_access." WHERE f_id=",
				"?".$f,
				" AND g_id=",
				"?".$this->user->data['gruppe_id'],
				" AND a_id='".$this->access['se']."'",
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		return empty($row['id']) ? false : true;
	}
	
	public function may_post_title($f){
		$sql_array = array(
				"SELECT `id` FROM ".forum_access." WHERE f_id=",
				"?".$f,
				" AND g_id=",
				"?".$this->user->data['gruppe_id'],
				" AND a_id=".$this->access['opret_topic'],
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		return (empty($row['id'])) ? false : true;
	}
	
	public function access_topic($f){
		$sql_array = array(
				"SELECT `id` FROM ".forum_access." WHERE f_id=",
				"?".$f,
				" AND g_id=",
				"?".$this->user->data['gruppe_id'],
				" AND a_id='".$this->access['se_topic']."'",
		);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		return empty($row['id']) ? false : true;		
	}
	
	public function bred_forum($f_id,$lang_index,$is_topic = false,$show_index = false){
		$bred_array = array();
				
		if($is_topic){
			$bred_array[] = array(
					'url'  => false,
					'name' => $is_topic,
					);
		}
		
		$sql_array = array(
				"SELECT * FROM ".forum." WHERE id=",
				"?".$f_id,
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		$bred_array[] = array(
				"url"  => (!$is_topic) ? false : "forum.php?name=".urlencode($row['name'])."&amp;f=".$row['id'],
				"name" => $row['name'],
				);
		
		$forset = true;
		$place  = $row['place'];
		$next   = "kat";
		
		while($forset){
			if($next == "kat"){
				$sql_array = array(
						"SELECT * FROM ".katolori." WHERE id=",
						"?".$place,
						);
				$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
				$row = $this->db->return_array($sql);
				$this->db->free_result($sql);
				if($row['place'] == '0')$forset = false;
				else{
					$next = "forum";
					$place = $row['place'];
				}
			}else{
			$sql_array = array(
					"SELECT * FROM ".forum." WHERE id=",
					"?".$place,
					);
			$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			
			if(empty($row['id']))$forset = false;
			else{
				$bred_array[] = array(
						"url"  => "forum.php?name=".urlencode($row['name'])."&amp;f=".$row['id'],
						"name" => $row['name'],
				);
				$place = $row['place'];
				$next  = "kat";
			}
			}
		}
		
		if(!$show_index)$bred_array[] = array(
				"url"  => "index.php",
				"name" => $lang_index,
		);
		else $bred_array[] = $show_index;
		
		if(!empty($bred_array)){
			for($i=count($bred_array)-1;$i>-1;$i--)$this->style->set_for("bred", $bred_array[$i]);
		}
		
	}
	
	public function MaySeKat($id,$UserId = null,$IsIdNullInt = false){
		if(!$UserId){
			global $user;
			if(!$IsIdNullInt)$UserId = $user->data['id'];
			else $UserId = "0";
		}
		$sql_array = array(
				"SELECT count(ka.id) FROM ".katolori." AS kat JOIN ".katolori_access." AS ka ON kat.id=",
				"?".$id,
				" AND ka.k_id = kat.id AND ka.g_id=",
				"?".$UserId,
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		
		return ($this->db->return_from_count($sql) != 0) ? true : false;
		
	}
	
	public function ReturnKatTitleFromId($id){
		$sql_array = array(
				"SELECT `name` FROM ".katolori." WHERE id=",
				"?".$id,
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		return (empty($row['name'])) ? null : $row['name'];
	}
	
	public function GetForumTitleFromId($id){
		$sql_array = array(
				"SELECT `name` FROM ".forum." WHERE id=",
				"?".$id,
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
	    $row = $this->db->return_array($sql);
	    $this->db->free_result($sql);
	    return (empty($row['name'])) ? null : $row['name'];
	}
	
	private function has_seen_post($t_id){
		$is_user = ($this->user->data['is_user']) ? 1 : 0;
		$sql_array = array(
				"SELECT count(id) FROM ".last_visist." WHERE is_user='".$is_user."' AND u_id='".$this->user->data['id']."' AND t_id=",
				"?".$t_id,
		);
		if($this->db->return_from_count($this->db->get_sql_query($this->db->clean_sql($sql_array))) == 0)return false;
		
		$sql_array = array(
				"SELECT count(l.id) FROM ".topic_title." AS t JOIN ".last_visist." AS l ON t.id = ",
				"?".$t_id,
				"AND l.t_id = t.id AND l.is_user=",
				"?".$is_user,
				" AND l.u_id=",
				"?".$this->user->data['id'],
				"AND l.time < t.last_post_time",
				);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$num_row = $this->db->return_from_count($sql);
		
		return ($num_row != 0) ? false : true;
	}
	
	private function has_visit_all_topic($f_id){
		$is_user = ($this->user->data['is_user']) ? 1 : 0;
		
		$sql_array = array(
				"SELECT count(tt.id) FROM `".topic_title."` AS tt JOIN `".last_visist."` AS lv ON tt.f_id=",
				"?".$f_id,
				"AND lv.t_id=tt.id AND lv.u_id=",
				"?".$this->user->data['id'],
				"AND lv.is_user=",
				"?".$is_user,
				"AND lv.time >= tt.last_post_time"
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		
		return ($this->db->return_from_count($sql) != 0);
	}
}