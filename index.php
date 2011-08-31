<html>
<head>
<?php
require_once('php-scripts/profile_functions.php');
	$username = $_GET['username'];
?>
	<title>Tasteplug - a simple SMS based to-do list for music </title>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
	<script type="text/javascript"
	 src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
	</script>
	<script type="text/javascript" src="js/tp.js"></script> 
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-1108031-20']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>  
</head>

<body>
	
	<div id="wrapper">

		<div id="header">
			<a href="/"><img id="logo" src="/mockup/images/header-new.png"></a>
			<div class="top-links">
				<?php
				if($_SESSION['logged_in'] == 1 || $_SESSION['logged_in'] == "1"){ 
				
				   print "Welcome back, ".$_SESSION['username'].". View your <a href='/{$_SESSION['username']}'>profile</a>, <a href='settings' alt='Settings | Tasteplug - a simple SMS based way to keep track of a music to-do list'>settings</a>, or <a href='php-scripts/logout.php'>logout.</a>"; 
				}
				else{   
				?>
				A useful to-do list for your music.
				<a href="/login">log in</a>.   
				<?php
				}
				
				?>
			</div>
			
		</div>
		
		<div id="welcome">
			The simplest way to keep track of music you want to hear.
		</div>
		
		<div id="explanation-container">
			<div id="column-right"> 
				<div id="text-right">
					<img id="text-right" src="images/heyjude.png">
				</div>  
				<div id="text-left">
					<img id="text-left" src="images/text.png">
				</div> 
				<img src="images/arrow.png" id="arrow">
				<img id="sample-plug" src="images/sample.png"/>
			</div>
			<div id="column-left">
				<div id="preview-text">  
					text any song<br><b>to (415) 968-5167</b>
				</div>            
				<div id="preview-text">download it later</div>
			</div>
		</div>

		
		<!-- 
<div id="preview-container">
			<div id="step"><div id="preview-text">Text us your songs at <b>(415) 968-5167</b>.</div><img src="/images/iphone_screenshot.png" class="screenshot"></div>
			<div id="step"><div id="preview-text">Or search and add them online.</div><img src="/images/song_selection.png" class="screenshot"></div>
			<div id="step"><div id="preview-text">And we'll save them for you.</div><img src="/images/profile_screenshot.png" class="screenshot"></div>
		</div>
 -->
		 <?php if(!$_SESSION['username']){?>
		<div id="login-container">
		
			It's free and easy, and we will never spam or give out your information.
			<?php
			$message = $_GET['message'];
			print "<div id='message'>$message</div>";
			?>
			<form action="/php-scripts/register_scripts.php" method="post">
			
			<!-- <input class="login-field" type="text" name="email" id="register-email" value="email address"  onFocus="clearText(this)" onBlur="clearText(this)" /> --> 
			<input class="login-field" type="text" name="username" id="register-username" value="username" onFocus="clearText(this)" onBlur="clearText(this)"/> 
			<input class="login-field" type="text" name="phone" id="register-phone" value="phone number" onFocus="clearText(this)" onBlur="clearText(this)"/> 
			<input class="login-field" type="password" name="password" id="register-password" value="password" onFocus="clearText(this)" onBlur="clearText(this)"/>
			
			  
			 
			<input id="sign-up" type="submit" value="let's see my beautiful new page » " />
			
			</form>  
			</div> 
			<?php 
			
			}
			
			?>
			<!-- 
<h3>If you already have an account login below:</h3>
			<?php
	  
			?>                                       
			
			<form action="/php-scripts/login-user.php" method="post">  
			Username: <input type="text" name="username" />   <br>
			Password: <input type="password" name="password" /> <br>
			<input type="submit" value="Login" />              <br>
			</form> 
 -->            
			
		
		<br><br><br>
	<?php require_once('php-scripts/footer.php');?>
			
	</div>

</body>
<script type="text/javascript">

	function clearText(field){
    	if (field.defaultValue == field.value) field.value = '';
    	else if (field.value == '') field.value = field.defaultValue;
	}
		
</script> 

</html>