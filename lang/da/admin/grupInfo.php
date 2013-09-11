<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}


$lang = array_merge($lang,array(
		'title'        => 'Gruppe info om \'[S.name]\'',
		'grupData'     => 'Ændre grup data',
		'grupName'     => 'Gruppe navn',
		'showAdmin'    => 'Vis i admin list',
		'noName'       => 'Du skal udfylde navn!',
		'changeData'   => 'Ændre data',
		'dataIsChange' => 'Data er ændret',
		'adminGrup'    => 'Admin indstillinger',
		'doAdmin'      => 'Gøre til admin',
		'adminChange'  => 'Ændre',
		'nowAdmin'     => 'Rettighederne er nu ændret',
		'deleteGrup'   => 'Slet gruppe',
		'deleteNow'    => 'Slet nu',
		'grupIsStand'  => 'Gruppen er en standart gruppe og kan derfor ikke slettes!',
));