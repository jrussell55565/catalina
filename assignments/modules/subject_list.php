<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("subjects");    

    require "extgrid.php";
    require "db/subjects_db.php";
    
    $hedaers = array("&nbsp;",QUIZ_NAME,NAME,"&nbsp;","&nbsp;");
    $columns = array("quiz_name"=>"text","subject_name"=>"text");
    
    
    $grd = new extgrid($hedaers,$columns, "index.php?module=subject_list");
    $grd->edit_link="index.php?module=add_edit_subject";      
    $grd->auto_id=true;        
    $grd->exp_enabled=false;
    $grd->row_info_table="subjects"; 
   // $grd->id_links = array(QUESTIONS=>"?module=questions_bank");
     
     
    if($grd->IsClickedBtnDelete())
    {
       orm::Delete("subjects", array("id"=>$grd->process_id));
    }
    
    //$query = orm::GetSelectQuery("subjects", array(), au::arr_where(array()), "id desc", $auto_search=true);
    
    $query = subjects_db::get_subjects("");
    $grd->search= true;
   // $grd->search=access::UserInfo()->access_type ==1 ? false : true;
    $grd->DrowTable($query);    
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(QUIZ_NAME,NAME),array("quiz_name","subject_name"));
    
    if(isset($_POST["ajax"]))
    {
         if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    }


    function desc_func()
    {
            return SUBJECTS;
    }



?>
