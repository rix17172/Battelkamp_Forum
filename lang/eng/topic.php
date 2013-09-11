<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'047' => '[S.name] | Topic',
		'048' => 'Post',
		'058' => 'Reply',
		'062' => 'Change',
		'069' => 'Reporter topic',
		'086' => 'Warn',
		));