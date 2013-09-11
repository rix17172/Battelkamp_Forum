<?php
if(!defined("in_install") && !is_object($this))exit;

require_once 'da/makeconfig.php';

$SystemPasth = $_SERVER["PHP_SELF"];
$SystemPasth = str_replace("install/index.php", "", $SystemPasth);

$this->style->set("SystemPath",$SystemPasth);



if(!empty($_POST['MakeConfig'])){
	$error = array();
	if(empty($_POST['host']))$error[] = $LangArray['NoHostPost'];
	if(empty($_POST['user']))$error[] = $LangArray['NoUserPost'];
	if(empty($_POST['pass']))$error[] = $LangArray['NoPassPost'];
	if(empty($_POST['data']))$error[] = $LangArray['NoDataPost'];
	
	if(empty($error)){
		$ConfigTemplate = "<?php
if(!defined('in_forum'))exit;

\$db_info = array(
		'host' => '".$_POST['host']."',
		'user' => '".$_POST['user']."',
		'pass' => '".$_POST['pass']."',
		'data' => '".$_POST['data']."',
		);

//define(\"show_error\",true);
define(\"table_prefix\",\"".(empty($_POST['prefix']) ? false : $_POST['prefix'])."\");

define(\"path\",\"".(empty($_POST['path']) ? "/" : $_POST['path'])."\");";
		
		$fil = fopen("../setting/config.php", "w");
		fwrite($fil, $ConfigTemplate);
		fclose($fil);
		
		$this->style->set_if("IsConfigCreatet",true);
		
	}else{
		$this->style->set_if("IsError",true);
		for($i=0;$i<count($error);$i++)$this->style->set_for("error",array(
				"error" => $error[$i],
				));
	}
}