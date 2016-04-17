
	 <?php 	
include_once "function.php";
	 
	$mediaid = $_GET['id'];
	$username = $_GET['username'];
	$insert = "insert into favList(favid, mediaid,username)".
	"values(NULL,'".$mediaid ."','" .$username ."')";
	$queryresult = mysql_query($insert)
	or die("Insert into Media error in media_upload_process.php " .mysql_error());

?>
 