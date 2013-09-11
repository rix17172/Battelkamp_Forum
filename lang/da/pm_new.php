<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'BoksTitle'  => "Opret ny PM",
		'NewPMtitle' => 'Opret ny PM',
		'PmTo'       => 'Til:'
));