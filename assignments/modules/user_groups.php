<?php if(!isset($RUN)) { exit(); } ?>
<?php
    

    access::menu("user_groups");
    access::has("view_groups",2);

    require "extgrid.php";
    
    $hedaers = array("&nbsp;",NAME,DEFAULT_FOR_SELF_USERS,"&nbsp;","&nbsp;");
    $columns = array("group_name"=>"text","is_default"=>"text");

    
    $grd = new extgrid($hedaers,$columns, "index.php?module=user_groups");
    $grd->edit_link="index.php?module=add_edit_usergroup";
    $grd->exp_enabled=false;
    $grd->modify_system_rows=false;
    $grd->system_column = "is_default";
    
    $grd->column_override = array("is_default"=>"is_default_override");
    
    function is_default_override($row)
    {
        return $row["is_default"] == "1" ? O_YES : O_NO;
    }
    
    if(!access::has("delete_user_group")) $grd->delete_text = "";
    if(!access::has("edit_user_group")) $grd->edit_text = "";
    
    $grd->auto_id=true;           
    
    if($grd->IsClickedBtnDelete() && access::has("delete_user_group"))
    {
       orm::Delete("user_groups", array("id"=>$grd->process_id));
    }
    
    $grd->sort_headers = array(NAME=>"group_name");
    $grd->default_sort="id desc";
    
    $query = orm::GetSelectQuery("user_groups", array(), au::arr_where(array()), $grd->GetSortQuery(), $auto_search=true);
   
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("group_name"));
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }

    

    function desc_func()
    {
            return USER_GROUPS;
    }



?>
