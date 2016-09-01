<?php

  require "../lib/util.php";
  require '../config.php';
  require "../db/questions_db.php";
  require "../db/asg_db.php";
  require "qst_viewer.php";
  require '../db/mysql2.php';
  require '../db/access_db.php';  
  require "../db/orm.php";
  require "../lib/validations.php";
  require "../lib/webcontrols.php";
  require '../lib/access.php';
  if(USE_MATH=="yes") include("../mathpublisher/mathpublisher.php") ;

 //@session_start();
 if(!isset($_POST['qst_id'])) exit ;
//sleep(3);
 $priority=intval($_POST['qst_id']);
 
 $link = "";
 $user_quiz_id = -1;
 $answer_order = 1; 
 if(!isset($_POST['preview']))
 {        
     $asg_id =db::clear($_POST['aid']);
     access::menu("active_assignments");
     $link = "?module=start_quiz&id=".$asg_id;
    // $user_quiz_id = $_SESSION['user_quiz_id'];
     $answer_order=db::clear($_POST['ao_']);
     $is_random = db::clear($_POST['rndm']);
     $asg_quiz_id = db::clear($_POST['aqi']);
     $variant_quiz_id = db::clear($_POST['vqi']);
     $user_quiz_id = db::clear($_POST['uid']);
     $u_quiz_id= db::clear($_POST['uqi']);
     $qst_query = questions_db::GetQuestionsByPriority($priority, $asg_id, access::UserInfo()->user_id, db::clear($_POST['ran_']),db::clear($_POST['qz_']),$user_quiz_id,$is_random,$asg_quiz_id,$variant_quiz_id,$u_quiz_id);
 }
 else     
 {      
     
      access::has("view_qst",2);
      $qst_query = questions_db::GetQuestionsByID($priority);      
 } 

 $mobile= $_SESSION['is_mobile'] =="false" ? false : true;
 //$mobile=true;
 $qst_viewer = new qst_viewer($link);
 $qst_viewer->mobile=$mobile;
 if(isset($_POST['qst_preview']))
 {
     $qst_viewer->video_enabled=false;
 }
 
 $qst_viewer->user_quiz_id=$user_quiz_id;

 $qst_viewer->show_prev=false;

 $qst_viewer->show_next=false;
 $qst_viewer->show_finish=false;

 
 $row_qst = db::fetch(db::exec_sql($qst_query));  

 $qst_viewer->ans_priority=$answer_order;
 $qst_viewer->BuildQuestionWithResultset($row_qst);
 $qst_html = $qst_viewer->html;

 $paused = isset($row_qst['paused']) ? $row_qst['paused'] : 0;
 if($paused==1 && !isset($_POST['preview']))
 echo ASG_PAUSED ;
 else     
 echo $qst_html;

?>
