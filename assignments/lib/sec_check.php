<?php
    
    if(IP_CHECK_ENABLED=="yes")
    {
        $is_banned = ip_util::is_banned();
        if($is_banned==true)
        {
            echo IP_IS_BLACKLISTED." (".$_SERVER['REMOTE_ADDR'].")";
            exit();
        }
    }

?>