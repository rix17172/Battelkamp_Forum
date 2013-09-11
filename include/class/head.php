<?php
if(!defined("in_forum"))exit;


class Head{
	function PageNotFound($FastCGI = false){
		if(!$FastCGI){
			header("HTTP/1.0 404 Not Found");
		}else{
			header("Status: 404 Not Found");
		}
	}
}