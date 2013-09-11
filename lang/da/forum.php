<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'forum_title'  => '[S.name] | Forum',
		'no_topic'     => 'Der er ingen topic at vise',
		'last_write'   => 'Sidst skrevet af',
		'create_topic' => 'Opret ny topic'
		));