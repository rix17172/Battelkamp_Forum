<?php
if(!defined("in_install"))exit;

$setting = array();
require_once first.'include/class/style.php';
require_once first.'include/function/string.php';

class InstallXml{
	
	private $style;
	private $xml;
	private $page = 1;
	private $data = array();
	
	public function LoadXmlFile(){
        $this->style = new Style();
		if(file_exists("install.xml")){
			$this->xml = new SimpleXMLElement(file_get_contents("install.xml"));
		}else{
			echo "Install xml missing";
			exit;
		}
	}
	
	public function DoInstall(){
		if(empty($this->xml->info)){
			echo "XML file have no info.";
			exit;
		}
      
		foreach ($this->xml->info->data as $data){
			$attibuder = $data->attributes();
			$this->data[(string)$attibuder->tag] = (string)$attibuder->value;
		}
		
		if(!empty($_GET['page'])){
			if(!is_numeric($_GET['page']) || $_GET['page'] > $this->data['CountPage']){
				echo "Page error";
				exit;
			}
			$this->page = (int)$_GET['page'];
		}
		
		if(empty($this->xml->page)){
			echo "Page xml error";
			exit;
		}
		
		$page = $this->ReturnXmlChildrenForPageId();
		
		
		if(empty($page)){
			echo "No page to see";
			exit;
		}elseif(empty($page->loadstyle)){
			echo "No style to see";
			exit;
		}elseif(!file_exists($page->loadstyle)){
			echo "Style dosent exist";
			exit;
		}else{
			$this->style->InsertHtml(file_get_contents((string)$page->loadstyle));
		}
		
		$LangArray = array();
		foreach ($page->lang as $l){
			$lll = $l->attributes();
			if(!empty($lll->src))$LangArray = array_merge($LangArray,$this->LoadLang((string)$lll->src));
		}
		if(!empty($LangArray))$this->style->load_lang($LangArray);
		
		foreach ($page->dofile as $do){
			$dofile = $do->attributes();
			if(file_exists((string)$dofile->src)){
				require_once (string)$dofile->src;
			}else{
				echo "dofile: ".$dofile->src." dosent exist";
			}
		}
		
		foreach ($page->set as $set){
			$insert = $set->attributes();
			$this->style->set((string)$insert->tag, (string)$insert->value);
		}
		
		if(!empty($page->converthtml) && $page->converthtml == "true"){
			$this->style->convert_html();
		}
		
		if(!empty($page->evalhtml) && $page->evalhtml == "true"){
			$this->style->eval_html();
		}
		
	}
	
	private function LoadLang($lang){
		if(file_exists($lang)){
			require_once $lang;
			return (is_array($lang)) ? $lang : array(); 
		}else{
			echo "File dosent exist: ".$lang;
			exit;
		}
	}
	
	private function ReturnXmlChildrenForPageId(){
		foreach ($this->xml->page as $page){
			$p = $page->attributes();
			if($p->id == $this->page){
				return $page;
				break;
			}
		}
		return false;
	}
	
}