<?php 

class fb
{
    public static function get_profile_photo($user_name,$widht,$height)
    {
         $url = FACEBOOK_PROFILE_URL;
         $url = str_replace("[USERID]",$user_name,$url);
         $url = str_replace("[WIDTH]",$widht, $url);
         //$url = str_replace("[HEIGHT]",$height,$url);
         return "<img src='$url' />";
    }

}

?>
