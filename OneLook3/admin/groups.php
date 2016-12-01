<?php

include "../settings/init.php";

if(!$the_user->adminUser()) {
    header("Location: $set_class->url");
    exit;
}



$page->title = "Manage Groups";

$nav->setActive("adminspanel");

$groups_type = array("Guest","Member", "Paid Membership", "Administrator");

$groups_columns = array("groupid", "name", "type", "priority");

$data = $db->getAll("SELECT * FROM `".OneLook_PREFIX."groups` ORDER BY `type`,`priority`");

$data_columns = get_object_vars($data[0]);


$user_action = isset($_GET['act']) ? $_GET['act'] : NULL;


if($_POST) {

    if( ($user_action == "add") || ($user_action == 'edit') ) {


      if($user_action == 'edit')
        $sql = "UPDATE `".OneLook_PREFIX."groups` SET ";




      if(($user_action == 'edit') && ($user_groups = $db->getRow("SELECT * FROM `".OneLook_PREFIX."groups` WHERE `groupid` = ?i", $_GET['id'])))


      $name = $_POST['name'];

      if(isset($_POST['type']))
        $type = $_POST['type'];

      $prioritys = $_POST['priority'];

      $sql .= $db->parse(" `name` = ?s, `priority` = ?s, ", $name, $prioritys);




      foreach ($_POST as $keys => $value)
        if(!in_array($keys, $groups_columns) && in_array($keys, array_keys($data_columns)))
          $sql .= $db->parse(" ?n = ?s,", $keys, $value);


      if($user_action == 'edit')
        $sql = trim($sql, ",").$db->parse(" WHERE `groupid` = ?i", $user_groups->groupid);
      else
        $sql = trim($sql, ",");


      if($db->query("?p", $sql))
        if($user_action == 'edit')
          $page->success = "Group settings have successfully been saved!";
      else
        $page->error = "An error has occurred, Please try again!";



}

}


include "../header.php";

?>
<div style='margin-top: 150px; margin-bottom: 150px;' class="container-fluid">
<div class="row-fluid">
 <div class="span3">
   <div style='margin-top: 100px; margin-bottom: 100px;' class="well sidebar-nav sidebar-nav-fixed">
    <ul class="nav nav-list">
      <li class="nav-header">ADMIN OPTIONS</li>
      <li><a href='index.php'>General Settings</a></li>
      <li class='active'><a href='groups.php'>Manage Groups</a></li>
    </ul>
   </div>
 </div>
 <div class="span9">
<?php


if(isset($page->error))
  $html_options->fError($page->error);
else if(isset($page->success))
  $html_options->success($page->success);





if(($user_action == 'edit')) {

  $edit = 0;


  if(($user_action == 'edit') && ($user_groups = $db->getRow("SELECT * FROM `".OneLook_PREFIX."groups` WHERE `groupid` = ?i", $_GET['id']))) {
    $edit = 1;
  }



  $types_allowed = '';
  $options_allowed = '';


  foreach ($groups_type as $a => $o)
    if($a != 0)
      $types_allowed .= "<option value='$a' ".($edit && ($user_groups->type == $a) ? "selected='1'" : "").">".$html_options->html($o)."</option>";




  foreach ($data_columns as $a => $o) {
    $safe_name = $html_options->html($a);

    if(!in_array($a, $groups_columns))
      if((strpos($a, "can") !== FALSE)) {
        $options_allowed .= "
          <div class='control-group'>
            <label class='control-label' for='$safe_name'>".$html_options->prettyPrint(str_ireplace("can", "can ", $safe_name))."</label>
            <div class='controls'>
              <select id='$safe_name' name='$safe_name' class='input-xlarge'>
                <option value='0' ".($edit && ($user_groups->$a == 0) ? "selected='1'" : "").">No</option>
                <option value='1' ".($edit && ($user_groups->$a == 1) ? "selected='1'" : "").">Yes</option>
              </select>
            </div>
          </div>";

      } else {

        $options_allowed .= "
          <div class='control-group'>
            <label class='control-label' for='$safe_name'>".$html_options->prettyPrint($safe_name)."</label>
            <div class='controls'>
              <input type='text' id='$safe_name' name='$safe_name' ".($edit ? "value='".$html_options->html($user_groups->$a)."'" : "")." class='input-xlarge'>
            </div>
          </div>
        ";
      }
  }



echo "
  <form class='form-horizontal' action='#' method='post'>
    <fieldset>

    <legend>".($edit ? "Edit" : "Add")." Group</legend>

    <div class='control-group'>
      <label class='control-label' for='name'>Name</label>
      <div class='controls'>
        <input id='name' name='name' type='text' ".($edit ? "value='".$html_options->html($user_groups->name)."'" : "")." class='input-xlarge'>
      </div>
    </div>";


if(!$edit)
  echo "
      <div class='control-group'>
        <label class='control-label' for='type'>Type</label>
        <div class='controls'>
          <select id='type' name='type' class='input-xlarge'>
            $types_allowed
          </select>
        </div>
      </div>";

echo "
    <div class='control-group'>
      <label class='control-label' for='priority'>Priority</label>
      <div class='controls'>
        <input id='priority' name='priority' type='text' class='input-small' ".($edit ? "value='".$html_options->html($user_groups->priority)."'" : "value='1'").">
        <p class='help-block'>the bigger the number the higher the priority it has compared with same type</p>
      </div>
    </div>


    $options_allowed

    <div class='control-group'>
      <div class='controls'>
        <input type='submit' value='Save Group' class='btn btn-success'>  <a href='?' class='btn'>Cancel</a>
      </div>
    </div>
    </fieldset>
  </form>";




} else {

    echo "<h3>Manage Groups</h3>
      <hr/>";



      echo "<table class='table table-striped'>
        <tr> <th>Name</th> <th>Type</th> <th>Options</th></tr>";
      foreach ($data as $b) {

        echo "
        <tr>
          <td>".$html_options->html($b->name)."</td>
          <td>".$groups_type[$b->type]."</td>
          <td><a href='?act=edit&id=$b->groupid' class='btn btn-primary'>Edit</a></td>
        </tr>";
      }


      echo "</table>";

}

?>

 </div>
</div>

</div>



<?php
include '../footer.php';
?>
