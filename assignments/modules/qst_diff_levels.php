<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("qst_diff_levels");    

    require "extgrid.php";
    
    $hedaers = array("&nbsp;",NAME,DESC,"&nbsp;","&nbsp;");
    $columns = array("level_name"=>"text","level_desc"=>"text");
    
    
    $grd = new extgrid($hedaers,$columns, "index.php?module=qst_diff_levels");
    $grd->edit_link="index.php?module=add_edit_diff_level";      
    $grd->auto_id=true;               
     
    if($grd->IsClickedBtnDelete())
    {
       orm::Delete("qst_diff_levels", array("id"=>$grd->process_id));
    }
    
    $query = orm::GetSelectQuery("qst_diff_levels", array(),array(), "priority,id");
    
    $grd->DrowTable($query);
    $grid_html = $grd->table;   
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }


    function desc_func()
    {
            return L_QST_DIFF_LEVELS;
    }



?>
