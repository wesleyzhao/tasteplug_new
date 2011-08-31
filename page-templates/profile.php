<?php

include("php-scripts/header.php");
require_once('php-scripts/profile_functions.php'); 
require_once('php-scripts/login_functions.php');   
?>	
		<div id="profile-name"><?=$username?>'s songs</div>
		<?php
			if($_SESSION['username'] == $username){
		
		?>
		<div id="plug-search"> 
		
		   	<input type="text" id="searchTerm" value="Start typing to add a song to your list..." align="center" onclick="this.value=''" onkeydown="if (event.keyCode == 13) document.getElementById('find-button').click()">
			<button type="submit" name="find-button" id="find-button" onClick="searchPlug();"> Find! </button>
      		
		</div>
			<div id="results-container">
		
			</div>
		
		<?php
		
		}
		if(phoneNoPassword($username)){
			print "<center>Is this your page? Claim it by entering a <a href='/register'>password.</a></center>";
		}
		?>
		
		
		
		<div id="plugs-container">
		
		
				<?php echo getPlugs($username);?>			
		</div>
		<?php require_once('php-scripts/footer.php');?>
  </div>
  
  		
</body>
<script type="text/javascript">
var user_id = <?php echo getUserId($username);?>;

function fixQuotes(string){
	string = string.replace("'", "&#39;");
	return string;
}

function searchPlug() {  
	var searchTerm = $("#searchTerm").val(); 
	var urlSearch = encodeURI('php-scripts/profile_ajax_functions.php?method=search&keywords='+searchTerm);
	var retString  = "";
	var message = "<p>Click the best match to add it to your list, or search again!</p>";
	$("#results-container").html('<center><img class="loading" src="/images/loading-gif.gif" ></center> ');
	$.get(urlSearch, function(data){
		var json_results = JSON.parse(data);          
		for(var i = 0; i < json_results.length; i++){
			var artist =  json_results[i].artist;
			var title = json_results[i].name;   
			var page_url =	json_results[i].url;  
			var image = "/images/album-default.png";
			try{
				image = json_results[i].image[1][0];   
				if (!image) image = "/images/album-default.png";
			}                                  
			catch(error){image = "/images/album-default.png"}
			//results[i] = "Song: " + title + ". Artist: " + artist + ".";
			artist = fixQuotes(artist);
			title = fixQuotes(title);
			page_url = fixQuotes(page_url);
			image = fixQuotes(image); 
			var url = encodeURI("/php-scripts/profile_ajax_functions.php?method=add_from_search&artist="+artist+"&title="+title+"&image_url="+image+ "&page_url="+page_url);
			var html = "<a href='javascript:addPlug(\"" + url + "\")'>Add Song</a>";  
			retString += "<a href='javascript:addPlug(\"" + url + "\")'> <div class='one-result' onMouseOver='this.className=\"highlight\"' onMouseOut='this.className=\"one-result\"'> "+ "<img class='result-pic' src=\'" + image + "\'>"	+ "<div class='result-title'>" + title + "</div>"+ "<div class='result-artist'>" + artist + "</div></div></a>";
		} 
		if(data.length == 0 || retString.length < 1){
			finalMessage = "No search results found.";
		}                                             
		else{
			finalMessage = message+retString;  
		}
		
		$("#results-container").html(finalMessage);
		
	});
}  
function deletePlug(plug_id){
	var url = encodeURI('php-scripts/profile_ajax_functions.php?method=delete&listed_songs_id='+plug_id);
	$("#plugs-container").load(url);
}   

function addPlug(url){  
	//alert(url);       
	$.get(url, function(data){
		$("#plugs-container").html(data);
	});
	$("#results-container").html('');
}

function addToList(list_id,current_user_id){
	var url = encodeURI('php-scripts/profile_ajax_functions.php?method=add_to_list&list_id='+list_id);
	$("#plug_"+list_id).load(url);
} 

function playPlug(list_id, preview){           
	var html;
	if(preview == 0 || preview == null || preview == ""){
	   html = "No preview available."
	}else{
	   html = "<OBJECT class='preview' id = 'preview_"+list_id+"' CLASSID='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' WIDTH='160' HEIGHT='15' CODEBASE='http://www.apple.com/qtactivex/qtplugin.cab'><PARAM name='SRC' VALUE='"+preview+"'><PARAM name='AUTOPLAY' VALUE='true'><PARAM name='CONTROLLER' VALUE='true'><EMBED SRC='"+preview+"' WIDTH='160' HEIGHT='15' AUTOPLAY='true' CONTROLLER='true' PLUGINSPAGE='http://www.apple.com/quicktime/download/'></EMBED></OBJECT>";      
	}    
	var div = "#play_"+list_id;  
	$(div).html(html);
}



</script>
</html>