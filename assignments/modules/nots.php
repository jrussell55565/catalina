<?php 

require "extgrid.php";  

access::has("view_not",2);

$id = util::GetID("?module=assignments");

$hedaers = array(SAVE, BODY, ADDED_DATE, "&nbsp;");
$columns = array("subject"=>"text","body"=>"test","added_date"=>"test");
    
$grd = new extgrid($hedaers,$columns, "index.php?module=nots&id=$id");

$grd->edit=false;


if(isset($_POST["add"]) && access::has("add_not"))
{
    $query = orm::GetInsertQuery("nots", array("asg_id"=>$id,"subject"=>$_POST["subject"],"body"=>$_POST["body"],"added_date"=>util::Now(), "sent_by"=>access::UserInfo()->user_id));
    db::exec_insert($query);
}

$query = orm::GetSelectQuery("nots", array(), array("asg_id"=>$id), "added_date");
$grd->main_table="nots";
$grd->auto_delete=true;
if(!access::has("delete_not")) 
{
    $grd->delete_text = "";
    $grd->auto_delete=false;
}
$grd->DrowTable($query);
$grid_html = $grd->table;


 if(isset($_POST["ajax"]))
 {
     echo $grid_html;
 }


function desc_func()
{
        return NOTIFICATIONS;
}

?>