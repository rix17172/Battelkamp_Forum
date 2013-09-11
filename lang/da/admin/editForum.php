<?php
if(!defined("in_admin")){
	exit;
}

if(empty($lang) || !is_array($lang)){
	$lang = array();
}

$lang = array_merge($lang,array(
		'title'        => 'Ændre forum',
		'editName'     => 'Ændre navn',
		'forumName'    => 'Forum navn',
		'changeName'   => 'Ændre navn',
		'cNameOkay'    => 'Navnet er nu ændret',
		'changeAccess' => 'Ændre adgang',
		'grupName'     => 'Gruppe navn',
		'seeForum'     => 'Se forum',
		'seeTopic'     => 'Se topic',
		'newTopic'     => 'Opret topic',
		'ansTopic'     => 'Svar topic',
		'seeReport'    => 'Se report',
		'givWarn'      => 'Giv warn',
		'delReport'    => 'Slet report',
		'changeAccNow' => 'Ændre',
		'DeleteTitle'  => 'Slet forum',
		'deleteTo'     => 'Send indhold til:',
		'deleteAll'    => 'Slet alt',
		'deleteNow'    => 'Slet',    
		'moveForum'    => 'Flyt forum',
		'moveTo'       => 'Flyt til:',
		'moveNow'      => 'Flyt nu',
		'moveError'    => 'Du skal angive hvor du ville have forumet hen!',
		'catDontExist' => 'Katolori findes ikke',
		'isMove'       => 'Forumet er nu flyttet',
));

