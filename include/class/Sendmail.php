<?php
if(!defined("in_forum"))exit;

class Sendmail{
	private $data     = array();
	private $variabel = array();
	private $LangData;
	
	function __construct($to,$file){
		global $lang;
		
		$this->data['TO'] = $to;
		$this->LangData = $lang->data;
		$this->LoadMailFile($file);
	}
	
	public function SetVariabel($name,$value){
		$this->variabel[$name] = $value;
	}
	
	public function Send(){
		global $setting;
		$header = null;
		$pregs = preg_match_all("/<!--(.*?)\((.*?)\)-->/", $this->data['file'],$regs);
		
		for($i=0;$i<$pregs;$i++){
			
			switch ($regs[1][$i]){
				case "SetSender":
					$this->data['Sender'] = $regs[2][$i];
					$this->data['file'] = str_replace($regs[0][$i], "", $this->data['file']);
				break;
				case "SetTitle":
					$this->data['Title'] = $regs[2][$i];
					$this->data['file'] = str_replace($regs[0][$i], "", $this->data['file']);
				break;
				default:
					foreach ($this->variabel as $tag => $name){
						if($tag == $regs[1][$i]){
							$this->data['file'] = str_replace($regs[0][$i], $name, $this->data['file']);
							break;
						}
					}
				break;
			}
		}
		
		if(!empty($this->data['file'])){
		$header  = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        if(!empty($this->data['Sender'])){
        	$header .= "from:".$this->data['Sender'];
        }else{
        	$header .= "from:".$setting->data['SiteName'];
        }
        if(!@mail($this->data['TO'],((empty($this->data['Title'])) ? 'No title' : $this->data['Title']),$this->data['file'],$header)){
        	echo "The mail dont sendt";
        	exit;
        }
		}
	}
	
	private function LoadMailFile($file){
		if(file_exists(first."lang/".$this->LangData['map'].'/email/'.$file)){
			$this->data['file'] = file_get_contents(first."lang/".$this->LangData['map'].'/email/'.$file);
		}else{
			echo "Mail file dosent exist";
			exit;
		}
	}
}