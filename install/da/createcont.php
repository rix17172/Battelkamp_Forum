<?php
if(!defined("in_install") || !is_object($this))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"               => "Indset data i database",
		"UserData"            => "Brugerdata",
		"UserName"            => "Brugernavn",
		"Password"            => "Password",
		"PasswordAgin"        => "Password igen",
		"OpretTable"          => "Indset data i tablerne",
		"Email"               => "email",
		"NoUsernamePost"      => "Du skal udfylde dit ønskede brugernavn",
		"NoEmailPost"         => "Du skal udfylde din email",
		"NoPasswordPost"      => "Du skal udfylde dit ønskede password",
		"NoPasswordAgainPost" => "Du skal udfylde dit ønskede password igen",
		"passwordNotSame"     => "De to password er ikke ens",
		"TableName"           => "Table navn",
		"IsOkay"              => "Gik det godt",
		"Yes"                 => "Ja",
		"No"                  => "Nej",
		"nextstep"            => "Neste",
		));