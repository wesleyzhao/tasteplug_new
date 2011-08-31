<?php

function searchItunesSong($keywords){
	//string $keywords represents the search terms given to iTunes search API
	//returns an array of the following keys
	//string 'title' : title of the song
	//string 'artist' : artist of the song
	//int 'trackId' : iTunes specific trackId of the specific song
	//string 'link' : encoded link with specific affiliate tags already on
	//string 'artworkUrl' : link to image of artwork of the song
	//string 'previewUrl' : link to the mp4 30s-60s preview of the song
	$results = getItunesResults($keywords);
	$result = $results[0];
	$title = $result['trackName'];
	$artist = $result['artistName'];
	$trackId = $result['trackId'];
	if (!$result['trackViewUrl']) $result['trackViewUrl']="http://ax.itunes.apple.com/WebObjects/MZSearch.woa/wa/search?term=$keywords";
	$link = makeItunesLink($result['trackViewUrl']);
	$artworkUrl = $result['artworkUrl60'];
	$previewUrl = $result['previewUrl'];
	$albumName = $result['collectionName'];
	$genre = $result['primaryGenreName'];
	//$price = $result['trackPrice'];
	$searchResult = array('title'=>$title,'artist'=>$artist,'trackId'=>$trackId,'link'=>$link
						,'artworkUrl'=>$artworkUrl,'previewUrl'=>$previewUrl,'albumName'=>$albumName
						,'genre'=>$genre);
	
	return $searchResult;	
}

function searchItunesById($id){
	$results = getItunesResultsById($id);
	$result = $result[0];
	$title = $result['trackName'];
	$artist = $result['artistName'];
	$trackId = $result['trackId'];
	$link = makeItunesLink($result['trackViewUrl']);
	$artworkUrl = $result['artworkUrl60'];
	$previewUrl = $result['previewUrl'];
	$albumName = $result['collectionName'];
	$genre = $result['primaryGenreName'];
	//$price = $result['trackPrice'];
	$searchResult = array('title'=>$title,'artist'=>$artist,'trackId'=>$trackId,'link'=>$link
						,'artworkUrl'=>$artworkUrl,'previewUrl'=>$previewUrl,'albumName'=>$albumName
						,'genre'=>$genre);
	
	return $searchResult;	
}

function getItunesResults($keywords,$limit=5){
	$term = urlencode($keywords);
	$url = "http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?media=music&entity=song&limit=$limit&term=$term";
	$json = file_get_contents($url);
	$arr = json_decode($json,true);
	$results = $arr['results'];
	return $results;
}

function getItunesResultsById($id){
	$url = "http://http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsLookup?id=$id";
	$json = file_get_contents($url);
	$arr = json_decode($json,true);
	$results = $arr['results'];
	return $results;
}
function makeItunesLink($url){
	//string $url is the unique url to the song/track/etc
	//returns string $url of double encoded link that adds the partnerId for commission tracking
	$wrapper = 'http://click.linksynergy.com/fs-bin/stat?id=m90ulZ6eSFI&offerid=146261&type=3&subid=0&tmpid=1826&RD_PARM1=';
	$partnerId='30';
	$siteID = '2687633';
	$trackUrl = urlencode($url."&partnerId=$partnerId");
	$trackUrl = $wrapper.urlencode($trackUrl);		//requires double encoding
	return $trackUrl;
}


?>