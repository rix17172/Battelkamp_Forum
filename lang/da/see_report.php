<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'076'   => "Behandle reporter",
		'078'   => "Gå til besked",
		'079'   => "Giv et warn",
		'080'   => "Slet denne report",
		'Topic' => 'Topic:',
		'Reson' => 'Grund:',
		));