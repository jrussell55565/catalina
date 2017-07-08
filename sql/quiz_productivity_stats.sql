CREATE PROCEDURE `quiz_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20), v_print BOOLEAN)
  BEGIN

    DROP TEMPORARY TABLE IF EXISTS quiz_productivity_tmp;
    CREATE TEMPORARY TABLE quiz_productivity_tmp (
      `username`      VARCHAR(50) NOT NULL,
      `employee_id`   VARCHAR(50) NOT NULL,
      `status`        VARCHAR(24) NOT NULL,
      `user_id`       VARCHAR(50) NOT NULL,
      `assignment_id` VARCHAR(50) NOT NULL,
      `max_score`     DOUBLE      NOT NULL
    );
    INSERT INTO quiz_productivity_tmp (
      SELECT
        cu.username,
        cu.employee_id,
        cu.status,
        q.user_id,
        q.assignment_id,
        MAX(q.pass_score_point) AS max_score
      FROM assignments.user_quizzes q, users cu, assignments.users au
      WHERE cu.employee_id = au.comments
            AND au.userid = q.user_id
            AND q.added_date BETWEEN str_to_date(v_date_start, '%Y-%m-%d') AND str_to_date(v_date_end, '%Y-%m-%d')
      GROUP BY username, employee_id, user_id, status, assignment_id
      ORDER BY max_score DESC);

    if v_print is TRUE THEN
    SELECT *
    FROM quiz_productivity_tmp;
    END IF ;

  END;

