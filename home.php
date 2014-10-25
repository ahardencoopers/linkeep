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

if(isset($_POST['logout']))
{
	$_SESSION['username'] = NULL;
	$_SESSION['password'] = NULL;
	echo "Logged out. Go to <a href=\"login.php\"> login </a> page.";
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

<form method="post" action"login.php">

<input type="hidden" name="logout" value="">

<input type="submit" value="Logout">

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
else
{
	echo "not logged in";
}



?>