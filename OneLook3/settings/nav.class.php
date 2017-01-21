<?php

class nav {

  var $active = '';

  function GenerateNavbar() {
      global $set_class, $the_user;
      $var = array();

      if(isset($the_user->group->type) == 3)
      $var[] = array("item",
                      array("href" => $set_class->url."/users_list.php",
                            "name" => "User List",
                            "class" => $this->isActive("userslist")),
                      "id" => "userslist");

      if(isset($the_user->group->type) == 3)
      $var[] = array("item",
                      array("href" => $set_class->url."/admin",
                            "name" => "Admin Panel",
                            "class" => $this->isActive("adminpanel")),
                      "id" => "adminpanel");


      $var[] = array("dropdown",
                      array(  array("href" => $set_class->url."/profile.php?u=".$the_user->data->userid,
                                       "name" => "<i class=\"icon-user\"></i> My Profile",
                                       "class" => 0),
                              array("href" => $set_class->url."/user.php",
                                       "name" => "<i class=\"icon-cog\"></i> Account settings",
                                       "class" => 0),

                              array("href" => $set_class->url."/logout.php",
                                         "name" => "LogOut",
                                         "class" => 0),
                          ),
                      "class" => 0,
                      "style" => 0,
                      "name" => $the_user->filter->username,
                      "id" => "user");





      return $var;
  }

  function setActive($id) {
    $this->active = $id;
  }

  function isActive($id) {
    if($id == $this->active)
      return "active";
    return 0;
  }

}
