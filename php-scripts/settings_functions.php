<?php
require_once('mysql_connect.php');
require_once('login_functions.php');
require_once('../twilio-scripts/twilio.php');
require_once('profile_functions.php');

function changePhoneNumber($new_number){
//verifies user with session credentials
//if verified, will alter phone_number and change the session variable
//if not verified, will return user not verified                    
	$new_number = "+1".$new_number;
    if($_SESSION['phone_number'] == $new_number){
		return 'Phone number entered was the same as your old number...';               
	}
	else{
		$user_id = verifyUser();
		if ($user_id){
			mysqlConnect();
			$res2 = mysql_query("SELECT id FROM users WHERE phone_number = '$new_number'");
			if (mysql_num_rows($res2)) return 'Phone number is already taken.';
			
			$res = mysql_query("UPDATE users SET phone_number='$new_number' WHERE id='$user_id'");
			$_SESSION['phone_number'] = substr($new_number,2);
			$username = getUserById($user_id);
			$msg_error = sendNewNumberConfirmation($username,$new_number);
			return "$msg_error";
		}
		else return 'You are trying to hack. No good.';
	}
}

function changeUsername($new_username){
//verifies user with session credentials
//if verified, will alter username and change session variable 'username'
//if not, then returns uer not verified
	$user_id = verifyUser();
	if ($user_id){
		mysqlConnect();
		$res2 = mysql_query("SELECT id FROM users WHERE username='$new_username'");
		if (mysql_num_rows($res2)) return 'Username has already been taken.';
		$res = mysql_query("UPDATE users SET username='$new_username' WHERE id='$user_id'");
		$_SESSION['username'] = $new_username;
		return 'Your username has been changed.';		
	}
	else return 'You are not verified, hacker.';
}

function changePassword($old_password,$new_password){
//verifies user with session credentials
//takes in input of not-saltified old and new passwords and then alters to check and update
//will return not verified if either fail
    if($old_password == "") return false;	
	$user_id = verifyUser();
	if ($user_id){
		mysqlConnect();
		$old_saltified = saltify($old_password);
		$res = mysql_query("SELECT password FROM users WHERE id = '$user_id'");
		$row = mysql_fetch_array($res);
		if ($row['password'] == $old_saltified){
			$new_saltified = saltify($new_password);
			mysql_query("UPDATE users SET password='$new_saltified' WHERE id='$user_id'");
			return 'Password has been changed.';	
		}
		else{
			return 'Old password was not correct';
		}
	}
	else return 'User not verified, hacker.';
}


function verifyUser(){
//requires session_start() to have been called
//will check $_SESSION['username'] and $_SESSION['id'] for match, if match then returns user_id
//if not match then false
	mysqlConnect();
	$res = mysql_query("SELECT id FROM users WHERE username='{$_SESSION['username']}'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		if ($row['id'] == $_SESSION['id']) return $row['id'];
		else return false;
	}
	else{
		return false;
	}
	
}

function sendNewNumberConfirmation($username,$phone_number){
	$from = "415-968-5167";
	// Twilio REST API version 
    $ApiVersion = "2010-04-01";
    
    // Set our AccountSid and AuthToken 
    $AccountSid = "AC64b5fb38b7482c485f832d3a4565b097";
    $AuthToken = "a43a6d6e797af43d229269e5aedb6592";
	$client = new TwilioRestClient($AccountSid, $AuthToken);
	$message = "$username - this is the new number that you have saved! Now search for a song to add to your list http://tasteplug.com/$username";
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
		return $response->ErrorMessage;
	}
	else return 'New phone number has been confirmed!';
    
}

session_start();
mysqlConnect();
$phone = $_POST['phone_number'];
$username = $_POST['username'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];  


$phone = str_replace("-", "", $phone);
$phone = mysql_real_escape_string($phone); 

$username = mysql_real_escape_string($username);
$old_password = mysql_real_escape_string($old_password);
$new_password = mysql_real_escape_string($new_password);

$username = strip_tags($username);

if($phone != '' && $phone != $_SESSION['phone_number']){
	$message_phone = changePhoneNumber($phone);                   
} 
if($username != '' && $username != $_SESSION['username']){
	$message_username = changeUsername($username);                     
}
if($old_password != '' && $new_password != ''){
	$message_pass = changePassword($old_password, $new_password); 
} 
//header("Location: /settings?message_phone=$message_phone&message_username=$message_username&message_pass=$message_pass"); 
header("Location: /settings?message=$message_phone<br>".$message_username."<br>".$message_pass); 
?>