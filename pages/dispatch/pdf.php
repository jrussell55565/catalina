<?php
session_start();

$target_file = '/tmp/'.$_SESSION['username'].'_upload.pdf';;
copy("php://input", $target_file);

// Open the file and read it.
$handle = fopen($target_file, "r");
if ($handle) {
    $counter = 0;
    while (($line = fgets($handle)) !== false) {
        if (preg_match('/^.+filenameis_(\w+\.pdf).+$/', $line, $matches, PREG_OFFSET_CAPTURE))
        {
            $counter++;
            $file_name = $_SERVER['DOCUMENT_ROOT'] . '/pages/onboarding/user_uploads/' . $matches[1][0];
            if (rename($target_file, $file_name))
            {
                fclose($handle);
                header('Content-Type: text/plain; charset=utf-8');
                return 'File upload was successful';
            }else{
                fclose($handle);
                header('Content-Type: text/plain; charset=utf-8');
                error_log('Unable to move '.$target_file.' to '.$file_name);
                return 'File upload failed';
            }
        }
    }
    fclose($handle);
    if ($counter == 0)
    {
        header('Content-Type: text/plain; charset=utf-8');
        error_log('Unable to find filename in '.$target_file);
        fclose($handle);
        return 'File upload failed';
    }
}
?>
