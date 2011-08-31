<?php
require_once('lastfm_functions.php');

$keywords = $_GET['term'];


$xml = getLastFmXMLResponse($keywords);
$results = getLastFmSearchResults($keywords);
/*
foreach($xml->results->trackmatches->track as $track){
	echo $track->name;
	echo "<br>";
}
*/

$url = "http://www.last.fm/music/Katy+Perry/_/E.T.";
$match = getPreviewUrl($url);
echo "matched: $match <br>";

print_r($results);

echo "<br>";
$result = $results[0];
echo $result['image'][1];


?>