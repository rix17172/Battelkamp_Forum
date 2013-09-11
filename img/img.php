<?php
define("in_forum",true);
if(empty($_GET['image_name']) || empty($_GET['h']) || empty($_GET['w']) || empty($_GET['sort'])){
	echo "lort";
	exit;
}



function get_map($sort){
	switch ($sort){
		case 1:
		return "smylie/";	
		break;
	}
}


// File and new size
$filename = get_map($_GET['sort']).$_GET['image_name'];



require_once '../include/class/file.php';
require_once '../include/class/style.php';

$style = new Style();
$StyleIni = $style->GetStyleSetting();

$img = new image($filename);
$img->ResizeImage($_GET['h'], $_GET['w']);
$img->ShowImage();
