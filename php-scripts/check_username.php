<?php
require_once('mysql_connect.php');

$username = $_GET['username'];
echo checkUsername($username);
function checkUsername($username){
//returns 1 if it DNE, and 0 if it does exist
	mysqlConnect();
	$res = mysql_query("SELECT id FROM users WHERE username='$username'");
	if (mysql_num_rows($res)){
		return 0;
	}
	else return 1;
}


?>