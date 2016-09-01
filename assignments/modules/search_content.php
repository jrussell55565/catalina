<?php if(!isset($RUN)) { exit(); } ?>
<?php 

   // access::allow("1,2");

    require "extgrid.php";

    $grid_html="";
    if(isset($_POST['query']))
    {
        $role_id=access::UserInfo()->role_id;
        $query = $_POST['query'];
        $query = db::clear($query);
        $query = "select p.* from pages p INNER JOIN roles_pages rp ON rp.page_id=p.id and rp.role_id=$role_id where p.page_name like '%".$query."%' or p.page_content like '%".$query."%'";
        
        $hedaers = array(NAME);
        $columns = array("page_name"=>"text");

        $grd = new extgrid($hedaers,$columns, "index.php?module=local_users");
        $grd->edit=false;
        $grd->delete=false;        
        $grd->column_override=array("page_name"=>"page_name_override");        
      //  $grd->id_links=array(QUIZZES=>"?module=old_assignments");
                
        $grd->DrowTable($query);
        $grid_html = $grd->table;
        
    }
    
    function page_name_override($row)
    {
        $link = "<a href='index.php?module=show_page&id=".$row['id']."'>".$row['page_name']."</a>";
        return $link;
    }

    function desc_func()
    {
        return SEARCH;
    }
?>