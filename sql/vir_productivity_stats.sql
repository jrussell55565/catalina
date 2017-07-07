CREATE PROCEDURE `vir_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20))
  BEGIN

    DROP TEMPORARY TABLE IF EXISTS vir_productivity_tmp;
    CREATE TEMPORARY TABLE vir_productivity_tmp (
      `vir_pretrip`                   DOUBLE,
      `vir_posttrip`                  DOUBLE,
      `vir_breakdown`                 DOUBLE,
      `days_worked`                   DOUBLE,
      `vir_additional_trailer`        DOUBLE,
      `max_total_vir_points`          DOUBLE,
      `vir_pretrip_percent`           DOUBLE,
      `vir_posttrip_percent`          DOUBLE,
      `vir_breakdown_percent`         DOUBLE,
      `username`                      VARCHAR(50),
      `miles`                         DOUBLE,
      `vir_pretrip_points`            DOUBLE,
      `vir_posttrip_points`           DOUBLE,
      `vir_additional_trailer_points` DOUBLE,
      `vir_total_points`              DOUBLE,
      `employee_id`                   VARCHAR(50),
      `status`                        VARCHAR(50),
      `real_name`                     VARCHAR(50),
      `vir_total_percent`             DOUBLE
    );
    INSERT INTO vir_productivity_tmp (
        SELECT
      mo_details.vir_pretrip,
      mo_details.vir_posttrip,
      mo_details.vir_breakdown,
      mo_details.days_worked,
      mo_details.vir_additional_trailer,
      mo_details.max_total_vir_points,
      mo_details.vir_pretrip_percent,
      mo_details.vir_posttrip_percent,
      mo_details.vir_breakdown_percent,
      mo_details.username,
      mo_details.miles,
      mo_details.vir_pretrip_points,
      mo_details.vir_posttrip_points,
      mo_details.vir_additional_trailer_points,
      mo_details.vir_total_points,
      u.employee_id,
      u.status,
      concat_ws(' ', u.fname, u.lname)                                    AS real_name,
      coalesce(round((vir_total_points / max_total_vir_points) * 100), 0) AS vir_total_percent
      FROM (
             SELECT
               *,
               (CASE WHEN vir_pretrip_points > days_worked
                 THEN days_worked
                ELSE vir_pretrip_points END) +
               (CASE WHEN vir_posttrip_points > days_worked
                 THEN days_worked
                ELSE vir_posttrip_points END) +
               (CASE WHEN vir_breakdown > days_worked
                 THEN days_worked
                ELSE vir_breakdown END) AS vir_total_points
             FROM (
                    SELECT
                      virs.employee_id,
                      virs.vir_pretrip,
                      virs.vir_posttrip,
                      virs.vir_breakdown,
                      worked.days_worked,
                      vir_additional_trailer,
                      days_worked * 2                          AS max_total_vir_points,
                      coalesce(round((virs.vir_pretrip / worked.days_worked) * 100, 0),
                               0)                              AS vir_pretrip_percent,
                      coalesce(round((virs.vir_posttrip / worked.days_worked) * 100, 0),
                               0)                              AS vir_posttrip_percent,
                      coalesce(round((virs.vir_breakdown / worked.days_worked) * 100, 0),
                               0)                              AS vir_breakdown_percent,
                      users.username,
                      users.status,
                      concat_ws(' ', users.fname, users.lname) AS real_name,
                      round(miles, 0)                          AS miles,
                      coalesce((virs.vir_pretrip * cp_virs.pre_trip_apoint) * cp_virs.pre_trip_cpoint,
                               0)                              AS vir_pretrip_points,
                      coalesce((virs.vir_posttrip * cp_virs.post_trip_apoint) * cp_virs.post_trip_cpoint,
                               0)                              AS vir_posttrip_points,
                      coalesce(
                          (virs.vir_additional_trailer * cp_virs.add_trailer_insp_apoint) *
                          cp_virs.add_trailer_insp_cpoint,
                          0)                                   AS vir_additional_trailer_points
                    FROM
                      (
                        SELECT
                          virs.employee_id,
                          coalesce(sum(CASE WHEN virs.insp_type = 'vir_pretrip'
                            THEN 1
                                       ELSE 0 END), 0) AS vir_pretrip,
                          coalesce(sum(CASE WHEN virs.insp_type = 'vir_posttrip'
                            THEN 1
                                       ELSE 0 END), 0) AS vir_posttrip,
                          coalesce(sum(CASE WHEN virs.insp_type = 'vir_breakdown'
                            THEN 1
                                       ELSE 0 END), 0) AS vir_breakdown,
                          coalesce(sum(CASE WHEN virs.trucktype = 'trailer'
                            THEN 1
                                       ELSE 0 END), 0) AS vir_additional_trailer
                        FROM virs
                        WHERE insp_date BETWEEN str_to_date(v_date_start, '%Y-%m-%d') AND str_to_date(v_date_end,
                                                                                                      '%Y-%m-%d')
                        GROUP BY employee_id
                      ) virs,
                      (
                        SELECT
                          count(*) AS days_worked,
                          `employee number`
                        FROM days_worked
                        WHERE worked = 1 AND
                              `date worked` BETWEEN str_to_date(v_date_start, '%Y-%m-%d') AND str_to_date(v_date_end,
                                                                                                          '%Y-%m-%d')
                        GROUP BY `employee number`
                      ) worked,
                      (
                        SELECT
                          username,
                          employee_id,
                          status,
                          fname,
                          lname
                        FROM users
                      ) users,
                      (SELECT
                         sum(miles) AS miles,
                         employee_id
                       FROM import_gps_trips
                       GROUP BY employee_id) import_gps_trips,
                      (SELECT *
                       FROM cp_virs) cp_virs
                    WHERE virs.employee_id = worked.`employee number`
                          AND virs.employee_id = users.employee_id
                          AND import_gps_trips.employee_id = users.employee_id) details) mo_details
        RIGHT OUTER JOIN users u ON u.employee_id = mo_details.employee_id
    );

    SELECT *
    FROM vir_productivity_tmp;
  END;

