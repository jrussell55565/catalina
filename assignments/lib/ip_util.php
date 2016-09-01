<?php

class ip_util
{
    public static function is_banned()
    {
        $ip = db::clear($_SERVER['REMOTE_ADDR']);
        $sql = "select ip_address from ip_banned_list where ip_address like '".$ip."%' ";
        $results = db::exec_sql($sql);
        if(db::num_rows($results)>0) return true;
        return false;
    }
    
    public static function ip_has_access($user_id)
    {
        $ip = db::clear($_SERVER['REMOTE_ADDR']);
        $sql = "SELECT * FROM (
                SELECT * FROM ip_res ir 
                WHERE ir.user_id=$user_id AND ir.ip_address LIKE '".$ip."%'
              ) ipres
              RIGHT JOIN (SELECT COUNT(*) AS allow_count FROM ip_res WHERE user_id=$user_id AND allow=1) ip_a_count ON 1=1";
        
        $results = db::exec_sql($sql);
        while ($row = db::fetch($results))
        {
            if($row['user_id']=="")
            {
                if(intval($row['allow_count'])>0) return false;
                else return true;
            }
            else
            {
                if($row['allow']=="1") return true;
                if($row['allow']=="0") return false;
            }
        }
        return true;
    }
    
}

?>