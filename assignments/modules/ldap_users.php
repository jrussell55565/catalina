<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("ldap_users");
    access::has("view_ldap_users",2);

    require "extgrid.php";
    require "db/users_db.php";
    //require "events/users_events.php";

    $hedaers = array("&nbsp;",  USER_NAME, USER_SURNAME, USER_TYPE, EMAIL,"&nbsp;","&nbsp;","&nbsp;","&nbsp;");
    $columns = array("Name"=>"text","Surname"=>"Surname","type_name"=>"text","email"=>"text");
                         
    $exp_hedaers = array(USER_NAME, USER_SURNAME, USER_TYPE, EMAIL); 
    
    $grd = new extgrid($hedaers,$columns, "index.php?module=ldap_users");
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->edit_link="index.php?module=add_edit_ldap_user";
    $grd->id_column="UserID";
    $grd->column_override=array("type_name"=>"user_type_override");
    $grd->auto_id=true;
    //$grd->modify_system_rows=false;
    $user_quizzes = access::has("app_usr_dtls") ? DETAILS : "";
    $ip_rests  = access::has("ldap_ip_res") ? IP_RESTRCITIONS : "";    
    $grd->id_links=array($ip_rests=>"?module=ip_res&type=3",$user_quizzes=>"?module=user_details");
    
    if(!access::has("delete_ldap_user")) $grd->delete_text = "";
    if(!access::has("edit_ldap_user")) $grd->edit_text = "";

  
    function user_type_override($row)
    {
      //  global $USER_TYPE;
     //   return $USER_TYPE[$row['user_type']];
        return $row["role_name"];
    }     

    if($grd->IsClickedBtnDelete() && access::has("delete_ldap_user"))
    {            
       orm::Delete("app_users", au::arr_where(array("UserID"=>$grd->process_id)));       
       orm::Delete("user_payment_accounts", array("user_id"=>$grd->process_id));
    }

    $grd->sort_headers = array(LOGIN=>"UserName",  USER_NAME=>"Name", USER_SURNAME=>"Surname", USER_TYPE=>"user_type");
    $grd->default_sort="added_date desc";
    $query = users_db::GetAppUsersQuery("",au::get_where(true), $grd->GetSortQuery(),4);
   
    //$grd->search= access::UserInfo()->access_type == 1 ? false :  true;
    $grd->search = true;
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array( USER_NAME, USER_SURNAME),array( "Name", "Surname"));

    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }
    if(isset($_GET["expgrid"]))
    {
        $grd->Export();
    }

    function desc_func()
    {
        return LDAP_USERS;
    }

?>
