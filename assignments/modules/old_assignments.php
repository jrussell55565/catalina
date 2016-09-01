<?php if(!isset($RUN)) { exit(); } ?>
<?php
    if(!access::menu("old_assignments", false) && !access::has("view_local_user_quizzes") && !access::has("view_imp_user_quizzes") && !access::has("view_fb_user_quizzes") && !access::has("view_ldap_user_quizzes"))
    {
        util::redirect("login.php");
        exit();
    }
    
    $user_mode = 2;
    if(access::has("view_local_user_quizzes") || access::has("view_imp_user_quizzes") || access::has("view_fb_user_quizzes") || access::has("view_ldap_user_quizzes")) $user_mode=1;

    require "extgrid.php";
    require "db/asg_db.php";

    $user_id = access::UserInfo()->user_id;

    if($user_mode==1 && isset($_GET['id']))
    {
	$user_id= util::GetID("?module=assignments");	
    }  

    $columns_quiz = array("assignment_name"=>"text", "added_date"=>"text","finish_date"=>"text","is_success"=>"text","pass_score"=>"text","total_point"=>"text","level_name"=>"text", "allow_review"=>"test","cert_enabled"=>"text");
    $headers_quiz = array(QUIZ_SURV_NAME,START_DATE,FINISH_DATE,SUCCESS,PASS_SCORE,TOTAL_POINT,LEVEL,VIEW_DETAILS,CERTIFICATE);    
    
    $exp_hedaers = array(QUIZ_SURV_NAME,START_DATE,FINISH_DATE,SUCCESS,PASS_SCORE,TOTAL_POINT,LEVEL,VIEW_DETAILS);    
    $exp_columns = array("assignment_name"=>"text", "added_date"=>"text","finish_date"=>"text","is_success"=>"text","pass_score"=>"text","total_point"=>"text","level_name"=>"text", "allow_review"=>"text");

    $query = asgDB::GetOldAssignmentsQuery($user_id,1);
    
    $grd_quiz = new extgrid($headers_quiz,$columns_quiz, "index.php?module=old_assignments");
    $grd_quiz->exp_headers = $exp_hedaers;
    $grd_quiz->exp_columns = $exp_columns;
    $grd_quiz->grid_control_name="div_grid";
    $grd_quiz->edit=false;
    $grd_quiz->delete=false;
    $grd_quiz->chk_class="chkquiz";
    $grd_quiz->column_override=array("finish_date"=>"finish_date_override","added_date"=>"added_date_override","is_success"=>"success_override", "allow_review"=>"review_override", "cert_enabled"=>"cert_enabled_override","assignment_name"=>"assignment_name_override");    
    
    function added_date_override($row)
    {        
        return date('d-m-Y H:i:s', strtotime($row['added_date']));
    }
    
    function finish_date_override($row)
    {        
        return date('d-m-Y H:i:s', strtotime($row['finish_date']));
    }

    function assignment_name_override($row)
    {        
        global $user_mode;
        $assignment_name=$row['assignment_name'];
      //  $img_src = $row['asg_image'];
       // if($img_src=="") $img_src = "no.jpg";
        $share_link = "";
        $user_quiz_id=$row['user_quiz_id'];
    
        if($user_mode==2 && $row['fb_share']!=0 && $row['finish_date']!="") //access::UserInfo()->imported==2 && 
        {
            $share_link = "<a href='javascript:post_to_wall($user_quiz_id)'>".util::get_fb_button()."</a>";
        }
       
        
        if(isset($_GET['expgrid'])) return $assignment_name;
        
        $html = "$assignment_name $share_link";
        return $html;
    }
    
    function success_override($row)
    {
        global $YES_NO;
        return $YES_NO[$row['is_success']];
    }
    
    function cert_enabled_override($row)
    {        
        if($row['cert_enabled']=="0" || $row['success']=="0") return "";
        
        return "<a href='?module=download_certificate&id=".$row['id']."'>".DOWNLOAD_CERTIFICATE."</a>";
    }
    
    function review_override($row)
    {
        global $user_mode;
        if(($row['allow_review']=="1" && $row['uq_status']!="1" && $row['uq_status']!="0" ) || $user_mode==1)
        {
            return "<a href='?module=view_details&user_quiz_id=".$row['id']."'>".VIEW_DETAILS."</a>";            
        }
        else
        {
            return NOT_ENABLED;
        }
    }
    

    $grd_quiz->DrowTable($query);
    $grid_quiz_html = $grd_quiz->table;


    $columns_surv = array("quiz_name"=>"text", "added_date"=>"text","finish_date"=>"text","allow_review"=>"test");
    $headers_surv = array(QUIZ_SURV_NAME,START_DATE,FINISH_DATE,VIEW_DETAILS);
    
    $exp_hedaers = array(QUIZ_SURV_NAME,START_DATE,FINISH_DATE,VIEW_DETAILS);        

    $query = asgDB::GetOldAssignmentsQuery($user_id,2);    
    $grd_surv = new extgrid($headers_surv,$columns_surv, "index.php?module=old_assignments");
    $grd_surv->exp_headers = $exp_hedaers;
    $grd_surv->exp_columns = $columns_surv;
    $grd_surv->grid_control_name="div_surv";
    $grd_surv->edit=false;
    $grd_surv->delete=false;
    $grd_surv->chk_class="chksurvey";
    $grd_surv->column_override=array("finish_date"=>"finish_date_override","added_date"=>"added_date_override","allow_review"=>"review_override");
    $grd_surv->DrowTable($query);
    $grid_surv_html = $grd_surv->table;
    
    if(isset($_POST['ajax']))
    {
        if($_POST['control_name']=="div_surv")
        {
            echo $grd_surv->table;
        }
        else
        {
            echo $grd_quiz->table;
        }
    }
    if(isset($_GET["expgrid"]))
    {
        if($_GET['cn']==$grd_quiz->grid_control_name) echo $grd_quiz->Export();        
        else echo $grd_surv->Export(); 
    }

    function desc_func()
    {
        return OLD_ASSIGNMENTS;
    }
?>
