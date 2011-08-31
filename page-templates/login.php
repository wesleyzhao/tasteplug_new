<?php
require_once("php-scripts/header.php");                 
$message = $_GET['message'];
?>
	<br><br><br><br><br>
	<div class='shadow'>
	<div id="login-form-container" >
   	<span class='login-title'>Login</span>
	<div id="message"><?php print $message ?></div>
	

	<form action="/php-scripts/login-user.php" method="post">
	<input class='login-field' type="text" onfocus="clearText(this)" onblur="clearText(this)" value= "username" name="username" />         <br>    <br>
	<input class='login-field' type="password" onfocus="clearText(this)" onblur="clearText(this)" value="password" name="password" />         <br>    <br>
	
	<input id='sign-up' type="submit" value="Login"/>
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