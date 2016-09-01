<?php

class cert
{
    public static function get_all_certs()
    {
        $i=0;
        $dirs = array();
        $path = dirname(__FILE__).'/../certificates_folder/';
        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $dirs[$i] = $fileinfo->getFilename();
                $i++;
            }
        }
        return $dirs;
    }
}

?>
