<?php 
//Error for battelkamps forum.
//set error
ini_set('display_errors', 1);
$level = E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT;
error_reporting($level);


ini_set("log_errors", 1);
ini_set("error_log", first."log/error_log.txt");

function ErrorHandler($errno, $errstr, $errfile, $errline){
	SaveError($errstr,$errfile,$errline);
	exit("500 Internal Server Error");
}

function SaveError($message,$errfile,$errline){
	error_log("[".$errfile."]".$errline." ".$message,3,first."log/error_log.txt");
}

//set ErrorHandler to get error
set_error_handler("ErrorHandler");