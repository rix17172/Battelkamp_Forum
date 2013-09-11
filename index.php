<?php
define("in_forum",true);
define("first","");
require_once 'include/main.php';
require_once 'include/class/forum.php';
require_once 'include/class/mod.php';

$lang_array = $lang->load_file(array(
		'front_page.php',
		'head.php',
		'kf.php',
		'menu.php',
		'pmmenu.php',
		));

$ini_setting_lang = $lang->get_all_file_setting_array();

if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);

for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));

$style->set_if('map', $lang->data['map']);

$user->update_location($lang_array['001']);

$forum = new Forum();
$forum->get_kat_and_forum();

$mod = new Mod();

$may_se_report = $mod->may_see_report();
$style->set_if("may_see_report",$may_se_report);
if($may_se_report != 0){
	$num_report = $mod->count_report();
	$style->set("count_report",$num_report);
	$style->set_if("count_report",$num_report);
}

require_once 'include/class/admin.php';

$style->set_if("admin_tool", may_visit_admin());

$style->load_file("front_page", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
//echo $style->return_clean_code();
