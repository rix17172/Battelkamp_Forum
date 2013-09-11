<?php
define("in_forum",true);
define("first","");

require_once 'include/main.php';

if(empty($_GET['title']) || empty($_GET['t'])){
	header("location:index.php");
	exit;
}

require_once 'include/class/topic.php';
require_once 'include/class/mod.php';

$topic = new topic();
$mod   = new Mod();

$lang_array = $lang->load_file(array(
		'topic.php',
		'head.php',
		'defult.php',
		'menu.php',
		'bred.php',
		'pmmenu.php'
		));

$style->set("name",$_GET['title']);

$user->update_location(str_replace("[S.name]",$_GET['title'],$lang_array['047']));

$topic_data = $topic->get_topic_data($_GET['t']);
if(!$topic_data){
	header("location:index.php");
	exit;
}

$topic->bred_forum($topic_data['f_id'], $lang_array['087'],$_GET['title']);

$topic->get_topic($topic_data['id']);
$style->set_if("may_answer", $topic->may_answer($topic_data["f_id"]));

$ini_setting_lang = $lang->get_all_file_setting_array();

if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);

for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));

$topic->save_visit($_GET['t']);

$style->set_if("may_warn", $mod->may_give_warn($topic_data['f_id']));

$style->set_if("pdf", $setting->data['AllowPDFShowTopic']);

$style->load_file("topic", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
//echo $style->return_clean_code();