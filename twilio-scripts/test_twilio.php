<?php
require_once("twilio.php");

echo sendSmsConfirmation("awesomelongnamelongmaelongnamelongnamelongnamejupiterscock",'+16099336872');
echo sendSmsConfirmation("awesomelongnamelongmaelongnamelongnamelongnamejupiterscock",'+14255333129');
echo sendSmsConfirmation("awesomelongnamelongmaelongnamelongnamelongnamejupiterscock",'+14255021843');

function sendSmsConfirmation($username,$phone_number){
	$from = "415-968-5167";
	// Twilio REST API version 
    $ApiVersion = "2010-04-01";
    
    // Set our AccountSid and AuthToken 
    $AccountSid = "AC64b5fb38b7482c485f832d3a4565b097";
    $AuthToken = "a43a6d6e797af43d229269e5aedb6592";
	$client = new TwilioRestClient($AccountSid, $AuthToken);
	$message = "$username welcome to Tasteplug! Save this number and you can start adding to your list by texting a song name,artist or both!";
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
	else return 'ok';
}

?>