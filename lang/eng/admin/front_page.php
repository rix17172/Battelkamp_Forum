<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'title'                    => 'Admin front',
		'statestik'                => 'Statistics',
		'count_user'               => 'Number of registered user:',
		'count_geaust'             => 'Number of guests:',
		'count_forum'              => 'Number of forum (s):',
		'count_kat'                => 'number of categories',
		'options'                  => 'Options',
		'deleate_unnecessary'      => 'Delete all unnecessary.',
		'perform_action'           => 'Perform action',
		'deleate_unnecessary_okay' => 'Unnecessary things are now deleted',
		'updats_check'             => 'Update control',
		'is_updatering'            => 'Are there updates',
		'update_yes'               => 'There are updates. Please update',
		'update_no'                => 'There are no updates',
		'update_error'             => 'There has been an error. please go to our forum to find fault',
		));