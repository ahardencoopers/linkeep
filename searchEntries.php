<?php
session_start();

require_once "dblog.php";
require_once "security.php";

$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if(!$db_server) die ("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());


$errorMessage;

$username;
$password;
$title;
$comment;
$link;
$tags;
$arrTags = array(0 => "no tags");
$arrTemp = array(0 => "");
$idTemp;
$searchTag;

if(isset($_POST['logout']) )
{
	$_SESSION['username'] = NULL;
	$_SESSION['password'] = NULL;
	echo "Logged out. Go to <a href=\"login.php\"> login </a> page.";
	$flag = true;
}

if(isset($_SESSION['username']) && isset($_SESSION['password']))
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	
	$query = "SELECT username, password FROM users WHERE username ='$username' 
		AND password = '$password'";
	
	$result = mysql_query($query);
	
	if(!$result) die ("Database access failed: " . mysql());
		
	$row = mysql_fetch_row($result);
	
	if($username == $row[0] && $password == $row[1])
	{
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		
echo <<< _END

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
<link rel="stylesheet" href="http://www.w3.org/StyleSheets/Core/Traditional" type="text/css">

<title>linkeep home</title>

</head>

<body>
<h1> Linkeep Home </h1>
<h2> Welcome, $username.</h2>

<form method="post" action"login.php">

<input type="hidden" name="logout" value="">

<input type="submit" value="Logout">

</form>

<ul>
  <li><a href="addEntry.php">Add entry </a></li>
  <li><a href="viewEntries.php">View entries </a></li>
  <li><a href="searchEntries.php">Search entries </a></li>
</ul>

<form method="post" action"login.php">

Search by tags: <input type="text" name="searchTag"/> <br>
<p>(Enter 1 tag e.g.:movie)</p>

<input type="submit">
</form>


_END;


if(isset($_POST['searchTag']))
{
	
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$searchTag = $_POST['searchTag'];
	
	$query1 = "SELECT idEntry FROM mapEntryTag WHERE contentTag='$searchTag'";
	$result1 = mysql_query($query1);
	if(!$result1) die ("Database access failed:" . mysql_error());
	
	$numRows1 = mysql_num_rows($result1);
	
	for($i=0; $i<$numRows1; $i++)
	{
		$row1 = mysql_fetch_row($result1);
		$tempTagId = $row1[0];
		
		$query2 = "SELECT title,comment,link FROM entries WHERE id='$tempTagId'";
		$result2 = mysql_query($query2);
		if(!$result2) die ("Database access failed:" . mysql_error());
		
		$numRows2 = mysql_num_rows($result2);
		
		for($j=0; $j<$numRows2; $j++)
		{
			$row2 = mysql_fetch_row($result2);
			echo "$row2[0] $row2[2]";
			echo "<br>";
		}
	}	
}

echo <<< _END2

</body>
</html>

_END2;


	}
	else
	{
		echo "Session timeout, please <a href=\"login.php\"> login </a> again.";
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
	}
	
	
	
}
else if(!$flag)
{
	echo "not logged in, go to <a href=\"login.php\"> login </a> page.";
}




?>