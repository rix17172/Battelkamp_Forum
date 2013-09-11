<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"           => "Change category",
		'ChangeKatTittle' => 'Change "[S.KatName]"',
		'ChangeKatName'   => 'Change category name',
		'ChangeKatData'   => 'Change category data',
		'NoKatName'       => 'You must fill out the category name!',
		'KatNameChanged'  => 'Category name has been changed',
		'ChangeAccessKat' => 'Change the access category',
		'KatChangeAcLink' => 'Change the access category',
		'DeleateKat'      => 'Delete category',
		));