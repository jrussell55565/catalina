CREATE PROCEDURE `new_shipment_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20), v_print BOOLEAN)
  BEGIN

    DECLARE l_emp_id VARCHAR(50);
    DECLARE done INT DEFAULT FALSE;
    DECLARE c1 CURSOR FOR
      select * from employee_id_tmp;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    DROP TEMPORARY TABLE IF EXISTS employee_id_tmp;
    CREATE TEMPORARY TABLE employee_id_tmp (
      `employee_id` VARCHAR(50)
    );

    # Populate the employee_id_tmp table
    INSERT INTO employee_id_tmp
    SELECT employee_id
     FROM users;

    DROP TEMPORARY TABLE IF EXISTS shipment_productivity_tmp;
    CREATE TEMPORARY TABLE shipment_productivity_tmp (
       `id`                                    INT(11)     NOT NULL AUTO_INCREMENT,
      `employee_id`                           VARCHAR(50) NOT NULL,
      `as_puagent`                            DOUBLE      NOT NULL,
      `as_delagent`                           DOUBLE      NOT NULL,
      `as_pu_and_delagent`                    DOUBLE      NOT NULL,
      `total_hwb`                             DOUBLE      NOT NULL,
      `sum_count`                             DOUBLE      NOT NULL,
      `puagent_required_updates`              DOUBLE      NOT NULL,
      `delagent_required_updates`             DOUBLE      NOT NULL,
      `puagent_and_delagent_required_updates` DOUBLE      NOT NULL,
      `core_updates_sum`                      DOUBLE      NOT NULL,
      `misc_updates_sum`                      DOUBLE      NOT NULL,
      `picked_up`                             DOUBLE      NOT NULL,
      `arrived_to_shipper`                    DOUBLE      NOT NULL,
      `delivered`                             DOUBLE      NOT NULL,
      `arrived_to_consignee`                  DOUBLE      NOT NULL,
      `accessorial_count`                     DOUBLE      NOT NULL,
      `arrived_to_shipper_points`             DOUBLE      NOT NULL,
      `max_arrived_to_shipper_points`         DOUBLE      NOT NULL,
      `picked_up_points`                      DOUBLE      NOT NULL,
      `max_picked_up_points`                  DOUBLE      NOT NULL,
      `arrived_to_consignee_points`           DOUBLE      NOT NULL,
      `max_arrived_to_consignee_points`       DOUBLE      NOT NULL,
      `delivered_points`                      DOUBLE      NOT NULL,
      `max_delivered_points`                  DOUBLE      NOT NULL,
      `accessorial_points`                    DOUBLE      NOT NULL,
      `max_accessorial_points`                DOUBLE      NOT NULL,
      `noncore_points`                        DOUBLE      NOT NULL,
      `max_noncore_points`                    DOUBLE      NOT NULL,
      `earned_points`                         DOUBLE      NOT NULL,
      `max_points`                            DOUBLE      NOT NULL,
      `percentage_earned`                     DOUBLE      NOT NULL,
      `name`                                  VARCHAR(50) NOT NULL,
      `status`                                VARCHAR(24) NOT NULL,
       PRIMARY KEY (`id`),
       UNIQUE KEY `employee_id_uk` (`employee_id`)
    );

    OPEN c1;
    READ_LOOP:
    LOOP
      FETCH c1
      INTO l_emp_id;

      IF done
      THEN
        LEAVE read_loop;
      END IF;

      INSERT INTO shipment_productivity_tmp
      (
        `employee_id`,
        `as_puagent`,
        `as_delagent`,
        `as_pu_and_delagent`,
        `total_hwb`,
        `sum_count`,
        `puagent_required_updates`,
        `delagent_required_updates`,
        `puagent_and_delagent_required_updates`,
        `core_updates_sum`,
        `misc_updates_sum`,
        `picked_up`,
        `arrived_to_shipper`,
        `delivered`,
        `arrived_to_consignee`,
        `accessorial_count`,
        `arrived_to_shipper_points`,
        `max_arrived_to_shipper_points`,
        `picked_up_points`,
        `max_picked_up_points`,
        `arrived_to_consignee_points`,
        `max_arrived_to_consignee_points`,
        `delivered_points`,
        `max_delivered_points`,
        `accessorial_points`,
        `max_accessorial_points`,
        `noncore_points`,
        `max_noncore_points`,
        `earned_points`,
        `max_points`,
        `percentage_earned`,
        `name`,
        `status`
      )
        SELECT
          c.*,
          concat_ws(' ', u.fname, u.lname) AS name,
          u.status
        FROM
          (
            SELECT
              l_emp_id                                                      AS 'employee_id',
              b.*,
              Round((Coalesce(b.earned_points / b.max_points, 0) * 100), 0) AS 'percentage_earned'
            FROM (
                   SELECT
                     a.*,
                     Round(a.arrived_to_shipper_points + a.picked_up_points + a.arrived_to_consignee_points +
                           a.delivered_points + a.accessorial_points + a.noncore_points, 0)           AS 'earned_points',
                     Round(
                         a.max_arrived_to_shipper_points + a.max_picked_up_points + a.max_arrived_to_consignee_points +
                         a.max_delivered_points + a.max_accessorial_points + a.max_noncore_points, 0) AS 'max_points'
                   FROM (
                          SELECT
                            _a.count                                                                               AS 'as_puagent',
                            _b.count                                                                               AS 'as_delagent',
                            _c.count                                                                               AS 'as_pu_and_delagent',
                            (_a.count + _b.count) +
                            _c.count                                                                               AS 'total_hwb',
                            _a.count + _b.count +
                            _c.count                                                                               AS 'sum_count',
                            _a.count *
                            2                                                                                      AS 'puagent_required_updates',
                            _b.count *
                            2                                                                                      AS 'delagent_required_updates',
                            _c.count *
                            4                                                                                      AS 'puagent_and_delagent_required_updates',
                            _d.count                                                                               AS 'core_updates_sum',
                            _e.count                                                                               AS 'misc_updates_sum',
                            _f.count                                                                               AS 'picked_up',
                            _g.count                                                                               AS 'arrived_to_shipper',
                            _h.count                                                                               AS 'delivered',
                            _i.count                                                                               AS 'arrived_to_consignee',
                            _j.count                                                                               AS 'accessorial_count',
                            round((_cp_shipments.arrived_shipper_apoint * _g.count) *
                                  _cp_shipments.arrived_shipper_cpoint,
                                  0)                                                                               AS 'arrived_to_shipper_points',
                            round((_cp_shipments.arrived_shipper_apoint * (_a.count + _c.count)) *
                                  _cp_shipments.arrived_shipper_cpoint,
                                  0)                                                                               AS 'max_arrived_to_shipper_points',
                            round((_cp_shipments.picked_up_apoint * _f.count) * _cp_shipments.picked_up_cpoint,
                                  0)                                                                               AS 'picked_up_points',
                            round((_cp_shipments.picked_up_apoint * (_a.count + _c.count)) *
                                  _cp_shipments.picked_up_cpoint,
                                  0)                                                                               AS 'max_picked_up_points',
                            round((_cp_shipments.arrived_consignee_apoint * _i.count) *
                                  _cp_shipments.arrived_consignee_cpoint,
                                  0)                                                                               AS 'arrived_to_consignee_points',
                            round((_cp_shipments.arrived_consignee_apoint * (_b.count + _c.count)) *
                                  _cp_shipments.arrived_consignee_cpoint,
                                  0)                                                                               AS 'max_arrived_to_consignee_points',
                            round((_cp_shipments.delivered_apoint * _h.count) * _cp_shipments.delivered_cpoint,
                                  0)                                                                               AS 'delivered_points',
                            round((_cp_shipments.delivered_apoint * (_b.count + _c.count)) *
                                  _cp_shipments.delivered_cpoint,
                                  0)                                                                               AS 'max_delivered_points',
                            round((_cp_shipments.accessorials_apoint * _j.count) * _cp_shipments.accessorials_cpoint,
                                  0)                                                                               AS 'accessorial_points',
                            0                                                                                      AS 'max_accessorial_points',
                            round((_cp_shipments.noncore_apoint * _e.count) * _cp_shipments.noncore_cpoint,
                                  0)                                                                               AS 'noncore_points',
                            0                                                                                      AS 'max_noncore_points'
                          FROM (
                                 SELECT Count(*) AS count
                                 FROM dispatch
                                 WHERE (
                                         puagentdriverphone =
                                         (
                                           SELECT driverid
                                           FROM users
                                           WHERE username =
                                                 (
                                                   SELECT username
                                                   FROM users
                                                   WHERE employee_id = l_emp_id)))
                                       AND (
                                         delagentdriverphone !=
                                         (
                                           SELECT driverid
                                           FROM users
                                           WHERE username =
                                                 (
                                                   SELECT username
                                                   FROM users
                                                   WHERE employee_id = l_emp_id)))
                                       AND Str_to_date(hawbdate, '%c/%e/%Y') BETWEEN STR_TO_DATE(v_date_start,
                                                                                                 '%Y-%m-%d') AND STR_TO_DATE(
                                     v_date_end, '%Y-%m-%d')) _a,
                            (
                              SELECT Count(*) AS count
                              FROM dispatch
                              WHERE (
                                      delagentdriverphone =
                                      (
                                        SELECT driverid
                                        FROM users
                                        WHERE username =
                                              (
                                                SELECT username
                                                FROM users
                                                WHERE employee_id = l_emp_id)))
                                    AND (
                                      puagentdriverphone !=
                                      (
                                        SELECT driverid
                                        FROM users
                                        WHERE username =
                                              (
                                                SELECT username
                                                FROM users
                                                WHERE employee_id = l_emp_id)))
                                    AND Str_to_date(duedate, '%c/%e/%Y') BETWEEN STR_TO_DATE(v_date_start,
                                                                                             '%Y-%m-%d') AND STR_TO_DATE(
                                  v_date_end, '%Y-%m-%d')) _b,
                            (
                              SELECT Count(*) AS count
                              FROM dispatch
                              WHERE (
                                      delagentdriverphone =
                                      (
                                        SELECT driverid
                                        FROM users
                                        WHERE username =
                                              (
                                                SELECT username
                                                FROM users
                                                WHERE employee_id = l_emp_id)))
                                    AND (
                                      puagentdriverphone =
                                      (
                                        SELECT driverid
                                        FROM users
                                        WHERE username =
                                              (
                                                SELECT username
                                                FROM users
                                                WHERE employee_id = l_emp_id)))
                                    AND Str_to_date(hawbdate, '%c/%e/%Y') BETWEEN STR_TO_DATE(v_date_start,
                                                                                              '%Y-%m-%d') AND STR_TO_DATE(
                                  v_date_end, '%Y-%m-%d')
                                    AND str_to_date(duedate, '%c/%e/%Y') BETWEEN STR_TO_DATE(v_date_start,
                                                                                             '%Y-%m-%d') AND STR_TO_DATE(
                                  v_date_end, '%Y-%m-%d')) _c,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE updated_by =
                                    (
                                      SELECT drivername
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND status IN ('Picked Up',
                                                   'Arrived to Shipper',
                                                   'Delivered',
                                                   'Arrived To Consignee')
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _d,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE updated_by =
                                    (
                                      SELECT drivername
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND status NOT IN ('Picked Up',
                                                       'Arrived to Shipper',
                                                       'Delivered',
                                                       'Arrived To Consignee')
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _e,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE updated_by =
                                    (
                                      SELECT drivername
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND status = 'Picked Up'
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _f,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE updated_by =
                                    (
                                      SELECT drivername
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND status = 'Arrived to Shipper'
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _g,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE updated_by =
                                    (
                                      SELECT drivername
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND status = 'Delivered'
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _h,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE updated_by =
                                    (
                                      SELECT drivername
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND status = 'Arrived To Consignee'
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _i,
                            (
                              SELECT count(*) AS count
                              FROM driverexport
                              WHERE employee_id =
                                    (
                                      SELECT employee_id
                                      FROM users
                                      WHERE username =
                                            (
                                              SELECT username
                                              FROM users
                                              WHERE employee_id = l_emp_id))
                                    AND accessorials <> status
                                    AND date BETWEEN STR_TO_DATE(v_date_start, '%Y-%m-%d') AND STR_TO_DATE(v_date_end,
                                                                                                           '%Y-%m-%d')) _j,
                            (
                              SELECT *
                              FROM cp_shipments) _cp_shipments) a) b) c
          JOIN users u ON BINARY u.employee_id = c.employee_id;
    END LOOP;
    CLOSE c1;

    if v_print is TRUE THEN
    SELECT *
    FROM shipment_productivity_tmp;
    END IF ;
  END
