<?php
if(!defined("in_install"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"VelkommenTitle" => "Velkommen til battelkamp install tool V.0.0.2",
		'AcceptBeting'   => 'Acceptere betingelserne',
		));