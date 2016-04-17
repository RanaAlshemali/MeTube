<script>alert("Im In")</script>
<?php 
session_start();
include_once "function.php";
$username=$_SESSION['username'];

$id = $_GET['id'];
 
	$delete = "delete from media where mediaid ='". $id . "' and username='" .$username."'";
	$queryresult = mysql_query($delete)
	or die("Delete media from media in delMedia.php " .mysql_error());
	
	$delete = "delete from favList where mediaid ='". $id . "'";
	$queryresult = mysql_query($delete)
	or die("Delete from favList in delFac.php " .mysql_error());
	
	$delete = "delete from comments where mediaid ='". $id . "'";
	$queryresult = mysql_query($delete)
	or die("Delete from comments in delFac.php " .mysql_error());
	
		header('Location: browse.php');
 

?>
