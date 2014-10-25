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

_END;

echo <<< _END3

<style type="text/css">
  table{
    border-collapse: collapse;
    border: 1px solid black;
  }
  table td{
    border: 1px solid black;
  }
</style>

_END3;

echo 
	"<table border=\"1\" width=\"100%\">
        <col style=\"width:40%\">
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

$queryFetch1 = "SELECT id,title,comment,link FROM entries WHERE userFK='$username'";
$resultFetch1 = mysql_query($queryFetch1);
if(!$resultFetch1) die ("Database access failed: " . mysql_error());

$numRowsFetch1 = mysql_num_rows($resultFetch1);

for($i=0; $i<$numRowsFetch1; $i++)
{
	$rowFetch1 = mysql_fetch_row($resultFetch1);
	
	$title = $rowFetch1[1];
	$comment = $rowFetch1[2];
	$link = $rowFetch1[3];
	
	$queryFetch2 = "SELECT contentTag FROM mapEntryTag WHERE idEntry='$rowFetch1[0]'";
	$resultFetch2 = mysql_query($queryFetch2);
	if(!$resultFetch2) die ("Database access failed: " . mysql_error());
	
	$numRowsFetch2 = mysql_num_rows($resultFetch2);
	for($j=0; $j<$numRowsFetch2; $j++)
	{
		$rowFetch2 = mysql_fetch_row($resultFetch2);
		$arrTags[$j] = $rowFetch2[0];
	}
	
	$tags = implode(',', $arrTags);
	
	echo"
        
        <tr>
                <th>$title</th>
                <td>$comment</td>
                <td>$link</td>
                <td>$tags</td>
        </tr>";
}

echo "</tbody>
</table>";

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
