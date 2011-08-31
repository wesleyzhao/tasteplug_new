<?php
require_once('Amazon.php');

function searchAmazonSong($keywords){
	//@param string $keywords is the keywords to be searched in the Amazon Product API
	//returns string URL of link to Amazon product
	$xml = getXMLresponse('DigitalMusic',$keywords);
	$url = getSearchURL($xml);

	if ($url == '') {
		$keywords = urlencode($keywords);
		$affiliateTag='tasteplug07-20';
		$searchUrl = "http://www.amazon.com/gp/search?ie=UTF8&keywords=$keywords&tag=$affiliateTag&index=digital-music";	
		$url = $searchUrl;
	}
	return $url;
}


function getXMLresponse($SearchIndex,$Keywords)
	//@param string $SearchIndex is the Amazon index to be searched in
	//@param string $Keywords is the keywords to be searched in Amazon
	//returns an XML response to the query
{
	$params = array(
		"region"=>"com",
		"Operation"=>"ItemSearch",
		"SearchIndex"=>$SearchIndex,
		//"SearchIndex"=>"Books", DigitalMusic
		'ResponseGroup'=>'Medium',
		//"Keywords"=>"'".$Keywords."'"
		"Keywords"=>$Keywords
	);
	$Amazon=new Amazon();
	$queryUrl = $Amazon->getSignedUrl($params);
	//mail('wesley.zhao@gmail.com',queryUrl,"$queryUrl");
	$response = simplexml_load_file($queryUrl);
	//$str = simplexml_load_string($queryUrl);
	//mail('wesley.zhao@gmail.com',queryXMLstr,"$str");
	return $response;
}

function getPrice($xmlResponse){
	if($xmlResponse->Items->TotalResults >0){
		//have at least one response
		$url = $xmlResponse->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice;
	}
	else{
		//no response
		$url='';
	}
	return $url;
	
}

function getSearchURL($xmlResponse){
	if($xmlResponse->Items->TotalResults >0){
		//have at least one response
		$url = $xmlResponse->Items->Item->DetailPageURL;
	}
	else{
		//no response
		$url='';
	}
	return $url;
}

function getUPC($xmlResponse){
	if($xmlResponse->Items->TotalResults >0){
		//have at least one response
		$url = $xmlResponse->Items->Item->ItemAttributes->UPC;
	}
	else{
		//no response
		$url='';
	}
	return $url;
}

function getArtist($xmlResponse){
	if($xmlResponse->Items->TotalResults >0){
		//have at least one response
		$url = $xmlResponse->Items->Item->ItemAttributes->Creator;
		//$url = $xmlResponse->Items->Item->ItemAttributes->Artist;
	}
	else{
		//no response
		$url='';
	}
	return $url;
}

function getTitle($xmlResponse){
	if($xmlResponse->Items->TotalResults >0){
		//have at least one response
		$title = $xmlResponse->Items->Item->ItemAttributes->Title;
	}
	else{
		//no response
		$title='not found';
	}
	return $title;
}

?>