<?php
if(!defined("in_forum"))exit;

function POST($post){
	if(empty($_POST[$post]) || !trim($_POST[$post])){
		return false;
	}
	
	return $_POST[$post];
}

function GET($get){
	if(empty($_GET[$get]) || !trim($_GET[$get])){
		return false;
	}
	
	return $_GET[$get];
}

function Cookie($cookie){
	if(empty($_COOKIE[$cookie]) || !trim($_COOKIE[$cookie])){
		return false;
	}
	
	return $_COOKIE[$cookie];
}

function DeleteCookie($cookieName){
	if(!Cookie($cookieName)){
		return;
	}
	
	setcookie($cookieName,"",time() - 10000,path);
}

function SetNewCookie($name,$value){
	setcookie($name, $value, strtotime("+1 year"), path);
}

function ReturnArrayNonInt($array){
	if(!is_array($array)){
	    return array();
	}
	
    $return = array();
    foreach ($array as $keys => $value){
    	if(!is_numeric($keys)){
    		$return[$keys] = $value;
    	}
    }
    
    return $return;
}

function showArray($array,$exit = true){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
	
	if($exit){
		exit;
	}
}