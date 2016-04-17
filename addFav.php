
	 <?php 	
include_once "function.php";
if(function_exists($_GET['f'])) {
	$_GET['f']();
}
function addFav(){	 
	<?php 	$mediaid = $_GET['id'];
	$username = $_GET['username'];
	$insert = "insert into favList(favid, mediaid,username)".
	"values(NULL,'".$mediaid ."','" .$username ."')";
	$queryresult = mysql_query($insert)
	or die("Insert into Media error in media_upload_process.php " .mysql_error());
	?>
}

 
/* ... SQL EXECUTION TO UPDATE DB ... */

header('Location: Browse.php');
?>
 
 