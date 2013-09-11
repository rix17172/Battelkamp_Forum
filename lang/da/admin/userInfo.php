<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'         => 'Bruger info om [S.username]',
		'userData'      => 'Brugerdata',
		'userName'      => 'Brugernavn',
		'email'         => 'Email',
		'changeData'    => 'Ændre brugerdata',
		'noUsername'    => 'Brugernavnet skal udfyldes!',
		'noEmail'       => 'Email skal udfyldes!',
		'usernameExist' => 'Brugernavnet er i brug',
		'changeOkay'    => 'Data er ændret',
		'changePass'    => 'Ændre password',
		'password'      => 'Password',
		'passwordAgian' => 'Password igen',
		'passChange'    => 'Ændre password',
		'noPassword'    => 'Password skal udfyldes!',
		'noPasswordA'   => 'Password igen skal udfyldes!',
		'passwordFail'  => 'De to password er ikke ens!',
		'PasswordOkay'  => 'Password er nu ændret',
		'changeGrup'    => 'Ændre grupe',
		'grupName'      => 'Gruppe navn',
		'grupChange'    => 'Ændre gruppe',
		'grupOkay'      => 'Brugen er sat i ny gruppe',
		'deleteUser'    => 'Slet bruger',
		'deleteDo'      => 'Hvordan slettes?',
		'deleteAll'     => 'Slet alt',
		'deleteNonAll'  => 'Slet alt unødvendig',
		'deleteNow'     => 'Slet nu',
		'userIsDeletet' => 'Brugeren er nu slettet',
));