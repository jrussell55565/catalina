<?php


class assignments_util {
    public static function CopyAssignmentStructure($asg_id,$user_id,$now)
    {
        $sql = "INSERT into assignments (quiz_id, org_quiz_id,asg_image,paused,added_date,
                          inserted_by,inserted_date,results_mode, 
                          quiz_time,show_results, pass_score,quiz_type,
                          status , allow_review,qst_order,answer_order,affect_changes,
                          limited, send_results,accept_new_users,assignment_name,asg_quiz_type,
                          branch_id,allow_change_prev,show_success_msg,
                          is_random,random_qst_count,variants, show_intro, intro_text,show_point_info,
                          results_template_id,cert_name, cert_enabled, asg_cat_id,mails_copy,
                          fb_users_list,fb_share,fb_allow_comments,short_desc,
                          v_start_time,v_end_time,calc_mode,ans_calc_mode)

                SELECT null, null,null,0,'$now',
                                          $user_id,'$now',results_mode, 
                                          quiz_time,show_results, pass_score,quiz_type,
                                          0 , allow_review,qst_order,answer_order,affect_changes,
                                          limited, send_results,accept_new_users,assignment_name,asg_quiz_type,
                                          branch_id,allow_change_prev,show_success_msg,
                                          is_random,random_qst_count,variants, show_intro, intro_text,show_point_info,
                                          results_template_id,cert_name, cert_enabled, asg_cat_id,mails_copy,
                                          fb_users_list,fb_share,fb_allow_comments,short_desc,
                                          v_start_time,v_end_time,calc_mode,ans_calc_mode
                FROM assignments                          
                WHERE id = $asg_id";
        return $sql;
    }
}

?>