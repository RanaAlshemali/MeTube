
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


	<a href='media_upload.php' style="color: #FF9900;">Upload File</a>
	<div id='upload_result'>

</div>
	<br />
	<br />
<?php

$query = "SELECT * from media";
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
			<tr valign="top">
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
				

				echo $mediaid . "  "; // mediaid
				
				?>
			
			<a href="media.php?id=<?php echo $mediaid;?>" target="_blank"><?php echo $filename;?></a>
			<lable align="right">Type: <?php echo $type;  echo '<br />';?></lable>
			 <?php
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
					<div >
	<!-- <p>Viewing Video:<?php echo $result_row[2].$result_row[1];?></p> -->
 
				
				
				<a href="media.php?id=<?php echo $mediaid;?>" target="_blank">	
				<video id="video" width="320" height="286" style="position: absolute"> 
				<source src="<?php echo $result_row[4];?>#t=15">  
				</video>
				<?php echo "\n <img id='image11' src='uploads/video-icon1.png' height='100' width='100'/>";?>
				
				</a>
				
          
          
       </div>
              
<?php
				}
				echo '<br />';
				?>
			
			By:  
					<?php
				echo $username; // mediaid
				
				?>
			
			Created On:  
			<?php
				echo substr( $dateCreated, 0, 10 );
				echo '<br />';
				?>
			
			<a href="<?php echo $filenpath;?>" target="_blank"
				onclick="javascript:saveDownload(<?php echo $result_row[4];?>);">Download</a>
				<?php echo '<br />';?>
					
					<?php
			}
			echo '<br />';
			?>
				Created On:  
			<?php
				echo substr( $dateCreated, 0, 10 );
				echo '<br />';
				?>
				</td>
			</tr> 
				<?php
		}
		?>
		</table>

</body>
</html>
