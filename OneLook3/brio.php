<?php

include_once("config.php");
include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'headersearch.php';

$menupage = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body>
	<header>
	    <img id="overlayimg" src="images/brio.jpg"/>
	    <aside>
	            <h3>BRIO Tuscan Grille</h3>
	            <br>
	            <h5>Location: </h5>
	            <p>480 N Orlando Ave, Winter Park, FL 32789, United States</p>
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
	<p>At BRIO, we celebrate life, love & all things Italian!  In our restaurants, the chef-inspired cuisine at BRIO is simply prepared using the finest and freshest ingredients, with an emphasis on high-quality steaks, housemade pasta specialties and flatbreads prepared in an authentic Italian oven. On this blog, we share our recipes, experiences, tips & more with you!</p>
</article>
<section id='pages'>
	<div id="tabs" style="padding: 0;">
		<ul>
			<li><a  href="brio.php">Menu</a></li>
			<li><a  href="#tabs-2">Reviews</a></li>
			<li><a  href="#tabs-3">Bill OverView</a></li>
	    <li><a  href="brio_cart.php">My Cart</a></li>
		</ul>
	</div>
			<hr id='endtab'>
</section>

<?php
if(isset($_SESSION["brio_items"]) && count($_SESSION["brio_items"])>0)
{
	echo '<div class="shoppingcart" id="view-cart">';
	echo '<h3>Your Menu Items</h3>';
	echo '<form method="post" action="brio_update.php">';
	echo '<table width="100%"  cellpadding="6" cellspacing="0">';
	echo '<tbody>';

	$total =0;

	foreach ($_SESSION["brio_items"] as $menu_items)
	{
		$menu_name = $menu_items["item_name"];
		$menu_qty = $menu_items["item_qty"];
		$menu_price = $menu_items["item_price"];
		$code = $menu_items["code"];
		echo '<tr>';
		echo '<td>Qty <input type="text" size="2" maxlength="2" name="item_qty['.$code.']" value="'.$menu_qty.'" /></td>';
		echo '<td>'.$menu_name.'</td>';
		echo '<td><input type="checkbox" name="remove_code[]" value="'.$code.'" /> Remove</td>';
		echo '</tr>';
		$subtotal = ($menu_price * $menu_qty);
		$total = ($total + $subtotal);
	}
	echo '<td colspan="4">';
	echo '<button class="button" type="submit">Update</button><a href="brio_cart.php" class="button">Checkout</a>';
	echo '</td>';
	echo '</tbody>';
	echo '</table>';

	$menupage = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	echo '<input type="hidden" name="return_url" value="'.$menupage.'" />';
	echo '</form>';
	echo '</div>';

}
?>

<?php
$data = $db->query("SELECT item_id, item_name, item_price, menu_id, category_id, descrip, section_id, code FROM OneLook_items WHERE menu_id = '5'");
if($data){
$food_item = '<ul class="items">';
foreach($data as $obj) {
$food_item .= <<<EOT
	<li class="item">
	<form method="post" action="brio_update.php">
	<div class="item-content"><h3>{$obj['item_name']}</h3>
	<div class="item-desc">{$obj['descrip']}</div>
	<div class="item-info">
	Price {$currency}{$obj['item_price']}

	<fieldset>

	<label>
		<span>Quantity</span>
		<input type="text" size="2" maxlength="2" name="item_qty" value="1" />
	</label>

	</fieldset>
	<input type="hidden" name="code" value="{$obj['code']}" />
	<input type="hidden" name="type" value="add" />
	<input type="hidden" name="return_url" value="{$menupage}" />
	<div align="center"><button type="submit" >Add</button></div>
	</div></div>
	</form>
	</li>
EOT;
}
$food_item .= '</ul>';
echo $food_item;
}
?>
</body>
</html>
<?php
include 'footer.php';

?>
