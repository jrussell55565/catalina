create procedure expiration_reminder (v_days INTEGER)
  BEGIN
select * from (
  SELECT
    status,
  concat_ws(' ',fname, lname) AS real_name,
  email,
             USERNAME,
             EMPLOYEE_ID,
             DRIVER_LICENSE_EXP,
             null as MED_CARD_EXP,
             NULL AS tsa_issue,
null as tsa_date_exp,
  null as tsa_date_change_exp
           FROM users
           UNION
SELECT
  status,
  concat_ws(' ',fname, lname) AS real_name,
  email,
             USERNAME,
             EMPLOYEE_ID,
             NULL AS DRIVER_LICENSE_EXP,
             MED_CARD_EXP,
             NULL AS tsa_issue,
null as tsa_date_exp,
  null as tsa_date_change_exp
           FROM users
           UNION
           SELECT
             status,
        concat_ws(' ',fname, lname) AS real_name,
  email,
             USERNAME,
             EMPLOYEE_ID,
             NULL AS DRIVER_LICENSE_EXP,
             NULL AS MED_CARD_EXP,
             CASE WHEN tsa_date_exp is not null then 1 END as tsa_issue,
            tsa_date_exp,
  tsa_date_change_exp
           FROM users) root
where root.status = 'Active'
AND (driver_license_exp = current_date + INTERVAL v_days DAY OR
      MED_CARD_EXP = current_date + INTERVAL v_days DAY OR
     (tsa_issue = 1 and (tsa_date_exp = current_date + INTERVAL v_days DAY or tsa_date_change_exp = current_date + INTERVAL v_days DAY)));
END ;
