<?php

/************* users ************/

function user_add_page_loading($id, $row)
{
    // $id = user id . will be -1 if adding new user
    // $row will be available if editing user . just check if($id!=-1), and then use $row variable
}

function user_adding($arr_columns)
{
    // $arr_columns is the array , with list of entered user information
}

function user_added($new_user_id, $arr_columns)
{
    // $new_user_id is the unuqie id just inserted user
    // $arr_columns is the array , with list of entered user information
}

function user_editing($id, $arr_columns)
{
    // $new_user_id is the unuqie id just inserted user
    // $arr_columns is the array , with list of entered user information
}

function user_edited($id, $arr_columns)
{
    // $new_user_id is the unuqie id just inserted user
    // $arr_columns is the array , with list of entered user information
}

function user_deleting($id)
{
    // $id is id of user that will be deleted
}

function user_deleted($id)
{
    // $id is id of user that has been deleted
}

function user_self_registering($arr_columns)
{    
    // $arr_columns is the array , with list of entered user information
}

function user_self_registered($new_user_id, $arr_columns,$guid)
{    
    // $new_user_id is the unuqie id just inserted user
    // $arr_columns is the array , with list of entered user information
    // $guid is the random string generet for self registered user 
}

function user_approving($guid)
{
    // $guid is the random string generet for self registered user 
}

function user_approved($guid)
{
    // $guid is the random string generet for self registered user 
}

/************* end users ************/

?>
