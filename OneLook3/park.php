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
	    <img id="overlayimg" src="images/parkstation.png"/>
	    <aside>
	            <h3>Park Station</h3>
	            <br>
	            <h5>Location: </h5>
	            <p>212 N Park Ave, Winter Park, FL 32789, United States</p>
	        </aside>
	       <ul>
	        <li><i class="fa fa-star" aria-hidden="true"></i></li>
	        <li><i class="fa fa-star" aria-hidden="true"></i></li>
	        <li><i class="fa fa-star" aria-hidden="true"></i></li>
					<li><i class="fa fa-star-o" aria-hidden="true"></i></li>
					<li><i class="fa fa-star-o" aria-hidden="true"></i></li>
	    </ul>
	</header>
	<article id= "details">
	<p>It started with David and Blake Levison. The father-son duo began with an inspiring culinary trip around the country. The picky two tried nearly sixty restaurants in their short three week trip and believed Winter Park is the perfect place to help elevate the foodie scene in Orlando. Their main goal is to get nominated by the James Beard Foundation. With such a difficult task and a year already under their belts they utilized relationships and friendships to build the restaurant from the ground up.</p>
</article>
<section id='pages'>
	<div id="tabs" style="padding: 0;">
		<ul>
			<li><a  href="park.php">Menu</a></li>
			<li><a  href="#tabs-2">Reviews</a></li>
			<li><a  href="#tabs-3">Bill OverView</a></li>
	    <li><a  href="park_cart.php">My Cart</a></li>
		</ul>
	</div>
			<hr id='endtab'>
</section>

<?php
if(isset($_SESSION["park_items"]) && count($_SESSION["park_items"])>0)
{
	echo '<div class="shoppingcart" id="view-cart">';
	echo '<h3>Your Menu Items</h3>';
	echo '<form method="post" action="park_update.php">';
	echo '<table width="100%"  cellpadding="6" cellspacing="0">';
	echo '<tbody>';

	$total =0;

	foreach ($_SESSION["park_items"] as $menu_items)
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
	echo '<button class="button" type="submit">Update</button><a href="park_cart.php" class="button">Checkout</a>';
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
$data = $db->query("SELECT item_id, item_name, item_price, menu_id, category_id, descrip, section_id, code FROM OneLook_items_continued WHERE menu_id = '14'");
if($data){
$food_item = '<ul class="items">';


foreach($data as $obj) {
$food_item .= <<<EOT
	<li class="item">
	<form method="post" action="park_update.php">
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
