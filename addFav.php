<script>alert("Im In")</script>
<?php 
session_start();
if (!$_SESSION["loggedIn"])
	header('Location: index.php');
 
include_once "function.php";
$username=$_SESSION['username'];

$id = $_GET['id'];
 
	$insert = "insert into favList(favid, mediaid,username)".
			"values(NULL,'". $id . "','" .$username."')";
	$queryresult = mysql_query($insert)
	or die("Insert into favList in delFac.php " .mysql_error());

		header('Location: browse.php');
	


?>
