<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
         '028' => 'Login',
		 '029' => 'Login',
		 '030' => 'User name',
		 '031' => 'Password',
		 '032' => 'Remember me',
		 '033' => 'Login',
		 '034' => 'You must fill in username',
		 '035' => 'You must fill in password',
		 '036' => 'Wrong username and / or password',
		 '037' => 'User is not approved yet',
		));