<?php if(!isset($RUN)) { exit(); } ?>
<?php 


    access::menu("cms");
    access::has("view_cms",2);

    $txtPagecontent="";
    $txtName="";
    $txtPriority="";
    $selected_type = "-1";
    $txtURL = "http://";

    $val = new validations("btnSubmit");
    $val->AddValidator("txtName", "empty", MENU_NAME_VAL,"");
    $val->AddValidator("txtPriority", "numeric", MENU_PRIORITY_VAL,"");
    $val->AddValidator("txtPriority", "empty", MENU_PRIORITY_E_VAL,"");        

    if(isset($_GET["id"]))
    {
        access::has("edit_page",2);
        $id = util::GetID("?module=cms");
        $res=orm::Select("pages", array(), array("id"=>$id), "");
        
        if(db::num_rows($res) == 0 ) util::redirect("?module=cms");

        $row=db::fetch($res);
        $txtName = $row["page_name"];
        $txtPagecontent = $row["page_content"]; 
	$txtPriority = $row["priority"]; 
        $selected_type = $row["page_type"]; 
        $txtURL = $row["link_url"]; 
    }
    else  access::has("add_page",2);


    $page_type_options = webcontrols::BuildOptions(array("1"=>PAGE_CONTENT,"2"=>LINK), $selected_type);
    
    if(isset($_POST["btnSubmit"]) && $val->IsValid())
    {
        $pid = util::GetKeyID("pid","?module=cms");
	if(!isset($_GET["id"]))
        {
	 	orm::Insert("pages", array(
                                "page_name"=>$_POST["txtName"],
				"priority"=>trim($_POST["txtPriority"])=="" ? 0 : trim($_POST["txtPriority"]),
                                "page_content"=>$_POST["editor1"],
                                "parent_id"=>$pid,
                                "page_type"=>$_POST["drpPageType"],
                                "link_url"=>$_POST["txtURL"]
                                ));
	}
        else 
        {
 		orm::Update("pages", array(
                                "page_name"=>$_POST["txtName"],
                                "priority"=>trim($_POST["txtPriority"])=="" ? 0 : trim($_POST["txtPriority"]),
                                "page_content"=>$_POST["editor1"],
                                 "page_type"=>$_POST["drpPageType"],
                                "link_url"=>$_POST["txtURL"]
                                ), 
				array("id"=>$id)				
				);	
    	}	        
	util::redirect("?module=cms&id=$pid");
    }


function desc_func() { return ADD_PAGE;}

?>
