<?php

    if(!isset($_SESSION['token']))
        $_SESSION['token'] = sha1(rand());

echo "
<div class='modal hide' id='loginModal'>
    <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>✕</button>
        <h3>Login</h3>
    </div>
        <div class='modal-body' style='text-align:center;'>
        <div class='row-fluid'>
            <div class='span10 offset1'>
                <div id='modalTab'>
                    <div class='tab-content'>
                        <div class='tab-pane active' id='login'>
                            <form method='post' action='$set_class->url/login.php' name='login_form'>
                                <p><input type='text' class='span12' name='name' placeholder='Username'></p>
                                <p><input type='password' class='span12' name='password' placeholder='Password'></p>
                                <p>
                                	<input class='pull-left' type='checkbox' name='r' value='1' id='rm'>
                                	<label class='pull-left' for='rm'>Remember Me</label>
                                </p>
                                <div class='clearfix'></div>

                                <input type='hidden' name='token' value='".$_SESSION['token']."'>

                                <p><button type='submit' class='btn btn-primary'>Sign in</button>
                                <a href='#forgotpassword' data-toggle='tab'>Forgot Password?</a>
                                </p>
                            </form>
                        </div>
                        <div class='tab-pane fade' id='forgotpassword'>
                            <form method='post' action='$set_class->url/login.php?forget=1' name='forgot_password'>
                                <p>To reset your password, please enter your email below.
                                Password reset instructions will be sent to the email address associated with your account.</p>
                                <input type='text' class='span12' name='email' placeholder='Email'>
                                <p><button type='submit' class='btn btn-primary'>Submit</button>

                                <input type='hidden' name='token' value='".$_SESSION['token']."'>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
";

?>




<div style='background-color: #3E0808; width: 1450px;'class="container">
			<div class="row-fluid">
				<div class="span11">
						<p class="muted pull-right">© 2016 OneLook. All rights reserved</p>
				</div>
			</div>
  </div>
</div>

<script src="//ajax.googleapis.com/ajax/settingss/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $set_class->url;?>/js/jquery-1.9.1.min.js"><\/script>')</script>

<script src="<?php echo $set_class->url;?>/js/bootstrap.min.js"></script>

<script src="<?php echo $set_class->url;?>/js/jquery.validate.min.js"></script>

<script src="<?php echo $set_class->url;?>/js/main.js"></script>

<script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
</body>
</html>
