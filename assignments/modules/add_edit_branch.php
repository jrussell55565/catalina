<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("branches");    
    //access::has("view_branch", 2);

    $val = new validations("btnSave");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    
    $id = "-1";
    $self_display = "";
    
    if(isset($_GET["id"]))
    {
        access::has("edit_branch", 2);
        $id = util::GetID("?module=branches");
        $rs_groups=orm::Select("branches", array(), au::arr_where(array("id"=>$id)), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=branches");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["branch_name"];
        $txtDesc = $row_groups["branch_desc"];
        $chkSelfReg = $row_groups["self_reg"]=="1" ? "checked" : "";
        
        if($row_groups["system_row"]=="1") $self_display  = "none";
     
    }
    else access::has("add_branch", 2);
    
    
    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {            
            orm::Insert("branches", array("branch_name"=>trim($_POST["txtName"]),
                                    "branch_desc"=>trim($_POST["txtDesc"]) ,                               
                                    "self_reg"=>isset($_POST["chkSelfReg"]) ? "1" : "0" 
                                   ));
        }
        else
        {            
            $arr_columns=array("branch_name"=>trim($_POST["txtName"]),
                                    "branch_desc"=>trim($_POST["txtDesc"]) ,
                                    "self_reg"=>isset($_POST["chkSelfReg"]) ? "1" : "0"
                                   );
     
            orm::Update("branches", $arr_columns, array("id"=>$id));
        }

        util::redirect("?module=branches");
    }
 
    
    function desc_func()
    {
        return ADD_EDIT_BRANCH;
    }
?>