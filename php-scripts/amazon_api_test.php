<?php
require('amazon_functions.php');

$keywords = $_GET['keywords'];

echo searchAmazonSong($keywords);

	$xml = getXMLresponse('DigitalMusic',$keywords);
	$title = getTitle($xml);
	$artist = getArtist($xml);
	$title_trim = trim(preg_replace('/\s*\([^)]*\)/', '', $title));
	$title_trim = trim(preg_replace('/\s*\[[^)]*\]/', '', $title_trim));
	$artist_trim = trim(preg_replace('/\s*\([^)]*\)/', '', $artist));
	echo "<br>Title: ".getTitle($xml)." stripped: $title_trim";
	echo "<br>Artist: ".getArtist($xml)." stripped: $artist_trim";
	echo "<br>";
	print_r($xml);
	$url = getUPC($xml);
	echo "<br>UPC: ". $url;
?>