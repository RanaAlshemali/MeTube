<? error_reporting(0);
ini_set('display_errors', 0); ?>
<script>alert("Im In")</script>
<?php 
session_start();
include_once "function.php";
$username=$_SESSION['username'];
$PlaylistName = $_GET['playlist'];
$mediaid =$_GET['id'];
 
	$delete = "delete from playList where mediaid ='". $id . "' and username='" .$username."' and playlistname='" .$PlaylistName."'";
	$queryresult = mysql_query($delete)
	or die("Delete from favList in delFac.php " .mysql_error());

		header('Location: browse.php');
 

?>
