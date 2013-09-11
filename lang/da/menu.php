<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'064' => 'Forside',
		'065' => 'Bruger oversigt',
		'066' => 'Team oversigt',
		));