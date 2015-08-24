<?php
session_start();
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$body = $_POST['vtext_broadcast'];
$subject = "Broadcast Message";
$email = "dispatch@catalinacartage.com";
$headers = "From: $email" . "\r\n" .
"CC: jaime.russell@catalinacartage.com" . "\r\n" .
'X-Mailer: PHP/' . phpversion();

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
$result = mysql_query("select vtext from users where vtext != \"\"");

while ($row = mysql_fetch_array($result, MYSQL_BOTH))
{
	$to = $row['vtext'];
	mail($to, $subject, $body, $headers);
}

header("Location: admin.php");

?>
