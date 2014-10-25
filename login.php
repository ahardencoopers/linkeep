<?php

require_once "dblog.php"

$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if(!$db_server) die ("Unable to connect to MySQL: " . mysql_error());

mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
echo <<< _END

<!doctype html>	

<html lang="en">
<head>

<title>linkeep login</title>

</head>

<body>
<p> hello world</p>
</body>
</html>

_END;

?>

