<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'header.php';

  //Uncomment this line to show errors
  // error_reporting(0);

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

  if($_POST['foodType'] == 'Giovanni'){
      echo '<h1></h1>';
      echo '<table>';
      echo '<tr><th>Name</th><th>Price</th></tr>';
      foreach ($giovanniResult as $row) {
        echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
      }
      echo '</table';
  }else if($_POST['foodType'] == 'Pannullo'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($pannulloResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Rocco'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($roccoResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Armando'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($armandoResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Brio'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($brioResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'AlBacio'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($albacioResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Carlucci'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($carlucciResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Tamarind'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($tamarindResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'MyntFine'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($myntfineResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Moghul'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($moghulResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Cocina'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($cocinaResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'PepeCantina'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($pepecantinaResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'ElPotro'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($elpotroResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'ParkStation'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($parkstationResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'BurgerFi'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($burgerfiResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'Ravenous'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($ravenousResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'JumboChinese'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($jumbochineseResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }else if($_POST['foodType'] == 'ChinaGarden'){
    echo '<h1></h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Price</th></tr>';
    foreach ($chinagardenResult as $row) {
      echo '<tr><td>' .$row['item_name'] . '</td><td>' .$row['item_price'] . '</td>  <button>Add to cart</button></tr>';
    }
    echo '</table';
  }



include 'footer.php';
  // var_dump($total_num_rows);

  // var_dump($search_query);
  // var_dump($search_query_location);
 ?>
