<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'042' => 'Der er ingen forum eller katoloriere at vise',
		'043' => 'Sidst skrevet af',
		'044' => 'Antal post',
		));