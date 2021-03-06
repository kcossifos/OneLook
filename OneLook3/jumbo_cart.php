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

<link href="css/stylesheet.css" rel="stylesheet" type="text/css"></head>
<body>
  <header>
      <img id="overlayimg" src="images/jumbo.png"/>
      <aside>
              <h3>Jumbo Chinese Restaurant</h3>
              <br>
              <h5>Location: </h5>
              <p>1967 Aloma Ave, Winter Park, FL 32792, United States</p>
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
  <p>Jumbo Chinese Restaurant offers delicious dining and takeout to Winter Park, FL.Jumbo Chinese Restaurant is a cornerstone in the Winter Park community and has been recognized for its outstanding Chinese cuisine, excellent service and friendly staff.
    Our Chinese restaurant is known for its modern interpretation of classic dishes and its insistence on only using high quality fresh ingredients.</p>
</article>
<section id='pages'>
  <div id="tabs" style="padding: 0;">
    <ul>
      <li><a  href="jumbo.php">Menu</a></li>
      <li><a  href="#tabs-2">Reviews</a></li>
      <li><a  href="#tabs-3">Bill OverView</a></li>
      <li><a  href="jumbo_cart.php">My Cart</a></li>
    </ul>
  </div>
      <hr id='endtab'>
</section>

<div class="menu_cart">
<form method="post" action="jumbo_update.php">
<table width="100%"  cellpadding="6" cellspacing="0"><thead><tr><th>Quantity</th><th>Name</th><th>Price</th><th>Total</th><th>Remove</th></tr></thead>
  <tbody>


 	<?php
	if(isset($_SESSION["jumbo_items"]))
    {
		$total = 0;
		
		foreach ($_SESSION["jumbo_items"] as $menu_items)
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
    <tr><td colspan="5"><a href="jumbo.php" class="button">Add More Items</a><button type="submit">Update</button></td></tr>
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
