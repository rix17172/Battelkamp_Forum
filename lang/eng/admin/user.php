<?php
if(!defined("in_admin"))exit;


if(empty($lang) || !is_array($lang))$lang = array();


$lang = array_merge($lang,array(
		"title"             => "User index",
		'InfoTitle'         => 'Info',
		'CountUser'         => 'Number of user',
		'CountGrup'         => 'number of groups',
		'CountAllowUser'    => 'Number of approved uses',
		'CountNotAllowUser' => 'Number of unapproved uses'
		));