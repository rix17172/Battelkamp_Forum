<?php
define('in_forum',true);
define('first','');

if(empty($_GET['mode'])){
	header("location:index.php");
	exit;
}

require_once 'include/main.php';
require_once 'include/class/modul.php';

$modul = new Modul();
$modul->SetGet("mode");
$modul->SetPage("login", "ucp_login.php");
$modul->SetPage("login_ok", "ucp_login_ok.php");
$modul->SetPage("rig", "ucp_rig.php");
$modul->RunPage();
/*

function AktiveringsLink(){

	$GenrateActivLink = function(){
		$UseChar = "abcdefghijklmnopqrstABCDEFGHIJKLMNOPQRST123456789";
		$res = null;
		for($i = 0; $i < 100; $i++) {
			$res .= $UseChar[rand(0, strlen($UseChar) - 1)];
		}
		return $res;
	};
	
	
	$g = $GenrateActivLink();
	$SN = "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?mode=activering&amp;activ_id=".$g;
	return array($SN,$g);
}

function RequstNewPasswordLink($Uid){
	$RequstPasswordKey = function(){
		$UserChar = "abcdefghijklmnopqrstABCDEFGHIJKLMNOPQRST123456789";
		$res = null;
		for($i=0;$i<100;$i++){
			$res .= $UserChar[rand(0,strlen($UserChar)-1)];
		}
		return $res;
	};
	
	$NewKey = $RequstPasswordKey();
	
	global $db;
	
	$sql_array = array(
			"UPDATE `".user."` SET status=",
			"?".UserBlock,
			", ActivieringKey=",
			"?".$NewKey,
			"WHERE id=",
			"?".$id
			);
	
	$db->get_sql_query($db->clean_sql($sql_array));
	
	$sn = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?mode=AcceptRequstPassword&amp;key=".$NewKey;
	$dn = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?mode=DesilineRequstPassword&amp;key=".$NewKey;
	
	return array($sn,$dn,$NewKey);
	
}


$lang_array = $lang->load_file(array(
		'ucp_rig.php',
		'ucp_login.php',
		'ucp_login_ok.php',
		'ucp_activate.php',
		'ucp_forgotPassword.php',
		
		'menu.php',
		));

if($_GET['mode'] == "rig"){
	$style->load_file("ucp_rig", "html");
	$style->set('title',$lang_array['009']);
	$user->update_location($lang_array['009']);
	
	if(!empty($_POST)){
		$error = array();
		if(empty($_POST['username']))$error[] = $lang_array['016'];
        else{
        	$sql_array = array(
        			"SELECT `id` FROM `".user."` WHERE username=",
        			"?".$_POST['username'],
        			);
        	$sql = $db->get_sql_query($db->clean_sql($sql_array));
            $row = $db->return_array($sql);
            $db->free_result($sql);
            if(!empty($row['id']))$error[] = $lang_array['022'];
        }
		if(!POST("email")){
			$error[] = $lang_array['018'];
		}else{
			$sql_array = array(
					"SELECT `id` FROM `".user."` WHERE email=",
					"?".POST("email"),
					);
			$sql = $db->get_sql_query($db->clean_sql($sql_array));
			$row = $db->return_array($sql);
			$db->free_result($sql);
			if(!empty($row['id'])){
				$error[] = $lang_array["EmailIsTaken"];
			}
		}
		if(empty($_POST['password']))$error[] = $lang_array['019'];
		if(empty($_POST['re_password']))$error[] = $lang_array['020'];
		if(!empty($_POST['password']) && !empty($_POST['re_password']) && $_POST['password'] != $_POST['re_password'])$error[] = $lang_array['021'];

		if(empty($error)){
			$rig_val = $setting->data['RIG_VALIATE'];
			$status = $rig_val == 1 ? 1 : (($rig_val == 3) ? 2 : 0);
			$array = AktiveringsLink();
			$AktiveringsKey = $array[1];
			$time = time();
			$sql_array = array(
					"INSERT INTO ".user." (username, password,email,opret_time,last_online,status,post,ActivieringKey,ip) VALUES (",
					"?".$_POST['username'],
					",",
					"?".$user->hash_password($_POST['password'], $time),
					",",
					"?".$_POST['email'],
					",'".$time."','".$time."','".$status."','0',",
					"?".$AktiveringsKey,
					",",
					"?".$user->GetUserIp(),
					")",
					);
			$db->get_sql_query($db->clean_sql($sql_array));
			$sql_array = array(
					"INSERT INTO ".grup_member." (u_id,g_id) VALUES (",
					"?".$db->last_inset_id(),
					",",
					"?".$setting->data['STAND_GRUP'],
					")",
					);
			$db->get_sql_query($db->clean_sql($sql_array));
			switch ($rig_val){
				case 1:
					$style->set_if("ok", true);
					$style->set("ok", $lang_array['024']);
				break;
				case 2:
					require_once 'include/class/Sendmail.php';
					$Sendmail = new Sendmail($_POST['email'], "newuser.txt");
					$Sendmail->SetVariabel("Username", $_POST['username']);
					$Sendmail->SetVariabel("AktivLink", $array[0]);
					$Sendmail->Send();
					$style->set_if("warning", true);
					$style->set("warning", $lang_array['026']);
				break;
				case 3:
					$style->set_if("warning", true);
					$style->set("warning", $lang_array['027']);					
				break;
			}
		}else{
			$style->set_if("if_error", true);
			for($i=0;$i<count($error);$i++)$style->set_for('error', array('error' => $error[$i]));
		}		
	}
}elseif($_GET['mode'] == "login"){
	$style->load_file('ucp_login', "html");
	$style->set("title",$lang_array['028']);
	$user->update_location($lang_array['028']);
	if(!empty($_POST)){
		$error = array();
		if(empty($_POST['username']))$error[] = $lang_array['034'];
		if(empty($_POST['password']))$error[] = $lang_array['035'];
		if(empty($error)){
			$sql_array = array(
					"SELECT * FROM `".user."` WHERE username=",
					"?".$_POST['username'],
			);
			$sql = $db->get_sql_query($db->clean_sql($sql_array));
			$row = $db->return_array($sql);
			$db->free_result($sql);
			if(empty($row['id'])){
				$style->set_if("if_error", true);
				$style->set_for("error",array('error' => $lang_array['036']));
			}else{
				if($row['password'] == $user->hash_password($_POST['password'], $row['opret_time'])){
					if($row['status'] == 1){
						if(!empty($_POST['rem'])){
							setcookie("user_name", $row['username'], strtotime("+1 year"), path);
							setcookie("user_id", $row['id'], strtotime("+1 year"), path);
						}else{
							$_SESSION['user_name'] = $row['username'];
							$_SESSION['user_id']   = $row['id'];
						}
						//vi kontrollere om ip'en stadig er rigtigt
						if($row['ip'] != $user->GetUserIp()){
							$user->UpdateUserIp($row['id']);
						}
						header('location:ucp.php?mode=login_ok');
						exit;
					}else{
						$style->set_if("if_error", true);
						$style->set_for("error",array('error' => $lang_array['037']));						
					}
				}else{
					$style->set_if("if_error", true);
					$style->set_for("error",array('error' => $lang_array['036']));					
				}
			}
		}else{
			$style->set_if("if_error",true);
			for($i=0;$i<count($error);$i++)$style->set_for("error", array('error' => $error[$i]));
		}
	}
}elseif($_GET['mode'] == "login_ok"){
	$style->load_file('login_ok', "html");
	$style->set("title",$lang_array['038']);
	$style->set_if("new_page", true);
}elseif($_GET['mode'] == "activering" && !empty($_GET['activ_id'])){
	$style->load_file("activate", "html");
	
	
	$sql_array = array(
			"SELECT `id`,`status` FROM ".user." WHERE ActivieringKey=",
			"?".$_GET['activ_id'],
			);
	$sql = $db->get_sql_query($db->clean_sql($sql_array));
	$row = $db->return_array($sql);
	$db->free_result($sql);
	
	if(empty($row['id'])){
		$style->set("title", $lang_array['InvalidKey']);
		$style->set_if("is_error", true);
		$style->set("ErrorMessage",$lang_array['ErrorInvalidKey']);
	}elseif($row['status'] == 1){
		$style->set("title", $lang_array['KontoIsActiv']);
		$style->set_if("is_error", true);
		$style->set("ErrorMessage",$lang_array['KontoIsAllradyActi']);
	}else{
		$sql_array = array(
				"UPDATE ".user." SET status=0 WHERE id=",
				"?".$row['id'],
				);
		$db->get_sql_query($db->clean_sql($sql_array));
		
		$style->set("title",$lang_array['KontoIsNowActiv']);
		$style->set_if("is_okay",true);
		$style->set("OkayMessage",$lang_array['OkayKontoIsNowActiv']);
	}
	
}elseif($_GET['mode'] == "FogotPassword"){
    $style->load_file("forgotPassword", "html");
    $style->set("title", $lang_array['FogotPasswordTitle']);
    $user->update_location($lang_array['FogotPasswordTitle']);
    
    if(POST("post")){
    	if(!POST("email")){
    		$style->set_if("if_error", true);
    		$style->set_for("error", array("error" => $lang_array['NoEmail']));
    	}else{
    		$sql_array = array(
    				"SELECT `email`,`username`,`id` FROM `".user."` WHERE email=",
    				"?".POST("email"),
    				);
    		$sql = $db->get_sql_query($db->clean_sql($sql_array));
    		$row = $db->return_array($sql);
    		$db->free_result($sql);
    		
    		if(empty($row['username'])){
    			$style->set_if("if_error", true);
    			$style->set_for("error", array("error" => $lang_array['NoUserNameForEmail']));
    		}else{
    			
    			$NewKey = RequstNewPasswordLink($row['id']);
    			
    			require_once 'include/class/Sendmail.php';
    			$mail = new Sendmail($row['email'], "RequstNewEmail.txt");
    			$mail->SetVariabel("Username", $row['username']);
    			$mail->SetVariabel("SiteName", $setting->data['SiteName']);
    		}
    	}
    }
    
}else{
	header("location:index.php?err=NonPage");
	exit;
}

$style->load_lang($lang_array);
$style->convert_html();
$style->eval_html();
*/