<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"             => 'Generale board settings',
		'options'           => 'General settings',
		'stand_style'       => 'Standard style',
		'allow_style_end'   => 'Tiladte style types (separated by commas)',
		'stand_lang'        => 'Standard Language',
		'change_setting'    => 'Updating settings',
		'stand_style_empty' => 'You must select a default style!',
		'style_end_empty'   => 'You must complete the allowed file types',
		'lang_empty'        => 'You must select the default language',
		'setting_updatet'   => 'The settings are updated',
		));