<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'      => 'Ændre rettigheder for "[S.name]"',
		'rights'     => 'Rettigheder for [S.name]',
		'frontPage'  => 'Forside',
		'forumPage'  => 'Forum',
		'userPage'   => 'Bruger',
		'adminPage'  => 'Admin',
		'right'      => 'Rettigheder',
		'mySee'      => 'Må se',
		'myNotSee'   => 'Må ikke se',
		'change'     => 'Ændre',
		'changeOkay' => 'Ændringer er nu gemt',
));