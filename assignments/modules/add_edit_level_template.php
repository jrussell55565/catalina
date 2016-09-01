<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("qresult_templates");
    access::has("manage_levels",2);

  //  $val = new validations("btnSave");
  //  $val->AddValidator("txtName", "empty", NAME_VAL,"");
 //   $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    
    $id = "-1";
    $txtSuccess="";
    $selected = "-1";
    
    $t_id = util::GetKeyID("t_id", "?module=qresult_template");
    
    if(isset($_GET["id"]))
    {
       
        $id = util::GetID("?module=qresult_template");
           
        
        $result_template = orm::Select("result_template_contents", array(), array("id"=>$id), "1");
        if(db::num_rows($result_template) == 0 ) util::redirect("?module=qresult_template");
        
        $tmp_row = db::fetch($result_template);    
        
        $txtSuccess = $tmp_row["template_content"];
        $txtMinPoint = $tmp_row["min_point"];
        $txtMaxPoint = $tmp_row["max_point"];
        $txtName = $tmp_row["c_temp_name"];
        $selected = $tmp_row["level_id"];
       
     
    }    

    $results = db::GetResultsAsArray(orm::GetSelectQuery("result_levels", array(), array(),"id"));
    $level_options = webcontrols::GetArrayOptions($results, "id", "level_name", $selected);
    
    if(isset($_POST["btnSave"]))
    {
        if(!isset($_GET["id"]))
        {                               
            orm::Insert("result_template_contents", array("template_type"=>"3","template_id"=>$t_id,"level_id"=>$_POST['drpLevel'], "template_content"=>$_POST['txtSuccess'], "c_temp_name"=>$_POST['txtName'], "min_point"=>$_POST['txtMinPoint'], "max_point"=>$_POST['txtMaxPoint']));            
        }
        else
        {            
            orm::Update("result_template_contents", array("template_type"=>"3","template_id"=>$t_id,"level_id"=>$_POST['drpLevel'], "template_content"=>$_POST['txtSuccess'], "c_temp_name"=>$_POST['txtName'], "min_point"=>$_POST['txtMinPoint'], "max_point"=>$_POST['txtMaxPoint']), array("id"=>$id));            
        }

        util::redirect("?module=result_levels&id=$t_id");
    }
 
  
    
    function desc_func()
    {
        return ADD_EDIT_QRESULT_TEMPLATES;
    }
?>