<?php
include_once("config.php");
include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'headersearch.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body>
	<header>
	    <img id="overlayimg" src="images/car.png"/>
	    <aside>
	            <h3>Carluccis</h3>
	            <br>
	            <h5>Location: </h5>
	            <p>463 W New England Ave, Winter Park, FL 32789, United States</p>
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
	<p>The concept for Armando's developed from a love of cooking, wine, and celebrating family and friends. Armando's features authentic Italian cuisine from Napoli, where is originally from, and other regions as well as an extensive wine list. Whether you dine inside or outside, our atmosphere is warm and inviting. We look forward to having you join us.</p>
</article>
<section id='pages'>
	<div id="tabs" style="padding: 0;">
		<ul>
			<li><a  href="carluccis.php">Menu</a></li>
			<li><a  href="#tabs-2">Reviews</a></li>
			<li><a  href="#tabs-3">Bill OverView</a></li>
	    <li><a  href="carluccis_cart.php">My Cart</a></li>
		</ul>
	</div>
			<hr id='endtab'>
</section>

<div class="menu_cart">
<form method="post" action="carluccis_update.php">
<table width="100%"  cellpadding="6" cellspacing="0"><thead><tr><th>Quantity</th><th>Name</th><th>Price</th><th>Total</th><th>Remove</th></tr></thead>
  <tbody>


 	<?php
	if(isset($_SESSION["carluccis_items"]))
    {
		$total = 0;
		
		foreach ($_SESSION["carluccis_items"] as $menu_items)
        {
			$menu_name = $menu_items["item_name"];
			$menu_qty = $menu_items["item_qty"];
			$menu_price = $menu_items["item_price"];
			$code = $menu_items["code"];
			$subtotal = ($menu_price * $menu_qty);

		    echo '<tr>';
			echo '<td><input type="text" size="2" maxlength="2" name="item_qty['.$code.']" value="'.$menu_qty.'" /></td>';
			echo '<td>'.$menu_name.'</td>';
			echo '<td>'.$currency.$menu_price.'</td>';
			echo '<td>'.$currency.$subtotal.'</td>';
			echo '<td><input type="checkbox" name="remove_code[]" value="'.$code.'" /></td>';
            echo '</tr>';
			$total = ($total + $subtotal);
        }

		$grand_total = $total;
		foreach($taxes as $key => $value){
				$tax_total     = round($total * ($value / 100));
				$tax_percentage[$key] = $tax_total;
				$grand_total    = $grand_total + $tax_total;
		}

		$tax       = '';
		foreach($tax_percentage as $key => $value){ 
			$tax .= $key. ' : '. $currency. sprintf("%01.2f", $value).'<br />';
		}
	}
    ?>
    <?php
    class Tip_Percentage
    {

    public function per($a,$b)
    {
      $res = ($a + ($b/100) * $a);
      return $res;
    }

    }
    $res = '';
    $first = '';
    $second = '';
    if(isset($_POST['submit']))
    {
    $obj = new Tip_Percentage;

    $first = $total;
    $second = $_POST['val2'];

      $res = $obj->per($first,$second);



    }
    ?>

    <tr><td colspan="5"><span style="float:right;text-align: right;"><?php echo $tax; ?></span></td></tr>
		<tr class='heading'><th>Tip</th><th></th><th>Submit</th><th></th><th>Total</th></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td colspan="5"><a href="carluccis.php" class="button">Add More Items</a><button type="submit">Update</button></td></tr>
  </tbody>
</table>
<input type="hidden" name="return_url" value="<?php
$menupage = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
echo $menupage; ?>" />
</form>
</div>

</body>
</html>

<script src='http://code.jquery.com/jquery-2.1.3.min.js' type='text/javascript'></script>
<form class='tip' method='post'>

	<div>
	<input type='text' name='val2' id='val2'  required='1' placeholder='Tip Percentage' value='<?php echo $second; ?>'/>
	<input id='sym2' type='submit' value='=' name='submit'/>&nbsp;&nbsp;<b id='left'><?php if($res != ''){echo sprintf("%01.2f",$res + $tax_total); } ?></b>
	</div>
</form>
