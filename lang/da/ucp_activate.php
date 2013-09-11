<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'InvalidKey'          => 'ERROR',
		'ErrorInvalidKey'     => 'Kunne ikke finde kontoen som skulle aktiveres. kontrolere linket.',
		'KontoIsActiv'        => 'Kontoen er allerede aktiveret.',
		'KontoIsAllradyActi'  => 'Kontoen er allerede aktiveret.',
		'KontoIsNowActiv'     => 'Kontoen er nu aktiveret',
		'OkayKontoIsNowActiv' => 'Kontoen er nu aktiveret. du kan nu logge ind',
		));