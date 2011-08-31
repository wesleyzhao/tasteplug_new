<?php                       
require_once("mysql_connect.php");
require_once("login_functions.php");
                                     
mysqlConnect();

$username = strtolower($_POST['username']);
$password = $_POST['password'];

$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);

$password = saltify($password);

$sql = "SELECT `phone_number`, `id`, `current_city` FROM `users` WHERE `username` = '$username' LIMIT 1";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result);

$id = $row['id'];            
$current_city = $row['current_city']; 
$phone = $row['phone_number'];            
print "phone = $phone";
session_start();
loginUser($id, $phone, $username, $current_city, $password);    

?>