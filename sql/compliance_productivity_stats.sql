CREATE PROCEDURE `compliance_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20))
  BEGIN

    DROP TEMPORARY TABLE IF EXISTS compliance_productivity_tmp;
    CREATE TEMPORARY TABLE compliance_productivity_tmp (
      `total_points`          VARCHAR(50) NOT NULL,
      `points_cash_value`     DOUBLE      NOT NULL,
      `vehicle_maint_points`  DOUBLE      NOT NULL,
      `vehicle_maint_cash`    DOUBLE      NOT NULL,
      `hos_compliance_points` DOUBLE      NOT NULL,
      `hos_compliance_cash`   DOUBLE      NOT NULL,
      `no_violation_points`   DOUBLE      NOT NULL,
      `no_violation_cash`     DOUBLE      NOT NULL,
      `unsafe_driving_points` DOUBLE      NOT NULL,
      `unsafe_driving_cash`   DOUBLE      NOT NULL,
      `driver_fitness_points` DOUBLE      NOT NULL,
      `driver_fitness_cash`   DOUBLE      NOT NULL,
      `controlled_sub_points` DOUBLE      NOT NULL,
      `controlled_sub_cash`   DOUBLE      NOT NULL,
      `hazard_points`         DOUBLE      NOT NULL,
      `hazard_cash`           DOUBLE      NOT NULL,
      `crash_points`          DOUBLE      NOT NULL,
      `crash_cash`            DOUBLE      NOT NULL,
      `employee_id`           VARCHAR(50) NOT NULL,
      `status`                VARCHAR(24) NOT NULL,
      `real_name`             VARCHAR(50) NOT NULL
    );

    INSERT INTO compliance_productivity_tmp
      SELECT
        total_points,
        (points_cash_value * cp_csa.cash_apoint) * cp_csa.cash_cpoint                                       AS points_cash_value,
        (vehicle_maint_points * cp_csa.vehicle_maint_apoint) *
        cp_csa.vehicle_maint_cpoint                                                                         AS vehicle_maint_points,
        vehicle_maint_cash,
        (hos_compliance_points * cp_csa.hos_compliance_apoint) *
        cp_csa.hos_compliance_cpoint                                                                        AS hos_compliance_points,
        hos_compliance_cash,
        (no_violation_points * cp_csa.no_violation_apoint) *
        cp_csa.no_violation_cpoint                                                                          AS no_violation_points,
        no_violation_cash,
        (unsafe_driving_points * cp_csa.unsafe_driving_apoint) *
        cp_csa.unsafe_driving_cpoint                                                                        AS unsafe_driving_points,
        unsafe_driving_cash,
        (driver_fitness_points * cp_csa.driver_fitness_apoint) *
        cp_csa.driver_fitness_cpoint                                                                        AS driver_fitness_points,
        driver_fitness_cash,
        (controlled_sub_points * cp_csa.controlled_substances_apoint) *
        cp_csa.controlled_substances_cpoint                                                                 AS controlled_sub_points,
        controlled_sub_cash,
        (hazard_points * cp_csa.hazmat_compliance_apoint) *
        cp_csa.hazmat_compliance_cpoint                                                                     AS hazard_points,
        hazard_cash,
        (crash_points * cp_csa.crash_indicator_apoint) *
        crash_indicator_cpoint                                                                              AS crash_points,
        crash_cash,
        employee_id,
        status,
        real_name
      FROM
        (
          SELECT
            coalesce(total_points, 0)                AS total_points,
            coalesce(points_cash_value, 0)           AS points_cash_value,
            coalesce(vehicle_maint_points, 0)        AS vehicle_maint_points,
            coalesce(vehicle_maint_cash, 0)          AS vehicle_maint_cash,
            coalesce(hos_compliance_points, 0)       AS hos_compliance_points,
            coalesce(hos_compliance_cash, 0)         AS hos_compliance_cash,
            coalesce(no_violation_points, 0)         AS no_violation_points,
            coalesce(no_violation_cash, 0)           AS no_violation_cash,
            coalesce(unsafe_driving_points, 0)       AS unsafe_driving_points,
            coalesce(unsafe_driving_cash, 0)         AS unsafe_driving_cash,
            coalesce(driver_fitness_points, 0)       AS driver_fitness_points,
            coalesce(driver_fitness_cash, 0)         AS driver_fitness_cash,
            coalesce(controlled_sub_points, 0)       AS controlled_sub_points,
            coalesce(controlled_sub_cash, 0)         AS controlled_sub_cash,
            coalesce(hazard_points, 0)               AS hazard_points,
            coalesce(hazard_cash, 0)                 AS hazard_cash,
            coalesce(crash_points, 0)                AS crash_points,
            coalesce(crash_cash, 0)                  AS crash_cash,
            users.employee_id,
            users.status,
            concat_ws(' ', users.fname, users.lname) AS real_name
          FROM
            (
              SELECT
                SUM(total_points)      AS total_points,
                SUM(points_cash_value) AS points_cash_value,
                sum(CASE WHEN basic = 'Vehicle Maint.'
                  THEN total_points
                    ELSE 0 END)        AS vehicle_maint_points,
                sum(CASE WHEN basic = 'Vehicle Maint.'
                  THEN points_cash_value
                    ELSE 0 END)        AS vehicle_maint_cash,
                sum(CASE WHEN basic = 'HOS Compliance'
                  THEN total_points
                    ELSE 0 END)        AS hos_compliance_points,
                sum(CASE WHEN basic = 'HOS Compliance'
                  THEN points_cash_value
                    ELSE 0 END)        AS hos_compliance_cash,
                sum(CASE WHEN basic = 'No Violation'
                  THEN total_points
                    ELSE 0 END)        AS no_violation_points,
                sum(CASE WHEN basic = 'No Violation'
                  THEN points_cash_value
                    ELSE 0 END)        AS no_violation_cash,
                sum(CASE WHEN basic = 'Unsafe Driving'
                  THEN total_points
                    ELSE 0 END)        AS unsafe_driving_points,
                sum(CASE WHEN basic = 'Unsafe Driving'
                  THEN points_cash_value
                    ELSE 0 END)        AS unsafe_driving_cash,
                sum(CASE WHEN basic = 'Driver Fitness'
                  THEN total_points
                    ELSE 0 END)        AS driver_fitness_points,
                sum(CASE WHEN basic = 'Driver Fitness'
                  THEN points_cash_value
                    ELSE 0 END)        AS driver_fitness_cash,
                sum(CASE WHEN basic = 'Controlled Substances'
                  THEN total_points
                    ELSE 0 END)        AS controlled_sub_points,
                sum(CASE WHEN basic = 'Controlled Substances'
                  THEN points_cash_value
                    ELSE 0 END)        AS controlled_sub_cash,
                sum(CASE WHEN basic = 'Hazmat Compliance'
                  THEN total_points
                    ELSE 0 END)        AS hazard_points,
                sum(CASE WHEN basic = 'Hazmat Compliance'
                  THEN points_cash_value
                    ELSE 0 END)        AS hazard_cash,
                sum(CASE WHEN basic = 'Crash Indicator'
                  THEN total_points
                    ELSE 0 END)        AS crash_points,
                sum(CASE WHEN basic = 'Crash Indicator'
                  THEN points_cash_value
                    ELSE 0 END)        AS crash_cash,
                employee_id
              FROM csadata
              WHERE date BETWEEN str_to_date(v_date_start, '%Y-%m-%d') AND str_to_date(v_date_end, '%Y-%m-%d')
                    AND basic IN
                        ('Vehicle Maint.', 'HOS Compliance', 'No Violation', 'Unsafe Driving', 'Driver Fitness', 'Controlled Substances', 'Hazmat Compliance', 'Crash Indicator')
              GROUP BY employee_id) csa
            RIGHT JOIN users ON users.employee_id = csa.employee_id) whole_shebang,
        (SELECT *
         FROM cp_csa) cp_csa;

    SELECT *
    FROM shipment_productivity_tmp;

  END
