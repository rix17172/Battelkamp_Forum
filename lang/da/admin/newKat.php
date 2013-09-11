<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'     => 'Opret ny katolori',
		'katName'   => 'Katolori navn',
		'greateKat' => 'Opret',
));