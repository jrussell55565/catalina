<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("user_groups");    
  //  access::has("view_roles", 2);

    $val = new validations("btnSave");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    
    $id = "-1";
    $chkDefault="";
    $txtSTYears = "0";
    $chkShowInList = "checked";
    
    if(isset($_GET["id"]))
    {
        access::has("edit_user_group", 2);
        $id = util::GetID("?module=user_groups");
        $rs_groups=orm::Select("user_groups", array(), au::arr_where(array("id"=>$id)), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=user_groups");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["group_name"];
        $txtDesc = $row_groups["group_desc"];
        $chkDefault = $row_groups["is_default"]=="1" ? "checked" : "";
        $txtSTYears = $row_groups["st_years"];
        
        $chkShowInList=$row_groups["show_in_list"]=="1" ? "checked" : "";
        
        $txtStartDate = date('Y/m/d', strtotime($row_groups["start_date"]));
        
     
    }
    else access::has("add_user_group", 2);
    
    
    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        
        $chkShowList = isset($_POST["chkShowInList"]) ? "1" : "0";
        if(!isset($_GET["id"]))
        {            
            $id=db::exec_insert(orm::GetInsertQuery("user_groups", au::add_insert(array("group_name"=>trim($_POST["txtName"]),
                                    "group_desc"=>trim($_POST["txtDesc"]),
                                     "start_date"=>trim($_POST['datetimepicker_start']),                                                               
                                    "st_years"=>trim($_POST['txtYears']),
                                    "show_in_list"=>$chkShowList
                                   ))));
        }
        else
        {            
            $arr_columns=array("group_name"=>trim($_POST["txtName"]),
                                    "group_desc"=>trim($_POST["txtDesc"]) ,
                                    "start_date"=>trim($_POST['datetimepicker_start']),                                                               
                                    "st_years"=>trim($_POST['txtYears']),
                                    "show_in_list"=>$chkShowList                                    
                                   );
     
            orm::Update("user_groups", au::add_update($arr_columns), array("id"=>$id));
        }        
        
        $chkDefault = isset($_POST["chkDefault"]) ? "1" : "0";
        if($chkDefault=="1")
        {
            orm::Update("user_groups", array("is_default"=>"0"), array());
            orm::Update("user_groups", array("is_default"=>"1"), array("id"=>$id));
        }
        
        
        util::redirect("?module=user_groups");
    }
 
    
    function desc_func()
    {
        return ADD_EDIT_GROUP;
    }
?>