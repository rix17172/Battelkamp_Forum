<?php
class ucp_rig{
	private $style;
	private $lang;
	private $l;
	private $db;
	private $user;
	private $setting;
	
	function __construct(){
		global $style,$lang,$db,$user,$setting;
		
		$this->style   = $style;
		$this->lang    = $lang;
		$this->db      = $db;
		$this->user    = $user;
		$this->setting = $setting;
	
		$this->SetStyle();
		$this->GetLang();
		
		if(!empty($_POST)){
			$this->DoPost();
		}
		
		$this->SetCss();
		$this->ShowStyle();
	}
	
	private function GetActiveringsKey(){
		$GenrateActivLink = function(){
			$UseChar = "abcdefghijklmnopqrstABCDEFGHIJKLMNOPQRST123456789";
			$res = null;
			for($i = 0; $i < 100; $i++) {
				$res .= $UseChar[rand(0, strlen($UseChar) - 1)];
			}
			return $res;
		};
		
		
		$g = $GenrateActivLink();
		$SN = "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?mode=activering&amp;activ_id=".$g;
		return array($SN,$g);
	}
	
	private function DoPost(){
		$error = array();
		
		if(!POST("username")){
			$error[] = $this->l['016'];
		}
		
		if(!POST("email")){
			$error[] = $this->l['018'];
		}
		
		if(!POST("password")){
			$error[] = $this->l['019'];
		}
		
		if(!POST("repassword")){
			$error[] = $this->l['020'];
		}
		
		if(empty($error) && POST("password") != POST("repassword")){
			$error[] = $this->l['021'];
		}
		
		if(empty($error)){
			//control off username
			$sql_array = array(
					"SELECT `id` FROM `".user."` WHERE username=",
					"?".POST("username"),
			);
			
			$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			
			if(!empty($row['id'])){
				$error[] = $this->l['022'];
			}else{
				//control of email
				$sql_array = array(
						"SELECT `id` FROM `".user."` WHERE email=",
						"?".POST("email"),
				);
				
				$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
				$row = $this->db->return_array($sql);
				$this->db->free_result($sql);
				
				if(!empty($row['id'])){
					$error[] = $this->l['EmailIsTaken'];
				}else{
					$time = time();
					$act  = $this->GetActiveringsKey();
					
					$insetUser = array(
							"username"       => POST("username"),
							"password"       => $this->user->hash_password(POST("password"), $time),
							"email"          => POST("email"),
							"opret_time"     => $time,
							"last_online"    => $time,
							"ip"             => $this->user->GetUserIp(),
							"post"           => 0,
							"ActivieringKey" => $act[1],
							"TimeFormat"     => $this->setting->StandTimeFormat,
					);
					
					$insertGrup = array(
							"u_id" => 0, //coming later ;)
							"g_id" => $this->setting->data['STAND_GRUP'],
					);
					
					switch ($this->setting->data['RIG_VALIATE']){
						case 1:
							$insetUser['status'] = 1;
							$this->style->set_if("is_okay", true);
						break;
						case 2:
							require_once 'include/class/Sendmail.php';
							$s = new Sendmail(POST('email'), "newuser.txt");
							$s->SetVariabel("Username", POST("username"));
							$s->SetVariabel("AktivLink", $act[0]);
							$s->Send();
							$this->style->set_if("is_warning", true);
							$this->style->set("warning",$this->l['026']);
							$insetUser['status'] = 0;
						break;
						case 3:
							$insetUser['status'] = 2;
							$this->style->set_if("is_warning", true);
							$this->style->set("warning",$this->l['027']);
						break;
					}
					
					$insertGrup['u_id'] = $this->db->Insert(user, $insetUser);
					$this->db->Insert(grup_member, $insertGrup);
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
	
	private function SetStyle(){
		$this->style->load_file("ucp_rig", "html");
	}
	
	private function GetLang(){
		$this->l = $this->lang->load_file(array(
				"ucp_rig.php",
		        'head.php',
		        'menu.php',
		        'pmmenu.php',
		));
		
		$this->style->load_lang($this->l);
	}
	
	private function SetCss(){
		$this->style->set_for("css", array(
				'name' => 'ucp',
		));
	}
	
	private function ShowStyle(){
		$this->style->convert_html();
		$this->style->eval_html();
	}
}