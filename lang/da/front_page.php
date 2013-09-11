<?php
if(!defined('in_forum'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'001'        => 'Forun index',
		'004'        => 'Login',
		'005'        => 'Statistikker',
		'006'        => 'Antal bruger online:',
		'007'        => 'Antal gæster online:',
		'008'        => 'Rekoden for flest online bruger er:',
		'075'        => 'Der er [S.count_report] reporter som venter på at blive behandlet',
		'admin_tool' => 'Kontrol panel',
		));