<? error_reporting(0);
ini_set('display_errors', 0); ?>
<script>alert("Im In")</script>
<?php 
session_start();
include_once "function.php";
$username=$_SESSION['username'];

$id = $_POST['id'];
 
	$insert = "insert into favList(favid, mediaid,username)".
			"values(NULL,'". $id . "','" .$username."')";
	$queryresult = mysql_query($insert)
	or die("Insert into favList in delFac.php " .mysql_error());

		header('Location: browse.php');
	


?>
