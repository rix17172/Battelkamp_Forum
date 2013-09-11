<?php
class ucp_login_ok{
	
	function __construct(){
		global $style,$lang;
		$l = $lang->load_file(array(
			"ucp_login_ok.php",
		    'menu.php',
		    'pmmenu.php',
		    'head.php',	
		));
		$style->load_file("ucp_login_ok", "html");
		$style->load_lang($l);
		$style->set_for("css", array("name" => "ucp"));
		$style->set_if("login_ok", true);
		$style->convert_html();
		$style->eval_html();
	}
	
}