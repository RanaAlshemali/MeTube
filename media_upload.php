<?php
session_start();
 if (!$_SESSION["loggedIn"])
 	header('Location: index.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media Upload</title>
</head>

<body>

<form method="post" action="media_upload_process.php" enctype="multipart/form-data" >
 
  <p style="margin:0; padding:0">
  <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
    <label style="color:#663399">Add a Media: <em> (Each file limit 10M)</em></label><br/>
   <input  name="file" type="file" size="50" />
   <label >Media Title: </label><br/>
   <input type="text" name="name">
   <label >Media Duration: (0 for images) </label><br/>
   <input type="text" name="title">
   <label >Media Description: </label><br/>
   <input type="text" name="description">
   <label >Media Keywords: </label><br/>
   <input type="text" name="keywords">
   <select>
  <option value="volvo">Public</option>
  <option value="saab">Privet</option>

</select>
   
  
	<input value="Upload" name="submit" type="submit" />
  </p>
 
                
 </form>

</body>
</html>
