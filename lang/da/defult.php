<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

//denne er lidt anderledes end alle andre!
$lang = array_merge($lang, array(
		'Geaust' => 'Gæst',
		));