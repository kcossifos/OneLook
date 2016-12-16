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
<form id="filtering" action="menuList.php" method="post">
  <input type="hidden" name="searchFood" value="' . $search_query . '">
  <input type="hidden" name="locationParam" value="' . $search_query_location . '">
  <input type="submit" name="sort" value="low"> Cost - low to high<br>
  <input type="submit" name="sort" value="high"> Cost - high to low<br>
  <input type="submit" name="sort" value="rating"> Rating
</form>
    <h3 id="rate">Rating</h3>
    <form class="rating" action="menuList.php" method="post">
    <input type="hidden" name="searchFood" value="' . $search_query . '">
    <input type="hidden" name="locationParam" value="' . $search_query_location . '">
    <input type="submit" name="rating" value="5" />
    <label class = "full" for="star5"></label>
    <input type="submit" id="star4half" name="rating" value=4.5 />
    <label class="half" for="star4half"></label>
    <input type="submit" id="star4" name="rating" value=4 />
    <label class = "full" for="star4"></label>
    <input type="submit" id="star3half" name="rating" value=3.5 />
    <label class="half" for="star3half"></label>
    <input type="submit" id="star3" name="rating" value=3 />
    <label class = "full" for="star3"></label>
    <input type="submit" id="star2half" name="rating" value=2.5 />
    <label class="half" for="star2half"></label>
    <input type="submit" id="star2" name="rating" value=2 />
    <label class = "full" for="star2"></label>
    <input type="submit" id="star1half" name="rating" value=1.5 />
    <label class="half" for="star1half"></label>
    <input type="submit" id="star1" name="rating" value=1 />
    <label class = "full" for="star1"></label>
    <input type="submit" id="starhalf" name="rating" value=0.5 />
    <label class="half" for="starhalf"></label>
</form>
    <h3 id="price">Price</h3>
    <form class="pricing" action="menuList.php" method="post">
    <input type="hidden" name="searchFood" value="' . $search_query . '">
    <input type="hidden" name="locationParam" value="' . $search_query_location . '">
    <input type="submit" name="pricing" value=4 />
    <label class = "full" for="price5" ></label>
    <input type="submit" id="price4" name="pricing" value=3 />
    <label class = "full" for="price4"></label>
    <input type="submit" id="price3" name="pricing" value=2 />
    <label class = "full" for="price3"></label>
    <input type="submit" id="price2" name="pricing" value=1 />
    <label class = "full" for="price2"></label>
    <input type="submit" id="price1" name="pricing" value=0 />
    <label class = "full" for="price1"></label>
</form>
    </aside>';

  $rating = $_POST['rating'];
  $price_level = $_POST['pricing'];
  $sorting = $_POST['sort'];

  $rating_array = array();
  $low_high_array = array();

  for($i = 0; $i < 10; $i++){
    array_push($rating_array, $restaurant_array['results'][$i]);
  }

  for($i = 0; $i < 10; $i++){
    array_push($low_high_array, $restaurant_array['results'][$i]);
  }

  function cmp($a, $b)
  {
      return strcmp($a["rating"], $b["rating"]);
  }

  function coolcmp($a, $b)
    {
        return strcmp($a["price_level"], $b["price_level"]);
    }

  usort($rating_array, "cmp");

  usort($low_high_array, "coolcmp");


  if($rating){
    for($i = 0; $i < 10; $i++){
      if($restaurant_array['results'][$i]['rating'] == $rating){
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
          echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . ' \ 5</p> </aside>
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
          }else {
          }
    }
  }else if($price_level) {
    for($i = 0; $i < 10; $i++){
      if($restaurant_array['results'][$i]['price_level'] == $price_level){
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
          echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . ' \ 5</p> </aside>
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
          }else {
          }
    }
  }else if($sorting == 'rating') {
    for($i = 0; $i < 10; $i++){
      if($rating_array[$i]['name'] == ''){
        echo '';
      }
        echo '<section id="view">
          <article id="rest">
              <img src="images/olive.jpg"/>
          <aside>  <h3>' . $rating_array[$i]['name'] . '</h3><br>';
        if($rating_array[$i]['formatted_address']){
          echo '<h5>Address:</h5><p>' . $rating_array[$i]['formatted_address'] . '</p>';
        }else {
          echo '';
        }
        if($rating_array[$i]['rating']){
          echo '<p>Rating: ' . $rating_array[$i]['rating'] . ' \ 5</p> </aside>
                <hr>
               </article><section id="options">
                <ul id="left">
                <li>Hours: </li>
                </ul>
                <ul id="right">';
        }else {
          echo ' ';
        }
        if($rating_array[$i]['price_level']){
          echo '<p>Price Level: ' . $rating_array[$i]['price_level'] . '</p>';
        }else {
          echo '';
        }
        if($rating_array[$i]['opening_hours']['open_now'] === false){
          echo ' <li>Closed now</li></ul>
              </section>
              <hr style="margin-top: 25%;">';
        }else if($rating_array[$i]['opening_hours']['open_now'] === true){
          echo '<li>Open now</li></ul>
              </section>
              <hr style="margin-top: 25%;">';
        }
        if($rating_array[$i]['name'] == "Giovanni's Italian Restaurant & Pizzeria"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="italian" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($rating_array[$i]['name'] == "Cocina 214"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="mexican" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($rating_array[$i]['name'] == "Sakari Sushi"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="japanese" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }
      echo '</section>
            </section>';
          }
    }else if($sorting == 'low') {
      for($i = 0; $i < 10; $i++){
          if($low_high_array[$i]['formatted_address']){
            echo '<h5>Address:</h5><p>' . $low_high_array[$i]['formatted_address'] . '</p>';
          }else {
            echo '';
          }
          if($low_high_array[$i]['rating']){
            echo '<p>Rating: ' . $low_high_array[$i]['rating'] . ' \ 5</p> </aside>
                  <hr>
                 </article><section id="options">
                  <ul id="left">
                  <li>Hours: </li>
                  </ul>
                  <ul id="right">';
          }else {
            echo ' ';
          }
          if($low_high_array[$i]['price_level']){
            echo '<p>Price Level: ' . $low_high_array[$i]['price_level'] . '</p>';
          }else {
            echo '';
          }
          if($low_high_array[$i]['opening_hours']['open_now'] === false){
            echo ' <li>Closed now</li></ul>
                </section>
                <hr style="margin-top: 25%;">';
          }else if($low_high_array[$i]['opening_hours']['open_now'] === true){
            echo '<li>Open now</li></ul>
                </section>
                <hr style="margin-top: 25%;">';
          }
          if($low_high_array[$i]['name'] == "Giovanni's Italian Restaurant & Pizzeria"){
            echo '  <form action="menuPage.php" method="post">
                      <input type="hidden" name="foodType" value="italian" />
                      <input type="submit" value="Menu" class="button" />
                    </form>';
          }else if($low_high_array[$i]['name'] == "Cocina 214"){
            echo '  <form action="menuPage.php" method="post">
                      <input type="hidden" name="foodType" value="mexican" />
                      <input type="submit" value="Menu" class="button" />
                    </form>';
          }else if($low_high_array[$i]['name'] == "Sakari Sushi"){
            echo '  <form action="menuPage.php" method="post">
                      <input type="hidden" name="foodType" value="japanese" />
                      <input type="submit" value="Menu" class="button" />
                    </form>';
          }
        echo '</section>
              </section>';
            }
      }else {
    for($i = 0; $i < 10; $i++){
      if($restaurant_array['results'][$i]['name'] === 'Spoleto- My Italian Kitchen (Winter Park)'){
        echo '';
      }
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
          echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . ' \ 5</p> </aside>
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
                    <input type="hidden" name="foodType" value="Giovanni" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Pannullo's Italian Restaurant"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Pannullo" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Rocco's Italian Grille"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Rocco" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Armando's"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Armando" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Brio Tuscan Grille"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Brio" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Al Bacio Florida"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="AlBacio" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Carlucci's of Winter Park"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Carlucci" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Tamarind Indian Cuisine"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Tamarind" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Mynt Fine Indian Cuisine"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="MyntFine" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Moghul Indian Cuisine"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Moghul" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Cocina214"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Cocina" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Pepe's Cantina"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="PepeCantina" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "El Potro Mexican Restaurant"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="ElPotro" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Park Station"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="ParkStation" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "BurgerFi"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="BurgerFi" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "The Ravenous Pig"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="Ravenous" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Jumbo Chinese Restaurant"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="JumboChinese" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "China Garden Restaurant"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="ChinaGarden" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }
      echo '</section>
            </section>';
          }
    }


  include 'footer.php';
  // var_dump($restaurant_array);
  // var_dump($rating);
  // var_dump($search_query);
  // var_dump($search_query_location);
  // var_dump($search_query_location);
 ?>
