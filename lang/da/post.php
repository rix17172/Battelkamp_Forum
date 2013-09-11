<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'051'               => 'Topic title',
		'052'               => 'Gæst navn',
		'053'               => 'Besked:',
		'054'               => 'Godkend',
		'055'               => 'Du skal skrive det navn du ville have skal stå ved topic',
		'056'               => 'Du skal skrive en title',
		'057'               => 'Du skal skrive en besked',
		'To'                => 'Til',
		'NoToInput'         => 'Du skal angive hvem skal modtage pm',
		'UserNameDontExist' => 'Brugernavnet eksistere ikke',
		));