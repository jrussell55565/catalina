<?php if(!isset($RUN)) { exit(); } ?>
<?php
    

    access::menu("result_temps");    

    require "extgrid.php";
    
  //  $ID = util::GetID("?module=qresult_levels");
    
    $hedaers = array("&nbsp;",NAME,"&nbsp;","&nbsp;");
    $columns = array("level_name"=>"text");

    
    $grd = new extgrid($hedaers,$columns, "index.php?module=qresult_levels");
    $grd->edit_link="index.php?module=add_edit_qresult_level";
    //$grd->modify_system_rows=false;
    $grd->exp_enabled=false;
    $grd->search=false;

    $grd->auto_id=true;   
    
    if($grd->IsClickedBtnDelete())
    {
       orm::Delete("result_levels", array("id"=>$grd->process_id));
    }
    
    $query = orm::GetSelectQuery("result_levels", array(), array(), "id", $auto_search=true);
   
    $grd->DrowTable($query);
    
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("level_name"));
    
    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }

    

    function desc_func()
    {
            return RESULT_LEVELS;
    }



?>
