<?php

include_once("config.php");
include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'headersearch.php';

//current URL of the Page. cart_update.php redirects back to this URL
$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopping Cart</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<script src="https://use.fontawesome.com/20929198f1.js"></script>
</head>
<body>
	<header>
	    <img id="overlayimg" src="images/pannullo-s.jpg"/>
	    <aside>
	            <h3>Pannullo's Italian Restaurant</h3>
	            <br>
	            <h5>Location: </h5>
	            <p>12361 State Road 535, Orlando, FL 32836</p>
	        </aside>
			 <ul>
				<li><i class="fa fa-star" aria-hidden="true"></i></li>
				<li><i class="fa fa-star" aria-hidden="true"></i></li>
				<li><i class="fa fa-star" aria-hidden="true"></i></li>
				<li><i class="fa fa-star" aria-hidden="true"></i></li>
				<li><i class="fa fa-star-o" aria-hidden="true"></i></li>
		</ul>
</header>
<article id= "details">
<p> Pannullo's Italian Restaurant was established in March of 1993 and is owned and operated by your hosts, Michael Schwartz and Richard Pannullo. What began as a vision on Thanksgiving Day 1992, became a reality four months later and thanks to you, our loyal friends, we have thrived. We greatly appreciate your patronage and welcome your comments and suggestions. We are always available to assist you in any aspect of your dining experience.</p>
</article>
<section id='pages'>
<div id="tabs" style="padding: 0;">
	<ul>
		<li><a  href="pannullos.php">Menu</a></li>
		<li><a  href="#tabs-2">Reviews</a></li>
		<li><a  href="#tabs-3">Bill OverView</a></li>
		<li><a  href="pannullos_cart.php">My Cart</a></li>
	</ul>
</div>
		<hr id='endtab'>
</section>


<!-- View Cart Box Start -->
<?php
if(isset($_SESSION["panullos_items"]) && count($_SESSION["panullos_items"])>0)
{
	echo '<div class="shoppingcart">';
	echo '<h3>Your Menu Items</h3>';
	echo '<form method="post" action="pannullos_update.php">';
	echo '<table width="100%"  cellpadding="6" cellspacing="0">';
	echo '<tbody>';

	$total =0;
	$b = 0;
	foreach ($_SESSION["panullos_items"] as $menu_items)
	{
		$item_name = $menu_items["item_name"];
		$item_qty = $menu_items["item_qty"];
		$item_price = $menu_items["item_price"];
		$code = $menu_items["code"];
		echo '<tr>';
		echo '<td>Qty <input type="text" size="2" maxlength="2" name="item_qty['.$code.']" value="'.$item_qty.'" /></td>';
		echo '<td>'.$item_name.'</td>';
		echo '<td><input type="checkbox" name="remove_code[]" value="'.$code.'" /> Remove</td>';
		echo '</tr>';
		$subtotal = ($item_price * $item_qty);
		$total = ($total + $subtotal);
	}
	echo '<td colspan="4">';
	echo '<button type="submit">Update</button><a href="pannullos_cart.php" class="button">Checkout</a>';
	echo '</td>';
	echo '</tbody>';
	echo '</table>';

	$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
	echo '</form>';
	echo '</div>';

}
?>
<?php
$data = $mysqli->query("SELECT item_id, item_name, item_price, menu_id, category_id, descrip, section_id, code FROM OneLook_items WHERE menu_id = '2'");
if($data){
$single_item = '<ul class="items">';
while($obj = $data->fetch_object())
{
$single_item .= <<<EOT
	<li class="item">
	<form method="post" action="pannullos_update.php">
	<div class="item-content"><h3>{$obj->item_name}</h3>
	<div class="item-desc">{$obj->descrip}</div>
	<div class="item-info">
	Price {$currency}{$obj->item_price}

	<fieldset>

	<label>
		<span>Quantity</span>
		<input type="text" size="2" maxlength="2" name="item_qty" value="1" />
	</label>

	</fieldset>
	<input type="hidden" name="code" value="{$obj->code}" />
	<input type="hidden" name="type" value="add" />
	<input type="hidden" name="return_url" value="{$current_url}" />
	<div align="center"><button type="submit" class="add_to_cart">Add</button></div>
	</div></div>
	</form>
	</li>
EOT;
}
$single_item .= '</ul>';
echo $single_item;
}
?>
<!-- items List End -->
</body>
</html>
