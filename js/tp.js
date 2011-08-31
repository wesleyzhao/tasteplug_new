function validateUsername(username){
	if(username.length > 0){
		$.get("/php-scripts/check_username.php?username="+username, function(data){            
			if(data == "1" || data == 1){
				$(".username").html("<img src='/images/check.png' class='check'>Username<br>"); 
				$("#username-error").html(""); 
	           	return true;
				
			}                                                                                   
			else{
				$(".username").html("<img src='/images/x.png' alt='That username is already taken.' class='check'>Username<br>"); 
				$("#username-error").html("Sorry, that username is taken."); 
			 
			}
		}); 
	}else{
		$(".username").html("<img src='/images/x.png' alt='Please enter a username.' class='check'>Username<br>"); 
		$("#username-error").html("Please enter a username.");

	}   
	return false;
} 

function validatePassword(password){
	if(password.length > 3){
		$(".password").html("<img src='/images/check.png' class='check'>Password<br>");
		$("#password-error").html("");
	    return true;
	}                                                                                        
	else{
		$(".password").html("<img src='/images/x.png' class='check'>Password<br>");
		$("#password-error").html("Sorry, your password must be longer than 3 characters.");  
	}                                                        
	return false;
}                

function validatePasswordsMatch(password, password2){
	if(password == password2){
		$(".password2").html("<img src='/images/check.png' class='check'>Confirm password<br>");
		$("#password2-error").html("");  
		return true;
	}
	else{
		$(".password2").html("<img src='/images/x.png' class='check'>Confirm password<br>");
		$("#password2-error").html("Sorry, your passwords must match.");   
	}                    
	return false;
}     

function validatePhone(phone){
	if(phone.length == 10 && phone.match(/^\d+$/)){
		$.get("/php-scripts/check_phone.php?phone="+phone, function(data){            
			if(data == "1" || data == 1){
		    	$(".phone").html("<img src='/images/check.png' class='check'>Phone<br>");
				$("#name-error").html("");
      			return true; 
			}else{
				$(".phone").html("<img src='/images/x.png' class='check'>Phone<br>");
				$("#phone-error").html("Phone number is taken.");
			}
		});
	}  
	else{
	   	$(".phone").html("<img src='/images/x.png' class='check'>Phone<br>");
		$("#phone-error").html("Phone number is not properly formatted.");  
	 
	}
	return false;
}

function putCheckmark(div){
	$(document).ready(function(){ 
		if(div == "username"){
			var username = $("#username").val();
			validateUsername(username);   
		}   
		else if(div == "password"){
			var password = $("#password").val();
			validatePassword(password);
			
		}   
		else if(div == "password2"){ 
			var password = $("#password").val(); 
			var password2 = $("#password2").val();
			validatePasswordsMatch(password, password2);
			       
		}   
		else if(div == "phone"){
			var phone = $("#phone").val();
			validatePhone(phone);
		}
	});
} 

$(document).ready(function(){
	$("#register-phone").bind('keypress', function(event){ 
		//alert("keycode: " + event.keyCode);   
		var numbers = $("#register-phone").val();
		if(event.keyCode != 8 && event.charCode >= 48 && event.charCode <= 57){  
			if(numbers.length == 3 || numbers.length == 7){     
				numbers = numbers + "-";            
			}else if(numbers.length == 12){
				numbers = numbers.substring(0, numbers.length-1);  
			} 
		}
	   	$("#register-phone").val(numbers);   
	}); 
	$("#register-phone").bind('keyup', function(event){  
	   	var numbers = $("#register-phone").val();                        
	   	if(numbers.match(/^[^\d]+$/)){
			numbers = "";
	   	}else if(numbers.substring(numbers.length-1, numbers.length).match(/[^\d]/)){
			numbers = numbers.substring(0, numbers.length-1);
		}
	   	$("#register-phone").val(numbers);   
	});
});