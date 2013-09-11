<?php
define('in_forum',true);
define('in_admin',true);
define('first','../');

require_once '../include/main.php';

$lang_array = $lang->load_file(array(
		'login.php',
		));

$ini_setting_lang = $lang->get_all_file_setting_array();
if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);
for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));
$style->set_if('map', $lang->data['map']);

if(!empty($_POST)){
	$error = array();
	if(empty($_POST['username']))$error[] = $lang_array['no_username'];
	if(empty($_POST['password']))$error[] = $lang_array['no_password'];
	
	if(empty($error)){
		$sql_array = array(
				"SELECT `password` FROM ".user." WHERE username=",
				"?".$_POST['username'],
				);
		$sql = $db->get_sql_query($db->clean_sql($sql_array));
		$row = $db->return_array($sql);
		$db->free_result($sql);
		
		if(empty($row['password'])){
			$style->set_if("is_error", true);
			$style->set_for("error", array('error' => $lang_array['invalid']));
		}else{
			if($_POST['username'] != $user->data['username']){
				$style->set_if("is_error", true);
				$style->set_for("error", array('error' => $lang_array['invalid']));				
			}elseif($user->hash_password($_POST['password'], $user->data['opret_time']) != $row['password']){
				$style->set_if("is_error", true);
				$style->set_for("error", array('error' => $lang_array['invalid']));				
			}else{
				setcookie("is_admin", "true", strtotime("+1 year"), path);
				$_SESSION['IsNew'] = true;
				header('location:index.php?page=front_page');
				exit;
			}
		}
		
	}else{
		$style->set_if("is_error", true);
		for($i=0;$i<count($error);$i++)$style->set_for("error", array('error' => $error[$i]));
	}
}

$style->load_file("login", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
//echo $style->return_clean_code();