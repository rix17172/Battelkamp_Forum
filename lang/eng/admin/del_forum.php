<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"             => "Delete Forum",
		'DelForumMiniTitle' => 'Delete Forum "[S.ForumTitle]"',
		'MoveTopic'         => 'Move topic to another Forum',
		'DelAllInForum'     => 'Delete all',
		'DelForumNow'       => 'Delete Forum',
		));