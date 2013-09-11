<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		'title'            => 'Forum',
		'mini_forum_title' => 'Forum',
		'change'           => 'Change',
		'go_to'            => 'Go to forum',
		'no_forum'         => 'There is nothing to show',
		'index'            => 'Index',
		'GreateKat'        => 'Create new category',
		'GreateForum'      => 'Create new Forum',
		'KatIsDel'         => 'The category is now deleted',
		));