<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"            => "User Creation Settings",
		'SettingTittle'    => 'Creating settings',
		'StartGrup'        => 'Start Group',
		'UseHash'          => 'Whose hash is to be used',
		'OpretControl'     => 'Creating control',
		'NoControl'        => 'No control',
		'EmailControl'     => 'Send activation e mail',
		'AdminCOntrol'     => 'Let Admin activate the account',
		'ChangeData'       => 'Change settings',
		'SettingIsUpdatet' => 'The settings are now updated',
		));