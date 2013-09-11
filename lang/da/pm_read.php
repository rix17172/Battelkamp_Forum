<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'Title'      => '[S.PMTitle] &bull; Pm',
		'PMFrom'     => 'Fra',
		'DeletePM'   => 'Slet',
		'NoPmTopic'  => 'Kunne ikke finde nogle beskeder..',
		'AnswerBack' => 'Svar',
));