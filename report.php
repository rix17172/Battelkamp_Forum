<?php
define("in_forum",true);
define("first",'');
require_once 'include/main.php';
require_once 'include/class/topic.php';

if(!empty($_GET['mode']) && $_GET['mode'] == "see_report"){
	require_once 'include/class/mod.php';
	$mod = new Mod();
	if(!$mod->may_see_report() || $mod->count_report() == 0){
		header("location:index.php");
		exit;
	}
	
	$lang_array = $lang->load_file(array(
			"see_report.php",
			"head.php",
			"menu.php",
		    'pmmenu.php'
			));
	
	if(!empty($_GET['remove']) && is_numeric($_GET['remove'])){
		$sql_array = array(
				"DELETE FROM ".report." WHERE id=",
				"?".$_GET['remove'],
				);
		$db->get_sql_query($db->clean_sql($sql_array));
		if($mod->count_report() == 0){
			header("location:index.php");
			exit;
		}
		$style->set_if("remove_ok", true);
	}
	
	$mod->get_all_my_report();
	
	$style->load_file("see_report", "html");
	$style->load_lang($lang_array);
	$style->convert_html();
	$style->eval_html();

}elseif(!empty($_GET['mode']) && $_GET['mode'] == "warn"){ 
$lang_array = $lang->load_file(array(
		"warn.php",
			"head.php",
			"menu.php",
		    'pmmenu.php'
		));	
	
  require_once 'include/class/mod.php';
  
  $mod = new Mod();
  

  
  $topic_data = $mod->get_topic_data($_GET['t_id']);

  if(!$topic_data || !$mod->may_give_warn($topic_data['f_id'])){
  	header("location:index.php");
  	exit;
  }
  
  if(!empty($_POST)){
  	if(empty($_POST['message'])){
  		$style->set_if("error", true);
  		$style->set("error",$lang_array['084']);
  	}else{
  		$sql_array = array(
  				"INSERT INTO ".warn." (report_op,grund,af,til) VALUES (",
  				"?".$_POST['option'],
  				",",
  				"?".$_POST['message'],
  				",",
  				"?".$user->data['id'],
  				",",
  				"?".$_GET['user'],
  				")",
  		);
  		$db->get_sql_query($db->clean_sql($sql_array));
  		$style->set_if("ok",true);
  	}
  }
  
  
  $sql = $db->get_sql_query("SELECT * FROM ".report_op);
  while($row = $db->return_array($sql)){
  	$style->set_for("option",array("options" => $row['options'], "id" => $row['id']));
  }

$style->load_file("warn", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
}else{


	$lang_array = $lang->load_file(array(
			"reporter.php",
			"head.php",
			"menu.php",
		    'pmmenu.php'
	));	
if(empty($_GET['is_title']) || empty($_GET['t'])){
	header('location:index.php');
	exit;
}

$ini_setting_lang = $lang->get_all_file_setting_array();
if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);
for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));
$style->set_if('map', $lang->data['map']);

$topic = new topic();

$is_topic_title = ($_GET['is_title'] == "true") ? true : false;
if(!$topic->may_report_topic($_GET['t'], $is_topic_title)){
	header("location:index.php");
	exit;
}


$sql = $db->get_sql_query("SELECT * FROM ".report_op);
while($row = $db->return_array($sql))$style->set_for("report_op",array('name' => $row['options'], 'id' => $row['id']));

if(!empty($_POST)){
	if(empty($_POST['message'])){
		$style->set_if("error",true);
		$style->set("error",$lang_array['074']);
	}else{
		$is_title = ($_GET['is_title'] == "true") ? 1 : 2;
		$sql_array = array(
				"INSERT INTO ".report." (u_id,t_id,is_title,report_op,report_reason) VALUES (",
				"?".$user->data['id'],
				",",
				"?".$_GET['t'],
				",",
				"?".$is_title,
				",",
				"?".$_POST['report_op'],
				",",
				"?".$_POST['message'],
				")",
				);
		$db->get_sql_query($db->clean_sql($sql_array));
		$style->set_if("post_ok",true);
	}
}

$style->load_file("report", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
}