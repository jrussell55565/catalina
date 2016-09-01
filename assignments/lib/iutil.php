<?php


class iutil {
    public static function get_asg_status_img($status,$paused)
    {
        $img = "t_red.png";
        if($status==1)
        {
            $img = "t_green.png";
        }
        else if($status==2)
        {
            $img = "t_red.png";
        }
        if($paused==1)
        {
            $img = "t_yellow.png";
        }
        return "<img align=center border=0 src='style/i/".$img."' />";
    }
    
    public static function get_quiz_status_img($status,$paused)
    {
        $img = "t_red.png";
        if($status==1)
        {
            $img = "t_green.png";
        }
        else if($status==2 || $status==3 || $status==4)
        {
            $img = "t_red.png";
        }
  
        if($paused==1)
        {
            $img = "t_yellow.png";
        }
        return "<img align=center width='13px' border=0 src='style/i/".$img."' />";
    }
    
    public static function get_chk_all($chk_id,$onclick="")
    {     
        if($onclick!="") $onclick = "$onclick()";
        return "<input type=checkbox name=chkAll2 class='els' onclick='grd_select_all(document.getElementById(\"form1\"),\"$chk_id\",\"this.checked\");$onclick'>";
    }
    
    public static function get_access_hide($access=3)
    {
        $hide_html = "";
        if(access::UserInfo()->role_system_row==$access)
        {
            $hide_html="style='display:none'";
        }
        return $hide_html;
    }
    
    public static function ifempty($text)
    {
        if(trim($text) == "") $text="&nbsp;";
        
        return $text;
    }
    
    public static function role_empty($role,$str)
    {
        if($role==access::UserInfo()->role_system_row)
        {
            $str ="";
        }
        return $str;
    }
    
    
}


?>
