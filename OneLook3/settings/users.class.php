<?php


class User {

	var $db;

	var $filter;

	var $data;

	var $group;

	function __construct($db) {
		$this->db = $db;
		$this->data = new stdClass();
		$this->filter = array();


		if($this->islg()){
			$this->data = $this->grabData($_SESSION['user']);

			if($this->data) {

				foreach ($this->data as $k => $o) {
					$this->filter[$k] = htmlentities($o, ENT_QUOTES);
				}

				$this->filter = (object)$this->filter;
			} else
				$this->logout();

		}



		if(!$this->islg()) {

			$this->filter = new stdClass();
			$this->data = new stdClass();
			$this->filter->username = "Guest";
			$this->data->userid = 0;
			$this->data->groupid = 1;
			$this->data->banned = 0;
		}

		$this->group = $this->getGroup();
	}

	function islg() {
		if(isset($_SESSION['user']))
			return true;
		return false;
	}
	function getAvatar($userid = 0, $size = null) {
		global $set_class;
		if($size)
			$size = "?s=$size";
		if(!$userid) {
			if($this->data->showavt)
				return "$set_class->url/images/person-icon.jpg";
		}
		$u = $this->db->getRow("SELECT `email`, `showavt` FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $userid);
		if($u->showavt)
			return "$set_class->url/images/person-icon.jpg";

	}

	function getGroup($userid = 0) {

		if(!$userid)
			return $this->db->getRow("SELECT * FROM `".OneLook_PREFIX."groups` WHERE `groupid` = ?i", $this->data->groupid);

		$u = $this->db->getRow("SELECT `groupid` FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $userid);
		return $this->db->getRow("SELECT * FROM `".OneLook_PREFIX."groups` WHERE `groupid` = ?i", $u->groupid);
	}

	function getBan($userid = 0) {

		if(!$userid)
			$userid = $this->data->userid;
		return $this->db->getRow("SELECT * FROM `".OneLook_PREFIX."banned` WHERE `userid` = ?i", $userid);
	}

	function showName($userid = 0) {

		if(!$userid)
			if($this->data->banned)
				return "<strike>".$this->filter->display_name."</strike>";
			else
				return "<font>".$this->filter->display_username."</font>";

		$u = $this->db->getRow("SELECT `display_name`,`banned` FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $userid);
		$group = $this->getGroup($userid);

		if($u->banned)
			return "<strike>".htmlentities($u->display_name, ENT_QUOTES)."</strike>";
		else
			return "<font'>".htmlentities($u->display_name, ENT_QUOTES)."</font>";
	}

	function hasPrivilege($userid, $userid2 = 0) {

		$group = $this->getGroup($userid);

		if(!$userid2) {
			if(($this->group->type >=3) || ($this->group->type > $group->type) || (($this->group->type == $group->type) && ($this->group->priority > $group->priority)))
				return TRUE;
			return FALSE;
		}

		$group2 = $this->getGroup($userid2);

		if(($group2->type > $group->type) || (($group2->type == $group->type) && ($group2->priority > $group->priority)))
			return TRUE;
		return FALSE;



	}

	function exists($userid) {
		if($this->db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $userid))
			return TRUE;
		return FALSE;
	}

	function grabData($userid) {
		return $this->db->getRow("SELECT * FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $userid);
	}

	function adminUser($userid = 0) {
		if(!$userid)
			if($this->group->type >= 3)
				return TRUE;
			else
				return FALSE;

		$u = $this->db->getRow("SELECT `username`,`banned` FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $userid);
		$group = $this->getGroup($userid);
		if($group->type >= 3)
			return TRUE;
		return FALSE;
	}

	function logout() {
		global $set_class;
		session_unset('user');
		$path_info = parse_url($set_class->url);
		setcookie("user", 0, time() - 3600 * 24 * 30, $path_info['path']);
		setcookie("pass", 0, time() - 3600 * 24 * 30, $path_info['path']); 
	}


}
