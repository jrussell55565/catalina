<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("pres");    

    $val = new validations("btnSave");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    
    $id = "-1";    
    $txtPText = "";
    
    if(isset($_GET["id"]))
    {        
        $id = util::GetID("?module=pres_list");
        $rs_groups=orm::Select("pres", array(), au::arr_where(array("id"=>$id)), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=pres_list");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["pres_name"];
        $txtDesc = $row_groups["pres_desc"];      
        $txtPText = $row_groups["pres_text"];  
     
    }    
    
    
    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {            
            orm::Insert("pres", au::add_insert(array("pres_name"=>trim($_POST["txtName"]),"pres_text"=>trim($_POST["editor1"]),
                                    "pres_desc"=>trim($_POST["txtDesc"])                                                                  
                                   )));
        }
        else
        {            
            $arr_columns=array("pres_name"=>trim($_POST["txtName"]),"pres_text"=>trim($_POST["editor1"]),
                                    "pres_desc"=>trim($_POST["txtDesc"])                                     
                                   );
     
            orm::Update("pres", au::add_update($arr_columns), array("id"=>$id));
        }

        util::redirect("?module=pres_list");
    }
 
    
    function desc_func()
    {
        return ADD_EDIT_PRES;
    }
?>