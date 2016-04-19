<? error_reporting(0);
ini_set('display_errors', 0); ?>
<!DOCTYPE html>
<a href="browse.php"><img src="phpimages/phpmkrlogo1.png" alt="MeTube Logo"></a>
<?php
session_start ();

if ($_SESSION['username']=="")
	$currentuser="";
else 
	$currentuser= $_SESSION['username'];
include_once "function.php";
?>
<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media Upload</title>
<link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>
<?php if ($currentuser == "") {
	header('Location: index.php');
	$_SESSION ["loggedIn"] = false;
} else {
	?>
	  <p>Welcome <?php echo $_SESSION['username']; ?></p>
	<a href="index.php">Home</a>  
	<a href="media_upload.php" style="color: #FF9900;">Upload File</a>
	<a href="UserChannel.php">My Channel</a>  
	<a href="#"><strong>My Playlists</strong></a>  
	<a href="favListdisplay.php">My Favorite List</a>  
    <a href="accountlist.php">Account Panel</a>
	<a href="logout.php">logout</a>

	
<?php
}
echo '<br />';
echo '<br />';
?>
 
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
