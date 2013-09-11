<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'title'             => 'Change forum',
		'ChangeForumTitle'  => 'Change forum "[S.ForumName]"',
		'ChangeForumName'   => 'Change forum name',
		'ChangeLocation'    => 'Change location',
		'ChangeData'        => 'Change data',
		'NoForumName'       => 'You must enter forum name',
		'NoForumLocation'   => 'You must enter the forum location',
		'DataUpdatet'       => 'Forum data is now updated',
		'ChangeForumAccess' => 'Change access to the forum',
		'DelForum'          => 'Delete Forum',
		));