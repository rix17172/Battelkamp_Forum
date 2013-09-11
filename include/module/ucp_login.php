<?php 
class ucp_login{
	
	private $style,$lang,$MyLang,$db,$user;
	
	function __construct(){
		global $style,$lang,$db,$user;
		$this->style = $style;	
		$this->lang  = $lang;
		$this->db    = $db;
		$this->user  = $user;
		
		$this->IncludeLang();
		
		if(!empty($_POST)){
			$this->ControlPost();
		}
		
		$this->IncludeStyle();
		
		$this->SetCSS();
		$this->ShowHTML();
		
	}
	
	private function ControlPost(){
		$error = array();
		if(!POST("username")){
			$error[] = $this->MyLang['034'];
		}
		if(!POST("password")){
			$error[] = $this->MyLang['035'];
		}
		
		if(empty($error)){
			$sql_array = array(
					"SELECT `id`,`opret_time`,`password`,`status`,`ip` FROM `".user."` WHERE `username`=",
					"?".POST("username"),
			);
			
			$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			
			if(empty($row['id'])){
				$error[] = $this->MyLang['036'];
			}else{
				if($row['password'] == $this->user->hash_password(POST("password"), $row['opret_time'])){
					if($row['status'] == 1){
						if(!POST("rem")){
							$_SESSION['user_name'] = POST("username");
							$_SESSION['user_id']   = $row['id'];
						}else{
							SetNewCookie("user_name", POST("username"));
							SetNewCookie("user_id", $row['id']);
						}
						
						$this->KontrolIP($row['id'],$row['ip']);
						
						header("location:?mode=login_ok");
						exit;
					}else{
						$error[] = $this->MyLang['037'];
					}
				}else{
					$error[] = $this->MyLang['036'];
				}
			}
			
		}
		
		if(!empty($error)){
			for($i=0;$i<count($error);$i++){
				$this->style->set_for("error", array(
					"error" => $error[$i],	
				));
			}
			
			$this->style->set_if("is_error", true);
		}
	}
	
	private function KontrolIP($id,$saveIp = null){
		if($saveIp != $this->user->GetUserIp()){
			$this->user->UpdateUserIp($id);
		}
	}
	
	private function IncludeStyle(){
		$this->style->load_file("ucp_login", "html");
	}
	
	private function IncludeLang(){
		$this->MyLang = $this->lang->load_file(array(
				'ucp_login.php',
		        'menu.php',
		        'pmmenu.php',
		        'head.php',
		));
		$this->style->load_lang($this->MyLang);
	}
	
	private function SetCSS(){
		$this->style->set_for("css", array('name' => 'ucp'));
	}
	
	private function ShowHTML(){
		$this->style->convert_html();
		$this->style->eval_html();
	}
}