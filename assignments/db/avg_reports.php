<?php

class avg
{
    public static function GetQuestionsCountQuery()
    {
        $sql = "select count(*) as qcount from questions where parent_id=0 ".au::get_where(true);
        return $sql;
    }       
    public static function GetUsersCountQuery()
    {
        $sql = "select count(*) as qcount from users where approved=1 ".au::get_where(true);
        return $sql;
    }       
       
    public static function GetExamsCountQuery()
    {
        $sql = "select count(*) as qcount from assignments where quiz_type=1 ".au::get_where(true);
        return $sql;
    } 
    
    public static function GetSurveyCountQuery()
    {
        $sql = "select count(*) as qcount from assignments where quiz_type=2 ".au::get_where(true);
        return $sql;
    } 
    
    public static function GetPagesCountQuery()
    {
        $sql = "select count(*) as qcount from pages ";
        return $sql;
    } 
    
    public static function GetUsersByCountry()
    {
        $sql = "select count(country_name) user_count,country_name from users u inner join countries c on u.country_id=c.id ".au::get_where()." group by country_name";
        return $sql;
    }
    
    public static function GetLastExamUsers()
    {
        $sql = "select * from (
                select  pass_score_point , u.Name,u.Surname from assignments asg 
                left join assignment_users asgu on asg.id=asgu.assignment_id 
                left join users u on u.UserID=asgu.user_id
                left join user_quizzes uq on uq.assignment_id=asg.id and uq.user_id = u.UserID
                where asg.id = (select max(id) from assignments where quiz_type=1 and status = 2 and results_mode=1) and pass_score_point<>0
                and uq.success is not null ".au::get_where(true,"asg")."
                order by pass_score_point desc
                ) usr LIMIT 0, 5";
        return $sql;
    }
    
}

?>
