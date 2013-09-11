<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'InvalidKey'          => 'ERROR',
		'ErrorInvalidKey'     => 'Could not find your account to be activated. check up on the link.',
		'KontoIsActiv'        => 'The account is already activated.',
		'KontoIsAllradyActi'  => 'The account is already activated.',
		'KontoIsNowActiv'     => 'The account is now activated',
		'OkayKontoIsNowActiv' => 'The account is now activated. You can now login',
		));