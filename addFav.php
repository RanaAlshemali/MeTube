<!doctype html>

<html lang="en">
<body>
<script>
alert("hi");

	 <?php 	
	 $insert = "insert into favList(favid, mediaid,username)".
	 		"values(NULL,'".$mediaid ."','" .$username ."')";
	 $queryresult = mysql_query($insert)
	 or die("Insert into Media error in media_upload_process.php " .mysql_error());
?>
alert("This Media is Successfully Added to Your Favorite List");


</script>
</body>
</html>
