<?php
session_start();
include_once("config.php");


if(isset($_POST["type"]) && $_POST["type"]=='add' && $_POST["item_qty"]>0)
{
	foreach($_POST as $key => $value){
		$new_menuitem[$key] = filter_var($value, FILTER_SANITIZE_STRING);
    }
	unset($new_menuitem['type']);
	unset($new_menuitem['return_url']);

    $statement = $mysqli->prepare("SELECT item_name, item_price FROM OneLook_items WHERE code=?");
    $statement->bind_param('s', $new_menuitem['code']);
    $statement->execute();
    $statement->bind_result($menu_name, $menu_price);

	while($statement->fetch()){


        $new_menuitem["item_name"] = $menu_name;
        $new_menuitem["item_price"] = $menu_price;

        if(isset($_SESSION["roccos_items"])){
            if(isset($_SESSION["roccos_items"][$new_menuitem['code']]))
            {
                unset($_SESSION["roccos_items"][$new_menuitem['code']]);
            }
        }
        $_SESSION["roccos_items"][$new_menuitem['code']] = $new_menuitem;
    }
}

if(isset($_POST["item_qty"]) || isset($_POST["remove_code"]))
{
	if(isset($_POST["item_qty"]) && is_array($_POST["item_qty"])){
		foreach($_POST["item_qty"] as $key => $value){
			if(is_numeric($value)){
				$_SESSION["roccos_items"][$key]["item_qty"] = $value;
			}
		}
	}
	if(isset($_POST["remove_code"]) && is_array($_POST["remove_code"])){
		foreach($_POST["remove_code"] as $key){
			unset($_SESSION["roccos_items"][$key]);
		}
	}
}

$return_url = (isset($_POST["return_url"]))?urldecode($_POST["return_url"]):'';
header('Location:'.$return_url);

?>
