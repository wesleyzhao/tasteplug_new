<?php
require_once('amazon_functions.php');
require_once('itunes_functions.php');
require_once('mysql_connect.php');
require_once('lastfm_functions.php');
session_start();

function searchSong($keywords){
	//string $keywords represents the search terms given to iTunes search API
	//returns an array of the following keys
	//string 'title' : title of the song
	//string 'artist' : artist of the song
	//int 'trackId' : iTunes specific trackId of the specific song
	//string 'link' : encoded link with specific affiliate tags already on
	//string 'artworkUrl' : link to image of artwork of the song
	//string 'previewUrl' : link to the mp4 30s-60s preview of the song
	//string 'amazonLink' : encoded link to Amazon product with specific affiliate tags in
	//string 'message' : either OK, or NOT FOUND
	
	/*
	$itunesArr = searchItunesSong($keywords);
	if (!$itunesArr['link']) return array('message'=>'NOT FOUND');
	else{
		$url = searchAmazonSong($itunesArr['title']." ".$itunesArr['artist']);
		$itunesArr['message'] = 'OK';
		$itunesArr['amazonLink']=$url;
		return $itunesArr;
	}
	*/
	$lastfm_results = getLastFmSearchResults($keywords);
	$title = $lastfm_results[0]['name'];
	$artist = $lastfm_results[0]['artist'];
	$posterUrl = $lastfm_results[0]['image'][1];
	$lastFmUrl = $lastfm_results[0]['url'];
	//$xml = getXMLresponse('DigitalMusic',$keywords);
	//$title = getTitle($xml);
	//$artist = getArtist($xml);
	
	$title = trim(preg_replace('/\s*\([^)]*\)/', '', $title)); //remove all paren itmes
	$title = trim(preg_replace('/\s*\[[^)]*\]/', '', $title));	//remove all bracekt items
	$artist = trim(preg_replace('/\s*\([^)]*\)/', '', $artist));	//remove all paren items
	$artist = trim(preg_replace('/\s*\[[^)]*\]/', '', $artist));	//remove all bracket items
	if (!$artist){
		return array('message'=>'NOT FOUND');
	} 
	else{
	
	$itunesArr = searchItunesSong("$title $artist");
	if (!$itunesArr['link']) return array('message'=>'NOT FOUND');
	else{
		//$url = searchAmazonSong($itunesArr['title']." ".$itunesArr['artist']);
		$url = searchAmazonSong($title." ".$artist);
		if ($itunesArr['previewUrl']==''){
			$lastFmPreview = getPreviewUrl($lastFmUrl);
			$itunesArr['previewUrl'] = $lastFmPreview;
		}
		$itunesArr['title'] = $title;
		$itunesArr['artist'] = $artist;
		$itunesArr['message'] = 'OK';
		$itunesArr['amazonLink']=$url;
		$itunesArr['artworkUrl']=$posterUrl;
		return $itunesArr;
	}
	}
}
function searchItunesAndAmazon($title,$artist,$image,$page){
	$title = trim(preg_replace('/\s*\([^)]*\)/', '', $title)); //remove all paren itmes
	$title = trim(preg_replace('/\s*\[[^)]*\]/', '', $title));	//remove all bracekt items
	$artist = trim(preg_replace('/\s*\([^)]*\)/', '', $artist));	//remove all paren items
	$artist = trim(preg_replace('/\s*\[[^)]*\]/', '', $artist));	//remove all bracket items
	if (!$artist){
		return array('message'=>'NOT FOUND');
	} 
	else{
	
	$itunesArr = searchItunesSong("$title $artist");
	if (!$itunesArr['link']) return array('message'=>'NOT FOUND');
	else{
		//$url = searchAmazonSong($itunesArr['title']." ".$itunesArr['artist']);
		$url = searchAmazonSong($title." ".$artist);
		if ($itunesArr['previewUrl']==''){
			$lastFmPreview = getPreviewUrl($page);
			$itunesArr['previewUrl'] = $lastFmPreview;
		}
		$itunesArr['title'] = $title;
		$itunesArr['artist'] = $artist;
		$itunesArr['message'] = 'OK';
		$itunesArr['amazonLink']=$url;
		$itunesArr['artworkUrl']=$image;
		return $itunesArr;
	}
	}	
}

function searchSongById($itunesId){
	$itunesArr = searchItunesById($itunesId);
	if (!$itunesArr['link']) return array('message'=>'NOT FOUND');
	else{
		$url = searchAmazonSong($itunesArr['title']." ".$itunesArr['artist']);
		$itunesArr['message'] = 'OK';
		$itunesArr['amazonLink']=$url;
		return $itunesArr;		
	}
}

function addSong($user_id,$songArr){
	//@param int $user_id is the user_id for the song to be added to
	//@param array $songArr should include the following keys:
	//string 'title' : title of the song
	//string 'artist' : artist of the song
	//int 'trackId' : iTunes specific trackId of the specific song
	//string 'link' : encoded link with specific affiliate tags already on
	//string 'artworkUrl' : link to image of artwork of the song
	//string 'previewUrl' : link to the mp4 30s-60s preview of the song
	//string 'amazonLink' : encoded link to Amazon product with specific affiliate tags in
	$trackId = $songArr['trackId'];
	mysqlConnect();
	$title = mysql_real_escape_string($songArr['title']);
	$res = mysql_query("SELECT id FROM songs WHERE (itunes_trackId = '$trackId' AND title='$title')");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		$song_id = $row['id'];
	}
	else{
		//$title = mysql_real_escape_string($songArr['title']);
		$songArr = escapeArrValues($songArr);
		mysql_query("INSERT INTO songs (itunes_trackId,title,artist,itunes_url,amazon_url,preview_url,artwork_url,album_name,genre)
		VALUES('$trackId','{$songArr['title']}','{$songArr['artist']}','{$songArr['link']}','{$songArr['amazonLink']}','{$songArr['previewUrl']}'
		,'{$songArr['artworkUrl']}','{$songArr['albumName']}','{$songArr['genre']}')");
		$song_id = mysql_insert_id();
		//$res = mysql_query("SELECT id FROM songs WHERE itunes_trackId = '$trackId'");
		//$row = mysql_fetch_array($res);
		//$song_id = $row['id'];
		//$song_id=$trackId;
	}
	$song_id = mysql_real_escape_string($song_id);
	$user_id = mysql_real_escape_string($user_id);
	mysql_query("INSERT INTO listed_songs (song_id,user_id) VALUES('$song_id','$user_id')");
}

function escapeArrValues($array){
	foreach($array as $key=>$value){
		$array[$key]=mysql_real_escape_string($value);
	}
	return $array;
}
function deleteSong($listed_songs_id){
	//@param int $user_id is the user that is to be affected
	//@param int $song_id is song_id from the table
	mysqlConnect();
	//$song_id = getSongId($trackId);
	$listed_songs_id = mysql_escape_string($listed_songs_id);
	mysql_query("UPDATE listed_songs SET is_deleted='1' WHERE id='$listed_songs_id'");
}

function getSongId($trackId){
	//@param int $trackId is the iTunes trackId
	//returns int song_id based off the dataBase
	mysqlConnect();
	$res = mysql_query("SELECT id FROM songs WHERE itunes_trackId='$trackId'");
	$row = mysql_fetch_array($res);
	return $row['id'];
}

function getUserById($user_id){
	//@param int $user_id : id of the user to be found
	mysqlConnect();
	$res = mysql_query("SELECT username FROM users WHERE id='$user_id'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		return $row['username'];
	}
}	

function getUserByNumber($phoneNumber){
	//@param int $phoneNumber - phone number of user to be found
	//returns array with the following keys:
	//string 'username'
	//int 'user_id'
	mysqlConnect();
	$res = mysql_query("SELECT id,username FROM users WHERE phone_number='$phoneNumber'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		$userArr  = array('username'=>$row['username'],'user_id'=>$row['id']);
	}
	else $userArr = array();
	return $userArr;
}

function getUserId($username){
	mysqlConnect();
	$res = mysql_query("SELECT id FROM users WHERE username='$username'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		return intval($row['id']);
	}
	else{
		return 0;
	}
}

function getTweet($url,$text){
//add url with
	$text = fixQuotes($text);
	$script = "<a href='http://twitter.com/share' onclick=\"return tweet_click('$url','$text')\">tweet</a>";
	$html = "<script src='http://platform.twitter.com/widgets.js' type='text/javascript'></script>
<a href='http://twitter.com/share' class='twitter-share-button' data-related='tasteplug' data-via='tasteplug' data-url='$url' data-count='none' data-text='$text'>Tweet</a>";
	return $script;
}

function getPlugs($username){
	$curUsername = $_SESSION['username'];
	$isOwner = false;
	if ($curUsername == $username){
		$isOwner = true;
	}
	$cur_user_id = $_SESSION['id'];
	$html = '';
	mysqlConnect();
	$song_ids = array();
	$user_id = getUserId($username);
	$res = mysql_query("SELECT id,song_id,time_stamp FROM listed_songs WHERE (user_id='$user_id' AND is_deleted='0') ORDER BY id DESC");
	if (mysql_num_rows($res)){
		while ($row = mysql_fetch_array($res)){
			$startDiv = "<div class = 'plug' id='plug_{$row['id']}'>";
			$song_id = intval($row['song_id']);
			if ($song_id)
			//if ($song_id && checkSongExists($song_id))
				$html = $html.$startDiv.makePlug($row['id'],$row['song_id'],$row['time_stamp'],$username,$isOwner,$cur_user_id)."</div>";
		}
	}
	$twitter_script= "<script>function tweet_click(u,t) 
	{

	v='tasteplug';
	r='tasteplug';
	ref = 'http://twitter.com/share?related='+encodeURIComponent(r)+'&url='+encodeURIComponent(u)+'&text='+encodeURIComponent(t)+'&via='+encodeURIComponent(v);
	window.open(ref,'sharer','toolbar=0,status=0,width=535,height=355');
	return false;
	}
	</script>";
	return $twitter_script.$html;
}

function checkSongExists($song_id){
	mysqlConnect();
	$res = mysql_query("SELECT id FROM songs WHERE id='$song_id'");
	if (mysql_num_rows($res)) return true;
	else return false;
}
function makePlug($list_id,$song_id,$time_stamp='',$username='',$isOwner = false,$current_user_id = 0){
	$html = '';
	mysqlConnect();
	if ($time_stamp!='')$time_stamp = convertTime($time_stamp);
	
	$res = mysql_query("SELECT * FROM songs WHERE id='$song_id'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
			$title = fixQuotes($row['title']);
			$artist = fixQuotes($row['artist']);
			//$title_slashes = addslashes($title);
			//$artist_slashes = addslashes($artist);
			$tweetHtml = getTweet("http://tasteplug.com/$username","A song to listen to: $title by $artist #nowplaying");
			if ($isOwner) $ownerTool = "| <a href='javascript:deletePlug($list_id);'>delete</a>";
			else if (!$_SESSION['id']) $ownerTool = "";	//if user is not logged in
			else {
				//$ownerTool = "| <a href='javascript:addToList($list_id,$current_user_id);'>add to list</a>";
				$ownerTool = getAddedTool($current_user_id,getUserId($username),$song_id,$list_id);
				//$ownerTool = getAddedTool(1,3,128,200);
			}
		$user_tool = "<span class='user-tool'>$tweetHtml $ownerTool</span>";
		$timeStamp = "<span class='timestamp'>$time_stamp</span>";
		$rightFloater = "<div class='right-floater'>$timeStamp$user_tool</div>";
			$imageUrl = $row['artwork_url'];
			if ($imageUrl=='') $imageUrl = '/images/album-default.png';
		$image_poster = "<img class='album-art' src='$imageUrl' alt='Album Artwork for $title by $artist'>";
			$shortened_title = shortenString($title,30);
		$song = "<div class='song'>$shortened_title</div>";
			$shortened_artist = shortenString($artist,60);
		//$artist_html = "performed by <div class='artist'>$shortened_artist</div>";
		$artist_html = "<div class='artist'>$shortened_artist</div>";
		//if ($row['album_name']) $album = "on <div class='album'>{$row['album_name']}</div>";
		//else $album = '';
		$album = '';
		//$audio = "<audio id='preview_$list_id' class='preview' controls='controls' preload='none'><source src='{$row['preview_url']}' type='audio/mp4'>Browser does not support this audio format</audio>";
		$preview = $row['preview_url'];
		$audio = "<div id='play_$list_id' class='preview'><a href=\"javascript:playPlug('$list_id', '$preview')\"><img src = 'images/play_button_18.png' class='play-button' alt='play button'/> Play</a></div>";	     
	

			$purchaseLogos = getPurchaseIcons($row['itunes_url'],$row['amazon_url'],$title,$artist);
		$purchase_links = "<ul class='purchase-links'>
					<li> <a href='#'>Download</a>
						<ul>
							<li> $purchaseLogos</li>
						</ul>
					</li>
					
				</ul>";
		$superBottom = "<div class='super-bottom'>$purchase_links$audio</div>";
		$html = $rightFloater.$image_poster."$song<br>$artist_html $album".$superBottom;
	}
	
	return $html;
}

function fixQuotes($string){
	$string = str_replace("'",'&#39;',$string);
	return $string;
}

function getPurchaseIcons($itunes_link,$amazon_link,$title,$artist){
	$html = "<a href='$itunes_link' target='_blank' alt='Download $title by $artist from iTunes'><img src='images/itunes-transparent.png' alt='iTunes download logo'/></a>";
	if ($amazon_link) $html = $html . "<a href='$amazon_link' target='_blank' alt='Download $title by $artist from Amazon'><img src='images/amazon-transparent.png' alt='Amazon download logo'/></a>";
	return "<span id='link'>".$html.'</span>';
}
function convertTime($sqlTime){
	return date("g:i a M j",strtotime($sqlTime));
}

function shortenString($string, $length){
	if (strlen($string)>$length) $string = substr($string,0,$length)."...";
	return $string;
}

function getAddedTool($cur_user_id,$owner_id,$song_id,$list_id){
	mysqlConnect();
	$res = mysql_query("SELECT id FROM listed_songs WHERE (song_id='$song_id' AND user_id='$cur_user_id' AND added_from='$owner_id' AND is_deleted='0')");
	if (mysql_num_rows($res)){
		return "| added";
	}
	else return "| <a href='javascript:addToList($list_id,$cur_user_id);'>add to list</a>";
}

function isPasswordCreated($username){
	mysqlConnect();
	$res = mysql_query("SELECT password FROM users WHERE username='$username'");
	if (mysql_num_rows($res)){
		$row = mysql_fetch_array($res);
		if ($row['password']=='') return false;
		else return true;
	}
	else{
		return false;
	}
}
?>