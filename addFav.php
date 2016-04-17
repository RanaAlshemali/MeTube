<?php 
if (isset($_POST['id'])) {

	addFav($_POST['id']);

function addFav($id){
	
	$insert = "insert into favList(favid, mediaid,username)".
			"valuss(NULL,'". $id . "','" .$_SESSION['username'] ."')";
	$queryresult = mysql_query($insert)
	or die("Insert into Media error in media_upload_process.php " .mysql_error());
}
	


}
?>