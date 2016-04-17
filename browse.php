
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
session_start ();
include_once "function.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script type="text/javascript" src="js/jquery-latest.pack.js"></script>
<script type="text/javascript">
function saveDownload(id)
{
	$.post("media_download_process.php",
	{
       id: id,
	},
	function(message) 
    { }
 	);
} 
</script>
<style >
#image11{
    position:relative;
   top:50%;
   left:50%;
   margin-top:100px;
   margin-left:110px;
}
</style>
</head>

<body>
	
<?php if (!$_SESSION["loggedIn"]) {?>
		<p>Welcome New Guest</p>
	<a href="index.php">Login or register</a>
		<?php
	$_SESSION ["loggedIn"] = false;
} else {
	?>
	  <p>Welcome <?php echo $_SESSION['username']; ?></p>
	<a href="logout.php">logout</a>
<?php
}
?>
<form  method="GET" action="<?php $_PHP_SELF ?>"> 
<input  type="text" name="search"> 
<input  type="submit" name="submit" value="Search"> 
</form> 

<form name="formCatagory" action = "<?php $_PHP_SELF ?>" method = "GET">
<p>
View by Catagory:
<select name="formCatagory" onchange="this.form.submit()">
<option value="" >Select Catagory...</option>  
<option value="" >All Catagories</option>
  <!--<option value="1=1">All Catagories</option>-->
  <option value="Film & Animation">Film & Animation</option>
  <option value="Autos & Vehicles">Autos & Vehicles</option>
  <option value="Music">Music</option>
  <option value="Pets & Animals">Pets & Animals</option>
  <option value="Sports">Sports</option>
  <option value="Gaming">Gaming</option>
  <option value="People & Blogs">People & Blogs</option>
  <option value="Comedy">Comedy</option>
  <option value="Entertainment">Entertainment</option>
  <option value="Film & Animation">Film & Animation</option>
  <option value="Howto & Style">Howto & Style</option>
  <option value="Education">Education</option>
  <option value="Science & Technology">Science & Technology</option>
  <option value="Nonprofits & Activism">Nonprofits & Activism</option>

</select>
</p> 
</form>
	<a href='media_upload.php' style="color: #FF9900;">Upload File</a>
	<div id='upload_result'>

</div>
	<br />
	<br />

<?php
$where = "WHERE catagory = ''";
if(isset($_GET['formCatagory']) )
 {
  $catagory = $_GET['formCatagory'];
  $where = "WHERE catagory = '$catagory'";
  //echo $where;
}
if (isset($_GET['search'])){
 $search = $_GET['search'];
if ($where == "WHERE catagory = ''"){ 
$where= 'WHERE lower(concat(username, \'\', filename, \'\', dateCreated, \'\', keywords, \'\', duration, \'\', privacy, \'\', catagory, \'\', duration)) like "%' . $search. '%"';
} else {
$where.= ' AND lower(concat(username, \'\', filename, \'\', dateCreated, \'\', keywords, \'\', duration, \'\', privacy, \'\', catagory, \'\', duration)) like "%' . $search. '%"';
}
}
//if ($where != "")  

if ($where == "WHERE catagory = ''"){ //All catagories
$query = "SELECT * from media";
} else {
$query = "SELECT * from media $where ";
}
//} else {
//$query = "SELECT * from media";
//}
//$query = "SELECT * from media WHERE catagory = 'People & Blogs'";
$result = mysql_query ( $query );
if (! $result) {
	die ( "Could not query the media table in the database: <br />" . mysql_error () );
}
?>
    
    <div style="background: #339900; color: #FFFFFF; width: 150px;">Uploaded
		Media</div>
	<table width="75%" cellpadding="5" cellspacing="15"  align="center" valign="center" border="1">
		<?php
		$num_rows = mysql_num_rows ( $result );
 
		$size = (( int ) ($num_rows / 4)) + 1;
		
		for($i = 1; $i <= $size; $i ++) {
			
			$rowSize;
			if ($num_rows > 4) {
				$rowSize = 4;
			} else {
				$rowSize = $num_rows;
			}
			$num_rows = $num_rows - 4;
			?>
			<tr>
				<?php
			
			for($j = 0; $j < $rowSize; $j ++) {
				?>
					<td >
										<?php
				$result_row = mysql_fetch_row ( $result );
				
				$filename = $result_row [0];
				$username = $result_row [1];
				$type = $result_row [2];
				$mediaid = $result_row [3];
				$filenpath = $result_row [4];
				$dateCreated = $result_row [5];
				if(strlen($filename)>20){
					$filename= substr ( $filename, 0, 20 );
				}
				$type = substr ( $type, 0, 5 );
				

				?>
			
			<div>
			<div  style="float: left;"><?php echo $mediaid . "  ";?><a href="media.php?id=<?php echo $mediaid;?>" target="_blank"><?php echo $filename; ?></a></div>
			<div  style="float: right;"><lable align="right">Type: <?php echo $type;  echo '<br />';?></lable></div>
			</div>
			 <?php
			 echo '<br />';
			 echo '<br />';
				if ( $type == "image"){ // view image
?>
				<a href="media.php?id=<?php echo $mediaid;?>" target="_blank">	
				<?php echo "\n <img src='" . $filenpath . "' height='286' width='320'/>";?></a>
		<?php
				} else if ( $type  == "audio"){ // view audio

					?>
				<a href="media.php?id=<?php echo $mediaid;?>" target="_blank">	
				<?php echo "\n <img src='uploads/Audio-Radio-icon.png' height='286' width='320'/>";?></a>
		<?php 
				}
				else{ // view movie

					?>
					<div  style="position: relative; left: 0; top: 0;" >
	<!-- <p>Viewing Video:<?php echo $result_row[2].$result_row[1];?></p> -->
 
				
				
				<a href="media.php?id=<?php echo $mediaid;?>" target="_blank">	
				<video id="video" width="320" height="286"  style="position: relative;"> 
				<source src="<?php echo $result_row[4];?>#t=15">  
				</video>
				<?php echo "\n <img  src='uploads/video-icon1.png' height='100' width='100'  style='position: absolute; top: 100px; left: 110px;'/>";?>
				
				</a>
				
          
          
       </div>
              
<?php
				}
				echo '<br />';
				?>
			<div>
			<div  style="float: left;">By:<?php echo $username;echo '<br />';?></div>
			<div  style="float: right;"><a href="<?php echo $filenpath;?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[4];?>);">Download</a></div>
			</div>
			
			
		 <?php echo '<br />';?>
			<div>
			<div  style="float: left;">Created On: <?php echo substr( $dateCreated, 0, 10 ); echo '<br />';?></div>
			<div  id="<?php echo  $mediaid;?>" style="float: right;">
			<img id="<?php echo  $mediaid;?>" src="uploads/ralshem/Star-Full.png" height="20" width="20" onClick="javascript:addFav(this)"/></div>
			</div>
			
			</div>
			 <?php 	
			}?>
				</td>
			</tr> 
				<?php
		}
		?>
		</table>

 <script  >
 function addFav(div){	 
	 alert("In");
	var id = div.id
	 alert(id);
	 document.write('<?php add("'+id+'");?>');
	 <?php 	
	 function add($id){
	     		$insert = "insert into favList(favid, mediaid,username)".
				"values(NULL,'". $id . "','" .$_SESSION['username'] ."')";
				$queryresult = mysql_query($insert)
				or die("Insert into Media error in media_upload_process.php " .mysql_error());
	 }
				?>
		alert("Added");
		}
	alert("out");
 
</script>
</body>
</html>
