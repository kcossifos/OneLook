<?php

include "settings/init.php";

if($the_user->islg()) {
  header("Location: $set_class->url");
  exit;
}


$page->title = "Register to ". $set_class->site_name;

if($_POST && isset($_SESSION['token']) && ($_SESSION['token'] == $_POST['token']) && $set_class->register) {

  $name = $_POST['name'];
  $display_name = $_POST['display_name'];
  $email = $_POST['email'];
  $password = $_POST['password'];


  if(!isset($name[3]) || isset($name[30]))
    $page->error = "Username is too short or too long!";

  if(!$html_options->validUsername($name))
    $page->error = "Invalid username!";

  if(!isset($display_name[3]) || isset($display_name[50]))
    $page->error = "First name too short or too long!";

  if(!isset($password[3]) || isset($password[30]))
    $page->error = "Password is too short or too long!";

  if(!$html_options->isValidMail($email))
    $page->error = "Email address is not valid.";

  if($db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `username` = ?s", $name))
    $page->error = "Username is already in use!";

  if($db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `email` = ?s", $email))
    $page->error = "Email is already in use!";


  if(!isset($page->error)){
    $user_data = array(
      "username" => $name,
      "display_name" => $display_name,
      "password" => sha1($password),
      "email" => $email,
      "lastactive" => time(),
      "regtime" => time(),
      "validated" => 1
      );

    if($set_class->email_validation == 1) {

      $user_data["validated"] = $keys = sha1(rand());

      $link = $set_class->url."/validate.php?key=".$keys."&username=".urlencode($name);


      $url_info = parse_url($set_class->url);
      $from ="From: not.reply@".$url_info['host'];
      $sub = "Activate your account !";
      $msg = "Hello ".$html_options->html($name).",<br> Thank you for choosing to be a member of out community.<br/><br/> To confirm your account <a href='$link'>click here</a>.<br>If you can't access copy this to your browser<br/>$link  <br><br>Regards<br><small>Note: Dont reply to this email. If you got this email by mistake then ignore this email.</small>";
      if(!$html_options->sendMail($email, $sub, $msg, $from))
          $user_data["validated"] = 1;
    }

    if(($db->query("INSERT INTO `".OneLook_PREFIX."users` SET ?u", $user_data)) && ($id = $db->insertId()) && $db->query("INSERT INTO `".OneLook_PREFIX."privacy` SET `userid` = ?i", $id)) {
      $page->success = 1;
      $_SESSION['user'] = $id;
      $the_user = new User($db);
    } else
      $page->error = "There was an error. Please try again!";

  }


}


include 'header.php';


if(!$set_class->register)
  $html_options->fError("We are sorry registration is blocked momentarily please try again leater!");


$_SESSION['token'] = sha1(rand());

$extra_content = '';
if(isset($page->error))
  $extra_content = $html_options->error($page->error);

if(isset($page->success)) {



} else {



  echo "
  <div style='margin-top: 100px; margin-bottom:100px;' class='container'>
    <div class='span3 hidden-phone'></div>
      <div class='span6'>

      ".$extra_content."

      <form action='#' id='contact-form' class='form-horizontal well' method='post'>
        <fieldset>
          <legend>Register Form </legend>

          <div class='control-group'>
            <label class='control-label' for='name'>Username</label>
            <div class='controls'>
              <input type='text' class='input-xlarge' name='name' id='name'>
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='display_name'>First Name</label>
            <div class='controls'>
              <input type='text' class='input-xlarge' name='display_name' id='display_name'>
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='email'>Email Address</label>
            <div class='controls'>
              <input type='text' class='input-xlarge' name='email' id='email'>
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='password'>Password</label>
            <div class='controls'>
              <input type='password' class='input-xlarge' name='password' id='password'>
            </div>
          </div>
          <input type='hidden' name='token' value='".$_SESSION['token']."'>
          <div class='form-actions'>
          <button type='submit' class='btn btn-primary btn-large'>Register</button>
          </div>
        </fieldset>
      </form>
    </div>
  </div>";
}

include "footer.php";
