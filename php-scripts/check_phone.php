<?php
require_once('mysql_connect.php');

$phone = $_GET['phone'];		//should be 10 digits, '+1' is not included
echo checkPhone($phone);
function checkPhone($phone){
//returns 1 if it DNE, and 0 if it does exist
	mysqlConnect();
	$res = mysql_query("SELECT id FROM users WHERE phone_number='+1$phone'");
	if (mysql_num_rows($res)){
		return 0;
	}
	else return 1;
}
?>