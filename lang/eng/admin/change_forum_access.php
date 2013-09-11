<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"                      => "Change forum access",
		'ForumChangeAccessMiniTitle' => "Change forum access",
		'AccessSeeForum'             => "Must see forum",
		'AccessSeTopic'              => "Must see topic",
		'AccessNewTopic'             => "Do create topic",
		'AccessAnswerTopic'          => "Do correspond topic",
		'AccessLookReport'           => 'Must see report',
		'AccessGiveWarn'             => 'Give Warn',
		'AccessDeleateReport'        => 'Delete reporter',
		'GrupName'                   => 'Group Name',
		'ChangeAccess'               => 'Change access',
		'DataIsUpdat'                => 'The data is now updated',
		));