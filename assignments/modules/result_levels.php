<?php if(!isset($RUN)) { exit(); } ?>
<?php
    

    access::menu("qresult_templates");
    access::has("manage_levels",2);

    require "extgrid.php";
    
    $ID = util::GetID("?module=qresult_templates");
    
    $hedaers = array("&nbsp;",NAME,MIN_POINT,MAX_POINT,"&nbsp;","&nbsp;");
    $columns = array("c_temp_name"=>"text","min_point"=>"text","max_point"=>"text");

    
    $grd = new extgrid($hedaers,$columns, "index.php?module=result_levels&id=$ID");
    $grd->edit_link="index.php?module=add_edit_level_template&t_id=$ID";
    //$grd->modify_system_rows=false;
    $grd->exp_enabled=false;
    $grd->search=true;

    $grd->auto_id=true;   
    
    if($grd->IsClickedBtnDelete())
    {
       orm::Delete("result_template_contents", array("id"=>$grd->process_id, "template_type"=>"3"));
    }
    
    $query = orm::GetSelectQuery("result_template_contents", array(), array("template_id"=>$ID,"template_type"=>"3"), "min_point", $auto_search=true);
   
    $grd->DrowTable($query);
    
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("c_temp_name"));
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }

    

    function desc_func()
    {
            return RESULT_LEVEL_TEMPS;
    }



?>
