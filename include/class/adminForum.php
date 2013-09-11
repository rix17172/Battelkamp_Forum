<?php
if(!defined("in_admin")){
	exit;
}

class AdminForum{
	
	private $id;
	private $db;
	private $data = array();
	private $style;
	public  $is_exsit = false;
	public  $place = null;
	
	function __construct($id){
		global $style;
		
		$this->id    = $id;
		$this->db    = new Db();
		$this->style = $style;
		
		if($id != 0){
			$this->Init();
		}
	}
	
	
	
	public function newForum($name,$level){
		$insert = array(
				'name'     => $name,
				'place'    => $level,
				'post_num' => 0,
		);
		
		return $this->db->Insert(forum, $insert); 
	}
	
	public function changeName($name){
		$sql_array = array(
				"UPDATE `".forum."` SET `name`=",
				"?".$name,
				"WHERE `id`=",
				"?".$this->id,
		);
		$this->db->get_sql_query($this->db->clean_sql($sql_array));
		$this->data['name'] = $name;
	}
	
	private function Init(){
		$sql_array = array(
				"SELECT * FROM `".forum."` WHERE `id`=",
				"?".$this->id,
		);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$this->data = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		$this->is_exsit =  (!empty($this->data['id']));
		if($this->is_exsit){
			$this->place = $this->data['place'];
		}
	}
	
	function __get($key){
		if(empty($this->data[$key]))return false;
		return $this->data[$key];
	}
	
	function getGrupAccess($grup){
		$sql_array = array(
				"SELECT `a_id` FROM `".forum_access."` WHERE `f_id`=",
				"?".$this->id,
				"AND `g_id`=",
				"?".$grup,
		);
		
		$return = array(
				'seForum'    => false,
				'seTopic'    => false,
				'opretTopic' => false,
				'ansTopic'   => false,
				'seeReport'  => false,
				'givWarn'    => false,
				'delReport'  => false,
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			switch ((int)$row['a_id']){
				case 1:
					$return['seForum'] = true;
				break;
				case 2:
					$return['seTopic'] = true;
				break;
				case 3:
					$return['opretTopic'] = true;
				break;
				case 4:
					$return['ansTopic'] = true;
				break;
				case 5:
					$return['seeReport'] = true;
				break;
				case 6:
					$return['givWarn']  = true;
				break;
				case 7:
					$return['delReport'] = true;
				break;
			}
		}
		
		return $return;
	}
	
	public function changeAccess($accessId,$grupId = 0,$delete = false){
		if(!$delete){
			$this->db->Delete(forum_access, array(
					'f_id' => $this->id,
					'g_id' => $grupId,
					'a_id' => $accessId,
			));
		}else{
			
			//vi ville ikke indsætte noget der allerede findes ;)
			$sql_array = array(
					"SELECT `id` FROM `".forum_access."` WHERE `f_id`=",
					"?".$this->id,
					"AND `g_id`=",
					"?".$grupId,
					"AND `a_id`=",
					"?".$accessId,
			);
			
			$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			
			if(empty($row['id'])){
			$this->db->Insert(forum_access, array(
					'f_id' => $this->id,
					'g_id' => $grupId,
					'a_id' => $accessId,
			));
			}
		}
	}
	
	public function setDeleteTo($includeNot,$KatLevel = 0,$first = null){
		$sql_array = array(
				"SELECT `id`,`name` FROM `".katolori."` WHERE place=",
				"?".$KatLevel,
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			$first .= $row['name']."->";
			$sql_array = array(
					"SELECT `id`,`name` FROM `".forum."` WHERE `place`=",
					"?".$row['id'],
					"AND `id`!=",
					"?".$includeNot,
			);
			$f_sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			while($f_row = $this->db->return_array($f_sql)){
				$this->style->set_for("deleteTo", array(
						'id' => $f_row['id'],
						'show' => $first.$f_row['name'],
				));
				$this->setDeleteTo($includeNot,$f_row['id'],$first.$f_row['name']."->");
			}
		}
	}
	
	public function setMoveTo($includeNot,$place=0,$first = null){

		$sql = $this->db->get_sql_query("SELECT `id`,`name` FROM `".katolori."` WHERE `place`='".$place."'");
		while($row = $this->db->return_array($sql)){
			$sql2 = $this->db->get_sql_query("SELECT `id`,`name` FROM `".forum."` WHERE `place`='".$row['id']."' AND `id`!='".$includeNot."'");
			while($row2 = $this->db->return_array($sql2)){
					$this->setMoveTo($includeNot,$row2['id'],$first.$row['name'].'->'.$row2['name'].'->');
			}
			$this->style->set_for("move", array(
					'id' => $row['id'],
					'name' => $first.$row['name'],
			));
		}
		
	   /*$sql = $this->db->get_sql_query("SELECT * FROM `".katolori."` WHERE `place`='".$place."'");
	   while($row = $this->db->return_array($sql)){
	   	  if($row['id'] != $includeNot){
	   	  	 $this->style->set_for("move", array(
	   	  	 		'id'   => $row['id'],
	   	  	 		'name' => $first.$row['name'],
	   	  	 ));
	   	  	 
	   	  	 $sql2 = $this->db->get_sql_query("SELECT `id`,`name` FROM `".forum."` WHERE `place`='".$row['id']."'");
	   	  	 while($row2 = $this->db->return_array($sql2)){
	   	  	 	$this->setMoveTo($includeNot,$row2['id'],$first.$row['name'].'->'.$row2['name'].'->');
	   	  	 }
	   	  }
	   }*/
	}
	
	public function deleteAllforum(){
		$this->deleteAllInThisForum($this->id);
		
		$sql_array = array(
			    "SELECT `id` FROM `".topic_title."` WHERE `f_id`=",
				"?".$this->id,	
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			
			$sql_array = array(
					"SELECT `id` FROM `".topic_message."` WHERE `t_id`=",
					"?".$row['id'],
			);
			
			$tmSql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			while($tmRow = $this->db->return_array($tmSql)){
				//vi sletter denne ;)
				$this->db->Delete(topic_message, array(
						'id' => $tmRow['id'],
				));
				
				//vi sletter reporter som må være på denne besked ;)
				$this->db->Delete(report, array(
						't_id' => $tmRow['id'],
						'is_title' => 2,
				));
			}
			
			//vi sletter topic title ;)
			$this->db->Delete(topic_title, array(
					'id' => $row['id'],
			));
				
			//vi sletter report som må ligge i denne ;)
			$this->db->Delete(report, array(
					'id'       => $row['id'],
					'is_title' => 1,
			));
				
			//vi sletter last visit ;)
			$this->db->Delete(last_visist, array(
					't_id' => $row['id'],
			));
		}
		
		//last wee delete this forum
		$this->deleteThisForum();
	}
	
	private function deleteAllInThisForum($f_id){
		$sql_array = array(
				"SELECT `id` FROM `".katolori."` WHERE `place`=",
				"?".$f_id,
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			
			$sql_array = array(
					"SELECT `id` FROM `".forum."` WHERE `place`=",
					"?".$row['id'],
			);
			
			$forumSql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		
			while($forumRow = $this->db->return_array($forumSql)){
				
				$sql_array = array(
						"SELECT `id` FROM `".topic_title."` WHERE `f_id`=",
						"?".$forumRow['id'],
				);
				
				$topicSql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
				while($topicRow = $this->db->return_array($topicSql)){
					
					$sql_array = array(
							"SELECT `id` FROM `".topic_message."` WHERE `t_id`=",
							"?".$topicRow['id'],
					);
					
					$tmSql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
					while ($tmRow = $this->db->return_array($tmSql)){
						
						//vi sletter denne ;)
						$this->db->Delete(topic_message, array(
								'id' => $tmRow['id'],
						));
						
						//vi sletter reporter som må være på denne besked ;)
						$this->db->Delete(report, array(
								't_id' => $tmRow['id'],
								'is_title' => 2,
						));
						
						
					}
					
					//vi sletter topic title ;)
					$this->db->Delete(topic_title, array(
							'id' => $topicRow['id'],
					));
					
					//vi sletter report som må ligge i denne ;)
					$this->db->Delete(report, array(
							'id'       => $topicRow['id'],
							'is_title' => 1,
					));
					
					//vi sletter last visit ;)
					$this->db->Delete(last_visist, array(
							't_id' => $topicRow['id'],
					));
				}
				//vi sletter forun access ;)
				$this->db->Delete(forum_access, array(
						'f_id' => $forumRow['id'],
				));
				
				//vi sletter nu forum ;) 
				$this->db->Delete(forum, array(
						'id' => $forumRow['id'],
				));
				
				$this->deleteAllInThisForum($forumRow['id']);
			}
			
			//vi sletter nu katolori access ;)
			$this->db->Delete(katolori_access, array(
					'k_id' => $row['id'],
			));
			
			//vi sletter denne katolori
			$this->db->Delete(katolori, array(
					'id' => $row['id'],
			));
		}
	}
	
	public function deleteForumAndMove($toId){
		$sql_array = array(
				"UPDATE `".topic_title."` SET `f_id`=",
				"?".$toId,
				"WHERE f_id=",
				"?".$this->id,
		);
		
		$this->db->get_sql_query($this->db->clean_sql($sql_array));
		
		$sql_array = array(
				"UPDATE `".katolori."` SET `place`=",
				"?".$toId,
				"WHERE `place`=",
				"?".$this->id,
		);
		
		$this->db->get_sql_query($this->db->clean_sql($sql_array));
		$this->updatePostCount($toId);
		$this->deleteThisForum();
	}
	
	private function deleteThisForum(){
	   $this->db->Delete(forum, array(
	   		'id' => $this->id,
	   ));

	   $this->db->Delete(forum_access, array(
	   		'f_id' => $this->id,
	   ));
	}
	
	private function updatePostCount($f_id){
		$count = 0;
		
		$sql_array = array(
				"SELECT `id` FROM `".topic_title."` WHERE `f_id`=",
				"?".$f_id,
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			$count++;
			$sql_array = array(
					"SELECT `id` FROM `".topic_message."` WHERE `t_id`=",
					"?".$row['id'],
			);
			
			$t_sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			while($t_row = $this->db->return_array($t_sql)){
				$count++;
			}
		}
		
		$sql_array = array(
				"UPDATE `".forum."` SET `post_num`=",
				"?".(int)$count,
				"WHERE `id`=",
				"?".$f_id,
		);
		$this->db->get_sql_query($this->db->clean_sql($sql_array));
		
	}
	
}