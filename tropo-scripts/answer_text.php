<?php

require_once('../php-scripts/profile_functions.php');
/*
$id = $currentCall->callerID;
$id = intval($id);
$keywords = $currentCall->initialText;
if ($keywords == 'Delete' || $keywords == 'delete'){
	$res = mysql_query("SELECT id FROM users WHERE phone_number='$id'");
	$row = mysql_fetch_array($res);
	$res2 = mysql_query("SELECT id,song_id FROM listed_songs WHERE user_id='{$row['id']}' ORDER BY id DESC");
	$row2 = mysql_fetch_array($res2);
	mysql_query("UPDATE listed_songs SET is_deleted='1' WHERE id='{$row2['id']}'");
}
else{
	$songArr = searchSong($keywords);
	mysqlConnect();
	$res = mysql_query("SELECT id FROM users WHERE phone_number='$id'");
	if (!mysql_num_rows($res)){
			say("here");
			
			$event = ask("You just added {$songArr['title']} by {$songArr['artist']}. To save, please text back a username", array(
				"timeout"=>60,
				"onEvent"=>"checkUsername",
				"onTimeout"=>"timeoutFcn"
				)
			);
			
	}
	else{
		$row = mysql_fetch_array($res);
		$user_id = $row['id'];
		addSong($user_id,$songArr);
		say("You just added {$songArr['title']} by {$songArr['artist']}. Text 'Delete' if you would like to remove and search again.");
	}
}
*/
function checkUsername($event){
	global $id,$songArr;
	$username = $event->value;
	mysql_query("INSERT INTO users (name,phone_number,is_confirmed) VALUES('$username','$id','1')");
	$res = mysql_query("SELECT id FROM users WHERE phone_number='$id'");
	$row =mysql_fetch_array($res);
	$user_id = $row['id'];
	addSong($user_id,$songArr);
	say("Thanks $username! You can now check out your list and change your username here: Tasteplug.com/$username");
}

function timeoutFcn(){
	say("Sorry, we didn't get your username. Enter in another search if you would like to create an account!");
}
?>