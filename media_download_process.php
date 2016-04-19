<? error_reporting(0);
ini_set('display_errors', 0); ?>
<?php
session_start();
include_once "function.php";

/******************************************************
*
* download by username
*
*******************************************************/

$username=$_SESSION['username'];
$mediaid=$_REQUEST['id'];

//insert into upload table
$insertDownload="insert into download(downloadid,username,mediaid) values(NULL,'$username','$mediaid')";
$queryresult = mysql_query($insertDownload)
	
?>


