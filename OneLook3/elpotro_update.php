<?php
session_start();
include_once("config.php");


if(isset($_POST["type"]) && $_POST["type"]=='add' && $_POST["item_qty"]>0)
{
	foreach($_POST as $key => $value){
		$new_menuitem[$key] = filter_var($value, FILTER_SANITIZE_STRING);
    }
		$menu_name;
		$menu_price;
	unset($new_menuitem['type']);
	unset($new_menuitem['return_url']);

		$sql = "SELECT item_name, item_price FROM OneLook_items_continued WHERE code = :code";
		$statement = $db->prepare($sql);
		$statement->execute(array(':code'=>$new_menuitem['code']));

	 while($row = $statement->fetch(PDO::FETCH_ASSOC)){

				$new_menuitem["item_name"] = $row['item_name'];
				$new_menuitem["item_price"] = $row['item_price'];

        if(isset($_SESSION["elpotro_items"])){
            if(isset($_SESSION["elpotro_items"][$new_menuitem['code']]))
            {
                unset($_SESSION["elpotro_items"][$new_menuitem['code']]);
            }
        }
        $_SESSION["elpotro_items"][$new_menuitem['code']] = $new_menuitem;
    }
}

if(isset($_POST["item_qty"]) || isset($_POST["remove_code"]))
{
	if(isset($_POST["item_qty"]) && is_array($_POST["item_qty"])){
		foreach($_POST["item_qty"] as $key => $value){
			if(is_numeric($value)){
				$_SESSION["elpotro_items"][$key]["item_qty"] = $value;
			}
		}
	}
	if(isset($_POST["remove_code"]) && is_array($_POST["remove_code"])){
		foreach($_POST["remove_code"] as $key){
			unset($_SESSION["elpotro_items"][$key]);
		}
	}
}

$return_url = (isset($_POST["return_url"]))?urldecode($_POST["return_url"]):'';
header('Location:'.$return_url);

?>
