<?php
$currency = '&#36; ';

$db_username = 'root';
$db_password = 'root';
$db_name = 'OneLook';
$db_host = 'localhost';

$taxes              = array(
                            'Tax' => 6
                            );

$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>
