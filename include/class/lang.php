<?php
if(!defined("in_forum"))exit;

class Lang{
	public $data = array();
	
	private $db;
	private $setting;
	private $user;
	
	function __construct(){
		global $db,$setting,$user;
		$this->db      = $db;
		$this->setting = $setting;
		$this->user    = $user;
	}
	
	private function set_lang($map){
		if($this->user->data['is_user']){
			setcookie("lang", $map, strtotime("+1 year"), path);
		}else{
			$_SESSION['lang'] = $map;
		}
	}
	
	private function get_data(){
		if(!empty($_SESSION['lang'])){
			return array('lang' => $_SESSION['lang'], 'sort' => 'session');
		}elseif(!empty($_COOKIE['lang'])){
			return array('lang' => $_COOKIE['lang'], 'sort' => 'cookie');
		}else{
			return false;
		}
	}
	
	private function getDefaultLanguage() {
		if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			return $this->parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
		else
			return $this->parseDefaultLanguage(NULL);
	}
	
	private function parseDefaultLanguage($http_accept, $deflang = "dk") {
		if(isset($http_accept) && strlen($http_accept) > 1)  {
			# Split possible languages into array
			$x = explode(",",$http_accept);
			foreach ($x as $val) {
				#check for q-value and create associative array. No q-value means 1 by rule
				if(preg_match("/(.*);q=([0-1]{0,1}\.\d{0,4})/i",$val,$matches))
					$lang[$matches[1]] = (float)$matches[2];
				else
					$lang[$val] = 1.0;
			}
	
			#return default language (highest q-value)
			$qval = 0.0;
			foreach ($lang as $key => $value) {
			if ($value > $qval) {
				$qval = (float)$value;
					$deflang = $key;
				}
				}
				}
				return strtolower($deflang);
	}
	
	public function get_lang_data(){
		   $lang_data = $this->get_data();
           $browser_lang = $this->getDefaultLanguage();
			if($lang_data['lang']){
				$lang_map = $lang_data['lang'];
			}elseif(file_exists('lang/'.$browser_lang)){
				$lang_map = $browser_lang;
			}else{
				$lang_map = $this->setting->data['STAND_LANG'];
			}
				
		$this->data['map'] = $lang_map;
	}
	
	public function LoadFileFromDest($LangDest){
		if(!file_exists(first.'lang/'.$this->data['map']."/".$LangDest)){
			echo "No lang file ".$LangDest." in this function '".__FUNCTION__."'";
		}
		require_once first.'lang/'.$this->data['map']."/".$LangDest;
		
		return (empty($lang) || !is_array($lang)) ? array() : $lang;
	}
	
	public function load_file($array = array()){
		if(defined('in_admin'))$admin = "admin/";
		else $admin = null;
		for($i=0;$i<count($array);$i++){
			if(file_exists(first.'lang/'.$this->data['map'].'/'.$admin.$array[$i])){
				require_once first.'lang/'.$this->data['map'].'/'.$admin.$array[$i];
			}else{
				exit("No lang file ".$array[$i]);
			}
		}
		
		return empty($lang) ? array() : $lang;
	}
	
	public function get_all_file_setting_array(){
		$array = array();
		$dirname = first."lang/"; //Hvor skal den lede efter filer?
		$dirhandle = opendir($dirname); //Åben mappen
		while($file = readdir($dirhandle)) //Loop gennem mappen
		{
			if ($file != "." && $file != "..") //Fjern . og ..
			{
				if (!is_file($dirname.$file) && file_exists($dirname.$file."/setting.ini")) //Find ud af om det er en fil eller en mappe
				{
					$ini_array = parse_ini_file($dirname.$file."/setting.ini",true);
					$array[] = array(
							'name' => $ini_array['setting']['name'],
							'flag' => first.$ini_array['setting']['flag'],
							'map'  => $file,
							);
				}
			}
		}
		return $array;
	}
	
	public function new_lang($map_name){
		if(file_exists('lang/'.$map_name)){
			$new_lang = $map_name;
		}else{
			$new_lang = $this->setting->data['STAND_LANG'];
		}
		$this->set_lang($new_lang);
		header('location:index.php');
		exit;
	}
	
}