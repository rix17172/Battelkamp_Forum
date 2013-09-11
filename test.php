<?php
define("in_forum",true);
define("first",null);
require_once 'include/class/modul.php';
require_once 'include/main.php';

$module = new Modul();

$module->SetModulDir("ucp");

$module->SetFileLibrie("login", "login.php");
$module->SetDefultPage("login.php");

$module->runModul("module");