<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'047'       => '[S.name] | Topic',
		'048'       => 'Post',
		'058'       => 'Svar',
		'062'       => 'Ændre',
		'069'       => 'Reportere indlægget',
		'086'       => 'Warn',
		'ShowInPDF' => 'Vis topic i en pdf fil',
		));