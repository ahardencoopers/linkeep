<?php

require_once "dblog.php";
require_once "security.php";

$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if(!$db_server) die ("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());

$username = "hello world";
$sex = "none";
$email = "mail";
$password = "none";
$confirm = "none";
$date = date("Y-m-d H:i:s");

if(isset($_POST['username']) && isset($_POST['email']))
{
	$username = sanitizeString($_POST['username']);
	$email = sanitizeString($_POST['email']);
	
	if(isset($_POST['password']) && isset($_POST['confirm']))
	{
		
		$password = sanitizeString($_POST['password']);
		$confirm = sanitizeString($_POST['confirm']);
		
		if($password == $confirm)
		{
			$date = date("Y-m-d H:i:s");
			$query = "INSERT INTO users VALUES('$username',
				'$sex', '$email', '$password', '$date')";
			
			$result = mysql_query($query);
			if(!$result) die ("Database access failed:" . mysql_error());
		
		}
	}
}
else
{
echo "";
}

if(isset($_POST['sex']))
{
	$sex = sanitizeString($_POST['sex']);
}

if(isset($_POST['date']))
{
	$date = sanitizeString($_POST['date']);
}

echo <<< _END

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
<link rel="stylesheet" href="http://www.w3.org/StyleSheets/Core/Traditional" type="text/css">
<title>linkeep login</title>
<title>linkeep register</title>
<style type="text/css">
    input {
    float:right;
    clear:both;
}
div{
 width: 290px;}
    </style>
</head>

<body>

<h1>Register</h1>

<form method="post" action"register.php">

<div>
username: <input type="text" name="username"/> <br>
</div>

<div>
Sex:<br>
<input type="radio" name="sex" value="male" />Male <br>
<input type="radio" name="sex" value="female" /> Female <br>
</div>

<div>
email: <input type="text" name="email" style="text-align: right"/> <br>
</div>

<div>
password:<input style="text-align: right" type="password" type="text" name="password"/> <br>
</div>

<div>
confirm password: <input  type="password"type="text" name="confirm"/> <br>
</div>

<div>
<input type="submit">
</div>

</form>

<a href="login.php">Go back</a>

</body>

</html>

_END;

?>

