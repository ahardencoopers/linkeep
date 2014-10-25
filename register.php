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
echo "Form was filled incorrectly.";
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

<!doctype html>	

<html lang="en">
<head>

<title>linkeep register</title>

</head>

<body>

<h1>Register</h1>

<form method="post" action"register.php">

username: <input type="text" name="username"/> <br>
<br>
Sex: <br>
<input type="radio" name="sex" value="male" />Male <br>
<input type="radio" name="sex" value="female" /> Female <br>
<br>
<br>
email: <input type="text" name="email"/> <br>
<br>
<br>

password: <input type="text" name="password"/> <br>
confirm: <input type="text" name="confirm"/> <br>

<input type="submit">
</form>

</body>

<pre>
	Entered data
	$username
	$sex
	$email
	$password
	$confirm
	$date
</pre>

</html>

_END;

?>

