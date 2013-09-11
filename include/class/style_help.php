<?php
if(!defined('in_forum'))exit;

class Style_help{
	private $use = array();
	
	public function _Use($full,$sec,$html){
		if(!empty($this->use[$sec]))return "Use \"".$sec."\" is alredy in use";
		$this->use[$sec] = true;
		return str_replace($full, "", $html);
	}
	
	public function _If($full,$sec,$html,$if,$if_type,$set){
		if(empty($this->use['Defult']))return str_replace($full, "Defult is not in use", $html);
		$if_builder = null;
		$reg = explode(" ", $sec);
		for($i=0;$i<count($reg);$i++){
			switch ($reg[$i]){
				case "&&":
				case "AND":
					$if_builder .= " &&";
				break;
				case "||":
				case "OR":
					$if_builder .= " ||";
				break;
				case "LIKE":
				case "==":
				     $if_builder .= " ==";	
				break;
				case "DONT":
				case "!=":
				case "!":
					$if_builder .= " !=";
				break;
				case "<":
				    $if_builder .= " <";	
				break;
				case ">":
				    $if_builder .= " >";	
				break;
				default:
				    if(preg_match("/!(.+)/", $reg[$i], $regs)){
				    	if(empty($if[$regs[1]]))$if_builder .= " true";
				    	else $if_builder .= " false";
				    }elseif(is_numeric($reg[$i])){
				    	$if_builder .= " ".$reg[$i];
				    }elseif(preg_match("/'(.*?)'/", $reg[$i]) || preg_match('/"(.*?)"/', $reg[$i])){
				    	$if_builder .= " ".$reg[$i];
				    }elseif(stripos($reg[$i],'.')){
				    	$explorde = explode('.', $reg[$i]);
				    	if($explorde[1] == $explorde[0]."_num")$if_builder .= " \$".$explorde[0]."_num";
				    	else $if_builder .= " \$".$explorde[0]."_array[\$".$explorde[0]."_i]['".$explorde[1]."']";
				    }elseif(preg_match("/Get\[(.*?)\]/", $reg[$i], $regs)){ 
				    	if(empty($_GET[$regs[1]]))$if_builder .= " false";
				    	else $if_builder .= " '".$_GET[$regs[1]]."'";
				    }elseif(preg_match("/Set\[(.*?)\]/", $reg[$i], $regs)){
				    	if(empty($set[$regs[1]]))$if_builder .= " false";
				    	else $if_builder .= " '".$set[$regs[1]]."'";
				    }else{
				    	if(empty($if[$reg[$i]]))$if_builder .= " false";
				    	else{
				    		if(is_numeric($if[$reg[$i]]))$if_builder .= " ".$if[$reg[$i]];
				    		else $if_builder .= " '".$if[$reg[$i]]."'";
				    	}
				    }	
				break;
			}
		}
		
		$if_builder = preg_replace("/(false == false)/", "false", $if_builder);
		
		$html = str_replace("<!--Else-->", "<?php }else{ ?>", $html);
		$html = str_replace("<!--End_if-->", "<?php } ?>", $html);
		
		return str_replace($full,"<?php ".($if_type == "Elseif" ? '}else' : null)."if(".$if_builder."){ ?>",$html);
	}
	
	public function _For($full,$sec,$html,$for){
		//vi har to tegn vi ikke ville have i vores system så vi ændre det :D
		$not_allow = array(
				"'" => "\'",
				);
		if(preg_match("/^!.*?/", $sec,$reg)){
			
		}else{
			$first = "\$".$sec."_num = -1;\n\$".$sec."_array = array(";
            if(!empty($for[$sec])){
			foreach ($for[$sec] as $firstt => $second){
				$first .= "array(";
				foreach ($second as $tag => $value){
					$first .= "'".str_replace(array_keys($not_allow), array_values($not_allow), $tag)."' => '".str_replace(array_keys($not_allow), array_values($not_allow), $value)."',";
				}
				$first .= "),";
			}
            }
			$first .= ");";
			
			$html = preg_replace("<!--".$sec."\.(.*?)-->", "?php echo \$".$sec."_array[\$".$sec."_i]['\\1']; ?", $html);
			$html = str_replace("<!--End_for.".$sec."-->", "<?php } ?>", $html);
			$html = str_replace("<!--".$sec.".".$sec."_num-->", "<?php echo \$".$sec."_num ?>", $html);
			$html = str_replace($full, "<?php \n ".$first." \n for(\$".$sec."_i=0;\$".$sec."_i<count(\$".$sec."_array);\$".$sec."_i++){  \n \$".$sec."_num++; \n  ?>", $html);
		}
		return $html;
	}
	
	public function _Lang($full,$sec,$html,$lang,$set){
		if(empty($this->use['Defult']))return str_replace($full, "Defult is not in use", $html);
		elseif(empty($lang[$sec]))return "Lang dosent have ".$sec;
		else{
			$langg = $lang[$sec];
			$pregs = preg_match_all("/\[S\.(.*?)\]/", $lang[$sec], $regs);
			for($i=0;$i<$pregs;$i++){
             if(!empty($set[$regs[1][$i]])) $langg = str_replace($regs[0][$i], $set[$regs[1][$i]], $langg);
             else $langg = str_replace($regs[0][$i], "NO set value for ".$regs[1][$i], $langg);			
			}
			return str_replace($full, $langg, $html);
		}
	}
	
	public function _Set($full,$sec,$html,$set){
		if(empty($set[$sec]))return str_replace($full, '0', $html);
		else{
			$array = array(
					"\'" => "'",
					'\"' => '"',
					);
			$pregs = preg_match_all("/\[S\.(.*?)\]/", $set[$sec],$regs);
			$SetSet = $set[$sec];
			for($i=0;$i<$pregs;$i++){
				if(empty($set[$regs[1][$i]]))$SetSet = "No Set value for \"".$regs[1][$i]."\"";
				else $SetSet = str_replace($regs[0][$i], $set[$regs[1][$i]], $SetSet);
			}
			$tekst = str_replace(array_keys($array), array_values($array), $SetSet);
			return str_replace($full, $tekst, $html);
		}
	}
	
	public function _Include($full,$sec,$html,$if,$for,$set,$lang){
		if(empty($this->use['Defult']))return str_replace($full, "Defult not in use", $html);
		$style = new Style();
		$expolder = explode(',', $sec);
		$style->load_file($expolder[0], $expolder[1]);
		$style->set_all($if, $for, $set, $lang);
		$style->convert_html();
		return str_replace($full,$style->return_html(),$html);
	}
	
	public function _Get($full,$sec,$html){
		if(empty($this->use['Defult']))return str_replace($full, "Defult not in use", $html);
		elseif(stripos($sec,",")){
			$explorde = explode(",", $sec);
			switch ($explorde[1]){
				case "urlencode":
				return str_replace($full, urlencode($_GET[$explorde[0]]), $html);	
				break;
			}
		}
		elseif(empty($_GET[$sec]))return str_replace($full, "No value ".$sec, $html);
		else return str_replace($full, $_GET[$sec], $html);
	}
	
	public function _Post($full,$sec,$html){
		if(empty($this->use['Defult']))return str_replace($full,"Defult not in use", $html);
		
		return str_replace($full, (!POST($sec) ? null : POST($sec)), $html);
	}
	
	public function _IncludeLang($full,$sec,$html){
		global $lang;
		return array(
				str_replace($full, "", $html),
				$lang->load_file(array($sec)),
				);
	}
	
}