
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
		$num_rows = mysql_num_rows ( $result );
		
		//echo ( int ) ($num_rows / 4) . " Rows\n";
		$size = (( int ) ($num_rows / 4)) + 1;
		
		for($i = 1; $i <= $size; $i ++) {
			
			$rowSize;
			if($num_rows>4){
				$rowSize=4;
			}else{
				$rowSize=$num_rows;
			}
			$num_rows=$num_rows-4;
			?>
			<tr valign="top">
				<?php
				//echo  " Rows\n";
			for($j = 0; $j < $rowSize; $j ++) {
				?>
					<td>
										<?php
										$result_row = mysql_fetch_row ( $result );

										$filename = $result_row [0];
										$username = $result_row [1];
										$type = $result_row [2];
										$mediaid = $result_row [3];
										$filenpath = $result_row [4];
										$dateCreated = $result_row [5];
			echo '<br />';
			echo '<br />';
			echo $mediaid."  "; // mediaid
			
			?>
			
			<a href="media.php?id=<?php echo $mediaid;?>" target="_blank"><?php echo $filename; echo '<br />';?></a> <?php
			echo '<br />';
			if (substr ( $type, 0, 5 ) == "image") // view image
{
				
				echo "\n <img src='" . $filenpath . "' height='286' width='320'/>";
			} else // view movie
{
				?>
	<!-- <p>Viewing Video:<?php echo $result_row[2].$result_row[1];?></p> -->


				<object id="MediaPlayer" width=320 height=286
					classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95"
					standby="Loading Windows Media Player componentsâ€¦"
					type="application/x-oleobject"
					codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

					<param name="filename" value="uploads/rana/Work+-+Rihanna+ft.+Drake+.mp3#t=10,30">
					<!-- echo $result_row[2].$result_row[1];  -->


					<param name="Showcontrols" value="true">
					<param name="autoStart" value="true">




					<embed type="application/x-mplayer2"
						src="uploads/rana/Work+-+Rihanna+ft.+Drake+.mp3#t=10,30" name="MediaPlayer" width=320
						height=240></embed>

				</object>

          
          
          
       
              
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
			echo substr ( $dateCreated, 0, 10 );
			echo '<br />';
			?>
			
			<a href="<?php echo $filenpath;?>" target="_blank"
				onclick="javascript:saveDownload(<?php echo $result_row[4];?>);">Download</a>
				<?php echo '<br />';?>
					</td>
					<?php
			}echo '<br />';
			?></tr> 
				<?php
		}
		?>
		</table>
 
</body>
</html>
