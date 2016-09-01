<?php

class reports_db {
    public static function GetQuestionsForReports($asg_id)
    {
       /*
        $sql ="select qs.* from questions qs ".
              "  LEFT join quizzes q on q.id=qs.quiz_id ".
              "  left join assignments a on a.quiz_id=q.id ".
              "  where a.id=$asg_id and question_type_id in (0,1) ".au::get_where(true,"a");
        
        */
        $sql = "  SELECT q.*, av.variant_name,a.is_random, qst_correct_count,qst_fail_count
                  FROM questions q 
                  INNER JOIN assignments a ON a.id=$asg_id
                  LEFT JOIN variant_quizzes vq1 on vq1.asg_id = a.id and q.quiz_id=vq1.quiz_id
                  LEFT JOIN answer_variants av ON av.id=vq1.variant_id
                  INNER JOIN quizzes qz ON qz.id = q.quiz_id
                  LEFT JOIN 
                  (
                      SELECT SUM((CASE WHEN aqp.total_point>0 then 1 ELSE 0 end)) AS qst_correct_count , 
                            SUM((CASE WHEN aqp.total_point>0 then 0 ELSE 1 end)) AS qst_fail_count , 
                             aqp.question_id     
                      FROM assignment_question_points aqp
                      WHERE aqp.user_quiz_id IN (SELECT id from user_quizzes uq WHERE uq.assignment_id=$asg_id)
                      GROUP by aqp.question_id
                  ) qst_ct on qst_ct.question_id = q.id
                  WHERE q.question_type_id in (0,1)
                    AND qz.id in(
                        SELECT (case a1.is_random when 1 then a1.quiz_id when 2 then vq.quiz_id else qz2.id end  )  AS id FROM assignments a1
                        LEFT JOIN variant_quizzes vq on vq.asg_id = a1.id
                        LEFT JOIN assignment_users au on au.assignment_id=a1.id
                        LEFT JOIN quizzes qz2 on qz2.id=au.u_quiz_id
                        WHERE a1.id=$asg_id
                    )
                  ORDER BY av.id";  
        
         return $sql;
    }

    public static function GetAnswersReport($question_id,$asg_id)
    {
        $sql = "select answer_text, sum( (case when ua.id is null then 0 else 1 end) ) as rate,".
              // " (select count(*) from answers where group_id=qg.id) answer_count ".
               " ( ".
               " select count(*) from user_answers ua2 ".
               "  left join user_quizzes uq2 on uq2.id=ua2.user_quiz_id ".
               "  inner join assignments asg on asg.id ".
               "  where ua2.question_id=q.id and asg.id=uq.assignment_id ".
               "  ) as acount ".
               " from answers a ".
               " left join question_groups qg on qg.id=a.group_id ".
               " left join questions q on q.id=qg.question_id ".
               "  left join user_quizzes uq on uq.assignment_id=$asg_id ".
               "  left join user_answers ua on ua.user_quiz_id=uq.id and ua.user_answer_id=a.id ".
               "  where q.id=$question_id ".
               "  group by answer_text order by a.priority " ;
      //echo $sql."<br>";
     
        return $sql;
    }
    
    public static function GetReportsByRoleID($where = "")
    {
        $sql = "SELECT mr.report_id FROM modules m 
                INNER JOIN module_reports mr ON mr.module_id = m.id
                INNER JOIN role_reports rr ON rr.report_id = mr.report_id                
                $where order by mr.priority,m.id";
        return $sql;
    }
    
    public static function GetReportList($role_id)
    {
        $sql = "SELECT r.id,r.report_name, rr.id AS has_access
                FROM reports r 
                LEFT JOIN role_reports rr ON rr.report_id=r.id and rr.role_id=$role_id
                ORDER BY r.id ";
        return $sql;
    }
    
}
?>