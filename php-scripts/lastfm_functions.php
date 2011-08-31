<?php

function getLastFmXMLResponse($keywords,$limit=5){
	$api_key = "2a7811ba2c53c4f496b16efff95e25ff";
	$url = "http://ws.audioscrobbler.com/2.0/?method=track.search&limit=$limit&api_key=$api_key&track=$keywords";
	$response = simplexml_load_file($url);
	return $response;
}

function getLastFmSearchResults($keywords, $limit = 5){
	$xml = getLastFmXMLResponse($keywords,$limit);
	$results = array();
	foreach ($xml->results->trackmatches->track as $track){
		$images = array();
		foreach ($track->image as $image){
			$images[] = $image;
		}
		$results[] = array('name'=>((string)$track->name),'artist'=>((string)$track->artist),'url'=>((string)$track->url),
		'image'=>($images));
	}
	
	return $results;
}

function getPreviewUrl($pageUrl){
	$source = file_get_contents($pageUrl);
	$regex = '/"([^"]+\.mp3)"/';
	$matched = preg_match($regex,$source,$matches);
	if ($matched){
		return $matches[1];
	}
	else return '';
}

?>