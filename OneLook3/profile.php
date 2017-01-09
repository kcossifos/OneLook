<?php

include 'settings/init.php';

if(!isset($_GET["u"]) || !($u = $db->getRow("SELECT * FROM `".OneLook_PREFIX."users` WHERE `userid`= ?i", $_GET["u"]))){
	$page->error = "User doesn't exists or it was deleted !";
	$u = new stdClass();
	$u->username = 'Guest';
}

$page->title = "Profile of ". $html_options->html($u->username);


include 'header.php';


if(isset($page->error))
  $html_options->fError($page->error);



$show_actions = '';




if($the_user->group->canban && $the_user->hasPrivilege($u->userid) && ($the_user->data->userid != $u->userid))
	$show_actions .= "<li><a href='$set_class->url/mod.php?act=ban&id=$u->userid'><i class='icon-ban-circle'></i> ".($u->banned ? "Un" : "")."Ban ".$html_options->html($u->username)."</a></li>";

if(($the_user->data->userid == $u->userid) || ($the_user->group->canedit && $the_user->hasPrivilege($u->userid)))
	$show_actions .= "<li><a href='$set_class->url/user.php?id=$u->userid'><i class='icon-pencil'></i> Edit profile</a></li>";


if($the_user->adminUser() && $the_user->data->userid != $u->userid)
	$show_actions .="<li><a href='$set_class->url/mod.php?act=del&id=$u->userid'><i class='icon-trash'></i> Delete ".$html_options->html($u->username)."</li>";


$tooltip = '';

if($the_user->data->userid == $u->userid) {
	$tooltip = " rel='tooltip' title='change avatar'";
}

$extra_details = '';


$privacy  = $db->getRow("SELECT * FROM `".OneLook_PREFIX."privacy` WHERE `userid` = ?i", $u->userid);
$the_user_groups  = $db->getRow("SELECT * FROM `".OneLook_PREFIX."groups` WHERE `groupid` = ?i", $u->groupid);

if($privacy->email == 1 || $the_user->adminUser())
	$extra_details .= "<b>Email:</b> ". $html_options->html($u->email)."<br/>";





echo "<div style='margin-top: 212px; margin-bottom: 212px;' class='container'>
	<h3 class='pull-left'>Profile of ".$html_options->html($u->username)."</h3>";

if($show_actions != '')
echo "<div class='btn-group pull-right'>
  		<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
    		Actions
    		<span class='caret'></span>
  		</a>
  		<ul class='dropdown-menu'>

  			$show_actions

  		</ul>
  	</div>";

echo "
  	<div class='clearfix'></div>
	<hr>
	<div class='row'>
		<div  class='span12 well' style='margin:10px;'>
			<b>Rank:</b> ".$html_options->html($the_user_groups->name)."<br/>
			<b>Last seen:</b> ".$html_options->tsince($u->lastactive)."<br/>
			$extra_details
		</div>

	</div>
	<div class='row payment-container'>
		<h2>Pay For Membership Here</h2>

		<form action='checkout.php' method='post' autocomplete='off'>
			<label for='item'>
			Membership:
				<input type='text' name='product'>
			</label>
			<label for='amount'>
			Price:
				<input type='text' name='price'>
			</label>
			<input type='submit' value='Pay'>
		</form>
		<p>You'll be taken to paypal to complete your payment</p>


	</div>
</div>";


include 'footer.php';
