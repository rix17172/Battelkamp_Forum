<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'002'          => 'Login',
		'003'          => 'Create new account',
		'041'          => 'Logout',
		'edit_profile' => "edit profile",
		));