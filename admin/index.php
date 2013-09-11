<?php
define("in_forum",true);
define("in_admin", true);
define("first","../");
require_once '../include/main.php';

if(empty($_GET['page'])){
	header("location:../index.php");
	exit;
}

$FileAccess = substr(sprintf('%o', fileperms('../setting/config.php')), -4);

if($FileAccess < 0640){
    echo "Config in \"setting/config.php\" have a cmod on ".$FileAccess.".<br>
For aktivate admin use at last 640.";
	exit;
}

//vi undersøger om vi må være her.
if(!$admin->may_see_this_tool()){
	//ikke lige denne. nu ser vi om vi må være et andet sted.....(mest nyttig når man lige er logget ind og man ikke har adgang til forsiden),
	$go_too = $admin->get_first_tool_i_may_see();
	if($go_too == null) $go_too = "../index.php";
	else $go_too = "?page=".$go_too;
	
	header("location:".$go_too);
	exit;
}

$sql_array = array(
		"SELECT `a_id` FROM `".admin_access."` WHERE `g_id`=",
		"?".$user->gruppe_id,
);

$sql = $db->get_sql_query($db->clean_sql($sql_array));
while($row = $db->return_array($sql)){
	switch ($row['a_id']){
		case $admin_access['front_page']:
			$style->set_if("mySeeFront", true);
		break;
		case $admin_access['forum']:
			$style->set_if('mySeeForum', true);
		break;
		case $admin_access['user']:
			$style->set_if("mySeeUser", true);
		break;
		case $admin_access['admin']:
			$style->set_if("mySeeAdmin", true);
		break;
	}
}

require_once first.'include/class/modul.php';

$module = new Modul();
$module->SetGet("page");
$module->SetPage("front_page", "front.php");
$module->SetPage("forum", "forum.php");
$module->SetPage("user", "userMod.php");
$module->SetPage("admin", "adminMod.php");

$module->RunPage();