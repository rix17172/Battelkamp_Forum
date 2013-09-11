<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'     => 'Bruger forside',
		'countUser' => 'Antal bruger',
		'validUser' => 'Godkendte bruger',
		'noneUser'  => 'Ikke godkendte bruger',
		'countGrup' => 'Antal grupper',
));