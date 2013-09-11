<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"           => "Group settings admin",
		"Access"          => "Admin access",
		"AccessFrontPage" => "Frontpage",
		"AccessForum"     => "Forum",
		"AccessMember"    => "User",
		"AccessAdmin"     => "Admin",
		"ChangeData"      => "Change rights",
		"DataIsChange"    => "Rights are now changed",
		"GrupName"        => "Group name",
		"Yes"             => "Yes",
		"No"              => "No",
		));