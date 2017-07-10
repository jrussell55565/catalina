CREATE PROCEDURE `task_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20), v_print BOOLEAN)
  BEGIN

    DROP TEMPORARY TABLE IF EXISTS task_productivity_tmp;
    CREATE TEMPORARY TABLE task_productivity_tmp (
      `days_worked`             DOUBLE DEFAULT 0,
      `miles`                   DOUBLE,
      `idle_time`               DOUBLE,
      `passed_quizzes`          DOUBLE,
      `all_quizzes`             DOUBLE,
      `tasks_completed_by_user` DOUBLE,
      `tasks_all_user`          DOUBLE,
      `category`                VARCHAR(50),
      `days_shoulda_worked`     DOUBLE,
      `idle_from_hrs`           DOUBLE,
      `idle_to_hrs`             DOUBLE,
      `idle_points`             DOUBLE,
      `est_per_gallon`          DOUBLE,
      `aprox_used_gallons`      DOUBLE,
      `aprox_idle_costs`        DOUBLE,
      `days_worked_points`      DOUBLE,
      `miles_points`            DOUBLE,
      `task_points`             DOUBLE,
      `quiz_points`             DOUBLE,
      `idle_time_points`        DOUBLE,
      `activity_total_points`   DOUBLE DEFAULT 0,
      `activity_max_points`     DOUBLE,
      `total_percent`           DOUBLE,
      `real_name`               VARCHAR(50),
      `status`                  VARCHAR(50),
      `employee_id`             VARCHAR(50)
    );

    INSERT INTO task_productivity_tmp (
      SELECT
        coalesce(and_last_one.days_worked, 0)                                                                AS days_worked,
        coalesce(and_last_one.miles, 0)                                                                      AS miles,
        coalesce(and_last_one.idle_time,
                 0)                                                                                          AS idle_time,
        coalesce(and_last_one.passed_quizzes,
                 0)                                                                                          AS passed_quizzes,
        coalesce(and_last_one.all_quizzes,
                 0)                                                                                          AS all_quizzes,
        coalesce(and_last_one.tasks_completed_by_user,
                 0)                                                                                          AS tasks_completed_by_user,
        coalesce(and_last_one.tasks_all_user,
                 0)                                                                                          AS tasks_all_user,
        coalesce(and_last_one.category,
                 0)                                                                                          AS category,
        coalesce(and_last_one.days_shoulda_worked,
                 0)                                                                                          AS days_shoulda_worked,
        coalesce(and_last_one.idle_from_hrs,
                 0)                                                                                          AS idle_from_hrs,
        coalesce(and_last_one.idle_to_hrs,
                 0)                                                                                          AS idle_to_hrs,
        coalesce(and_last_one.idle_points,
                 0)                                                                                          AS idle_points,
        coalesce(and_last_one.est_per_gallon,
                 0)                                                                                          AS est_per_gallon,
        coalesce(and_last_one.aprox_used_gallons,
                 0)                                                                                          AS aprox_used_gallons,
        coalesce(and_last_one.aprox_idle_costs,
                 0)                                                                                          AS aprox_idle_costs,
        coalesce(and_last_one.days_worked_points,
                 0)                                                                                          AS days_worked_points,
        coalesce(and_last_one.miles_points,
                 0)                                                                                          AS miles_points,
        coalesce(and_last_one.task_points,
                 0)                                                                                          AS task_points,
        coalesce(and_last_one.quiz_points,
                 0)                                                                                          AS quiz_points,
        coalesce(and_last_one.idle_time_points,
                 0)                                                                                          AS idle_time_points,
        coalesce(and_last_one.activity_total_points,
                 0)                                                                                          AS activity_total_points,
        coalesce(and_last_one.activity_max_points,
                 0)                                                                                          AS activity_max_points,
        coalesce(round((and_last_one.activity_total_points / and_last_one.activity_max_points) * 100, 0),
                 0)                                                                                          AS total_percent,
        concat_ws(' ', users.fname,
                  users.lname)                                                                               AS real_name,
        users.status,
        users.employee_id
      FROM (
             SELECT
               seriously_thats_it.*,
               coalesce(
                   days_worked_points + miles_points + task_points + quiz_points + idle_time_points,
                   0) AS activity_total_points,
               (
                 tasks_all_user + days_shoulda_worked + all_quizzes
               )      AS activity_max_points
             FROM (
                    SELECT
                      thats_it.*,
                      coalesce((days_worked * cp_activity.daysworked_apoint) * cp_activity.daysworked_cpoint,
                               0) AS days_worked_points,
                      round(coalesce((miles * cp_activity.miles_apoint) * cp_activity.miles_cpoint, 0),
                            0)    AS miles_points,
                      coalesce((tasks_completed_by_user * cp_activity.tasks_apoint) * cp_activity.tasks_cpoint,
                               0) AS task_points,
                      coalesce((passed_quizzes * cp_activity.quiz_apoint) * cp_activity.quiz_cpoint,
                               0) AS quiz_points,
                      coalesce((idle_time * cp_activity.idle_apoint) * cp_activity.idle_cpoint,
                               0) AS idle_time_points
                    FROM (
                           SELECT *
                           FROM (
                                  SELECT
                                    days_worked,
                                    miles,
                                    idle_time,
                                    worked_trips_quiz.employee_id,
                                    coalesce(passed_quizzes, 0)          AS passed_quizzes,
                                    coalesce(all_quizzes, 0)             AS all_quizzes,
                                    coalesce(tasks_completed_by_user, 0) AS tasks_completed_by_user,
                                    coalesce(tasks_all_user, 0)          AS tasks_all_user,
                                    category,
                                    round(
                                        TIMESTAMPDIFF(DAY, str_to_date(v_date_start, '%Y-%m-%d'),
                                                      str_to_date(v_date_end, '%Y-%m-%d')) *
                                        .675,
                                        0)                               AS days_shoulda_worked
                                  FROM (
                                         SELECT
                                           days_worked,
                                           miles,
                                           idle_time,
                                           worked_trips.employee_id,
                                           passed_quizzes,
                                           all_quizzes
                                         FROM (
                                                SELECT
                                                  worked.days_worked,
                                                  trips.miles,
                                                  trips.idle_time,
                                                  worked.employee_id
                                                FROM (
                                                       SELECT
                                                         COUNT(*)          AS days_worked,
                                                         `employee number` AS employee_id
                                                       FROM days_worked
                                                       WHERE worked = 1 AND
                                                             `date worked` BETWEEN STR_TO_DATE(v_date_start,
                                                                                               '%Y-%m-%d') AND STR_TO_DATE(
                                                                 v_date_end, '%Y-%m-%d')
                                                       GROUP BY `employee number`
                                                     ) worked
                                                  LEFT OUTER JOIN (SELECT
                                                                     sum(miles)       AS miles,
                                                                     employee_id,
                                                                     sum(`Idle Time`) AS idle_time
                                                                   FROM import_gps_trips
                                                                   WHERE (
                                                                     began BETWEEN STR_TO_DATE(v_date_start,
                                                                                               '%Y-%m-%d') AND STR_TO_DATE(
                                                                         v_date_end,
                                                                         '%Y-%m-%d')
                                                                     AND Ended BETWEEN STR_TO_DATE(v_date_start,
                                                                                                   '%Y-%m-%d') AND STR_TO_DATE(
                                                                         v_date_end,
                                                                         '%Y-%m-%d'))
                                                                   GROUP BY employee_id) trips
                                                    ON trips.employee_id = worked.employee_id) worked_trips
                                           LEFT OUTER JOIN (SELECT
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
                                             ON quiz.employee_id = worked_trips.employee_id) worked_trips_quiz
                                    LEFT OUTER JOIN (SELECT
                                                       assign_to                  AS employee_id,
                                                       sum(CASE WHEN tasks.complete_user = 1
                                                         THEN 1
                                                           ELSE 0 END)            AS tasks_completed_by_user,
                                                       count(tasks.complete_user) AS tasks_all_user,
                                                       category
                                                     FROM tasks
                                                     WHERE
                                                       submit_date BETWEEN STR_TO_DATE(v_date_start,
                                                                                       '%Y-%m-%d') AND STR_TO_DATE(
                                                           v_date_end,
                                                           '%Y-%m-%d')
                                                     GROUP BY assign_to) tasks
                                      ON worked_trips_quiz.employee_id = tasks.employee_id) worked_trips_quiz_tasks
                             JOIN (
                                    SELECT
                                      idle_from_hrs,
                                      idle_to_hrs,
                                      idle_points,
                                      est_per_gallon,
                                      aprox_used_gallons,
                                      aprox_idle_costs
                                    FROM
                                      idle_calcs) idle_cals
                               ON (idle_time /
                                   60) BETWEEN idle_cals.idle_from_hrs AND idle_cals.idle_to_hrs) thats_it,
                      (SELECT *
                       FROM cp_activity) cp_activity) seriously_thats_it) and_last_one
        RIGHT OUTER JOIN users ON users.employee_id = and_last_one.employee_id
    );
    IF v_print IS TRUE
    THEN
      SELECT *
      FROM task_productivity_tmp;
    END IF;
  END;


