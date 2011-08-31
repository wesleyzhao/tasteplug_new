<!DOCTYPE html>
<html lang="en">
<?php
	require_once("php-scripts/handler_functions.php"); 
	require_once('php-scripts/mysql_connect.php');
	$allURLS=array();		//will store all custom_urls
	
 $customURL = end(explode('/', $_SERVER['REQUEST_URI']));
 $request = $_SERVER['REQUEST_URI'];
 $exploded = explode('/',$request);	//element 0 should be tasteplug.com
 $customURL = strtolower($exploded[1]);
 $getPos = strrpos($customURL,'?');
 if ($getPos) {
	$customURL = substr($customURL,0,$getPos);
 }
 
 

		mysqlConnect();
		$result=mysql_query("SELECT username FROM users");
		while ($row = mysql_fetch_array($result)){
			if ($row['username']!='') $allURLS[] = $row['username'];
		}
	if (in_array($customURL,$allURLS)){
	//if the customURL typed exists in the database
	$_GET['username'] = $customURL;
	$_GET['page_title'] = "$customURL's Tasteplug | A simple SMS based to-do list for music";
		if (count($exploded)>2){
		//if the url exists, and there is call for something else (e.g. tasteplug.com/wesley/foo)
			if (count($exploded)==3){
				if ($exploded[2]=='list'){
					echo loadList();
				}
				else if (strlen($exploded[2])==0){
					require_once('page-templates/profile.php');
				}
				else {
					echo notFound();
				}
			}
			else{
				echo notFound();
			}
		}
		else{
		//if there is just one thing after / e.g. tasteplug.com/foo
			require_once('page-templates/profile.php');
		}
	}
	else if ($customURL == 'login'){
		if ($getPos) $getStr = substr($customURL,$getPos);
		$_GET['page_title'] = "Login | Tasteplug - A simple SMS based to-do list for music";
		require_once("page-templates/login.php$getStr");
	}
	else if ($customURL == 'register'){
		if ($getPos) $getStr = substr($customURL,$getPos);
		$_GET['page_title'] = "Register | Tasteplug - A simple SMS based to-do list for music";
		require_once("page-templates/register.php$getStr");
	}
	else if ($customURL == 'settings'){
		if ($getPos) $getStr = substr($customURL,$getPos);
		$_GET['page_title'] = "Settings | Tasteplug - A simple SMS based to-do list for music";
		require_once("page-templates/settings.php$getStr");
	}
	else if ($customURL == 'about'){
		$_GET['page_title'] = "About | Tasteplug - A simple SMS based to-do list for music";
		require_once('page-templates/about.php');
	}
	else if ($customURL == 'instructions'){
		$_GET['page_title'] = "Instructions | Tasteplug - A simple SMS based to-do list for music";
		require_once('page-templates/instructions.php');
	}
	else{
	//if url does not exist
		echo notFound();
	}
		
?>