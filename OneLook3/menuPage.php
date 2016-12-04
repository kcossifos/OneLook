<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'header.php';

  //Uncomment this line to show errors
  // error_reporting(0);

  $result = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '1'");
  $total_num_rows = $result->num_rows;

  echo '<h1></h1>';
  echo '<table>';
  echo '<tr><th>Name</th><th>Price</th></tr>';
  foreach ($result as $row) {
    echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
  }
  echo '</table';


include 'footer.php';
  // var_dump($total_num_rows);

  // var_dump($search_query);
  // var_dump($search_query_location);
 ?>
