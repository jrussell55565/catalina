CREATE PROCEDURE `quiz_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20))
  BEGIN

SELECT cu.username,cu.employee_id,cu.status,q.user_id,q.assignment_id,MAX(q.pass_score_point) AS max_score
      FROM assignments.user_quizzes q, users cu, assignments.users au
      WHERE cu.employee_id = au.comments
      AND au.userid = q.user_id
      AND q.added_date BETWEEN str_to_date(v_date_start,'%Y-%m-%d') AND str_to_date(v_date_end,'%Y-%m-%d')
      GROUP BY username, employee_id, user_id, status, assignment_id
      ORDER BY max_score DESC;
