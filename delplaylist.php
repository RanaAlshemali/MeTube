
<? error_reporting(0);
ini_set('display_errors', 0); ?>
<script>alert("Im In")</script>
<?php 
session_start();
include_once "function.php";
$username=$_SESSION['username'];
$PlaylistName = $_GET['playlist'];
 
	$delete = "delete from playList where playlistname='" .$PlaylistName."' and username='" .$username."'";
	$queryresult = mysql_query($delete)
	or die("Delete from favList in delFac.php " .mysql_error());

		header('Location: browse.php');
 

?>
