<?php
require_once('profile_functions.php');

$method = $_GET['method'];

if ($method == 'delete'){
	//should require the listed_songs id to be deleted
	//should echo the updated plugs
	$listed_songs_id = $_GET['listed_songs_id'];
	session_start();
	$user_id = $_SESSION['id'];
	if ($user_id){
		deleteSong($listed_songs_id);
		$username = getUserById($user_id);
		echo getPlugs($username);
	}
	else echo 'stop trying to hack';
}
else if ($method == 'search'){
	$keywords = $_GET['keywords'];
	$xml = getXMLresponse('DigitalMusic',$keywords);
	$title = getTitle($xml);
	$artist = getArtist($xml);
	
	$title = trim(preg_replace('/\s*\([^)]*\)/', '', $title)); //remove all paren itmes
	$title = trim(preg_replace('/\s*\[[^)]*\]/', '', $title));	//remove all bracekt items
	$artist = trim(preg_replace('/\s*\([^)]*\)/', '', $artist));	//remove all paren items
	$artist = trim(preg_replace('/\s*\[[^)]*\]/', '', $artist));	//remove all bracket items
	
	//$results = getItunesResults("$title $artist",5);
	$results = getLastFmSearchResults($keywords,5);
	echo json_encode($results);
}
else if ($method == 'add'){
	$itunes_id = $_GET['itunes_id'];
	session_start();
	$cur_user_id = $_SESSION['id'];
	if ($cur_user_id){
		$songArr = searchSongById($itunes_id);
		addSong($cur_user_id,$songArr);
		$username = getUserById($cur_user_id);
		echo getPlugs($username);
	}
	else echo 'stop trying to hack';
}
else if ($method == 'add_from_search'){
	$title = $_GET['title'];
	$artist = $_GET['artist'];
	$image_url = $_GET['image_url'];
	$page_url = $_GET['page_url'];
	$songArr = searchItunesAndAmazon($title,$artist,$image_url,$page_url);
	//$user_id = $_GET['user_id'];
	session_start();
	$user_id = $_SESSION['id'];
	if ($user_id){
	addSong($user_id,$songArr);
	$username = getUserById($user_id);
	echo getPlugs($username);
	}
	else echo 'stop trying to hack';
}
else if ($method == 'add_to_list'){
	session_start();
	$cur_user_id = $_SESSION['id'];
	if ($cur_user_id){
		$list_id = $_GET['list_id'];
		mysqlConnect();
		$res = mysql_query("SELECT song_id,user_id FROM listed_songs WHERE id='$list_id'");
		$row = mysql_fetch_array($res);
		mysql_query("INSERT INTO listed_songs (song_id,user_id,added_from)
		VALUES('{$row['song_id']}','$cur_user_id','{$row['user_id']}')");
	}
	$res2 =mysql_query("SELECT * FROM listed_songs WHERE id = '$list_id'");
	$row = mysql_fetch_array($res2);
	echo makePlug($list_id,$row['song_id'],$row['time_stamp'],getUserById($row['user_id']),false,$cur_user_id);
}
else{
	echo 'bad method';
}
?>