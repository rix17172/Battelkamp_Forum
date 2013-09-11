<?php
if(!defined("in_install") || !is_object($this))exit;

if(file_exists("da/betingelser.txt"))$this->style->set("betingelser",file_get_contents("da/betingelser.txt"));
else{
	echo "betingelser mangler";
	exit;
}