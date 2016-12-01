<?php

include "../settings/init.php";

if(!$the_user->adminUser()) {
    header("Location: $set_class->url");
    exit;
}


$page->title = "Admin Panel";

$nav->setActive("adminspanel");


if($_POST) {

  $data = $db->getRow("SELECT * FROM `".OneLook_PREFIX."settings` LIMIT 1");
  $data_columns = get_object_vars($data);

  $sql = "UPDATE `".OneLook_PREFIX."settings` SET ";

  foreach ($data_columns as $a => $o)
    if($a != 'userid')
      $sql .= $db->parse(" ?n = ?s,", $a, $_POST[$a]);

  $sql = trim($sql, ",")." LIMIT 1";

  if($db->query(" ?p ", $sql))
    $page->success = "Your setting have been saved!";
  else
    $page->error = "An error has occurred!";

}

$set_class = (object)array_merge((array)$set_class,(array)$db->getRow("SELECT * FROM `".OneLook_PREFIX."settings` LIMIT 1"));

include "../header.php";

?>
<div style='margin-top: 100px; margin-bottom: 100px;' class="container-fluid">
<div class="row-fluid">
 <div class="span3">
   <div style='margin-top: 100px; margin-bottom: 100px;' class="well sidebar-nav sidebar-nav-fixed">
    <ul  class="nav nav-list">
      <li class="nav-header">ADMIN OPTIONS</li>
      <li class='active'><a href='?'>General Settings</a></li>
      <li><a href='groups.php'>Manage Groups</a></li>
    </ul>
   </div>
 </div>
 <div class="span9">
<?php



$data = $db->getRow("SELECT * FROM `".OneLook_PREFIX."settings` LIMIT 1");

$data_columns = get_object_vars($data);



if(isset($page->error))
  $html_options->error($page->error);
else if(isset($page->success))
  $html_options->success($page->success);




echo "
  <form class='form-horizontal' action='#' method='post'>
      <fieldset>

      <legend>General Settings</legend>";


foreach ($data_columns as $keys => $values) {
  $name = $html_options->html($keys);
  $values = $html_options->html($values);

  if(in_array($keys, array("register", "email_validation")))
  echo "
      <div class='control-group'>
        <label class='control-label' for='$name'>".$html_options->prettyPrint($name)."</label>
        <div class='controls'>
          <select id='$name' name='$name' class='input-xlarge'>
            <option value='1' ".($values == 1 ? "selected='1'" : "").">Enabled</option>
            <option value='0' ".($values == 0 ? "selected='1'" : "").">Disabled</option>
          </select>
        </div>
      </div>";
  else if(strpos($values, "\n") !== FALSE)
  echo "
      <div class='control-group'>
        <label class='control-label' for='$name'>".$html_options->prettyPrint($name)."</label>
        <div class='controls'>
          <textarea id='$name' name='$name' class='input-xlarge'>$values</textarea>
        </div>
      </div>";
  else
  echo "
      <div class='control-group'>
        <label class='control-label' for='$name'>".$html_options->prettyPrint($name)."</label>
        <div class='controls'>
          <input id='$name' name='$name' type='text' value='$values' class='input-xlarge'>
        </div>
      </div>";



}


echo "<div class='control-group'>
        <div class='controls'>
          <button class='btn btn-primary'>Save</button>
        </div>
      </div>

      </fieldset>
  </form>";

?>


 </div>
</div>

</div>



<?php
include '../footer.php';
?>
