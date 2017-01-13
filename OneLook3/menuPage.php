<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'header.php';

require_once("settings/db.php");

  $giovanniResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '1'");
  $pannulloResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '2'");
  $roccoResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '3'");
  $armandoResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '4'");
  $brioResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '5'");
  $albacioResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '6'");
  $carlucciResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '7'");
  $tamarindResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '8'");
  $myntfineResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '9'");
  $moghulResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '10'");
  $cocinaResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '11'");
  $pepecantinaResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '12'");
  $elpotroResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '13'");
  $parkstationResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '14'");
  $burgerfiResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '15'");
  $ravenousResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '16'");
  $jumbochineseResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '17'");
  $chinagardenResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '18'");


include 'footer.php';

 ?>
