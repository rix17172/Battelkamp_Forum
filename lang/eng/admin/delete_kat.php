<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();


$lang = array_merge($lang,array(
		'title'               => "Delete category",
		'DeleateKatMiniTitle' => 'Delete "[S.KatTitle]"',
		'MoveForums'          => 'Move forum:',
		'DeleateAll'          => 'Or delete everything:',
		'DelNow'              => 'Delete all',
		'NoMoveInput'         => 'You must choose where all forums are moving to!',
		));