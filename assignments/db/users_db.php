<?php

class users_db {
    public static function GetUsersQuery($where="", $access_where="",$orderby="added_date desc")
    {
        $sql ="select u.*, ut.role_name from users u left join roles ut on u.user_type=ut.id where u.system_row<>-1 $access_where [{where}] order by $orderby";
	if($where!="") $sql=str_replace("[{where}]" ,$where, $sql);        
        return $sql;
    }
    
    public static function GetUsersByRoleTypeQuery($type)
    {
        $sql = "select * from v_all_users u left join roles r on r.id=u.user_type where r.system_row=$type and disabled=0 and approved=1";
        return $sql;
    }
    
    
    public static function GetAppUsersQuery($where="", $access_where="",$order_by = "added_date desc",$app_id=3)
    {
        $sql ="select u.*, ut.role_name from app_users u left join roles ut on u.user_type=ut.id where u.app_id=$app_id $access_where [{where}] order by $order_by ";
	if($where!="") $sql=str_replace("[{where}]" ,$where, $sql);
        
        return $sql;
    }
    
    public static function LastUsersQuery()
    {
        $sql ="select u.*,c.country_name from users u inner join roles r on r.id=u.user_type left join countries c on c.id=u.country_id where r.system_row=2 ".au::get_where(true, "u")." order by added_date desc limit 0 ,10";

        return $sql;
    }

    public static function GetImportedUsersQuery($where="")
    {
        $sql ="select * from v_imported_users where 1=1 $where ".au::get_where(true)." order by name,surname";
        //@util::TestLog($sql);
       //  $sql ="select * from v_imported_users  order by name,surname";
        return $sql;
    }
    
    public static function GetLDAPUsersQuery($where="")
    {
       // $sql ="select * from v_imported_users ".au::get_where(false)." order by name,surname";
        $sql ="select * from app_users where app_id=4 $where ".au::get_where(true)." order by name,surname";
        return $sql;
    }
    
    public static function GetRolesByAccess($where_clause="")
    {
        $where = "";
        if(access::UserInfo()->access_type==2) $where.=" where access_type in (2,3) ";
        else if(access::UserInfo()->access_type==3) $where.=" where access_type in (3) ";
        
        if($where_clause!="")
        {
            $where = $where == "" ? $where." where ".$where_clause : $where." AND ".$where_clause;
        }
        $where = db::clear($where);
        $sql = "select * from roles $where order by id";
        return $sql;
    }
    
    public static function AddAppUser($app_user_id , $name, $surname,$user_name,$email,$disabled,$app_id,$branch_id,$group_id, $role_id)
    {
        $branch_id = "(select id from branches where system_row=1)";
        $role_id = "(select id from roles where system_row=2)";
        $group_id = "(select id from user_groups where is_default=1)";
        $sql = "insert into app_users (app_user_id,Name,Surname,UserName,email,disabled,app_id,branch_id,group_id,user_type) "
                . " values ($app_user_id,'$name','$surname','$user_name','$email',$disabled,$app_id,$branch_id,$group_id,$role_id) on duplicate key "
                . " update app_user_id=values(app_user_id), Name=values(Name), Surname=values(Surname), UserName=values(UserName), email=values(email) ";
      //  echo $sql;
      //  exit();
        db::exec_sql($sql);
    }
    
    public static function GetUserDetails($id)
    {
        $sql = "SELECT vau.*, b.branch_name FROM v_all_users vau
                INNER JOIN branches b ON b.id=vau.branch_id where vau.UserID=$id ".au::get_where();
        return $sql;
    }
    
}
?>
