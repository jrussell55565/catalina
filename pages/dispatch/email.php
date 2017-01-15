<?php
session_start();
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$username = $_SESSION['drivername'];
$email = $_SESSION['email'];
$subject = $_POST['btn_submit'];
if (empty($_POST['hawbsearch'])) { $hawb = "[blank]"; }else{ $hawb = $_POST['hawbsearch']; };
$clockHawb = $_POST['hdn_clock'];
$dispatchRequest = $_POST['txt_dispatch'];

# Ticket LWS-EBG-4789
$control = $_POST['Control'];

switch ($subject)
{
	case "Request Shipments":
		$subject = "Request Shipments";
		$to	= "dispatch@catalinacartage.com";
		$headers = "From: $email" . "\r\n" .
    		"Reply-To: $email" . "\r\n" .
    		"CC: $email" . "\r\n" .
    		"CC: dispatch@catalinacartage.com" . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		break;
	case "Clock In":
		$subject = "Clock In";
		$to	= "accounting@catalinacartage.com";
		$headers = "From: $email" . "\r\n" .
    		"Reply-To: $email" . "\r\n" .
    		"CC: $email" . "\r\n" .
    		"CC: dispatch@catalinacartage.com" . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		break;
	case "Clock Out":
		$subject = "Clock Out";
		$to	= "accounting@catalinacartage.com";
		$headers = "From: $email" . "\r\n" .
    		"Reply-To: $email" . "\r\n" .
    		"CC: $email" . "\r\n" .
    		"CC: dispatch@catalinacartage.com" . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		break;
	case "Email Dispatch":
        if ($control == 'FSITUS' || $control == 'FSIBK') {
            $to = "operations@freightservices.net";
        }else{
		    $to	= "dispatch@catalinacartage.com";
        }
		$subject = "Driver Missing HWB";
		$headers = "From: $email" . "\r\n" .
    		"Reply-To: $email" . "\r\n" .
    		"CC: $email" . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		$body = "HWB $hawb was not found in the Drivers Dispatch Board.\nDriver $username was not able to find this shipment when trying to update.\n";
		$body .= "Please Send Agent Dispatch immediately so driver can update shipment accordingly.\n\n";
        $body .= "Details entered by driver:\n";
        $body .= "$dispatchRequest\n";
		$body .= "Thank you";
		break;
	case "Forgot Credentials":
                $subject = "Forgot Credentials";
                $to     = "dispatch@catalinacartage.com";
		$email = $_POST['email'];
                $headers = "From: $email" . "\r\n" .
                "Reply-To: $email" . "\r\n" .
                "CC: $email" . "\r\n" .
                "CC: dispatch@catalinacartage.com" . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
		$body = "Please email my login credentials to me.\n";
		$body .= "Name: ".$_POST['DriverUserName']."\n";
		$body .= "Number: ".$_POST['Phone']."\n";
		break;
}

if (! $body)
{
	$body = "User:\t\t$username\nSubject:\t\t$subject\nDate:\t\t$localdate\nTime:\t\t$localtime\nHAWB:\t\t$clockHawb";
}

mail($to, $subject, $body, $headers);
header("Location: orders.php");

?>
