<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'header.php';
  //Uncomment this line to show errors
  error_reporting(0);

  function restaurant_list($search, $type) {
    $curl = curl_init('https://maps.googleapis.com/maps/api/place/textsearch/json?type=restaurant&query=' . $search . '+in+' . $type . '&key=AIzaSyARJSoveW_upL1XTKnEi801Qd7TnIGXGRI');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }

  $search_query = $_POST['searchFood'];
  $search_query_location = $_POST['locationParam'];

  $exploded = explode(" ", $search_query);
  $imploded = implode("+", $exploded);

  $restaurant_listing = restaurant_list($imploded, $search_query_location);
  $restaurant_array = json_decode($restaurant_listing, true);

  echo '<h1>Restaurants in the Winter Park Area</h1>';

  for($i = 0; $i < 10; $i++){
    echo '<h1>' . $restaurant_array['results'][$i]['name'] . '</h1>';
    if($restaurant_array['results'][$i]['formatted_address']){
      echo '<p>Address: ' . $restaurant_array['results'][$i]['formatted_address'] . '</p>';
    }else {
      echo '';
    }
    if($restaurant_array['results'][$i]['rating']){
      echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . '</p>';
    }else {
      echo '';
    }
    if($restaurant_array['results'][$i]['opening_hours']['open_now'] === false){
      echo '<p>Closed now</p>';
    }else if($restaurant_array['results'][$i]['opening_hours']['open_now'] === true){
      echo '<p>Open now</p>';
    }
    if($restaurant_array['results'][$i]['name'] == "Giovanni's Italian Restaurant & Pizzeria"){
      echo '<button><a href="menuPage.php">View Menu</a></button>';
    }else {
      echo '';
    }
  }

  include 'footer.php';
  // var_dump($search_query);
  // var_dump($search_query_location);
 ?>
