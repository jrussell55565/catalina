DELIMITER $$

CREATE  PROCEDURE `move_question`(
v_direction varchar(4),
v_question_id int
)
BEGIN
declare id_up int;
declare priority_up int;
declare id_down int     ;
declare priority_down int;
declare priority_current int;
declare v_quiz_id int;
select priority,quiz_id into priority_current,v_quiz_id from questions where id=v_question_id and parent_id=0;
select id,priority into id_up,priority_up from questions where quiz_id=v_quiz_id and priority<priority_current and parent_id=0 order by priority desc limit 0,1;
select id,priority into id_down,priority_down from questions where quiz_id=v_quiz_id and priority>priority_current and parent_id=0 order by priority limit 0,1;
if v_direction = 'up' then
    if priority_up is not null then
update questions set priority=priority_up where id=v_question_id and parent_id=0;
update questions set priority=priority_current where id=id_up and parent_id=0;
    end if;
ELSEIF  v_direction = 'down' then
    if priority_down is not null then
update questions set priority=priority_down where id=v_question_id and parent_id=0;
update questions set priority=priority_current where id=id_down and parent_id=0;
    end if;
end if ;
END$$

CREATE PROCEDURE p_calc_quiz_results(IN v_user_quiz_id int)
SELECT
    ROUND(IF(total_point < 0, 0, ar.total_point)  * a.point_koe,2) as total_point,
    total_perc,
    ROUND(total_apoint * a.point_koe,2) as total_apoint,
    user_quiz_id,
    results_mode,
    show_results,
    pass_score,
    a.calc_mode,
    a.results_template_id,
    IF(a.fail_sbj_exam = 1 AND success_sbj_count <> subject_count, 0, (CASE results_mode WHEN 1 THEN (CASE WHEN IF(a.calc_mode = 2, total_apoint, total_point) * a.point_koe >= pass_score THEN 1 ELSE 0
      END) WHEN 2 THEN (CASE WHEN total_perc >= pass_score THEN 1 ELSE 0
      END)
    END)) AS quiz_success
  FROM (SELECT
      SUM(subject_point) AS total_point,
      SUM(subject_percent) AS total_perc,
      SUM(subject_apoint) AS total_apoint,
      SUM(subject_success) AS success_sbj_count,
      COUNT(*) AS subject_count,
      user_quiz_id
    FROM assignment_subject_results abr
    WHERE abr.user_quiz_id = v_user_quiz_id
    GROUP BY user_quiz_id) ar
    INNER JOIN user_quizzes uq
      ON uq.id = ar.user_quiz_id
    INNER JOIN assignments a
      ON a.id = uq.assignment_id$$


CREATE  PROCEDURE p_insert_question_point(IN CALC_MODE int, IN v_user_quiz_id int,
  IN v_question_id int , IN ANS_CALC_MODE int, IN SET_TRUE int
 -- IN question_point decimal(18,2),
 -- IN total_point decimal(18,2),
 -- IN question_percent decimal(18,2),
 -- IN total_percent decimal(18,2),
 -- IN penalty_point decimal(18,2)  
)
INSERT INTO assignment_question_points (user_quiz_id,question_id,question_point,total_point,question_percent,total_percent,penalty_point,question_apoint, is_true)

SELECT user_quiz_id,
       question_id,
       question_point,
       if(SET_TRUE=1,question_point,q_total),
       max_perc,
       if(SET_TRUE=1,q_perc,max_perc),penalty_point,
       if(SET_TRUE=1,answer_point,question_apoint),
       SET_TRUE
FROM (
SELECT user_quiz_id ,
       question_id,
      ( CASE asg_calc_mode WHEN 3 THEN diff_point WHEN 2 THEN answer_point else  point end  ) question_point ,
       q_total,max_perc,q_perc,
       (case calc_pen when 1 THEN  penalty_point WHEN 2 THEN pen_point else 0 end ) penalty_point,
       ( 
          CASE ANS_CALC_MODE WHEN 1  THEN 
            CASE WHEN q_total>0 THEN answer_point ELSE 0 END
          ELSE 
           answer_point
          END
       ) question_apoint,SET_TRUE, answer_point
FROM (
select user_quiz_id, 	   
	   question_id,diff_point,pen_point,calc_pen,
	   point ,	   
     (CASE when CALC_MODE = 1 THEN       
	   (point / (case when question_type_id in (0,1) then (case ca_count when 0 then 1 else ca_count end) else answer_count end ) ) * SUM(correct_answers_count) 
     ELSE
          CASE WHEN (case when question_type_id in (0,1) then ca_count ELSE answer_count END ) = SUM(correct_answers_count) THEN ( CASE asg_calc_mode WHEN 3 THEN diff_point else  point end  ) ELSE -1 END
     END)
     as q_total ,
     (100.00/cnts.q_count) as max_perc,
     (CASE when CALC_MODE = 1 THEN 
	   ((100.00/cnts.q_count)/(case when question_type_id in (0,1) then (case ca_count when 0 then 1 else ca_count end) else answer_count end ) ) * SUM(correct_answers_count) 
     ELSE 
        CASE WHEN (case when question_type_id in (0,1) then ca_count ELSE answer_count END ) = SUM(correct_answers_count) THEN (100.00/cnts.q_count) ELSE -1 END
     END )
      as q_perc,     
      penalty_point , asg_calc_mode,
     SUM(q_answer_point) AS answer_point      
	   from (
select (
			case when q.question_type_id in (0,1) then
				case when a.correct_answer=1 then
					1
				else -1 end
				when q.question_type_id in (3,4) then
				case when uq.user_answer_text='' then 0 
        when uq.user_answer_text = a.correct_answer_text then
					1
				else -1 end
			end
		) correct_answers_count , 
		q.point,
		uq.question_id,	
		q.question_type_id,
		q.quiz_id,
		uq.user_quiz_id ,
    coalesce(adlx.diff_point,q.point) diff_point,
    q.penalty_point,
    (
			case when q.question_type_id in (0,1) then
				case when a.correct_answer=1 then
					answer_point
				else (CASE ANS_CALC_MODE WHEN 1 THEN 0 ELSE answer_point END) end
				when q.question_type_id in (3,4) then
				case when uq.user_answer_text='' then 0 
        when uq.user_answer_text = a.correct_answer_text then
					IF(q.question_type_id = 3, q.point ,answer_point)
				else (CASE ANS_CALC_MODE WHEN 1 THEN 0 ELSE IF(q.question_type_id = 3, q.point ,answer_point) END) end
			end
		) q_answer_point,
    asg.calc_mode as asg_calc_mode ,adlx.pen_point ,calc_pen
from user_answers uq
left join answers a on a.id = uq.answer_id
left join questions q on q.id = uq.question_id
INNER JOIN user_quizzes uq1 ON uq1.id=v_user_quiz_id 
inner join assignments asg ON asg.id = uq1.assignment_id
INNER JOIN v_all_users u on u.UserID=uq1.user_id
INNER JOIN v_user_groups vug ON vug.id = u.group_id
LEFT JOIN qst_diff_xreff qcx ON qcx.qst_id=q.id AND qcx.course_id= vug.course
LEFT JOIN assignment_diff_level_xreff adlx ON adlx.asg_id = uq1.assignment_id AND adlx.diff_id = IFNULL(qcx.diff_id,q.diff_level_id) AND adlx.quiz_id=q.parent_quiz_id

WHERE q.id = v_question_id AND uq.user_quiz_id=v_user_quiz_id
) total 
left join (

				select count(*) answer_count,                             
                SUM(correct_answer) ca_count,
               qs.id 
        from answers av
				left join question_groups qg on qg.id = av.group_id
			  left join questions qs on qs.id=qg.question_id
			  where av.control_type=1 AND qs.id=v_question_id
			  group by qs.id
	
			) qst
on qst.id = total.question_id
left join (
				select COUNT(*) q_count,quiz_id from questions 	qs2			
       -- WHERE qs2.id=v_question_id
			    group by quiz_id 
			)	cnts on cnts.quiz_id = total.quiz_id
group by question_id ,
		 qst.answer_count,
		 cnts.q_count,
		 point ,question_type_id ,qst.ca_count,
		 user_quiz_id,
     penalty_point
 ) t1

) tres$$
 
CREATE PROCEDURE p_calc_subject_results(IN v_user_quiz_id int)
INSERT INTO assignment_subject_results (user_quiz_id , subject_id,subject_point,subject_percent, subject_apoint, subject_success,quiz_id)                                               
SELECT v_user_quiz_id,
       NULL AS subject_id,
       GREATEST(IFNULL(IF(total_point<0, 0 ,ar.total_point),0),0) total_point,
       GREATEST(IFNULL(total_perc,0),0),GREATEST(IFNULL(total_apoint,0),0) ,       
	     
	    IFNULL((case results_mode when 1 then ( case when GREATEST(IFNULL(IF(a.calc_mode=1,total_point,total_apoint),0),0) >= IFNULL(min_subject_point,0) then 1 else 0 end) 
			when 2 then ( case when GREATEST(IFNULL(total_perc,0),0) >= IFNULL(min_subject_point,0) then 1 else 0 end) end ),0) as subject_success
      ,aqb.quiz_id
FROM asg_qbank_quizzes aqb
LEFT JOIN 
(
    SELECT  ifnull(round(sum((case when aqp.total_point < 0 then (aqp.penalty_point*-1) else aqp.total_point end)),2),0) as total_point ,
  	      ifnull(round(sum((case when aqp.total_percent < 0 then 0 else total_percent end)),1),0) as total_perc ,	
          ifnull(round(sum((case when aqp.question_apoint <= 0 then (aqp.penalty_point*-1) else aqp.question_apoint end)),2),0) as total_apoint ,    
  	      uq.id as user_quiz_id	, q.parent_quiz_id , uq.assignment_id  
    FROM assignment_question_points aqp  
    LEFT JOIN questions q ON q.id=aqp.question_id
    RIGHT JOIN user_quizzes uq ON uq.id=aqp.user_quiz_id
    WHERE uq.id=v_user_quiz_id
    GROUP BY uq.id, q.parent_quiz_id, uq.assignment_id
 ) 
 ar ON ar.parent_quiz_id = aqb.quiz_id
RIGHT JOIN user_quizzes uq1 ON uq1.id=v_user_quiz_id
LEFT JOIN v_all_users al ON al.UserID = uq1.user_id
LEFT JOIN assignments a ON a.id = uq1.assignment_id
LEFT JOIN assignment_subjects asb on asb.subject_id =aqb.quiz_id AND asb.asg_id = a.id
inner join assignment_users au on au.assignment_id=a.id and au.user_id=uq1.user_id AND au.user_type=al.user_type
WHERE aqb.asg_id=a.id$$

DELIMITER ;