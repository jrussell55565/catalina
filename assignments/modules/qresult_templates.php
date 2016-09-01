<?php if(!isset($RUN)) { exit(); } ?>
<?php
    

    access::menu("qresult_templates");
    access::has("view_results_template",2);

    require "extgrid.php";
    
    $hedaers = array("&nbsp;",NAME,"&nbsp;","&nbsp;","&nbsp;");
    $columns = array("template_name"=>"text");

    
    $grd = new extgrid($hedaers,$columns, "index.php?module=qresult_templates");
    $grd->edit_link="index.php?module=add_edit_qresult_template";
    $grd->modify_system_rows=false;
    $grd->exp_enabled=false;
    
    $grd->id_links=(array("?module=result_levels"=>"Levels"));
    $grd->id_link_direction = ArrDirection::ValueFirst;
    
    if(!access::has("delete_results_template")) $grd->delete_text = "";
    if(!access::has("edit_results_template")) $grd->edit_text = "";
    
    $grd->auto_id=true;   
    
    if($grd->IsClickedBtnDelete() && access::has("delete_results_template"))
    {
       orm::Delete("result_templates", array("id"=>$grd->process_id));
    }
    
    $query = orm::GetSelectQuery("result_templates", array(), array(), "id desc", $auto_search=true);
    
    $grd->DrowTable($query);
    
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("template_name"));
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }

    

    function desc_func()
    {
            return QRESULT_TEMPLATES;
    }



?>
