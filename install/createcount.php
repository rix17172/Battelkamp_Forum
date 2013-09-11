<?php
if(!defined("in_install") || !is_object($this))exit;


if(!empty($_POST['post'])){
	$error = array();
	
	if(empty($_POST['username']))$error[] = $LangArray["NoUsernamePost"];
	if(empty($_POST['email']))$error[] = $LangArray["NoEmailPost"];
	if(empty($_POST['password']))$error[] = $LangArray["NoPasswordPost"];
	elseif(empty($_POST['passwordagin']))$error[] = $LangArray["NoPasswordAgainPost"];
	elseif($_POST['password'] != $_POST['passwordagin'])$error[] = $LangArray['passwordNotSame'];
	
	if(empty($error)){
		
		require_once '../include/class/user.php';
		$user = new User();
		
		$time = time();
		$password = $user->hash_password($_POST['password'], $time,"sha1");
		

		
		$SqlArray = array(
				"INSERT INTO `".$_POST['prefix']."admin_access` (`id`, `g_id`, `a_id`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5);",
				
				"INSERT INTO `".$_POST['prefix']."forum` (`id`, `name`, `place`, `post_num`, `last_write`, `last_topic`, `last_is_title`, `last_id`) VALUES
(1, 'Din føste forum', 1, 1, 'rix', 'Dit føste indlæg', 1, 1);",
				
				"INSERT INTO `".$_POST['prefix']."forum_access` (`id`, `f_id`, `g_id`, `a_id`) VALUES
(1, 1, 2, 1),
(2, 1, 2, 2),
(3, 1, 2, 3),
(4, 1, 2, 4),
(5, 1, 2, 5),
(6, 1, 2, 6),
(7, 1, 2, 7);",
				
				"INSERT INTO `".$_POST['prefix']."grup_member` (`id`, `u_id`, `g_id`) VALUES
(1, 1, 2);",
				
				"INSERT INTO `".$_POST['prefix']."grup_name` (`id`, `name`, `show_team`) VALUES
(1, 'Bruger', 0),
(2, 'Admin', 1);",
				
				"INSERT INTO `".$_POST['prefix']."katolori` (`id`, `name`, `place`) VALUES
(1, 'Din føste katolori', 0);",
				
				"INSERT INTO `".$_POST['prefix']."katolori_access` (`id`, `k_id`, `g_id`) VALUES
(1, 1, 2);",
				
				"INSERT INTO `".$_POST['prefix']."member` (`id`, `username`, `password`, `email`, `opret_time`, `last_online`, `status`, `page_title`, `url`, `post`, `ActivieringKey`,`ip`,`TimeFormat`) VALUES
(1, '".$_POST['username']."', '".$password."', '".$_POST['email']."', ".$time.", ".$time.", 1, 'Install', '#', 0, NULL,'0','i:H d-m-Y')",
				
				"INSERT INTO `".$_POST['prefix']."report_op` (`id`, `options`) VALUES
(1, 'Brud imod landets lov'),
(2, 'Brud imod forumes regler');",
				
				"INSERT INTO `".$_POST['prefix']."setting` (`id`, `tag`, `value`) VALUES
(1, 'STAND_STYLE', 'nuke'),
(2, 'ALLOW_FILE_TYPE', 'html,xml,js,css'),
(3, 'GEAUST_ID', '1'),
(4, 'STAND_LANG', 'da'),
(5, 'RECOD_ONLINE_USER', '0'),
(6, 'RIG_VALIATE', '1'),
(7, 'PASS_HASH', 'sha1'),
(8, 'STAND_GRUP', '1'),
(9, 'VERISION', 'V.0.0.2T1'),
(10, 'Control_Ip', '1'),
(11, 'StandTimeFormat', 'i:H d-m-Y'),
(12, 'AllowPDFShowTopic','0');",
				
				"INSERT INTO `".$_POST['prefix']."topic_title` (`id`, `f_id`, `is_user`, `user_id`, `user_name`, `title`, `message`, `post_time`, `last_post_time`, `last_write_is_user`, `last_write_username`) VALUES
(1, 1, 0, 1, 'rix', 'Dit føste indlæg', 'Tak fordi du valgte at bruge [b]battelkamp.dk[/b] forum!.\r\n\r\nDette er en simpel visning af hvordan et indlæg ville se ud. \r\nHar du spørgsmål kan du til en hver tid stille det på vores [url=http://battelkamp.dk]forum[/url]\r\n\r\nHåber du du får en god oplevelse med vores forum.\r\nHilsen Battelkamp.dk', 1357675969, 1357675969, 1, 'rix');"
				);
		
		mysql_connect($_POST['host'],$_POST['user'],$_POST['pass']) OR die(mysql_error());
		mysql_select_db($_POST['data']) OR die(mysql_error());

		for($i=0;$i<count($SqlArray);$i++){
			preg_match("/INSERT INTO \`(.*?)\`/", $SqlArray[$i],$regs);
			$tablename = str_replace($_POST['prefix'], "", $regs[1]);
			
			$this->style->set_if("IsInsert",true);
			
			if(@mysql_query($SqlArray[$i])){
				$this->style->set_for("sql",array("tablename" => $tablename,"IsOkay" => true));
			}else{
				$this->style->set_for("sql",array("tablename" => $tablename,"IsOkay" => false, "error" => @mysql_error()));
			}
		}
		
	}else{
		$this->style->set_if("error",true);
		for($i=0;$i<count($error);$i++)$this->style->set_for("error",array("error" => $error[$i]));
	}
}