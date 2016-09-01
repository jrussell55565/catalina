<?php if(!isset($RUN)) { exit(); } ?>
<?php

 access::menu("assignments");
 access::has("view_inf_asg",2);
  
 require "extgrid.php";
 require "db/asg_db.php";
 require "db/res_temp.php";
 require "db/users_db.php";
 require "db/payments_db.php";
 require "lib/cmail.php";
 require "lib/libmail.php";
 require "db/questions_db.php";
 require "lib/questions_util.php";
 require "lib/logs.php";

 $asg_id=util::GetKeyID("asg_id", "?module=assignments");

 $asg_res = asgDB::GetAsgById($asg_id);

 if(db::num_rows($asg_res)==0)
 {
     die(ASG_CANNOT_BE_FOUND);
 }

 $row=db::fetch($asg_res);

 $creator=$row['Name'].' '.$row['Surname'];
 $asg_name = $row['assignment_name'];
 $cat_name= $row['asg_cat_name']; //$row["org_quiz_id"]=="-100" ? $row['asg_cat_name'] : $row['cat_name'] ; // org_quiz_id
 $test_name=$row["org_quiz_id"] != "-100" ? $row['quiz_name'] : QUESTIONS_BANK;
 $quiz_type=$row['quiz_type']=="1" ? O_QUIZ : O_SURVEY;
 $a_quiz_id = $row["quiz_id"];
 
 $questions_order=$row['qst_order']=="1" ? BY_PRIORITY : RANDOM;
 $answer_order=$row['answer_order']=="1" ? BY_PRIORITY : RANDOM;
 $review_answers=$row['allow_review']=="1" ? O_YES : O_NO;
 
 $show_results=$row['show_results']=="1" ? O_YES : O_NO;
 $results_by=$row['results_mode'] == "1" ? O_POINT : O_PERCENT;
 $asg_how_many=$row['limited'];
 $asg_affect_change=$row['affect_changes'] == "1" ? AFFECT : DONT_AFFECT;
 $asg_send_results=$row['send_results'] == "2" ? ASG_SEND_MAN : ASG_SEND_AUTO;
 $accept_new=$row['accept_new_users'] == "2" ? O_NO : O_YES;
 $pass_score=$row['pass_score'];
 $test_time=$row['quiz_time'];
 $show_questions = ALL_QUESTIONS;
 if($row['is_random'] =="2") $show_questions = L_SHOW_RAND_VARIANTS;
 else if($row['is_random'] =="3") $show_questions = L_SHOW_RAND_USER;
 $show_randomly = $row['random_qst_count'];
 $random_type = $row['random_type']=="2" ? RANDOM_FROM_EACH_SBJ : RANDOM_TOTAL;
 $variants = $row['variants'];
 $allow_back_examp = $row['allow_change_prev'] == "1" ? O_YES : O_NO;
 $show_msg_after = $row['show_success_msg'] == "1" ? O_YES : O_NO;
 $show_point_info = $row['show_point_info'] == "1" ? O_YES : O_NO;
 $certoficate = $row['cert_enabled'] == "1" ? $row['cert_name']  : NO_CERTIFICATE;
 $txtMailCopy = $row['mails_copy'];
 $asg_cost = $row['asg_cost'];
 if($row['fb_share'] == "0") $show_fb_share=DISABLE_SHARE; 
 else if($row['fb_share'] == "1") $show_fb_share=ALLOW_SHARE ;
 else $show_fb_share=AUTO_SHARE ;
 
 $cal_by = $row['calc_mode'] =="1" ? QSTS_POINT : ANSWERS_POINT ;
 $ans_calc_mode = $row['ans_calc_mode'] =="1" ? MODE_IF_CORRECT : MODE_ANYWAY ;
 
 $show_sbj_name = $row['show_subject_name'] == "1" ? O_YES : O_NO;
 $fail_sbj_exam = $row['fail_sbj_exam'] == "1" ? O_YES : O_NO;
 
 $asg_rate_id = $row['asg_rate_id'];
 $asg_rate = $row['asg_rate_id'] == "-1" ? NOT_SELECTED : $row['asg_rate'];
 $qst_rate = $row['qst_rate_id'] == "-1" ? NOT_SELECTED : $row['qst_rate'];
 
 $total_payments = db::exec_sql_single_value(payments_db::GetTotalIncomeByExamID($asg_id), "total", "0");
  
 $img_src = $row['asg_image'];
 if($img_src=="") $img_src = "no.jpg";
  
 $asg_desc = $row['short_desc'];

 $srv_display = "";
 if($row['quiz_type']=="2") $srv_display ="none";
 
 $calcmode = QSTS_POINT;
 if($row['calc_mode']=="2") $calcmode = ANSWERS_POINT;
 else if($row['calc_mode']=="3") $calcmode = L_ENTERED_POINTS;
 
 $calcpen = O_NO;
 
 if($row['calc_pen']=="1") $calcpen = L_QST_PEN;
 else if($row['calc_pen']=="2") $calcpen = L_ENT_PEN;
 
 $qst_diff_leves_res = db::GetResultsAsArray(orm::GetSelectQuery("qst_diff_levels", array(), array(), "priority , id"));
 $qst_diff_leves_asg_res = db::GetResultsAsArray(orm::GetSelectQuery("assignment_diff_level_xreff", array(), array("asg_id"=>$asg_id), ""));
 
 $selected_quiz_ids = array();
 
 $subject_list = db::GetResultsAsArray(asgDB::GetAsgThemes($asg_id));
 $results_tmp = LoadQuestionSettings(); 
 
 $log_display = access::has("view_exam_logs") ? "" : "none";
  
 
  //echo db::arr_to_in_arrres($subject_list, "theme_id");
 //exit();
 
 function LoadQuestionSettings()
 {
    global $asg_id,$selected_quiz_ids;    
    $asg_bank_quizzes_res = db::exec_sql(asgDB::GetAsgQuizzes($asg_id));
  
    $results_tmp = "<table border=0>";
    $z = 0;
    while($quiz_row = db::fetch($asg_bank_quizzes_res))
    {           
        $selected_quiz_ids[$z] = $quiz_row['id'];               
        $load_results = LoadDiffLevels($quiz_row['quiz_name'],$quiz_row['id'],$quiz_row['view_priority']);								
        $st_begin = "";					
        if($z==0)
        {
                $st_begin = "<tr>";						
        }
        else if($z%2==0)
        {
                $st_begin = "</tr><tr>";						
        }
        $results_tmp.= "$st_begin<td>$load_results</td><td style='width:50px'>&nbsp;</td>";
        $z++;
    }     
  
    $results_tmp.= "</tr></table>";
    return $results_tmp;
 }
 
function LoadDiffLevels($quiz_name,$quiz_id,$priority)
{    
        $disable_controls = "disabled";
	global $qst_diff_leves_res,$subject_list;
	$results = "	
	<table align='left' border='0' style='width:350px' id='tblDiff".$quiz_id."' >
					<tr>
						<td colspan=3><font color=red size=3>".$quiz_name ."</font></td>
					</tr>
                    <tr>
                        <td><b>".L_DIFF_LEVEL."</b>
                        </td>
                        <td><b>".L_QST_COUNT."</b>
                        </td>
                         <td><b>".L_QST_POINT."</b>
                        </td>
                         <td><b>".L_QST_PENALTY."</b>
                        </td>
                    </tr> ";
                    
                    
                       for($i=0;$i<count($qst_diff_leves_res);$i++)
                       {
                           $diff_row = $qst_diff_leves_res[$i];
                         //  $diff_ids.=$diff_row['id'].",";                                                      
                             
                            $results.= "<tr>
                                 <td style='width:140px'>".$diff_row['level_name']." : &nbsp;</td>
                                 <td><input style='width:70px' class='form-control input-sm inline' onkeypress='return onlyNumbers(event);' $disable_controls id='txtDiffLevel_".$quiz_id."_".$diff_row['id']."' name='txtDiffLevel_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".get_diff_value($diff_row['id'],$quiz_id)."' /></td>
                                 <td><input style='width:70px' class='form-control input-sm inline'  onkeypress='return onlyDecs(event);' $disable_controls id='txtDiffPoint_".$quiz_id."_".$diff_row['id']."' name='txtDiffPoint_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".get_diff_point($diff_row['id'],$diff_row['level_point'],$quiz_id)."' /></td>
                                      <td><input style='width:70px' class='form-control input-sm inline'  onkeypress='return onlyDecs(event);' $disable_controls id='txtPenPoint_".$quiz_id."_".$diff_row['id']."' name='txtPenPoint_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".get_pen_point($diff_row['id'],$quiz_id)."' /><input id='txtHPoints_".$quiz_id."_".$diff_row['id']."' name='txtHPoints_".$quiz_id."_".$diff_row['id']."' type='hidden' value='0' /></td>
                             </tr>";
                       
                                                        
                       }
                    
                  
                  $results.="<tr >
                        <td><b>".L_QST_PRIORITY."</b>
                        </td>
                        <td align=\"center\" colspan=\"3\"><input $disable_controls style='width:70px' class='form-control input-small inline' onkeypress='return onlyDecs(event);'  id='txtQuizPrior".$quiz_id."' name='txtQuizPrior".$quiz_id."' type='text'  value='".$priority."' />
                        </td>                     
                    </tr>
                </table>
                <p></p> &nbsp; ";

                $theme_list = "<table class='table table-striped table-bordered' ><font color=green size=3>".SUBJECTS."</font>";
                $find = false;
                for($i=0;$i<count($subject_list);$i++)
                {                    
                    $subject_row = $subject_list[$i];
                    if($subject_row['quiz_id']==$quiz_id)
                    {       
                        $find=true;
                        $sbj_name = $subject_row['subject_name'];
                        if($subject_row['theme_id']=="-1") $sbj_name=OTHERS;
                        $theme_list.="<tr><td>".$sbj_name."</td></tr>";
                    }
                }
                if(!$find)
                {
                    $theme_list.="<tr><td>".ALL."</td></tr>";
                }
                $theme_list.="</table>";
                $html_res = "<table><tr><td valign=top>$results</td><td>&nbsp;&nbsp;&nbsp;</td><td valign=top>$theme_list</td></tr></table>";
                            
		return $html_res;
	
	
} 
 
function get_diff_value($diff_id,$quiz_id)
{        
    if(!isset($_GET['asg_id'])) return "0";    
    global $qst_diff_leves_asg_res;        
    
    $results =  db::Select($qst_diff_leves_asg_res, "diff_id", $diff_id, false, "", 0);
    return db::Select($results, "quiz_id", $quiz_id, true, "qst_count", 0);
    
}

function get_diff_point($diff_id,$level_point,$quiz_id)
{
    if(!isset($_GET['asg_id'])) {
        return $level_point;
    }    
    global $qst_diff_leves_asg_res;
    
    $results =  db::Select($qst_diff_leves_asg_res, "diff_id", $diff_id, false, "", 0);    
    return db::Select($results, "quiz_id", $quiz_id, true, "diff_point", 0);    
}

function get_pen_point($diff_id,$quiz_id)
{
    if(!isset($_GET['asg_id'])) {
        return "0";
    }    
    global $qst_diff_leves_asg_res;
    
    $results =  db::Select($qst_diff_leves_asg_res, "diff_id", $diff_id, false, "", 0);    
    return db::Select($results, "quiz_id", $quiz_id, true, "pen_point", 0);    
}

function get_quiz_where()
{
    global $selected_quiz_ids,$subject_list;
    $where = "";            
    for($z=0;$z<count($selected_quiz_ids);$z++)
    {        
        $d_quiz_id = $selected_quiz_ids[$z];                        
        $theme_where = "";
        for($i=0;$i<count($subject_list);$i++)
        {                    
            $subject_row = $subject_list[$i]; 
            if($subject_row['quiz_id']==$d_quiz_id)
            $theme_where.=",".$subject_row['theme_id'];
        }
        if($theme_where!="")
        {              
            $theme_where = substr($theme_where, 1);
            $theme_where = " and subject_id in ($theme_where) ";
          
        }
        
        $where.=" OR ( quiz_id=$d_quiz_id $theme_where ) ";
    }
    
    $where = substr($where, 3);
    
    $where = " ( $where ) ";
    
    return $where;
    //hdnQ
}

function get_randomize_where()
{
    global $qst_diff_leves_asg_res,$selected_quiz_ids;       
    global $qst_diff_leves_res;
   
    $globalwhere = "";
    for($z=0;$z<count($selected_quiz_ids);$z++)
    {        
        $d_quiz_id = $selected_quiz_ids[$z];
        $where="";
        $qst_diff_leves_asg_row = db::Select($qst_diff_leves_asg_res, "quiz_id",$d_quiz_id);
        for($i=0;$i<count($qst_diff_leves_asg_row);$i++)
        {
            $diff_id = $qst_diff_leves_asg_row[$i]["diff_id"];
            $row = db::Select($qst_diff_leves_asg_row, "diff_id", $diff_id);
            $qst_count= $row[0]['qst_count'];                        
                                    
            $where.=" OR (q.diff_id=$diff_id AND q.diff_row_number<=$qst_count AND q.sbj_id=$d_quiz_id ) ";
            
        }
        $where =  db::clear(substr($where, 3));    
        $globalwhere.= " OR ( $where ) ";
    }
    
    $globalwhere =  db::clear(substr($globalwhere, 3));   
    $globalwhere=" ( $globalwhere )";
            
    return $globalwhere;
}
 
 if(isset($_POST["ajax"]))
 {
        if(isset($_POST['add_user']) && access::has("add_user_asg"))
	{
            $user_type = db::esp($_POST['is_local'], true) ;
            $user_id = db::esp($_POST['user'], true) ;
            $variant_id = db::esp($_POST['variant'], true) ;
            $arr_columns =  array("assignment_id"=>$asg_id, "user_id"=>$user_id, "user_type"=>$user_type);
                  
            $u_quiz_id = 0;
            if($row["is_random"]=="2") $arr_columns["variant_id"]=$variant_id;
            else if($row["is_random"]=="3")
            {
                $db = new db();
                $db->connect();
                $db->begin();
                
                $randomize_where = get_randomize_where(); 
                $quiz_where =get_quiz_where();
                
                $quiz_id_in = db::clear(db::arr_to_in($selected_quiz_ids));   
                $u_quiz_id =  questions_util::CopyQuiz($quiz_where,$selected_quiz_ids[0], 3 ,$show_randomly,2,$randomize_where, $user_id);
				questions_util::CopyAsgQuestionsBulk($asg_id, $u_quiz_id);
                
                $db->commit();
                $db->close_connection();
                
            }
            
            $arr_columns["u_quiz_id"] = $u_quiz_id;            
            
            $asg_user_res = orm::Select("assignment_users", array(), array("user_id"=>$user_id,"assignment_id"=>$asg_id), "");
            if(db::num_rows($asg_user_res)==0) orm::Insert("assignment_users",$arr_columns);              
            
            logs::add_log3(23, "User added to exam : ".$user_id, $asg_id);
            
        }
        else if(isset($_POST['recalc_all']) && access::has("recalc_all"))
        {
            $res_uq = db::exec_sql(orm::GetSelectQuery("user_quizzes", array(), array("assignment_id"=>$asg_id), ""));
            
            asgDB::ClearSubjectResults($asg_id);
            
            while($row_uq=db::fetch($res_uq))
            {
                $date = date('Y-m-d H:i:s');
                asgDB::UpdateUserQuiz($row_uq['id'], "-1", $date,false);
            }    
            
            logs::add_log3(22, "Total point recalculated for all users ", $asg_id);
            
        }        
        else if(isset($_POST['delete_user']) && access::has("delete_user_asg"))
        {            
            $myarr = $_POST['myarr'];                   
            $myarrImp = $_POST['myarrImp'];                   
            $myarrLdap = $_POST['myarrLdap'];                   
            $myarrfb = $_POST['myarrfb'];         
            
            $myarr = is_array($myarr) ? $myarr : array();
            $myarrImp = is_array($myarrImp) ? $myarrImp : array();
            $myarrLdap = is_array($myarrLdap) ? $myarrLdap : array();
            $myarrfb = is_array($myarrfb) ? $myarrfb : array();
                        
            $user_ids = array_merge($myarr,$myarrImp,$myarrLdap,$myarrfb);
            
            for($i=0;$i<count($user_ids);$i++)
            {                
                $arr_id = explode(",",$user_ids[$i]);                                
                orm::Delete("user_quizzes", array("user_id"=>$arr_id[0], "assignment_id"=>$asg_id));
                orm::Delete("assignment_users", array("user_id"=>$arr_id[0], "assignment_id"=>$asg_id));                                    
            }            
            logs::add_log3(6, "User deleted : ".implode(",", $user_ids), $asg_id);
            
        }
        else if(isset($_POST['delete_exam']) && access::has("delete_user_asg"))
        {            
            $myarr = $_POST['myarr'];                   
            $myarrImp = $_POST['myarrImp'];                   
            $myarrLdap = $_POST['myarrLdap'];                   
            $myarrfb = $_POST['myarrfb'];      
            
            $myarr = is_array($myarr) ? $myarr : array();
            $myarrImp = is_array($myarrImp) ? $myarrImp : array();
            $myarrLdap = is_array($myarrLdap) ? $myarrLdap : array();
            $myarrfb = is_array($myarrfb) ? $myarrfb : array();
            
            $user_ids = array_merge($myarr,$myarrImp,$myarrLdap,$myarrfb);
            
            for($i=0;$i<count($user_ids);$i++)
            {                
                $arr_id = explode(",",$user_ids[$i]);                                
                if($arr_id[1]!="") orm::Delete("user_quizzes", array("id"=>$arr_id[1], "assignment_id"=>$asg_id));                
            }            
            logs::add_log3(5, "Exam deleted :".implode(",", $user_ids), $asg_id);
            
        }
        else if(isset($_POST['recalc_points']) && access::has("recalc_points"))
        {
            $myarr = $_POST['myarr'];                   
            $myarrImp = $_POST['myarrImp'];                   
            $myarrLdap = $_POST['myarrLdap'];                   
            $myarrfb = $_POST['myarrfb'];   
            
            $myarr = is_array($myarr) ? $myarr : array();
            $myarrImp = is_array($myarrImp) ? $myarrImp : array();
            $myarrLdap = is_array($myarrLdap) ? $myarrLdap : array();
            $myarrfb = is_array($myarrfb) ? $myarrfb : array();
            
            $user_ids = array_merge($myarr,$myarrImp,$myarrLdap,$myarrfb);
            
            $in = db::clear(db::arr_to_in_multi($user_ids,1));
            
            if($in!=""){                            
            $res_uq = db::exec_sql(asgDB::GetUserQuizzesIn($in));            
           // asgDB::ClearSubjectResultsIn($in);
            
            while($row_uq=db::fetch($res_uq))
            {
                
                $db = new db();
                $db->connect();
                $db->begin();             
                questions_util::RecalcUserResults($db, $row_uq['id'], 1, true);
                $db->commit();
                $db->close_connection();
                
                $date = date('Y-m-d H:i:s');
                asgDB::UpdateUserQuiz($row_uq['id'], "-1", $date,false);
            }    
            }
            
            logs::add_log3(21, "Points recalculated :".implode(",", $user_ids), $asg_id);
            
        }
        else if(isset($_POST['loadlogs'] ) && access::has("view_exam_logs"))
        {
              $hedaers = array("&nbsp;",USER_NAME,  LOG_TYPE, IP_ADDRESS,LOGS);
              $columns = array("FullName"=>"text", "log_type"=>"text","ip_address"=>"text","log_text"=>"text");
              $grd_logs = new extgrid($hedaers,$columns, "index.php?module=user_logs");                    
              $grd_logs->info_identity="user_logs";  
              $grd_logs->row_info_table="user_logs";                    
              $grd_logs->delete=false;    
              $grd_logs->edit=false;    
              $grd_logs->exp_enabled=false;
              $grd_logs->PAGING=100000;
              $commands_arr = array("log_text"=>"log_text_override", "log_type"=>"log_type_override");              
              $commands_arr['FullName']='full_name_override';
              $grd_logs->column_override=$commands_arr;
              $grd_logs->auto_id=true;                       
              $query = logs::get_logs(" where log_type in (5,6,16,17,18,19,20,21,22,23) and log_type_id =$asg_id  ","", "inserted_date desc");                                           
              $grd_logs->DrowTable($query);
              $grid_html = $grd_logs->table;

              echo $grid_html;

        }
        
 }  
 
 function log_type_override($row)
 {
     global $LOG_TYPES;
     return $LOG_TYPES[$row['log_type']];
 }

 function full_name_override($row)
 {        
     $text = $row['FullName'];
     if($row['user_type']=="1") $text ="<a href='?module=add_edit_user&id=".$row['UserID']."'>$text</a>";
     else if($row['user_type']=="3") $text ="<a href='?module=add_edit_fb_user&id=".$row['UserID']."'>$text</a>";
     return $text;
 }
 
 function log_text_override($row)
 {
     global $grd_logs;
     //return $row['log_text'];
     //str_replace("\n", "<br />", $row['headers'])
     return extgrid::GetModalRowTemplate(LOGS,$row['log_text'], $grd_logs, $row['id']);
 }
 
 
 $sbj_query = asgDB::GetAsgSubjects($asg_id);
 $sbj_headers = array(SUBJECT, MIN_SUCCESS_POINT, PRESENTATION, PRESENTATION_DURATION);
 $sbj_columns = array("subject_name"=>"text","min_subject_point"=>"text","pres_name"=>"text","pres_duration"=>"text");
 $sbj_grd = new extgrid($sbj_headers, $sbj_columns, "");
 $sbj_grd->delete=false;
 $sbj_grd->edit=false;
 $sbj_grd->PAGING=100000;
 $sbj_grd->exp_enabled=false;
 $sbj_grd->DrowTable($sbj_query);
 $sbj_grd_html = $sbj_grd->table;

 $chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chklcl\",\"this.checked\")'>";
 
 if(!$mobile)
 {
    $hedaers = array($chk_all_html,USER_ID, LOGIN, USER_NAME, USER_SURNAME ,STATUS, START_SENT,RESULTS_SENT ,SUCCESS, TOTAL_POINT,LEVEL);
    $columns = array("user_id"=>"text","UserName"=>"text", "Name"=>"text","Surname"=>"text","status_name"=>"text","start_sent"=>"text","results_sent"=>"text","is_success"=>"text","total_point"=>"text","level_name"=>"text");
 }
 else
 {
    $hedaers = array($chk_all_html,  USER_NAME, USER_SURNAME);
    $columns = array("Name"=>"text","Surname"=>"text");
 }

 $exp_hedaers = array(USER_ID, LOGIN, USER_NAME, USER_SURNAME ,STATUS, START_SENT,RESULTS_SENT ,SUCCESS, TOTAL_POINT,LEVEL);  
 
 $v_links = "";
 $v_print_links = "";
 $v_print_c_links = "";
 if($row["is_random"]=="2")
 {
     $hedaers[]=VARIANTS;
     $exp_hedaers[]=VARIANTS;
     $columns["variant_name"]="text";
     $variant_quizzes_res = db::exec_sql(asgDB::GetVariantQuizzes($asg_id));
     BuildVariantsLink();
 }
 $hedaers[]="&nbsp;";$hedaers[]="&nbsp;";
 
 $enable_delete=false;
 if(access::has("delete_user_asg"))
 {
     $hedaers[]="&nbsp;";
     $enable_delete = true;
 } 
 
 function BuildVariantsLink()
 {
     global $variant_quizzes_res, $v_links,$asg_id,$v_print_links,$v_print_c_links;
     while($v_row = db::fetch($variant_quizzes_res))
     {         
         $v_links.="<a href='?module=questions&a_id=".$asg_id."&quiz_id=".$v_row['quiz_id']."'>".$v_row['variant_name']."</a>&nbsp;";
         $v_print_links.="<a target='_blank' href='?module=print_questions&id=".$v_row['quiz_id']."'>".$v_row['variant_name']."</a>&nbsp;";
         $v_print_c_links.="<a target='_blank' href='?module=print_questions&c=yes&id=".$v_row['quiz_id']."'>".$v_row['variant_name']."</a>&nbsp;";
     }     
 }
 
 $grd = new extgrid($hedaers,$columns, "index.php?module=view_assignment&asg_id=$asg_id","lc");
 $grd->exp_headers = $exp_hedaers;
 $grd->exp_columns = $columns;
 $grd->edit=false;
 $grd->delete=$enable_delete;
 $grd->delete_text=REMOVE;
 $grd->message =ARE_YOU_SURE_REMOVE_USER;
 
 if($grd->IsClickedBtnDelete() && access::has("delete_user_asg"))
 {    
     orm::Delete("user_quizzes", array("user_id"=>$grd->process_id, "assignment_id"=>$asg_id));
     orm::Delete("assignment_users", array("user_id"=>$grd->process_id, "assignment_id"=>$asg_id));     
     
     logs::add_log3(6, "User deleted : ".$grd->process_id, $asg_id);
 }

 $detailsl = access::has("view_quiz_details_asg") ? DETAILS : "";
 $download_cert = access::has("download_cert") && $row['cert_enabled'] == "1" ? CERTIFICATE : "";
 $grd->id_link_direction=  ArrDirection::ValueFirst;
 $grd->id_links=(array("?module=view_details"=>$detailsl,"?module=download_certificate"=>$download_cert));
 $grd->id_link_key="user_quiz_id";
 $grd->id_column="user_quiz_id";
 $grd->id_checkbox="user_id,user_quiz_id";
 $grd->delete_id_column = "UserID";
 $grd->checkbox=true;
 $grd->chk_class="chklcl";
 $grd->grid_control_name="divLU";
 $grd->column_override=array("UserName"=>"login_override","is_success"=>"success_override","status_name"=>"status_override","start_sent"=>"start_override", "results_sent"=>"results_override");
 $grd->PAGING = 1000000;
 $v = -1;
 function success_override($row)
 {
     $name = $row['Name'].' '.$row['Surname'];
     global $YES_NO,$v;
     $v++; 
     return $YES_NO[$row['is_success']]."<input type=hidden id=hdnnames$v value='".$name."'><input type=hidden id=hdnuq$v value='".$row['user_quiz_id']."'><input type=hidden id=hdn$v value=".$row['user_id']." />";
 }
 
 function login_override($row)
 {
        if(isset($_GET['expgrid'])) return $row['UserName'];
        $login = $row['UserName'];
        $user_photo_file = util::get_img_file($row['user_photo']);
        $href= "index.php?module=add_edit_user&id=".$row['UserID'];
       // $thumb = util::get_thumb($user_photo_file);
        $res = "<a href=\"$href\" class=\"ttip_b\" title=\"<img style='width:200px' src='user_photos/$user_photo_file' />\">$login</a>";
        //class="ttip_b" title="<b><i>salam</i></b>" 
      //  echo "user_photos/$user_photo_file";
        return $res;
 }


 function status_override($row)
 {
     global $ASG_STATUS;
     return $ASG_STATUS[$row['status_id']];
 }

 function start_override($row)
 {
	global $SENT;
 	$key = $row['start_sent'] == "" ? "no" : "yes";
	return "<span id=lbl".$row['user_id'].">".$SENT[$key]."</span>";
 }

 function results_override($row)
 {
	global $SENT;
 	$key = $row['results_sent'] == "" ? "no" : "yes";
	return "<span id=lblres".$row['user_id'].$row['user_quiz_id'].">".$SENT[$key]."</span>";
 }
//$columns = array("user_id"=>"text","UserName"=>"text", "Name"=>"text","Surname"=>"text","status_name"=>"text","start_sent"=>"text","results_sent"=>"text","is_success"=>"text","total_point"=>"text");
 $grd->sort_headers = array(USER_ID=>"user_id", LOGIN=>"UserName", USER_NAME=>"Name", USER_SURNAME=>"Surname" ,STATUS=>"status_name", START_SENT=>"start_sent",RESULTS_SENT=>"results_sent" ,SUCCESS=>"is_success", TOTAL_POINT=>"total_point", LEVEL=>"level_name");
 $grd->default_sort = " ua.added_date asc ";

 $query = asgDB::GetUserResultsQuery($asg_id, 1, $grd->GetSortQuery());

 $grd->mobile_grid=false;
 $grd->PAGING = 1000000; 
 $grd->DrowTable($query);
 $grid_lu_html = $grd->table;

 $chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chkimp\",\"this.checked\")'>";
 if(!$mobile) {
 $hedaers = array($chk_all_html,USER_ID, LOGIN, USER_NAME, USER_SURNAME ,STATUS, START_SENT,RESULTS_SENT ,SUCCESS, TOTAL_POINT,LEVEL);
 }else {
 $hedaers = array($chk_all_html, USER_NAME, USER_SURNAME );    
 }
     
 
 if($row["is_random"]=="2")
 {
     $hedaers[]=VARIANTS;     
 }
 $hedaers[]="&nbsp;"; $hedaers[]="&nbsp;"; 
 $enable_delete=false;
 if(access::has("delete_user_asg"))
 {
     $hedaers[]="&nbsp;";
     $enable_delete = true;
 }
 
 $grd_iu = new extgrid($hedaers,$columns, "index.php?module=view_assignment&asg_id=$asg_id");
 $grd_iu->exp_headers = $exp_hedaers;
 $grd_iu->exp_columns = $columns;
 $grd_iu->edit=false;
 $grd_iu->delete=$enable_delete;
 $grd_iu->delete_text=REMOVE;
 $grd_iu->message =ARE_YOU_SURE_REMOVE_USER;
 


 $detailsl = access::has("view_quiz_details_asg") ? DETAILS : "";
 $download_cert = access::has("download_cert") && $row['cert_enabled'] == "1" ? CERTIFICATE : "";
 $grd_iu->id_link_direction=  ArrDirection::ValueFirst;
 $grd_iu->id_links=(array("?module=view_details"=>$detailsl,"?module=download_certificate"=>$download_cert));
 $grd_iu->id_link_key="user_quiz_id";
 $grd_iu->id_column="user_quiz_id";
 $grd_iu->id_checkbox="user_id,user_quiz_id";
 $grd_iu->delete_id_column = "UserID";
 $grd_iu->checkbox=true;
 $grd_iu->chk_class="chkimp";
 $grd_iu->grid_control_name="divIU";
 $grd_iu->column_override=array("is_success"=>"iu_success_override","status_name"=>"iu_status_override","start_sent"=>"start_override", "results_sent"=>"results_override");
 $grd_iu->PAGING = 1000000;

 $y = -1;
 function iu_success_override($row)
 {
     $name = $row['Name'].' '.$row['Surname'];
     global $YES_NO,$y;
     $y++;
     return $YES_NO[$row['is_success']]."<input type=hidden id=hdnnamesi$y value='".$name."'><input type=hidden id=hdnuqi$y value='".$row['user_quiz_id']."'><input type=hidden id=hdnI$y value=".$row['user_id']." />";;
 }
  

 function iu_status_override($row)
 {
     global $ASG_STATUS;
     return $ASG_STATUS[$row['status_id']];
 }

 $grd_iu->sort_headers = array(USER_ID=>"user_id", LOGIN=>"UserName", USER_NAME=>"Name", USER_SURNAME=>"Surname" ,STATUS=>"status_name", START_SENT=>"start_sent",RESULTS_SENT=>"results_sent" ,SUCCESS=>"is_success", TOTAL_POINT=>"total_point", LEVEL=>"level_name");
 $grd_iu->default_sort = " ua.added_date asc ";
 
 $query = asgDB::GetUserResultsQuery($asg_id, 2, $grd_iu->GetSortQuery());
 $grd_iu->mobile_grid=false;
 $grd_iu->DrowTable($query);
 $grid_iu_html = $grd_iu->table;
 
 
 
 $chk_all_html = "<input class='els' type=checkbox name=chkAll4 onclick='grd_select_all(document.getElementById(\"form1\"),\"chkldap\",\"this.checked\")'>";
 if(!$mobile) {
 $hedaers = array($chk_all_html,USER_ID, LOGIN, USER_NAME, USER_SURNAME ,STATUS, START_SENT,RESULTS_SENT ,SUCCESS, TOTAL_POINT,LEVEL);
 }else {
 $hedaers = array($chk_all_html, USER_NAME, USER_SURNAME );    
 }
     
 
 if($row["is_random"]=="2")
 {
     $hedaers[]=VARIANTS;     
 }
 $hedaers[]="&nbsp;"; $hedaers[]="&nbsp;"; 
 $enable_delete=false;
 if(access::has("delete_user_asg"))
 {
     $hedaers[]="&nbsp;";
     $enable_delete = true;
 }
 
 $grd_ldap = new extgrid($hedaers,$columns, "index.php?module=view_assignment&asg_id=$asg_id");
 $grd_ldap->exp_headers = $exp_hedaers;
 $grd_ldap->exp_columns = $columns;
 $grd_ldap->edit=false;
 $grd_ldap->delete=$enable_delete;
 $grd_ldap->delete_text=REMOVE;
 $grd_ldap->message =ARE_YOU_SURE_REMOVE_USER;
 


 $detailsl = access::has("view_quiz_details_asg") ? DETAILS : "";
 $download_cert = access::has("download_cert") && $row['cert_enabled'] == "1" ? CERTIFICATE : "";
 $grd_ldap->id_link_direction=  ArrDirection::ValueFirst;
 $grd_ldap->id_links=(array("?module=view_details"=>$detailsl,"?module=download_certificate"=>$download_cert));
 $grd_ldap->id_link_key="user_quiz_id";
 $grd_ldap->id_column="user_quiz_id";
 $grd_ldap->id_checkbox="user_id,user_quiz_id";
 $grd_ldap->delete_id_column = "UserID";
 $grd_ldap->checkbox=true;
 $grd_ldap->chk_class="chkldap";
 $grd_ldap->grid_control_name="divLDAP";
 $grd_ldap->column_override=array("is_success"=>"ldap_success_override","status_name"=>"ldap_status_override","start_sent"=>"start_override", "results_sent"=>"results_override");
 $grd_ldap->PAGING = 1000000;

 $y = -1;
 function ldap_success_override($row)
 {
     $name = $row['Name'].' '.$row['Surname'];
     global $YES_NO,$y;
     $y++;
     return $YES_NO[$row['is_success']]."<input type=hidden id=hdnnamesldap$y value='".$name."'><input type=hidden id=hdnuqldap$y value='".$row['user_quiz_id']."'><input type=hidden id=hdnldap$y value=".$row['user_id']." />";;
 }
  

 function ldap_status_override($row)
 {
     global $ASG_STATUS;
     return $ASG_STATUS[$row['status_id']];
 }

 $grd_ldap->sort_headers = array(USER_ID=>"user_id", LOGIN=>"UserName", USER_NAME=>"Name", USER_SURNAME=>"Surname" ,STATUS=>"status_name", START_SENT=>"start_sent",RESULTS_SENT=>"results_sent" ,SUCCESS=>"is_success", TOTAL_POINT=>"total_point", LEVEL=>"level_name");
 $grd_ldap->default_sort = " ua.added_date asc ";
 
 $query = asgDB::GetUserResultsQuery($asg_id, 4, $grd_ldap->GetSortQuery());
 
 $grd_ldap->mobile_grid=false;
 $grd_ldap->DrowTable($query);
 $grid_ldap_html = $grd_ldap->table;
 
 
 
 $chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chkfb\",\"this.checked\")'>";
 if(!$mobile) {
 $hedaers = array($chk_all_html, USER_ID, LOGIN, USER_NAME, USER_SURNAME ,STATUS, START_SENT,RESULTS_SENT ,SUCCESS, TOTAL_POINT,LEVEL);
 }else {
 $hedaers = array($chk_all_html, USER_NAME, USER_SURNAME );    
 }
     
 
 if($row["is_random"]=="2")
 {
     $hedaers[]=VARIANTS;     
 }
 $hedaers[]="&nbsp;"; $hedaers[]="&nbsp;"; 
 $enable_delete=false;
 if(access::has("delete_user_asg"))
 {
     $hedaers[]="&nbsp;";
     $enable_delete = true;
 }
 
 $grd_fb = new extgrid($hedaers,$columns, "index.php?module=view_assignment&asg_id=$asg_id");
 $grd_fb->exp_headers = $exp_hedaers;
 $grd_fb->exp_columns = $columns;
 $grd_fb->edit=false;
 $grd_fb->delete=$enable_delete;
 $grd_fb->delete_text=REMOVE;
 $grd_fb->message =ARE_YOU_SURE_REMOVE_USER;
 

 $detailsl = access::has("view_quiz_details_asg") ? DETAILS : "";
 $download_cert = access::has("download_cert") && $row['cert_enabled'] == "1" ? CERTIFICATE : "";
 $grd_fb->id_link_direction=  ArrDirection::ValueFirst;
 $grd_fb->id_links=(array("?module=view_details"=>$detailsl,"?module=download_certificate"=>$download_cert));
 $grd_fb->id_link_key="user_quiz_id";
 $grd_fb->id_column="user_quiz_id";
 $grd_fb->id_checkbox="user_id,user_quiz_id";
 $grd_fb->delete_id_column = "UserID";
 $grd_fb->checkbox=true;
 $grd_fb->chk_class="chkfb";
 $grd_fb->grid_control_name="divFB";
 $grd_fb->column_override=array("is_success"=>"fb_success_override","status_name"=>"fb_status_override","start_sent"=>"start_override", "results_sent"=>"results_override");
 $grd_fb->PAGING = 1000000;

 $y = -1;
 function fb_success_override($row)
 {
     $name = $row['Name'].' '.$row['Surname'];
     global $YES_NO,$y;
     $y++;
     return $YES_NO[$row['is_success']]."<input type=hidden id=hdnnamesfb$y value='".$name."'><input type=hidden id=hdnuqfb$y value='".$row['user_quiz_id']."'><input type=hidden id=hdnfb$y value=".$row['user_id']." />";;
 }
  

 function fb_status_override($row)
 {
     global $ASG_STATUS;
     return $ASG_STATUS[$row['status_id']];
 }

 $grd_fb->sort_headers = array(USER_ID=>"user_id", LOGIN=>"UserName", USER_NAME=>"Name", USER_SURNAME=>"Surname" ,STATUS=>"status_name", START_SENT=>"start_sent",RESULTS_SENT=>"results_sent" ,SUCCESS=>"is_success", TOTAL_POINT=>"total_point", LEVEL=>"level_name");
 $grd_fb->default_sort = " ua.added_date asc ";
 
 $query = asgDB::GetUserResultsQuery($asg_id, 3, $grd_fb->GetSortQuery());
 
 $grd_fb->mobile_grid=false;
 $grd_fb->DrowTable($query);
 $grid_fb_html = $grd_fb->table;
 

 $local_users_result = db::exec_sql(users_db::GetUsersQuery(" ",au::get_where(true)));
 $local_user_options = webcontrols::GetOptions($local_users_result, "UserID", "Name;Surname", "-1", false);
 $ldap_users_result= db::exec_sql(users_db::GetLDAPUsersQuery());
 $ldap_user_options = webcontrols::GetOptions($ldap_users_result, "UserID", "Name;Surname;email", "-1", false);

 //$imp_users_result = db::exec_sql(orm::GetSelectQuery("v_imported_users", array(), au::arr_where(array()), "name,surname",false));
 $imp_users_result = db::exec_sql(orm::GetSelectQuery("v_imported_users", array(), array(), "name,surname",false));
 $imp_user_options = webcontrols::GetOptions($imp_users_result, "UserID", "Name;Surname", "-1", false);
 
 $variants_display="none";
 $variant_options = "";
 if($row["is_random"]==2)
 {
     $answer_variants = orm::Select("answer_variants", array(), array(), "id");
     $variant_options = webcontrols::GetOptions($answer_variants, "id", "variant_name", "-1", false, $row["variants"]);
     $variants_display="";     
 }
 
 if(isset($_POST['ajax'])) 
 {
	if(isset($_POST['send_mail']))
	{
                $msg_type = $_POST['msgtype'] ;
                
                if($msg_type=="1" && !access::has("send_start_mail_asg")) exit();
                else if($msg_type=="2" && !access::has("send_results_mail_asg")) exit();
                
		global  $asg_id;
		$user_id = db::esp($_POST['user_id'],true);
		$user_type = db::esp($_POST['user_type'] ,true);
		$user_quiz_id = db::esp($_POST['user_quiz_id'] ,true);
		$array_where =  array("user_id"=>$user_id,"assignment_id"=>$asg_id,"user_type"=>$user_type , "mail_type"=>$msg_type) ;
		if($msg_type=="2") 
		$array_where =  array("user_id"=>$user_id,"assignment_id"=>$asg_id,"user_type"=>$user_type , "mail_type"=>$msg_type,"user_quiz_id"=>$user_quiz_id) ;

		$results = orm::Select("mailed_users", array() ,$array_where, "");
		$count = db::num_rows($results);	
		if($count == 0 || $_POST['drpSendType']=="2")
		{	                    
			SendMail($user_id);
		}
	}       
        else if(isset($_POST['add_user']))
	{
           // $user_type = $_POST['is_local'] =="yes" ? 1 : 2;   
            
            if($_POST['is_local']=="1") echo $grd->table;  
            else if($_POST['is_local']=="2") echo $grd_iu->table;  
            else echo $grd_ldap->table;
            
        }
        else if(isset($_POST['control_name']))
        {                                    
            if($_POST['control_name']==$grd->grid_control_name) echo $grd->table;
            else if ($_POST['control_name']==$grd_iu->grid_control_name) echo $grd_iu->table;
            else if ($_POST['control_name']==$grd_ldap->grid_control_name) echo $grd_ldap->table;
            else echo $grd_fb->table;
        }
        else if(isset($_POST['recalc_all']))
        {    
           echo  json_encode(array("divLU"=>$grd->table,"divIU"=>$grd_iu->table,"divLDAP"=>$grd_ldap->table,"divFB"=>$grd_fb->table));
        }
        else if(isset($_POST['delete_user'])  || isset($_POST['delete_exam']) || isset($_POST['refresh_users']) || isset($_POST['recalc_points'])) //recalc_points
        {    
           echo  json_encode(array("divLU"=>$grd->table,"divIU"=>$grd_iu->table,"divLDAP"=>$grd_ldap->table,"divFB"=>$grd_fb->table));
        }
	
 }
 
 if(isset($_GET["expgrid"]))
 {
        if($_GET['cn']==$grd->grid_control_name) echo $grd->Export();
        else if ($_GET['cn']==$grd_iu->grid_control_name) echo $grd_iu->Export();
        else if ($_GET['cn']==$grd_ldap->grid_control_name) echo $grd_ldap->Export();
        else echo $grd_fb->Export();        
 }

 function SendMail($user_id)
 {
	global  $asg_id,$msg_type,$user_type,$user_quiz_id;
	$results = asgDB::GetUserInfoByAsgId($asg_id,$user_id,$user_type,$user_quiz_id);
	$row = $results[0];

	if($msg_type=="1") $temp = "quiz_start_message";
	else 
	{
		if($row['success']==1)  $temp = 'quiz_results_success'; 
		else if($row['success']==0) $temp = 'quiz_results_not_success';
		else return ;
	}

	$cmail = new cmail($temp, $row);

	$subject = str_replace("[url]", WEB_SITE_URL, $cmail->subject);	
	$subject = str_replace("[quiz_name]", $row['assignment_name'], $subject);
        $subject = str_replace("[assignment_name]", $row['assignment_name'], $subject);

	$body = str_replace("[url]", WEB_SITE_URL, $cmail->body);	
	$body = str_replace("[quiz_name]", $row['assignment_name'], $body);
        $body = str_replace("[assignment_name]", $row['assignment_name'], $body);


	if($msg_type==2)
	{
		$subject = str_replace("[start_date]", $row['added_date'], $subject);	
		$subject = str_replace("[finish_date]", $row['finish_date'], $subject);
		$subject = str_replace("[user_score]", $row['results_mode']==1 ? $row['pass_score_point'] : $row['pass_score_perc']."%" , $subject);
		$subject = str_replace("[pass_score]", $row['pass_score'], $subject);
                $subject = str_replace("[level_name]", $row['level_name'], $subject);

		$body = str_replace("[start_date]", $row['added_date'], $body);	
		$body = str_replace("[finish_date]", $row['finish_date'], $body);
		$body = str_replace("[user_score]", $row['results_mode']==1 ? $row['pass_score_point'] : $row['pass_score_perc']."%" , $body);
		$body = str_replace("[pass_score]", $row['pass_score'], $body);
                $body = str_replace("[level_name]", $row['level_name'], $body);
	}

	$m= new Mail; 
	$m->From(MAIL_FROM ); 

	$m->To( trim($row['email']));
        if(trim($row["mails_copy"])!="")
        {
           $m->Cc($row["mails_copy"]); 
        }
	$m->Subject( $subject );
	$m->Body( $body);    	
	$m->Priority(3) ;    
	//$m->Attach( "asd.gif","", "image/gif" ) ;
	
	if(MAIL_USE_SMTP=="yes")
	{
		$m->smtp_on(MAIL_SERVER, MAIL_USER_NAME, MAIL_PASSWORD ) ;    
	}
	$m->Send(); 


	$array_insert = array("user_id"=>$user_id, "user_type"=>$user_type , "assignment_id"=>$asg_id, "mail_type"=>$msg_type);

	if($msg_type=="2")
	{
            $array_insert = array("user_id"=>$user_id, "user_type"=>$user_type , "assignment_id"=>$asg_id, "mail_type"=>$msg_type,"user_quiz_id"=>$user_quiz_id);
	}

        if($_POST['drpSendType']=="2")
        {
            orm::Update("mailed_users", array("arch"=>"1"), $array_insert);
        }
        
	orm::Insert("mailed_users", $array_insert);
	
	//echo $temp;
 }


 function desc_func()
 {
        return VIEW_ASSIGNMENT;
 }

?>
