<?php
require_once('mysql_connect.php');

$ownerTool = getAddedTool(1,3,128,200);
echo $ownerTool;

function getAddedTool($cur_user_id,$owner_id,$song_id,$list_id){
	mysqlConnect();
	//$res = mysql_connect("SELECT id FROM listed_songs WHERE (song_id='$song_id' AND user_id='$cur_user_id' AND added_from='$owner_id' AND is_deleted='0')");
	$res = mysql_connect("SELECT id FROM listed_songs WHERE (`song_id`='128' AND `user_id`='1' AND `added_from`='3' AND is_deleted='0')");
	if (mysql_num_rows($res)){
		return "| added";
	}
	else {
		$row = mysql_fetch_array($res);
		echo $row['id'];
	//return "| <a href='javascript:addToList($list_id,$cur_user_id);'>add to list</a>";
	}
}
?>