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
$date = date("Y-m-d H:i:s");
$title;
$comment;
$link;
$tags;
$arrTags = array(0 => "no tags");
$arrTemp = array(0 => "");
$idTemp;

if(isset($_POST['logout']) )
{
	$_SESSION['username'] = NULL;
	$_SESSION['password'] = NULL;
	echo "Logged out. Go to <a href=\"login.php\"> login </a> page.";
	$flag = true;
}

if(isset($_POST['title']) && isset($_POST['tags']))
{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$title = $_POST['title'];	
	$comment = $_POST['comment'];
	$link = $_POST['link'];
	$date = date("Y-m-d H:i:s");

	$tags = $_POST['tags'];	
	$arr = str_split($tags);
	
	for($i = 0; $i < strlen($tags); $i++)
	{
		if($arr[$i] != ",")
		{
			if($i == (strlen($tags)-1))
			{
				$arrTemp[$i] = $arr[$i];
				$stringTemp = implode("",$arrTemp);
				$arrTemp = array(0 => "");
				$arrTags[] = $stringTemp;
			}
			$arrTemp[$i] = $arr[$i];
		}
		else
		{
			$stringTemp = implode("",$arrTemp);
			$arrTemp = array(0 => "");
			$arrTags[] = $stringTemp;
		}
	}
	
	
	$query1 = "INSERT INTO `entries`(`title`, `comment`, `link`, `date`, `userFK`) 
		VALUES ('$title','$comment','$link','$date','$username')";
	
	$result1 = mysql_query($query1);
	if(!$result1) die ("Database access failed:" . mysql_error());
	
	for($j = 1; $j < sizeof($arrTags); $j++)
	{
		$query2 = "INSERT INTO `tags`(`content`) 
		VALUES ('$arrTags[$j]')";
		
		$result2 = mysql_query($query2);
		if(!$result2) echo "";
	}
	
	
	for($j = 1; $j < sizeof($arrTags); $j++)
	{
		$queryTemp = "SELECT id FROM entries WHERE userFK='$username' AND 
			date='$date'";
		
		$resultTemp = mysql_query($queryTemp);
		if(!$resultTemp) echo "queryTemp failed";
		$idTemp = mysql_fetch_row($resultTemp);
		
	
		$query3 = "INSERT INTO `mapEntryTag`(`idEntry`, `contentTag`) VALUES ('$idTemp[0]','$arrTags[$j]')";
		
		$result3 = mysql_query($query3);
		if(!$result3) echo "query 3 failed";
	}
	
	for($k = 1; $k < sizeof($arrTags); $k++)
	{
		$query4 = "INSERT INTO `mapUserTag`(`nameUser`, `contentTag`) VALUES ('$username','$arrTags[$k]')";
		
		$result4 = mysql_query($query4);
		if(!$result4) echo "query 4 failed";
	}
	$errorMessage = "Entry added succesfully";
	
}
else
{
	$errorMessage = "Entry must have at least a title and 1 tag";
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

<!doctype html>	

<html lang="en">
<head>

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

$errorMessage

<form method="post" action"addEntry.php">

Title: <input type="text" name="title"/> <br>
Comment: <input type="text" name="comment"/> <br>
link: <input type="text" name="link"/> <br>
Tags: <input type="text" name="tags"/> <br>
<p>(Write 1 or more tags separated by commas, e.g.: horror,movie,halloween)</p>

<input type="submit" value="Add Entry">

</form>

</body>
</html>

_END;

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