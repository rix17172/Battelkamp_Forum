<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title"                => "Grup overview of \"[S.GrupName]\"",
		'ChangeAdminData'      => "Change admin settings",
		'MaySeeAdmin'          => 'May see admin',
		'ChangeDataAdmin'      => 'change data',
		'GrupNoLongerSeeAdmin' => 'The group "[S.GrupName]" can now access the admin admintool',
		'ChangeDefData'        => 'Change basic group data',
		'ChangeGrupName'       => 'Change the group name',
		'ShowInTeamlist'       => 'View in team history',
		'SubmitChangeDefData'  => 'Change data',
		'NoNameValue'          => 'No group name is entered',
		'DataIsUpdatet'        => 'Data is now updated',
		'GrupNowSeeAdmin'      => 'The group can now access the admin',
		));