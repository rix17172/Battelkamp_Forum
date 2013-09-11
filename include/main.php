<?php
if(!defined("in_forum"))exit;
session_start();

if(!defined("first")){
	define("first","");
}

date_default_timezone_set(@date_default_timezone_get());

require_once first.'include/error.php';

function IncludeExsternPage($url){
	require_once $url;
}

//make sure too brower dont save cache
	//no  cache headers 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if(is_dir(first."install/") && !file_exists(first."setting/config.php")){
	header("location:".first."install/index.php");
	exit;
}elseif(is_dir(first."install/")){
	echo "Remove install dir too open this forum";
	exit;
}

require_once first.'setting/config.php';
require_once first.'setting/prefix.php';
require_once first.'include/class/db.php';

$db = new Db();
$db->conect_db($db_info['host'], $db_info['user'], $db_info['pass'],$db_info['data']);
unset($db_info);

require_once first.'include/class/setting.php';
require_once first.'include/class/style.php';
require_once first.'include/class/user.php';
require_once first.'include/class/lang.php';
require_once first.'include/class/pm.php';
require_once first.'include/function/string.php';
require_once first.'include/class/head.php';

$setting = new Setting();
$setting->get_setting();

$style = new Style();

$user = new User();
$user->get_user_data();

$lang = new Lang();
$lang->get_lang_data();

$header = new Head();

if(!empty($_GET['change_lang']) && !empty($_GET['new_lang']))$lang->new_lang($_GET['new_lang']);

$style->set_if("is_admin", $user->data['is_admin']);
$style->set_if("is_user", $user->data['is_user']);
$style->set_if("is_geaust", $user->data['is_geaust']);

//PM
if($user->data['is_user']){
$style->set_if("IsPMUnread",$user->data['PmIsUnreadMessage']);
$style->set("NumUnreadPM",$user->data['PmUnreadCount']);
}

if(GET("logout") && $user->data['is_user'] && !defined("in_admin")){
	$user->logout();
}

if(defined("in_admin")){
	
	if(GET("AdminLogut")){
		DeleteCookie("is_admin");
		header("location:".first."index.php");
		exit;
	}
	
	require_once first.'include/class/admin.php';
	if(!may_visit_admin()){
		header("location:../index.php");
		exit;
	}
	
	$admin = new Admin();
	
}