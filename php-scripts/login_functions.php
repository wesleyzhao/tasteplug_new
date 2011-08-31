<?php

function loginUser($id, $phone, $username, $current_city, $password){
	$sql = "SELECT `id` FROM `users` WHERE `phone_number` = '$phone' AND `password` = '$password'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)){
		//log this bitch in
		$_SESSION['logged_in'] = 1;
		$_SESSION['id'] = $id;
		$_SESSION['phone_number'] = substr($phone, 2);
		$_SESSION['current_city'] = $current_city;
		$_SESSION['username'] = $username; 
		if($username != 'null'){
			header("Location: /$username");    
		}  
		
	}else{
		header("Location: http://tasteplug.com/login?message=Sorry we couldn't log you in.");
	}
}  

function validateUsername($username){  
	$sql = "SELECT `id` FROM `users` WHERE `username` = '$username'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)) return false;
	if(strlen($username) > 0){
		return true;
	} 
	        
	return false;
}  

function phoneNoPassword($username,$phone_number=''){
	mysqlConnect();
	if ($phone_number!=''){
		$res = mysql_query("SELECT password,phone_number FROM users WHERE phone_number='$phone_number'");
		if (mysql_num_rows($res)){
			$row = mysql_fetch_array($res);
			if ($row['password'] =='') return true;
			else return false;
		}
		else return false;
	}
	else{
		$res = mysql_query("SELECT password,phone_number FROM users WHERE username='$username'");
		if (mysql_num_rows($res)){
			$row = mysql_fetch_array($res);
			if ($row['phone_number']!='' && $row['password']=='') return true;
			else return false;
		}
		else return false;
	}
}

function validatePassword($password){
	if(strlen($password) > 0){
		return true;
	}               
	return false;
}                

function validatePhone($phone){
	if(strlen($phone) == 12 && preg_match("/^\+[0-9]+$/", $phone)){                    
		return true;
	}               
	return false;
}

function saltify($password){
   return md5($password.'2343jdjh53jbdsfkj49djmxnckwjehr3scsfda');
}

?>