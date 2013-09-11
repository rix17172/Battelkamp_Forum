<?php
if(!defined("in_admin"))exit;

if(empty($lang) || !is_array($lang))$lang = array();


$lang = array_merge($lang,array(
		'title'                => "User data about \"[S.UserName]\"",
		'UserDataMiniTitle'    => "User data about \"[S.UserName]\"",
		'UserName'             => 'Username:',
		'email'                => 'Email:',
		'ChangeData'           => 'Change user data',
		'NoUserNameValue'      => 'You must enter a username',
		'NoEmailValue'         => 'You must write an email',
		'DataIsUpdatet'        => 'User data is updated. Remember to inform the user about changes',
		'UserNameIsTaken'      => 'The user name is taken',
		'password'             => 'Password:',
		'PasswordAgin'         => 'Password again:',
		'NoPasswordValue'      => 'You must fill in password',
		'NoPasswordAginValue'  => 'You must fill in your password again',
		'PasswordIsNotTheSame' => 'The two passwords are not the same',
		'DelUser'              => 'Delete User',
		'ChangeGrup'           => 'Change group',
		'GrupName'             => 'Group name:',
		'ChangeGrupNameNow'    => 'Change group now',
		'UserInNewGrupNow'     => 'The user is now in the new group',
		'UserIsNotActivatet'   => 'The user is not authorized',
		'ActivateUserNow'      => 'Enable user',
		'UserIsNowActivat'     => 'Users are now activated',
		));