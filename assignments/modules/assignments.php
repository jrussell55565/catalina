<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("assignments");

    require "extgrid.php";
    require "db/res_temp.php";
    require "db/asg_db.php";
    require "events/assignments_events.php";
    require "lib/assignments_util.php";
    require "lib/logs.php";
    
    $selected_branch="-1";
    $branch_where = access::UserInfo()->access_type != 1 ? array("id"=>access::UserInfo()->branch_id) : array();
    $branches_res= orm::SelectAsArray("branches", array(), $branch_where, "branch_name");
    $branch_options =  webcontrols::GetArrayOptions($branches_res, "id", "branch_name", $selected_branch,false);

    
    $hedaers = array("&nbsp;","&nbsp;",ASSIGNMENT_NAME, ADDED_DATE, "&nbsp;","&nbsp;","&nbsp;","&nbsp;");
    $columns = array("status"=>"text","assignment_name"=>"text", "added_date"=>"short date");

    if(access::has("reset_asg")) $hedaers[]= "&nbsp";
    if(access::has("start_assignment")) $hedaers[]= "&nbsp";  
        
    
    $exp_hedaers = array(ASSIGNMENT_NAME, ADDED_DATE);
    
    $url = "index.php?module=assignments";
    $grd = new extgrid($hedaers,$columns, $url);
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns =  array("assignment_name"=>"text", "added_date"=>"short date");
    $grd->edit_link="index.php?module=add_assignment";
    $grd->row_info_table="assignments";
    $grd->remember_checkboxes=false;
    
    $arr_commands = array();
    if(access::has("start_assignment")) $arr_commands[START] = "start";      
    if(access::has("reset_asg")) $arr_commands[RESET_ASG] = "reset";        

    $grd->commands=$arr_commands;
    
    //$grd->process_command_override="grd_process_command_override";
    $grd->process_commands_override = array("start"=>"start_override", "reset"=>"reset_override");
    $grd->column_override = array("status"=>"status_override");
    //$grd->edit_attr_override = "grd_ovverride_edit_attr";
    $grd->edit_link_override = "grd_ovverride_edit_link";

    $information = access::has("view_inf_asg") == true ? INFORMATION : "";
    $reports = access::has("report_assignment") == true ? REPORTS : "";
    
    $grd->id_links=(array("?module=view_assignment"=>$information,"?module=reports_chart"=>$reports));
   // $grd->id_links=(array("Information"=>"?module=view_assignment"));
    $grd->id_link_key="asg_id";    
    $grd->search=access::UserInfo()->access_type ==1 ? false : true;
    $grd->search_mode=2;   
    $grd->id_link_direction = ArrDirection::ValueFirst;
    if(ASG_ENABLE_HEADER_OPTIONS=="yes" && !$mobile) $grd->checkbox = true;    
    else { $grd->auto_id = true;  $hide_mobile="none"; }
    
    $grd->chk_class = "chk_qb";
    
    if(!access::has("delete_assignment")) $grd->delete_text = "";
    if(!access::has("edit_assignment")) $grd->edit_text = "";
        
    function DeleteAssignment($asg_id)
    {
        $asg_res = orm::Select("assignments", array("asg_image"), au::arr_where(array("id"=>$asg_id)), "");
        if(db::num_rows($asg_res)>0)
        {
            $asg_row = db::fetch($asg_res);
            if($asg_row['asg_image']!="")
            {
                @unlink("asg_images".DIRECTORY_SEPARATOR.$asg_row['asg_image']);
            }
            assignment_deleting($asg_id);
            orm::Delete("mailed_users", array("assignment_id"=>$asg_id));
            orm::Delete("variant_quizzes", array("asg_id"=>$asg_id));            
            orm::Delete("assignment_subjects", array("asg_id"=>$asg_id));
            orm::Delete("assignment_themes_xreff", array("asg_id"=>$asg_id));
            asgDB::DeleteRelatedQuiz($asg_id);  
            asgDB::DeleteAsgById($asg_id);  
            assignment_deleted($asg_id);
        }
    }
    
    if($grd->IsClickedBtnDelete() && access::has("delete_assignment"))
    {
        DeleteAssignment($grd->process_id);
    }
    
    if(isset($_POST['pcommand']) && !empty($_POST['chkboxes']))
    {
        $chkboxes = $_POST['chkboxes'];
       
        if($_POST['command']=="delete" && access::has("delete_assignment"))
        {            
            for($i=0;$i<count($chkboxes);$i++)
            {
                DeleteAssignment(util::GetInt($chkboxes[$i]));                
            }
        }
        else if($_POST['command']=="create_copy" && access::has("asg_copy_struct")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            for($i=0;$i<count($chkboxes);$i++)
            {
                $id = util::GetInt($chkboxes[$i]);                
                $db->query(assignments_util::CopyAssignmentStructure($id, access::UserInfo()->user_id, util::Now()));
            }
            $db->close_connection();
        }
        else if($_POST['command']=="change_branch" && access::has("asg_change_brn")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            $branch_id = $_POST['branch_id'];
            if($branch_id!="-1") {
            for($i=0;$i<count($chkboxes);$i++)
            {
                $id = util::GetInt($chkboxes[$i]);
                $db->query(orm::GetUpdateQuery("assignments", array("branch_id"=>$branch_id), array("id"=>$id)));                
            }
            logs::add_log3(16, "Branch changed - ".db::arr_to_in($chkboxes), $id); 
            }
            $db->close_connection();
        }
    }

    if($grd->IsClickedBtn("reset") && access::has("reset_asg"))
    {
        assignment_reseting($grd->process_id);
        asgDB::ChangeStat("0", $grd->process_id);
        orm::Delete("assignment_pauses", array("assignment_id"=>$grd->process_id));
        orm::Delete("user_quizzes", array("assignment_id"=>$grd->process_id));
        orm::Delete("mailed_users", array("assignment_id"=>$grd->process_id));
      //  orm::Delete("assignment_subjects", array("asg_id"=>$grd->process_id));     
        assignment_reseted($grd->process_id);
    }
    
    if($grd->IsClickedBtn("start") && access::has("start_assignment"))
    {
        assignment_starting($grd->process_id);
        asgDB::ChangeStat("1", $grd->process_id);
        assignment_started($grd->process_id);
        logs::add_log3(17, "Assignment started", $grd->process_id); 
    }
    
    if($grd->IsClickedBtn("pause") && access::has("stop_assignment"))
    {
        orm::Update("assignments", array("paused"=>1), array("id"=>$grd->process_id));
        orm::Insert("assignment_pauses", array("assignment_id"=>$grd->process_id, "pause_type"=>1, "pause_date"=>util::Now()));
        logs::add_log3(19, "Assignment paused", $grd->process_id); 
    }
    
    if($grd->IsClickedBtn("continue") && access::has("stop_assignment"))
    {
        orm::Update("assignments", array("paused"=>0), array("id"=>$grd->process_id));
        orm::Insert("assignment_pauses", array("assignment_id"=>$grd->process_id, "pause_type"=>0, "pause_date"=>util::Now()));
        logs::add_log3(20, "Assignment continued", $grd->process_id); 
    }

    if($grd->IsClickedBtn("stop") && access::has("stop_assignment"))
    {        
        assignment_stopping($grd->process_id);
        orm::Delete("assignment_pauses", array("assignment_id"=>$grd->process_id));
        orm::Update("assignments", array("paused"=>0), array("id"=>$grd->process_id));        

        $res_uq = db::exec_sql(orm::GetSelectQuery("user_quizzes", array(), array("assignment_id"=>$grd->process_id, "status"=>"1"), ""));

        while($row_uq=db::fetch($res_uq))
        {
	    $date = date('Y-m-d H:i:s');
            asgDB::UpdateUserQuiz($row_uq['id'], "4", $date);
        }
        
        asgDB::ChangeStat("2", $grd->process_id);
        
        assignment_stopped($grd->process_id);
        
        logs::add_log3(18, "Assignment stopped", $grd->process_id); 
    }

    function grd_ovverride_edit_link($row)
    {
        global $grd;
        
         return extgrid::EditCommandTemplate($row, $grd);
        /*
        if(intval($row['status'])>0)
        {
            return "&nbsp;";
        }
        else
        {
            return extgrid::EditCommandTemplate($row, $grd);
        }
        */ 
         
    }
    
    function status_override($row)
    {
        $img = "t_red.png";
        if($row['status']==1)
        {
            $img = "t_green.png";
        }
        else if($row['status']==2)
        {
            $img = "t_red.png";
        }
        if($row['paused']==1)
        {
            $img = "t_yellow.png";
        }
        return "<img align=center border=0 src='style/i/".$img."' />";
    }

  //  function grd_ovverride_edit_attr($row)
  //  {
   //     if(intval($row['status'])>0)
  //      {
  //          return "onclick='return alert(\"Cannot edit assignment , because quiz/survey already started\")';return false;";
  //      }
  //  }

    function start_override($row)
    {
        global $grd;
        if($row['status']==0)
        {
            if(!access::has("start_assignment")) return extgrid::EmptyColumn();
            return extgrid::ProcessCommandTemplate($row, "start", START,$grd,"",$grd->mobile_grid);
        }
        else if($row['status']==1)
        {
            if(!access::has("stop_assignment")) return extgrid::EmptyColumn();
            
            if($row['paused']==0) {
                $command_name = "pause";
                $command_text = C_PAUSE;
            }
            else
            {
                $command_name = "continue";
                $command_text = C_CONTINUE;
            }
            return extgrid::ProcessCommandTemplate($row, array($command_name,"stop"), array($command_text,STOP), $grd,ARE_YOU_SURE,$grd->mobile_grid);
        }
        else 
        {
            return extgrid::EmptyColumn();
        }
    }
    
    function reset_override($row)
    {
        global $grd;
        if(!access::has("reset_asg")) return extgrid::EmptyColumn();
        return extgrid::ProcessCommandTemplate($row, "reset", RESET_ASG,$grd,RESET_ASG_CONFIRM,$grd->mobile_grid);
    }

    $grd->sort_headers = array(ASSIGNMENT_NAME=>"assignment_name",ADDED_DATE=>"added_date");
    
    $grd->default_sort = "asg.added_date desc";
    $query = asgDB::GetAsgQuery($grd->GetSortQuery());    

    $grd->DrowTable($query);
    $grid_html = $grd->table;    

    $search_html = $grd->DrowSearch(array(ASSIGNMENT_NAME),array("assignment_name"));

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
        return ASSIGNMENTS;
    }

?>
