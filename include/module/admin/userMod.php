<?php
if(!defined("in_admin")){
	exit;
}

class userMod{

	private $style;
	private $lang,$l;
	private $user;
	private $db;
	private $cache = array();
	
	function __construct(){
		global $style,$lang,$user;
		
		$this->style = $style;
		$this->lang  = $lang;
		$this->user  = $user;
		$this->db    = new Db();
		
		
		switch (GET("sub")){
			case 'userList':
				$this->userList();
			break;
			case 'userInfo':
				$this->userInfo();
			break;
			case 'grupList':
				$this->grupList();
			break;
			case 'grupInfo':
				$this->grupInfo();
			break;
			case 'newGrup':
			    $this->newGrup();
			break;
			case 'tilSettings':
				$this->tilSettings();
			break;
			default: 
				$this->frontPage();
			break;
		}
		
		$this->style->set("Welkommen", sprintf($this->l['Welkommen'],$this->user->username));
		
		$this->style->convert_html();
		$this->style->eval_html();
	}
	
	private function tilSettings(){
		global $setting;
		
		$this->l = $this->lang->load_file(array(
				'leftMenu.php',
				'top.php',
				'tilSettings.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("tilSettings", "html");
		$this->style->set_for("css", array('url' => 'main'));
		
		if(POST("post")){
			$error = array();
			if(!in_array(POST("hashName"), hash_algos())){
				$error[] = $this->l['hashDenaid'];
			}
			
			if(empty($error)){
				$setting->STAND_GRUP  = POST("startGrup");
				$setting->PASS_HASH   = POST("hashName");
				$setting->RIG_VALIATE = POST("opretKontrol");
				$this->style->set_if("Okay", true);
				$this->style->set_for("Okay", array(
						'Message' => $this->l['Updatet'],
				));
			}else{
				$this->style->set_if("Error", true);
				for($i=0;$i<count($error);$i++){
				$this->style->set_for("Error", array(
						'Message' => $error[$i],
				));
				}
			}
		}
		
		$hash = hash_algos();
		for($i=0;$i<count($hash);$i++){
			$this->style->set_for("hash", array(
					'hash'  => $hash[$i],
					'isUse' => ($hash[$i] == $setting->PASS_HASH),
			));
		}
		
		$sql = $this->db->get_sql_query("SELECT * FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("standGrup", array_merge($row,array(
					'isStand' => ($row['id'] == $setting->STAND_GRUP),
			)));
		}
		
		$this->style->set_if("kontrolId",$setting->RIG_VALIATE);
		
	}
	
	private function newGrup(){
		$this->l = $this->lang->load_file(array(
				'leftMenu.php',
				'top.php',
				'newGrup.php',
				));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("newGrup", "html");
		$this->style->set_for("css", array('url' => 'main'));
		
		if(POST("name")){
		 $id = $this->db->Insert(grup_name, array(
		 		'name'      => POST('name'),
		 		'show_team' => No,
		 ));
		 
		 header("location: ?page=user&sub=grupInfo&gid=".$id);
		 exit;
		}
	}
	
	private function grupInfo(){
		
		if(!GET("gid")){
			$this->header("?page=user");
		}
		
		$sql_array = array(
				"SELECT * FROM `".grup_name."` WHERE `id`=",
				"?".GET("gid"),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$this->cache['grupInfo'] = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if(empty($this->cache['grupInfo']['id'])){
			$this->header('?page=user');
		}
		
		$this->l = $this->lang->load_file(array(
				'top.php',
				'leftMenu.php',
				'grupInfo.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("grupInfo", "html");
		$this->style->set_for("css", array("url" => 'main'));
		
		$this->style->setArray($this->cache['grupInfo']);
		
		if(POST("code")){
			switch (POST("code")){
				case '1':
					$this->changeData();
				break;
				case '2':
					$this->changeAdmindata();
				break;
				case '3':
					$this->deleteGrup();
				break;
			}
		}
		
		global $admin_access;
		
		$sql_array = array(
				"SELECT `id` FROM `".admin_access."` WHERE `a_id`='".$admin_access['admin_tool']."'"." AND `g_id`=",
				"?".GET('gid'),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		$this->style->set_if("isAdmin", (!empty($row['id'])));
		
	}
	
	private function deleteGrup(){
		$error = array();
		
		global $setting;
		
		if($setting->STAND_GRUP == GET('gid') || GET('gid') == '2'){
			$error[] = $this->l['grupIsStand'];
		}else{
			$sql_array = array(
				"UPDATE `".grup_member."` SET `g_id`=",
				"?".$setting->STAND_GRUP,
				"WHERE `g_id`=",
			    "?".GET('gid'),
			);
			
			$this->db->get_sql_query($this->db->clean_sql($sql_array));
			
			$this->db->Delete(grup_name, array('id' => GET('gid')));
			
			header('location: ?page=user&sub=grupList');
			exit;
		}
		
		if(!empty($error)){
			$this->style->set_if("Error", true);
			for($i=0;$i<count($error);$i++){
				$this->style->set_for("Error", array('Message' => $error[$i]));
			}
		}
	}
	
	private function changeAdmindata(){
		global $admin_access;
		
		$sql_array = array(
				"SELECT `id` FROM `".admin_access."` WHERE `a_id`='".$admin_access['admin_tool']."'"." AND `g_id`=",
				"?".GET('gid'),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if(empty($row['id']) && POST('doAdmin')){
			$this->db->Insert(admin_access, array(
					'a_id' => $admin_access['admin_tool'],
					'g_id' => GET('gid'),
			));
		}elseif(!empty($row['id']) && !POST('doAdmin')){
			$this->db->Delete(admin_access, array(
					'a_id' => $admin_access['admin_tool'],
					'g_id' => GET('gid'),
			));
		}
		
		$this->style->set_if("Okay", true);
		$this->style->set_for("Okay", array('Message' => $this->l['nowAdmin']));
	}
	
	private function changeData(){
		$error = array();
		
		if(!POST("name")){
			$error[] = $this->l['noName'];
		}else{
			
			$showTeam = '0';
			
			if($this->cache['grupInfo']['show_team'] == Yes && !POST("adminList")){
				$showTeam = No;
			}elseif($this->cache['grupInfo']['show_team'] == No && POST("adminList")){
				$showTeam = Yes;
			}else{
				$showTeam = $this->cache['grupInfo']['show_team'];
			}
			
			$sql_array = array(
					"UPDATE `".grup_name."` SET `name`=",
					"?".POST("name"),
					", `show_team`=",
					"?".$showTeam,
					"WHERE `id`=",
					"?".GET('gid'),
			);
			
			$this->style->set("show_team",$showTeam);
			$this->style->set("name",POST("name"));
			
			$this->db->get_sql_query($this->db->clean_sql($sql_array));
			$this->style->set('name', POST('name'));
		}
		
		if(empty($error)){
			$this->style->set_if("Okay", true);
			$this->style->set_for("Okay", array('Message' => $this->l['dataIsChange']));
		}else{
			$this->style->set_if("Error", true);
			for($i=0;$i<count($error);$i++){
				$this->style->set_for("Error", array('Message' => $error[$i]));
			}
		}
	}
	
	private function header($location){
		header("location: ".$location);
		exit;
	}
	
	private function grupList(){
		$this->l = $this->lang->load_file(array(
				'top.php',
				'leftMenu.php',
				'grupList.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("grupList", "html");
		$this->style->set_for("css", array("url" => 'main'));
		
		$sql = $this->db->get_sql_query("SELECT * FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$sqll = $this->db->get_sql_query("SELECT COUNT(id) FROM `".grup_member."` WHERE `g_id`='".$row['id']."'");
			$this->style->set_for("grupList", array_merge($row,array('memberCount' => $this->db->return_from_count($sqll))));
		}
	}
	
	private function userInfo(){
		if(!GET("uid") || !is_numeric(GET("uid"))){
			header("location:?page=user");
			exit;
		}
		
		//vi finder brugeren ;)
		$sql_array = array(
				"SELECT * FROM `".user."` WHERE `id`=",
				"?".GET("uid"),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if(empty($row['id'])){
			header("location:?page=user");
			exit;
		}
		
		$sql_array = array(
				'SELECT `g_id` FROM `'.grup_member.'` WHERE `u_id`=',
				'?'.$row['id'],
		);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$g   = $this->db->return_array($sql);
		$this->db->free_result($sql);
		$this->cache['grupId'] = $g['g_id'];
		
		$this->cache['userData'] = $row;
		
		$this->l = $this->lang->load_file(array(
				'top.php',
				'leftMenu.php',
				'userInfo.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("userInfo", "html");
		$this->style->set_for("css", array("url" => 'main'));
		
		$this->style->set("username", $row['username']);
		$this->style->set("email",    $row['email']);
		
		if(POST("code")){
			$this->handleUserInfoPost();
		}
		
		$g = $this->lang->LoadFileFromDest("defult.php");
		
		$this->style->set_for("grup", array(
				'id'       => 0,
				'name'     => $g['Geaust'],
				'isMember' => ($this->cache['grupId'] == 0),
		));
		
		$sql = $this->db->get_sql_query("SELECT `id`,`name` FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("grup", array(
					'id'       => $row['id'],
					'name'     => $row['name'],
					'isMember' => ($this->cache['grupId'] == $row['id']),
			));
		}
	}
	
	private function handleUserInfoPost(){
		switch (POST("code")){
			case '1':
				$this->changeUserData();
			break;
			case '2':
				$this->changePassword();
			break;
			case '3':
				$this->changeGruppe();
			break;
			case '4':
				$this->deleteUser();
			break;
		}
	}
	
	private function deleteUser(){
		
		$id   = $this->cache['userData']['id'];
		$nick = $this->cache['userData']['username']; 
		
		switch (POST("chose")){
			case "noneAll":
				$this->db->Delete(grup_member, array('u_id' => $id));
				$this->db->Delete(warn, array('til' => $id));
				$this->db->Delete(report, array('u_id' => $id));
				$this->db->Delete(user, array('id' => $id));
				
				$sql_array = array(
						"DELETE FROM `".last_visist."` WHERE `is_user`='".Yes."' AND `u_id`=",
						"?".$id,
				);
				$this->db->get_sql_query($this->db->clean_sql($sql_array));
				
				$sql_array = array(
						"UPDATE `".topic_message."` SET `is_user`='".No."' WHERE `is_user`='".Yes."' AND `u_id`=",
						"?".$id,
				);
				$this->db->get_sql_query($this->db->clean_sql($sql_array));
				
				$sql_array = array(
						"UPDATE `".topic_title."` SET `is_user`='".No."' WHERE `is_user`='".Yes."' AND `user_id`=",
						"?".$id,
				);
				$this->db->get_sql_query($this->db->clean_sql($sql_array));
				
				$sql_array = array(
						'SELECT `id` FROM `'.pm_title.'` WHERE `from_id`=',
						'?'.$id,
						'OR `to_id`=',
						'?'.$id,
				);
				
				$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
				while($row = $this->db->return_array($sql)){
					$this->db->Delete(pm_message, array('pm_id' => $row['id']));
					$this->db->Delete(pm_title, array('id' => $row['id']));
				}
			break;
			case "all":
				$this->db->Delete(grup_member, array('u_id' => $id));
				$this->db->Delete(warn, array('til' => $id));
				$this->db->Delete(report, array('u_id' => $id));
				$this->db->Delete(user, array('id' => $id));
				$this->db->Delete(last_visist, array('is_user' => Yes, 'u_id' => $id));
				$this->db->Delete(topic_message, array('is_user' => Yes, 'u_id' => $id));
				$this->db->Delete(topic_title, array('is_user' => Yes, 'user_id' => $id));
				
				$sql_array = array(
						'SELECT `id` FROM `'.pm_title.'` WHERE `from_id`=',
						'?'.$id,
						'OR `to_id`=',
						'?'.$id,
				);
				
				$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
				while($row = $this->db->return_array($sql)){
					$this->db->Delete(pm_message, array('pm_id' => $row['id']));
					$this->db->Delete(pm_title, array('id' => $row['id']));
				}
			break;
		}
		
		$this->style->set_if("Okay", true);
		$this->style->set_for("Okay", array('Message' => $this->l['userIsDeletet']));
	}
	
	private function changeGruppe(){
		
		
		$sql_array = array(
				"UPDATE `".grup_member."` SET `g_id`=",
				"?".POST("newGrup"),
				"WHERE `u_id`=",
				"?".$this->cache['userData']['id'],
		);
		
		$this->db->get_sql_query($this->db->clean_sql($sql_array));
		
		$this->cache['grupId'] = POST("newGrup");
		
		$this->style->set_if("Okay", true);
		$this->style->set_for("Okay", array(
				'Message' => $this->l['grupOkay'],
		));
	}
	
	private function changePassword(){
		$error = array();
		
		if(!POST("password")){
			$error[] = $this->l['noPassword'];
		}elseif(!POST("passwordA")){
			$error[] = $this->l['noPasswordA'];
		}elseif(POST("password") != POST("passwordA")){
			$error[] = $this->l['passwordFail'];
		}else{
			$newPassword = $this->user->hash_password(POST("password"), $this->cache['userData']['opret_time']);
			$this->db->get_sql_query($this->db->clean_sql(array(
					"UPDATE `".user."` SET `password`=",
					"?".$newPassword,
					"WHERE `id`=",
					"?".$this->cache['userData']['id'],
			)));
			
			$this->style->set_if("Okay", true);
			$this->style->set_for("Okay", array(
					'Message' => $this->l['PasswordOkay'],
			));
		}
		
		if(!empty($error)){
			$this->style->set_if("Error", true);
			for($i=0;$i<count($error);$i++){
				$this->style->set_for("Error", array("Message" => $error[$i]));
			}
		}
	}
	
	private function changeUserData(){
		$error = array();
		
		if(!POST("username")){
			$error[] = $this->l['noUsername'];
		}else{
			$sql_array = array(
					'SELECT `id` FROM `'.user.'` WHERE `id`!=',
					'?'.GET("uid"),
					' AND `username`=',
					"?".POST("username"),
			);
			$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			
			if(!empty($row['id'])){
				$error[] = $this->l['usernameExist'];
			}
		}
		
		if(!POST("email")){
			$error[] = $this->l['noEmail'];
		}
		
		if(empty($error)){
			$this->style->set("username", POST("username"));
			$this->style->set("email",    POST("email"));
			
			$this->db->get_sql_query($this->db->clean_sql(array(
					"UPDATE `".user."` SET `username`=",
					"?".POST("username"),
					", `email`=",
					"?".POST("email"),
					"WHERE `id`=",
					"?".GET("uid"),
			)));
			
			$this->style->set_if("Okay", true);
			$this->style->set_for("Okay", array("Message" => $this->l['changeOkay']));
		}else{
			$this->style->set_if("Error", true);
			for($i=0;$i<count($error);$i++){
				$this->style->set_for("Error", array("Message" => $error[$i]));
			}
		}
	}
	
	private function userList(){
		$this->l = $this->lang->load_file(array(
				'top.php',
				'leftMenu.php',
				'userList.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("userList", "html");
		$this->style->set_for("css", array('url' => 'main'));
		
		$sql = $this->db->get_sql_query("SELECT * FROM `".user."`");
		while($row = $this->db->return_array($sql)){
			$row['opret_time'] = date($this->user->TimeFormat,$row['opret_time']);
			$row['last_online'] = date($this->user->TimeFormat,$row['last_online']);
			if($row['ip'] != null){
			$row['host'] = gethostbyaddr($row['ip']);
			}
			$row['showHost'] = ($row['ip'] != null);
			
			$this->style->set_for("userList", $row);
		}
	}
	
	private function frontPage(){
		$this->l = $this->lang->load_file(array(
				'top.php',
				'leftMenu.php',
				'userFront.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("userFront", "html");
		$this->style->set_for("css", array("url" => "main"));
		
		$this->setStat();
		
	}
	
	private function setStat(){
		$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".user."`");
		$this->style->set("countUser", $this->db->return_from_count($sql));
		
		$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".user."` WHERE `status`='1'");
		$this->style->set("validUser", $this->db->return_from_count($sql));
		
		$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".user."` WHERE `status`!='1'");
		$this->style->set("nonUser", $this->db->return_from_count($sql));
		
		$sql = $this->db->get_sql_query("SELECT COUNT(id) FROM `".grup_name."`");
		$this->style->set("countGrup", $this->db->return_from_count($sql));
	}
}