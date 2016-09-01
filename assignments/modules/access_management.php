<?php if(!isset($RUN)) { exit(); } ?>
<?php
    
    
    access::menu("roles");
    access::has("acc_man",2);
    
    require "extgrid.php";
    require "db/reports_db.php";
    
    $id = util::GetKeyID("id", "?module=roles");
    
    $roles_res = db::GetResultsAsArray(orm::GetSelectQuery("roles", array(), array("id"=>$id), ""));
    $roles_row = $roles_res[0];
    
    $disable = "";
    if($roles_row["system_row"]!="0") $disable="disabled";
    
    if(isset($_POST["btnSave"]))
    {        
            
        $db = new db();
        $db->connect();
        $db->begin();        
        try
        {   
            
            if(access::UserInfo()->access_type=="1" && $roles_row["system_row"]=="0")
            {
                $query = orm::GetUpdateQuery("roles", array("access_type"=>$_POST['grpAccess']) , array("id"=>$id));
                $db->query($query);
            }
            
            $query = orm::GetDeleteQuery("roles_pages", array("role_id"=>$id));
            $db->query($query);
            
            $query = orm::GetUpdateQuery("roles", array("allow_export"=>$_POST['drpExport'] ,"default_view"=>$_POST['drpDefView'], "rec_mails"=>$_POST['drpNotTick'], "is_technican"=>$_POST['drpIsTech']) , array("id"=>$id));
            $db->query($query);
            
            if($roles_row["system_row"]=="0") {
            
            $query = orm::GetUpdateQuery("roles", array("default_page"=>$_POST['drpDef']) , array("id"=>$id));
            $db->query($query);
                
            $query = orm::GetDeleteQuery("roles_rights", array("role_id"=>$id));
            $db->query($query);
            
            $query = orm::GetDeleteQuery("roles_access_rights", array("role_id"=>$id));
            $db->query($query);
                
            $arrMain = $_POST['arrMain'];       
            while (list ($key,$val) = @each ($arrMain))
            {
                $query = orm::GetInsertQuery("roles_rights", array("module_id"=>$val, "role_id"=>$id));
                $db->query($query);
            }     
            
            $arrChild = $_POST['arrChild'];       
            while (list ($key,$val) = @each ($arrChild))
            {
                $query = orm::GetInsertQuery("roles_rights", array("module_id"=>$val, "role_id"=>$id));
                $db->query($query);
            }     
            
            $arrAcc = $_POST['arrAcc'];       
            while (list ($key,$val) = @each ($arrAcc))
            {
                $query = orm::GetInsertQuery("roles_access_rights", array("access_id"=>$val, "role_id"=>$id));
                $db->query($query);
            }    
            }
            
            $arrMainPages = $_POST['arrMainPages'];       
            while (list ($key,$val) = @each ($arrMainPages))
            {
                $query = orm::GetInsertQuery("roles_pages", array("page_id"=>$val, "role_id"=>$id));
                $db->query($query);
            }  
            
            $db->query(orm::GetDeleteQuery("tview_role_xreff", array("role_id"=>$id)));
            
            $chkboxes = $_POST['chkgrdtick'];

            for($i=0;$i<count($chkboxes);$i++)
            {
                $db->query(orm::GetInsertQuery("tview_role_xreff", array("role_id"=>$id, "tview_id"=>$chkboxes[$i])));
            }  
            
            $db->query(orm::GetDeleteQuery("role_reports", array("role_id"=>$id)));
            
            $chkboxes = $_POST['chkgrdrep'];
                      
            for($i=0;$i<count($chkboxes);$i++)
            {
                $db->query(orm::GetInsertQuery("role_reports", array("role_id"=>$id, "report_id"=>$chkboxes[$i])));
            }  
            
            $db->commit();     
            
            util::redirect("?module=roles");    
        }
        catch(Exception $e)
        {
            //echo $e->getMessage();
            $db->rollback();
        }

        $db->close_connection();
    }
    
    if(isset($_POST["btnCancel"]))
    {
        util::redirect("?module=roles");   
    }
    
    $views=db::GetResultsAsArray(orm::GetSelectQuery("tview_role_xreff", array(), array("role_id"=>$id), ""));
    $view_ids= db::GetResultsByColumn($views, "tview_id");
    
    $hedaers = array("&nbsp;",VIEW_NAME);
    $columns = array("view_name"=>"text");
    $grd = new extgrid($hedaers,$columns, "");
    $grd->id_column="id";        
    $grd->delete=false;
    $grd->edit=false;
    $grd->auto_id=false;
    $grd->checkbox=true;
    $grd->unread_enabled=false;    
    $grd->selected_ids=$view_ids;
    $grd->exp_enabled=false;
    $grd->identity="tick";
    $grd->chk_class="chkgrd";
    
    $grd->DrowTable(orm::GetSelectQuery("ticket_views", array(), array(), ""));
    $grid_html=$grd->table;
    
    $reports_res = db::GetResultsAsArray(orm::GetSelectQuery("role_reports", array(), array("role_id"=>$id), ""));
    $report_ids = db::GetResultsByColumn($reports_res, "report_id");
    
    $hedaers = array("&nbsp;",REPORTS);
    $columns = array("report_name"=>"text");
    $grd_rep = new extgrid($hedaers,$columns, "");
    $grd_rep->id_column="id";        
    $grd_rep->delete=false;
    $grd_rep->edit=false;
    $grd_rep->auto_id=false;
    $grd_rep->checkbox=true;
    $grd_rep->unread_enabled=false;   
    $grd_rep->selected_ids=$report_ids;
    $grd_rep->exp_enabled=false;
    $grd_rep->identity="rep";
    $grd_rep->chk_class="chkrep";
    
    $grd_rep->DrowTable(orm::GetSelectQuery("reports", array(), array(), ""));
    $gridrep_html=$grd_rep->table;
         

    //require "extgrid.php";
    $dres = orm::Select("modules", array(),array("can_be_default"=>"1"), "priority");
    $default_options = webcontrols::GetOptions($dres, "id", "module_name", $roles_row["default_page"],false);
    
    $dres = orm::Select("ticket_views", array(),array(), "");
    $default_views = webcontrols::GetOptions($dres, "id", "view_name", $roles_row["default_view"],false);
    
    $export_options = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO), $roles_row["allow_export"]);
    
    $tech_opts = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO), $roles_row["is_technican"]);
    
    $tick_opts = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO), $roles_row["rec_mails"]);
    
    $modules_res = db::GetResultsAsArray(orm::GetSelectQuery("modules", array(), array(), "priority"));    
    $access_res = db::GetResultsAsArray(orm::GetSelectQuery("modules_access", array(), array(), "priority"));
    
    $module_current_res = db::GetResultsAsArray(orm::GetSelectQuery("roles_rights",array(), array("role_id"=>$id), ""));  
    $access_current_res = db::GetResultsAsArray(orm::GetSelectQuery("roles_access_rights",array(), array("role_id"=>$id), ""));  
    
    $rmain_modules = db::Select($modules_res, "parent_id", "0");
    
    $page_res = db::GetResultsAsArray(orm::GetSelectQuery("pages", array(), array(), ""));
    $page_current_res = db::GetResultsAsArray(orm::GetSelectQuery("roles_pages",array(), array("role_id"=>$id), ""));  
    
    $rmain_pages = db::Select($page_res, "parent_id", "0");
    
    function IsChecked($module_id)
    {
        global $module_current_res;
        $arr = db::Select($module_current_res, "module_id", $module_id);
        if(count($arr)>0) return "checked";
        else return "";
    }
    
    function IsAccessChecked($access_id)
    {
        global $access_current_res;
        $arr = db::Select($access_current_res, "access_id", $access_id);
        if(count($arr)>0) return "checked";
        else return "";
    }
    
    function IsPageChecked($page_id)
    {
        global $page_current_res;
        $arr = db::Select($page_current_res, "page_id", $page_id);
        if(count($arr)>0) return "checked";
        else return "";
    }
    
    function IsBranchChecked($access_type)
    {
        global $roles_row;
        $checked = "";        
        if($access_type == $roles_row["access_type"])
        {
            $checked = "checked";
        }                
        
        return $checked;
        
    }
    
    function IsBranchDisabled($access_type)
    {
        $disabled = "";
        if(access::UserInfo()->access_type!="1")
        {
            $disabled = "disabled";
        }
        return $disabled;
    }
    
    function desc_func()
    {
            return ACCESS_MANAGEMENT;
    }
    
?>

