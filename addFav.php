
	 <?php 	

	$mediaid = $_POST['id'];
	$username = $_POST['username'];
	$insert = "insert into favList(favid, mediaid,username)".
	"values(NULL,'".$mediaid ."','" .$username ."')";
	$queryresult = mysql_query($insert)
	or die("Insert into Media error in media_upload_process.php " .mysql_error());

?>
 