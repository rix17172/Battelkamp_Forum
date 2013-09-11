<?php
if(!defined('in_admin'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'title'       => "Login",
		'warning'     => "To get access to the admin tool, type your username and password!",
		'username'    => "Username",
		'password'    => "Password",
		'login'       => "Login",
		'no_username' => "You must fill in your username",
		'no_password' => "You must fill in your password",
		'invalid'     => "You have entered wrong username and / or password",
		));