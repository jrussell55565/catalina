<?php

@session_start();

$random  = rand(11111, 99999);
$_SESSION['_RANDOM'] = $random;

$im = imagecreate(80, 30);

// White background and blue text
$bg = imagecolorallocate($im, 224, 224, 224);
$textcolor = imagecolorallocate($im, 0, 0, 255);

// Write the string at the top left
imagestring($im, 5, 0, 5, " ".$random, $textcolor);

// Output the image
header('Content-type: image/png');

imagepng($im);
imagedestroy($im);
?>