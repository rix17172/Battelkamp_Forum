<?php
if(!defined("in_forum"))exit;

class Style{
	private $data = array('html' => null);
	private $if   = array();
	private $for  = array();
	private $set  = array();
	private $setting;
	private $lang = array();
	
	function __construct(){
		global $setting;
		$this->setting = $setting;
	}
	
	public function load_file($file_name,$file_type){
		if(defined('in_admin'))$admin = "admin/";
		else $admin = null;
		$allow_file_type = explode(',', $this->setting->data['ALLOW_FILE_TYPE']);
		if(!in_array($file_type, $allow_file_type)){
			$this->data['html'] = "File type is not allow \"".$file_type."\"";
		}else{
			if(file_exists(first."style/".$this->setting->data['STAND_STYLE']."/".$admin.$file_name.".".$file_type)){
			   $this->data['html'] = file_get_contents(first."style/".$this->setting->data['STAND_STYLE']."/".$admin.$file_name.".".$file_type);
			}else{
				$this->data['html'] = "No style file";
			}
		}
	}
	
	public function InsertHtml($html){
		$this->data['html'] = $html;
	}
	
	public function GetStyleSetting(){
		if(!file_exists(first."style/".$this->setting->data['STAND_STYLE']."/setting.ini")){
			return array();
		}
		if(!function_exists("ReadIni")){
			IncludeExsternPage(first."include/function/ini.php");
		}
		return ReadIni(first."style/".$this->setting->data['STAND_STYLE']."/setting.ini");
	}
	
	public function convert_html(){
		if(is_object($this->setting))$this->data['html'] = str_replace("<!--style_map-->", $this->setting->data['STAND_STYLE'], $this->data['html']);
		
		if(!class_exists("Style_help"))require_once first.'include/class/style_help.php';
		$style = new Style_help();
		$pregs = preg_match_all("/<!--\s?(.*?)\((.*?)\)\s?-->/", $this->data['html'],$regs);
		
		for($i=0;$i<$pregs;$i++){
			switch ($regs[1][$i]){
				case "Use":
					$this->data['html'] = $style->_Use($regs[0][$i], $regs[2][$i], $this->data['html']);
				break;
				case "If":
				case "Elseif":
					$this->data['html'] = $style->_If($regs[0][$i], $regs[2][$i], $this->data['html'], $this->if, $regs[1][$i], $this->set);
				break;
				case "For":
					$this->data['html'] = $style->_For($regs[0][$i], $regs[2][$i], $this->data['html'], $this->for);
				break;
				case "Lang":
				    $this->data['html'] = $style->_Lang($regs[0][$i], $regs[2][$i], $this->data['html'], $this->lang, $this->set);	
				break;
				case "Set":
					$this->data['html'] = $style->_Set($regs[0][$i], $regs[2][$i], $this->data['html'], $this->set);
				break;
				case "Include":
					$this->data['html'] = $style->_Include($regs[0][$i], $regs[2][$i], $this->data['html'], $this->if, $this->for, $this->set, $this->lang);
				break;
				case "Get":
					$this->data['html'] = $style->_Get($regs[0][$i], $regs[2][$i], $this->data['html']);
				break;
				case "Post":
					$this->data['html'] = $style->_Post($regs[0][$i], $regs[2][$i], $this->data['html']);
				break;
				case "IncludeLang":
					list($html,$NewLang) = $style->_IncludeLang($regs[0][$i], $regs[2][$i], $this->data['html']);
					$this->data['html'] = $html;
					$this->lang = array_merge($this->lang,$NewLang);
				break;
			}
		}
		
		$Convert = array(
				'<!--end-->' => '<?php } ?>',
		);
		
		$this->data['html'] = str_replace(array_keys($Convert), array_values($Convert), $this->data['html']);
	}
	
	public function eval_html(){
	   eval(' ?>'.$this->data['html'].'<?php ');	
	}
	
	public function return_clean_code(){
		$html = htmlentities($this->data['html']);
		return nl2br($html);
	}
	
	public function set_if($tag,$value){
		$this->if[$tag] = $value;
	}
	
	public function load_lang($lang = array()){
		$this->lang = array_merge($lang,$this->lang);
	}
	
	public function set_for($tag,$for_array){
		if(is_array($for_array))$this->for[$tag][] = $for_array;
	}
	
	public function set($tag,$value){
		$this->set[$tag] = $value;
	}
	
	public function setArray($array){
		if(!is_array($array)){
			return false;
		}
		
		$this->set = array_merge($this->set,$array);
		return true;
	}
	
	public function emptyFor($for){
		$this->for[$for] = null;
		unset($this->for[$for]);
	}
	
	/*only for stylehelp pleas do not use this other wey*/
	public function set_all($if,$for,$set,$lang){
		$this->if   = $if;
		$this->for  = $for;
		$this->set  = $set;
		$this->lang = $lang;
	}
	
	public function return_html(){
		return $this->data['html'];
	}
}