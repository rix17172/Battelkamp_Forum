<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'forum_title'  => '[S.name] | Forum',
		'no_topic'     => 'There is no topic to display',
		'last_write'   => 'Last written by',
		'create_topic' => 'Create new topic'
		));