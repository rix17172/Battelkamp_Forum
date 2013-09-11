<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"          => "Change access to category",
		'ChangeKatTitle' => 'Change access to category',
		'ChangeAccess'   => 'Change',
		'DataIsUpdat'    => 'Access to this category is updated',
		));