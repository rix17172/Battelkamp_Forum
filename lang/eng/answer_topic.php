<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'059' => 'Response back to topic',
		'060' => 'Reply back on topic',
		'061' => 'Response back to: #topic_title#',
		));