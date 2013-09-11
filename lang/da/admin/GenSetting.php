<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		"title"            => "Generale indstillinger",
		"Style"            => "Style:",
		'StyleType'        => 'Tiladte style typer (adskil med komma)',
		'StandLang'        => 'Standert sporg:',
		'Change'           => 'Ændre',
		'MissingStyle'     => 'Du skal udfylde style',
		'MissingStyleType' => 'Du skal udfylde style typer',
		'MissingStandLang' => 'Du skal udfylde standert lang',
		'SettingUpdatet'   => 'Indstillingerne er nu opdateret',
		'ipControl'        => 'Kontrol af ip',
		'ipTrue'           => 'Slået til',
		'ipFalse'          => 'Slået fra',
		'MissingIpControl' => 'Du skal angive om ip skal kontrolleres',
));