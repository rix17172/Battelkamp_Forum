<?php
if(!defined("in_forum") || !defined("in_admin")){
	exit;
}

class front{
	
	private $style;
	private $lang,$l;
	private $db;
	private $setting;
	private $user;
	
	function __construct(){
		global $style,$lang,$setting,$user;
		
		$this->style = $style;
		$this->lang  = $lang;
		$this->db    = new Db();
		$this->setting = $setting;
		$this->user    = $user;
		
		switch (GET("sub")){
			case "GenSettings":
				$this->GenSetting();
			break;
			case "DatabaseSize":
				$this->DatabaseSize();
			break;
			default: 
				$this->FrontPage();
			break;
		}
		
		$this->style->set("Welkommen", sprintf($this->l['Welkommen'],$this->user->username));
		
		$this->style->convert_html();
		$this->style->eval_html();
	}
	
	private function DatabaseSize(){
		$sql = $this->db->get_sql_query("SHOW TABLE STATUS");
		while($row = $this->db->return_array($sql)){
			$Size = ($row['Data_length'] + $row['Index_length']) / 1024;
			$Name = str_replace(table_prefix, "", $row['Name']);
			$this->style->set_for("dbSize", array(
					"Name" => $Name,
					"Size" => sprintf("%.2f",$Size),
			));
		}
		
		$this->style->load_file("dbSize", "html");
		$this->l = $this->lang->load_file(array("dbSize.php","top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		$this->style->set_for("css", array("url" => 'main'));
	}
	
	private function GenSetting(){
		$this->style->load_file("GenSetting", "html");
		$this->l = $this->lang->load_file(array("GenSetting.php","top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		$this->style->set_for("css", array("url" => 'GenSetting'));
		
		if(!empty($_POST)){
			$this->SettingPost();
		}
		
		$openDir = opendir(first."style/");
		
		while($file = readdir($openDir)){
			if($file != "." && $file != ".." && !is_file($openDir.$file)){
				$this->style->set_for("styleChose", array(
						"name"    => $file,
						"IsStand" => ($file == $this->setting->STAND_STYLE),
				));
			}
		}
		
		$iniLang = $this->lang->get_all_file_setting_array();
		
		for($i=0;$i<count($iniLang);$i++){
			$this->style->set_for("StandLang", array(
					"Map"     => $iniLang[$i]['map'],
					"Flag"    => $iniLang[$i]['flag'],
					"Name"    => $iniLang[$i]['name'],
					"IsStand" => ($iniLang[$i]['map'] == $this->setting->STAND_LANG),
			));
		}
		
		$this->style->set("styleType", $this->setting->ALLOW_FILE_TYPE);
		$this->style->set_if("ipControl",($this->setting->Control_Ip == Yes));
	}
	
	private function SettingPost(){
		$error = array();
		
		if(!POST("Style"))$error[] = $this->l['MissingStyle'];
		if(!POST("styleType"))$error[] = $this->l['MissingStyleType'];
		if(!POST("standLang"))$error[] = $this->l['MissingStandLang'];
		if(!POST("controlIp"))$error[] = $this->l['MissingIpControl'];
		
		if(empty($error)){
			$this->setting->STAND_STYLE = POST("Style");
			$this->setting->ALLOW_FILE_TYPE = POST("styleType");
			$this->setting->STAND_LANG = POST("standLang");
			$this->setting->Control_Ip = (POST("controlIp") == 'true' ? Yes : No);
			
            $this->style->set_if("Okay", true);
			$this->style->set_for("Okay", array("Message" => $this->l['SettingUpdatet']));
		}else{
			$this->style->set_if("Error", true);
			for($i=0;$i<count($error);$i++){
				$this->style->set_for("Error", array("Message" => $error[$i]));
			}
		}
	}
	
	private function FrontPage(){
		$this->style->load_file("front", "html");
		$this->l = $this->lang->load_file(array("front.php","top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		$this->style->set_for("css", array("url" => 'front'));
		
		//vi ser om vi har error log ;)
		$this->style->set_if("IsErrorLog", file_exists(first."/log/error_log.txt"));
		global $admin;
		
		$admin->GetState();
		$admin->is_ther_update();
		
		if(GET("Delete") && is_numeric(GET("Delete"))){
			switch (GET("Delete")){
				case 1:
					$this->DeleteOne();
				break;
			}
		}
	}
	
	private function DeleteOne(){
		$SqlArray = array(
				"TRUNCATE TABLE `".geaust."`",
				"TRUNCATE TABLE `".last_visist."`",
				"TRUNCATE TABLE `".geaust."`",
		);
		
		for($i=0;$i<count($SqlArray);$i++){
			$this->db->get_sql_query($SqlArray[$i]);
		}
		
		$this->setting->GEAUST_ID = 0;
		$this->style->set_if("Okay", true);
		$this->style->set_for("Okay", array("Message" => $this->l['DeleteOne']));
	}
}