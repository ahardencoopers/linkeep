<?php

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
	
	$query = "SELECT username FROM users WHERE username ='$username' 
		AND password = '$password'";
	
	$result = mysql_query($query);
	
	if(!$result) die ("Database access failed: " . mysql());
	
	$rows = mysql_num_rows($result);
	
	for($i=0; i < $rows; $i++)
	{
		$row = mysql_fetch_row($result);
		for($k = 0; k < 1; $k++)
		{
			echo "$row[$k]";
		}
	}
	
}

echo <<< _END

<!doctype html>	

<html lang="en">
<head>

<title>linkeep login</title>

</head>

<body>
<h1> Linkeep! </h1>
<h2> Keep track, share, discover.</h2>

<form method="post" action"login.php">
username: <input type="text" name="username" /> <br>
password: <input type="text" name="password" /> <br>

<input type="submit">
</form>
<a href="register.php">Sign Up</a>
</body>
</html>

_END;

?>

