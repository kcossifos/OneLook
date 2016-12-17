<?php

include 'settings/init.php';

$page->title = 'Welcome to '. $set_class->site_name;

$nav->setActive('home');

include 'header.php';

require_once("settings/db.php");
$db_handle = new DBController();
  //Uncomment this line to show errors
  // error_reporting(0);

  $giovanniResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '1'");
  $pannulloResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '2'");
  $roccoResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '3'");
  $armandoResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '4'");
  $brioResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '5'");
  $albacioResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items WHERE menu_id = '6'");
  $carlucciResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '7'");
  $tamarindResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '8'");
  $myntfineResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '9'");
  $moghulResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '10'");
  $cocinaResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '11'");
  $pepecantinaResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '12'");
  $elpotroResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '13'");
  $parkstationResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued WHERE menu_id = '14'");
  $burgerfiResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '15'");
  $ravenousResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '16'");
  $jumbochineseResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '17'");
  $chinagardenResult = $db->query("SELECT * FROM ".OneLook_PREFIX."items_continued_2 WHERE menu_id = '18'");


    if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
    	case "add":
    		if(!empty($_POST["quantity"])) {
    			$productByCode = $db_handle->runQuery("SELECT * FROM OneLook_items WHERE code='" . $_GET["code"] . "'");
    			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["item_name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["item_price"]));

    			if(!empty($_SESSION["cart_item"])) {
    				if(in_array($productByCode[0]["code"],$_SESSION["cart_item"])) {
    					foreach($_SESSION["cart_item"] as $k => $v) {
    							if($productByCode[0]["code"] == $k)
    								$_SESSION["cart_item"][$k]["quantity"] = $_POST["quantity"];
    					}
    				} else {
    					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
    				}
    			} else {
    				$_SESSION["cart_item"] = $itemArray;
    			}
    		}
    	break;
    	case "remove":
    		if(!empty($_SESSION["cart_item"])) {
    			foreach($_SESSION["cart_item"] as $k => $v) {
    					if($_GET["code"] == $k)
    						unset($_SESSION["cart_item"][$k]);
    					if(empty($_SESSION["cart_item"]))
    						unset($_SESSION["cart_item"]);
    			}
    		}
    	break;
    	case "empty":
    		unset($_SESSION["cart_item"]);
    	break;
    }
    }
    ?>
    <HTML>
    <HEAD>
    <TITLE>Simple PHP Shopping Cart</TITLE>
    <link href="style.css" type="text/css" rel="stylesheet" />
    </HEAD>
    <BODY>
    <div id="shopping-cart">
    <div class="txt-heading">Shopping Cart <a id="btnEmpty" href="menuPage.php?action=empty">Empty Cart</a></div>
    <?php
    if(isset($_SESSION["cart_item"])){
        $item_total = 0;
    ?>
    <table cellpadding="10" cellspacing="1">
    <tbody>
    <tr>
    <th><strong>Name</strong></th>
    <th><strong>Code</strong></th>
    <th><strong>Quantity</strong></th>
    <th><strong>Price</strong></th>
    <th><strong>Action</strong></th>
    </tr>
    <?php
        foreach ($_SESSION["cart_item"] as $item){
    		?>
    				<tr>
    				<td><strong><?php echo $item["name"]; ?></strong></td>
    				<td><?php echo $item["code"]; ?></td>
    				<td><?php echo $item["quantity"]; ?></td>
    				<td align=right><?php echo "$".$item["price"]; ?></td>
    				<td><a href="menuPage.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction">Remove Item</a></td>
    				</tr>
    				<?php
            $item_total += ($item["price"]*$item["quantity"]);
    		}
    		?>

    <tr>
    <td colspan="5" align=right><strong>Total:</strong> <?php echo "$".$item_total; ?></td>
    </tr>
    </tbody>
    </table>
      <?php
    }
    ?>
    </div>

    <div id="product-grid">
    	<div class="txt-heading">Products</div>
    	<?php
    	$product_array = $db_handle->runQuery("SELECT * FROM OneLook_items WHERE menu_id = '1'");
    	if (!empty($product_array)) {
    		foreach($product_array as $key=>$value){
    	?>
    		<div class="product-item">
    			<form method="post" action="menuPage.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
    			<div><strong><?php echo $product_array[$key]["item_name"]; ?></strong></div>
    			<div class="product-price"><?php echo "$".$product_array[$key]["item_price"]; ?></div>
    			<div><input type="text" name="quantity" value="1" size="2" /><input type="submit" value="Add to cart" class="btnAddAction" /></div>
    			</form>
    		</div>
    	<?php
    			}
    	}









include 'footer.php';
  // var_dump($total_num_rows);

  // var_dump($search_query);
  // var_dump($search_query_location);
 ?>
