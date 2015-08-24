<?php
session_start();
if ($_SESSION['login'] != 1)
{
        header('Location: orders.php');
}
include('global.php');

$content = $_POST['vtext_comments'];
file_put_contents(constant('VTEXTFILE'), $content);

header("Location: admin.php");
?>
