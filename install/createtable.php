<?php
if(!defined("in_install") || !is_object($this))exit;

$prefix = empty($_POST['prefix']) ? null : $_POST['prefix'];

$sql_array = array(
		"CREATE TABLE IF NOT EXISTS `".$prefix."admin_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g_id` int(11) NOT NULL,
  `a_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `place` int(11) NOT NULL,
  `post_num` int(255) NOT NULL,
  `last_write` varchar(255) DEFAULT NULL,
  `last_topic` text,
  `last_is_title` tinyint(1) DEFAULT NULL,
  `last_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",

		"CREATE TABLE IF NOT EXISTS `".$prefix."forum_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_id` int(11) NOT NULL,
  `g_id` int(11) NOT NULL,
  `a_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."geaust` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g_id` int(11) NOT NULL,
  `time` int(20) NOT NULL,
  `title` text,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."grup_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `g_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."grup_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `show_team` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."katolori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `place` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."katolori_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `k_id` int(11) NOT NULL,
  `g_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."last_visist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_user` int(1) NOT NULL,
  `u_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `time` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `opret_time` int(50) NOT NULL,
  `last_online` int(50) NOT NULL,
  `status` int(1) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `post` int(11) NOT NULL,
  `ActivieringKey` varchar(101) DEFAULT NULL,
  `ip` varchar(14),
  `TimeFormat` varchar(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."pm_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SendtFrom` int(11) NOT NULL,
  `message` text NOT NULL,
  `posttime` int(50) NOT NULL,
  `pm_id` int(11) NOT NULL,
  `fromdel` int(1),
  `todel` int(1),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."pm_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `gettime` int(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `fromdel` tinyint(1) NOT NULL,
  `todel` tinyint(1) NOT NULL,
  `fromunread` tinyint(1) NOT NULL,
  `tounread` tinyint(1) NOT NULL,
  `messagecount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `is_title` int(1) NOT NULL,
  `report_op` int(11) NOT NULL,
  `report_reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."report_op` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `options` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."smylie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `width` int(11) NOT NULL,
  `heigh` int(11) NOT NULL,
  `bb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."topic_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_id` int(11) NOT NULL,
  `is_user` int(1) NOT NULL,
  `u_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `post_time` int(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."topic_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_id` int(11) NOT NULL,
  `is_user` int(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `post_time` int(50) NOT NULL,
  `last_post_time` int(50) NOT NULL,
  `last_write_is_user` int(1) NOT NULL,
  `last_write_username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;",
		
		"CREATE TABLE IF NOT EXISTS `".$prefix."warn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_op` int(11) NOT NULL,
  `grund` text NOT NULL,
  `af` int(11) NOT NULL,
  `til` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;",
		);



mysql_connect($_POST['host'],$_POST['user'],$_POST['pass']) OR die(mysql_error());
mysql_select_db($_POST['data']) OR die(mysql_error());

for($i=0;$i<count($sql_array);$i++){
	preg_match("/EXISTS \`(.*?)\`/", $sql_array[$i],$regs);
	$TableName = str_replace($prefix, "", $regs[1]);
	
	if(@mysql_query($sql_array[$i])){
		$this->style->set_for("sql", array("tablename" => $TableName, "IsSu" => true));
	}else{
		$this->style->set_for("sql", array("tablename" => $TableName, "IsSu" => false, "error" => @mysql_error()));
		break;
	}
}