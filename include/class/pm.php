<?php
if(!defined("in_forum"))exit;

class PrivateMessage{
	
	private $db;
	private $user;
	private static $pmSql = false;
	private $cache = array();
	
	const to   = 1;
	const from = 2;
	
	private function SqlArray(){
		$return = array();
		
		//Count Undread PM's
		//0
		$return[] = array(
				"SELECT count(id) FROM `".pm_title."` WHERE to_id = ",
				"?[id:0]",
				" AND todel != ",
				"?".Yes,
				" AND tounread = ",
				"?".Yes,
				" OR from_id = ",
				"?[id:0]",
				" AND fromdel != ",
				"?".Yes,
				" AND fromunread = ",
				"?".Yes,
		);
		
		//get my topic title
		//1
		$return[] = array(
				"SELECT * FROM `".pm_title."` WHERE to_id=",
				"?[id:0]",
				" AND todel=",
				"?".No,
				" OR from_id=",
				"?[id:0]",
				" AND fromdel=",
				"?".No,
				
		);
		
		//2
		$return[] = array(
				"SELECT * FROM `".pm_title."` WHERE id=",
				"?[id:0]",
		);
		
		//3
		$return[] = array(
				"SELECT pm.id,pm.SendtFrom,pm.fromdel,pm.message,u.username,pm.todel FROM `".pm_message."` AS pm JOIN `".user."` AS u ON pm.pm_id=",
				"?[id:0]",
				"AND u.id = pm.SendtFrom"
		);
		
		//4
		$return[] = array(
				"UPDATE `".pm_title."` SET fromdel=",
				"?".Yes,
				"WHERE id=",
				"?[id:0]",
		);
		
		//5
		$return[] = array(
				"UPDATE `".pm_message."` SET todel=",
				"?".Yes,
				"WHERE id=",
				"?[id:0]",
		);
		
		//6
		$return[] = array(
				"DELETE FROM `".pm_title."` WHERE id=",
				"?[id:0]",
		);
		
		//7
		$return[] = array(
				"DELETE FROM `".pm_message."` WHERE id=",
				"?[id:0]",
		);
		
		//8
		$return[] = array(
				"UPDATE `".pm_message."` SET fromdel=",
				"?".Yes,
			    "WHERE id=",
				"?[id:0]",
		);
		
		//9
		$return[] = array(
				"UPDATE `".pm_title."` SET todel=",
				"?".Yes,
				"WHERE id=",
				"?[id:0]",
		);
		
		//10
		$return[] = array(
				"UPDATE `".pm_title."` SET tounread=",
				"?".No,
				"WHERE id=",
				"?[id:0]",
		);
		
		//11
		$return[] = array(
				"UPDATE `".pm_title."` SET fromunread=",
				"?".No,
				"WHERE id=",
				"?[id:0]",
		);
		
		//12
		$return[] = array(
				"SELECT * FROM `".pm_message."` WHERE id=",
				"?[id:0]",
		);
		 
		return $return;
	}
	
	private function GetSQL($sqlNumber,$sqldata = false){
		if(!self::$pmSql){
		    $sqql = $this->SqlArray();
		    self::$pmSql = $sqql;
		}else{
			$sqql = self::$pmSql;
		}
		
		if(empty($sqql[$sqlNumber])){
			exit("Sql ERROR!");
		}
		
		if(gettype($sqldata) == "array"){
		  $s = $sqql[$sqlNumber];
		  	for($i=0;$i<count($s);$i++){
		  		if(preg_match("/\[id:([0-9]*)\]/", $s[$i],$reg)){
		  			$s[$i] = str_replace("[id:".$reg[1]."]", (empty($sqldata[$reg[1]]) ? No : $sqldata[$reg[1]]), $s[$i]);
		  		}
		  	}
		  	
		  	$sql = $this->db->clean_sql($s);
		  
		}else{
			$sql = $this->db->clean_sql($sqql[$sqlNumber]);
		}
		
		return $sql;
	}
	
	function IsPmDelete($id,$is_title = false){
		if($is_title){
			if(empty($this->cache['title'])){
				$r = $this->cache['title'];
			}else{
				$sql = $this->db->get_sql_query($this->GetSQL(2,array($id)));
				$r   = $this->db->return_array($sql);
				$this->db->free_result($sql);
			}
			
			if($r['from_id'] == $this->user->id){
				if($r['fromdel'] == Yes){
					return true;
				}
				return false;
			}else{
				if($r['todel'] == Yes){
					return true;
				}
				return false;
			}
			
		}
	}
	
	function emptyCache(){
		$this->cache = array();
	}
	
	function __construct(){
		global $db,$user;
		
		$this->db = $db;
		$this->user = $user;
	}
	
	function UnreadMessageCount($id = NIL){
		if($id === NIL){
			$id = $this->user->data['id'];
		}
		
		$sql = $this->db->get_sql_query($this->GetSQL(0,array($id)));
		return $this->db->return_from_count($sql);
		
	}
	
	function GetMessageTitle(){
		
		$return = array();
		
		$sql = $this->db->get_sql_query($this->GetSQL(1,array($this->user->data['id'])));
		while($row = $this->db->return_array($sql)){
			$return[] = array(
					"id"            => $row['id'],
					"my"            => ($row['from_id'] == $this->user->data['id'] ? self::from : self::to),
					"from_id"       => $row['from_id'],
					"to_id"         => $row['to_id'],
					"from_username" => $this->user->GetUserNickFromId($row['from_id']),
					"to_username"   => $this->user->GetUserNickFromId($row['to_id']),
					"time"          => date($this->user->data['TimeFormat'],$row['gettime']),
					"title"         => $row['title'],
					"submessage"    => substr($row['message'], 0, 100),
					"MessageCount"  => $row['messagecount'],
			);
		}
		
		return $return;
	}
	
	function RenameArray($orgName,$toName,$array){
		$array[$toName] = $array[$orgName];
		unset($array[$orgName]);
		return $array;
	}
	
	function GetPmMessageTopic($id = false,$SetRead = true){
		
		if(!function_exists("bb")){
			require_once first.'include/function/bb.php';
		}
		
		$too = false;
		
		$return = array();
		if(!$id){
			//vi tager cache
			if(empty($this->cache['title'])){
				$h = new Head();
				$h->PageNotFound();
				exit("<strong>404 - not found</strong");
			}
			$r = ReturnArrayNonInt(array_merge($this->cache['title'],$this->RenameArray("id", "uid", $this->user->GetUserDataFromId($this->cache['title']['from_id']))));
			$r['message'] = bb($r['message']);
			if(!$this->IsPmDelete($r['id'],true)){
			$return[] = array_merge($r,array("sort" => "title"));
			}else{
				$c = $this->cache['title']['id'];
			}
			
			$too = $r['to_id'] == $this->user->id ? true : false;
			
		}else{
			$sql = $this->db->get_sql_query($this->GetSQL(2,array($id)));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			$r = ReturnArrayNonInt($row);
			if(!$this->IsPmDelete($r['id'],true)){
			$r['message'] = bb($r['message']);
			$return[] = array_merge($r,array('sort' => 'title'));
			}else{
				$c = $row['id'];
			}
			
			$too = $r['to_id'] == $this->user->id ? true : false;
		}
		
		if($SetRead){
			$id = !empty($c) ? $c : $return[0]['id'];
			   if($too){
			   	 $this->db->get_sql_query($this->GetSQL(10,array($id)));
			   }else{
			   	 $this->db->get_sql_query($this->GetSQL(11,array($id)));
			   }	   
		}
		
		$sql = $this->db->get_sql_query($this->GetSQL(3,array((!empty($c) ? $c : $return[0]['id']))));
		while($row = $this->db->return_array($sql)){
			if($row['SendtFrom'] == $this->user->id && $row['fromdel'] == No || $row['SendtFrom'] != $this->user->id && $row['todel'] != No){
			$r = ReturnArrayNonInt($row);
			$r['message'] = bb($r['message']);
			$return[] = array_merge($r,array('sort' => 'message'));
			}
		}
		
		return $return;
	}
	
	function DeletePM($id,$title = false){
		if($title){
			$s = $this->GetSQL(2,array($id));
		}else{
			$s = $this->GetSQL(12,array($id));
		}
		
		$sql = $this->db->get_sql_query($s);
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if(empty($row['id'])){
			return false;
		}
		
		if($title){
			if($row['from_id'] == $this->user->id){
				if($row['todel'] == Yes){
					$this->db->get_sql_query($this->GetSQL(6,array($row['id'])));
				}else{
				     $this->db->get_sql_query($this->GetSQL(4,array($row['id'])));
				}
			}else{
				if($row['fromdel'] == Yes){
					$this->db->get_sql_query($this->GetSQL(6,array($row['id'])));
				}else{
				    $this->db->get_sql_query($this->GetSQL(9,array($row['id'])));
				}
			}
		}else{
			if($row['SendtFrom'] == $this->user->id){
				if($row['todel'] == Yes){
					$this->db->get_sql_query($this->GetSQL(7,array($row['id'])));
				}else{
				    $this->db->get_sql_query($this->GetSQL(8,array($row['id'])));	
				}
			}else{
				if($row['fromdel'] == $this->user->id){
					if($row['fromdel'] == $this->user->id){
						$this->db->get_sql_query($this->GetSQL(7,array($row['id'])));
					}else{
						$this->db->get_sql_query($this->GetSQL(5,array($row['id'])));
					}
				}
			}
		}
	}
	
	function GetPMListTilte($id){
		$sql = $this->db->get_sql_query($this->GetSQL(2,array($id)));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		$this->cache['title'] = $row;
		return (empty($row['title']) ? 'Unknwon' : $row['title']);
	}
}