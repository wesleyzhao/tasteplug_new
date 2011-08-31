<?php
function getTextTemp($dir){
	$temp =fopen($dir,'r');
	$data = fread($temp,filesize("$dir"));
		fclose($temp);
	
	return $data;
	} 
function customPage(){
	$html = getTextTemp("page-templates/profile.php");
	return $html;
}

function notFound(){
	$html = getTextTemp("not_found.php");
	return $html;
}

function loadList(){
	$html = getTextTemp("/page-templates/list_template.php");
	$html = '';
	return $html;
}

 
?>