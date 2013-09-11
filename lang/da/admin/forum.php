<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array(
		"title"       => "Forum og katolori admin",
		"numPost"     => "Antal post",
		"ChangeKat"   => "Ændre katolori",
		"ChangeForum" => "Ændre forum",
		'newKat'      => 'Opret ny katolori',
		'newForum'    => 'Opret ny forum',
);