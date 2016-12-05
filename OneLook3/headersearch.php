<?php

if($page->navbar == array())
    $page->navbar = $nav->GenerateNavbar();

if(!$the_user->islg())
    unset($page->navbar[count($page->navbar)-1]);


?><!DOCTYPE html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $page->title; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <script src="https://use.fontawesome.com/20929198f1.js"></script>
        <link rel="stylesheet" href="<?php echo $set_class->url; ?>/css/bootstrap.css">
        <style>
            body {
                padding-top: 60px;
            }
        </style>
        <link rel="stylesheet" href="<?php echo $set_class->url; ?>/css/bootstrap-responsive.css">
        <link rel="stylesheet" href="<?php echo $set_class->url; ?>/css/stylesheet.css">

    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<?php echo $set_class->url; ?>"><?php echo "<img src='images/ArialLogo.png'/>"; ?></a>
                    <a class="brand" href="<?php echo $set_class->url; ?>"><?php echo "<img src='../images/ArialLogo.png'/>"; ?></a>
                    <section>
                          <form action='menuList.php' method='POST'>
                            <select id='locationtwo' name='locationParam'>
                              <option value='WinterPark'>Winter Park</option>
                            </select>
                            <input placeholder='&#xf002;     Search for a restuarant menu or cuisine...' id='menutwo' name='searchFood' list='foodTypes'>
                            <datalist id='foodTypes'>
                              <option value='Mexican'>
                              <option value='Italian'>
                              <option value='Japanese'>
                              <option value='Chinese'>
                              <option value='American'>
                              <option value='Indian'>
                          </form>
                    </section>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">


<?php

foreach ($page->navbar as $keys => $o) {

    if ($o[0] == 'item') {

        echo "<li".($o[1]['class'] ? " class='".$o[1]['class']."'" : "").">
            <a href='".$o[1]['href']."'>".$o[1]['name']."</a></li>";

    } else if($o[0] == 'dropdown') {

        echo "<li class='dropdown".
            // extra classes
            ($o['class'] ? " ".$o['class'] : "")."'".
            // extra style
            ($o['style'] ? " style='".$o['style']."'" : "").">

            <a href='#' class='dropdown-toggle' data-toggle='dropdown'>".$o['name']." <b class='caret'></b></a>
            <ul class='dropdown-menu'>";
        foreach ($o[1] as $a => $o)
            echo "<li".

                ($o['class'] ? " class='".$o['class']."'" : "").">

                <a href='".$o['href']."'>".$o['name']."</a></li>";
        echo "</ul></li>";

    }

}

echo "</ul>";

if(!$the_user->islg()) {

echo "


<span class='pull-right'>
        <a href='$set_class->url/register.php'  id='sign' class='btn btn-primary'>Sign Up</a>
        <!-- <a href='$set_class->url/login.php' class='btn btn-small'>Login</a> -->
        <a href='#loginModal' data-toggle='modal' id='log' class='btn'>Login</a>
    </span>
    ";
}

echo "

            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>";




if($the_user->data->banned) {

    // we delete the expired banned
    $_unban = $db->getAll("SELECT `userid` FROM `".OneLook_PREFIX."banned` WHERE `until` < ".time());
    if($_unban)
        foreach ($_unban as $_usr) {
            $db->query("DELETE FROM `".OneLook_PREFIX."banned` WHERE `userid` = ?i", $_usr->userid);
            $db->query("UPDATE `".OneLook_PREFIX."users` SET `banned` = '0' WHERE `userid` = ?i", $_usr->userid);
        }


    $_banned = $the_user->getBan();
    if($_banned)
    $html_options->error("You were banned by <a href='$set_class->url/profile.php?u=$_banned->by'>".$the_user->showName($_banned->by)."</a> for `<i>".$html_options->html($_banned->reason)."</i>`.
        Your ban will expire in ".$html_options->tsince($_banned->until, "from now.")."
        ");





}



if($the_user->islg() && $set_class->email_validation && ($the_user->data->validated != 1)) {
    $html_options->fError("Your account is not yet acctivated ! Please check your email !");
}



if(isset($_SESSION['success'])){
    $html_options->success($_SESSION['success']);
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])){
    $html_options->error($_SESSION['error']);
    unset($_SESSION['error']);

}
