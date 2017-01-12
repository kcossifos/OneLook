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
	    <img id="overlayimg" src="images/moghul.jpg"/>
	    <aside>
	            <h3>Moghul Indian Cuisine</h3>
	            <br>
	            <h5>Location: </h5>
	            <p>401 N Semoran Blvd, Winter Park, FL 32792, United States</p>
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
	<p>We are an award winning family restaurant beautifully located in several exquisite destinations in the United States. Moghul Indian Cuisine, Indian Restaurant is in the heart of Winter Park florida. One of our restaurants is near the world famous Grand Canyon in Flagstaff, Arizona ; and our other two restaurants are in Orange County California.</p>
</article>
<section id='pages'>
	<div id="tabs" style="padding: 0;">
		<ul>
			<li><a  href="moghul.php">Menu</a></li>
			<li><a  href="#tabs-2">Reviews</a></li>
			<li><a  href="#tabs-3">Bill OverView</a></li>
	    <li><a  href="moghul_cart.php">My Cart</a></li>
		</ul>
	</div>
			<hr id='endtab'>
</section>


<div class="menu_cart">
<form method="post" action="moghul_update.php">
<table width="100%"  cellpadding="6" cellspacing="0"><thead><tr><th>Quantity</th><th>Name</th><th>Price</th><th>Total</th><th>Remove</th></tr></thead>
  <tbody>


 	<?php
	if(isset($_SESSION["moghul_items"])) //check session var
    {
		$total = 0; //set initial total value
		 //var for zebra stripe table
		foreach ($_SESSION["moghul_items"] as $menu_items)
        {
			//set variables to use in content below
			$menu_name = $menu_items["item_name"];
			$menu_qty = $menu_items["item_qty"];
			$menu_price = $menu_items["item_price"];
			$code = $menu_items["code"];
			$subtotal = ($menu_price * $menu_qty); //calculate Price x Qty

		    echo '<tr>';
			echo '<td><input type="text" size="2" maxlength="2" name="item_qty['.$code.']" value="'.$menu_qty.'" /></td>';
			echo '<td>'.$menu_name.'</td>';
			echo '<td>'.$currency.$menu_price.'</td>';
			echo '<td>'.$currency.$subtotal.'</td>';
			echo '<td><input type="checkbox" name="remove_code[]" value="'.$code.'" /></td>';
            echo '</tr>';
			$total = ($total + $subtotal); //add subtotal to total var
        }

		$grand_total = $total; //grand total including shipping cost
		foreach($taxes as $key => $value){ //list and calculate all taxes in array
				$tax_total     = round($total * ($value / 100));
				$tax_percentage[$key] = $tax_total;
				$grand_total    = $grand_total + $tax_total;  //add tax val to grand total
		}

		$tax       = '';
		foreach($tax_percentage as $key => $value){ //List all taxes
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
		<tr><td colspan="5"><a href="moghul.php" class="button">Add More Items</a><button type="submit">Update</button></td></tr>
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