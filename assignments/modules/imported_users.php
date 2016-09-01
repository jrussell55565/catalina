<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("imported_users");
    access::has("view_imp_users",2);

    require "extgrid.php";
    require "db/users_db.php";

    $hedaers = array("&nbsp;",LOGIN,  USER_NAME, USER_SURNAME, EMAIL,"&nbsp;","&nbsp;");
    $columns = array("UserName"=>"text", "Name"=>"text","Surname"=>"Surname","email"=>"text");

    $exp_hedaers = array(LOGIN,  USER_NAME, USER_SURNAME, EMAIL); 
    
    $grd = new extgrid($hedaers,$columns, "index.php?module=imported_users");
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->id_column="UserID";
    $grd->auto_id=true;
    $grd->edit = false;
    $grd->delete=false;
    $grd->search= access::UserInfo()->access_type == 1 ? false :  true;
    $user_quizzes = access::has("imp_usr_dtls") ? DETAILS : "";
    $ip_rests  = access::has("local_ip_res") ? IP_RESTRCITIONS : "";    
    $grd->id_links=array($ip_rests=>"?module=ip_res&type=2",$user_quizzes=>"?module=user_details");

    $grd->sort_headers = array(LOGIN=>"UserName",  USER_NAME=>"Name", USER_SURNAME=>"Surname");
    $grd->default_sort="UserID desc";
    
    $query = orm::GetSelectQuery("v_imported_users", array(), au::arr_where(array()), $grd->GetSortQuery(),true);
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(LOGIN, USER_NAME, USER_SURNAME),array("UserName", "Name", "Surname"));

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
        return IMPORTED_USERS;
    }

?>
