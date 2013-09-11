<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		 'Title' => 'Login lykkes',
		 '039' => 'Login lykkes',
		 '040' => 'Du er nu logget ind. du ville blive sendt vidre om 5 sek.',
		));