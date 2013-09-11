<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'001'        => 'Forun index',
		'004'        => 'Login',
		'005'        => 'Statistics',
		'006'        => 'Number of users online:',
		'007'        => 'Number of guests online:',
		'008'        => 'The record for most online user is:',
		'075'        => 'There are [S.count_report] reporter waiting to be processed',
		'admin_tool' => 'Control Panel',
		));