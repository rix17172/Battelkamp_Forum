<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'045' => 'Online liste',
		'046' => 'Bruger',
		'047' => 'Gæster',
		));