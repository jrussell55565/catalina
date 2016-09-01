<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("ip_ban_list");
    
    if(isset($_POST['addip']))
    {
        $ip = $_POST['ip'];
        $comments= $_POST['comments'];
        if(trim($ip)!="")
        {
            orm::Insert("ip_banned_list", au::add_insert(array("ip_address"=>$ip,"comments"=>$comments)));
        }
    }
    
    require "extgrid.php";

    $hedaers = array("&nbsp;",IP_ADDRESS, COMMENTS ,"&nbsp;");
    $columns = array("ip_address"=>"text", "comments"=>"text");

    $url = util::GetCurrentUrl();    
    $grd = new extgrid($hedaers,$columns, $url);    
    $grd->row_info_table="ip_banned_list";        
    $grd->edit=false;    
    //$grd->column_override=array("allow"=>"allow_override");
    $grd->auto_id=true;     
    $grd->exp_enabled=false;
    $grd->auto_delete=true;
    $grd->main_table="ip_banned_list";
    $query = orm::GetSelectQuery("ip_banned_list", array(), array(), "inserted_date desc", $auto_search=true) ;
    $grd->search= false;
    $grd->identity = "ip_banned_list";
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(IP_ADDRESS),array("ip_address"));

    if(isset($_POST["ajax"]))
    {
          if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    }  
    

    function desc_func()
    {        
        return IP_BANNED;
    }
?>