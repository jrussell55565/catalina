<?php

class access_db {
    
    public static function GetModules($txtLogin,$txtPass,$txtPassImp,$check_pass=true)
    {
        $query= "select m.*, u.UserID, r.system_row, m2.file_name as default_page, u.user_type, r.access_type,u.branch_id, b.branch_name, u.UserName, u.Password,u.UserID as user_id, u.password,u.Name,u.Surname,u.email, 0 as imported,user_photo,allow_export from users u " .
                "left join roles_rights rr on rr.role_id = u.user_type " .
                "left join roles r on r.id=u.user_type " .
                "left join modules m2 on m2.id=r.default_page ".
                "left join modules m on m.id= rr.module_id ".
                "left join branches b on b.id=u.branch_id ".
                "where (u.UserName='$txtLogin' || u.email='$txtLogin') and disabled=0 and approved=1 ";
        if($check_pass==true) $query.="and Password='$txtPass' ";
        $query.=" union ";
        $query.="select m.*,u.UserID, 2, m2.file_name as default_page, r.id ,1 as access_type,u.branch_id, b.branch_name, u.UserName, u.Password, u.UserID as user_id, u.password,u.Name,u.Surname,u.email, 1 as imported,user_photo,allow_export from v_imported_users u ".
                " left join roles r on r.system_row=2 " .
                " left join roles_rights rr on rr.role_id = r.id ".                
                " left join modules m2 on m2.id=rr.module_id ".
                " left join modules m on m.id= rr.module_id ".
                " left join branches b on b.id=u.branch_id ".
                " where (u.UserName='$txtLogin' || u.email='$txtLogin') ";
        if($check_pass==true) $query.=" and u.Password='$txtPassImp' ";
        $query.=" order by priority ";        
        
        return db::GetResultsAsArray($query);
    }   
    
     public static function GetModulesByAppUser($user_name,$email,$txtPassImp,$check_pass=true)
    {
        $query= "select m.*, u.UserID, r.system_row, m2.file_name as default_page, u.user_type, r.access_type,u.branch_id, b.branch_name, u.UserName, u.Password,u.UserID as user_id,u.Name,u.Surname,u.email, 2 as imported,'' as user_photo,allow_export,u.app_id from app_users u " .
                "left join roles_rights rr on rr.role_id = u.user_type " .
                "left join roles r on r.id=u.user_type " .
                "left join modules m2 on m2.id=r.default_page ".
                "left join modules m on m.id= rr.module_id ".
                "left join branches b on b.id=u.branch_id ".
                "where u.email='$email' and disabled=0  "; 
                if($check_pass==true) $query.=" and u.Password='$txtPassImp' ";
                $query.=" order by priority "; 
                //echo $query;
               // exit();
        return db::GetResultsAsArray($query);
    } 
    
    public static function HasAccess($txtLogin,$txtPass)
    {
        $user_id=-1;
        $results = access_db::GetModules($txtLogin, "", "", false);
        $has_result = db::num_rows($results);      
        if($has_result!=0)
        {
            $row = db::fetch($results);
            if($row['imported']=="0") $password = Local_Users_Password_Hash($txtPass) ;
            else $password = Imported_Users_Password_Hash(trim($_POST['txtPass']), $row['password']);

            if($password==$row['password'])
            {                
                $user_id=$row['user_id'];
            }
        }
        return $user_id;
    }
    
    public static function GetAccessList($role_id)
    {
        $query = "SELECT * FROM roles_access_rights rar LEFT JOIN modules_access ma ON ma.id = rar.access_id WHERE role_id=$role_id";        
        return db::GetResultsAsArray($query);
    }
    
    public static function GetPageList($role_id,$where = "",$columns="p.id,page_name,priority,parent_id,page_type,link_url")
    {
        $query = "SELECT $columns FROM pages p INNER JOIN roles_pages rp ON rp.page_id=p.id WHERE rp.role_id=$role_id $where";       
        return db::GetResultsAsArray($query);
    }
    
    public static function GetBalance($user_id)
    {
        $query = "SELECT IFNULL(SUM((CASE when dbt_crd =2 THEN 1 ELSE -1 end) * po.mc_gross),0) AS balance
                FROM payment_orders po 
                WHERE po.user_id = $user_id";  
       return db::GetResultsAsArray($query);
    }
    
    public static function GetViewsList($role_id)
    {
        $query = "SELECT * FROM ticket_views tv
                INNER JOIN tview_role_xreff trx ON trx.tview_id = tv.id
                WHERE trx.role_id=$role_id";
        return db::GetResultsAsArray($query);
    }
    
   
    

}
?>
