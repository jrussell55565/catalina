<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("logs");

    $type= util::GetKeyID("type");
    $user_id = util::GetID();

    if($type=="1"){ 
        access::menu("local_users");
        access::has("local_ip_res",2);
    }
    else if($type=="2") { 
        access::menu("imported_users");    
        access::has("imp_ip_res",2);
    }
    else if($type=="3") {
        access::menu("fb_users"); 
        access::has("fb_ip_res",2);    
    }
    else if($type=="4") {
        access::menu("ldap_users"); 
        access::has("ldap_ip_res",2);    
    }

    $results = orm::Select("v_all_users", array("FullName"), au::arr_where(array("UserID"=>$user_id)), "");
    if(db::num_rows($results)==0) util::redirect ("login.php");

    $user_row = db::fetch($results);
    $full_name = $user_row['FullName'];
    
    if(isset($_POST['addip']))
    {
        $ip = $_POST['ip'];
        $itype= $_POST['itype'];
        if(trim($ip)!="")
        {
            orm::Insert("ip_res", au::add_insert(array("ip_address"=>$ip,"allow"=>$itype, "user_id"=>$user_id)));
        }
    }
    
    require "extgrid.php";

    $hedaers = array("&nbsp;",IP_ADDRESS, TYPE ,"&nbsp;");
    $columns = array("ip_address"=>"text", "allow"=>"text");

    $url = util::GetCurrentUrl();    
    $grd = new extgrid($hedaers,$columns, $url);    
    $grd->row_info_table="ip_res";        
    $grd->edit=false;    
    $grd->column_override=array("allow"=>"allow_override");
    $grd->auto_id=true;     
    $grd->exp_enabled=false;
    $grd->auto_delete=true;
    $grd->main_table="ip_res";
    $query = orm::GetSelectQuery("ip_res", array(), array("user_id"=>$user_id), "inserted_date desc", $auto_search=true) ;
    $grd->search= true;
    $grd->identity = "ipres";
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(IP_ADDRESS),array("ip_address"));

    if(isset($_POST["ajax"]))
    {
          if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    }  
    
    function allow_override($row)
    {        
        if($row['allow']=="1") return ALLOW;
        return DENY;
    }
    

    function desc_func()
    {
        global $full_name;
        return IP_RESTRCITIONS." - ".$full_name;
    }
    

?>
