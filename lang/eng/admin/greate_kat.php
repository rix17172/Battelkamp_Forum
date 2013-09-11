<?php
if(!defined('in_admin'))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'title'           => 'Create new category',
		'GreateKatTitlte' => 'Create new category',
		'KatName'         => 'Category Name:',
		'GreateKat'       => 'Create Category',
		'NoNameValue'     => 'You must fill in a name',
		));