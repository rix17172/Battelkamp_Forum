<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'GoToPM'          => "PM",
		'PMUnreadMessage' => "Ulæste besked ([S.NumUnreadPM])"
		));