<?php
if(!defined("in_forum"))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang, array(
		'edit_p_title'     => 'Change profile',
		'l_title'          => 'Change profile',
		'username'         => 'User name',
		'change_username'  => 'Change username',
		'no_p_username'    => 'You must fill in username',
		'email'            => 'Email',
		'no_p_email'       => 'You must fill out the e mail',
		'okay'             => 'Your data is now updated. <br> remember to use the updated information the next time you log on',
		'old_password'     => 'Your current password',
		'new_password'     => 'New password',
		'again_password'   => 'Password again',
		'change_pass'      => 'Change Password',
		'empty_o_password' => 'You must enter the password you have now!',
		'invalid_o_pass'   => 'You have not completed your current password correctly',
		'empty_n_password' => 'You must fill new password',
		'empty_a_password' => 'You must complete the new password again',
		));