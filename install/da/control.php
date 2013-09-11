<?php
if(!defined("in_install"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'title'            => 'Kontrol af system',
		'ControlOffSystem' => 'Kontrol af systemet',
		'next'             => 'næste'
		));