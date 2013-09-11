<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'081' => "Giv en warn",
		'082' => "Brud:",
		'083' => "Giv et warn",
		'084' => "Du skal skrive en begrundelse for at du ville give en warn",
		'085' => "Brugeren har nu fået et warn",
		));