<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'042' => 'There is no forum or katoloriere to show',
		'043' => 'Last written by',
		'044' => 'number of post',
		));