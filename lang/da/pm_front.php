<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		"Title"   => "Privat besked forside",
		"From"    => "Fra",
		"PMTitle" => "Title",
		"Time"    => "Modtaget sidst",
		"NumPM"   => "Antal beskeder",
		"NewPM"   => "Opret ny PM",
));