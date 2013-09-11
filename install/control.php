<?php
if(!defined("in_install") || !is_object($this))exit;

$mysql = function_exists("mysql_connect");
$this->style->set_if("IsMySQL",$mysql);

$img = array(
		"imagesx",
		"imagesy",
		"imagejpeg",
		"imagegif",
		"imagepng",
		"imagecreatetruecolor",
		"imagecopyresampled",
		"imagedestroy",
		"imagecopy",
		"imagecolorallocate",
		"imagedestroy",
		"imagesetpixel",
		);

$all = true;

foreach ($img as $image){
	if(!function_exists($image)){
		$all = false;
		break;
	}
		
}

$this->style->set_if("Image",$all);
$data = (function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get")) ? true : false;
$this->style->set_if("Data",$data);

if($mysql && $all && $data)$this->style->set_if("AllOkay",true);