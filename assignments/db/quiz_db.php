<?php

class quizDB{


    public static function GetQuizQuery($where="",$orderby="added_date desc")
    {
        $sql = "select q.*,c.cat_name from quizzes q left join cats c on c.id=q.cat_id where parent_id=0 [{where}] ".au::get_where(true,"q")." order by $orderby";         
        if($where!="") $sql=str_replace("[{where}]" ,$where, $sql);
        return $sql;
    }
    
    public static function GetQuizAndCount($where,$orderby="id desc")
    {         
        $sql = "select count(qs.id) as qst_count , q.quiz_name,q.id from questions qs inner join quizzes q on qs.quiz_id=q.id where q.parent_id=0 and qs.parent_id=0 [{where}] ".au::get_where(true,"q")."  group by q.quiz_name,q.id order by $orderby ";         
        if($where!="") $sql=str_replace("[{where}]" ,$where, $sql);             
        return $sql;
    }

    public static function DeleteQuizById($id)
    {        
        db::exec_sql(quizDB::DeleteQuestionByQuizQuery($id));
        db::exec_sql(quizDB::DeleteQuizByIdQuery($id));
    }
    
    public static function DeleteQuestionByQuizQuery($quiz_id)
    {
        $sql=" delete from questions where id=$quiz_id ".au::get_where();
        return $sql;
    }
    
    public static function DeleteChildQuizByIdQuery($id)
    {        
        return "delete from quizzes where parent_id<>0 and id=$id";
    }


    public static function DeleteQuizByIdQuery($id)
    {
        $sql = "delete from quizzes where id=$id ".au::get_where();
        return $sql;
    }

    public static function AddNewQuiz($name,$desc,$show_into,$into_text)
    {
        $name = db::clear($name);
        $desc = db::clear($desc);
        $sql = "insert into cats(cat_name) values('$name')";
        db::exec_sql($sql);
    }
    
    public static function get_quiz_by_id_list($ids)
    {
            $sql = "select * from quizzes q where q.parent_id=0 ".au::get_where(true,"q")." and q.id in ($ids) order by q.id desc";
            return $sql;
            //select q.quiz_name,q.id,count(qs.id) as qst_count from questions qs inner join quizzes q on q.id=qs.quiz_id where q.parent_id=0 and qs.parent_id=0 and q.id in ($ids) order by q.id desc group by q.quiz_name,q.id
    }
    
     public static function get_quiz_count_by_id_list($ids)
    {
            $sql = "SELECT qs.diff_level_id,
                            qz.id ,COUNT(diff_level_id) as dcount
                     FROM questions qs 
                     inner join quizzes qz on qs.quiz_id=qz.id
                     where qz.parent_id=0 and qz.id in ($ids) ".au::get_where(true,"qz")."
                     GROUP by qs.diff_level_id, qz.id  ";
            return $sql;
            //select q.quiz_name,q.id,count(qs.id) as qst_count from questions qs inner join quizzes q on q.id=qs.quiz_id where q.parent_id=0 and qs.parent_id=0 and q.id in ($ids) order by q.id desc group by q.quiz_name,q.id
    }
}
?>
