<?php
session_start();
define("in_forum",true);
define("in_install",true);
define("first","../");

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

//if config.php is exsist is not install but update ;)
if(empty($_SESSION['in_install'])){
if(file_exists(first."setting/config.php")){
	header("location:update.php");
	exit;
}else{
	if(empty($_SESSION['in_install'])){
		$_SESSION['in_install'] = true;
	}
}
}

require_once 'InstallMain.php';

$xml->DoInstall();
exit;