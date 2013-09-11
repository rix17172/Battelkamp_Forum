<?php
if(!defined("in_install"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"        => "Opret config",
		"MenuTitle"    => "Opret config",
		"MySQLData"    => "Database indstillinger",
		"Host"         => "Server",
		"User"         => "Brugernavn",
		"Pass"         => "Password",
		"Data"         => "Database",
		"OtherSetting" => "Generalt",
		"DataPrefix"   => 'Tabel prefix',
		"SystemPath"   => "Forum mappe",
		"Submit"       => "Opret config",
		"NoHostPost"   => "Du skal udfylde host",
		"NoUserPost"   => "Du skal udfylde brugernavn",
		"NoPassPost"   => "Du skal udfylde password",
		"NoDataPost"   => "Du skal udfylde database navn",
		"GoNext"       => "Opret tabler",
		));