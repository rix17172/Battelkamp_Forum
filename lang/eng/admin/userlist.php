<?php
if(!defined("in_admin"))exit;


if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'title'             => 'User list',
		'UserListMiniTitle' => 'User list',
		'UserName'          => 'User name',
		'CountPost'         => 'Number of post',
		'OpretTime'         => 'Created in',
		'LastOnline'        => 'Last online',
		));