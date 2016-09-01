<?php

class rtemplates
{
      
    public static function replace_values($var, $row)
    {
        $photo = $row['user_photo'];
        if($photo=="") $photo ="nophoto.jpg";
       	$var = str_replace("[quiz_name]", $row['assignment_name'],$var);
        $var = str_replace("[assignment_name]", $row['assignment_name'],$var);
        $var = str_replace("[level_name]", $row['level_name'],$var);
	$var = str_replace("[start_date]", $row['uq_added_date'],$var);
	$var = str_replace("[finish_date]", $row['finish_date'],$var);
	$var = str_replace("[pass_score]", $row['pass_score'],$var);
	$var = str_replace("[user_score]", $row['results_mode']=="1" ? $row['pass_score_point'] : $row['pass_score_perc']." %" ,$var);
	$var = str_replace("[UserName]", $row['UserName'],$var);
	$var = str_replace("[Name]", $row['Name'],$var);
	$var = str_replace("[Surname]", $row['Surname'],$var);
	$var = str_replace("[email]", $row['email'],$var);
        $var = str_replace("[user_photo]", $photo,$var);
	$var = str_replace("[url]", WEB_SITE_URL,$var);
        return $var;
    }
    
    public static function t_replace_values($var, $row, $symbols=10000)
    {
                global $STATUSES,$PRIORITIES;
                $var = str_replace("[subject]", util::GetShortText($row['t_subject'],$symbols),$var); 
		$var = str_replace("[body]", $row['t_body'],$var); 
		$var = str_replace("[category_name]", $row['CatName'],$var); 
		$var = str_replace("[department_name]", $row['DepName'],$var); 
		$var = str_replace("[priority_name]", $PRIORITIES[$row['PriorityName']],$var); 
                $var = str_replace("[status_name]", $STATUSES[$row['StatusName']],$var); 
                $var = str_replace("[creator_name]", $row['cr_full_name'],$var);                 
		$var = str_replace("[url]", WEB_SITE_URL,$var);
                $var = str_replace("[ticket_url]", WEB_SITE_URL."?module=read_ticket&r=1&id=".$row['id'],$var);
                
        return $var;
    }
    
}


?>
