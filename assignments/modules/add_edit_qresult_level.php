<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("result_temps"); 

    $val = new validations("btnSave");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");    
    
    $id = "-1";
    
    if(isset($_GET["id"]))
    {        
        $id = util::GetID("?module=qresult_levels");
      
        $rs_groups=orm::Select("result_levels", array(), array("id"=>$id), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=qresult_levels");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["level_name"];        
     
    }    
    
    
    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {            
            orm::Insert("result_levels", array("level_name"=>trim($_POST["txtName"])                            
                                   ));
        }
        else
        {            
            $arr_columns=array("level_name"=>trim($_POST["txtName"])                               
                                   );
     
            orm::Update("result_levels", $arr_columns, array("id"=>$id));
        }

        util::redirect("?module=qresult_levels");
    }
 
    
    function desc_func()
    {
        return ADD_EDIT_LEVEL;
    }
?>