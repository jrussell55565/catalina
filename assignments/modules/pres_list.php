<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("pres");    

    require "extgrid.php";
    
    $hedaers = array("&nbsp;",NAME,DESC,"&nbsp;","&nbsp;");
    $columns = array("pres_name"=>"text","pres_desc"=>"text");
    
    
    $grd = new extgrid($hedaers,$columns, "index.php?module=pres_list");
    $grd->edit_link="index.php?module=add_edit_pres";      
    $grd->auto_id=true;        
    $grd->exp_enabled=false;
   // $grd->id_links = array(QUESTIONS=>"?module=questions_bank");
    $grd->row_info_table="pres";  
     
    if($grd->IsClickedBtnDelete())
    {
       orm::Delete("pres", array("id"=>$grd->process_id));
    }
    
    $query = orm::GetSelectQuery("pres", array(), au::arr_where(array()), "id desc", $auto_search=true);
    $grd->search=access::UserInfo()->access_type ==1 ? false : true;
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(NAME),array("pres_name"));
    
    if(isset($_POST["ajax"]))
    {
         if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    }


    function desc_func()
    {
            return PRESENTATIONS;
    }



?>
