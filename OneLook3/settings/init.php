<?php

session_start();

$set_class = new stdClass();
$page = new stdClass();
$page->navbar = array();

$set_class->url = "http://localhost:8888/OneLooks/OneLook3/";
$set_class->register = "http://localhost:8888/OneLooks/OneLook3/";
 $set_class->site_name = "http://localhost:8888/OneLooks/OneLook3/";
define("OneLook_ROOT", dirname(dirname(__FILE__)));

include "db.php";

include OneLook_ROOT."/settings/mysql.class.php";
include OneLook_ROOT."/settings/users.class.php";
include OneLook_ROOT."/settings/nav.class.php";
include OneLook_ROOT."/settings/mail.class.php";


$db = new OneLook(array(
	'host' 	=> $set_class->db_host,
	'user'	=> $set_class->db_user,
	'pass'	=> $set_class->db_pass,
	'db'=> $set_class->db_name));

if(!($db_set = $db->getRow("SELECT * FROM `".OneLook_PREFIX."settings` LIMIT 1"))) {

}

$set_class = (object)array_merge((array)$set_class,(array)$db_set);

$nav = new nav;
$the_user = new User($db);
$html_options = new mail;



if(!$the_user->islg() && isset($_COOKIE['user']) && isset($_COOKIE['pass'])) {
	 if($auser = $db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `username` = ?s AND `password` = ?s", $_COOKIE['user'], $_COOKIE['pass'])) {
	 	$_SESSION['user'] = $auser->userid;
	 	$the_user = new User($db);
	}

} else {

	$time = time();

	if(!isset($_SESSION['last_login']))
		$_SESSION['last_login'] = 0;


	if($_SESSION['last_login'] < $time - 60 * 2){
		$db->query("UPDATE `".OneLook_PREFIX."users` SET `lastactive` = '".$time."' WHERE `userid`='".$the_user->data->userid."'");
		$_SESSION['last_login'] = $time;
	}
}
