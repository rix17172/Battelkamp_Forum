<?php
define("in_forum",true);
define("first", "");

if(empty($_GET['post'])){
	header("location: index.php");
	exit;
}

require_once 'include/main.php';
require_once 'include/class/topic.php';

$topic = new topic();

$lang_array = $lang->load_file(array(
		"new_topic.php",
		"answer_topic.php",
		'head.php',
		"post.php",
		"edit_post.php",
		"menu.php",
        'pmmenu.php',
		'pm_answer.php',
		'pm_new.php',
		));

$ini_setting_lang = $lang->get_all_file_setting_array();
if(!empty($ini_setting_lang) && is_array($ini_setting_lang))$style->set_if('allow_lang_change', true);
for($i=0;$i<count($ini_setting_lang);$i++)$style->set_for("lang_setting",array('name' => $ini_setting_lang[$i]['name'], 'flag' => $ini_setting_lang[$i]['flag'], 'map' => $ini_setting_lang[$i]['map']));
$style->set_if('map', $lang->data['map']);

$is_smylie = false;
$sql = $db->get_sql_query("SELECT * FROM ".smylie);
while($row = $db->return_array($sql)){
	$style->set_for("smylie", array(
			"url"   => urlencode($row['url']),
			"width" => $row['width'],
			"heigh" => $row['heigh'],
			"name"  => $row['name'],
			"bb"    => $row['bb'],
			));
	$is_smylie = true;
}
$style->set_if("is_smylie",$is_smylie);

if(!empty($_GET['forum_id']) && $_GET['post'] == "new_topic"){
 
 if(!$topic->may_post_title($_GET['forum_id'])){
 	header("location:index.php");
 	exit;
 }
	
 $style->set("title",$lang_array['049']);
 $user->update_location($lang_array['049']);
 $style->set("boks_title",$lang_array['050']);
 $style->set_if("title",true);
 
 if(!empty($_POST)){
 	$error = array();
 	if($user->data['is_geaust'] && empty($_POST['g_name']))$error[] = $lang_array['055'];
 	if(empty($_POST['title']))$error[] = $lang_array['056'];
 	if(empty($_POST['message']))$error[] = $lang_array['057'];
 	
 	if(empty($error)){
 		$is_user = ($user->data['is_user']) ? 1 : 0;
 		$user_name = ($user->data['is_user']) ? $user->data['username'] : $_POST['g_name'];
 		$sql_array = array(
 				"INSERT INTO ".topic_title." (f_id,is_user,user_id,user_name,title,message,post_time,last_post_time,last_write_is_user,last_write_username) VALUES (",
 				"?".$_GET['forum_id'],
 				",",
 				"?".$is_user,
 				",",
 				"?".$user->data['id'],
 				",",
 				"?".$user_name,
 				",",
 				"?".$_POST['title'],
 				",",
 				"?".$_POST['message'],
 				",'",
 				time()."','".time()."',",
 				"?".$is_user,
 				",",
 				"?".$user_name,
 				")",
 				);
 		$db->get_sql_query($db->clean_sql($sql_array));
 		$this_topic_id = $db->last_inset_id();
 		
 		$sql_array = array(
 				"SELECT post_num FROM ".forum." WHERE id=",
 				"?".$_GET['forum_id']
 				);
 		
 		$sql = $db->get_sql_query($db->clean_sql($sql_array));
 		$row = $db->return_array($sql);
 		$db->free_result($sql);
 		
 		$post_num = $row['post_num'] + 1;
 		
 		$sql_array = array(
 				"UPDATE ".forum." SET post_num=",
 				"?".$post_num,
 				", last_write=",
 				"?".$user_name,
 				", last_topic=",
 				"?".$_POST['title'],
 				",last_is_title='1',last_id=",
 				"?".$this_topic_id,
 				"WHERE id=",
 				"?".$_GET['forum_id'],
 				);
 		$db->get_sql_query($db->clean_sql($sql_array));
 		
 		
 		$user->update_post($user->data['id']);
 		
 		header("location: topic.php?title=".urlencode($_POST['title'])."&t=".$this_topic_id);
 		exit;
 		
 	}else{
 		for($i=0;$i<count($error);$i++)$style->set_for("error", array('message' => $error[$i]));
 		$style->set_if("error", true);
 	}
 }
}elseif($_GET['post'] == "answer_topic" && !empty($_GET['topic_title'])){
	$style->set("title",$lang_array['059']);
	$user->update_location($lang_array['060']);
	require_once 'include/class/topic.php';
	$topic = new topic();
	$topic_data = $topic->get_topic_data($_GET['topic_title']);
	
	if(!$topic_data || !$topic->may_answer($topic_data['f_id'])){
		header('location:index.php');
		exit;
	}
	
	$style->set("boks_title", str_replace("#topic_title#", $topic_data['title'], $lang_array['061']));
	if(!empty($_POST)){
		$error = array();
		if($user->data['is_geaust'] && empty($_POST['g_name']))$error[] = $lang_array['055'];
 	    if(empty($_POST['message']))$error[] = $lang_array['057'];

 	    if(empty($error)){
 	    	$is_user = ($user->data['is_user']) ? 1 : 0;
 	    	$user_name = ($user->data['is_user']) ? $user->data['username'] : $_POST['g_name'];
 	    	
 	    	$insert = array(
 	    			"t_id"      => GET('topic_title'),
 	    			"is_user"   => $is_user,
 	    			"u_id"      => $user->id,
 	    			"username"  => $user_name,
 	    			"message"   => POST('message'),
 	    			"post_time" => time(),
 	    	);
 	    	
 	    	$last_insert = $db->Insert(topic_message, $insert);
 	    	
 	    	$sql_array = array(
 	    			"UPDATE ".topic_title." SET last_post_time=",
 	    			"?".time(),
 	    			" WHERE id=",
 	    			"?".$_GET['topic_title'],
 	    			);
 	    	$db->get_sql_query($db->clean_sql($sql_array));
 	    	
 	    	$sql_array = array(
 	    			"SELECT post_num FROM ".forum." WHERE id=",
 	    			"?".$topic_data['f_id'],
 	    	);
 	    		
 	    	$sql = $db->get_sql_query($db->clean_sql($sql_array));
 	    	$row = $db->return_array($sql);
 	    	$db->free_result($sql);
 	    		
 	    	$post_num = $row['post_num'] + 1;
 	    		
 	    	$sql_array = array(
 	    			"UPDATE ".forum." SET post_num=",
 	    			"?".$post_num,
 	    			", last_write=",
 	    			"?".$user_name,
 	    			",last_topic=",
 	    			"?".$topic_data['title'],
 	    			", last_is_title='2', last_id=",
 	    			"?".$last_insert,
 	    			"WHERE id=",
 	    			"?".$topic_data['f_id'],
 	    	);
 	    	$db->get_sql_query($db->clean_sql($sql_array));
 	    	
 	    	//vi skal nu finde ud af hvormange post der er
 	    	$sql_array = array(
 	    			"SELECT count(id) FROM ".topic_message." WHERE t_id=",
 	    			"?".$_GET['topic_title'],
 	    			);
 	    	$sql = $db->get_sql_query($db->clean_sql($sql_array));
 	    	$row_num = $db->return_from_count($sql);
 	    	$row_num++;
 	    	$user->update_post($user->data['id']);
 	    	header("location:topic.php?title=".urlencode($topic_data['title'])."&t=".$_GET['topic_title']."#topic_".$row_num);
 	    	exit;
 	    	
 	    }else{
 	    	$style->set_if("error", true);
 	    	for($i=0;$i<count($error);$i++)$style->set_for("error", array("message" => $error[$i]));
 	    }
 	    
	}
}elseif($_GET['post'] == "change_post" && !empty($_GET['p']) && !empty($_GET['is_title'])){
 
	$is_title = ($_GET['is_title'] == "true") ? true : false;
	if(!$topic->may_change_topic($_GET['p'], $is_title)){
		header("location:index.php");
		exit;
	}
	
	if($is_title){
		$style->set_if("title",true);
	}
	
	$style->set("title",$lang_array['062']);
	$user->update_location($lang_array['062']);
	$style->set("boks_title",$lang_array['063']);
	if($_GET['is_title'] == "true" && empty($_POST)){
		$sql_array = array(
				"SELECT `title`,`message`,`is_user`,`user_name` FROM ".topic_title." WHERE id=",
				"?".$_GET['p'],
				);
		$sql = $db->get_sql_query($db->clean_sql($sql_array));
		$row = $db->return_array($sql);
		$db->free_result($sql);
		$style->set_if("title_value", true);
		$style->set("title_value",$row['title']);
		if($row['is_user'] != 1){
			$style->set_if("edit_g_name", true);
			$style->set_if("g_name_value",true);
			$style->set("g_name_value",$row['user_name']);
		}
		$style->set_if("message_value",true);
		$style->set("message_value",$row['message']);
	}elseif(empty($_POST)){
		$sql_array = array(
				"SELECT `is_user`,`username`,`message` FROM ".topic_message." WHERE id=",
				"?".$_GET['p'],
				);
		$sql = $db->get_sql_query($db->clean_sql($sql_array));
		$row = $db->return_array($sql);
		$db->free_result($sql);
		if($row['is_user'] != 1){
			$style->set_if("edit_g_name", true);
			$style->set_if("g_name_value",true);
			$style->set("g_name_value",$row['username']);
		}
		$style->set_if("message_value",true);
		$style->set("message_value",$row['message']);
		
	}
	
	if(!empty($_POST)){
		if($_GET['is_title'] == "true"){
			
			$sql_array = array(
					"SELECT `title`,`message`,`is_user`,`user_name`,`f_id`,`id` FROM ".topic_title." WHERE id=",
					"?".$_GET['p'],
			);
			$sql = $db->get_sql_query($db->clean_sql($sql_array));
			$row = $db->return_array($sql);
			$db->free_result($sql);
			
			$error = array();
			if($row['is_user'] != 1 && empty($_POST['g_name']))$error[] = $lang_array['055'];
			if(empty($_POST['title']))$error[] = $lang_array['056'];
			if(empty($_POST['message']))$error[] = $lang_array['057'];
			
			if($row['is_user'] != 1)$sql_help = array(",user_name=",
					"?".$_POST['g_name'],
					",");
			else$sql_help = array(false,false,false);
			
			if(empty($error)){
				if($row['title'] != POST("title")){
					$sql_array = array(
							"SELECT `last_is_title`,`last_id` FROM `".forum."` WHERE id=",
							"?".$row['f_id'],
					);
					$s = $db->get_sql_query($db->clean_sql($sql_array));
					$f_row = $db->return_array($s);
					$db->free_result($s);
					
					if($f_row['is_title'] == Yes){
						$t_id = $row['id'];
					}else{
						$t_id = $topic->get_over_topic($f_row['last_id']);
					}
					
					$sql_array = array(
							"UPDATE `".forum."` SET last_topic=",
							"?".POST("title"),
							"WHERE id=",
							"?".$row['f_id'],
					);
					$db->get_sql_query($db->clean_sql($sql_array));
				}
				$sql_array = array(
						"UPDATE ".topic_title." SET title=",
						"?".$_POST['title'],
						",message=",
						"?".$_POST['message'],
						$sql_help[0],
						$sql_help[1],
						$sql_help[2],
						"WHERE id=",
						"?".$_GET['p']
						);
				$db->get_sql_query($db->clean_sql($sql_array));
				header("location:topic.php?title=".urlencode($_POST['title'])."&t=".$_GET['p']."#topic_1");
				exit;
			}
			
		}else{
			$error = array();
			
			$sql_array = array(
					"SELECT `is_user`,`username`,`message`,`t_id` FROM ".topic_message." WHERE id=",
					"?".$_GET['p'],
			);
			$sql = $db->get_sql_query($db->clean_sql($sql_array));
			$row = $db->return_array($sql);
			$db->free_result($sql);
			
			if(empty($_POST['message']))$error[] = $lang_array['057'];
			if($row['is_user'] != 1 && empty($_POST['g_name']))$error[] = $lang_array['055'];
			
			if(empty($error)){
				
				if($row['is_user'] != 1)$sql_help = array(",username=",
						"?".$_POST['g_name'],
						",");
				else$sql_help = array(false,false,false);
				
				$sql_array = array(
						"UPDATE ".topic_message." SET message=",
						"?".$_POST['message'],
						$sql_help[0],
						$sql_help[1],
						$sql_help[2],
						" WHERE id=",
						"?".$_GET['p'],
						);
				$db->get_sql_query($db->clean_sql($sql_array));
				
				$topic_data = $topic->get_topic_data($row['t_id']);
				
				header("location:topic.php?title=".urlencode($topic_data['title'])."&t=".$topic_data['id']."#topic_".$_GET['pp']);
			}
		}
		
		if(!empty($error)){
			$style->set_if("error", true);
			for($i=0;$i<count($error);$i++)$style->set_for("error", array("message" => $error[$i]));
		}
		
	}
	
}elseif($_GET['post'] == "new_pm" && $user->data['is_user']){
	$style->set("boks_title",$lang_array['BoksTitle']);
	$style->set("title", $lang_array["NewPMtitle"]);
	$style->set_if("title", true);
	$style->set_if("to", true);
	
	$sql = $db->get_sql_query("SELECT `username` FROM ".user);
	while($row = $db->return_array($sql))$style->set_for("UserList", array("username" => $row['username']));
	
	if(!empty($_POST)){
		$error = array();
		if(empty($_POST['title']))$error[] = $lang_array["056"];
		if(empty($_POST['To']))$error[] = $lang_array["NoToInput"];
		elseif(!$user->GetUserIdFromUsername($_POST['To']))$error[] = $lang_array['UserNameDontExist'];
		if(empty($_POST['message']))$error[] = $lang_array["057"];
		
		if(empty($error)){
			$sql_array = array(
					"INSERT INTO ".pm_title." (from_id,to_id,gettime,title,message,fromdel,todel,fromunread,tounread,messagecount) VALUES (",
					"?".$user->data["id"],
					",",
					"?".$user->GetUserIdFromUsername($_POST['To']),
					",".time().",",
					"?".$_POST['title'],
					",",
					"?".$_POST['message'],
					",0,0,0,1,0)"
					);
			$db->get_sql_query($db->clean_sql($sql_array));
			
			header("location:pm.php?page=Read&id=".$db->last_inset_id());
			exit;
		}else{
			for($i=0;$i<count($error);$i++){
				$style->set_for("error",array("message" => $error[$i]));
				$style->set_if("error",true);
			}
		}
	}
	
}elseif($_GET['post'] == "answer_pm" && $user->data['is_user'] && !empty($_GET['PmId'])){
	$style->set("boks_title",$lang_array['AnswerPmTitleBoks']);
	$style->set("title", $lang_array["AnswerPmTitle"]);
	
	if(!empty($_POST['message'])){
		$sql_array = array(
				"SELECT `from_id` FROM ".pm_title." WHERE id=",
				"?".$_GET['PmId'],
		);
		$sql = $db->get_sql_query($db->clean_sql($sql_array));
		$TitleData = $db->return_array($sql);
		$db->free_result($sql);
		
		$sql_array = array(
				"INSERT INTO `".pm_message."` (SendtFrom,message,posttime,pm_id) VALUES (",
				"?".$user->data['id'],
				",",
				"?".$_POST['message'],
				",'".time()."',",
				"?".$_GET['PmId'],
				")",
				);
		$db->get_sql_query($db->clean_sql($sql_array));
		
		$sql_array = array(
				"UPDATE ".pm_title." SET ".(($TitleData["from_id"] == $user->data['id']) ? "tounread" : "fromunread")."=1 WHERE id=",
				"?".$_GET['PmId'],
				);
		$db->get_sql_query($db->clean_sql($sql_array));
		
		header("location:pm.php?page=Read&id=".GET("PmId"));
		exit;
	}
	
}


$style->load_file("post", "html");
$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
//echo $style->return_clean_code();
