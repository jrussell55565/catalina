<?php if(!isset($RUN)) { exit(); } ?>
<?php
    

    access::menu("branches");
    access::has("view_branch",2);

    require "extgrid.php";
    
    $hedaers = array("&nbsp;",NAME,LINK_FOR_REG,"&nbsp;","&nbsp;");
    $columns = array("branch_name"=>"text","uniq_link"=>"text");
    
    
    $grd = new extgrid($hedaers,$columns, "index.php?module=branches");
    $grd->edit_link="index.php?module=add_edit_branch";
    $grd->modify_system_rows=false;
    $grd->exp_enabled=false;
    $grd->auto_id=true;        
    $grd->column_override = array("uniq_link"=>"uniq_link_override");
    
    if(!access::has("delete_branch")) $grd->delete_text = "";
    if(!access::has("edit_branch")) $grd->edit_text = "";
     
    if($grd->IsClickedBtnDelete() && access::has("delete_branch"))
    {
       orm::Delete("branches", array("id"=>$grd->process_id));
    }
    
    $query = orm::GetSelectQuery("branches", array(), array(), "id desc", $auto_search=true);
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("branch_name"));
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }

    function uniq_link_override($row)
    {
        if($row['self_reg']=="0") return NOT_ENABLED;
        
        if($row['system_row']=="1")  $reg_link = WEB_SITE_URL."register.php";
        else $reg_link = WEB_SITE_URL."register.php?b=".$row['id'];
        
        return "<input style='width:400px' type=textbox disabled value='".$reg_link."' />";
    }

    function desc_func()
    {
            return BRANCHES;
    }



?>
