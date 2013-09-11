<?php
if(!defined("in_admin") || !defined("in_forum")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		"FrontPageMenu" => "Forside",
		"ForumMenu"     => "Forum",
		"UserMenu"      => "Bruger",
		"AdminMenu"     => "Admin",
));