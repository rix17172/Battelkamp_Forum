<?php
define("in_forum",true);
define("first","");

if(empty($_GET['name']) && empty($_GET['f'])){
	header('location:index.php');
	exit;
}


require_once 'include/main.php';


$lang_array = $lang->load_file(array(
		'kf.php',
		'forum.php',
		'head.php',
		'menu.php',
		'bred.php',
		'pmmenu.php'
		));


$user->update_location(str_replace("[S.name]", $_GET['name'], $lang_array['043']));


$style->Set("name", $_GET['name']);


$ini_setting_lang = $lang->get_all_file_setting_array();
if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);
for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));
$style->set_if('map', $lang->data['map']);


require_once 'include/class/forum.php';
$forum = new Forum();


$style->set_if("may_see_topic", $forum->access_topic($_GET['f']));
if(!$forum->access_forum($_GET['f'])){
	header('location:index.php');
	exit;
}


$forum->get_kat_and_forum($_GET['f']);
$forum->show_topic_title($_GET['f']);
$style->set_if("Show_no_fk", true);
$style->set_if("may_start", $forum->may_post_title($_GET['f']));

$forum->bred_forum($_GET['f'],$lang_array['087']);

$style->load_file("forum", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
//echo $style->return_clean_code();