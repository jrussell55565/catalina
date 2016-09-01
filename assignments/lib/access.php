<?php
class access
{      
    
     
    public static function SetCredentials($results)
    {        
        $access_list = access_db::GetAccessList($results[0]['user_type']);
        $page_list = access_db::GetPageList($results[0]['user_type']);
        $view_list = access_db::GetViewsList($results[0]['user_type']);
        $balance = access_db::GetBalance($results[0]['UserID']);
        $userinfo = new UserInfo($results,$access_list,$page_list,$view_list,$balance);
        $_SESSION["user_info"] = serialize($userinfo);
    }
    
    public static function has($key, $redirect=0)
    {
        $has_access = access::UserInfo()->CheckAccess($key);        
        if($has_access==false && $redirect==1) util::redirect("login.php");
        else if($has_access==false && $redirect==2) util::redirect("?module=no_access");        
        return $has_access;
    }
    
    public static function menu($key, $redirect=true)
    {
        $has_access = access::UserInfo()->CheckMenuAccess($key);        
        if($has_access==false && $redirect==true) util::redirect("login.php");
        return $has_access;
    }
    
    public static function UserInfo()
    {
        if(!isset($_SESSION["user_info"])) util::redirect("login.php");
        $userinfo = unserialize($_SESSION["user_info"]);
        return $userinfo;
    }
    
    public static function Save($userinfo)
    {
        $_SESSION["user_info"] = serialize($userinfo);
    }
    
    public static function Clear()
    {
        $_SESSION["user_info"] = null;
        unset($_SESSION['app_admin']);
    }
    
    public static function check_autorize($redirect = true)
    {
        $autorized=false;
        if(isset($_SESSION["user_info"])) $autorized = true;
        
        
        if($autorized==false && $redirect==true) util::redirect("login.php");
        
        return $autorized;
    }
    
    public static function display($access_key)
    {
        $style = "";
        if(!access::has($access_key)) $style = "style='display:none'";
        return $style;
    }
    
    public static function set_app_admin()
    {
        $_SESSION['app_admin'] = "1";
    }
    
    public static function get_app_admin()
    {
        $is_app_admin = false;
        if(isset($_SESSION['app_admin']))
        {
            $is_app_admin = true; 
        }
        return $is_app_admin;
    }
    
}

class UserInfo
{
    var $login;
    var $password;
    var $imp_password;
    var $user_id;
    var $user_type;
    var $imported;
    var $name;
    var $surname;
    var $email;
    var $access_type;
    var $branch_id;
    var $branch_name;
    var $user_photo;
    var $allow_export;
    
    var $access_list ;
    var $modules_list;
    var $page_list;
    var $view_list;
    
    var $app_id = -1;
    
    var $balance = 0;
       
    public function UserInfo($results,$access_list,$page_list,$view_list,$balance)
    {
        $this->user_id = $results[0]["user_id"];
        $this->role_id = $results[0]["user_type"];
        $this->user_type= $results[0]["user_type"];
        $this->login = $results[0]["UserName"];
        $this->password = $results[0]["Password"];
        $this->imp_password = $results[0]["Password"];
        $this->name = $results[0]["Name"];
        $this->surname = $results[0]["Surname"];
        $this->login = $results[0]["UserName"];
        $this->email = $results[0]["email"];
        $this->access_type = $results[0]["system_row"] =="2" ? 1 : $results[0]["access_type"];
        $this->branch_id = $results[0]["branch_id"];
        $this->branch_name = $results[0]["branch_name"];
        $this->user_photo = $results[0]["user_photo"];
        $this->imported = $results[0]["imported"];
        $this->allow_export = $results[0]["allow_export"];
        $this->access_list=$access_list;
        $this->modules_list = $results;
        $this->page_list = $page_list;
        $this->view_list = $view_list;
        $this->app_id = isset($results[0]["app_id"]) ? $results[0]["app_id"] : -1;
        $this->balance = $balance[0]["balance"];
       
    }          
    
    public function CheckAccess($access_key)
    {
        $has_access = false;             
        //echo count($this->access_list);
        for($i=0;$i<count($this->access_list);$i++)
        {         
            if($this->access_list[$i]["access_key"]==$access_key)
            {
                $has_access = true;
                break;
            }
        }        
        return $has_access;
    }
    
    public function CheckMenuAccess($access_key)
    {
        $has_access = false;             
        for($i=0;$i<count($this->modules_list);$i++)
        {
            if($this->modules_list[$i]["access_key"]==$access_key)
            {
                $has_access = true;
                break;
            }
        }
        return $has_access;
    }
    
    public function UpdateBalance()
    {
        $balance = access_db::GetBalance($this->user_id);
        $this->balance = $balance[0]["balance"];
        access::Save($this);
    }
}

class au 
{
    public static function get_where($and=true,$prefix="",$add_anyway=false)
    {
        $where = "";
        $prefix = $prefix=="" ? "" : $prefix.".";
        if(access::UserInfo()->access_type == 2)
        {
            $and_q = $and == true ? " AND " :  " WHERE ";
            $where=$and_q." $prefix"."branch_id=".access::UserInfo()->branch_id." ";
        }
        else if(access::UserInfo()->access_type == 3)
        {
            $and_q = $and == true ? " AND " :  " WHERE ";
            $where=$and_q." $prefix"."inserted_by=".access::UserInfo()->user_id." ";
        }
        else 
        {
            $and_q = $and == true ? " AND " :  " WHERE ";
            if($add_anyway==true) $where = $where=$and_q." 1=1 ";
        }
        return $where;
    }
    
    public static function set_uwhere($and=true)
    {
        $where = "";
        if(access::UserInfo()->access_type == 2)
        {            
            $where=" , updated_by=".access::UserInfo()->user_id.", updated_date='".util::Now()."' ";
        }
        return $where;
    }
    
    public static function arr_where($array)
    {
        if(access::UserInfo()->access_type == 2)
        {            
            $array['branch_id'] = access::UserInfo()->branch_id;
        }
        else if(access::UserInfo()->access_type == 3)
        {
            $array['inserted_by'] = access::UserInfo()->user_id;
        }
        return $array;
    }
    
    public static function arr_where_brn($array)
    {
        $array['branch_id'] = access::UserInfo()->branch_id;
        return $array;
    }
    
    public static function add_insert($array)
    {                   
       $array['branch_id'] = access::UserInfo()->branch_id;
       $array['inserted_by'] = access::UserInfo()->user_id;
       $array['inserted_date'] = util::Now();       
       return $array;
    }
    
    public static function add_update($array)
    {                   
       //$array['branch_id'] = access::UserInfo()->branch_id;
       $array['updated_by'] = access::UserInfo()->user_id;
       $array['updated_date'] = util::Now();       
       return $array;
    }
    
    public static function view_where()
    {
        $sql = "";
        $arr = access::UserInfo()->view_list ;
        $y = 0;
        for($i=0;$i<count($arr);$i++)
        {
            $row = $arr[$i];
            $query = $row['sql_query'];
            if($query=="") continue;
            
            $add = $y==0 ? "" : "or";
            $sql.= " $add ( $query ) ";
            $y++;                        
        }
        
        if($sql!="")
        {
            $sql = " and ( $sql )";
            $sql = str_replace("[user_id]" ,access::UserInfo()->user_id, $sql);
            $sql = str_replace("[branch_id]" ,access::UserInfo()->branch_id, $sql);
        }
        return $sql;
        
    }
    
    public static function view_in_where($prefix="")
    {
        $sql = "";
        $arr = access::UserInfo()->view_list ;        
        for($i=0;$i<count($arr);$i++)
        {
            $row = $arr[$i];
            $id = $row['id'];            
            
            $add = $i==0 ? "" : ",";
            $sql.= " $add $id ";                                  
        }
        
        $sql = " and ".$prefix."id in ( $sql ) ";
        return $sql;
   
    }
    
    
}

?>
