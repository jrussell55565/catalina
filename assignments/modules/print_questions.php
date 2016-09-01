<?php if(!isset($RUN)) { exit(); } ?>
<?php

 access::menu("assignments");
 access::has("print_asg_qsts",2);

 $print = true;

 require "extgrid.php";
 require "db/asg_db.php";
 require "db/questions_db.php";
 require "qst_viewer.php";
 

 $quiz_id = util::GetID("?module=assignments");
 $asg_res = db::exec_sql(questions_db::GetQuestionsQuery($quiz_id));
 
 $uq  = 0;
 function get_question($row)
 {
     global $id,$uq;

     $qst_viewer = new qst_viewer("#");          
  
     
     $qst_viewer->user_quiz_id=-1;

     $qst_viewer->show_prev=false;

     $qst_viewer->show_next=false;
     $qst_viewer->print_version=true;
     
     $qst_viewer->show_finish=false;
     $qst_viewer->SetReadOnly();
     
     $qst_viewer->show_correct_answers=isset($_GET['c']) ? true : false;
     $qst_viewer->show_success_msg=false;
     $qst_viewer->show_point_info = false;
     
     $qst_viewer->control_unq = $uq;     
     $qst_viewer->BuildQuestionWithResultset($row);
     $qst_html = $qst_viewer->html;
     $uq++;
     return $qst_html;
 }
 
function desc_func()
{
        return VIEW_DETAILS;
}

?>