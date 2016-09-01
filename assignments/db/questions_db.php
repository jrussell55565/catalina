<?php


class questions_db {


    public static function GetQuestionsQuery($quiz_id,$oderby="priority asc")
    {
        $where = " where is_arch=0 and quiz_id=$quiz_id ".au::get_where(true,"q");
     
        $sql = "select q.*, qt.question_type, qz.quiz_name,'' as group_name, '' as next_priority  from questions q left join quizzes qz on qz.id=q.quiz_id left join question_types qt on q.question_type_id=qt.id $where order by $oderby";
        
        return $sql;
    }   
    
    public static function GetQuestionsBankQuery($where,$orderby="id desc")
    {
        $sql = "select q.*, qt.question_type, qz.quiz_name from questions q left join quizzes qz on qz.id=q.quiz_id left join question_types qt on q.question_type_id=qt.id where q.parent_id=0 $where [{where}] ".au::get_where(true, "q")." order by $orderby";             
        return $sql;
    }  

    public static function GetQuestionsByPriority($priority,$asg_id,$user_id,$qst_order,$quiz_id,$user_quiz_id,$asg_is_random=0,$asg_quiz_id=0, $variant_quiz_id=0,$u_quiz_id=0)
    {
        $u_quiz_id = $u_quiz_id=="" ? 0 : $u_quiz_id;
        $priority = db::clear($priority);
        $table = questions_db::GetQstPriorityTable($asg_id,$asg_is_random,$u_quiz_id,$asg_quiz_id,$variant_quiz_id);        
        if($qst_order=="2")
        {
            $table = questions_db::GetQstTable($asg_id,$quiz_id, $user_id ,$user_quiz_id,$asg_is_random,$asg_quiz_id,$variant_quiz_id,$u_quiz_id);
        }
        $sql = "select qs.* , qg.group_name, asg.paused, ".
        "ifnull((select priority from ".questions_db::ConfigQuery($table, 1)." qs2 where qs2.priority>qs.priority and qs2.quiz_id=qs.quiz_id order by priority limit 0,1),-1) next_priority,".
        "(select priority from ".questions_db::ConfigQuery($table, 3)."  qs3 where qs3.priority<".
        " qs.priority and qs3.quiz_id=qs.quiz_id order by priority desc limit 0,1) prev_priority".
        " from ".questions_db::ConfigQuery($table, 2)."   qs ".
        " left join quizzes q on q.id=qs.quiz_id ".
        " left join assignments asg on asg.id=$asg_id ".
        " inner join assignment_users au on au.assignment_id=asg.id and au.user_id=$user_id ".
        " left join variant_quizzes vq on vq.asg_id=$asg_id and vq.variant_id=au.variant_id ".
        " left join question_groups qg on qg.question_id=qs.id ".
        " where asg.id=$asg_id ".                    
        " and (case asg.is_random when 1 then asg.quiz_id when 3 then $u_quiz_id else vq.quiz_id end) = q.id ".   
        " and qs.priority=$priority ";  
    
        return $sql;
    }
    
    public static function GetQstTable($asg_id,$quiz_id,$user_id,$user_quiz_id,$asg_is_random=0,$asg_quiz_id=0, $variant_quiz_id=0,$u_quiz_id=0)
    {
        $variant_quiz_id= $variant_quiz_id=="" ? 0 : $variant_quiz_id;
        $u_quiz_id = $u_quiz_id=="" ? 0 : $u_quiz_id;
//        return "(select @row := @row + 1 AS priority, q2.* from ( select id,question_text,question_type_id,quiz_id,point,added_date,parent_id,header_text,footer_text,video_file, success_msg,psuccess_msg,unsuccess_msg,subject_id from questions   q where quiz_id=if($asg_is_random=2, $variant_quiz_id,$asg_quiz_id) order by rand($user_quiz_id) ) q2 ,  (SELECT @row := 0) r)";
        
        $query = "(select @row := @row + 1 AS priority, q2.* from 
                     ( select q.id,q.question_text,
                              q.question_type_id,q.quiz_id,
                              q.point,q.added_date,q.parent_id,
                              q.header_text,q.footer_text,
                              q.video_file, q.success_msg,
                              q.psuccess_msg,q.unsuccess_msg,
                              q.subject_id,q.parent_quiz_id as parent_quiz_id ,
                              abq1.view_priority
                      from questions q                       
                      left join asg_qbank_quizzes abq1 on abq1.quiz_id=q.parent_quiz_id and abq1.asg_id=$asg_id
                      where q.quiz_id=(case $asg_is_random when 1 then $asg_quiz_id when 3 then $u_quiz_id else $variant_quiz_id end) "
                . "order by abq1.view_priority,rand($user_quiz_id) ) q2 ,  (SELECT @row := 0) r)";
     
        return $query;
    }
    
    public static function ConfigQuery($query,$id)
    {
        return str_replace("@row", "@row".$id, $query);
    }

    public static function GetQuestionsByID($ID)
    {
        $sql = "select qs.* , qg.group_name, ".
        "ifnull((select priority from questions qs2 where qs2.priority>qs.priority and qs2.quiz_id=q.id order by priority limit 0,1),-1) next_priority,".
        "(select priority from questions qs3 where qs3.priority<qs.priority and qs3.quiz_id=q.id order by priority desc limit 0,1) prev_priority".
        " from questions qs ".
        " left join quizzes q on q.id=qs.quiz_id ".         
        " left join question_groups qg on qg.question_id=qs.id ".        
        " where qs.id=$ID";
        return $sql;
    }

    public static function GetQuestionsByUserQuizId($user_quiz_id,$where = "",$qst_order="1")
    {
        $order_priority = $qst_order == "1" ? " qs.priority " : " rand($user_quiz_id) ";
        $sql = "select qs.* , qg.group_name, '-1' as next_priority, '-1' as prev_priority, aqp.question_point,aqp.question_apoint,asg.ans_calc_mode,".
               " aqp.question_percent,if(aqp.total_point<0, 0 ,aqp.total_point) total_point, if(aqp.total_percent<0, 0 ,aqp.total_percent) total_percent,if(aqp.total_percent<0, aqp.penalty_point ,0) asg_penalty_point ".
               " from user_quizzes uq ".
               " inner join assignments asg on asg.id=uq.assignment_id ".
               " inner join assignment_users au on au.user_id=uq.user_id and au.assignment_id=asg.id ". 
               " left join variant_quizzes vq on vq.asg_id=asg.id and vq.variant_id=au.variant_id ".
               " inner join questions qs on qs.quiz_id=(case asg.is_random when 1 then asg.quiz_id when 3 then au.u_quiz_id else vq.quiz_id end) ".
               " left join assignment_question_points aqp on aqp.question_id=qs.id and aqp.user_quiz_id=$user_quiz_id ".
               " inner join question_groups qg on qg.question_id=qs.id ".
               " left join asg_qbank_quizzes abq1 on abq1.quiz_id=qs.parent_quiz_id and abq1.asg_id=uq.assignment_id ".
               " where uq.id=$user_quiz_id $where order by abq1.view_priority , $order_priority  ";
      
        return db::exec_sql($sql);
    }

    public static function MoveQuestion($direction,$question_id)
    {
        $sql = "CALL move_question(\"$direction\", $question_id);";
        db::exec_sql($sql);
    }

    public static function GetAnswerDeleteQuery($question_id)
    {
        $sql = "delete from answers where group_id in (select id from question_groups where question_id=$question_id  )";
        return $sql;
    }
    
      public static function GetAnswerDeleteQuery2($group_id,$priority)
    {
        $sql = "delete from answers where group_id =$group_id and priority >$priority ";
        return $sql;
    }

    public static function GetAnswersByQstID($question_id)
    {
        $sql = "select a.id as a_id,a.*,qg.* from answers a ".
        " left join question_groups qg on a.group_id=qg.id ".
        " where qg.question_id = $question_id order by priority";
        //echo $sql;
        return db::exec_sql($sql);
    }

    public static function GetAnswersByQstID2($question_id,$user_quiz_id,$ans_priority)
    {
        $sql = "select a.id as a_id,a.*,qg.*,ua.user_answer_id,ua.user_answer_text from answers a ".
        " left join question_groups qg on a.group_id=qg.id ".
        " left join user_answers ua on ua.answer_id=a.id and ua.user_quiz_id=".$user_quiz_id.
        " where qg.question_id = $question_id ";
        if($ans_priority=="2")
        {
            $sql.=" order by rand($user_quiz_id) ";
        }
        else
        {
            $sql.=" order by a.priority ";
        }
   
        return db::exec_sql($sql);
    }

    public static function GetQuestionsByAsgIdQuery($asg_id,$quiz_id, $user_id,$qst_order,$user_quiz_id,$asg_is_random=0,$asg_quiz_id=0, $variant_quiz_id=0,$u_quiz_id=0)
    {
        $table = questions_db::GetQstPriorityTable($asg_id,$asg_is_random,$u_quiz_id,$asg_quiz_id,$variant_quiz_id);       
        if($qst_order=="2")
        {
            $table = questions_db::GetQstTable($asg_id,$quiz_id, $user_id,$user_quiz_id,$asg_is_random,$asg_quiz_id,$variant_quiz_id,$u_quiz_id);
        }        
        $sql = "select q.*, aqp.id as aqp_id ,ifnull(uqv.id,0) as marked from assignments a ".
                " left join assignment_users au on au.user_id = $user_id and au.assignment_id=a.id ".
                " left join variant_quizzes vq on vq.variant_id = au.variant_id and vq.asg_id=a.id ".
                " left join $table q on q.quiz_id=(case a.is_random when 1 then a.quiz_id when 3 then au.u_quiz_id else vq.quiz_id end) ".
                " left join assignment_question_points aqp on aqp.user_quiz_id=$user_quiz_id and aqp.question_id=q.id ".
                " left join user_quiz_qst_reviews uqv on uqv.user_quiz_id=$user_quiz_id and uqv.review_type=1 and uqv.qst_id=q.id ".
                " where a.id=".$asg_id." order by priority";              
        //echo $sql;
        return $sql;
    }
    
    public static function GetQstPriorityTable($asg_id,$asg_is_random,$u_quiz_id,$asg_quiz_id,$variant_quiz_id)
    {
        $u_quiz_id = $u_quiz_id=="" ? 0 : $u_quiz_id;
        $variant_quiz_id = $variant_quiz_id=="" ? 0 : $variant_quiz_id;
        
        $query = " ( select @row := @row + 1 AS priority, q2.* from ( select q.id,q.question_text,
                              q.question_type_id,q.quiz_id,
                              q.point,q.added_date,q.parent_id,
                              q.header_text,q.footer_text,
                              q.video_file, q.success_msg,
                              q.psuccess_msg,q.unsuccess_msg,
                              q.subject_id,q.parent_quiz_id as parent_quiz_id                              
                      from questions q                       
                      left join asg_qbank_quizzes abq1 on abq1.quiz_id=q.parent_quiz_id and abq1.asg_id=$asg_id 
                      where q.quiz_id=(case $asg_is_random when 1 then $asg_quiz_id when 3 then $u_quiz_id else $variant_quiz_id end)
                      order by abq1.view_priority 
                      ) q2 ,  (SELECT @row := 0) r )
                 ";
        
        return $query;
    }      

    public static function GetGroupDeleteQuery($question_id)
    {
        $sql = "delete from question_groups where question_id=$question_id";
        return $sql;
    }

    public static function DeleteQuestion($question_id)
    {
        //$sql = "delete from answers where group_id in (select id from question_groups where question_id=$question_id) ;";
        //$sql.= " delete from question_groups where question_id=$question_id ;";
        $sql=" delete from questions where id=$question_id";
        
        db::exec_sql($sql);
    }
   

    public static function UpdatePriority($quiz_id,$priority)
    {
	$sql = " update questions set priority = 1 where quiz_id = $quiz_id and priority = $priority ";
	db::exec_sql($sql);
    }

    public static function GetMinPriority($quiz_id)
    {
	$sql = "select ifnull(min(priority),-1) as minp from questions where quiz_id=$quiz_id";        
	$results = db::exec_sql($sql);
	$row=db::fetch($results);
	return $row['minp'];
    }

    public static function UpdatePriorityQuery($quiz_id,$question_id)
    {
        $sql = "update questions ,(select ifnull(max(priority)+1,1) as priority from questions where quiz_id=$quiz_id) questions2 set questions.priority = questions2.priority where questions.id=$question_id";
        return $sql ; 
    }
    
    public static function GetAsgQuestions($asg_id)
    {
        $sql = " select q.*, qt.question_type, qz.quiz_name 
        from questions q  
        left join quizzes qz on qz.id=q.quiz_id 
        left join question_types qt on q.question_type_id=qt.id   
        INNER JOIN assignments a ON a.id=$asg_id 
        where q.parent_id<>0 
        AND a.id=$asg_id 
        AND q.quiz_id in ( SELECT IF(a.is_random=2 , vq.quiz_id , a.quiz_id) FROM variant_quizzes vq WHERE vq.asg_id=$asg_id ) ";
        return $sql;

    }
    
    /*
    public static function GetRandomizedQuestions($question_count,$where,$group_by_subject=true)
    {
        
        $add_sql = "@type = subject_id";
        $add_orderby ="order by sbj_id";
        if($group_by_subject==false) {
            $add_sql = "@type <> 'mytype'";
            $add_orderby="";
        }
        $sql =" SELECT * FROM (
                select
                   *,
                   @num := if($add_sql, @num + 1, 1) as row_number,
                   @type := subject_id as sbj_id
                from (select * from questions where parent_id=0 $where order by rand() ) qst 
                $add_orderby
                ) q 
                WHERE q.row_number<=$question_count ";
        //ORDER by RAND('".util::GUID()."') ";        
        return $sql;
    }
     * 
     */
    
    public static function GetRandomizedQuestions($question_count,$where,$group_by_subject=true, $randomiz_where = "", $user_id,$column_list="q.*")
    {
        
        $add_sql = " @type = quiz_id AND ";
        $add_orderby =" sbj_id, ";
        if($group_by_subject==false) {
            $add_sql = "";
            $add_orderby="";
        }       
        
        $sql = " 
                SELECT $column_list, q.question_text as q_text
                 FROM 
                  ( 
                    SELECT *, @num := if(@type = quiz_id, @num + 1, 1) as row_number, 
                    @diff_num := if($add_sql @diff_type=difflevel_id, @diff_num + 1, 1) as diff_row_number,     
                    @type := quiz_id as sbj_id , @diff_type := difflevel_id as diff_id     
                    FROM 
                    (      
                      SELECT IFNULL(qdx.diff_id,qs1.diff_level_id) as difflevel_id,qs1.*       
                      FROM 
                      (
                        SELECT *, @z_num := if(@z_type = sb_row_number, @z_num , RAND()/RAND()/RAND()/RAND() / q1.diff_level_id) as znum, @z_type:=sb_row_number              
                        FROM (
                         select *,
                                 @num2 := if(@type2 = quiz_id, @num2 + 1, 1) as row_number2, 
                                 @sb_num := if( @type2 = quiz_id AND @sb_type=subject_id, @sb_num + 1, 1) as sb_row_number,                        
                                 @sb_num2 := if( @type2 = quiz_id AND @sb_type2=subject_id, @sb_num2 + 1, 1) as sb_row_number2,   
                                 @type2 := quiz_id , @sb_type := subject_id  , @sb_type2 := subject_id 
                         from           
                         questions 
                         WHERE parent_id = 0 $where
                         ORDER by quiz_id,subject_id
                        ) q1 ORDER by q1.sb_row_number
                      )
                      qs1 

                      LEFT JOIN (SELECT vug.course FROM v_all_users u 
                      INNER JOIN v_user_groups vug ON vug.id=u.group_id where u.UserID=$user_id) u2 on 1=1 
                      LEFT JOIN qst_diff_xreff qdx ON qdx.qst_id = qs1.id 
                      AND qdx.course_id = u2.course 
                      order by rand() 
                    ) qst ORDER BY $add_orderby  diff_id , znum ) q 
                        
                    WHERE $randomiz_where 
    
            ";
                       
        // $where add into query
      
        return $sql;
    }
       
    

}
?>
