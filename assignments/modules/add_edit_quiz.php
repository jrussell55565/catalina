<?php

    access::menu("add_edit_quiz");

    require "grid.php";    
    require "db/quiz_db.php";    

    $val = new validations("btnSubmit");
    $val->AddValidator("txtName", "empty", QUIZ_NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", QUIZ_DESC_VAL,"");
    $val->AddValidator("drpCats", "notequal", QUIZ_CAT_VAL, "-1");

   // $txtIntroText="";
    $selected = "-1";
    if(isset($_GET["id"]))
    {
        access::has("edit_quiz", 2);
        $id = util::GetID("?module=quizzes");
        $rs_quiz=orm::Select("quizzes", array(), au::arr_where(array("id"=>$id)), "");
        
        if(db::num_rows($rs_quiz) == 0 ) util::redirect("?module=quizzes");

        $row_quiz=db::fetch($rs_quiz);
        $txtName = $row_quiz["quiz_name"];
        $txtDesc = $row_quiz["quiz_desc"];
       // $chkShowIntro = $row_quiz["show_intro"] == "1" ? "checked" : "";
       // $txtIntroText = $row_quiz["intro_text"];
        $selected = $row_quiz["cat_id"];        
    }
    else access::has("add_quiz", 2);

    $results = orm::Select("cats", array(), au::arr_where(array()),"");
    $cat_options = webcontrols::GetOptions($results, "id", "cat_name", $selected);

    if(isset($_POST["btnSubmit"]) && $val->IsValid())
    {        
     
        $date = date('Y-m-d H:i:s');
        if(!isset($_GET["id"]))
        {
            orm::Insert("quizzes", au::add_insert(array(
                                "cat_id"=>$_POST["drpCats"],
                                "quiz_name"=>$_POST["txtName"] ,
                               "quiz_desc"=>$_POST["txtDesc"],
                               "added_date"=>$date
                                )));
        }
        else
        {
            orm::Update("quizzes", au::add_update(array(
                                "cat_id"=>$_POST["drpCats"],
                                "quiz_name"=>$_POST["txtName"] ,
                               "quiz_desc"=>$_POST["txtDesc"]
                                )) ,
                                au::arr_where(array("id"=>$id))
                        );
        }
        util::redirect("?module=quizzes");
    }





    function desc_func()
    {
        return ADD_EDIT_QUIZ;
    }

/*
    if(isset($_POST["ajax"]))
    {
         
    }
 */
?>
