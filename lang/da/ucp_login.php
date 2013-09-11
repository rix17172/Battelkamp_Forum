<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
         'Title'         => 'Login',
		 '029'           => 'Login',
		 '030'           => 'Brugernavn',
		 '031'           => 'Password',
		 '032'           => 'Husk mig',
		 '033'           => 'Login',
		 '034'           => 'Du skal udfyle brugernavn',
		 '035'           => 'Du skal udfyle password',
		 '036'           => 'Forkert brugernavn og/eller password',
		 '037'           => 'Din bruger er ikke godkendt endnu',
		 'FogotPassword' => "Har du glemt dit passowrd?",
		));