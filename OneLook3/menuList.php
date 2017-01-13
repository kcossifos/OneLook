<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'headersearch.php';
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

  echo '

  <section>
        <form action="menuList.php" method="POST">
          <select id="locationthree" name="locationParam">
            <option value="WinterPark">Winter Park</option>
          </select>
          <input placeholder="&#xf002;     Search for a restuarant menu or cuisine..." id="menuthree" name="searchFood" list="foodTypes">
          <datalist id="foodTypes">
            <option value="Mexican">
            <option value="Italian">
            <option value="Chinese">
            <option value="American">
            <option value="Indian">
        </form>
  </section>
  <aside id="filter">
    <h2>Filters</h2>
    <h3 id="sort">Sort By</h3>
    <form id="filtering" action="menuList.php" method="post">
      <input type="hidden" name="searchFood" value="' . $search_query . '">
      <input type="hidden" name="locationParam" value="' . $search_query_location . '">
      <input type="submit" name="sort" value="low"> Cost - low to high<br>
      <input type="submit" name="sort" value="rating"> Rating
    </form>
    <h3 id="rate">Rating</h3>
    <form class="rating" action="menuList.php" method="post">
    <input type="hidden" name="searchFood" value="' . $search_query . '">
    <input type="hidden" name="locationParam" value="' . $search_query_location . '">
    <input type="submit" name="rating" value="5" />
    <label class = "full" for="star5"></label>
    <input type="submit" id="star4" name="rating" value=4 />
    <label class= "full" for="star4"></label>
    <input type="submit" id="star3" name="rating" value=3 />
    <label class = "full" for="star3"></label>
    <input type="submit" id="star2" name="rating" value=2 />
    <label class = "full" for="star2"></label>
    <input type="submit" id="star1" name="rating" value=1 />
    <label class = "full" for="star1"></label>
</form>
    <h3 id="price">Price</h3>
    <form class="pricing" action="menuList.php" method="post">
    <input type="hidden" name="searchFood" value="' . $search_query . '">
    <input type="hidden" name="locationParam" value="' . $search_query_location . '">
    <input type="submit" name="pricing" value=5 />
    <label class = "full" for="price5" ></label>
    <input type="submit" id="price4" name="pricing" value=4 />
    <label class = "full" for="price4"></label>
    <input type="submit" id="price3" name="pricing" value=2 />
    <label class = "full" for="price3"></label>
    <input type="submit" id="price2" name="pricing" value=2 />
    <label class = "full" for="price2"></label>
    <input type="submit" id="price1" name="pricing" value=1 />
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
      if($restaurant_array['results'][$i]['rating'] >= $rating && $restaurant_array['results'][$i]['rating'] <= $rating + 0.9){
      echo '<section id="view">
          <article id="rest">
              <img src="images/resticon.png"/>
          <aside>  <h3>' . $restaurant_array['results'][$i]['name'] . '</h3><br>';
        if($restaurant_array['results'][$i]['formatted_address']){
          echo '<h5>Address:</h5><p>' . $restaurant_array['results'][$i]['formatted_address'] . '</p>';
        }else {
          echo '';
        }
        if($restaurant_array['results'][$i]['rating']){
          echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . ' / 5</p> </aside>
                <hr>
               </article><section id="options">
                <ul id="left">
                 <li>Cuisine:</li>
                 <li>Price:</li>
                  <li>Hours: </li>
                </ul>
                <ul id="right">';
        }else {
          echo ' ';
        }
        if($restaurant_array['results'][$i]['price_level']){
          echo '<li class="italian">Italian</li><li>' . $restaurant_array['results'][$i]['price_level'] . '</li>';
        }else {
          echo '';
        }
        if($restaurant_array['results'][$i]['opening_hours']['open_now'] === false){
          echo ' <li>Closed now</li></ul>
              </section>
              <hr class="length">';
        }else if($restaurant_array['results'][$i]['opening_hours']['open_now'] === true){
          echo '<li>Open now</li></ul>
              </section>
              <hr class="length">';
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
              <img src="images/resticon.png"/>
          <aside>  <h3>' . $restaurant_array['results'][$i]['name'] . '</h3><br>';
        if($restaurant_array['results'][$i]['formatted_address']){
          echo '<h5>Address:</h5><p>' . $restaurant_array['results'][$i]['formatted_address'] . '</p>';
        }else {
          echo '';
        }
        if($restaurant_array['results'][$i]['rating']){
          echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . ' / 5</p> </aside>
                <hr>
               </article><section id="options">
                <ul id="left">
                <li>Cuisine:</li>
                <li>Price:</li>
                <li>Hours: </li>
                </ul>
                <ul id="right">';
        }else {
          echo ' ';
        }
        if($restaurant_array['results'][$i]['price_level']){
          echo '<li class="italian">Italian</li><li>' . $restaurant_array['results'][$i]['price_level'] . '</li>';
        }else {
          echo '';
        }
        if($restaurant_array['results'][$i]['opening_hours']['open_now'] === false){
          echo ' <li>Closed now</li></ul>
              </section>
              <hr class="length">';
        }else if($restaurant_array['results'][$i]['opening_hours']['open_now'] === true){
          echo '<li>Open now</li></ul>
              </section>
              <hr class="length">';
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
              <img src="images/resticon.png"/>
              <aside>  <h3>' . $rating_array[$i]['name'] . '</h3><br>';
        if($rating_array[$i]['formatted_address']){
          echo '<h5>Address:</h5><p>' . $rating_array[$i]['formatted_address'] . '</p>';
        }else {
          echo '';
        }
        if($rating_array[$i]['rating']){
          echo '<p>Rating: ' . $rating_array[$i]['rating'] . ' / 5</p> </aside>
                <hr>
               </article><section id="options">
                <ul id="left">
                <li>Cuisine:</li>
                <li>Price:</li>
                <li>Hours: </li>
                </ul>
                <ul id="right">';
        }else {
          echo ' ';
        }
        if($rating_array[$i]['price_level']){
          echo '<li class="italian">Italian</li><li>' . $rating_array[$i]['price_level'] . '</li>';
        }else {
          echo '';
        }
        if($rating_array[$i]['opening_hours']['open_now'] === false){
          echo ' <li>Closed now</li></ul>
              </section>
              <hr class="length">';
        }else if($rating_array[$i]['opening_hours']['open_now'] === true){
          echo '<li>Open now</li></ul>
              </section>
              <hr class="length">';
        }
        if($rating_array[$i]['name'] == "Giovanni's Italian Restaurant & Pizzeria"){
          echo ' <form action="giovanni.php" method="post">
                    <input type="hidden" name="foodType" value="Giovanni" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($rating_array[$i]['name'] == "Cocina 214"){
          echo '  <form action="menuPage.php" method="post">
                    <input type="hidden" name="foodType" value="mexican" />
                    <input type="submit" value="Menu" class="button" />
                  </form>';
        }
      echo '</section>
            </section>';
          }
    }else if($sorting == 'low') {
      for($i = 0; $i < 10; $i++){
        echo '<section id="view">
              <article id="rest">
              <img src="images/resticon.png"/>
              <aside>  <h3>' . $rating_array[$i]['name'] . '</h3><br>';
          if($low_high_array[$i]['formatted_address']){
            echo '<h5>Address:</h5><p>' . $low_high_array[$i]['formatted_address'] . '</p>';
          }else {
            echo '';
          }
          if($low_high_array[$i]['rating']){
            echo '<p>Rating: ' . $low_high_array[$i]['rating'] . ' / 5</p> </aside>
                  <hr>
                 </article><section id="options">
                  <ul id="left">
                  <li>Cuisine:</li>
                  <li>Price:</li>
                  <li>Hours: </li>
                  </ul>
                  <ul id="right">';
          }else {
            echo ' ';
          }
          if($low_high_array[$i]['price_level']){
            echo '<li class="italian">Italian</li><li>' . $low_high_array[$i]['price_level'] . '</li>';
          }else {
            echo '';
          }
          if($low_high_array[$i]['opening_hours']['open_now'] === false){
            echo ' <li>Closed now</li></ul>
                </section>
                <hr class="length">';
          }else if($low_high_array[$i]['opening_hours']['open_now'] === true){
            echo '<li>Open now</li></ul>
                </section>
                <hr class="length">';
          }
          if($low_high_array[$i]['name'] == "Giovanni's Italian Restaurant & Pizzeria"){
            echo '  <form action="giovanni.php" method="post">
                      <input type="hidden" name="foodType" value="italian" />
                      <input type="submit" value="Menu" class="button" />
                    </form>';
                  }else if($low_high_array[$i]['name'] ==  "Pannullo's Italian Restaurant"){
                    echo '   <form action="pannullos.php" method="post">
                              <input type="hidden" name="foodType" value="Pannullos" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Rocco's Italian Grille"){
                    echo '   <form action="roccos.php" method="post">
                              <input type="hidden" name="foodType" value="Roccos" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                              <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Armando's"){
                    echo '   <form action="armandos.php" method="post">
                              <input type="hidden" name="foodType" value="Armando" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Brio Tuscan Grille"){
                    echo '   <form action="brio.php" method="post">
                              <input type="hidden" name="foodType" value="Brio" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                              <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Al Bacio Florida"){
                    echo '  <form action="albacio.php" method="post">
                              <input type="hidden" name="foodType" value="AlBacio" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Carlucci's of Winter Park"){
                    echo '  <form action="carlucci.php" method="post">
                              <input type="hidden" name="foodType" value="Carlucci" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                              <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Tamarind Indian Cuisine"){
                    echo '  <form action="tamarind.php" method="post">
                              <input type="hidden" name="foodType" value="Tamarind" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                               <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Mynt Fine Indian Cuisine"){
                    echo '  <form action="mynt.php" method="post">
                              <input type="hidden" name="foodType" value="MyntFine" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Moghul Indian Cuisine"){
                    echo '  <form action="moghul.php" method="post">
                              <input type="hidden" name="foodType" value="Moghul" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                            <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Cocina214"){
                    echo '  <form action="cocina.php" method="post">
                              <input type="hidden" name="foodType" value="Cocina" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                              <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($restaurant_array['results'][$i]['name'] == "Pepe's Cantina"){
                    echo '  <form action="pepes.php" method="post">
                              <input type="hidden" name="foodType" value="PepeCantina" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                              <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "El Potro Mexican Restaurant"){
                    echo '  <form action="elpotro.php" method="post">
                              <input type="hidden" name="foodType" value="ElPotro" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Park Station"){
                    echo '  <form action="park.php" method="post">
                              <input type="hidden" name="foodType" value="ParkStation" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "BurgerFi"){
                    echo '  <form action="burgerfi.php" method="post">
                              <input type="hidden" name="foodType" value="BurgerFi" />
                              <i class="fa fa-cutlery" aria-hidden="true"></i>
                              <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "The Ravenous Pig"){
                    echo '  <form action="ravenous.php" method="post">
                              <input type="hidden" name="foodType" value="Ravenous" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                 <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "Jumbo Chinese Restaurant"){
                    echo '  <form action="jumbo.php" method="post">
                              <input type="hidden" name="foodType" value="JumboChinese" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }else if($low_high_array[$i]['name'] ==  "China Garden Restaurant"){
                    echo '  <form action="china.php" method="post">
                              <input type="hidden" name="foodType" value="ChinaGarden" />
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <input id="food" type="submit" value="View Menu" class="button" />
                            </form>';
                  }
                echo '</section>
                      </section>';
            }
      }else {
    for($i = 0; $i < 10; $i++){
      if($restaurant_array['results'][$i]['name'] === "Spoleto- My Italian Kitchen (Winter Park)"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Rome's Flavours"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Olive Garden"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "O'Stromboli Italian Eatery"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Winter Park China Star"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Chung On Chinese Restaurant"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "China Master"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Lucky China"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Asia Kitchen"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "P.F. Chang's"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "New Hong Kong"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "He Sheng Chinese Restaurant"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "310 Park South"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Briarpatch Restaurant"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Luma on Park"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Hamilton's Kitchen"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Dexter's of Winter Park"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Park Plaza Gardens Restaurant"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Johnny's Diner"){
        echo '';
      }else if($restaurant_array['results'][$i]['name'] === "Royal Indian Cuisine"){
        echo '';
      }else{
      echo '<section id="view">
          <article id="rest">
              <img src="images/resticon.png"/>
          <aside>  <h3>' . $restaurant_array['results'][$i]['name'] . '</h3><br>';
        if($restaurant_array['results'][$i]['formatted_address']){
          echo '<h5>Address:</h5><p>' . $restaurant_array['results'][$i]['formatted_address'] . '</p>';
        }else {
          echo '';
        }
        if($restaurant_array['results'][$i]['rating']){
          echo '<p>Rating: ' . $restaurant_array['results'][$i]['rating'] . ' / 5</p> </aside>
                <hr>
               </article><section id="options">
                <ul id="left">
                <li>Cuisine:</li>
                <li>Price:</li>
                <li>Hours: </li>
                </ul>
                <ul id="right">';
        }else {
          echo ' ';
        }
        if($restaurant_array['results'][$i]['price_level']){
          echo '<li class="italian">Italian</li><li>' . $restaurant_array['results'][$i]['price_level'] . '</li>';
        }else {
          echo '';
        }
        if($restaurant_array['results'][$i]['opening_hours']['open_now'] === false){
          echo ' <li>Closed now</li></ul>
              </section>
              <hr class="length">';
        }else if($restaurant_array['results'][$i]['opening_hours']['open_now'] === true){
          echo '<li>Open now</li></ul>
              </section>
              <hr class="length">';
        }
        if($restaurant_array['results'][$i]['name'] === "Giovanni's Italian Restaurant & Pizzeria"){
          echo '  <form action="giovanni.php" method="post">
                    <input type="hidden" name="foodType" value="Giovanni" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] === "Pannullo's Italian Restaurant"){
          echo '   <form action="pannullos.php" method="post">
                    <input type="hidden" name="foodType" value="Pannullos" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] === "Rocco's Italian Grille & Bar"){
          echo '   <form action="roccos.php" method="post">
                    <input type="hidden" name="foodType" value="Roccos" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] === "Armando's"){
          echo '   <form action="armandos.php" method="post">
                    <input type="hidden" name="foodType" value="Armando" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] === "BRIO Tuscan Grille"){
          echo '   <form action="brio.php" method="post">
                    <input type="hidden" name="foodType" value="Brio" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] === "Al Bacio Florida"){
          echo '  <form action="albacio.php" method="post">
                    <input type="hidden" name="foodType" value="AlBacio" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] === "Carlucci's of Winter Park"){
          echo '  <form action="carlucci.php" method="post">
                    <input type="hidden" name="foodType" value="Carlucci" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Tamarind Indian Cuisine"){
          echo '  <form action="tamarind.php" method="post">
                    <input type="hidden" name="foodType" value="Tamarind" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                     <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Mynt Fine Indian Cuisine"){
          echo '  <form action="mynt.php" method="post">
                    <input type="hidden" name="foodType" value="MyntFine" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Moghul Indian Cuisine"){
          echo '  <form action="moghul.php" method="post">
                    <input type="hidden" name="foodType" value="Moghul" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                  <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Cocina214"){
          echo '  <form action="cocina.php" method="post">
                    <input type="hidden" name="foodType" value="Cocina" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Pepe's Cantina"){
          echo '  <form action="pepes.php" method="post">
                    <input type="hidden" name="foodType" value="PepeCantina" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "El Potro Mexican Restaurant"){
          echo '  <form action="elpotro.php" method="post">
                    <input type="hidden" name="foodType" value="ElPotro" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Park Station"){
          echo '  <form action="park.php" method="post">
                    <input type="hidden" name="foodType" value="ParkStation" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "BurgerFi"){
          echo '  <form action="burgerfi.php" method="post">
                    <input type="hidden" name="foodType" value="BurgerFi" />
                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                    <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "The Ravenous Pig"){
          echo '  <form action="ravenous.php" method="post">
                    <input type="hidden" name="foodType" value="Ravenous" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                       <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "Jumbo Chinese Restaurant"){
          echo '  <form action="jumbo.php" method="post">
                    <input type="hidden" name="foodType" value="JumboChinese" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }else if($restaurant_array['results'][$i]['name'] == "China Garden Restaurant"){
          echo '  <form action="china.php" method="post">
                    <input type="hidden" name="foodType" value="ChinaGarden" />
                      <i class="fa fa-cutlery" aria-hidden="true"></i>
                      <input id="food" type="submit" value="View Menu" class="button" />
                  </form>';
        }
      echo '</section>
            </section>';
          }
      }
    }


  include 'footer.php';

  for($i = 0; $i < 10; $i++){
    var_dump($restaurant_array['results'][$i]['name']);
  }

 ?>
