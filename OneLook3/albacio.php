<?php

include_once("config.php");
include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'headersearch.php';

$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body>
	<header>
	    <img id="overlayimg" src="images/albacio.png"/>
	    <aside>
	            <h3>Al Bacio</h3>
	            <br>
	            <h5>Location: </h5>
	            <p>505 N Park Ave, Winter Park, FL 32789, United States</p>
	        </aside>
	       <ul>
	        <li><i class="fa fa-star" aria-hidden="true"></i></li>
	        <li><i class="fa fa-star" aria-hidden="true"></i></li>
	        <li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star" aria-hidden="true"></i></li>
	    </ul>
	</header>
	<article id= "details">
	<p> We were born on Park Ave with the vision of sharing a unique culinary experience with Winter Park’s discerning residents and guests.
We offer a mix of Italian, American and Latin American food carefully prepared by world-class pastry and Italian chefs in a warm, inviting café-style restaurant.
For all of us at Al Bacio, your satisfaction is our top priority and serving our customers is both a pleasure and an honor. We invite you and your friends to enjoy our hospitality and the Al Bacio experience.</p>
</article>
<section id='pages'>
	<div id="tabs" style="padding: 0;">
		<ul>
			<li><a  href="albacio.php">Menu</a></li>
			<li><a  href="#tabs-2">Reviews</a></li>
			<li><a  href="#tabs-3">Bill OverView</a></li>
	    <li><a  href="albacio_cart.php">My Cart</a></li>
		</ul>
	</div>
			<hr id='endtab'>
</section>

<?php
if(isset($_SESSION["albacio_items"]) && count($_SESSION["albacio_items"])>0)
{
	echo '<div class="shoppingcart">';
	echo '<h3>Your Menu Items</h3>';
	echo '<form method="post" action="albacio_update.php">';
	echo '<table width="100%"  cellpadding="6" cellspacing="0">';
	echo '<tbody>';

	$total =0;
	$b = 0;
	foreach ($_SESSION["albacio_items"] as $menu_items)
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
	echo '<button type="submit">Update</button><a href="albacio_cart.php" class="button">Checkout</a>';
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
$data = $mysqli->query("SELECT item_id, item_name, item_price, menu_id, category_id, descrip, section_id, code FROM OneLook_items WHERE menu_id = '6'");
if($data){
$single_item = '<ul class="items">';
while($obj = $data->fetch_object())
{
$single_item .= <<<EOT
	<li class="item">
	<form method="post" action="albacio_update.php">
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
</body>
</html>
