<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'headersearch.php';
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

  echo '<h1>Restaurants in the Winter Park Area</h1>

  <aside id="filter">
    <h2>Filters</h2>
<h3>Sort By</h3>
<form id="filtering" action="">
  <input type="radio" name="sort" value="low"> Cost - low to high<br>
  <input type="radio" name="sort" value="high"> Cost - high to low<br>
  <input type="radio" name="sort" value="rating"> Rating
</form>
    <h3 id="rate">Rating</h3>
    <fieldset class="rating">
    <input type="radio" name="rating" value="5" />
    <label class = "full" for="star5"></label>
    <input type="radio" id="star4half" name="rating" value="4 and a half" />
    <label class="half" for="star4half"></label>
    <input type="radio" id="star4" name="rating" value="4" />
    <label class = "full" for="star4"></label>
    <input type="radio" id="star3half" name="rating" value="3 and a half" />
    <label class="half" for="star3half"></label>
    <input type="radio" id="star3" name="rating" value="3" />
    <label class = "full" for="star3"></label>
    <input type="radio" id="star2half" name="rating" value="2 and a half" />
    <label class="half" for="star2half"></label>
    <input type="radio" id="star2" name="rating" value="2" />
    <label class = "full" for="star2"></label>
    <input type="radio" id="star1half" name="rating" value="1 and a half" />
    <label class="half" for="star1half"></label>
    <input type="radio" id="star1" name="rating" value="1" />
    <label class = "full" for="star1"></label>
    <input type="radio" id="starhalf" name="rating" value="half" />
    <label class="half" for="starhalf"></label>
</fieldset>
    <h3 id="price">Price</h3>
    <fieldset class="pricing">
    <input type="radio" name="pricing" value="5" />
    <label class = "full" for="price5" ></label>
    <input type="radio" id="price4" name="pricing" value="4" />
    <label class = "full" for="price4"></label>
    <input type="radio" id="price3" name="pricing" value="3" />
    <label class = "full" for="price3"></label>
    <input type="radio" id="price2" name="pricing" value="2" />
    <label class = "full" for="price2"></label>
    <input type="radio" id="price1" name="rating" value="1" />
    <label class = "full" for="price1"></label>
</fieldset>
    </aside>';

  for($i = 0; $i < 10; $i++){
    echo '<section id="view">
        <article id="rest">
            <img src="images/olive.jpg"/>
        <aside>  <h3>' . $restaurant_array['results'][$i]['name'] . '</h3><br>';
    if($restaurant_array['results'][$i]['formatted_address']){
      echo '<h5>Address:</h5><p>' . $restaurant_array['results'][$i]['formatted_address'] . '</p>';
    }else {
      echo '';
    }
    if($restaurant_array['results'][$i]['rating']){
      echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . '</p> </aside>
            <hr>
           </article><section id="options">
            <ul id="left">
            <li>Hours: </li>
            </ul>
            <ul id="right">';
    }else {
      echo ' ';
    }
    if($restaurant_array['results'][$i]['price_level']){
      echo '<p>Price Level: ' . $restaurant_array['results'][$i]['price_level'] . '</p>';
    }else {
      echo '';
    }
    if($restaurant_array['results'][$i]['opening_hours']['open_now'] === false){
      echo ' <li>Closed now</li></ul>
          </section>
          <hr style="margin-top: 25%;">';
    }else if($restaurant_array['results'][$i]['opening_hours']['open_now'] === true){
      echo '<li>Open now</li></ul>
          </section>
          <hr style="margin-top: 25%;">';
    }
    if($restaurant_array['results'][$i]['name'] == "Giovanni's Italian Restaurant & Pizzeria"){
      // echo '
      //     <section> <a href="menuPage.php"><i class="fa fa-cutlery" aria-hidden="true"></i>  View Menu</a>';

      echo '  <form action="menuPage.php" method="post">
                <input type="hidden" name="foodType" value="italian" />
                <input type="submit" value="Menu" class="button" />
              </form>';
    }else if($restaurant_array['results'][$i]['name'] == "Cocina 214"){
      echo '  <form action="menuPage.php" method="post">
                <input type="hidden" name="foodType" value="mexican" />
                <input type="submit" value="Menu" class="button" />
              </form>';
    }else if($restaurant_array['results'][$i]['name'] == "Sakari Sushi"){
      echo '  <form action="menuPage.php" method="post">
                <input type="hidden" name="foodType" value="japanese" />
                <input type="submit" value="Menu" class="button" />
              </form>';
    }
    echo '</section>
  </section>';
  }

  include 'footer.php';
  // var_dump($search_query);
  // var_dump($search_query_location);
 ?>
