<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"         => "Create new group",
		'OpretNewGrup'  => 'Create new group',
		'GrupName'      => 'Group Name',
		'CreateGrupNow' => 'Create groups now',
		));