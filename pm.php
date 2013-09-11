<?php
define("in_forum",true);
define("first","");
require_once 'include/main.php';

//All in this page has been changede in v.0.0.2!

$pm = new PrivateMessage();

if($user->data['is_geaust']){
	header("location:index.php");
	exit;
}

if(!GET("page")){
	$_GET['page'] = "Front";
}

$style->load_lang($lang->load_file(array("pmmenu.php","head.php")));
$style->set_if("pm_to", PrivateMessage::to);
$style->set_if("pm_from", PrivateMessage::from);

switch (GET("page")){
	case "Front":
		$style->load_file("pmFront", "html");
		$pm_title = $pm->GetMessageTitle();
		for($i=0;$i<count($pm_title);$i++){
			$style->set_for("title", $pm_title[$i]);
		}
		$style->load_lang($lang->load_file(array("pm_front.php","menu.php")));
	break;
	case "Read":
		if(!GET('id')){
			header('HTTP/1.1 404 Not Found');
			exit("<strong>404 - Not found</strong>");
		}
		$Title = $pm->GetPMListTilte(GET("id"));
		
		if(!$Title){
			header('HTTP/1.1 404 Not Found');
			exit("<strong>404 - Not found</strong>");			
		}
		
		if(GET('Delete') && is_numeric(GET('Delete')) && GET('is_title')){
			$pm->DeletePM( GET("Delete"),(GET('is_title') == "true"));
		}
		
		$style->set("PMTitle", $Title);
		
		$topic = $pm->GetPmMessageTopic();
		for($i=0;$i<count($topic);$i++){
			$style->set_for("pm", $topic[$i]);
		}
		
		$style->set_if("MessageCount", count($topic));
		
		$style->load_file("pmRead", "html");
		$style->load_lang($lang->load_file(array('pm_read.php','menu.php')));
		$style->set('id', GET("id"));
	break;
	default: 
		header('HTTP/1.1 404 Not Found');
		exit("<strong>404 - Not found</strong>");
	break;
}

$style->convert_html();
//echo $style->return_clean_code();
$style->eval_html();