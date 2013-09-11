<?php
if(!defined("in_admin")){
	exit;
}

class adminMod{
	private $lang,$l;
	private $style;
	private $db;
	private $adminAccess;
	private $user;
	
	function __construct(){
		global $lang,$style,$admin_access,$user;
		
		$this->lang        = $lang;
		$this->style       = $style;
		$this->db          = new Db();
		$this->user        = $user;
		$this->adminAccess = $admin_access;
		
		switch (GET("sub")){
			case 'right':
				$this->right();
			break;
			default: 
				$this->front();
			break;
		}
		
		$this->style->set("Welkommen", sprintf($this->l['Welkommen'],$this->user->username));
		
		$this->style->convert_html();
		$this->style->eval_html();
	}
	
	private function right(){
		
		if(!GET("id")){
			header("location:?page=admin");
			exit;
		}
		
		$sql_array = array(
				"SELECT * FROM `".grup_name."` WHERE `id`=",
				"?".GET("id"),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if(empty($row['id'])){
			header("location:?page=admin");
			exit;
		}
		
		$this->style->setArray($row);
		
		$this->l = $this->lang->load_file(array(
				'top.php',
				'leftMenu.php',
				'right.php',
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("right", "html");
		$this->style->set_for("css", array('url' => 'main'));
		
		if(POST("post")){
		   $this->doPost(POST("frontPage"), 2);
		   $this->doPost(POST("forum"), 3);
		   $this->doPost(POST("user"), 4);
		   $this->doPost(POST("admin"), 5);
		   $this->style->set_if("Okay", true);
		   $this->style->set_for("Okay", array(
		   		'Message' => $this->l['changeOkay'],
		   ));
		}
		
		$sql_array = array(
				"SELECT `a_id` FROM `".admin_access."` WHERE `a_id`!='1' AND `g_id`=",
				"?".GET("id"),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			switch ($row['a_id']){
				case '2':
					$this->style->set_if("frontSee", true);
				break;
				case '3':
					$this->style->set_if("forumSee", true);
				break;
				case '4':
					$this->style->set_if("userSee", true);
				break;
				case '5':
					$this->style->set_if("adminSee", true);
				break;
			}
		}
		
	}
	
	private function doPost($see,$find){
		$sql_array = array(
				"SELECT `id` FROM `".admin_access."` WHERE `a_id`=",
				"?".$find,
				"AND `g_id`=",
				"?".GET("id"),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if($see == 'true' && empty($row['id'])){
			$this->db->Insert(admin_access, array(
					'a_id' => $find,
					'g_id' => GET("id"),
			));
		}elseif($see == 'false' && !empty($row['id'])){
			$this->db->Delete(admin_access, array(
					'a_id' => $find,
					'g_id' => GET("id"),
			));
		}
	}
	
	private function front(){
		$this->l = $this->lang->load_file(array(
			    'top.php',
				'leftMenu.php',
				'adminFront.php',	
		));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("adminFront", "html");
		$this->style->set_for("css", array('url' => 'main'));
		
		$sql = $this->db->get_sql_query("SELECT * FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("grup", $row);
		}
	}
}