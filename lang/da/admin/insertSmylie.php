<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'         => 'Smylie',
		'file'          => 'Fil',
		'tag'           => 'Tag',
		'submitSmylie'  => 'Upload smylie',
		'notImg'        => 'Uploadning fejlede: Filen er ikke godkendt som billed!',
		'uploadFail'    => 'Kunne ikke flytte filen til systemet!',
		'name'          => 'Navn',
		'UploadSuccess' => 'Smylie er nu oprettet i systemet',
		'smylieList'    => 'Smylie list',
		'smylie'        => 'Smylie',
		'handling'      => 'Handling',
		'deleteSmylie'  => 'Slet',
));