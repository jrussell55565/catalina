<?php
// Inialize session
session_start();
#print_r($_POST);

if ($_SESSION['login'] != 1)
{
        header('Location: orders.php');
}

if ($_POST["ck_refresh"] == 'on')
{
  setcookie('ck_refresh', 'checked');
}else{
  setcookie('ck_refresh', 'NULL');
}

if ($_POST["mapType"] == 'map')
{
  setcookie('mapType','map');
}elseif ($_POST["mapType"] == 'satellite') {
  setcookie('mapType','satellite');
}

setcookie('hdn_zoom',$_POST['hdn_zoom']);
setcookie('sel_refreshTime',$_POST['sel_refreshTime']);

header('Location: location.php');

?>
