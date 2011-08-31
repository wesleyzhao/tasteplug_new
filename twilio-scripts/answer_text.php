<?php
require_once('../php-scripts/profile_functions.php');
$id = $_REQUEST['From'];
$keywords = $_REQUEST['Body'];

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

if ($keywords == 'Delete' || $keywords == 'delete'){
	mysqlConnect();
	$res = mysql_query("SELECT id FROM users WHERE phone_number='$id'");
	$row = mysql_fetch_array($res);
	$user_id = $row['id'];
	$res2 = mysql_query("SELECT id,song_id FROM listed_songs WHERE (user_id='$user_id' and is_deleted='0') ORDER BY id DESC");
		
	$row2 = mysql_fetch_array($res2);
	$song_id = $row2['song_id'];
	mysql_query("UPDATE listed_songs SET is_deleted='1' WHERE id='{$row2['id']}'");
	
	$res = mysql_query("SELECT title,artist FROM songs WHERE id='$song_id'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		echo "<Response><Sms>{$row['title']} by {$row['artist']} has been deleted. Search again!</Sms></Response>";
	}
}
else{
	$songArr = searchSong($keywords);
	$songArr['title'] = str_replace('&','and',$songArr['title']);
	$songArr['artist'] = str_replace('&', 'and',$songArr['artist']);
	mysqlConnect();
	$res = mysql_query("SELECT username,id FROM users WHERE phone_number='$id'");
	try{
		$row = mysql_fetch_array($res);
		$username = $row['username'];
	}
	catch (Exception $e){
		$username = 'blah';
	}
	if (!mysql_num_rows($res) || $username==''){
		mysqlConnect();
		//mysql_query("INSERT INTO users (name) VALUES('bitch')");
		$keywordsArr = explode(' ',$keywords);
		if (count($keywordsArr)>1 && stristr($keywordsArr[0],'create')){
			
			$username = checkUsername($keywordsArr[1],$id);
			//$msg = "<Response><Sms>Thanks $username! You can now check out your list or change your username here: Tasteplug.com/$username - Try another song!</Sms></Response>";
			//$msg = mysql_real_escape_string($msg);
			//mysql_query("INSERT INTO users (name) VALUES('$msg')");
			//echo "<Response><Sms>yo</Sms></Response>";
			echo "<Response><Sms>Thanks $username! You can now check out your list or change your username here: Tasteplug.com/$username - Try another song!</Sms></Response>";
			
		}
		else{
			$current_city = $_REQUEST['FromCity'].', '.$_REQUEST['FromState'];
			$res2 = mysql_query("INSERT INTO users (phone_number,current_city) VALUES ('$id','$current_city')");
			$userArr = getUserByNumber($id);
			$user_id = $userArr['user_id'];
			if ($songArr['message']!='NOT FOUND'){
				addSong($user_id,$songArr);
			$title = stripParensBrackets($songArr['title']);
			$artist = stripParensBrackets($songArr['artist']);
				$message = "You just added $title by $artist. To save or edit your list text back \"create\" followed by a username!";
				$message = breakUpLink($message);
					echo "<Response>$message</Response>";
				
			}
			else{
				echo "<Response><Sms>Sorry, song was not found :( To create your page text back \"create\" followed by a username!</Sms></Response>";
			}
		}
	}
	else{
	//if user phone number already found in the database
		//$row = mysql_fetch_array($res);		//$row has already been created in the 'try' statement
		$user_id = $row['id'];
		mysqlConnect();
		mysql_query("INSERT INTO users (name) VALUES ('bitchASSbitch')");
		if ($songArr['message']!='NOT FOUND'){
			mysqlConnect();
			mysql_query("INSERT INTO users (name) VALUES ('bitchASS')");
			addSong($user_id,$songArr);
			//$url = searchAmazonSong("{$songArr['title']} by {$songArr['artist']}");
			$username = getUserById($user_id);
			$title = stripParensBrackets($songArr['title']);
			$artist = stripParensBrackets($songArr['artist']);
			$message = "You just added $title by $artist. Text 'Delete' if you would like to remove and search again. Updated list: http://tasteplug.com/$username";
			$text = breakUpLink($message);
			echo "<Response>$text</Response>";
		}
		else{
			mysqlConnect();
			mysql_query("INSERT INTO users (name) VALUES ('bitch')");
			echo "<Response><Sms>Sorry, song was not found :(. Maybe try another search with different or more keywords!</Sms></Response>";
		}
	}
}

function breakUpText($text){
	if (strlen($text)>160){
		$first = substr($text,0,160);
		$second = substr($text,160);
		return "<Sms>$first</Sms><Sms>$second</Sms>";
	}
	else return "<Sms>$text</Sms>";
}

function breakUpLink($text){
	$pos = strrpos($text,'Updated list: http');
	if ($pos){
		if ($pos>=160){
			return breakUpText($text);
		}
		else{
			if (strlen($text)>160){
				$first = substr($text,0,$pos);
				$second = substr($text,$pos);
				return "<Sms>$first</Sms><Sms>$second</Sms>";
			}
			else return "<Sms>$text</Sms>";
		}
	}
	else return breakUpText($text);
}

function stripParens($text){
	$text = trim(preg_replace('/\s*\([^)]*\)/', '', $text)); //remove all paren itmes

	return $text;
}

function stripBrackets($text){
	$text = trim(preg_replace('/\s*\[[^)]*\]/', '', $text));	//remove all bracekt items
	return $text;
}

function stripParensBrackets($text){
	$text = stripParens($text);
	return stripBrackets($text);
}
function checkUsername($username,$phone_number){
	mail("dshipper@gmail.com", "yo", "called");
	$songArr = searchSong($keywords);
	$res = mysql_query("SELECT id FROM users WHERE username = '$username'");
	if (mysql_num_rows($res)){
		$count = 1;
		$username_available = false;
		while (!$username_available){
			$res = mysql_query("SELECT id FROM users WHERE username = '$username".$count."'");
			if (mysql_num_rows($res)){
			$count+=1;
			}
			else{
				$username = $username.$count;
				$username_available = true;
			}
		}
	}
	$res = mysql_query("SELECT id FROM users WHERE phone_number='$phone_number'");
	if (mysql_num_rows($res)){
		$row =mysql_fetch_array($res);
		$user_id = $row['id'];
	}
	else{
		$res2 = mysql_query("INSERT INTO users (phone_number) VALUES('$phone_number')");
		$user_id = mysql_insert_id();
	}
	$username = strtolower($username);
	mysql_query("UPDATE users SET username='$username', is_phone_confirmed='1' WHERE id='$user_id'");
	
	return $username;
}

?>
