<?php
if(!defined('in_admin'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'title'       => "Logind",
		'warning'     => "For at få access til admin tool skal du skrive dit brugernavn og password!",
		'username'    => "Brugernavn",
		'password'    => "Password",
		'login'       => "Login",
		'no_username' => "Du skal udfylde dit brugernavn",
		'no_password' => "Du skal udfylde dit password",
		'invalid'     => "Du har indtastet forkert brugernavn og/eller password",
		));