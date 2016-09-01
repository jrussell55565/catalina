<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("roles");    
  //  access::has("view_roles", 2);

    $val = new validations("btnSave");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    
    $id = "-1";
    $disabled = "";
    if(isset($_GET["id"]))
    {
        access::has("edit_role", 2);
        $id = util::GetID("?module=roles");
        $rs_groups=orm::Select("roles", array(), au::arr_where(array("id"=>$id)), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=roles");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["role_name"];
        $txtDesc = $row_groups["role_desc"];
        
     //   if($row_groups["system_row"]!="0") $disabled = "disabled";
     
    }
    else access::has("add_role", 2);
    
    
    if(isset($_POST["btnSave"]) && $val->IsValid() ) // && $row_groups["system_row"]=="0"
    {
        if(!isset($_GET["id"]))
        {            
            orm::Insert("roles", array("role_name"=>trim($_POST["txtName"]),
                                    "role_desc"=>trim($_POST["txtDesc"])                                 
                                   ));
        }
        else
        {            
            $arr_columns=array("role_name"=>trim($_POST["txtName"]),
                                    "role_desc"=>trim($_POST["txtDesc"])                                 
                                   );
     
            orm::Update("roles", $arr_columns, array("id"=>$id));
        }

        util::redirect("?module=roles");
    }
 
    
    function desc_func()
    {
        return ADD_EDIT_ROLE;
    }
?>