<?php if(!isset($RUN)) { exit(); } ?>
<?php

 access::menu("active_assignments");

 require "grid.php";
 require "db/questions_db.php";
 require "db/asg_db.php";
 include "lib/libmail.php";
 require "lib/cmail.php";
 require "lib/questions_util.php";
 require "qst_viewer.php";
 require "db/res_temp.php";

 $app_display = "";
 $msg_display = "none"; 
 $msg_text = "";
 $qst_html ="";
 $timer_script = "";
 $timer_display = "none";
 $pager_html="";
 $pager_display="";
 $qst_rate_display = "";

 $emulate_goto=true;
 while($emulate_goto==true) { // emulating goto operator 

 $user_id = access::UserInfo()->user_id;
 $asg_id = util::GetID("?module=active_assignments");
 $ID = $asg_id;
 $_SESSION['asg_id']=$asg_id; 

 $active_asg = asgDB::GetActAsgByUserID($user_id, $asg_id);
 
 $asg_num = db::num_rows($active_asg);
 if($asg_num==0)
 {
     DisplayMsg("error",QUIZ_NO_ACCESS,false);
     break;
 }

 $asg_row = db::fetch($active_asg);
 $status = intval($asg_row['user_quiz_status']);
 $user_quiz_id = $asg_row['user_quiz_id'];
 $allow_review = $asg_row['allow_review'];
 $send_results = $asg_row['send_results'];
 $results_mode = $asg_row['results_mode'];
 $result_template_id= $asg_row['results_template_id'];
 $allow_prev_change = $asg_row['allow_change_prev'];
 $show_point_info = $asg_row['show_point_info'];
 $show_success_msg = $asg_row['show_success_msg']; 
 $cert_name = $asg_row['cert_name'];
 $cert_enabled = $asg_row['cert_enabled']; 
  
 $quiz_type = $asg_row['quiz_type'];
 $subjects_html = "";
 $subject_display = $asg_row['show_subject_name'] == "1" ? "" : "none";
 
 if($asg_row['qst_rate_id']=="-1") $qst_rate_display="none";
 
 $js_script="";
 
 if($asg_row['asg_cost']>0 && $asg_row['is_paid']==0)
 {
    DisplayMsg("error",ASG_NEED_PAY,false);
    break;
 }

 if($status==0)
 {    
     $date = util::Now();
     $user_quiz_id=db::exec_insert(orm::GetInsertQuery("user_quizzes", array("assignment_id"=>$asg_id,
                                                            "user_id"=>$user_id,
                                                            "status"=>"1",
                                                            "added_date"=>$date,
                                                            "success"=>"0"
                                                       )));     
 }
 else if($status>=2)
 {         
    DisplayMsg("error",ALREADY_FINISHED,false);
    break;
 }

 $minuets = 0;
 $seconds = 0;
 $showtimer = 0;
 if($quiz_type=="1" && $asg_row['quiz_time']!="0") // if survey
 {          
    $showtimer=1;
    $timer_display= "";
    $ended=ShowTimer($status,$asg_row);    
    if($ended==true) break;  
 }

 $_SESSION['user_quiz_id']=$user_quiz_id;
 
 $page = "?module=start_quiz&id=".$asg_id ;
 $qst_viewer = new qst_viewer($page);
 $qst_viewer->mobile = $mobile;
 $qst_viewer->user_quiz_id=$user_quiz_id;
 $priority = $qst_viewer->GetPriority(); 
 
 

 if(isset($_POST['btnNext']))
 {
     $already_answered = IsAlreadyAnswered($user_quiz_id,$_POST["qstID"]);
     $just_inserted = false;
     if(!$already_answered)
     {
        $just_inserted = UpdateValues();                
     }
     if($_POST['finish_quiz']=="1") break;
     
     if($already_answered || !$just_inserted  || $allow_prev_change=="1") $priority =$qst_viewer->GetNextPriority();    
     else $priority =$qst_viewer->GetCurrentPriority();
 }
 if(isset($_POST['btnPrev']))
 {
    $priority =$qst_viewer->GetPrevPriority();
 }

 if(isset($_POST['load_question']))
 {
    $priority = $_POST['load_priority'];     
 }
 
 if(isset($_POST['mark_for_review']))
 {
    $mark_qst_id = db::clear($_POST['mark_for_review_id']);   
    asgDB::MarkQstForReview($user_quiz_id, $mark_qst_id, 1);
 }
 
 $qst_query = questions_db::GetQuestionsByPriority($priority, $asg_id, $user_id , $asg_row['qst_order'],$asg_row['quiz_id'],$user_quiz_id,$asg_row['is_random'],$asg_row['asg_quiz_id'], $asg_row['variant_quiz_id'],$asg_row['u_quiz_id']);
 
 $row_qst = db::fetch(db::exec_sql($qst_query));
 
 CheckPresentation();

 $alr_answered = IsAlreadyAnswered($user_quiz_id, $row_qst["id"]); 
 $edit_enabled = !$alr_answered;
  
 if($alr_answered)
 {
    $qst_viewer->show_correct_answers=true;
    
    $point_results = asgDB::GetQuestionPointQuery($user_quiz_id, $row_qst["id"]);   
    $points_row = db::fetch($point_results);    
    $qst_viewer->calc_mode = $asg_row['calc_mode'];
    $total_point = $asg_row['calc_mode']=="2" ? $points_row["question_apoint"] : $points_row["total_point"];
    $qst_viewer->question_point = $results_mode =="1" ? $points_row["question_point"] : $points_row["question_percent"];
    $qst_viewer->total_point=$results_mode =="1" ? $total_point : $points_row["total_percent"];
    $qst_viewer->penalty_point = $results_mode =="1" ? $points_row["penalty_point"] : 0 ;
    
    $success = 0;    
    if($qst_viewer->question_point==$qst_viewer->total_point) $success=1;
    else if($qst_viewer->total_point>0 && POINT_CALCULATION!="COMPLETE") $success=2;
    
    if($show_success_msg=="1")
    {       
        if($qst_viewer->calc_mode!="2") $qst_viewer->show_success_msg=true;
        $qst_viewer->success_type=$success;
    }
    
    if($show_point_info=="1")
    {        
        $qst_viewer->quiz_type=$quiz_type; 
        $qst_viewer->show_point_info=true;
    }
    
 } else  asgDB::MarkQstForReview($user_quiz_id, $row_qst["id"], 0);
     
 if($priority==1)
 {
     $qst_viewer->show_prev=false;
 }
 
 if($row_qst['next_priority']==-1)
 {
     $qst_viewer->show_next=false;
     $qst_viewer->show_finish=true;
 }
// echo $qst_query; 
 

 //$qst_viewer->show_correct_answers=true;
 
 $qst_viewer->show_mark=true;
 $qst_viewer->edit_enabled=$edit_enabled;
 $qst_viewer->ans_priority=$asg_row['answer_order'];
 $qst_viewer->BuildQuestionWithResultset($row_qst);
 $qst_html = $qst_viewer->html;
 $ids=$qst_viewer->GetIDS();
 

// $row_num = db::num_rows($qst_results);

// if($row_num==0)
 //{
//    DisplayError("You don't have access to this quiz/survey");
 //}
 
 $sbj_first_questions = array();
 
 $pager_html = GetPager(); 
 $subjects_html = GetSubjects();


 $additional_scripts = "";

 $presentation_text = base64_encode($presentation_text);
 
 if(isset($_POST['data_post']))
 {
    echo $qst_html."[{sep}]".$pager_html."[{sep}]".$ids."[{sep}]".$subjects_html."[{sep}]".$presentation_text."[{sep}]".$asg_row['qst_rate_id']."[{sep}]".$row_qst['id']."[{sep}]".$minuets."[{sep}]".$seconds."[{sep}]".$showtimer;
 }
 else
 {
     if($presentation_text!="") $additional_scripts = "setTimeout(function(){ LoadPresentation('".$presentation_text."')},0)";
        // $additional_scripts = "<script language='javascript'>setTimeout(function(){ LoadPresentation('".$presentation_text."')},0)</script>";
 }
 
 if($row['qst_rate_id']!="-1")
 {      
     $qst_id_for_rate = $row_qst['parent_id'];
     if($qst_id_for_rate==0) $qst_id_for_rate = $row_qst['id'];
     $additional_scripts.="\n DrowQstRating('".$asg_row['qst_rate_id']."','".$qst_id_for_rate."')";
 }

 $emulate_goto =false;
 
 }

 function UpdateValues()
 {
    global $user_quiz_id,$js_script,$asg_row;
    $inserted = false;
    
    $db = new db();
    $db->connect();
    $db->begin();

    try
    {
     
     $current_qst_id=intval($_POST['qstID']);
     $inserted = questions_util::UpdateQuestionValue($db,$current_qst_id,$user_quiz_id,$_POST['qst_type'],$_POST['post_data']);
          
     $db->query(orm::GetDeleteQuery("assignment_question_points", array("user_quiz_id"=>$user_quiz_id, "question_id"=>$current_qst_id)));
     $db->query(asgDB::UpdateQstPointQuery($user_quiz_id, $current_qst_id, $asg_row['ans_calc_mode']));
     
     $db->commit();

     if($_POST['finish_quiz']=="1")
     {                  
	  global $fdate,$send_results;
	  $fdate = util::Now();
          $update_results=asgDB::UpdateUserQuiz($user_quiz_id,2,$fdate);
          $msg = GetQuizResults($update_results[0],$update_results[1]);
          DisplayMsg("warning",$msg,true);
          echo "[{sep}]".$js_script;
	  if($send_results == "1") SendMail($update_results[0]);
     }     

     $db->commit();

    }
    catch(Exception $e)
    {
        echo $e->getMessage();
        $db->rollback();
    }
    $db->close_connection();    
    
    return $inserted;
 }



 function GetQuizResults($row,$tmp_row)
 {
    global $quiz_type,$user_quiz_id,$allow_review,$success,$asg_row,$result_template_id,$cert_enabled,$js_script,$ended;
    
    $msg = QUIZ_FINISHED."<br><br>";      
    if($row['show_results']=="1" && $quiz_type=="1")
    {
        $total = $row['total_point'];
        if($row['results_mode']=="2") $total = $row['total_perc']." %"; 
        
      //  $result_template=db::exec_sql(res_temp::GetTemplateByPoint($result_template_id, $total));                
      //  $tmp_row = db::fetch($result_template);     
        $template_content = $tmp_row["template_content_u"];
        $prefix="u";
      //  $level_id = $tmp_row["level_id_u"];
        
        $success =false;
        if($row['quiz_success']=="1")
        {
            $template_content = $tmp_row["template_content_s"];
            $prefix="s";
            $success = true;
        }                
        
        if($tmp_row["level_id_f"]!="") 
        {
            $template_content = $tmp_row["template_content_f"];            
        }
        
      //  orm::Update("user_quizzes", array("level_id"=>$level_id), array("id"=>$user_quiz_id));

        $msg = ReplaceVars($template_content, $asg_row, $row);  	
        
        if($success && $cert_enabled=="1")
        {
            $msg.="<br><br><a href='?module=download_certificate&id=".$user_quiz_id."'>".DOWNLOAD_CERTIFICATE."</a>";
        }
    }    
    if($allow_review=="1")
    {
        $msg.="<br><br><a href='?module=view_details&user_quiz_id=".$user_quiz_id."'>".VIEW_DETAILS."</a>";
    }
    if(isset($tmp_row))
    {
        $fb_message = addslashes(db::clear(ReplaceVars($tmp_row["fb_message_".$prefix], $asg_row, $row))); 
        $fb_name = addslashes(db::clear(ReplaceVars($tmp_row["fb_name_".$prefix], $asg_row, $row))); 
     //   $fb_description = db::clear(ReplaceVars($tmp_row["fb_description"], $asg_row, $row)); 
        $fb_description = addslashes($asg_row['short_desc']);
        $fb_link=addslashes(db::clear(ReplaceVars($tmp_row["fb_link_".$prefix], $asg_row, $row)));
        $asg_img = util::get_asg_image($asg_row['asg_image']);
        $js_post_wall = "postToWall('$fb_message','$fb_name','$fb_link','$asg_img','$fb_description')";
        $show_fb_post="none";
        if($asg_row['fb_share']==1 ) //|| $ended==true
        {
            $show_fb_post="";
            $msg.="<input type=hidden id=fbshare value='1' />";
        }
        else if($asg_row['fb_share']==2)
        {
            $show_fb_post="none";
            $js_script = $js_post_wall;
            $msg.="<input type=hidden id=fbshare value='2' />";
        }
        
        $msg.="<br><br><a id=btnFBPost href='#' style='cursor:hand;display:$show_fb_post' onclick=\"$js_post_wall\">".util::get_fb_button()."</a>";
    }
   
    return $msg;
 }

 function SendMail($row)
 {
    
	global $success,$user_id,$asg_row,$user_quiz_id;

//	$results = orm::Select("user_quizzes", array() , array("id"=>$user_quiz_id), "");
//	$row = db::fetch($results);
	
	$temp = "quiz_results_success";
	if(!$success) $temp = "quiz_results_not_success";
	$cmail = new cmail($temp,"");

	$subject = $cmail->subject;
	$body = $cmail->body;

	$subject = ReplaceVars($subject, $asg_row , $row);
	$body = ReplaceVars($body, $asg_row , $row);

    try
    {
	$m= new Mail; 
	$m->From(MAIL_FROM ); 
	$m->To( trim(access::UserInfo()->email) );
        if(trim($asg_row["mails_copy"])!="")
        {
           $m->Cc($asg_row["mails_copy"]); 
        }
	$m->Subject( $subject);
	$m->Body( $body);    	
	$m->Priority(3) ;    
	//$m->Attach( "asd.gif","", "image/gif" ) ;

	if(MAIL_USE_SMTP=="yes")
	{
		$m->smtp_on(MAIL_SERVER, MAIL_USER_NAME, MAIL_PASSWORD ) ;    
	}
	$m->Send(); 

    }
    catch(Exception $e)
    {
    //    echo $e->getMessage();
    //    $db->rollback();
    }
	
 }

 function ReplaceVars($var,$asg_row,$row)
 {
	global $fdate;
        $total_point = $row['calc_mode']!="2" ? $row['total_point'] : $row['total_apoint'];
	$var = str_replace("[quiz_name]", $asg_row['assignment_name'],$var);
        $var = str_replace("[level_name]", $asg_row['level_name'],$var);
        $var = str_replace("[assignment_name]", $asg_row['assignment_name'],$var);
	$var = str_replace("[start_date]", $asg_row['uq_added_date'],$var);
	$var = str_replace("[finish_date]", $fdate,$var);
	$var = str_replace("[pass_score]", $row['pass_score'],$var);
	$var = str_replace("[user_score]", $row['results_mode']=="1" ? $total_point : $row['total_perc']." %" ,$var);
	$var = str_replace("[UserName]", access::UserInfo()->login,$var);
	$var = str_replace("[Name]", access::UserInfo()->name,$var);
	$var = str_replace("[Surname]", access::UserInfo()->surname,$var);
	$var = str_replace("[email]", access::UserInfo()->email,$var);
	$var = str_replace("[url]", WEB_SITE_URL,$var);
	return $var;
 }

 function DisplayMsg($type,$msg,$isajax)
 {
     if(isset($_POST['ajax'])) $isajax=true;
     
     if($isajax==true)
     {
        if($type=="error")
        {
            echo "error:".$msg;
        }
        else if($type=="warning")
        {
             echo "warni:".$msg;
        }
        else
        {
             echo $msg;
        }
     }
     else
     {
        
        global $app_display,$msg_display,$msg_text,$timer_display,$pager_display,$qst_rate_display;
        $app_display="none";
        $msg_display = "";
        $msg_text=$msg;
        $timer_display="none";
        $pager_display="none";
        $qst_rate_display="none";
     }

    // echo $msg;

 }
 //$post_script = "";
 function ShowTimer($status,$row)
 {
     global $js_script,$post_script,$ended,$asg_id,$user_quiz_id,$minuets,$seconds;
     $ended = false;
     $start_date =$row['uq_added_date'];
     if($status=="0") $start_date = util::Now();

     $diff = abs(strtotime(util::Now()) - strtotime($start_date));     

     $total_minutes = intval($diff/60);
     
     $paused_minutes = db::exec_sql_single_value(asgDB::GetPausedTime($asg_id, $user_quiz_id), "totalmin", 0);
   
     $quiz_time =intval($row['user_quiz_time']) + intval($paused_minutes);

     $minuets = $quiz_time - $total_minutes -1;
     $seconds = 60-($diff%60);
 
     if($total_minutes>=intval($quiz_time))
     {
        $ended=true;    
        global $user_quiz_id,$fdate;         
        $fdate = util::Now();
        $update_results=asgDB::UpdateUserQuiz($user_quiz_id,3,util::Now());
        $msg="<font color='red' ><b>".TIME_ENDED."</b></font> <br><br>";
        $msg.=GetQuizResults($update_results[0],$update_results[1]);        
    //    $msg.="<script language=javascript>alert('on time end');</script>";
        DisplayMsg("warning",$msg,false);                       
     }
     else
     {      
         global $timer_script;
         $timer_script="<script language=javascript>Init_Timer($minuets,$seconds)</script>";
     }
     return $ended;

 }
 
  
 $presentation_text = "";
 function CheckPresentation()
 {
     global $row_qst,$user_quiz_id,$asg_id,$presentation_text;
          
     $id = db::exec_sql_single_value(orm::GetSelectQuery("assignment_qst_views", array("id"), array("user_quiz_id"=>$user_quiz_id, "subject_id"=>$row_qst['parent_quiz_id']), ""), "id");
     if($id=="") 
     {
         orm::Insert("assignment_qst_views", array("user_quiz_id"=>$user_quiz_id,"subject_id"=>$row_qst['parent_quiz_id'], "qst_id"=>$row_qst['id'], "inserted_date"=>util::Now()));
         $query = asgDB::GetAsgSubjects($asg_id, " AND ab.pres_id!=-1 AND ab.subject_id=".$row_qst['parent_quiz_id']);
         $p_res = db::exec_sql($query);
         if(db::num_rows($p_res)==0) return ;
         $p_row = db::fetch($p_res);
         if($p_row['pres_text']!="") 
         {
             $presentation_text = $p_row['subject_name']."[{psep}]".$p_row['pres_text']."[{psep}]".$p_row['pres_duration'];
         }
     }
     
 }
 
 function GetSubjects()
 {     
     global $asg_id,$row_qst,$page,$sbj_first_questions,$subjects_res,$asg_row,$mobile;
     $subject_names = "";
     
     //$subjects_res = db::exec_sql(asgDB::GetQuestionsSubjects($asg_row['is_random'], $asg_id, $asg_row['quiz_id']));
     $subjects_res = db::exec_sql(asgDB::GetQuestionQuizzes($asg_id));
     
     $seperator = $mobile == true ? "<br />" : "";
     
     while($row = db::fetch($subjects_res))
     {
         $subject_id= $row['subject_id'];
         $color = "white";
         if($subject_id==$row_qst['parent_quiz_id']) $color="yellow";
         if(isset($sbj_first_questions['s'.$subject_id]))
         {             
            $row_p = $sbj_first_questions['s'.$subject_id];
            $onclick="LoadQst(\"$page\",$row_p[question_type_id],$row_p[priority],$row_p[id],0)";
            $subject_names.="&nbsp;<input class='btn btn-primary' onclick='$onclick' style='color:".$color."' type='button' value='".$row['subject_name']."' />$seperator";
         }
     }
     
     return $subject_names;
     //<input style="width:120px;border:0;color:blue" type="button" value ="Fizika" />
 }
 
 function GetAnswersCountHtml()
 {
      global $arr;
      $html= C_ANSWERED." - ".ANSWERED."(".$arr['answered'].")<br>";
      $html.= C_NOTANSWERED." - ".NOTANSWERED."(".$arr['unanswered'].")<br>";
      $html.= C_CURRENT." - ".CURRENT."<br>";
      $html.= C_MARK_REV." - ".MARKED_REV."(".$arr['marked'].")<br>";
      return $html;
 }

 function GetPager()
 {
      global $priority,$asg_id,$page,$asg_row,$user_id,$user_quiz_id,$sbj_first_questions,$mobile;
      //echo questions_db::GetQuestionsByAsgIdQuery($asg_id,$asg_row['quiz_id'], $user_id,$asg_row['qst_order'],$user_quiz_id,$asg_row['is_random'],$asg_row['asg_quiz_id'], $asg_row['variant_quiz_id']);
      $res_qst=db::exec_sql(questions_db::GetQuestionsByAsgIdQuery($asg_id,$asg_row['quiz_id'], $user_id,$asg_row['qst_order'],$user_quiz_id,$asg_row['is_random'],$asg_row['asg_quiz_id'], $asg_row['variant_quiz_id'], $asg_row['u_quiz_id']));
      if(db::num_rows($res_qst)==0) return "";
      $i=0;
      $pager_html = "";
      $finish = 0;
      $variant_quiz_id = $asg_row['variant_quiz_id'];
      $variant_quiz_id =  $variant_quiz_id!= "" ? $variant_quiz_id : 0;
      
      global $arr;
      $arr['answered'] = 0;
      $arr['marked'] = 0;
      $arr['unanswered'] = 0;
      
      while($row=db::fetch($res_qst))
      {          
                 $br = "";
                 
                 $bgcolor="red";
                 if($priority==$row['priority'])
                 {
                     $bgcolor = "orange";
                 }                
                 else if($row["aqp_id"]!="")
                 {
                     $bgcolor = "green";
                 }
                 else if($row["marked"]>0)
                 {
                     $bgcolor = "silver";
                 }
                 
                 if($row["aqp_id"]!="")
                 {
                     $arr['answered']++;
                 }
                 else
                 {
                    if($row["marked"]>0)
                    {
                        $arr['marked']++;
                    }
                    $arr['unanswered']++;
                 }

                 $u_quiz_id = $asg_row['u_quiz_id'] == "" ? 0 : $asg_row['u_quiz_id'];
                 
                 $event = "onmouseover";
                 if($mobile) $event = "xonmouseover";
                 
                 $i%5==0 ? $br = "<br />" : $br = "";
                 $pager_html.= "$br<span style='display: inline-block; margin-top:2px' ><a border=0 style='cursor:pointer;background-color:$bgcolor;color:white' onmouseout='HideObject(\"tblTip\")' ".
                               " $event='ShowQst(event.pageX, event.pageY ,".$row['priority'].", ".$asg_row['qst_order'].", ".$asg_row['quiz_id'].", ".$asg_row['answer_order'].",".$asg_row['is_random'].",".$asg_row['asg_quiz_id'].",".$variant_quiz_id.",$asg_id,$user_quiz_id,".$u_quiz_id."  )' ".
                               " onclick='LoadQst(\"$page\",$row[question_type_id],$row[priority],$row[id],$finish)'>&nbsp;<b>".($i+1)."</b>&nbsp;</a></span>&nbsp;";
                 $i++;
                 
                 //$subject_id = $row['subject_id'];                 
                 //if(!isset($sbj_first_questions['s'.$subject_id])) $sbj_first_questions['s'.$subject_id] = $row;
                 
                 $subject_id = $row['parent_quiz_id'];                   
                 if(!isset($sbj_first_questions['s'.$subject_id])) $sbj_first_questions['s'.$subject_id] = $row;
                 
      }

      return $pager_html;
 }

 function IsAlreadyAnswered($user_quiz_id,$my_qst_id)
 {         
      global $allow_prev_change,$quiz_type;
//      if($quiz_type=="2") return 
      $is_already_answered = false;  
      if($allow_prev_change=="0")
      {
         $is_already_answered = asgDB::IsAlreadyAnswered($user_quiz_id, $my_qst_id);
         //if($is_already_answered==true) $is_already_answered=true;
      } 
      return $is_already_answered;
 }
 
 function desc_func()
 {
        global $asg_row;
        return $asg_row['assignment_name']." ( ".access::UserInfo()->name." ".access::UserInfo()->surname." )";
 }

?>
