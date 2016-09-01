<?php if(!isset($RUN)) { exit(); } ?>
<?php

 access::menu("active_assignments");

 require "grid.php";
 //require "db/users_db.php";
 require "db/asg_db.php";

 $asg_id = util::GetID("?module=active_assignments");
 $results=asgDB::GetAsgQueryById($asg_id);

 $row_num = db::num_rows($results);
 if($row_num==0)
 {
     util::redirect("?module=active_assignments");
     exit;
 }
 $row = db::fetch($results);

 if($row['show_intro']=="1")
 {
    $intro = $row['intro_text'];
 }
 else
 {
     archive_old() ;
     util::redirect("?module=start_quiz&id=".$asg_id);
 }

 if(isset($_POST['btnCont']))
 {
     archive_old() ;
     util::redirect("?module=start_quiz&id=".$asg_id);     
 }

 function archive_old() 
 {
	global $asg_id;
	$user_id = access::UserInfo()->user_id;
	$query = asgDB::GetActAsgByUserIDQuery($user_id," and a.id=$asg_id ");
	$results = db::exec_sql($query);
	$row = db::fetch($results);
        
        if($row['asg_cost']>0 && $row['is_paid']==0)
        {
           util::redirect("?module=start_quiz&id=".$asg_id);  
           exit();
        }
        
	$status = intval($row['user_quiz_status']);  
	if(intval($row['limited'])>=intval($row['uq_count']) && intval($status)>1)  
	{            
		orm::Update("user_quizzes", array("archived"=>1) , array("assignment_id"=>$asg_id,"user_id"=>$user_id));
	}
 }

 function desc_func()
 {
        return INTRO;
 }
 
?>
