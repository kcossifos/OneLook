<?php

include 'settings/init.php';
$page->title = 'Welcome to '. $set_class->site_name;
$nav->setActive('home');
include 'header.php';


echo "
<section id='cta'>
      <h1 id='caption'>Know your total before you GO!</h1>
      <form action='menuList.php' method='POST'>
        <select id='location' name='locationParam'>
          <option value='WinterPark'>Winter Park</option>
        </select>
        <input id='menu' name='searchFood' list='foodTypes'>
        <datalist id='foodTypes'>
          <option value='Mexican'>
          <option value='Italian'>
          <option value='Chinese'>
          <option value='American'>
          <option value='Indian'>
        </datalist>
      </form>
      <img src='images/pizza.png' />
</section>
<section id='onelook' style='padding-bottom: 2%; padding-top: 1%; background-color: #F3F3F3;'>
    <h2>How to OneLook it!</h2>
    <ul>
        <li><img class='icon' src='images/account.png'/></li>
        <li class='arrow'><img src='images/right-arrow.png'/></li>
        <li class='darrow'><img src='images/down-arrow.png'/></li>
        <li><img class='icon' src='images/search.png'/></li>
        <li class='arrow'><img src='images/right-arrow.png'/></li>
        <li class='darrow'><img src='images/down-arrow.png'/></li>
        <li><img class='icon' src='images/cart.png'/></li>
        <li class='arrow'><img src='images/right-arrow.png'/></li>
        <li class='darrow'><img src='images/down-arrow.png'/></li>
        <li><img class='icon' src='images/total.png'/></li>
    </ul>
    <p id='one'>Create an Account</p>
    <p id='two'>Search for a Restuarant Menu </p>
    <p id='three'>Add Menu Items to Your Cart</p>
    <p id='four'>Get your Total Bill</p>
</section>
<section id='rated'>
    <h2>Top Rated Menu's</h2>
    <article class='overlay-container' id='artone'>
        <img src='images/albacio.png'/>
    <aside>
    <aside class='overlay'>
  <p style='margin-top: 10px;'><b> Address: </b> 505 N Park Ave, Winter Park, FL 32789, United States</p><br/>
  <p><b>Rating: 4.9 / 5</b></p><br/><br/>
   <a href='albacio.php'><i class='fa fa-cutlery' aria-hidden='true'></i>  View Menu</a>
    </aside>
    </aside>
    </article>
    <article class='overlay-container' id='arttwo'>
        <img src='images/cantina.jpg'/>
    <aside>
    <aside class='overlay'>
    <p style='margin-top: 10px;'><b> Address: </b> 433 W New England Ave a, Winter Park, FL 32789, United States</p><br/>
    <p><b>Rating: 4.4 / 5</b></p><br/><br/>
     <a href='pepes.php'><i class='fa fa-cutlery' aria-hidden='true'></i>  View Menu</a>
    </aside>
    </aside>
    </article>
    <article class='overlay-container' id='artthree'>
        <img src='images/elpotro.jpg'/>
    <aside>
    <aside class='overlay'>
    <p style='margin-top: 10px;'><b> Address: </b> 501 N Orlando Ave # 217, Winter Park, FL 32789, United States</p><br/>
    <p><b>Rating: 4.4 / 5</b></p><br/><br/>
     <a href='elpotro.php'><i class='fa fa-cutlery' aria-hidden='true'></i>  View Menu</a>
    </aside>
    </aside>
    </article>
    <article class='overlay-container' id='artfour'>
        <img src='images/ravenous.jpg'/>
    <aside>
    <aside class='overlay'>
    <p style='margin-top: 10px;'><b> Address: </b> 565 W Fairbanks Ave, Winter Park, FL 32789, United States</p><br/>
    <p><b>Rating: Rating: 4.3 / 5</b></p><br/><br/>
     <a href='ravenous.php'><i class='fa fa-cutlery' aria-hidden='true'></i>  View Menu</a>
    </aside>
    </aside>
    </article>

</section>";

include 'footer.php';
