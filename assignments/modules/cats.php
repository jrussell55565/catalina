<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("cats");
    access::has("view_cats",2);

    require "extgrid.php";
    require "db/cats_db.php";
    require "modules/report_viewer.php";

    $hedaers = array(CAT_NAME, "&nbsp;", "&nbsp;");
    $columns = array("cat_name"=>"test");
    $exp_hedaers = array(CAT_NAME); 
    $grd = new extgrid($hedaers,$columns, "index.php?module=cats");
    
    $grd->process_html_command="process_quiz_status";
    
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    
    $grd->edit=false;
    $grd->row_info_table="cats";
    
    if(!access::has("delete_cat")) $grd->delete_text="";

    function process_quiz_status($row)
    {
        global $grd;
        if(!access::has("edit_cat")) return extgrid::EmptyColumn ();
        
        $style="";
        if($grd->mobile_grid)
        {
            $style="colspan=2";
        }
        
        $editjs="EditCat('".$row['cat_name']."','".$row['id']."')";
        $html="<td $style><a href='#' onclick=\"".$editjs."\">&nbsp;".EDIT."</a></td>";
        return $html;
    }

    if(isset($_POST["add"]))
    {
        if($_POST["add"]=="adding")
        {
            if(access::has("add_cat"))
            catsDB::AddNewCat($_POST["name"]);
        }
        else
        {      
            if(access::has("edit_cat"))
            catsDB::EditCat($_POST["name"],$_POST["hdnT"]);
        }
    }

    if($grd->IsClickedBtnDelete() && access::has("delete_cat"))
    {        
       catsDB::DeleteCategoryById($grd->process_id);
    }    
  
    //$grd->links =array("Az"=>"az.php" , "En"=>"english.php");
    $grd->sort_headers = array(CAT_NAME=>"cat_name");
    $grd->default_sort = "id desc";
    $query = catsDB::GetCatsQuery($grd->GetSortQuery());
    $grd->DrowTable($query);
    $grid_html = $grd->table;    

    if(isset($_POST["ajax"]))
    {
         if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    }
    if(isset($_GET["expgrid"]))
    {
        $grd->Export();
    }

    function desc_func()
    {
        return CAT_DESC;
    }

    
?>
