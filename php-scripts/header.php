<html>
<head>
<?php
	session_start();
	$username = $_GET['username'];
	$title = $_GET['page_title'];
?>
	<title><?= $title ?></title>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
	<script type="text/javascript"
	 src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
	</script>     
	<script type="text/javascript" src="/js/tp.js"></script>
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
				<a href="/login">log in</a> or 
				<a href="/">get your own</a>.   
				<?php
				}
				
				?>
			</div>
			
		</div>