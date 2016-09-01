<?php

class subjects_db
{
    public function get_subjects($where="",$orderby = " id desc ")
    {
        $sql = " select s.* , q.quiz_name from subjects s inner join quizzes q on s.quiz_id=q.id where q.parent_id=0 [{where}]  order by $orderby ";
        
         if($where!="") $sql=str_replace("[{where}]" ,$where, $sql);             
        return $sql;
    }
}

?>

