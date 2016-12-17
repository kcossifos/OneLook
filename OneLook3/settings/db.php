<?php


$set_class->db_host = 'localhost'; // database host
$set_class->db_user = 'root'; // database user
$set_class->db_pass = 'root'; // database password
$set_class->db_name = 'OneLook'; // database name

define('OneLook_PREFIX', 'OneLook_');
?>


<?php
class DBController {
	private $host = "localhost";
	private $user = "root";
	private $password = "root";
	private $database = "OneLook";

	function __construct() {
		$conn = $this->connectDB();
		if(!empty($conn)) {
			$this->selectDB($conn);
		}
	}

	function connectDB() {
		$conn = mysql_connect($this->host,$this->user,$this->password);
		return $conn;
	}

	function selectDB($conn) {
		mysql_select_db($this->database,$conn);
	}

	function runQuery($query) {
		$result = mysql_query($query) or die(mysql_error());
		while($row=mysql_fetch_assoc($result)) {
			$resultset[] = $row;
		}
		if(!empty($resultset))
			return $resultset;
	}

	function numRows($query) {
		$result  = mysql_query($query);
		$rowcount = mysql_num_rows($result);
		return $rowcount;
	}
}
?>
