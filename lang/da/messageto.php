<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"    => "PM &bull; [S.MessageTitle]",
		"From"     => "Fra",
		"AnswerPm" => "Svar tilbage",
		));