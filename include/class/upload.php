<?php
if(!defined("in_forum")){
	exit;
}

function uploadImage(){
	return array(
			'gif',
			'jpeg',
			'jpg',
			'png',
			'tga',
			'tif',
			'tiff',
	);
}

class Upload{
	private $dir;
	private $fileName;
	private $file;
	private $fileContext = array();
	
	public $isFile = false;
	
	function __construct($dir,$fileName){
		if(!is_dir($dir)){
			$this->__Error("Dir don't exist");
		}
		
		$this->dir = $dir;
		$this->fileName = $fileName;
		
		$this->isFile = (!empty($_FILES[$this->fileName]));
		
		if(!$this->isFile){
			return;
		}
		
		$this->file = $_FILES[$this->fileName];
		
		$this->Init();
		
		//control if there is error
		if($this->fileContext['error']){
			$this->__Error($this->fileContext['error']);
		}
	}
	
	public function controlSize($allowSize){
		return ($this->fileContext['size'] < $allowSize);//return true if true else false ;)
	}
	
	public function controlExtension($extension){
		$ex = explode(".", $this->fileContext['name']);
		$end = end($ex);
		
		return in_array($end, $extension);
	}
	
	public function Save($name = null){
		if(!$name){
			$nameNumber = $this->GetRand("123456789", 4);
			$nameLetter = $this->GetRand("qwertyuiopasdfghjklmnbvcxz", 10);
			$ex = explode(".", $this->fileContext['name']);
			$end = end($ex);
			$name = $nameNumber."_".$nameLetter.".".$end;//like that ;)
		}
		
		return array('is_move' => move_uploaded_file($this->fileContext['tmp_name'],$this->dir.$name), 'dir' => $this->dir.$name, 'name' => $name);
	}
	
	private function GetRand($use,$length){
		$return = "";
		for($i=0;$i<$length;$i++){
			$return .= $use[rand(0, (strlen($use)-1))];
		}
		return $return;
	}
	
	private function Init(){
		$this->fileContext = array(
				"name"     => $this->file['name'],
				"type"     => $this->file['type'],
				"size"     => $this->file['size'],
				"tmp_name" => $this->file['tmp_name'],
				"error"    => $this->file['error'],
		);
	}
	
	private function __Error($errorMsg){
		exit($errorMsg);
	}
}