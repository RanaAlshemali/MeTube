<? error_reporting(0);
ini_set('display_errors', 0); ?>
<!DOCTYPE html>
<?php
session_start ();

if ($_SESSION['username']==""){
	header('Location: index.php');
}
else 
	$currentuser= $_SESSION['username'];
include_once "function.php";
?>
 
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<script type="text/javascript" src="js/jquery-latest.pack.js"></script>
   <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
     
<script type="text/javascript">
 

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
	
 
	  <p>Welcome <?php echo $_SESSION['username']; ?></p>
	<a href="UserChannel.php">My Channel</a>  
	<a href="playlist.php">My Playlists</a>  
	<a href="favListdisplay.php">My Favorite List</a>  
	<a href="logout.php">logout</a>
        <a href="accountlist.php">Account Panel</a>
	<a href='media_upload.php' style="color: #FF9900;">Upload File</a>
<?php
 
?>

	
	<div id='upload_result'>

</div>
	<br />
	<br />
<form method="post" action="createPlaylist.php" enctype="multipart/form-data" >
 
  <p style="margin:0; padding:0">
  	<label >Playlist Name: </label><label style="color:red;">*</label>
     <input type="text" name="PlaylistName" required></input><input value="Create" name="submit" type="submit" />

	
  </p>
 </form>
<?php
$currentuser=  $_SESSION['username'];

$query = "SELECT DISTINCT(playlistname) AS 	playlistname from playList Where username= '".$currentuser."'";
$result = mysql_query ( $query );
if (! $result) {
	die ( "Could not query the media table in the database: <br />" . mysql_error () );
}
?>
    <br/><br/>
    <div style="background: #339900; color: #FFFFFF; width: 150px;">My Playlists: </div><br/>
	<div ><table width="75%" cellpadding="5" cellspacing="15"  align="center" valign="center" border="1">
		<?php
		$num_rows = mysql_num_rows ( $result );
 
			?>
			
				<?php
			
			for($j = 0; $j < $num_rows; $j ++) {
				?>
				<tr>	<td >
				
										<?php
				$result_row = mysql_fetch_row ( $result );
				
				$PlaylistName = $result_row [0];
		 
				?>
			
			<div>
			<div  style="float: left;"><a href="playlistdisplay.php?playlist=<?php echo $PlaylistName;?>"><?php echo $PlaylistName;?></a></div>
			<div  style="float: right;"> 			
			<img id="<?php echo  $PlaylistName;?>" src="uploads/deletePL1.png" height="20" width="20" onClick="javascript:delpaylist(this.id)"/>

		</div>
			</div>
		
			</td></tr> 
				<?php
		}
		?>
		</table>
 </div>
 <script  type="text/javascript">
 function delpaylist(id) {
		var username = "<?php echo $_SESSION['username'] ;?>";
		var playlist = "<?php echo $playlistname ;?>";

		    $.ajax({
		        url: 'delplaylist.php',
		        type: 'GET',
		        data: {id:id, username:username, playlist:playlist },
		        success: function(data) {
		            console.log(data); // Inspect this in your console
		        }
		    });
		    location.reload();
	}
</script>

</body>
</html>
