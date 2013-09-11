<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		"title"        => 'Ændre katolori',
		'KatName'      => 'Navn:',
		'ChangeKN'     => 'Ændre',
		'KatIsU'       => 'Navnet er nu opdateret',
		'NameEmpty'    => 'Navnet er tom!',
		'ChangeAcc'    => 'Ændre rettigheder',
		'changeAcc'    => 'Ændre rettigheder',
		'grupName'     => 'Gruppe navn',
		'maySee'       => 'Må se',
		'ChanegAOkay'  => 'Ændring af adgang er fuldført',
		'deleteAll'    => 'Slet alt',
		'deleteKat'    => 'Slet katolori',
		'deleteTo'     => 'Slet til',
		'deleteKatSub' => 'Slet',
));