<?php if(!isset($RUN)) { exit(); } ?>

<?php 
    access::menu("cms");
    access::has("view_cms",2);
    
    require "extgrid.php";

    $hedaers = array("&nbsp;",MENU_NAME ,"&nbsp;","&nbsp;","&nbsp;");
    $columns = array("page_name"=>"text");

    $grd = new extgrid($hedaers,$columns, "index.php?module=cms");
    $grd->edit_link="index.php?module=add_page&pid=".get_page_id();
    $grd->auto_id=true;
    $grd->exp_enabled=false;
    
    if(!access::has("delete_page")) $grd->delete_text = "";
    if(!access::has("edit_page")) $grd->edit_text = "";

    $grd->id_links=array(CHILD_PAGES=>"index.php?module=cms");
    
    if($grd->IsClickedBtnDelete() && access::has("delete_page"))
    {
       orm::Delete("pages", array("id"=>$grd->process_id));
    }
    
    $array_where = array();
    $id_url="&pid=0";
    if(isset($_GET["id"]))
    {
        $id = util::GetID("?module=cms");
        $array_where = array("parent_id"=>$id);
        $id_url = "&pid=".$id;
    }
    
    $query = orm::GetSelectQuery("pages",array(), $array_where, "priority");
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }
    
    function get_page_id()
    {
        if(isset($_GET["id"]))
        {
            return util::GetID("?module=cms");
        }
        return "0";
    }

function desc_func() { return CONTENT_MAN;}

?>
