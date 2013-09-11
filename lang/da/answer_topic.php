<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'059' => 'Svar tilbage på topic',
		'060' => 'Svar tilbage på en topic',
		'061' => 'Svar tilbage på: #topic_title#',
		));