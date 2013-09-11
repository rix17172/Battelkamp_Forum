<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'edit_p_title'     => 'Ændre profil',
		'l_title'          => 'Ændre profil',
		'username'         => 'Brugernavn',
		'change_username'  => 'Ændre brugernavn',
		'no_p_username'    => 'Du skal udfylde brugernavn',
		'email'            => 'Email',
		'no_p_email'       => 'Du skal udfylde email',
		'okay'             => 'Dine data er nu opdateret. <br> husk at bruge de opdateret oplysninger når du næste gang logger på',
		'old_password'     => 'Din nuværende password',
		'new_password'     => 'Nyt password',
		'again_password'   => 'Pasword igen',
		'change_pass'      => 'Ændre password',
		'empty_o_password' => 'Du skal skrive den password du har nu!',
		'invalid_o_pass'   => 'Du har ikke udfyldt din nuværende password korrekt',
		'empty_n_password' => 'Du skal udfylde nyt password',
		'empty_a_password' => 'Du skal udfylde nyt password igen',
		'changeTime'       => 'Tids format',
		'timeFormat'       => 'Tids format',
		'phpTime'          => 'se hvordan',
		'submitTime'       => 'Ændre tids format',
		'noTime'           => 'Du skal udfylde time format!',
		));