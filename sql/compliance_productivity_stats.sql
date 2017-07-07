CREATE PROCEDURE `compliance_productivity_stats`(IN v_date_start VARCHAR(20), v_date_end VARCHAR(20))
  BEGIN

select
  total_points
  ,(points_cash_value * cp_csa.cash_apoint) * cp_csa.cash_cpoint as points_cash_value
  ,(vehicle_maint_points * cp_csa.vehicle_maint_apoint) * cp_csa.vehicle_maint_cpoint as vehicle_maint_points
  ,vehicle_maint_cash
  ,(hos_compliance_points * cp_csa.hos_compliance_apoint) * cp_csa.hos_compliance_cpoint as hos_compliance_points
  ,hos_compliance_cash
  ,(no_violation_points * cp_csa.no_violation_apoint) * cp_csa.no_violation_cpoint as no_violation_points
  ,no_violation_cash
  ,(unsafe_driving_points * cp_csa.unsafe_driving_apoint) * cp_csa.unsafe_driving_cpoint as unsafe_driving_points
  ,unsafe_driving_cash
  ,(driver_fitness_points * cp_csa.driver_fitness_apoint) * cp_csa.driver_fitness_cpoint as driver_fitness_points
  ,driver_fitness_cash
  ,(controlled_sub_points * cp_csa.controlled_substances_apoint) * cp_csa.controlled_substances_cpoint as controlled_sub_points
  ,controlled_sub_cash
  ,(hazard_points * cp_csa.hazmat_compliance_apoint) * cp_csa.hazmat_compliance_cpoint as hazard_points
  ,hazard_cash
  ,(crash_points * cp_csa.crash_indicator_apoint) * crash_indicator_cpoint as crash_points
  ,crash_cash
  , employee_id
  , status
  ,real_name
  FROM
  (
  select coalesce(total_points,0) as total_points
  ,coalesce(points_cash_value,0) as points_cash_value
  ,coalesce(vehicle_maint_points,0) as vehicle_maint_points
  ,coalesce(vehicle_maint_cash,0) as vehicle_maint_cash
  ,coalesce(hos_compliance_points,0) as hos_compliance_points
  ,coalesce(hos_compliance_cash,0) AS hos_compliance_cash
  ,coalesce(no_violation_points,0) as no_violation_points
  ,coalesce(no_violation_cash,0) as no_violation_cash
  ,coalesce(unsafe_driving_points,0) as unsafe_driving_points
  ,coalesce(unsafe_driving_cash,0) as unsafe_driving_cash
  ,coalesce(driver_fitness_points,0) as driver_fitness_points
  ,coalesce(driver_fitness_cash,0) as driver_fitness_cash
  ,coalesce(controlled_sub_points,0) as controlled_sub_points
  ,coalesce(controlled_sub_cash,0) as controlled_sub_cash
  ,coalesce(hazard_points,0) as hazard_points
  ,coalesce(hazard_cash,0) as hazard_cash
  ,coalesce(crash_points,0) as crash_points
  ,coalesce(crash_cash,0) as crash_cash
  , users.employee_id
  , users.status
  ,concat_ws(' ',users.fname,users.lname) as real_name
   FROM
  (
select
  SUM(total_points)      AS total_points
  , SUM(points_cash_value) AS points_cash_value
  ,  sum(case when basic = 'Vehicle Maint.' then total_points else 0 end) as vehicle_maint_points
  ,  sum(case when basic = 'Vehicle Maint.' then points_cash_value else 0 end) as vehicle_maint_cash
  ,  sum(case when basic = 'HOS Compliance' then total_points else 0 end) as hos_compliance_points
  ,  sum(case when basic = 'HOS Compliance' then points_cash_value else 0 end) as hos_compliance_cash
  ,  sum(case when basic = 'No Violation' then total_points else 0 end) as no_violation_points
  ,  sum(case when basic = 'No Violation' then points_cash_value else 0 end) as no_violation_cash
  ,  sum(case when basic = 'Unsafe Driving' then total_points else 0 end) as unsafe_driving_points
  ,  sum(case when basic = 'Unsafe Driving' then points_cash_value else 0 end) as unsafe_driving_cash
  ,  sum(case when basic = 'Driver Fitness' then total_points else 0 end) as driver_fitness_points
  ,  sum(case when basic = 'Driver Fitness' then points_cash_value else 0 end) as driver_fitness_cash
  ,  sum(case when basic = 'Controlled Substances' then total_points else 0 end) as controlled_sub_points
  ,  sum(case when basic = 'Controlled Substances' then points_cash_value else 0 end) as controlled_sub_cash
  ,  sum(case when basic = 'Hazmat Compliance' then total_points else 0 end) as hazard_points
  ,  sum(case when basic = 'Hazmat Compliance' then points_cash_value else 0 end) as hazard_cash
  ,  sum(case when basic = 'Crash Indicator' then total_points else 0 end) as crash_points
  ,  sum(case when basic = 'Crash Indicator' then points_cash_value else 0 end) as crash_cash
, employee_id
from csadata
where date BETWEEN str_to_date(v_date_start,'%Y-%m-%d') and str_to_date(v_date_end,'%Y-%m-%d')
  and basic in ('Vehicle Maint.','HOS Compliance','No Violation','Unsafe Driving','Driver Fitness','Controlled Substances','Hazmat Compliance','Crash Indicator')
group by employee_id ) csa
RIGHT JOIN users on users.employee_id = csa.employee_id) whole_shebang,
  (select * from cp_csa) cp_csa;

END
