<?php

/************* assignments ************/

function assignment_add_page_loading($id, $row, $local_asg_users, $imp_asg_users)
{
    // $id = assignment id . will be -1 if adding new assignment
    // $row = the row of editing assignment from "assignments" table . will be available if editing assignment . just check if($id!=-1), and then use $row variable
    // $local_asg_users = the list of local users , rows from "assignment_users" table . will be available if editing assignment . just check if($id!=-1), and then use $local_asg_users variable
    // $imp_asg_users = the list of imported users , rows from "assignment_users" table . will be available if editing assignment . just check if($id!=-1), and then use $imp_asg_users variable

}

function assignment_adding($arr_columns)
{
    // $arr_columns is the array , with list of entered assignment information
}

function assignment_added($db,$id, $arr_columns, $local_user_ids, $imp_user_ids)
{
    // $db - Use this object , if you want to execute sql queries in same transactions . Or use $db->rollback(); to rollback all executed queries .
    // $id = id of currently added assignment (auto increment)
    // $arr_columns is the array , with list of entered assignment information
    // $local_user_ids is the array with selected user ids , just posted from HTML .
    // $imp_user_ids is the array with selected user ids , just posted from HTML .
}

function assignment_editing($id, $arr_columns)
{
    // $id = id of currently editing assignment 
    // $arr_columns is the array , with list of entered assignment information
}

function assignment_edited($db,$id, $arr_columns, $local_user_ids, $imp_user_ids)
{
    // $db - Use this object , if you want to execute sql queries in same transactions . Or use $db->rollback(); to rollback all executed queries .
    // $id = id of currently edited assignment 
    // $arr_columns is the array , with list of entered assignment information
    // $local_user_ids is the array with selected user ids , just posted from HTML .
    // $imp_user_ids is the array with selected user ids , just posted from HTML .
}

function assignment_deleting($id)
{
    // $id is id of assignment that will be deleted
}

function assignment_deleted($id)
{
    // $id is id of assignment that has been deleted
}

function assignment_reseting($id)
{
    // $id is id of assignment that will be reseted
}

function assignment_reseted($id)
{
    // $id is id of assignment that has been reseted
}

function assignment_starting($id)
{
    // $id is id of assignment that will be started
}

function assignment_started($id)
{
    // $id is id of assignment that has been started
}

function assignment_stopping($id)
{
    // $id is id of assignment that will be stoped
}

function assignment_stopped($id)
{
   // $id is id of assignment that has been stopped
}

/************* end assignments ************/

?>