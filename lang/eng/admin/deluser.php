<?php
if(!defined("in_admin"))exit;


if(empty($lang) || !is_array($lang))$lang = array();


$lang = array_merge($lang,array(
		'title'            => 'Delete User "[S.UserName]"',
		'DelUserMiniTitle' => 'Delete User "[S.UserName]"',
		'DelAllData'       => 'Delete all data about user',
		'DellOnlyLittel'   => 'Only delete unnecessary things',
		'DellNow'          => 'Delete now',
		));