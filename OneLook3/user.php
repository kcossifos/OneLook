<?php

include "settings/init.php";

if(!$the_user->islg()){
	header("Location: ".$set_class->url);
	exit;
}

if(isset($_GET['id']) && $the_user->group->canedit && $the_user->exists($_GET['id'])) {
	$uid = (int)$_GET['id'];
	$can_edit = 1;
}else{
	$uid = $the_user->data->userid;
	$can_edit = 0;
}
$u = $db->getRow("SELECT * FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $uid);


$page->title = "Edit info of ". $html_options->html($u->username);

if($_POST) {
	if(isset($_GET['password']) && ($the_user->data->userid == $u->userid)) {
		$opass = $_POST['oldpass'];
		$npass = $_POST['newpass'];
		$npass2 = $_POST['newpass2'];
		if($db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i AND `password` = ?s", $u->userid, sha1($opass))) {

			if(!isset($npass[3]) || isset($npass[30]))
				$page->error = "Password too short or too long !";
			else if($npass != $npass2)
				$page->error = "New passwords don't match !";
			else
				if($db->query("UPDATE `".OneLook_PREFIX."users` SET `password` = ?s WHERE `userid` = ?i", sha1($npass), $u->userid))
					$page->success = "Password updated successfully !";

		} else
		  $page->error = 'Invalid password !';

	} else {

      	$email = $_POST['email'];
      	$display_name = $_POST['display_name'];


      	$extra = '';
      	if($can_edit) {
	      	$username = $_POST['username'];
	      	$password = $_POST['password'];
	      	if(isset($_POST['groupid']))
		      	$user_groupsid = $_POST['groupid'];

	      	$extra = $db->parse(", `username` = ?s", $username);

	      	if($the_user->adminUser())
	      		$extra .= $db->parse(", `groupid` = ?i", $user_groupsid);

	      	if(!empty($password))
	      		$extra .= $db->parse(", `password` = ?s", sha1($password));

			if(!isset($username[3]) || isset($username[30]))
			    $page->error = "Username too short or too long !";

			if(!$html_options->validUsername($username))
				$page->error = "Invalid username !";

			if($the_user->adminUser() && !$db->getRow("SELECT `groupid` FROM `".OneLook_PREFIX."groups` WHERE `groupid` = ?i", $user_groupsid))
				$page->error = "The group is invalid !";
		}


	  	if(!$html_options->isValidMail($email))
	    	$page->error = "Email address is not valid.";

	    if(!isset($display_name[3]) || isset($display_name[50]))
		    $page->error = "Display name too short or too long !";

	  	if(!isset($page->error) && $db->query("UPDATE `".OneLook_PREFIX."users` SET `email` = ?s, `display_name` = ?s ?p WHERE `userid` = ?i", $email, $display_name, $extra, $u->userid)) {
	  		$page->success = "Info was saved !";

			$u = $db->getRow("SELECT * FROM `".OneLook_PREFIX."users` WHERE `userid` = ?i", $u->userid);
	  	}
	}
}

include 'header.php';


echo "
<div class=\"container\"><div class='span6'>";


if(isset($page->error))
  $html_options->error($page->error);
else if(isset($page->success))
  $html_options->success($page->success);


if(isset($_GET['password']) && ($the_user->data->userid == $u->userid) ) {

	echo "<form style='margin-top: 100px; margin-bottom: 100px; margin-left: 300px; width: 500px;' class='form-horizontal well' action='#' method='post'>
		        <fieldset>
		            <legend>Change Password</legend>

		            <div class='control-group'>
		              <div class='control-label'>
		                <label>Old Password</label>
		              </div>
		              <div class='controls'>
		                <input type='password' name='oldpass' class='input-large'>
		              </div>
		            </div>
		            <div class='control-group'>
		              <div class='control-label'>
		                <label>New Password</label>
		              </div>
		              <div class='controls'>
		                <input type='password' name='newpass' class='input-large'>
		              </div>
		            </div>
		            <div class='control-group'>
		              <div class='control-label'>
		                <label>New Password Again</label>
		              </div>
		              <div class='controls'>
		                <input type='password' name='newpass2' class='input-large'>
		              </div>
		            </div>

		            <div class='control-group'>
		              <div class='controls'>
		              <button type='submit' id='submit' class='btn btn-primary'>Save</button>
		              </div>
		            </div>
		          </fieldset>
		    </form>
		    <a href='?'>Edit Info</a>";

} else {

	echo "<form  style='margin-top: 100px; margin-bottom: 100px; margin-left: 300px; width: 500px;' class='form-horizontal well' action='#' method='post'>
		        <fieldset>
		            <legend>Edit info of ".$html_options->html($u->username)."</legend>";

if($can_edit) {

	$user_groupss = $db->getAll("SELECT * FROM `".OneLook_PREFIX."groups` ORDER BY `type`,`priority`");

	$show_groups = '';
	foreach($user_groupss as $user_groups)
		if($user_groups->groupid != 1)
			if($user_groups->groupid == $u->groupid)
				$show_groups .= "<option value='$user_groups->groupid' selected='1'>".$user_groups->name."</option>";
			else
				$show_groups .= "<option value='$user_groups->groupid'>".$user_groups->name."</option>";

	echo "
	    <div class='control-group'>
	      <div class='control-label'>
	        <label>Username</label>
	      </div>
	      <div class='controls'>
	        <input type='text' name='username' class='input-large' value='".$html_options->html($u->username)."'>
	      </div>
	    </div>

	    <div class='control-group'>
	      <div class='control-label'>
	        <label>Password</label>
	      </div>
	      <div class='controls'>
	        <input type='text' name='password' class='input-large'><br/>
	        <small>Leave blank if you don't want to change</small>
	      </div>
	    </div>

		<div class='control-group'>
		  <label class='control-label' for='selectbasic'>Group: </label>
		  <div class='controls'>
		    <select id='selectbasic' name='groupid' class='input-xlarge' ".($the_user->adminUser() ? "" : "disabled='disabled'").">
				$show_groups
		    </select>
		  </div>
		</div>
	";


}



echo "
        <div class='control-group'>
          <div class='control-label'>
            <label>Display name</label>
          </div>
          <div class='controls'>
            <input type='text' name='display_name' class='input-large' value='".$html_options->html($u->display_name)."'>
          </div>
        </div>

        <div class='control-group'>
          <div class='control-label'>
            <label>Email</label>
          </div>
          <div class='controls'>
            <input type='text' name='email' class='input-large' value='".$html_options->html($u->email)."'>
          </div>
        </div>
        <div class='control-group'>
          <div class='controls'>
          <button type='submit' id='submit' class='btn btn-primary'>Save</button>
          </div>
        </div>
      </fieldset>
</form>";
if(!$can_edit)
	echo "<a href='?password=1'>Change Password</a>";


}

echo "</div>
	</div><!-- /container -->";
include 'footer.php';
