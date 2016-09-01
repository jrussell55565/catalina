<?php if(!isset($RUN)) { exit(); } ?>
<?php

 if(!access::menu("old_assignments", false) && !access::has("view_quiz_details_asg"))
 {
     util::redirect("login.php");
     exit();
 }

 require "grid.php";
 require "db/asg_db.php";
 require "db/res_temp.php";
 require "db/questions_db.php";
 require "qst_viewer.php";
 require "lib/questions_util.php";
 require "extgrid.php";
 require "lib/logs.php";


 $id=util::GetKeyID("user_quiz_id", "?module=assignments"); 
 $uq_res = asgDB::GetUserQuizById($id, access::menu("old_assignments", false));
 if(db::num_rows($uq_res)==0) util::redirect("?module=old_assignments");
     
 $uq_row = db::fetch($uq_res);
 
 $show_point_info=true;
 $show_success_msg = true; 
 
 $results = db::GetResultsAsArray(orm::GetSelectQuery("result_levels", array(), array(),"id"));
 $level_options = webcontrols::GetArrayOptions($results, "id", "level_name", $uq_row['level_id'],false);
 
 $res_display="";
 $survey_active="";
 $exam_active="";
  $log_display = "";
 if($uq_row["quiz_type"]=="2")
 {
      $res_display= "none";
      $survey_active = "class=\"active\"";
      $survey_active_tab = "active";
      $exam_active_tab="";
 }
 else
 {
     $exam_active= "class=\"active\"";
     $survey_active_tab="";
     $exam_active_tab="active";
     $log_display = access::has("view_exam_logs") ? "" : "none";
 }

 
 if(!access::has("view_quiz_details_asg") && access::menu("old_assignments")) 
 {              
     if($uq_row['user_id']!=access::UserInfo()->user_id || $uq_row['allow_review']!="1") util::redirect("?module=old_assignments");
     if($uq_row['uq_status']=="1" || $uq_row['uq_status']=="0") util::redirect("?module=old_assignments");
     
     $show_point_info = $uq_row["show_point_info"]=="1" ? true : false;
     $show_success_msg = $uq_row["show_success_msg"] =="1" ? true : false ;
 }

 $download_cert = access::has("download_cert") && $uq_row['cert_enabled'] == "1" &&  $uq_row['success']=="1" ? DOWNLOAD_CERTIFICATE : "";
 $download_link = "?module=download_certificate&id=".$uq_row['user_quiz_id'];
 
 //$subject_results = db::exec_sql(asgDB::GetUsersSubjectResults($id));
 $subject_results = db::exec_sql(asgDB::GetUsersQuizResults($id)); 
 
 $asg_res = questions_db::GetQuestionsByUserQuizId($id,"",$uq_row['qst_order']);
 
 function get_success_point($row)
 {
     global $uq_row;
     if($uq_row['results_mode']=="1")
     {
        return $uq_row['calc_mode'] =="2" ? $row['subject_apoint'] : $row['subject_point'];
     }
     else{
         return $row['subject_percent'];
     }
 }
 
 function get_success_status_options($row)
 {
     $options_arr = array("0"=>O_NO, "1"=>O_YES);
     $selected = $row['subject_success'];
     return webcontrols::BuildOptions($options_arr, $selected);
 }
 
 
 //$uq  = 0;
 function get_question($row)
 {     
     global $id,$uq_row,$uq,$show_point_info,$show_success_msg,$mobile;          
     
     $qst_viewer = new qst_viewer("#");     
     $qst_viewer->mobile = $mobile;
     
     $qst_viewer->calc_mode = $uq_row['calc_mode'];
     $total_point = $uq_row['calc_mode']=="2" ? $row["question_apoint"] : $row["total_point"];
     $qst_viewer->question_point = $uq_row['results_mode'] =="1" ? $row["question_point"] : $row["question_percent"];
     $qst_viewer->total_point=$uq_row['results_mode'] =="1" ? $total_point : $row["total_percent"];     
     $qst_viewer->penalty_point =$uq_row['results_mode'] =="1" ? $uq_row['ans_calc_mode'] =="2" ? 0 : $row["asg_penalty_point"] : 0 ;
     
     $qst_viewer->additional_text = "<font color=green>".L_NUMBER." : ".$row['parent_id']."</font><br/>";
     
     $success = 0;    
     if($qst_viewer->question_point==$qst_viewer->total_point) $success=1;
     else if($qst_viewer->total_point>0 && POINT_CALCULATION!="COMPLETE") $success=2;
     
     $qst_viewer->success_type = $success;
//     $qst_viewer->video_enabled =$video_enabled;
     $qst_viewer->user_quiz_id=$id;

     $qst_viewer->show_prev=false;

     $qst_viewer->show_next=false;
     $qst_viewer->show_finish=false;
     $qst_viewer->SetReadOnly();
     
     if($uq_row["quiz_type"]=="1")
     {        
        $qst_viewer->show_correct_answers=true;
        $qst_viewer->show_point_info=$show_point_info;
        
        if($uq_row['calc_mode']=="1" || $uq_row['calc_mode']=="3" )
        $qst_viewer->show_success_msg=$show_success_msg;
     }
     
     if($row["question_point"]=="") 
     {
         $qst_viewer->show_success_msg=false;
         $qst_viewer->show_point_info = false;
     }
     
     $qst_viewer->control_unq = $row['id'];     
     $qst_viewer->BuildQuestionWithResultset($row);
     $qst_html = $qst_viewer->html;
 //    $uq++;
     return $qst_html;
 }
 
$selected_success =  $uq_row['success'];
$status_options = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO), $selected_success); 
$total_point = $uq_row['results_mode']==1 ? $uq_row['pass_score_point'] : $uq_row['pass_score_perc'];

 if(isset($_POST["ajax"]))
 {
         if(isset($_POST["update_results"]) && access::has("update_user_results"))
         {
             $status = $_POST['status'];
             $point = $_POST['point'];
             $level = $_POST['level'];
             
             $column = $uq_row['results_mode']==1 ? "pass_score_point" : "pass_score_perc";
             orm::Update("user_quizzes", array("success"=>$status,$column=>$point,"level_id"=>$level), array("id"=>$id));
             echo SAVED;
             
             logs::add_log3(12, "New point : $point", $id);
             exit();
         } 
         else if(isset($_POST["update_subject_results"]) && access::has("update_user_results"))
         {
             $status = $_POST['status'];
             $point = $_POST['point'];
             $id = $_POST['sbj_id'];
                           
            if($uq_row['results_mode']=="1") $column = $uq_row['calc_mode'] =="2"  ? "subject_apoint" : "subject_point";
            else $column = "subject_percent";
             
             orm::Update("assignment_subject_results", array("subject_success"=>$status,$column=>$point), array("id"=>$id));
             echo SAVED;
             
             logs::add_log3(13, "Total subject point changed : $point", $id);
             exit();
         }
         else if(isset($_POST['loadqst']) && access::has("make_cor") )
         {
             $qid = util::GetInt($_POST['qid']);

             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qid ");
             $asg_row = db::fetch($asg_res);
             
             $qst_viewer = get_question_for_edit($asg_row,false);
             echo json_encode(array("rhtml"=>$qst_viewer->html,"total_point"=>$qst_viewer->total_point,"penalty_point"=>$qst_viewer->penalty_point)) ;             
             
         }
         else if(isset($_POST['saveqst']) && access::has("make_cor"))
         {             
             $post_data = $_POST['post_data'];             
             $qst_id = db::clear($_POST['qid']);                          
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);                         
             
             $db = new db();
             $db->connect();
             $db->begin();
             questions_util::UpdateQuestionValue($db, $qst_id, $id, $_POST['question_type'], $post_data);
          //   questions_util::UpdateQuesitonPoints($db, $id, $qst_id, $asg_row['ans_calc_mode']);
             $db->commit();
             $db->close_connection();   
             
             $qst_html = get_question($asg_row);
             
             
             echo json_encode(array("rhtml"=>$qst_html)) ;  
             
             logs::add_log3(13, "$qst_html", $id);
             exit();
             
         }
         else if(isset($_POST['recalc']) && access::has("recalc_points"))
         {                                   
             $qst_id = db::clear($_POST['qid']);                                                                           
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);  
             
             $db = new db();
             $db->connect();
             $db->begin();             
             questions_util::UpdateQuesitonPoints($db, $id, $qst_id, $asg_row['ans_calc_mode']);
             $db->commit();
             $db->close_connection();   
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);  
             
             $qst_html = get_question($asg_row);
             
             
             echo json_encode(array("rhtml"=>$qst_html)) ;  
             
             logs::add_log3(14, "Question points recalculated", $id);
             exit();
             
         }
         else if(isset($_POST['recalcall']) && access::has("recalc_all"))
         {             
            $date = date('Y-m-d H:i:s');
            
            $db = new db();
            $db->connect();
            $db->begin();             
            questions_util::RecalcUserResults($db, $id, 1, true);
            $db->commit();
            $db->close_connection();
            
            orm::Delete("assignment_subject_results", array("user_quiz_id"=>$id));
            asgDB::UpdateUserQuiz($id, "-1", $date,false);
            //util::redirect(util::GetCurrentUrl());
            echo "1";
            logs::add_log3(11, "Total points recalculated", $id);
            exit();
         } 
         else if(isset($_POST['reset_userquiz']) && access::has("reset_userquiz"))
         {             
             orm::Delete("user_quizzes", array("id"=>$id));
             logs::add_log3(10, "Results reseted - $id", $id);
             echo "index.php?module=view_assignment&asg_id=".$uq_row['asg_id'];
         }
         else if(isset($_POST['addmins']) && access::has("add_mins"))
         {
             $time = intval(db::clear($_POST['txtTime']));
             $newtime = intval($uq_row['user_quiz_time'])+$time;
             orm::Update("user_quizzes", array("uquiz_time"=>$newtime), array("id"=>$id));
             logs::add_log3(9, "Minutes added - $time", $id);
             echo $newtime;
         }
         else if(isset($_POST['rep_issue']) && access::has("rep_issue"))
         {
             $qid = $_POST['qst_id'];
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qid ");
             if(db::num_rows($asg_res)>0)
             {
                $_SESSION['issue_qstid'] = intval(db::clear($qid));
                echo "1"; 
             }
         }
         else if (isset($_POST['open_exam']) && access::has("change_uq_status"))
         {
                if($_POST['ptype']=="1")
                {
                    orm::Delete("assignment_subject_results", array("user_quiz_id"=>$id));
                    orm::Update("user_quizzes", array("status"=>"1","finish_date"=>array("null",true)), array("id"=>$id));
                    logs::add_log3(7, "Exam status changed - Opened", $id);
                }
                else
                {
                    orm::Update("user_quizzes", array("status"=>"4","finish_date"=>util::Now()), array("id"=>$id));
                    logs::add_log3(8, "Exam status changed - Closed", $id);
                }
                
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
                $grd_logs->column_override=array("log_text"=>"log_text_override", "log_type"=>"log_type_override","FullName"=>"full_name_override");
                $grd_logs->auto_id=true;                       
                $query = logs::get_logs(" where log_type in (7,8,9,10,11,12,13,14,15,24) and log_type_id =$id  ","", "inserted_date desc");                                                           
                $grd_logs->DrowTable($query);
                $grid_html = $grd_logs->table;

                echo $grid_html;
                exit();
         }
         else if(isset($_POST['settrue']) && access::has("set_correct")) //settrue
         {
             $qst_id = db::clear($_POST['qid']);     
             //$mtype = $_POST['mtype']=="1" ? 1:2;
             $mtype = 1;
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);  
             
             $db = new db();
             $db->connect();
             $db->begin();             
             questions_util::UpdateQuesitonPoints($db, $id, $qst_id, $asg_row['ans_calc_mode'], $mtype);
             $db->commit();
             $db->close_connection();   
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);  
             
             $qst_html = get_question($asg_row);             
             
             echo json_encode(array("rhtml"=>$qst_html)) ;  
             
             logs::add_log3(24, "Question set as true", $id); 
             exit();
         }
         else if(isset($_POST['reset_ans']) && access::has("clear_answers")) //settrue
         {
             $qst_id = db::clear($_POST['qid']);                                                                           
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);  
             
             $db = new db();
             $db->connect();
             $db->begin();             
             $db->query(orm::GetDeleteQuery("user_answers", array("user_quiz_id"=>$id, "question_id"=>$qst_id)));
             $db->query(orm::GetDeleteQuery("assignment_question_points", array("user_quiz_id"=>$id, "question_id"=>$qst_id)));
             $db->commit();
             $db->close_connection();   
             
             $asg_res = questions_db::GetQuestionsByUserQuizId($id," and qs.id = $qst_id ");
             $asg_row = db::fetch($asg_res);  
             
             $qst_html = get_question($asg_row);
             
             
             echo json_encode(array("rhtml"=>$qst_html)) ;  
             
             logs::add_log3(15, "Question answers reseted", $id); 
             exit();
             
         }
 }
 
 function log_text_override($row)
 {
     global $grd_logs;
     //return $row['log_text'];
     //str_replace("\n", "<br />", $row['headers'])
     return extgrid::GetModalRowTemplate(LOGS,$row['log_text'], $grd_logs, $row['id']); // util::replace_n(logtex
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
 
 $btn_display = "";
 $disabled="";
 if(!access::has("update_user_results"))
 {
     $btn_display="none";
     $disabled="disabled";
 }
 
$displaym = $mobile == true ? "none" : "";

$img = util::get_img($uq_row['user_photo'],false,'user_photos',100);

$finish_date = $uq_row['finish_date'] =="" ? util::Now() : $uq_row['finish_date'];
$diff = abs(strtotime($finish_date) - strtotime($uq_row['added_date']));  
$time_spent = round($diff/60,2);

function get_question_for_edit($row)
{     
     global $id,$uq_row,$uq,$mobile;          
     
     $qst_viewer = new qst_viewer("#");     
     $qst_viewer->mobile = $mobile;
     $qst_viewer->edit_enabled=true;
     $qst_viewer->calc_mode = $uq_row['calc_mode'];
     $total_point = $uq_row['calc_mode']=="2" ? $row["question_apoint"] : $row["total_point"];
     $qst_viewer->question_point = $uq_row['results_mode'] =="1" ? $row["question_point"] : $row["question_percent"];
     $qst_viewer->total_point=$uq_row['results_mode'] =="1" ? $total_point : $row["total_percent"];     
     $qst_viewer->penalty_point =$uq_row['results_mode'] =="1" ?  $uq_row['ans_calc_mode'] =="2" ? 0 : $row["asg_penalty_point"] : 0 ;               
      
     
   
     $qst_viewer->video_enabled =false;
     $qst_viewer->user_quiz_id=$id;     

     $qst_viewer->show_prev=false;

     $qst_viewer->show_next=false;
     $qst_viewer->show_finish=false;   
     
     if($uq_row["quiz_type"]=="1")
     {        
        $qst_viewer->show_correct_answers=true;
        
     }
     
     $qst_viewer->show_success_msg=false;
     $qst_viewer->show_point_info = false;
     
    // $qst_viewer->control_unq = "q1";     
     $qst_viewer->BuildQuestionWithResultset($row);
     
  //   $qst_html = $qst_viewer->html;
     
     return $qst_viewer;
   //  return $qst_html;
}

function desc_func()
{
        return VIEW_DETAILS;
}

?>
