<html>
<head>
<?php
require_once('php-scripts/profile_functions.php');
	$username = $_GET['username'];
?>
	<title><?=$username?>'s tasteplug</title>
	<link rel="stylesheet" href="style/style.css" type="text/css"/>
	<script type="text/javascript"
	 src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
	</script>                                              
</head>
<body>

	<div id="wrapper">
	
		<div id="header">
			<a href="/"><img id="logo" src="/mockup/images/header-new.png"></a>
			<div class="top-links">
				A useful to-do list for your music.
				<a href="#">log in</a> or 
				<a href="/search">get your own</a>.
			</div>
			
		</div> 
	
		<div id="profile-name"><?=$username?>'s songs</div>
		
		<div id="plug-search"> 
		
		   
		   	<input type="text" id="searchTerm" value="Start typing to add a song to your list..." align="center" onclick="this.value=''">
			<button type="submit" name="find-button" id="find-button" onClick="searchPlug();"> Find! </button>
      
			
			<div id="results-container">
		
			</div>
		
		</div>
		
		
		
		<div id="plugs-container">
		
		
				<?php echo getPlugs($username);?>			
		</div>
  </div>
</body>
<script type="text/javascript">
var user_id = <?php echo getUserId($username);?>;

function searchPlug() {  
	var searchTerm = $("#searchTerm").val(); 
	var urlSearch = encodeURI('php-scripts/profile_ajax_functions.php?method=search&keywords='+searchTerm);
	var retString  = "";
	$.get(urlSearch, function(data){
		var json_results = JSON.parse(data);          
		for(var i = 0; i < json_results.length; i++){
			var artist =  json_results[i].artist;
			var title = json_results[i].name;   
			var page_url =	json_results[i].url;  
			var image = null;
			try{
				image = json_results[i].image[1][0];       
			}                                  
			catch(error){image = ""}
			//results[i] = "Song: " + title + ". Artist: " + artist + "."; 
			var url = encodeURI("/php-scripts/profile_ajax_functions.php?method=add_from_search&artist="+artist+"&title="+title+"&image_url="+image+"&user_id="+user_id + "&page_url="+page_url);
			var html = "<a href='javascript:addPlug(\"" + url + "\")'>Add Songasdf</a>";  
			retString += "<a href='javascript:addPlug(\"" + url + "\")'>
							<div class='one-result' onMouseOver='this.className="highlight"' onMouseOut='this.className="one-result"'> "
								+ "<img class='result-pic' src='" + image + "'>"
								+ "<div class='result-title'>" + title + "</div>"
								+ "<div class='result-artist'>" + artist + "</div>
							</div>
						</a>";
							  
		}  
		$("#results-container").html(retString); 
		
	});
}  
function deletePlug(plug_id){
	var url = encodeURI('php-scripts/profile_ajax_functions.php?method=delete&listed_songs_id='+plug_id+'&user_id='+user_id);
	$("#plugs-container").load(url);
}   

function addPlug(url){  
	//alert(url);       
	$.get(url, function(data){
		$("#plugs-container").html(data);
	});
}



</script>
</html>