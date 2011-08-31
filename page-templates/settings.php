<?php
require_once("php-scripts/header.php");  
$phone = $_SESSION['phone_number'];
$phone = substr($phone, 0, 3)."-".substr($phone, 3, 3)."-".substr($phone, 6,9);   
$message = $_GET['message'];            
?>
	<br><br><br><br><br>
	<div class='shadow'>
	<div id="register-form-container" >
   	<span class='login-title'>Account Settings</span>
	<div id="message"><?= $message ?></div>

	<form action="/php-scripts/settings_functions.php" method="post">                   
	<div class="phone"></div><input class='login-field' type="text" id="register-phone" name="phone_number" onfocus="clearText(this)" onblur="clearText(this)" value="<?= $phone ?>" /> <br> 
	<input class='login-field' type="text" id="username" name="username" onfocus="clearText(this)" onblur="clearText(this)" value="<?= $_SESSION['username'] ?>" /> <br><br>      
	Old Password:<br><input class='login-field' type="password" id="password" name="old_password" onfocus="clearText(this)" onblur="clearText(this)" /> <br><br>   
	New Password:<br><input class='login-field' type="password" id="password" name="new_password" onfocus="clearText(this)" onblur="clearText(this)" />  <br>
	<input id='sign-up' type="submit" value="Update your settings" />
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
