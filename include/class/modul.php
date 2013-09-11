<?php
if(!defined("in_forum"))exit;

function IncludeModul($dir,$file){
	require_once $dir.$file;
}

class Modul{
   private $get; 
   private $page = array();
   private $dir = "include/module/";
   
   function __construct(){
   	if(defined("in_admin")){
   		$this->dir .= "admin/";
   	}
   	
   	$this->dir = first.$this->dir;
   }
   
   function SetGet($get){
   	$this->get = $get;
   }
   
   function SetPage($get,$page){
   	  $this->page[$get] = $page;
   }
   
   function RunPage(){
   	  if(GET($this->get) && !empty($this->page[GET($this->get)])){
   	  	$this->GetPage($this->page[GET($this->get)]);
   	  }else{
   	  	$this->page_404();
   	  }
   }
   
   private function page_404(){
   	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
   	header("Status: 404 Not Found");
   	$_SERVER['REDIRECT_STATUS'] = 404;
   	echo "404 - page not found";
   	exit;
   }
   
   private function GetPage($page){
   	  if(!file_exists($this->dir.$page)){
   	  	 $this->page_404();
   	  }
   	  
   	  
   	  IncludeModul($this->dir, $page);
   	  
   	  $class = $this->GetClassName($page);
   	  
   	  if(!class_exists($class)){
   	  	$this->page_404();
   	  }
   	  
   	  $_ = new $class();
   }
   
   private function GetClassName($page){
   	  return preg_replace("/\.php/", "", $page);
   }
   
}