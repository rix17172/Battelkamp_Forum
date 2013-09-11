<?php
if(!defined("in_install"))exit;

require_once 'InstallXml.php';

$xml = new InstallXml();
$xml->LoadXmlFile();