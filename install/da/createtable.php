<?php
if(!defined("in_install") || !is_object($this))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title" => "Opret database tabler",
		"TableName" => "Table navn",
		"IsOpretCorext" => "Blev oprettet korrekt?",
		"Yes" => "Ja",
		"No"  => "Nej",
		"GoNext" => "Gå vidre",
		));