<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'009' => 'Creating a new user',
		'010' => 'Create a new user',
		'011' => 'user name',
		'012' => 'Email',
		'013' => 'Password',
		'014' => 'password again',
		'015' => 'Create',
		'016' => 'Username must be completed',
		'017' => 'Error message from the server',
		'018' => 'E Mail must be filled',
		'019' => 'Password must be filled',
		'020' => 'You must fill in your password again',
		'021' => 'The two passwords do not match',
		'022' => 'The username is already in use',
		'023' => 'Message from server',
		'024' => 'Your user is now created and you can now log on',
		'025' => 'Message from server',
		'026' => 'Your user is now created. You can only log in once you have activated your account. activation key you get with an email.',
		'027' => 'Your user is now created. When an admin has approved the account, you can log in',
		));