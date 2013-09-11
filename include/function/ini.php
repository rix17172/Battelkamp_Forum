<?php
if(!defined("in_forum"))exit;

function ReadIni($place,$process_sections = false){
	if(!file_exists($place)){
		exit;
	}
	return parse_ini_file($place,$process_sections);
}