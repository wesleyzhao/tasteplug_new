<?php
require_once("php-scripts/header.php");                 
?>
	<br><br><br><br><br>
	<div class='shadow'>
	<div id="register-form-container" >
   	<span class='login-title'>Sign Up</span><br><br> 
	<form action="/php-scripts/register_scripts.php" method="post">                   
	<input class='login-field' type="text" id="register-phone" name="phone" value="phone number" onfocus="clearText(this)" onblur="clearText(this)" onblur="putCheckmark('phone')" />    <br>
	<div id="phone-error"></div><br> 
	<input class='login-field' type="text" id="username" name="username" value="username" onfocus="clearText(this)" onblur="clearText(this)" onblur="putCheckmark('username')"/> <br> 
	<div id="username-error"></div><br> 
	Password:<br><input class='login-field' type="password" id="password" name="password" onfocus="clearText(this)" onblur="clearText(this)" onblur="putCheckmark('password')"/> <br>  
	<div id="password-error"></div><br> 
	Confirm password:<br><input class='login-field' type="password" id="password2" name="password2" onfocus="clearText(this)" onblur="clearText(this)" onblur="putCheckmark('password2')"/>  
	<div id="password2-error"></div><br> 
	<input type="hidden" name="redirect" value="register">
	<input type="submit" value="Create your page!" id='sign-up' />
</form>            
</body>
</html>
	</form>
	</div>
	</div>
</body>
<script type="text/javascript">
 
	function clearText(field){
    	if (field.defaultValue == field.value) field.value = '';
    	else if (field.value == '') field.value = field.defaultValue;
	}
	</script>
</html>


  