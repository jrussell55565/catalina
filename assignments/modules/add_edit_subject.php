<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("subjects");    

    $val = new validations("btnSave");
	
	$val->AddValidator("drpQuiz", "notequal", SBJ_VAL, "-1");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    $quiz_selected = "-1";
    
    $id = "-1";    
    
    if(isset($_GET["id"]))
    {        
        $id = util::GetID("?module=subject_list");
        $rs_groups=orm::Select("subjects", array(), au::arr_where(array("id"=>$id)), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=subject_list");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["subject_name"];
        $txtDesc = $row_groups["subject_desc"];   
        $quiz_selected = $row_groups["quiz_id"];  
     
    }    
    
    $quiz_results = orm::Select("quizzes", array(), au::arr_where(array("parent_id"=>"0")), "quiz_name");
    $quiz_options = webcontrols::GetOptions($quiz_results, "id", "quiz_name", $quiz_selected);
    
    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {            
            orm::Insert("subjects", au::add_insert(array("subject_name"=>trim($_POST["txtName"]),
                                    "subject_desc"=>trim($_POST["txtDesc"]),"quiz_id"=>trim($_POST["drpQuiz"])                                                              
                                   )));
        }
        else
        {            
            $arr_columns=array("subject_name"=>trim($_POST["txtName"]),
                                    "subject_desc"=>trim($_POST["txtDesc"]),"quiz_id"=>trim($_POST["drpQuiz"])                                   
                                   );
     
            orm::Update("subjects", au::add_update($arr_columns), array("id"=>$id));
        }

        util::redirect("?module=subject_list");
    }
 
    
    function desc_func()
    {
        return ADD_EDIT_SUBJECT;
    }
?>