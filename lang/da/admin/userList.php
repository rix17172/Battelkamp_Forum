<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'      => 'Bruger list',
		'userName'   => 'Brugernavn',
		'opretTime'  => 'Oprettet',
		'lastOnline' => 'Sidst online',
		'ip'         => 'IP',
));