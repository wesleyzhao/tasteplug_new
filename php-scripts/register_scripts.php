<?php
require_once("mysql_connect.php");
require_once("login_functions.php");
require_once("../twilio-scripts/twilio.php");
session_start();
if ($_SESSION['username']) header("location: /{$_SESSION['username']}");   
else{
mysqlConnect();

$phone = $_POST['phone'];
$phone = mysql_real_escape_string($phone);
$phone = str_replace("-", "", $phone);
$phone = "+1".$phone;
$password = $_POST['password']; 
$raw_password2 = $_POST['password2'];
$raw_password = $password;          
$password = mysql_real_escape_string($password);
$password = saltify($password);  

$username = strtolower($_POST['username']);
$redirect = $_POST['redirect'];
if(!redirect) $redirect = "index.php";     

$phone = strip_tags($phone);          
$username = strip_tags($username);

if(!validateUsername($username) && !phoneNoPassword($username)){
	header("Location: http://tasteplug.com/$redirect?message=Sorry please enter a valid username.");
}else if(!validatePhone($phone)){
	header("Location: http://tasteplug.com/$redirect?message=Sorry please enter a valid phone number.");
}else if(!validatePassword($raw_password)){
//else if(!validatePassword($raw_password) || $raw_password!=$raw_password2){
   	header("Location: http://tasteplug.com/$redirect?message=Sorry please enter a valid password."); 
}else{
	$sql = "SELECT `phone_number`, `username`, `id`, `current_city`, `password` FROM `users` WHERE `phone_number` = '$phone' LIMIT 1";
	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
	if($row['password'] == '' && $row['phone_number'] !=  ''){     
		//then we can update an existing user   
		$sql = "UPDATE `users` SET `password` = '$password',`username` = '$username' WHERE `phone_number` = '$phone'";   
		$result = mysql_query($sql);
		if($result){
			$id = $row['id'];
		    $username = $row['username'];
			$current_city = $row['current_city'];
			session_start();
			loginUser($id, $phone, $username, $current_city, $password);
		}else{
			header("Location: http://tasteplug.com/$redirect?message=Sorry there was an error processing your request");
		}
	}
	else if(!mysql_num_rows($result)){
		$sql = "INSERT INTO `users` (`phone_number`, `password`, `username`) VALUES ('$phone', '$password', '$username')";
		$result = mysql_query($sql) or die(mysql_query());
		sendSmsConfirmation($username,$phone);
		if($result){
			$id = mysql_insert_id();
			session_start();
			loginUser($id, $phone, $username, "null", $password);
		}   
		else{
			header("Location: http://tasteplug.com/$redirect?message=Sorry we could not process your request.");
		}
	}   
	else{
		header("Location: http://tasteplug.com/$redirect?message=Sorry that phone already exists in our database.");
	}
      
}
}

function sendSmsConfirmation($username,$phone_number){
	$from = "415-968-5167";
	// Twilio REST API version 
    $ApiVersion = "2010-04-01";
    
    // Set our AccountSid and AuthToken 
    $AccountSid = "AC64b5fb38b7482c485f832d3a4565b097";
    $AuthToken = "a43a6d6e797af43d229269e5aedb6592";
	$client = new TwilioRestClient($AccountSid, $AuthToken);
	$message = "$username welcome to Tasteplug! Save this number and you can start adding to your list by texting a song name, artist or both!";
	$message2 = substr($message,160);
	if (strlen($message)>160){
		$message = substr($message,0,160);
		$data = array(
			"From" => $from, 	      // Outgoing Caller ID
			"To" => $phone_number,	  // The phone number you wish to dial
			"Body" => $message2
		);
	
	$response = $client->request("/$ApiVersion/Accounts/$AccountSid/SMS/Messages", 
    "POST", $data); 
		
	}
	$data = array(
    	"From" => $from, 	      // Outgoing Caller ID
    	"To" => $phone_number,	  // The phone number you wish to dial
    	"Body" => $message
    );
	
	$response = $client->request("/$ApiVersion/Accounts/$AccountSid/SMS/Messages", 
    "POST", $data); 
	
	    if ($response->IsError){
		return false;
	}
	else return true;
    
}
?>