<?php if(!isset($RUN)) { exit(); } ?>
<?php
    

    access::menu("roles");
    access::has("view_roles",2);

    require "extgrid.php";
    
    $hedaers = array("&nbsp;",NAME,"&nbsp;","&nbsp;","&nbsp;");
    $columns = array("role_name"=>"text");

    
    $grd = new extgrid($hedaers,$columns, "index.php?module=roles");
    $grd->sort_headers = array(NAME=>"role_name");
    $grd->edit_link="index.php?module=add_edit_role";
    $grd->exp_enabled=false;
    
    if(!access::has("delete_role")) $grd->delete_text = "";
    if(!access::has("edit_role")) $grd->edit_text = "";        
    
    $grd->auto_id=true;
    $access_management = access::has("acc_man") == true ? ACCESS_MANAGEMENT : "";
    $grd->id_links=array($access_management=>"?module=access_management");       
    $grd->modify_system_rows = false;
    
    if($grd->IsClickedBtnDelete() && access::has("delete_role"))
    {
       orm::Delete("roles", array("id"=>$grd->process_id, "system_row"=>"0"));
    }
    
    $grd->default_sort="id desc";
    $query = orm::GetSelectQuery("roles", array(), array(), $grd->GetSortQuery(), $auto_search=true);
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("role_name"));
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }  

    function desc_func()
    {
            return USER_ROLES;
    }



?>
