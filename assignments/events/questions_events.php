<?php

/************* questions ************/

function question_add_page_loading($id, $row)
{
    // $id = question id . will be -1 if adding new question
    // $row will be available if editing question . just check if($id!=-1), and then use $row variable
    
    
}

function question_adding($db,$arr_columns)
{
    // $arr_columns is the array , with list of entered question information
}

function question_added($db,$new_question_id, $arr_columns)
{
    // $new_question_id is the unuqie id just inserted question
    // $arr_columns is the array , with list of entered question information    
}

function question_editing($db,$id, $arr_columns)
{
    // $new_question_id is the unuqie id just inserted question
    // $arr_columns is the array , with list of entered question information
}

function question_edited($db,$id, $arr_columns)
{
    // $new_question_id is the unuqie id just inserted question
    // $arr_columns is the array , with list of entered question information  
}

function question_deleting($id)
{
    // $id is id of question that will be deleted
}

function question_deleted($id)
{
    // $id is id of question that has been deleted
}

/************* end questions ************/

?>
