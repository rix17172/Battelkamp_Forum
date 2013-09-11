<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'AnswerPmTitleBoks' => 'Svar tilbage på PM',
		'AnswerPmTitle'     => 'Svar tilbage på PM',
));