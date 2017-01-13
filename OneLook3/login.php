<?php

include "settings/init.php";

if($the_user->islg()) {
  header("Location: $set_class->url");
  exit;
}


$page->title = "Login to ". $set_class->site_name;


if($_POST && isset($_SESSION['token']) && ($_SESSION['token'] == $_POST['token'])) {

    if(isset($_GET['forget'])) {

        $email = $_POST['email'];

        if(!$html_options->isValidMail($email))
            $page->error = "Email address is not valid.";

        if(!isset($page->error) && !($usr = $db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `email` = ?s", $email)))
            $page->error = "This email address doesn't exist in our database !";


        if(!isset($page->error)) {
            $keys = sha1(rand());

            $db->query("UPDATE `".OneLook_PREFIX."users` SET `key` = ?s WHERE `userid` = ?i", $keys, $usr->userid);

            $link = $set_class->url."/login.php?key=".$keys."&userid=".$usr->userid;

            $from ="From: not.reply@".$set_class->url;
            $sub = "New Password !";
            $msg = "Hello,<br> You requested for a new password. To confirm <a href='$link'>click here</a>.<br>If you can't access copy this to your browser<br/>$link  <br><br>Regards<br><small>Note: Dont reply to this email. If you got this email by mistake then ignore this email.</small>";
            if($html_options->sendMail($email, $sub, $msg, $from))
                $page->success = "An email with instructions was sent !";
        }

    } else if(isset($_GET['key'])) {
        if($_GET['key'] == '0') {
            header("Location: $set_class->url");
            exit;
        }
        if($usr = $db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `key` = ?s", $_GET['key'])) {
            if($db->query("UPDATE `".OneLook_PREFIX."users` SET `password` = ?s WHERE `userid` = ?i", sha1($_POST['password']), $usr->userid)) {
                $db->query("UPDATE `".OneLook_PREFIX."users` SET `key` = '0' WHERE `userid` = ?i", $usr->userid);
                $page->success = "Password was updated !";
            }

        }

    } else {
        $name = $_POST['name'];
        $password = $_POST['password'];


        if(!($usr = $db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `username` = ?s AND `password` = ?s", $name, sha1($password))))
            $page->error = "Username or password are wrong !";
        else {
            if($_POST['r'] == 1){
                $path_info = parse_url($set_class->url);
                setcookie("user", $name, time() + 3600 * 24 * 30, $path_info['path']);
                setcookie("pass", sha1($password), time() + 3600 * 24 * 30, $path_info['path']);
            }
            $_SESSION['user'] = $usr->userid;
            header("Location: $set_class->url");
            exit;
        }
    }
} else if($_POST)
    $page->error = "Invalid request !";


include 'header.php';


$_SESSION['token'] = sha1(rand());

  echo "<div class='container'>
  <div class='row'>
    <div class='span3 hidden-phone'></div>
      <div class='span6' id='form-login'>";


if(isset($page->error))
  $html_options->error($page->error);
else if(isset($page->success))
  $html_options->success($page->success);


if(isset($_GET['forget'])) {

    echo "<form class='form-horizontal well' action='#' method='post'>
        <fieldset>
            <legend>Recover</legend>
            <div class='control-group'>
              <div class='control-label'>
                <label>Email</label>
              </div>
              <div class='controls'>
                <input type='text' placeholder='john.doe@domain.com' name='email' class='input-large'>
              </div>
            </div>

            <input type='hidden' name='token' value='".$_SESSION['token']."'>

            <div class='control-group'>
              <div class='controls'>
              <button type='submit' id='submit' class='btn btn-primary'>Recover</button>
              </div>
            </div>
          </fieldset>";

} else if(isset($_GET['key']) && !isset($page->success)) {
    if($_GET['key'] == '0') {
        echo "<div class=\"alert alert-error\">Error !</div>";
        exit;
    }
    if($usr = $db->getRow("SELECT `userid` FROM `".OneLook_PREFIX."users` WHERE `key` = ?s AND `userid` = ?i", $_GET['key'], $_GET['userid'])) {
    echo "<form class='form-horizontal well' action='#' method='post'>
        <fieldset>
            <legend>Reset</legend>
            <div class='control-group'>
              <div class='control-label'>
                <label>New password</label>
              </div>
              <div class='controls'>
                <input type='password' name='password' class='input-large'>
              </div>
            </div>

            <input type='hidden' name='token' value='".$_SESSION['token']."'>

            <div class='control-group'>
              <div class='controls'>
              <button type='submit' id='submit' class='btn btn-primary'>Save</button>
              </div>
            </div>
          </fieldset>";


    } else {
        echo "<div class=\"alert alert-error\">Error bad key !</div>";
    }

}else {
    echo "<form class='form-horizontal well' action='?' method='post'>
        <fieldset>
            <legend>Login Form</legend>
            <div class='control-group'>
              <div class='control-label'>
                <label>Username</label>
              </div>
              <div class='controls'>
                <input type='text' placeholder='john.doe' name='name' class='input-large'>
              </div>
            </div>

            <div class='control-group'>
              <div class='control-label'>
                <label>Password</label>
              </div>
              <div class='controls'>
                <input type='password' placeholder='type your password' name='password' class='input-large'>

                <!-- Help-block example -->
                <!-- <span class='help-block'>Example block-level help text here.</span> -->
              </div>


            </div>
            <div class='control-group'>
              <div class='control-label'>
                <label for='r'>Remember Me</label>
              </div>
              <div class='controls'>
                <input type='checkbox' name='r' value='1' id='r'>
              </div>
            </div>

            <input type='hidden' name='token' value='".$_SESSION['token']."'>

            <div class='control-group'>
              <div class='controls'>

              <button type='submit' id='submit' class='btn btn-primary'>Sign in</button>

              <a href='?forget=1' class='btn btn-secondary'>Forgot Password</a>

              </div>
            </div>
          </fieldset>";
}
echo "  </form>
      </div>
</div>";


include "footer.php";
