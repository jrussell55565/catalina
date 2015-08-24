<?php
session_start();
$email = $_SESSION['email'];

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: driverlogin.php');
}

include('global.php');

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$hawb = $_GET['hawb'];
$count = 0;

$sql = mysql_query("select count(*) as count from hawb where hawbNumber='$hawb' AND status='Picked Up'");

      	while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
      	{
		if ($row[count] > 0)
		{
			$count = 1;
		}
	}

	if ($count == 1)
	{
                $subject = "Driver Updates";
                $to     = "dispatch@catalinacartage.com";
                $headers = "From: $email" . "\r\n" .
                "Reply-To: $email" . "\r\n" .
                "CC: $email" . "\r\n" .
                "CC: dispatch@catalinacartage.com" . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
                $body = "Driver Updates\n";

		mail($to, $subject, $body, $headers);
	}
	echo $count;
?>
