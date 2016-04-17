<script>alert("Im In")</script>
<?php 
session_start();
include_once "function.php";
$username=$_SESSION['username'];

$id = $_GET['id'];
 
	$insert = "insert into favList(favid, mediaid,username)".
			"valuss(NULL,'". $id . "','" .$username."')";
	$queryresult = mysql_query($insert)
	or die("Insert into Media error in media_upload_process.php " .mysql_error());

	


?>
