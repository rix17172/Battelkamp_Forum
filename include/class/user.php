<?php
if(!defined("in_forum") && !defined("in_install"))exit;


class User{
   public $data = array();
   
   private $db;
   private $setting;
   private $style;
   private $pm;
   
   function __construct(){
   	if(!defined("in_install")){
   	  global $db,$setting,$style;
   	  $this->db      = $db;
   	  $this->setting = $setting;
   	  $this->style   = $style;
   	  if(class_exists("Pm"))require_once first.'include/class/pm.php';
   	  $this->pm = new PrivateMessage();
   	}
   }
   
   public function get_user_data(){
   	 $user_data = $this->get_header_user_data();
   	 if(!$user_data['sort']){
   	 	$this->data['is_user']   = false;
   	 	$this->data['is_admin']  = false;
   	 	$this->data['is_geaust'] = true;
   	 	if(!empty($_COOKIE['geust_id'])){
   	 		$this->data['id'] = $_COOKIE['geust_id'];
   	 		$this->update_time_geaust_id($this->data['id']);
   	 	}else{
   	 	    $this->data['id'] = $this->get_new_geaust_id();	
   	 	}
   	 	$this->data['ip']         = $this->GetUserIp();
   	 	$this->data['TimeFormat'] = $this->setting->data['StandTimeFormat'];
   	 }else{
   	    //vi starter med at kontrollere brugerens oplysninger
   	 	$sql_array = array(
   	 			"SELECT * FROM `".user."` WHERE username=",
   	 			"?".$user_data['user_name'],
   	 			" AND id=",
   	 			"?".$user_data['user_id'],
   	 	);
   	 	
   	 	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	 	$row = $this->db->return_array($sql);
   	 	$this->db->free_result($sql);
   	 	
   	 	if(empty($row['id'])){
   	 		$this->delate_user_head_data();
   	 		header("location: ?empty=true");
   	 		exit;
   	 	}else{
   	 		//vi har fat i en bruger.
   	 		$this->data['is_user']   = true;
   	 		$this->data['is_admin']  = false;
   	 		$this->data['is_geaust'] = false;
   	 		//vi opdatere brugerens online tid
   	 		unset($row['password']);
   	 		$this->data = array_merge($this->data,$row);
   	 		$this->update_online_time();
   	 		if($this->GetUserIp() != $row['ip'] && $this->setting->data['Control_Ip'] == Yes){
   	 			$this->logout();
   	 		}elseif($this->GetUserIp() != $row['ip']){
   	 			$this->UpdateUserIp();
   	 		}
   	 		$MessageCount = $this->pm->UnreadMessageCount($row['id']);
   	 		$this->data['PmIsUnreadMessage'] = ($MessageCount != 0) ? true : false;
   	 		$this->data['PmUnreadCount'] = $MessageCount;
   	 	}
   	 	
   	 }
   	 $this->get_online($this->style);
   	 $this->data['gruppe_id'] = $this->get_grup_by_id($this->data['id']);
   }
   
   public function update_location($name = false){
   	if(!$name)return false;
   	$url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
   	 if($this->data['is_user']){
   	 	$sql_array = array(
   	 			"UPDATE ".user." SET page_title=",
   	 			"?".$name,
   	 			",url=",
   	 			"?".$url,
   	 			' WHERE id=',
   	 			'?'.$this->data['id'],
   	 			);
   	 }else{
   	 	$sql_array = array(
   	 			"UPDATE ".geaust." SET title=",
   	 			"?".$name,
   	 			",url=",
   	 			"?".$url,
   	 			" WHERE g_id=",
   	 			"?".$this->data['id'],
   	 	);
   	 }
   	 $this->db->get_sql_query($this->db->clean_sql($sql_array));   
   }
   
   public function hash_password($password,$opret_time,$has = null){
   	 $hash_form = !$has ? $this->setting->data['PASS_HASH'] : $has;
   	 return hash($hash_form, $opret_time.$opret_time.$password.$opret_time.$password.$opret_time.$opret_time.$password);
   }
   
   public function logout(){
   	if(!$this->data['is_user'])return false;
   	$this->delate_user_head_data();
   	header('location:index.php');
   	exit;
   }
   
   public function GetUserNickFromId($user_id){
   	$sql_array = array(
   			"SELECT `username` FROM `".user."` WHERE id=",
   			"?".(int)$user_id,
   	);
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$row = $this->db->return_array($sql);
   	$this->db->free_result($sql);
   	return empty($row['username']) ? "UNKNOWN" : $row['username'];
   }
   
   public function get_grup_by_id($id){
   	if($this->data['is_user']){
   	$sql_array = array(
   			"SELECT `g_id` FROM ".grup_member." WHERE u_id=",
   			"?".$id,
   			);
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$row = $this->db->return_array($sql);
   	$this->db->free_result($sql);
   	return empty($row['g_id']) ? 0 : $row['g_id'];
   	}else{
   		return 0;//gæst er jo ikke medlem af en gruppe og derfår har gruppen 0.
   	}
   }
   
   public function get_grup_name_by_userid($id){
   	//føst vi leder efter brugeren i mysql. 
   	$sql_array = array(
   			"SELECT `name` FROM ".grup_member." t JOIN ".grup_name." g ON t.u_id=",
   			"?".$id,
   			" AND g.id=t.g_id",
   			);
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$row = $this->db->return_array($sql);
   	$this->db->free_result($sql);
   	
   	return (empty($row['name'])) ? false : $row['name'];
   	
   }
   
   public function GetGrupNameById($id){
   	$sql_array = array(
   			"SELECT `name` FROM ".grup_name." WHERE id=",
   			"?".$id,
   			);
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$row = $this->db->return_array($sql);
   	$this->db->free_result($sql);
   	return (empty($row['name'])) ? null : $row['name'];
   }
   
   public function get_post_num_by_id($id){
   	$sql_array = array(
   			"SELECT `post` FROM ".user." WHERE id=",
   			"?".$id,
   			);
    $sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
    $row = $this->db->return_array($sql);
    $this->db->free_result($sql);
    return (empty($row['post'])) ? 0 : $row['post'];
   }
   
   public function GetUserIdFromUsername($username){
   	$sql_array = array(
   			"SELECT `id` FROM ".user." WHERE username=",
   			"?".$username,
   			);
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$row = $this->db->return_array($sql);
   	$this->db->free_result($sql);
   	
   	return (empty($row['id'])) ? false : $row['id'];
   }
   
   public function GetUserDataFromId($id){
   	$sql_array = array(
   	        "SELECT * FROM `".user."` WHERE id=",
   	        "?".$id
   	);
   	
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$row = $this->db->return_array($sql);
   	$this->db->free_result($sql);
   	
   	return (empty($row) ? array() : $row);
   }
   
   private function update_online_time(){
    $sql_array = array(
    		"UPDATE ".user." SET last_online=",
    		"?".time(),
    		" WHERE id=",
    		"?".$this->data['id'],
    		);
    $this->db->get_sql_query($this->db->clean_sql($sql_array));
   }
   
   private function delate_user_head_data(){
   	 $head_data = $this->get_header_user_data();
   	if(!$head_data['sort']){
   		return false;
   	}
   	
   	switch ($head_data['sort']){
   		case "session":
   			$_SESSION['user_name'] = null;
   			$_SESSION['user_id'] = null;
   		break;
   		case "cookie":
   			DeleteCookie("user_name");
   			DeleteCookie("user_id");
   			$_COOKIE['user_name'] = null;
   			$_COOKIE['user_id']   = null;
   		break;
   	}
   	
   	$this->delate_user_head_data();
   }
   
   public function GetUserIp(){
   	 return $_SERVER['REMOTE_ADDR'];
   }
   
   public function UpdateUserIp($user_id = false){
   	$sql_array = array(
   			"UPDATE `".user."` SET ip=",
   			"?".$this->GetUserIp(),
   			"WHERE id=",
   			"?".($user_id ? $user_id : $this->data['id']),
   			);
   	$this->db->get_sql_query($this->db->clean_sql($sql_array));
   }
   
   private function get_online($style){
   	
   	$sql_array = array(
   			"SELECT COUNT(*) FROM ".user." WHERE last_online > ",
   			"?".strtotime("-5 minute"),
   			" AND status=1",
   			);
   	
   	$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$member_numb = $this->db->return_from_count($sql);
   	
   	$style->set('user_online',$member_numb);
   	if($member_numb > $this->setting->data['RECOD_ONLINE_USER']){
   		$this->setting->update_setting("RECOD_ONLINE_USER", $member_numb);
   	}
   	
   	$sql_array = array(
   			"SELECT COUNT(*) FROM ".geaust." WHERE time > ",
   			"?".strtotime("-5 minute"),
   			);
   	$sql_g_time = $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	$style->set('geaust_online',$this->db->return_from_count($sql_g_time));
   	
   	$style->set('recod_user',$this->setting->data['RECOD_ONLINE_USER']);
   }
   
   private function update_time_geaust_id($g_id){
   	$sql_array = array(
   			"UPDATE ".geaust." SET time='".time()."' WHERE g_id=",
   			"?".$g_id,
   			);
   	$this->db->get_sql_query($this->db->clean_sql($sql_array));
   }
   
   private function get_new_geaust_id(){
   	$new_id = $this->setting->data['GEAUST_ID'];
   	setcookie("geust_id", $new_id, strtotime("+1 year"), path);
   	$this->setting->update_setting("GEAUST_ID", $new_id+1);
   	
   	//vi skal indsætte gæsten inde i gæst tablen.
   	$sql_array = array(
   			"INSERT INTO ".geaust." (g_id,time)  VALUES (",
   			"?".$new_id,
   			",'".time()."')",
   			);
   	$this->db->get_sql_query($this->db->clean_sql($sql_array));
   	
   	return $new_id;
   }
   
   private function get_header_user_data(){
   	  if(!empty($_SESSION['user_name']) && !empty($_SESSION['user_id'])){
   	  	return array('user_name' => $_SESSION['user_name'], 'user_id' => $_SESSION['user_id'], 'sort' => 'session');
   	  }elseif(Cookie("user_name") && Cookie("user_id")){
   	  	return array('user_name' => $_COOKIE['user_name'], 'user_id' => $_COOKIE['user_id'], 'sort' => 'cookie');
   	  }else{
   	  	return array('sort' => null);
   	  }
   }
   
   public function update_post($id,$down = false){
   	 
   	 
   	 $sql_array = array(
   	 		"UPDATE ".user." SET post=".($down ? "post-1" : "post+1")." WHERE id=",
   	 		"?".$id,
   	 		);
   	 $this->db->get_sql_query($this->db->clean_sql($sql_array));
   }
   
   function __get($get){
   	  if(empty($this->data[$get])){
   	  	return null;
   	  }
   	  
   	  return $this->data[$get];
   }
   
   function __set($tag,$value){
   	    //hvis dette er gæst så ordner vi det føst :)
   	    if($this->data['is_geaust']){
   	    	return $this->HandleSetGeaust($tag, $value);
   	    }
   	
   	   //vi kontroller om row findes
   	   if($this->ControlIfRowExist($tag)){
   	   	 $sql_array = array(
   	   	 		"UPDATE `".user."` SET `".$tag."`=",
   	   	 		"?".$value,
   	   	 		"WHERE id=",
   	   	 		"?".$this->data['id'],
   	   	 );
   	   	 
   	   	 $this->db->get_sql_query($this->db->clean_sql($sql_array));
   	   	 $this->data[$tag] = $value;
   	   }
   }
   
   private function HandleSetGeaust($tag,$value){
   	switch ($tag){
   		case "g_id":
   		case "time":
   		case "title":
   		case "url":
   			$sql_array = array(
   			 "UPDATE `".geaust."` SET `".$tag."`=",
   			 "?".$value,
   			 "WHERE id=",
   			 "?".$this->data['id'],
   			);
   			$this->db->get_sql_query($this->db->clean_sql($sql_array));
   			return true;
   		break;
   	}
   	return false;
   }
   
   function ControlIfRowExist($row){
      switch ($row){
      	case "username":
      	case "password":
      	case "email":
      	case "opret_time":
      	case "last_online":
      	case "status":
      	case "page_title":
      	case "url":
      	case "post":
      	case "ActivieringKey":
      	case "ip":
      	case "TimeFormat":
      	   return true;
      	break;
      	default:
      		return false;
      	break;
      }	
   }
   
}