<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'002'          => 'Login',
		'003'          => 'Opret ny konto',
		'041'          => 'Logud',
		'edit_profile' => "Redigere profil",
		));