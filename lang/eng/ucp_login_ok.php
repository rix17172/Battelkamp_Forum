<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		 '038' => 'Login succeeded',
		 '039' => 'Login succeeded',
		 '040' => 'You are now logged on. You will be redirected in 5 sec.',
		));