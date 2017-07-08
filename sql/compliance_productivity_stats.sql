CREATE PROCEDURE `compliance_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20), v_print BOOLEAN)
  BEGIN

    DROP TEMPORARY TABLE IF EXISTS compliance_productivity_tmp;
    CREATE TEMPORARY TABLE compliance_productivity_tmp (
      `current_violation_points`       DOUBLE,
      `past_24m_violation_points`      DOUBLE,
      `current_points_cash_value`      DOUBLE,
      `current_vehicle_maint_points`   DOUBLE,
      `current_vehicle_maint_cash`     DOUBLE,
      `current_hos_compliance_points`  DOUBLE,
      `current_hos_compliance_cash`    DOUBLE,
      `current_no_violation_points`    DOUBLE,
      `current_no_violation_cash`      DOUBLE,
      `current_unsafe_driving_points`  DOUBLE,
      `current_unsafe_driving_cash`    DOUBLE,
      `current_driver_fitness_points`  DOUBLE,
      `current_driver_fitness_cash`    DOUBLE,
      `current_controlled_sub_points`  DOUBLE,
      `current_controlled_sub_cash`    DOUBLE,
      `current_hazard_points`          DOUBLE,
      `current_hazard_cash`            DOUBLE,
      `current_crash_points`           DOUBLE,
      `current_crash_cash`             DOUBLE,
      `past_24m_points_cash_value`     DOUBLE,
      `past_24m_vehicle_maint_points`  DOUBLE,
      `past_24m_vehicle_maint_cash`    DOUBLE,
      `past_24m_hos_compliance_points` DOUBLE,
      `past_24m_hos_compliance_cash`   DOUBLE,
      `past_24m_no_violation_points`   DOUBLE,
      `past_24m_no_violation_cash`     DOUBLE,
      `past_24m_unsafe_driving_points` DOUBLE,
      `past_24m_unsafe_driving_cash`   DOUBLE,
      `past_24m_driver_fitness_points` DOUBLE,
      `past_24m_driver_fitness_cash`   DOUBLE,
      `past_24m_controlled_sub_points` DOUBLE,
      `past_24m_controlled_sub_cash`   DOUBLE,
      `past_24m_hazard_points`         DOUBLE,
      `past_24m_hazard_cash`           DOUBLE,
      `past_24m_crash_points`          DOUBLE,
      `status`                         VARCHAR(50),
      `real_name`                      VARCHAR(50),
      `inner_emp_id`                   VARCHAR(50)
    );

    INSERT INTO compliance_productivity_tmp
      SELECT
        current_violation_points,
        past_24m_violation_points,
        (current_points_cash_value * cp_csa.cash_apoint) * cp_csa.cash_cpoint  AS current_points_cash_value,
        (current_vehicle_maint_points * cp_csa.vehicle_maint_apoint) *
        cp_csa.vehicle_maint_cpoint                                            AS current_vehicle_maint_points,
        current_vehicle_maint_cash,
        (current_hos_compliance_points * cp_csa.hos_compliance_apoint) *
        cp_csa.hos_compliance_cpoint                                           AS current_hos_compliance_points,
        current_hos_compliance_cash,
        (current_no_violation_points * cp_csa.no_violation_apoint) *
        cp_csa.no_violation_cpoint                                             AS current_no_violation_points,
        current_no_violation_cash,
        (current_unsafe_driving_points * cp_csa.unsafe_driving_apoint) *
        cp_csa.unsafe_driving_cpoint                                           AS current_unsafe_driving_points,
        current_unsafe_driving_cash,
        (current_driver_fitness_points * cp_csa.driver_fitness_apoint) *
        cp_csa.driver_fitness_cpoint                                           AS current_driver_fitness_points,
        current_driver_fitness_cash,
        (current_controlled_sub_points * cp_csa.controlled_substances_apoint) *
        cp_csa.controlled_substances_cpoint                                    AS current_controlled_sub_points,
        current_controlled_sub_cash,
        (current_hazard_points * cp_csa.hazmat_compliance_apoint) *
        cp_csa.hazmat_compliance_cpoint                                        AS current_hazard_points,
        current_hazard_cash,
        (current_crash_points * cp_csa.crash_indicator_apoint) *
        crash_indicator_cpoint                                                 AS current_crash_points,
        current_crash_cash,
        (past_24m_points_cash_value * cp_csa.cash_apoint) * cp_csa.cash_cpoint AS past_24m_points_cash_value,
        (past_24m_vehicle_maint_points * cp_csa.vehicle_maint_apoint) *
        cp_csa.vehicle_maint_cpoint                                            AS past_24m_vehicle_maint_points,
        past_24m_vehicle_maint_cash,
        (past_24m_hos_compliance_points * cp_csa.hos_compliance_apoint) *
        cp_csa.hos_compliance_cpoint                                           AS past_24m_hos_compliance_points,
        past_24m_hos_compliance_cash,
        (past_24m_no_violation_points * cp_csa.no_violation_apoint) *
        cp_csa.no_violation_cpoint                                             AS past_24m_no_violation_points,
        past_24m_no_violation_cash,
        (past_24m_unsafe_driving_points * cp_csa.unsafe_driving_apoint) *
        cp_csa.unsafe_driving_cpoint                                           AS past_24m_unsafe_driving_points,
        past_24m_unsafe_driving_cash,
        (past_24m_driver_fitness_points * cp_csa.driver_fitness_apoint) *
        cp_csa.driver_fitness_cpoint                                           AS past_24m_driver_fitness_points,
        past_24m_driver_fitness_cash,
        (past_24m_controlled_sub_points * cp_csa.controlled_substances_apoint) *
        cp_csa.controlled_substances_cpoint                                    AS past_24m_controlled_sub_points,
        past_24m_controlled_sub_cash,
        (past_24m_hazard_points * cp_csa.hazmat_compliance_apoint) *
        cp_csa.hazmat_compliance_cpoint                                        AS past_24m_hazard_points,
        past_24m_hazard_cash,
        (past_24m_crash_points * cp_csa.crash_indicator_apoint) *
        crash_indicator_cpoint                                                 AS past_24m_crash_points,
        status,
        real_name,
        inner_emp_id                                                           AS employee_id
      FROM (
             SELECT
               all_violations.*,
               users.status,
               concat_ws(' ', users.fname, users.lname) AS real_name,
               users.employee_id                        AS inner_emp_id
             FROM (
                    SELECT
                      coalesce(current_violation_points, 0)       AS current_violation_points,
                      coalesce(current_points_cash_value, 0)      AS current_points_cash_value,
                      coalesce(current_vehicle_maint_points, 0)   AS current_vehicle_maint_points,
                      coalesce(current_vehicle_maint_cash, 0)     AS current_vehicle_maint_cash,
                      coalesce(current_hos_compliance_points, 0)  AS current_hos_compliance_points,
                      coalesce(current_hos_compliance_cash, 0)    AS current_hos_compliance_cash,
                      coalesce(current_no_violation_points, 0)    AS current_no_violation_points,
                      coalesce(current_no_violation_cash, 0)      AS current_no_violation_cash,
                      coalesce(current_unsafe_driving_points, 0)  AS current_unsafe_driving_points,
                      coalesce(current_unsafe_driving_cash, 0)    AS current_unsafe_driving_cash,
                      coalesce(current_driver_fitness_points, 0)  AS current_driver_fitness_points,
                      coalesce(current_driver_fitness_cash, 0)    AS current_driver_fitness_cash,
                      coalesce(current_controlled_sub_points, 0)  AS current_controlled_sub_points,
                      coalesce(current_controlled_sub_cash, 0)    AS current_controlled_sub_cash,
                      coalesce(current_hazard_points, 0)          AS current_hazard_points,
                      coalesce(current_hazard_cash, 0)            AS current_hazard_cash,
                      coalesce(current_crash_points, 0)           AS current_crash_points,
                      coalesce(current_crash_cash, 0)             AS current_crash_cash,
                      current_violations.employee_id,
                      coalesce(past_24m_violation_points, 0)      AS past_24m_violation_points,
                      coalesce(past_24m_points_cash_value, 0)     AS past_24m_points_cash_value,
                      coalesce(past_24m_vehicle_maint_points, 0)  AS past_24m_vehicle_maint_points,
                      coalesce(past_24m_vehicle_maint_cash, 0)    AS past_24m_vehicle_maint_cash,
                      coalesce(past_24m_hos_compliance_points, 0) AS past_24m_hos_compliance_points,
                      coalesce(past_24m_hos_compliance_cash, 0)   AS past_24m_hos_compliance_cash,
                      coalesce(past_24m_no_violation_points, 0)   AS past_24m_no_violation_points,
                      coalesce(past_24m_no_violation_cash, 0)     AS past_24m_no_violation_cash,
                      coalesce(past_24m_unsafe_driving_points, 0) AS past_24m_unsafe_driving_points,
                      coalesce(past_24m_unsafe_driving_cash, 0)   AS past_24m_unsafe_driving_cash,
                      coalesce(past_24m_driver_fitness_points, 0) AS past_24m_driver_fitness_points,
                      coalesce(past_24m_driver_fitness_cash, 0)   AS past_24m_driver_fitness_cash,
                      coalesce(past_24m_controlled_sub_points, 0) AS past_24m_controlled_sub_points,
                      coalesce(past_24m_controlled_sub_cash, 0)   AS past_24m_controlled_sub_cash,
                      coalesce(past_24m_hazard_points, 0)         AS past_24m_hazard_points,
                      coalesce(past_24m_hazard_cash, 0)           AS past_24m_hazard_cash,
                      coalesce(past_24m_crash_points, 0)          AS past_24m_crash_points,
                      coalesce(past_24m_crash_cash, 0)            AS past_24m_crash_cash
                    FROM (
                           SELECT
                             SUM(violation_weight)  AS current_violation_points,
                             SUM(points_cash_value) AS current_points_cash_value,
                             sum(CASE WHEN basic = 'Vehicle Maint.'
                               THEN total_points
                                 ELSE 0 END)        AS current_vehicle_maint_points,
                             sum(CASE WHEN basic = 'Vehicle Maint.'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_vehicle_maint_cash,
                             sum(CASE WHEN basic = 'HOS Compliance'
                               THEN total_points
                                 ELSE 0 END)        AS current_hos_compliance_points,
                             sum(CASE WHEN basic = 'HOS Compliance'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_hos_compliance_cash,
                             sum(CASE WHEN basic = 'No Violation'
                               THEN total_points
                                 ELSE 0 END)        AS current_no_violation_points,
                             sum(CASE WHEN basic = 'No Violation'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_no_violation_cash,
                             sum(CASE WHEN basic = 'Unsafe Driving'
                               THEN total_points
                                 ELSE 0 END)        AS current_unsafe_driving_points,
                             sum(CASE WHEN basic = 'Unsafe Driving'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_unsafe_driving_cash,
                             sum(CASE WHEN basic = 'Driver Fitness'
                               THEN total_points
                                 ELSE 0 END)        AS current_driver_fitness_points,
                             sum(CASE WHEN basic = 'Driver Fitness'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_driver_fitness_cash,
                             sum(CASE WHEN basic = 'Controlled Substances'
                               THEN total_points
                                 ELSE 0 END)        AS current_controlled_sub_points,
                             sum(CASE WHEN basic = 'Controlled Substances'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_controlled_sub_cash,
                             sum(CASE WHEN basic = 'Hazmat Compliance'
                               THEN total_points
                                 ELSE 0 END)        AS current_hazard_points,
                             sum(CASE WHEN basic = 'Hazmat Compliance'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_hazard_cash,
                             sum(CASE WHEN basic = 'Crash Indicator'
                               THEN total_points
                                 ELSE 0 END)        AS current_crash_points,
                             sum(CASE WHEN basic = 'Crash Indicator'
                               THEN points_cash_value
                                 ELSE 0 END)        AS current_crash_cash,
                             employee_id
                           FROM csadata
                           WHERE
                             date BETWEEN str_to_date(v_date_start, '%Y-%m-%d') AND str_to_date(v_date_end, '%Y-%m-%d')
                             AND basic IN
                                 ('Vehicle Maint.', 'HOS Compliance', 'No Violation', 'Unsafe Driving', 'Driver Fitness', 'Controlled Substances', 'Hazmat Compliance', 'Crash Indicator')
                           GROUP BY employee_id
                         ) current_violations,
                      (SELECT
                         SUM(violation_weight)  AS past_24m_violation_points,
                         SUM(points_cash_value) AS past_24m_points_cash_value,
                         sum(CASE WHEN basic = 'Vehicle Maint.'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_vehicle_maint_points,
                         sum(CASE WHEN basic = 'Vehicle Maint.'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_vehicle_maint_cash,
                         sum(CASE WHEN basic = 'HOS Compliance'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_hos_compliance_points,
                         sum(CASE WHEN basic = 'HOS Compliance'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_hos_compliance_cash,
                         sum(CASE WHEN basic = 'No Violation'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_no_violation_points,
                         sum(CASE WHEN basic = 'No Violation'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_no_violation_cash,
                         sum(CASE WHEN basic = 'Unsafe Driving'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_unsafe_driving_points,
                         sum(CASE WHEN basic = 'Unsafe Driving'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_unsafe_driving_cash,
                         sum(CASE WHEN basic = 'Driver Fitness'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_driver_fitness_points,
                         sum(CASE WHEN basic = 'Driver Fitness'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_driver_fitness_cash,
                         sum(CASE WHEN basic = 'Controlled Substances'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_controlled_sub_points,
                         sum(CASE WHEN basic = 'Controlled Substances'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_controlled_sub_cash,
                         sum(CASE WHEN basic = 'Hazmat Compliance'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_hazard_points,
                         sum(CASE WHEN basic = 'Hazmat Compliance'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_hazard_cash,
                         sum(CASE WHEN basic = 'Crash Indicator'
                           THEN total_points
                             ELSE 0 END)        AS past_24m_crash_points,
                         sum(CASE WHEN basic = 'Crash Indicator'
                           THEN points_cash_value
                             ELSE 0 END)        AS past_24m_crash_cash,
                         employee_id
                       FROM csadata
                       WHERE
                         date BETWEEN str_to_date(v_date_start, '%Y-%m-%d') - INTERVAL 24 MONTH AND str_to_date(
                             v_date_start,
                             '%Y-%m-%d')
                         AND basic IN
                             ('Vehicle Maint.', 'HOS Compliance', 'No Violation', 'Unsafe Driving', 'Driver Fitness', 'Controlled Substances', 'Hazmat Compliance', 'Crash Indicator')
                       GROUP BY employee_id
                      ) past_violations) all_violations
               RIGHT OUTER JOIN users ON users.employee_id = all_violations.employee_id
           ) whole_shebang,
        (SELECT *
         FROM cp_csa) cp_csa;

    IF v_print IS TRUE
    THEN
      SELECT *
      FROM compliance_productivity_tmp;
    END IF;

  END

