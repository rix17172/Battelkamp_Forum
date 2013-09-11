<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'076' => "Processing reporter",
		'078' => "Go to message",
		'079' => "Give a warn",
		'080' => "Delete this report",
		));