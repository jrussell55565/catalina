<?php
session_start();

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
$message_output = null;
$header = null;

try {
    $target_file = '/tmp/'.$_SESSION['username'].'_upload.pdf';;
    if (copy("php://input", $target_file))
    {
        // Open the file and read it.
        $handle = fopen($target_file, "r");
        if ($handle) {
            $counter = 0;
            while (($line = fgets($handle)) !== false) {
                if (preg_match('/^.+filenameis_(\w+\.pdf).+$/', $line, $matches, PREG_OFFSET_CAPTURE))
                {
                    $counter++;
                    $file_name = $_SERVER['DOCUMENT_ROOT'] . '/pages/onboarding/user_uploads/' . $_SESSION['username'].'_'.$matches[1][0];
                    if (rename($target_file, $file_name))
                    {
                        $message_output = 'File upload was successful';
                        $header = 200;
                    }else{
                        throw new Exception('Unable to move '.$target_file.' to '.$file_name, 1);
                    }
                }
            }
            if ($counter == 0)
            {
                throw new Exception('Unable to determine the pdf filename in '.$target_file, 1);
            }
        }
    } else {
        throw new Exception("Unable to rename temporary file.", 1);
    }
}catch (Exception $e) {
    $message_output = $e;
    error_log($e);
    $header = 500;
}finally{
    fclose($handle);
    header('Content-Type: text/plain; charset=utf-8');
    if ($header == 500) {
        http_response_code(500);
    }else{
        http_response_code(200);
    }
    echo $message_output;
    exit;
}
?>
