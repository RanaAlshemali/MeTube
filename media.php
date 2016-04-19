<? error_reporting(0);
ini_set('display_errors', 0); ?>
<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";

?>	
 
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
 


<?php
// DOES NOT WORK TODO
/* if(isset($_GET['submit'])) {
$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
        $result = mysql_query( $query );
        $result_row = mysql_fetch_row($result);

        //updateMediaTime($_GET['id']);

        $filename=$result_row[0];   ////0, 4, 2
        $filepath=$result_row[4];
        $type=$result_row[2];
	//echo ("Location: ./$filepath");
        header("Location: .//$result_row[4]");
	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=\"$result_row[4]\""); 
	echo readfile($result_row[4]);

} */ 

if(isset($_GET['id'])) {
	$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
	$result = mysql_query( $query );
	$result_row = mysql_fetch_row($result);
	
	//updateMediaTime($_GET['id']);
	
	$filename=$result_row[0];   ////0, 4, 2
	$filepath=$result_row[4]; 
 
	$type=$result_row[2];
	if(substr($type,0,5)=="image") //view image
	{
		echo "Viewing Picture:";
		echo $result_row[4];
		echo '<br />';
		echo "<img src='".$filepath."'/>";
	}
	else if (substr($type,0,5)=="video") //view movie
	{	
?>
<video width="320" height="240" controls>
  <source src="<?php echo $result_row[4];?>" type="video/mp4">
Your browser does not support the video. Sorry.
</video>
	<!-- <p>Viewing Video:<?php echo $result_row[2].$result_row[1];?></p> 
	<p>Viewing Video:<?php echo $result_row[4];?></p>
    <object id="MediaPlayer" width=320 height=286 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components…" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

<param name="filename" value="<?php echo $result_row[4];?>"> -->
	<!-- echo $result_row[2].$result_row[1];  -->
		

<!-- <param name="Showcontrols" value="True">
<param name="autoStart" value="True">

<embed type="application/x-mplayer2" src="<?php echo $filepath;  ?>" name="MediaPlayer" width=320 height=240></embed>

</object> -->
              
<?php
} else if (substr($type,0,5)=="audio") 
{
 ?>
 
<audio controls>
  <source src="<?php echo $result_row[4];?>" type="audio/mpeg">
Your browser does not support the audio.
</audio>
<?php
}
}
else
{
?>
File is ready to download. 
<!-- <meta http-equiv="refresh" content="0;url=browse.php"> -->
<?php
}
?>
<div><a href="<?php echo  $result_row[4];?>"  download>Download</a></div>
<hr>
Comments:
<?php 
/*Desplay comments*/
$query = "SELECT username, dateCreated, content FROM comments WHERE mediaid='".$_GET['id']."'";
$result = mysql_query($query);
echo "<table border='1'>
<tr>
<th>username</th>
<th>time</th>
<th>comment</th>
</tr>";

while($row = mysql_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['username'] . "</td>";
echo "<td>" . $row['dateCreated'] . "</td>";
echo "<td>" . $row['content'] . "</td>";
echo "</tr>";
}
echo "</table>";/*echo "<table>";

while ($row = mysql_fetch_array($result))
{
    echo "<tr>";

    foreach($row as $value)
    {
        echo "<td>".$value."</td>";
    }

    echo "</tr>";

}

echo "</table>";
*/
?>
<? if(mysql_fetch_assoc(mysql_query ("SELECT allowComments FROM media where mediaid ='".$_GET['id']."'"))['allowComments'] == 1): ?>
Add comment: <br>
<form action = "<?php $_PHP_SELF ?>"  method="POST">
<textarea name = "comments" cols=40 rows=6></textarea>
<input type= "submit" />
</form>
<? else: ?>
Adding comments is disabled.
<? endif; ?>

<?php 
//session_start();
//include_once "function.php";
/*Add comment*/
//$username=$_SESSION['username'];
// TODO: allow comment only if commenting is allowed
if(isset($_POST['comments']) )
{ 
if ($_SESSION["loggedIn"]) {
  $comment = $_POST['comments'];
  $insert = "INSERT INTO comments(content,username,mediaid) VALUES ('$comment','".$_SESSION['username']."','".$_GET['id']."')";
  $queryresult = mysql_query($insert) or die("Insert into Media error in media_upload_process.php " .mysql_error());
  header("Refresh:0"); //refresh page to display new comment
}
else 
header("Location: ./login.php"); /* Redirect browser to login */
}
 else {}
?>
</body>
</html>
