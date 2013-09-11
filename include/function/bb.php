<?php
if(!defined("in_forum"))exit;

function bb($tekst){
	global $db;
	$bb = array(
            "/\[b\](.*?)\[\/b\]/is" => "<strong>$1</strong>",
            "/\[u\](.*?)\[\/u\]/is" => "<u>$1</u>",
            "/\[url\=(.*?)\](.*?)\[\/url\]/is" => "<a href='$1'>$2</a>",
			"/\[i\](.*?)\[\/i\]/is" => "<i>\\1</i>",
			);
	
	$html = array(
			"&"  => "&amp;",
			"<"  => "&#60;",
			">"  => "&#62;",
			"\\" => "\\\\",
			);

	
	$tekst =  str_replace(array_keys($html), array_values($html), $tekst);	
	$tekst = preg_replace(array_keys($bb), array_values($bb), $tekst);

	$smaylie = array();
	$sql = $db->get_sql_query("SELECT * FROM ".smylie);
	while($row = $db->return_array($sql)){
		$tekst = str_replace($row['bb'], "<img src=\"img/img.php?image_name=".$row['url']."&amp;w=".$row['width']."&amp;h=".$row['heigh']."&amp;sort=1\" alt=\"".$row['name']."\">", $tekst);
	}
	
	return nl2br($tekst);
}