<?php
session_start();

require_once "dblog.php";
require_once "security.php";

$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if(!$db_server) die ("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());

$username;
$password;

if(isset($_POST['username']) && isset($_POST['password']))
{
	$username = sanitizeString($_POST['username']);
	$password = sanitizeString($_POST['password']);
	
	$query = "SELECT username, password FROM users WHERE username ='$username' 
		AND password = '$password'";
	
	$result = mysql_query($query);
	
	if(!$result) die ("Database access failed: " . mysql());
		
	$row = mysql_fetch_row($result);
	
	if($username == $row[0] && $password == $row[1])
	{
		echo "Login succesful, click <a href=\"home.php\"> here </a>";
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
	}
	else
	{
		echo "login information is incorrect";
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
	}
	
}

echo <<< _END

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
<link rel="stylesheet" href="http://www.w3.org/StyleSheets/Core/Chocolate" type="text/css">
<title>LinkGeet login</title>
</head>

<body>
<h1> LinkGit! </h1>
<h2> Keep track, share, discover.</h2>

<form method="post" action"login.php">
username: <input type="text" name="username" /> <br>
password: <input  type="password" type="text" name="password" /> <br>

<input type="submit">
</form>
<a href="register.php">Sign Up</a>
</body>
</html>

_END;

?>

