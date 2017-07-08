CREATE PROCEDURE `task_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20), v_print BOOLEAN)
  BEGIN

    DROP TEMPORARY TABLE IF EXISTS task_productivity_tmp;
    CREATE TEMPORARY TABLE task_productivity_tmp (
      `emp_id`                  VARCHAR(50),
      `tasks_completed_by_user` DOUBLE,
      `tasks_all_user`          DOUBLE,
      `category`                VARCHAR(50),
      `passed_quizzes`          DOUBLE,
      `all_quizzes`             DOUBLE,
      `days_worked`             DOUBLE,
      `miles`                   DOUBLE,
      `idle_time`               DOUBLE,
      `aprox_idle_costs`        DOUBLE,
      `days_worked_points`      DOUBLE,
      `miles_points`            DOUBLE,
      `task_points`             DOUBLE,
      `quiz_points`             DOUBLE,
      `idle_time_points`        DOUBLE,
      `days_shoulda_worked`     DOUBLE,
      `employee_id`             VARCHAR(50),
      `status`                  VARCHAR(50),
      `activity_total_points`   DOUBLE,
      `activity_max_points`     DOUBLE,
      `real_name`               VARCHAR(50),
      `total_percent`           DOUBLE
    );
    INSERT INTO task_productivity_tmp (
      SELECT
        mo_data.*,
        coalesce(round((activity_total_points / activity_max_points) * 100, 0), 0) AS total_percent
      FROM
        (
          SELECT
            a.*,
            users.employee_id,
            users.status,
            coalesce(
                days_worked_points + miles_points + task_points + quiz_points + idle_time_points,
                0)                                   AS activity_total_points,
            (
              tasks_all_user + days_shoulda_worked + all_quizzes
            )                                        AS activity_max_points,
            concat_ws(' ', users.fname, users.lname) AS real_name
          FROM (
                 SELECT
                   whole_shebang.*,
                   coalesce((days_worked * cp_activity.daysworked_apoint) * cp_activity.daysworked_cpoint,
                            0) AS days_worked_points,
                   round(coalesce((miles * cp_activity.miles_apoint) * cp_activity.miles_cpoint, 0),
                         0)    AS miles_points,
                   coalesce((tasks_completed_by_user * cp_activity.tasks_apoint) * cp_activity.tasks_cpoint,
                            0) AS task_points,
                   coalesce((passed_quizzes * cp_activity.quiz_apoint) * cp_activity.quiz_cpoint,
                            0) AS quiz_points,
                   coalesce((idle_time * cp_activity.idle_apoint) * cp_activity.idle_cpoint,
                            0) AS idle_time_points,
                   round(
                       TIMESTAMPDIFF(DAY, str_to_date(v_date_start, '%Y-%m-%d'), str_to_date(v_date_end, '%Y-%m-%d')) *
                       .675,
                       0)      AS days_shoulda_worked
                 FROM (
                        SELECT
                          tasks.employee_id           AS emp_id,
                          tasks_completed_by_user,
                          tasks_all_user,
                          category,
                          coalesce(passed_quizzes, 0) AS passed_quizzes,
                          coalesce(all_quizzes, 0)    AS all_quizzes,
                          days_worked,
                          round(miles, 0)             AS miles,
                          idle_time,
                          aprox_idle_costs
                        FROM ((
                                SELECT
                                  assign_to                  AS employee_id,
                                  sum(CASE WHEN tasks.complete_user = 1
                                    THEN 1
                                      ELSE 0 END)            AS tasks_completed_by_user,
                                  count(tasks.complete_user) AS tasks_all_user,
                                  category
                                FROM tasks
                                WHERE
                                  submit_date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                            '%Y-%m-%d')
                                GROUP BY assign_to) tasks LEFT OUTER JOIN (
                                                                            SELECT
                                                                              employee_id,
                                                                              sum(CASE WHEN success = 1
                                                                                THEN 1
                                                                                  ELSE 0 END) AS passed_quizzes,
                                                                              count(success)  AS all_quizzes
                                                                            FROM assignments.user_quizzes uq
                                                                              JOIN assignments.v_imported_users viu
                                                                                ON uq.user_id = viu.UserID
                                                                              JOIN catalina.users
                                                                                ON users.username = viu.UserName
                                                                            WHERE uq.added_date BETWEEN STR_TO_DATE(
                                                                                v_date_start,
                                                                                '%Y-%m-%d') AND STR_TO_DATE(
                                                                                v_date_end, '%Y-%m-%d')
                                                                            GROUP BY employee_id) quiz
                            ON quiz.employee_id = tasks.employee_id
                          LEFT OUTER JOIN (
                                            SELECT
                                              COUNT(*)          AS days_worked,
                                              `employee number` AS employee_id
                                            FROM days_worked
                                            WHERE worked = 1 AND
                                                  `date worked` BETWEEN STR_TO_DATE(v_date_start,
                                                                                    '%Y-%m-%d') AND STR_TO_DATE(
                                                      v_date_end, '%Y-%m-%d')
                                            GROUP BY `employee number`) worked ON worked.employee_id = tasks.employee_id
                          LEFT OUTER JOIN (
                                            SELECT
                                              sum(miles)       AS miles,
                                              employee_id,
                                              sum(`Idle Time`) AS idle_time
                                            FROM import_gps_trips
                                            WHERE (
                                              began BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(
                                                  v_date_end,
                                                  '%Y-%m-%d')
                                              AND Ended BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(
                                                  v_date_end,
                                                  '%Y-%m-%d'))
                                            GROUP BY employee_id) miles ON miles.employee_id = tasks.employee_id) JOIN (
                                                                                                                         SELECT
                                                                                                                           *
                                                                                                                         FROM
                                                                                                                           idle_calcs) idle_cals
                            ON (idle_time /
                                60) BETWEEN idle_cals.idle_from_hrs AND idle_cals.idle_to_hrs) whole_shebang,
                   (
                     SELECT *
                     FROM
                       cp_activity) cp_activity) a
            RIGHT OUTER JOIN users ON users.employee_id = a.emp_id) mo_data
    );

    if v_print is TRUE THEN
    SELECT *
    FROM task_productivity_tmp;
    END IF ;
  END;

