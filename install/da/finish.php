<?php
if(!defined("in_install") || !is_object($this))exit;

if(empty($lang) || !is_array($lang))$lang = array();

$lang = array_merge($lang,array(
		"title" => "Færdig!",
		"desc" => "Du er nu færdig med at installere forumet.<br>
		Du skal nu slette eller omdobe mappen \"root/install\" og nyde forumet.<br>
		Vi håber at du bliver tilfreds med forumet<br>
		hilsen Battelkamp.dk",
		));