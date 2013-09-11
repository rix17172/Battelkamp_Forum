<?php
if(!defined("in_forum"))exit;

if(!defined("table_prefix")){
	define("table_prefix","");//standert
}

define("setting",         table_prefix."setting");
define("geaust",          table_prefix."geaust");
define("user",            table_prefix."member");
define("grup_member",     table_prefix."grup_member");
define("katolori",        table_prefix."katolori");
define("katolori_access", table_prefix."katolori_access");
define("forum",           table_prefix."forum");
define("forum_access",    table_prefix."forum_access");
define("topic_title",     table_prefix."topic_title");
define("grup_name",       table_prefix."grup_name");
define("topic_message",   table_prefix."topic_message");
define("last_visist",     table_prefix."last_visist");
define("smylie",          table_prefix."smylie");
define("report_op",       table_prefix."report_op");
define("report",          table_prefix."report");
define("warn",            table_prefix."warn");
define("admin_access",    table_prefix."admin_access");
define("pm_title",        table_prefix."pm_title");
define("pm_message",      table_prefix."pm_message");

define("UserBlock", 4);
define("UserValid", 1);

define("FPDF_FONTPATH","include/font/");

define("Yes",1);
define("No",0);