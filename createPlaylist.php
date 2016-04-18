<script>alert("Im In")</script>
<?php 
session_start();
include_once "function.php";
$username=$_SESSION['username'];

$PlaylistName = $_POST['PlaylistName'];
 
	$insert = "insert into playList(playlistname, username,	mediaid)".
			"values('" .$PlaylistName ."','". $username. "', 0)";
	$queryresult = mysql_query($insert)
	or die("Insert into favList in delFac.php " .mysql_error());

		header('Location: playlist.php');
	


?>
