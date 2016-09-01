<?php if(!isset($RUN)) { exit(); } ?>
<?php

    require "extgrid.php";
    require "lib/logs.php";  

    $hedaers = array("&nbsp;",USER_NAME,  LOG_TYPE, IP_ADDRESS, ADDED_DATE, HEADERS, "&nbsp;");
    $columns = array("FullName"=>"text", "log_type"=>"text","ip_address"=>"text","inserted_date"=>"text","headers"=>"text");
    
    $exp_hedaers = array(USER_NAME,LOG_TYPE, IP_ADDRESS, ADDED_DATE, HEADERS);    

    $grd = new extgrid($hedaers,$columns, "index.php?module=user_logs");
    $grd->sort_headers = array(USER_NAME=>"FullName",LOG_TYPE=>"log_type",IP_ADDRESS=>"ip_address", ADDED_DATE=>"inserted_date");
    $grd->row_info_table="user_logs";
    $grd->main_table="user_logs";
    $grd->auto_delete=true;
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->edit=false;    
    $grd->column_override=array("headers"=>"headers_override", "log_type"=>"log_type_override","FullName"=>"full_name_override");
    $grd->auto_id=true;    
   
    $grd->default_sort = "inserted_date desc";
    $query = logs::get_logs("",au::get_where(false), $grd->GetSortQuery());    
    $grd->search= access::UserInfo()->access_type == 1 ? false :  true;
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(USER_NAME, IP_ADDRESS),array("FullName", "ip_address"));
    
    function headers_override($row)
    {
        global $grd;
        //str_replace("\n", "<br />", $row['headers'])
        return extgrid::GetModalRowTemplate(HEADERS, str_replace("\n", "<br />", $row['headers']), $grd);
    }
    
    function log_type_override($row)
    {
        global $LOG_TYPES;
        return $LOG_TYPES[$row['log_type']];
    }
    
    function full_name_override($row)
    {        
        $text = $row['FullName'];
        if($row['user_type']=="1") $text ="<a href='?module=add_edit_user&id=".$row['UserID']."'>$text</a>";
        else if($row['user_type']=="3") $text ="<a href='?module=add_edit_fb_user&id=".$row['UserID']."'>$text</a>";
        return $text;
    }

    if(isset($_POST["ajax"]))
    {
          if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    }
    if(isset($_GET["expgrid"]))
    {
        $grd->Export();
    }
    
    function desc_func()
    {
        return LOGS;
    }

?>

