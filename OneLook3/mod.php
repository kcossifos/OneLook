<?php

include "settings/init.php";


if(!isset($_GET['act']) || !isset($_GET['id']) || (!$the_user->exists($_GET['id'])) || !($the_user->hasPrivilege($_GET['id']))) {
	header("Location: ". $set_class->url);
	exit;
}

$u = $the_user->grabData($_GET['id']);


$page->title = "Moderator Panel";


$act = $_GET['act'];

$show_content = '';




if(($act == 'ban') && $the_user->group->canban && ($the_user->data->userid != $u->userid)) {

	if($_POST) {
		$period = $_POST['period'];
		$reason = $_POST['reason'];
		if(($period > 0 && $period <= $set_class->max_ban_period) && isset($reason[5])) {
			$period *= 3600*24;
			$db->query("UPDATE `".OneLook_PREFIX."users` SET `banned` = '1' WHERE `userid` = '$u->userid'");
			$db->query("INSERT INTO `".OneLook_PREFIX."banned` SET `userid` = ?i, `by` = ?i, `until` = ?i, `reason` = ?s", $u->userid, $the_user->data->userid, time()+$period, $reason);
			$page->success = "User has been banned successfully for ".(int)$_POST['period']." day(s) ! ";
		} else {
			$page->error = "Invalid period or reason !";
		}

	} else {
		$ban_options = '';
		for($i = 1; $i <= $set_class->max_ban_period; $i++)
			$ban_options .= "<option value='$i'>$i day".($i == 1 ? '' : 's')."</option>";

		$show_content = "
			<form class='well form-horizontal' action='#' method='post'>
			<fieldset>

			<!-- Form Name -->
			<legend>Ban ".$html_options->html($u->username)."</legend>

			<!-- Select Basic -->
			<div class='control-group'>
			  <label class='control-label' for='period'>Period</label>
			  <div class='controls'>
			    <select id='period' name='period' class='input-xlarge'>

			    	$ban_options

			    </select>
			  </div>
			</div>

			<div class='control-group'>
			  <label class='control-label' for='reason'>Reason</label>
			  <div class='controls'>
			    <input type='text' id='reason' name='reason'>
			  </div>
			</div>

			<!-- Button -->
			<div class='control-group'>
			  <label class='control-label' for='submit'></label>
			  <div class='controls'>
			    <button id='submit' name='submit' class='btn btn-primary'>Ban</button>
			  </div>
			</div>

			</fieldset>
			</form>


		";

		if($u->banned) {
			$banned = $the_user->getBan($u->userid);
			$show_content = "
			<form class='well form-horizontal' action='?act=unban&id=$u->userid' method='post'>
			<fieldset>

			<!-- Form Name -->
			<legend>UnBan ".$html_options->html($u->username)."</legend>
			".$html_options->info("This user was banned by <a href='$set_class->url/profile.php?u=$banned->by'>".$the_user->showName($banned->by)."</a> for `<i>".$html_options->html($banned->reason)."</i>`.",1)."
			<!-- Button -->
			<div class='control-group'>
			  <label class='control-label' for='submit'></label>
			  <div class='controls'>
			    <button id='submit' name='submit' class='btn btn-primary'>UnBan</button>
			  </div>
			</div>

			</fieldset>
			</form>
			";
		}



	}
} else if(($act == 'unban') && $the_user->group->canban) {
	$db->query("UPDATE `".OneLook_PREFIX."users` SET `banned` = '0' WHERE `userid` = ?i", $u->userid);
	$db->query("DELETE FROM `".OneLook_PREFIX."banned` WHERE `userid` = ?i", $u->userid);
	header("Location: ". $set_class->url."/profile.php?u=$u->userid");
	exit;
} else if(($act == 'avt') && $the_user->group->canhideavt) {
	if($u->showavt == 0){
		if($db->query("UPDATE `".OneLook_PREFIX."users` SET `showavt` = '1' WHERE `userid` = ?i", $u->userid))
			$_SESSION['success'] = 'Avatar showed successfully !';
	} else
		if($db->query("UPDATE `".OneLook_PREFIX."users` SET `showavt` = '0' WHERE `userid` = ?i", $u->userid))
			$_SESSION['success'] = 'Avatar hidden successfully !';

	header("Location: ". $set_class->url."/profile.php?u=$u->userid");
	exit;
} else if(($act == 'del') && $the_user->adminUser() && ($the_user->data->userid != $u->userid)) {

	if($_POST) {
		$db->query("DELETE FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $u->userid);
		$db->query("DELETE FROM `".OneLook_PREFIX."privacy` WHERE `userid` = ?i", $u->userid);

		$page->success = "You have deleted the user ".$html_options->html($u->username);

	} else {
		$show_content = "
			<form class='well form-horizontal' action='?act=del&id=$u->userid' method='post'>
			<fieldset>


			<legend>Delete ".$html_options->html($u->username)."</legend>
			".$html_options->error("You are about to DELETE ".$the_user->showName($u->userid).". Are you sure ?",1)."



			<div class='control-group'>
			  <label class='control-label' for='submit'></label>
			  <div class='controls'>
			    <button id='submit' name='submit' class='btn btn-primary'>Yes DELETE</button> <a href='$set_class->url/profile.php?u=$u->userid' class='btn'>Cancel</a>
			  </div>
			</div>

			</fieldset>
			</form>";
	}

} else {
	header("Location: ". $set_class->url."/profile.php?u=$u->userid");
	exit;
}



include 'header.php';


echo "
<div style='margin-top: 120px; margin-bottom: 120px;' class='container'>
<h3>Moderator Panel</h3>
<hr>
";

if(isset($page->error))
  $html_options->error($page->error);
else if(isset($page->success))
  $html_options->success($page->success);

echo "
$show_content
<br/> <a href='$set_class->url/profile.php?u=$u->userid' class='btn btn-primary'>Back to profile</a>
</div>";



include 'footer.php';
