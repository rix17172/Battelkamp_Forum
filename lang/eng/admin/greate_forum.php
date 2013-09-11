<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"            => "Create new forum",
		"GreateForumTitle" => "Create new forum",
		"ForumName"        => "Forum name:",
		"GreateLang"       => "Create forum",
		"NoForumValue"     => "You must enter a forum name"
		));