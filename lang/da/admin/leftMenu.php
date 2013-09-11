<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		"Logout"         => "Logud admin",
		'MenuFront'      => 'Forside',
		'Welkommen'      => 'Velkommen %s',
		'MenuGenSetting' => 'Generale indstillinger',
		'MenuDBSize'     => 'Database størelse',
		'MenuSmyli'      => 'Opret smyli',
		'MenuUserList'   => 'Bruger list',
		'MenuGrupSek'    => 'Gruppe',
		'MenuGrupList'   => 'Gruppe liste',
		'MenuNewGrup'    => 'Opret gruppe',
		'MenuTilSett'    => 'Opret instillinger',
));