<?php
define("in_forum",true);
define("first","");

require_once 'include/main.php';

if(empty($_GET['mode'])){
	header("location:index.php");
	exit;
}

$ini_setting_lang = $lang->get_all_file_setting_array();
if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);
for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));
$style->set_if('map', $lang->data['map']);

if($_GET['mode'] == "online"){
	$lang_array = $lang->load_file(array(
			'online_over.php',
			'menu.php',
			'head.php',
		    'menu.php',
		    'pmmenu.php',
			));
	$style->load_file("online_over", "html");
	$user->update_location($lang_array['045']);
	$count_user = 0;
	$sql = $db->get_sql_query("SELECT `username`,`page_title`,`url` FROM ".user." WHERE last_online > ".strtotime("-5 minute"));
	while($row = $db->return_array($sql)){
		$style->set_for("bruger", array(
			'name' => $row['username'],
			'where' => $row['page_title'],
			'url'   => $row['url'],
			 ));
	        $count_user++;
	}
	$style->set_if("count_user", $count_user);
	$num = 1;
	$counr_geust = 0;
	$sql = $db->get_sql_query("SELECT * FROM ".geaust." WHERE time > ".strtotime("-5 minute"));
	while($row = $db->return_array($sql)){
		$style->set_for("geust",array(
				'num' => $num,
				'where' => $row['title'],
				'url'   => $row['url'],
				));
		$num++;
		$counr_geust++;
	}
	$style->set_if("count_geaust", $counr_geust);
}elseif($_GET['mode'] == "all_member"){
	$lang_array = $lang->load_file(array(
			'user_list.php',
			'head.php',
			'menu.php',
		    'pmmenu.php'
			));
	
	$sql = $db->get_sql_query("SELECT u.username,gn.name FROM `".user."` AS u JOIN `".grup_member."` AS gm JOIN `".grup_name."` AS gn ON gm.u_id=u.id AND gn.id=gm.g_id");
	while($row = $db->return_array($sql)){
		$style->set_for("list", array(
				"name"        => $row['username'],
				"gruppe_name" => $row['name'],
				));
	}
	
	$style->load_file('user_list',"html");
	$user->update_location($lang_array['067']);
}elseif($_GET['mode'] == "team"){
    $lang_array = $lang->load_file(array(
    		'team.php',
			'head.php',
			'menu.php',
		    'pmmenu.php'
    		));	
    $style->load_file("team", "html");
    
    $sql = $db->get_sql_query("SELECT `name`,`id` FROM ".grup_name." WHERE show_team='1'");
    while($row = $db->return_array($sql)){
    	$style->set_for("list", array(
    			'type' => 'title',
    			'name' => $row['name'],
    			));
    	
    	$sql_array = array(
    			"SELECT user.username FROM ".grup_member." AS grup JOIN ".user." AS user ON grup.g_id=",
    			"?".$row['id'],
    			" AND user.id = grup.u_id"
    			);
    	$sql_to = $db->get_sql_query($db->clean_sql($sql_array));
    	while($row_user = $db->return_array($sql_to)){
    		$style->set_for("list",array(
    				'type' => 'list',
    				'name' => $row_user['username'],
    				));
    	}
    	
    }
    
}elseif($_GET['mode'] == "edit_profile" && $user->data['is_user']){
	$lang_array = $lang->load_file(array(
			'menu.php',
			'head.php',
			'pmmenu.php',
			'edit_user.php',
			));
	
	$user_name = $user->data['username'];
	$email     = $user->data['email'];
	
	if(!empty($_POST['what'])){
		$error = array();
		if($_POST['what'] == "1"){
		  if(empty($_POST['username']))$error[]  = $lang_array['no_p_username'];
		  elseif(empty($_POST['email']))$error[] = $lang_array['no_p_email'];
		  else{
		  	$sql_array = array(
		  			"UPDATE ".user." SET username=",
		  			"?".$_POST['username'],
		  			", email=",
		  			"?".$_POST['email'],
		  			" WHERE id=",
		  			"?".$user->data['id'],
		  			);
		  	$db->get_sql_query($db->clean_sql($sql_array));
		  	
		  	//vi ændre en cookie HVIS den findes. så undgår vi at brugeren bliver logget ud
		  	if(!empty($_COOKIE['user_name'])){
		  		setcookie("user_name", $_POST['username'], strtotime("+1 year"), path);
		  	}
		  	
		  	$_SESSION['user_name'] = POST("username");
		  	
		  	$user_name = $_POST['username'];
		  	$email     = $_POST['email'];
		  	$style->set_if("is_okay", true);
		  }
		}elseif($_POST['what'] == '2'){
			$sql_array = array(
					"SELECT `password` FROM ".user." WHERE id=",
					"?".$user->data['id'],
					);
			$sql = $db->get_sql_query($db->clean_sql($sql_array));
			$row = $db->return_array($sql);
			$db->free_result($sql);
			
			if(empty($_POST['old_password']))$error[] = $lang_array['empty_o_password'];
		    elseif($row['password'] != $user->hash_password($_POST['old_password'], $user->data['opret_time']))$error[] = $lang_array['invalid_o_pass'];
		    if(empty($_POST['new_password']))$error[] = $lang_array['empty_n_password'];
		    if(empty($_POST['again_password']))$error[] = $lang_array['empty_a_password'];
		    if(empty($error)){
		    	$sql_array = array(
		    			"UPDATE ".user." SET password=",
		    			"?".$user->hash_password($_POST['new_password'], $user->data['opret_time']),
		    			" WHERE id=",
		    			"?".$user->data['id'],
		    			);
		    	$db->get_sql_query($db->clean_sql($sql_array));
		    	$style->set_if("is_okay", true);
		    }
		}elseif(POST("what") == "3"){
			$error = array();
			
			if(!POST("timeFormat"))$error[] = $lang_array['noTime'];
			
			if(empty($error)){
				$user->TimeFormat = POST("timeFormat");
				$style->set_if("is_okay", true);
			}
		}
		
		if(!empty($error)){
			for($i=0;$i<count($error);$i++)$style->set_for("error", array('error' => $error[$i]));
			$style->set_if("is_error", true);
		}
		
	}
	
	$style->set("username",$user_name);
	$style->set("email",   $email);
	$style->set("timeFormat", $user->TimeFormat);
	
	$style->load_file("edit_profil", "html");
}else{
	header("location:index.php");
	exit;
}

$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
//echo $style->return_clean_code();