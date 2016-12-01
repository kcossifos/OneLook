<?php

include 'settings/init.php';



$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');


include 'header.php';


echo "
<section>
      <h1>Know your total before you GO!</h1>
      <input id='location' placeholder='&#xf041;      Please type your location...'/>
    <input id='menu' placeholder='&#xf002;     Search for a restuarant menu or cuisine...'/>
      <img style='margin-top: 20px;' data-u='image' src='images/pizza.png' />
</section>
<section style='padding-bottom: 2%; padding-top: 1%; background-color: #F3F3F3;'>
    <h2>How to OneLook it!</h2>
    <ul>
        <li><img src='images/account.png'/></li>
        <li id='arrow'><img src='images/right-arrow.png'/></li>
        <li><img src='images/search.png'/></li>
        <li id='arrow'><img src='images/right-arrow.png'/></li>
        <li><img src='images/cart.png'/></li>
        <li id='arrow'><img src='images/right-arrow.png'/></li>
        <li><img src='images/total.png'/></li>
    </ul>
    <p id='one'>Create an Account</p>
    <p id='two'>Search for a Restuarant Menu </p>
    <p id='three'>Add Menu Items to Your Cart</p>
    <p id='four'>Get your Total Bill</p>
</section>
<section id='rated'>
    <h2>Top Rated Menu's</h2>
    <article class='overlay-container' id='artone'>
        <img src='images/olive.jpg'/>
    <aside class='overlay' >
        <h3>Olive Garden</h3>
        <p>Keeping with modern-day Italian traditions, we're expanding our menu to provide more choices, variety and better-for-you options.
        <br>Our food is prepared with fresh ingredients presented simply with a focus on flavor and quality that is uniquely Italian.</p>
    </aside>
    </article>
    <article  class='overlay-container' id='arttwo'>
        <img src='images/redrobins.jpg'/>
    <aside class='overlay'>
        <h3>Red Robin's</h3>
        <p>Sure, we’re burgers. But Red Robin is much more. Like the sesame seeds on our buns, we’re pleasant, generous and just a little nutty.</p>
    </aside>
    </article>
    <article class='overlay-container' id='artthree'>
      <img src='images/applebees.jpg'/>
    <aside class='overlay'>
        <h3>Applebee's</h3>
        <p>Applebee’s started with the same philosophy we follow today – focused on serving good food to good people. Today, what was once a popular neighborhood restaurant has grown to become a popular restaurant in neighborhoods all across North America – with almost 2,000 locations and counting.</p>
    </aside>
    </article>
    <article class='overlay-container' id='artfour'>
         <img src='images/chilis.jpg'/>
          <aside class='overlay'>
        <h3>Chili's</h3>
        <p>Burgers. Ribs. Fajitas. It all started with a dream: to create a place you’d want to go and hang out with good friends over a burger and a beer. For people who craved connection with family and friends, we were the only ones to offer a genuine Southwest spirit filled with positive energy.</p>
    </aside>
    </article>
</section>";

include 'footer.php';
