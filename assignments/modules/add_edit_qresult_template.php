<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("qresult_templates");    
  //  access::has("view_roles", 2);

    $val = new validations("btnSave");
    $val->AddValidator("txtName", "empty", NAME_VAL,"");
    $val->AddValidator("txtDesc", "empty", DESC_VAL,"");
    
    $id = "-1";
    $selected_s = $selected_u = "-1";
    $txtSuccess = "";
    $txtUnsuccess = "";
    
    $txtSFBMessage="";
    $txtSFBLinkName="";
    $txtSFBLink=WEB_SITE_URL;
//   $txtSFBLink_default_value = WEB_SITE_URL;
    
    $txtUSFBMessage="";
    $txtUSFBLinkName="";
    $txtUSFBLink=WEB_SITE_URL;
 //   $txtUSFBLink_default_value = WEB_SITE_URL;
    
    if(isset($_GET["id"]))
    {
        access::has("edit_results_template", 2);
        $id = util::GetID("?module=qresult_template");
        $rs_groups=orm::Select("result_templates", array(), array("id"=>$id), "");

        if(db::num_rows($rs_groups) == 0 ) util::redirect("?module=qresult_template");

        $row_groups=db::fetch($rs_groups);
        $txtName = $row_groups["template_name"];
        $txtDesc = $row_groups["template_desc"];               
        
        $result_template = orm::Select("result_template_contents", array(), array("template_id"=>$id), "1");
        $tmp_row = db::fetch($result_template);    
        
        $txtSuccess = $tmp_row["template_content"];
        $txtSFBMessage = $tmp_row["fb_message"];
        $txtSFBLinkName = $tmp_row["fb_name"];
        $txtSFBLink = $tmp_row["fb_link"];
        
        $selected_s = $tmp_row["level_id"];
        
        $tmp_row = db::fetch($result_template);    
        
        $txtUnsuccess = $tmp_row["template_content"];        
        $txtUSFBMessage = $tmp_row["fb_message"];
        $txtUSFBLinkName = $tmp_row["fb_name"];
        $txtUSFBLink = $tmp_row["fb_link"];
        
        $selected_u = $tmp_row["level_id"];
        
     
    }
    else access::has("add_results_template", 2);
    
    $results = db::GetResultsAsArray(orm::GetSelectQuery("result_levels", array(), array(),"id"));
    $slevel_options = webcontrols::GetArrayOptions($results, "id", "level_name", $selected_s);
    $flevel_options = webcontrols::GetArrayOptions($results, "id", "level_name", $selected_u);
    
    
    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {            
            $id = db::exec_insert(orm::GetInsertQuery("result_templates", array("template_name"=>trim($_POST["txtName"]),
                                    "template_desc"=>trim($_POST["txtDesc"])                                 
                                   )));
            
            orm::Insert("result_template_contents", array("template_type"=>"1","template_id"=>$id,"level_id"=>$_POST['drpSLevel'], "template_content"=>$_POST['txtSuccess'], "fb_message"=>$_POST['txtSFBMessage'], "fb_name"=>$_POST['txtSFBLinkName'], "fb_link"=>$_POST['txtSFBLink']));
            orm::Insert("result_template_contents", array("template_type"=>"2","template_id"=>$id,"level_id"=>$_POST['drpULevel'], "template_content"=>$_POST['txtUnsuccess'], "fb_message"=>$_POST['txtUSFBMessage'], "fb_name"=>$_POST['txtUSFBLinkName'], "fb_link"=>$_POST['txtUSFBLink']));
        }
        else
        {            
            $arr_columns=array("template_name"=>trim($_POST["txtName"]),
                                    "template_desc"=>trim($_POST["txtDesc"])                                 
                                   );
     
            orm::Update("result_templates", $arr_columns, array("id"=>$id));            
            orm::Update("result_template_contents", array("template_content"=>$_POST['txtSuccess'], "level_id"=>$_POST['drpSLevel'],"fb_message"=>$_POST['txtSFBMessage'], "fb_name"=>$_POST['txtSFBLinkName'], "fb_link"=>$_POST['txtSFBLink']), array("template_id"=>$id,"template_type"=>"1"));
            orm::Update("result_template_contents", array("template_content"=>$_POST['txtUnsuccess'],"level_id"=>$_POST['drpULevel'], "fb_message"=>$_POST['txtUSFBMessage'], "fb_name"=>$_POST['txtUSFBLinkName'], "fb_link"=>$_POST['txtUSFBLink']), array("template_id"=>$id,"template_type"=>"2"));
        }

        util::redirect("?module=qresult_templates");
    }
 
  
    
    function desc_func()
    {
        return ADD_EDIT_QRESULT_TEMPLATES;
    }
?>