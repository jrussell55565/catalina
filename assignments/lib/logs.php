<?php

class logs 
{
    public static function add_log($log_type,$log_text)
    {
        if(LOG_DATA!="yes") return ;
        
        orm::Insert("user_logs", au::add_insert(array("log_type"=>$log_type,"log_text"=>$log_text, "ip_address"=>$_SERVER['REMOTE_ADDR'],"headers"=>logs::get_headers())));
    }
    
    public static function add_log2($log_type,$log_text,$log_insert_id, $log_date,$ip,$log_headers)
    {     
        orm::Insert("user_logs", array("log_type"=>$log_type,"log_text"=>$log_text, "ip_address"=>$ip,"headers"=>$log_headers, "inserted_by"=>$log_insert_id, "inserted_date"=>$log_date));
    }
    
    public static function add_log3($log_type,$log_text,$log_type_id=0)
    {
        //  if(LOG_DATA!="yes" || settings::get_settings()->write_logs!=1 ) return ;
          
          orm::Insert("user_logs", au::add_insert(array("log_type"=>$log_type,"log_text"=>$log_text, "log_type_id"=>$log_type_id,"ip_address"=>$_SERVER['REMOTE_ADDR'],"headers"=>logs::get_headers())));
    }
    
    public static function get_headers()
    {
         $headers = "";
         foreach($_SERVER as $key=>$value)
         {
            $headers.="$key = $value \n ";
         }
         return $headers;
    }
    
    public static function get_logs($where="", $access_where="",$orderby="inserted_date desc")
    {
        $sql = "SELECT ul.*, vau.FullName,vau.user_type,vau.UserID FROM user_logs ul
                LEFT JOIN v_all_users vau ON vau.UserID=ul.inserted_by
                $access_where [{where}] order by $orderby
                ";
        if($where!="") $sql=str_replace("[{where}]" ,$where, $sql);
        return $sql;
    }
    

    
}

?>