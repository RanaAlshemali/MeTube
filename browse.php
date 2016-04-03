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
</head>

<body>
	<p>Welcome <?php echo $_SESSION['username'];?></p>
	<a href='media_upload.php' style="color: #FF9900;">Upload File</a>
	<div id='upload_result'>
<?php
if (isset ( $_REQUEST ['result'] ) && $_REQUEST ['result'] != 0) {
	echo upload_error ( $_REQUEST ['result'] );
}
?>
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
	<table width="100%" cellpadding="0" cellspacing="0">
	
		<?php
		$num_rows = mysql_num_rows($result);
		echo "($num_rows/2) Rows\n";
		
		while ( $result_row = mysql_fetch_row ( $result ) ) // filename, username, type, mediaid, path
{
			
			$filename = $result_row [0];
			$username = $result_row [1];
			$type = $result_row [2];
			$mediaid = $result_row [3];
			$filenpath = $result_row [4];
			$dateCreated = $result_row [5];
			?>
        	 <tr valign="top">
			<td>
					<?php
			echo $mediaid; // mediaid
			?>
			</td>
			<td><a href="media.php?id=<?php echo $mediaid;?>" target="_blank"><?php echo $filename;?></a>
			</td>
			<td>By:  
					<?php
			echo $username; // mediaid
			?>
			</td>
			<td>Created On:  
			<?php
			echo substr($dateCreated,0,10); 

			?>
			</td>
			<td><a href="<?php echo $filenpath;?>" target="_blank"
				onclick="javascript:saveDownload(<?php echo $result_row[4];?>);">Download</a>
			</td>
			<td>  <?php
	if(substr($type,0,5)=="image") //view image
	{
		
		echo "Viewing Picture:";
		echo $result_row[4];
		echo "<img src='".$filenpath."' height='286' width='320'/>";
	}
	else //view movie
	{	
?>
	<!-- <p>Viewing Video:<?php echo $result_row[2].$result_row[1];?></p> -->
	<p>Viewing Video:<?php echo $result_row[4];?></p>
	      
    <object id="MediaPlayer" width=320 height=286 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components…" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

<param name="filename" value="<?php echo $result_row[4];?>">
	<!-- echo $result_row[2].$result_row[1];  -->
		

<param name="Showcontrols" value="True">
<param name="autoStart" value="True">

<embed type="application/x-mplayer2" src="<?php echo $filepath;  ?>" name="MediaPlayer" width=320 height=240></embed>

</object>

          
          
          
       
              
<?php
	}
			?>
			</td>
		</tr>
        	<?php
		}
		?>
	</table>
	</div>
</body>
</html>
