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
   <input  name="file" type="file" size="50" ></input><br/>
   <label >Media Title: </label> <label style="color:red;">*</label>
   <input type="text" name="medianame" required></input><br/>
    <label >Media Description: </label><label style="color:red;">*</label>
   <input type="text" name="description" required></input><br/>
   <label >Media Duration: (00:00 for images) </label><label style="color:red;">*</label>
   <input type="text" name="duration" required></input><br/>
 	<label >Media Keywords: </label><label style="color:red;">*</label>
     <input type="text" name="keywords" required></input><br/>
 

<p>Catagory:
<select name="category">
  <!--<option value="1=1">All Catagories</option>-->
  <option value="Film & Animation">Film & Animation</option>
  <option value="Autos & Vehicles">Autos & Vehicles</option>
  <option value="Music">Music</option>
  <option value="Pets & Animals">Pets & Animals</option>
  <option value="Sports">Sports</option>
  <option value="Gaming">Gaming</option>
  <option value="People & Blogs" selected>People & Blogs</option> <!-- default-->
  <option value="Comedy">Comedy</option>
  <option value="Entertainment">Entertainment</option>
  <option value="Film & Animation">Film & Animation</option>
  <option value="Howto & Style">Howto & Style</option>
  <option value="Education">Education</option>
  <option value="Science & Technology">Science & Technology</option>
  <option value="Nonprofits & Activism">Nonprofits & Activism</option>

</select>
</p>

  
<select name="privacy">
  <option value="public">Public</option>
  <option value="privet">Privet</option>
</select>
   
  
	<input value="Upload" name="submit" type="submit" />
  </p>
 
                
 </form>

</body>
</html>
