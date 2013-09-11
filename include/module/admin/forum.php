<?php
if(!defined("in_admin")){
	exit;
}

class forum{
	
	private $style;
	private $lang,$l;
	private $user;
	private $db;
	
	function __construct(){
		global $style,$lang,$user;
		
		$this->style = $style;
		$this->lang  = $lang;
		$this->user  = $user;
		$this->db    = new Db();
		
		switch (GET("sub")){
			case "EditKat":
				$this->editKat();
			break;
			case "newKat":
				$this->newKat();
			break;
			case "newForum":
				$this->newForum();
			break;
			case "editForum":
				$this->editForum();
			break;
			case "smylie":
				$this->insertSmylie();
			break;
			default: 
				$this->getForumFront();
			break;
		}
		
		$this->style->set("Welkommen", sprintf($this->l['Welkommen'],$this->user->username));
		
		$this->style->convert_html();
		$this->style->eval_html();
	}
	
	private function insertSmylie(){
		$this->l = $this->lang->load_file(array('insertSmylie.php','top.php','leftMenu.php'));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("smylie", "html");
		$this->style->set_for("css", array('url' => 'main'));
		
		if(!class_exists("Upload")){
			IncludeExsternPage(first."include/class/upload.php");
		}
		
		$upload = new Upload(first."img/smylie/", "file");
		
		if($upload->isFile && POST("tag") && POST('name')){
			if(!$upload->controlExtension(uploadImage())){
				$this->style->set_if("Error", true);
				$this->style->set_for("Error", array(
						'Message' => $this->l['notImg'],
				));
			}else{
				$uploadResult = $upload->Save();
				if($uploadResult['is_move']){
					$this->db->Insert(smylie, array(
							'bb'    => POST('tag'),
							'heigh' => 20,
							'width' => 20,
							'url'   => $uploadResult['name'],
							'name'  => POST('name'),
					));
					
					$this->style->set_if("Okay", true);
					$this->style->set_for("Okay", array('Message' => $this->l['UploadSuccess']));
				}else{
					$this->style->set_for("Error", array("Message" => $this->l['uploadFail']));
					$this->style->set_if("Error", true);
				}
			}
		}elseif(GET("delete") && is_numeric(GET("delete"))){
			
			$sql_array = array(
				    "SELECT `id`,`url` FROM `".smylie."` WHERE `id`=",
					"?".GET("delete"),	
			);
			
			$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
			$row = $this->db->return_array($sql);
			$this->db->free_result($sql);
			
			if(!empty($row['id'])){
			$this->db->Delete(smylie, array(
					'id' => GET("delete"),
			));
			 unlink(first."img/smylie/".$row['url']);
			}
		}
		
		$sql = $this->db->get_sql_query("SELECT * FROM `".smylie."`");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("smylie", array(
					'url' => $row['url'],
					'id'  => $row['id'],
			));
		}
	}
	
	private function editForum(){
		$this->l = $this->lang->load_file(array('editForum.php',"top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		if(!class_exists("AdminForum")){
			IncludeExsternPage(first."include/class/adminForum.php");
		}
		
		$forum = new AdminForum(GET('id'));
		
		if(!$forum->is_exsit){
			header("location:?page=forum");
			exit;
		}
		
		$this->style->load_file("editForum", "html");
		$this->style->set_for("css", array("url" => 'newForum'));
		
		$this->style->set("forumName",$forum->name);
		
		$forum->setDeleteTo(GET("id"));
		
		if(POST("what")){
			switch (POST("what")){
				case "1":
					$forum->changeName(POST("forumName"));
					$this->style->set("forumName", $forum->name);
					$this->style->set_if("Okay", true);
					$this->style->set_for("Okay", array("Message" => $this->l['cNameOkay']));
				break;
				case "2":
					$this->changeAccessForum($forum);
				break;
				case "3":
					$this->deleteForum($forum);
				break;
				case "4":
					$this->moveForum();
				break;
			}
		}
		
		$geaust = $this->lang->LoadFileFromDest("defult.php");
		
		$forum->setMoveTo(GET("id"));
		
		$this->style->set_for("access", array_merge(array(
				'Name' => $geaust['Geaust'],
				'Id'   => 0,
		),$forum->getGrupAccess(0)));
		
		$sql = $this->db->get_sql_query("SELECT `name`,`id` FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("access", array_merge(array(
					'Name' => $row['name'],
					'Id'   => $row['id'],
			),$forum->getGrupAccess($row['id'])));
		}
	}
	
	private function moveForum(){
		if(!POST("move")){
			$this->style->set_if("Error",true);
			$this->style->set_for("Error", array(
					'Message' => $this->l['moveError'],
			));
			return;
		}
		
		$sql_array = array(
				'SELECT `id` FROM `'.katolori.'` WHERE `id`=',
				'?'.POST("move"),
		);
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		$row = $this->db->return_array($sql);
		$this->db->free_result($sql);
		
		if(empty($row['id'])){
			$this->style->set_if("Error",true);
			$this->style->set_for("Error", array(
					'Message' => $this->l['catDontExist'],
			));
			return;
		}
		
		$sql_array = array(
				'UPDATE `'.forum.'` SET `place`=',
				"?".(int)POST("move"),
				"WHERE `id`=",
				"?".GET("id"),
		);
		$this->db->get_sql_query($this->db->clean_sql($sql_array));
		
		$this->style->set_if("Okay", true);
		$this->style->set_for("Okay", array(
				'Message' => $this->l['isMove'],
		));
	}
	
	private function deleteForum($forum){
		if(!POST("delete")){
			return;
		}
		
		if(!is_numeric(POST("delete"))){
			$forum->deleteAllforum();
		}else{
			$forum->deleteForumAndMove(POST('delete'));
		}
		
		header("location:?page=forum");
		exit;
	}
	
	private function changeAccessForum($forum){
		
			$post = empty($_POST['id0']) ? array() : $_POST['id0'];
			$forum->changeAccess(1,0,in_array(1,$post));
			$forum->changeAccess(2,0,in_array(2,$post));
			$forum->changeAccess(3,0,in_array(3,$post));
			$forum->changeAccess(4,0,in_array(4,$post));
			$forum->changeAccess(5,0,in_array(5,$post));
			$forum->changeAccess(6,0,in_array(6,$post));
			$forum->changeAccess(7,0,in_array(7,$post));
			
			//nu har vi klaret gæst ;) så nu klare vi de bruger som klares af systemet ;)
			$sql = $this->db->get_sql_query("SELECT `id` FROM `".grup_name."`");
			while($row = $this->db->return_array($sql)){
				$post = empty($_POST['id'.$row['id']]) ? array() : $_POST['id'.$row['id']];
				$forum->changeAccess(1,$row['id'],in_array(1,$post));
				$forum->changeAccess(2,$row['id'],in_array(2,$post));
				$forum->changeAccess(3,$row['id'],in_array(3,$post));
				$forum->changeAccess(4,$row['id'],in_array(4,$post));
				$forum->changeAccess(5,$row['id'],in_array(5,$post));
				$forum->changeAccess(6,$row['id'],in_array(6,$post));
				$forum->changeAccess(7,$row['id'],in_array(7,$post));
			}
	}
	
	private function newForum(){
	   
		if(!class_exists("AdminForum")){
			IncludeExsternPage(first."include/class/adminForum.php");
		}
		
		$forum = new AdminForum(0);
		
	   $this->l = $this->lang->load_file(array("newForum.php","top.php","leftMenu.php"));
	   $this->style->load_lang($this->l);

	   $this->style->load_file("newForum", "html");
	   $this->style->set_for("css", array("url" => 'newForum'));
	   
	   if(POST("forumName")){
	   	 $id = $forum->newForum(POST("forumName"),GET("level"));
	   	 header("location:?page=forum&sub=editForum&id=".$id);
	   	 exit;
	   }
	}
	
	private function newKat(){
		$this->l = $this->lang->load_file(array("newKat.php","top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("newKat", "html");
		$this->style->set_for("css", array("url" => 'newKat'));
		
		if(!class_exists("AdminKatolori")){
			IncludeExsternPage(first."include/class/adminKat.php");
		}
		
		global $admin;
		
		$admin->createBred(GET("level"));
		
		$kat = new AdminKatolori();
		
		if(POST("katNmae")){
			$id = $kat->createKat(POST("katNmae"), GET("level"));
			header("location:?page=forum&sub=EditKat&id=".$id);
			exit;
		}
	}
	
	private function editKat(){
		
		if(!class_exists("AdminKatolori")){
			IncludeExsternPage(first."include/class/adminKat.php");
		}
		
		$kat = new AdminKatolori();
		
		$this->l = $this->lang->load_file(array("editKat.php","top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("editKat", "html");
		$this->style->set_for("css", array("url" => "editKat"));
		
		if(!GET("id") || !is_numeric(GET("id"))){
			header("location:?page=forum");
			exit;
		}
		
		$SqlArray = array(
				"SELECT * FROM `".katolori."` WHERE id=",
				"?".GET("id"),
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($SqlArray));
		$row = $this->db->return_array($sql);
		
		if(empty($row['id'])){
			header("location:?page=forum");
			exit;
		}
		
		global $admin;
		$admin->createBred($row['place']);
		
		$this->style->set("katName", $row['name']);
		
		$geaust = $this->lang->LoadFileFromDest("defult.php");
		
		$this->style->set_for("access", array(
				'Id'   => 0,
				'Name' => $geaust['Geaust'],
				"MaySee" => $kat->maySee(0, GET("id")),
		));
		
		//change access
		$sql = $this->db->get_sql_query("SELECT `name`,`id` FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("access", array(
					'Id'     => $row['id'],
					"Name"   => $row['name'],
					"MaySee" => $kat->maySee($row['id'], GET("id")),
			));
		}
		
		//slet (Delete) ;)

		$this->style->set_for("delete", array(
				'Id'   => 'all',
				'Name' => $this->l['deleteAll'],
		));
		
		$this->setDeleteKatChose(0, true, GET("id"));
		
		
		if(POST("sort") && is_numeric(POST("sort"))){
			switch (POST("sort")){
				case "1":
					$this->changeKatName();
				break;
				case "2":
					$this->changeAccess($geaust);
				break;
				case "3":
					$this->deleteKat();
				break;
			}
		}
	}
	
	private function deleteKat(){
		if(!POST("deleteTo")){
			return;
		}
		
		if(!class_exists("AdminKatolori")){
			IncludeExsternPage(first."include/class/adminKat.php");
		}
		
		$kat = new AdminKatolori();
		
		if(POST("deleteTo") == 'all' || !is_numeric(POST("deleteTo"))){
			$kat->deleteAll(GET("id"));
		}else{
			$kat->moveForumsToAndDeleteKat(GET("id"), POST("deleteTo"));
		}
		header("location:?page=forum");
		exit;
	}
	
	private function setDeleteKatChose($id,$isKat,$includeNot,$first = null){
		if($isKat){
			$this->deleteKatChoseKat($id, $includeNot,$first);
		}else{
			$this->deleteKatChoseForum($id, $includeNot, $first);
		}
	}
	
	private function deleteKatChoseForum($id,$includeNot,$first = null){
		$sql_array = array(
				"SELECT `id`,`name` FROM `".forum."` WHERE `place`=",
				"?".$id,
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		
		while($row = $this->db->return_array($sql)){
			$this->setDeleteKatChose($row['id'], true, $includeNot , $first.$row['name']." -> ");
		}
	}
	
	private function deleteKatChoseKat($id,$includeNot,$first = null){
		$sql_array = array(
				"SELECT `id`,`name` FROM `".katolori."` WHERE `place`=",
				"?".$id,
				"AND `id`!=",
				"?".$includeNot,
		);
		
		$sql = $this->db->get_sql_query($this->db->clean_sql($sql_array));
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("delete", array(
					'Id'   => $row['id'],
					'Name' => $first.$row['name'],
			));
			
			$this->setDeleteKatChose($row['id'], false, $includeNot,$first.$row['name']." -> ");
		}
	}
	
	private function changeAccess($geaust){
		if(!class_exists("AdminKatolori")){
			IncludeExsternPage(first."include/class/adminKat.php");
		}
		
		$kat = new AdminKatolori();
		
		
		//unset access
		$this->style->emptyFor("access");
		
		$allow = 'none';
		
		if(POST("0")){
			if(!$kat->maySee(0, GET("id"))){
				$kat->setAccess(0, GET("id"));
				$allow = true;
			}
		}else{
			if($kat->maySee(0, GET("id"))){
				$kat->deleteAccess(0, GET("id"));
				$allow = false;
			}
		}
		
		if($allow == 'none')$allow = $kat->maySee(0, GET("id"));
		
		$this->style->set_for("access", array(
				'Id'     => 0,
				'Name'   => $geaust['Geaust'],
				"MaySee" => $allow,
		));

		$sql = $this->db->get_sql_query("SELECT `id`,`name` FROM `".grup_name."`");
		while($row = $this->db->return_array($sql)){
			$allow = 'none';
			
			if(POST($row['id'])){
				if(!$kat->maySee($row['id'], GET("id"))){
					$kat->setAccess($row['id'], GET("id"));
					$allow = true;
				}
			}else{
				if($kat->maySee($row['id'], GET("id"))){
					$kat->deleteAccess($row['id'], GET("id"));
					$allow = false;
				}
			}
			
			if($allow == 'none')$allow = $kat->maySee($row['id'], GET("id"));
			
			$this->style->set_for("access", array(
					'Id'     => $row['id'],
					'Name'   => $row['name'],
					"MaySee" => $allow,
			));
		}
		
		$this->style->set_if("Okay", true);
		$this->style->set_for("Okay", array(
				'Message' => $this->l['ChanegAOkay'],
		));
	}
	
	private function changeKatName(){
		
		if(!class_exists("AdminKatolori")){
			IncludeExsternPage(first."include/class/adminKat.php");
		}
		
		$kat = new AdminKatolori();
		
		if(!POST("Name")){
			$this->style->set_if("Error", true);
			$this->style->set_for("Error", array(
					"Message" => $this->l['NameEmpty'],
			));
		}else{
			$kat->changeKatName(GET("id"), POST("Name"));
			$this->style->set_if("Okay", true);
			$this->style->set_for("Okay", array(
					"Message" => $this->l['KatIsU'],
			));
			$this->style->set("katName", POST("Name"));
		}
	}
	
    private	function getForumFront(){
		$this->l = $this->lang->load_file(array("forum.php","top.php","leftMenu.php"));
		$this->style->load_lang($this->l);
		
		$this->style->load_file("forumFront", "html");
		$this->style->set_for("css", array("url" => 'forumFront'));
		
		$place = !GET("forum") || !is_numeric(GET("forum")) ? 0 : GET("forum");
		
		$admin = new Admin();
		$admin->createBred($place);
		
		$this->style->set("level", $place);
		
		$sql = $this->db->get_sql_query("SELECT * FROM `".katolori."` WHERE `place`='".$place."'");
		while($row = $this->db->return_array($sql)){
			$this->style->set_for("fk", array(
					"isKat" => true,
					"Name"  => $row['name'],
					"Id"    => $row['id'],
			));
			
			
			$forumSql = $this->db->get_sql_query("SELECT * FROM `".forum."` WHERE place='".$row['id']."'");
			while($forumRow = $this->db->return_array($forumSql)){
				$this->style->set_for("fk", array(
						"isKat"   => false,
						"Id"      => $forumRow['id'],
						"Name"    => $forumRow['name'],
						"numPost" => $forumRow['post_num'],
				));
			}
			
		}
	}
}