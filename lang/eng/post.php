<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'051'               => 'Topic title',
		'052'               => 'Guest name',
		'053'               => 'Message:',
		'054'               => 'Approve',
		'055'               => 'You must enter the name you would have to stand by topic',
		'056'               => 'You must enter a title',
		'057'               => 'You must enter a message',
		'To'                => 'To',
		'NoToInput'         => 'You must specify who should receive pm',
		'UserNameDontExist' => 'The username does not exist',
		));