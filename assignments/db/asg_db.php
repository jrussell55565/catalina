<?php

class asgDB
{
    public static function GetAsgQuery($orderby="asg.added_date desc")
    {
        $sql = "select asg.*, q.quiz_name from assignments asg left join quizzes q on q.id=asg.quiz_id ".au::get_where(false, "asg")." [{where}]  order by $orderby";
        return $sql;
    }

    public static function GetAsgQueryById($id)
    {
        $sql = "select * from assignments asg left join quizzes q on q.id=asg.quiz_id where asg.id=$id ".au::get_where(true, "asg");        
        return db::exec_sql($sql);
    }

    public static function DeleteAsgById($id)
    {
        //$sql = "delete from assignment_users where assignment_id=$id ;";
        //$sql = "delete from quizzes where parent_id<>0 and id in (select quiz_id from assignments where id=$id) ;";
        $sql = " delete from assignments where id=$id ".au::get_where();
        db::exec_sql($sql);
    }

    public static function DeleteRelatedQuiz($asg_id)
    {
	
        $sql = "delete from questions where quiz_id in (select id from quizzes where parent_id<>0 and id in (select quiz_id from assignments where id=$asg_id)) ";
	db::exec_sql($sql);
	
	$sql = "delete from quizzes where parent_id<>0 and id in (select quiz_id from assignments where id=$asg_id) ";
	db::exec_sql($sql);
	
    }

    public static function ChangeStat($stat,$id)
    {
        $sql = "update assignments set status=$stat where id=$id";
        return db::exec_sql($sql);
    }

    public static function GetActAsgByUserIDQuery($user_id,$where="")
    {
        $sql="select a.id as asg_id,a.*,ifnull(ua.uquiz_time,a.quiz_time) as user_quiz_time, (case when asg_cost = ifnull(mc_gross,0) then 1 else 0 end) as is_paid ,".
	" q.* ,ifnull(ua.status,0) as user_quiz_status, ua.finish_date,ua.id as user_quiz_id,".
        " (select count(*) from user_quizzes uqc where uqc.assignment_id=a.id and uqc.user_id=$user_id ) as uq_count ".          
        " from assignments a ".
        " left join payment_orders po on po.user_id =$user_id and payment_type=2 and payment_type_id=a.id ".
        " left join quizzes q on a.quiz_id = q.id ".
        " left join user_quizzes ua on ua.assignment_id=a.id and ua.archived=0 and ua.user_id=".$user_id.
        " where a.paused=0 and a.status = 1 $where and a.id in  (".
        " select assignment_id from assignment_users where user_id = ".$user_id.
        ") AND IFNULL(a.v_start_time, DATE_ADD(NOW(), INTERVAL -100 YEAR)) <= STR_TO_DATE('".util::Now()."', '%Y-%c-%e %H:%i:%s') ".
        "  AND IFNULL(a.v_end_time, DATE_ADD(NOW(), INTERVAL 100 YEAR)) >= STR_TO_DATE('".util::Now()."', '%Y-%c-%e %H:%i:%s') ".
        " order by a.added_date desc";
       
        return $sql;
    }

    public static function GetActAsgByUserID($user_id, $asg_id)
    {
        $sql="select a.id as asg_id,a.*,ifnull(ua.uquiz_time,a.quiz_time) as user_quiz_time, (case when asg_cost = ifnull(mc_gross,0) then 1 else 0 end) as is_paid,q.quiz_name,vq.quiz_id as variant_quiz_id,au.u_quiz_id,a.quiz_id as asg_quiz_id,rl.level_name,".
	" q.* ,ifnull(ua.status,0) as user_quiz_status , ua.id as user_quiz_id ,  ua.finish_date as uq_finish_date, ua.added_date as uq_added_date".
        " from assignments a ".
        " left join payment_orders po on po.user_id =$user_id and payment_type=2 and payment_type_id=a.id ".                
        " left join quizzes q on a.quiz_id = q.id ".
        " left join assignment_users au on au.user_id=$user_id and assignment_id=a.id ".
        " left join variant_quizzes vq on vq.variant_id=au.variant_id and vq.asg_id=a.id ".
        " left join user_quizzes ua on ua.assignment_id =a.id and ua.archived=0 and ua.user_id=".$user_id.
        " left join result_levels rl on rl.id=ua.level_id ".
        " where a.paused=0 and a.status = 1 and  a.id in  (".
        " select assignment_id from assignment_users where user_id = ".$user_id.
        ") and a.id=".$asg_id.
        " AND IFNULL(a.v_start_time, DATE_ADD(NOW(), INTERVAL -100 YEAR)) <= STR_TO_DATE('".util::Now()."', '%Y-%c-%e %H:%i:%s') ".
        " AND IFNULL(a.v_end_time, DATE_ADD(NOW(), INTERVAL 100 YEAR)) >= STR_TO_DATE('".util::Now()."', '%Y-%c-%e %H:%i:%s') ".                
        " order by a.added_date desc";

        return db::exec_sql($sql);
        
    }
    
    public static function IsAlreadyAnswered($user_quiz_id,$question_id)
    {
        $answered = false;
        $sql = "SELECT COUNT(*) AS rowcount FROM user_answers where user_quiz_id=$user_quiz_id and question_id=$question_id ";        
        $res = db::exec_sql($sql);
        $row = db::fetch($res);
        if($row["rowcount"]!="0") $answered = true;
        
        return $answered;
    }

    public static function GetOldAssignmentsQuery($user_id,$mode)
    {
        $sql ="select uq.*,rl.level_name,q.quiz_name,asg.quiz_type,asg.results_mode,asg.show_results ,asg.allow_review,uq.status as uq_status,asg.status as asg_status,".
        "asg.assignment_name,asg.cert_enabled,uq.finish_date as uq_finish_date,asg.fb_share,uq.id as user_quiz_id,".
        " (case show_results when 1 then asg.pass_score  else '' end) pass_score,".
        " (CASE show_results when 2 then 'Not enabled' ELSE (case success when 1 then 'Yes' else 'No' end) end) is_success ,".
        " (CASE show_results when 1 then (case results_mode when 1 THEN pass_score_point else pass_score_perc end) else '' end) total_point".
        " from user_quizzes uq left join assignments asg on asg.id=uq.assignment_id ".
        " left join result_levels rl on rl.id=uq.level_id ".
        " left join quizzes q on q.id=asg.quiz_id ".
        " where asg.quiz_type=$mode and uq.user_id=$user_id order by uq.added_date desc";      
        return $sql;
        //return db::exec_sql($sql);
    }
    
        
    public static function GetUserQuizById($user_quiz_id,$access)
    {
        $add = $access == false ? au::get_where() : "";
        $sql ="select a.*,uq.*,au.*,uq.id as user_quiz_id,a.status as asg_status,uq.status as uq_status, a.id as asg_id , ifnull(uq.uquiz_time,a.quiz_time) as user_quiz_time , vau.* ".
        " from user_quizzes uq ".
        " left join assignments a on a.id=uq.assignment_id ".        
        " left join assignment_users au on au.assignment_id =a.id and au.user_id=uq.user_id ".
        " left join v_all_users vau on vau.UserID=au.user_id ".
        " where uq.id = $user_quiz_id ".$add;
        
        return db::exec_sql($sql);
    }

    public static function GetAsgById($asg_id)
    {
        $sql="select c.cat_name,c2.cat_name as asg_cat_name, q.quiz_name,a.*, vau.Name,vau.Surname ,r.description as asg_rate,r2.description as qst_rate ".
        " from assignments a ".
        " left join ratings r on r.id = a.asg_rate_id left join ratings r2 on r2.id = a.qst_rate_id ".
        " left join quizzes q on a.org_quiz_id=q.id ".
        " left join cats c on c.id=q.cat_id  left join cats c2 on c2.id=a.asg_cat_id  ".
        " left join v_all_users vau on vau.UserID=a.inserted_by ".
        " where a.id=$asg_id ";   
    
        return db::exec_sql($sql);
    }


    public static function GetUserResultsQuery($asg_id,$user_type,$orderby=" ua.added_date asc ")
    {
      $table_name = "users";      
      if($user_type=="2") $table_name="v_imported_users";
      if($user_type=="3" || $user_type=="4")  $table_name="app_users";           
      
      $sql = "select asg.id,asg.assignment_name,u.user_id,Name,".
                "asg.cert_enabled,".
                "Surname, ".
                "UserName, ".
                "user_photo,UserID,av.variant_name,".
                "ifnull(ua.status,0) as status_id, ".
                "(case ifnull(ua.status,0) ".
                "when 0 then 'Not started' when 1 then 'Started' when 2 then 'Finished' ".
                "when 3 then 'Time ended' when 4 then 'Manually stopped' ".
                "    end ) as status_name, ".
                "ua.pass_score_point, ".
                "ua.pass_score_perc, ".
                "(CASE quiz_type when 2 then 'Not enabled' ELSE (case success when 1 then 'Yes' else 'No' end) end) is_success, ".
                "(CASE quiz_type when 1 then (case results_mode when 1 THEN pass_score_point else pass_score_perc end) else '' end) total_point, ".
                "ua.id as user_quiz_id, ".
                "mu.user_id as start_sent, mu2.user_id as results_sent,level_name ".
            "from assignment_users u	 ".
            "left join $table_name lu on lu.UserID = u.user_id ".
            "left join answer_variants av on av.id=u.variant_id ".
            "left join mailed_users mu on mu.user_id = u.user_id and mu.assignment_id=u.assignment_id and mu.user_type=$user_type and mu.mail_type=1 and mu.arch=0 ".
            "left join user_quizzes ua on ua.user_id = lu.UserID and ua.assignment_id = u.assignment_id ".
            "left join result_levels rl on rl.id=ua.level_id ".
            "left join mailed_users mu2 on mu2.user_id = u.user_id and mu2.assignment_id=u.assignment_id and mu2.user_type=$user_type and mu2.mail_type=2 and mu2.user_quiz_id=ua.id and mu2.arch=0 ".
            "left join assignments asg on asg.id=u.assignment_id ".
            "where u.assignment_id=$asg_id and u.user_type=$user_type  order by $orderby " ;      
     
        return $sql;
    }

     public static function UpdateUserQuiz($user_quiz_id,$status,$date,$update_status=true)
     {

        db::exec_sql("delete from assignment_subject_results where user_quiz_id=$user_quiz_id");
        db::exec_sql("CALL p_calc_subject_results(\"$user_quiz_id\");");
         
        $quiz_res = db::exec_sql("CALL p_calc_quiz_results(\"$user_quiz_id\");");
        $row = db::fetch($quiz_res);
        
        $total_point = $row['calc_mode']=="2" ? $row['total_apoint'] : $row['total_point'] ;
        
        $check_point = $row['results_mode']=="1"  ? $total_point : $row['total_perc'];
        
        $temp_res = db::exec_sql(res_temp::GetTemplateByPoint($row['results_template_id'], $check_point));
        
        $temp_row = db::fetch($temp_res);
        
        $level_id = $temp_row["level_id_u"];
        
        if($row['quiz_success']=="1") $level_id = $temp_row["level_id_s"];
        
        if($temp_row["level_id_f"]!="") $level_id = $temp_row["level_id_f"];     
        
        $update_arr = array("success"=>$row['quiz_success'],
                                                "status"=>$status,
                                                "finish_date"=>$date,
                                                "pass_score_point"=>$total_point,
                                                "pass_score_perc"=>$row['total_perc'],
                                                "level_id"=>$level_id
                                                );
        
        if($update_status==false)
        {
            unset($update_arr['finish_date']);
            unset($update_arr['status']);
        }
        
        $query = orm::GetUpdateQuery("user_quizzes",
                                          $update_arr,
                                          array("id"=>$user_quiz_id));
              
        
        db::exec_sql($query);

        return array($row,$temp_row);
    }
    
    public static function UpdateQstPointQuery($user_quiz_id,$question_id,$asg_answer_mode=1,$set_true=0)
    {
        $calc = 2;
        if(POINT_CALCULATION=="PARTLY") $calc =1 ; // 1 partly , 2 complete
        $sql = "CALL p_insert_question_point($calc,\"$user_quiz_id\",\"$question_id\",$asg_answer_mode,$set_true);";                
        return $sql ;
    }
    
    public static function GetUserQuizzesIn($in)
    {
        $sql = "select * from user_quizzes where id in ($in)";
        return $sql;
    }
    
    public static function GetQuestionPointQuery($user_quiz_id,$question_id)
    {
        $sql = "select question_point,question_percent,if(total_point<0, 0 ,total_point) total_point, if(total_percent<0, 0 ,total_percent) total_percent,if(total_percent<0, penalty_point ,0) penalty_point,question_apoint from assignment_question_points where user_quiz_id=$user_quiz_id and question_id=$question_id";        
        return db::exec_sql($sql);
    }

    public static function GetUserInfoByAsgId($asg_id,$user_id,$user_type,$user_quiz_id)
    {
	$users ="users";
	$user_quiz_where = "";
	if($user_type=="2") $users ="v_imported_users";
        else if($user_type=="3" || $user_type=="4") $users ="app_users";
	if($user_quiz_id!="") $user_quiz_where = " and uq.id=$user_quiz_id ";
	$query = "select usr.*,q.*,asg.*,uq.id as user_quiz_id , uq.added_date as uq_added_date,rl.level_name,". 
                 " uq.added_date,uq.finish_date,pass_score_point,pass_score_perc,uq.success, ". 
                 " au.user_type, asg.id as asg_id ".
                 " from  $users usr, assignments asg ".
                 " inner join assignment_users au on au.assignment_id=asg.id and au.user_id=$user_id ".
                 " left join variant_quizzes vq on vq.asg_id=$asg_id and vq.variant_id=au.variant_id ".
                 " left join user_quizzes uq on uq.assignment_id=asg.id and uq.user_id = $user_id $user_quiz_where ". 
                 " left join result_levels rl on rl.id=uq.level_id ".
                 " left join quizzes q on q.id=if(asg.is_random=2, vq.quiz_id,asg.quiz_id) ".
                 " where usr.UserID=$user_id and asg.id=$asg_id ";

	return db::GetResultsAsArray($query);
    }

    public static function AcceptNewUser($user_id,$branch_id, $exec=true)
    {
        $branch_id = db::clear($branch_id);
	$sql = "insert into assignment_users  (assignment_id,user_type,user_id,variant_id)".
	" select a.id, 1, $user_id , if(a.is_random=2, (SELECT variant_id FROM variant_quizzes vq WHERE asg_id=a.id ORDER BY RAND($user_id) LIMIT 0 ,1),0) from assignments a".
	" where a.accept_new_users = 1 and a.status in (0,1) and a.branch_id=$branch_id ";
   
	if($exec==true) db::exec_sql($sql);
        else return $sql;
     
    }
    
    public static function AcceptFacebookUser($user_id,$branch_id)
    {
        $sql = "INSERT INTO assignment_users (assignment_id,user_type,user_id,variant_id,already_checked)
                SELECT a.id,3,$user_id, 
                 if(a.is_random=2, (SELECT variant_id FROM variant_quizzes vq WHERE asg_id=a.id ORDER BY RAND($user_id) LIMIT 0 ,1),0),1
                from assignments a
                WHERE a.status in (0,1) and a.branch_id=$branch_id
                AND a.fb_users_list<>0 
                AND a.id NOT in (SELECT assignment_id FROM assignment_users WHERE user_type=3 AND assignment_id=a.id AND user_id=$user_id AND already_checked=1)
                AND (a.fb_users_list <> 2 OR a.id IN (SELECT assignment_id FROM assignment_users WHERE user_type=3 AND assignment_id=a.id AND user_id=$user_id) )";
        
        
        db::exec_sql($sql);
    }
    
    public static function AcceptLDAPUser($user_id,$branch_id)
    {
        //if( a.ldap_users_list=1, 
        $sql = "INSERT INTO assignment_users (assignment_id,user_type,user_id,variant_id,already_checked)
                SELECT a.id,4,$user_id, 
                 if(a.is_random=2, (SELECT variant_id FROM variant_quizzes vq WHERE asg_id=a.id ORDER BY RAND($user_id) LIMIT 0 ,1),0),1
                from assignments a
                WHERE a.status in (0,1) and a.branch_id=$branch_id
                AND a.ldap_users_list<>0 
                AND a.id NOT in (SELECT assignment_id FROM assignment_users WHERE user_type=4 AND assignment_id=a.id AND user_id=$user_id AND already_checked=1)
                AND (a.ldap_users_list <> 2 OR a.id IN (SELECT assignment_id FROM assignment_users WHERE user_type=4 AND assignment_id=a.id AND user_id=$user_id) )";
        
        
        db::exec_sql($sql);
    }
    
    
    public static function GetAssignmentAppUsersQuery($app_id,$asg_id,$user_type=3)
    {
        $sql ="select email from assignment_users u left join app_users au on u.user_id=au.UserID where au.app_id=$app_id and u.user_type=$user_type and u.assignment_id=$asg_id";
        return $sql;
    }
    
    public static function GetVariantQuizzes($asg_id)
    {
        $sql = "SELECT av.variant_name, vq.* FROM variant_quizzes vq 
        INNER JOIN answer_variants av ON vq.variant_id = av.id
        where vq.asg_id=$asg_id
        ";
        return $sql;        
    }
    
    public static function GetPausedTime($asg_id,$uq_id)
    {
        $sql = "SELECT  ( CASE when uq.id IS NULL OR uq.added_date>tblp.maxpdate THEN 0 else IFNULL(ROUND(SUM( ( CASE ap.pause_type when 0 then TO_SECONDS(ap.pause_date) ELSE (TO_SECONDS(ap.pause_date) * -1) END ) ) / 60, 0),0) END) as totalmin
                FROM assignment_pauses ap
                LEFT JOIN user_quizzes uq ON uq.assignment_id = ap.assignment_id and uq.id=$uq_id
                LEFT JOIN (SELECT MAX(pause_date) AS maxpdate,assignment_id FROM assignment_pauses WHERE assignment_id=$asg_id) tblp on tblp.assignment_id= ap.assignment_id
                WHERE ap.assignment_id = $asg_id and uq.id=$uq_id AND ap.pause_date>uq.added_date";
        
        return $sql;
    }
    
    public static function GetAsgSubjects($asg_id,$where = "")
    {
        $sql = "select ab.*, p.pres_name,p.pres_desc,p.pres_text,s.quiz_name as subject_name,s.quiz_desc from assignment_subjects ab inner join quizzes s on ab.subject_id=s.id and s.parent_id=0 left join pres p on p.id=ab.pres_id where ab.asg_id=$asg_id $where order by ab.id";   
        return $sql;
    }
    
    public static function GetUsersSubjectResults($user_quiz_id)
    {
        $sql = " select asr.*, s.subject_name from assignment_subject_results asr inner join subjects s on asr.subject_id=s.id where user_quiz_id=$user_quiz_id";
        return $sql;
    }
    
    public static function GetUsersQuizResults($user_quiz_id)
    {
        $sql = " select asr.*, q.quiz_name as subject_name from assignment_subject_results asr inner join quizzes q on asr.quiz_id=q.id where user_quiz_id=$user_quiz_id";
        return $sql;
    }
    
    public static function GetQuestionsSubjects($asg_is_random,$asg_id,$quiz_id,$asg_user_id)
    {
        $sql_and = "";
        if($asg_is_random=="2")
        {
            $sql_and.=" (select distinct (subject_id) from questions where quiz_id in (select quiz_id from variant_quizzes where asg_id = $asg_id) )  ";
        }
        else if($asg_is_random=="3")
        {
            $sql_and.=" (select distinct (subject_id) from questions where quiz_id in (select u_quiz_id from assignment_users where assignment_id = $asg_id and user_id=$asg_user_id) )  ";
        }
        else
        {
            $sql_and.="  (SELECT DISTINCT(subject_id) FROM questions WHERE quiz_id = $quiz_id) ";
        }
        
        $sql =  "select s.id as subject_id,subject_name from subjects s 
                        left join assignment_subjects ab on ab.subject_id=s.id AND ab.asg_id=$asg_id
                        left join pres p on p.id=ab.pres_id                       
                        inner join $sql_and q on q.subject_id=s.id
                        ";
        
        return $sql;
        
    }
    
    public static function GetQuestionQuizzes($asg_id)
    {
        $sql = " select q.id as subject_id, q.quiz_name as subject_name
                 from quizzes q inner join asg_qbank_quizzes abq on q.id=abq.quiz_id 
                 where abq.asg_id=$asg_id and q.parent_id=0 order by abq.view_priority
                ";

        return $sql;
        
    }
    
    public static function ClearSubjectResults($asg_id)
    {
        $sql = "DELETE from  assignment_subject_results WHERE user_quiz_id in (SELECT id FROM user_quizzes WHERE assignment_id=$asg_id)";
        db::exec_sql($sql);
    }
    
    public static function MarkQstForReview($user_quiz_id,$qst_id,$review_type)
    {
        $sql = orm::GetInsertQuery("user_quiz_qst_reviews", array("user_quiz_id"=>$user_quiz_id,"qst_id"=>$qst_id,"review_type"=>$review_type,"added_date"=>util::Now()));
        
        if($review_type==0 && LOG_QUESTION_VIEW=="yes")
        {
            db::exec_sql($sql);
        }
        else if($review_type==1)
        {
            orm::Delete("user_quiz_qst_reviews", array("user_quiz_id"=>$user_quiz_id,"qst_id"=>$qst_id,"review_type"=>$review_type));
            db::exec_sql($sql);
        }
    }
    
    public static function GetAsgQuizzes($asg_id)
    {
        $sql = " select q.*,view_priority from asg_qbank_quizzes aqq inner join quizzes q on aqq.quiz_id=q.id and q.parent_id=0 where aqq.asg_id=$asg_id  ";
   
        return $sql;
    }
    
    public static function GetAsgThemes($asg_id)
    {
        $sql = "select subject_name,atx.quiz_id,theme_id from assignment_themes_xreff atx
                LEFT JOIN subjects s ON s.id=atx.theme_id where atx.asg_id=$asg_id";
        return $sql;
    }
        
 
}
?>
