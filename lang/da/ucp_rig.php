<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'Title' => 'Opretelse af ny bruger',
		'010' => 'Opret en ny bruger',
		'011' => 'Brugernavn',
		'012' => 'Email',
		'013' => 'Password',
		'014' => 'Password igen',
		'015' => 'Opret',
		'016' => 'Brugernavn skal udfyldes',
		'017' => 'Fejlbesked fra serveren',
		'018' => 'Email skal være udfyldt',
		'019' => 'Password skal være udfyldt',
		'020' => 'Du skal udfylde password igen',
		'021' => 'Du har ikke indtastet ens password',
		'022' => 'Brugernavnet er allerede i brug',
		'023' => 'Besked fra serveren',
		'024' => 'Din bruger er nu oprettet og du kan nu logge på',
		'025' => 'Besked fra serveren',
		'026' => 'Din bruger er nu oprettet. du kan føst logge ind når du har aktiveret din bruger. aktiverings nøglen får du med en mail.',
		'027' => 'Din bruger er nu oprettet. du kan føst logge ind når en ADMIN har godkendt din bruger.',
		'EmailIsTaken' => "Email er ibrug!",
		));