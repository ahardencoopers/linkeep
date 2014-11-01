<?php
//Session start and database connection
session_start();

require_once "dblog.php";
require_once "security.php";

$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if(!$db_server) die ("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());

//Variables
$errorMessage;

$username;
$password;
$title;
$comment;
$link;
$tags;
$arrTags = array(0 => "");
$arrTemp = array(0 => "");
$idTemp;
$searchTag;
$arrTagsEntry = array(0 => "");
$strTagsEntry;
$arrFlags = array(0 => "");
$temp34;

//Session Validation
if(isset($_POST['logout']) )
{
	$_SESSION['username'] = NULL;
	$_SESSION['password'] = NULL;
	echo "Logged out. Go to <a href=\"login.php\"> login </a> page.";
	$flag = true;
}
//If user logged in, display search entry page
if(isset($_SESSION['username']) && isset($_SESSION['password']))
{
	//Session validation
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
<link rel="stylesheet" href="http://www.w3.org/StyleSheets/Core/Chocolate" type="text/css">

<title>LinkGit home</title>

<style type="text/css">
  table{
    border-collapse: collapse;
    border: 1px solid black;
  }
  table td{
    border: 1px solid black;
  }
</style>

</head>

<body>
<h1> LinkGit Home </h1>
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
<p>(Enter 1 or more tags separated by commas e.g.:music,album,band)</p>

<input type="submit">
</form>


_END;


if(isset($_POST['searchTag']))
{
	
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$tags = $_POST['searchTag'];
	
	//Convert string of individual tags into individual tags
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
	
	echo 
	"<table border=\"2\" width=\"100%\">
        <col style=\"width:25%\">
        <col style=\"width:30%\">
        <col style=\"width:30\">
        <thead>
        <tr>
                <th>Title</th>
                <th>Comment</th>
                <th>Link</th>
                <th>Tags</th>
        </tr>
        </thead>
        <tbody>";
	
	for($i = 0; $i < sizeof($arrTags); $i++)
	{
		$query1 = "SELECT title,comment,link,id FROM entries 
			WHERE id IN (SELECT idEntry FROM mapEntryTag WHERE contentTag='$arrTags[$i]')";
		$result1 = mysql_query($query1);
		if(!$result1) die ("Database access failed:" . mysql_error());
		
		$numRowsResult1 = mysql_num_rows($result1);
		
		for($j = 0; $j < $numRowsResult1; $j++)
		{
			$rowResult1 = mysql_fetch_row($result1);
			
			$idTemp = $rowResult1[3];
			
			//Get tags associated to 1 entry
			$query2 = "SELECT contentTag FROM mapEntryTag 
			WHERE idEntry='$rowResult1[3]'";
			$result2 = mysql_query($query2);
			if(!$result2) die ("Database access failed:" . mysql_error());
			
			
			$numRowsResult2 = mysql_num_rows($result2);
			
			for($t = 0; $t < $numRowsResult2; $t++)
			{
				$rowResult2 = mysql_fetch_row($result2);
				$arrTagsEntry[] = $rowResult2[0];
			}
			
			$strTagsEntry = implode(',', $arrTagsEntry);
			
			//Display results
			if($arrFlags[$idTemp] == "")
			{
				echo "<tr>";
				
				for($k = 0; $k < 3; $k++)
				{
					echo "<td>";
					echo "$rowResult1[$k] ";
					echo "</td>";
				}
				
				echo "<td>";
				echo "$strTagsEntry";
				echo "</td>";
				
				echo "</tr>";
				
				$arrFlags[$idTemp] = 1;
				
			}
			
			$arrTagsEntry = array(0 => "");
			$strTagsEntry = "";
	
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