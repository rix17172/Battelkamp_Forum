<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'        => 'Oprettelse indstillinger',
		'opretSetting' => 'Tilmeldnings indstillinger',
		'startGrup'    => 'Start gruppe:',
		'hashName'     => 'Password hash',
		'opretKontrol' => 'Oprettelse kontrol',
		'noKontrol'    => 'Ingen kontrol',
		'emailKontrol' => 'Send aktivirings mail',
		'adminKontrol' => 'Admin skal godkende ny bruger',
		'change'       => 'Ændre',
		'hashDenaid'   => 'Hash for kode ord blev ikke godkendt af serveren vælg venligst en anden',
		'Updatet'      => 'Indstillingerne er opdateret.',
));