<?php

class res_temp
{
    
        public static function GetTemplateByPoint($temp_id,$point)
        {
            $sql = "SELECT rtcd.fb_message fb_message_s,
                            rtcd.fb_name fb_name_s,
                            rtcd.fb_link fb_link_s,
                            rtcd.fb_description fb_description_s,
                            rtcd.template_content template_content_s,
                            rtcd2.fb_message fb_message_u,
                            rtcd2.fb_name fb_name_u,
                            rtcd2.fb_link fb_link_u,
                            rtcd2.fb_description fb_description_u,
                            rtcd2.template_content template_content_u,
                            rtcd.template_content template_content_s,
                            rtc2.template_content template_content_f,
                            rtcd2.level_id level_id_u,
                            rtcd.level_id level_id_s,
                            rtc2.level_id level_id_f                            
                     FROM result_template_contents rtcd
                     INNER JOIN result_template_contents rtcd2 ON rtcd.template_id=rtcd2.template_id AND rtcd2.template_type=2
                     LEFT JOIN (SELECT * FROM result_template_contents rtc
                                 WHERE rtc.template_type = 3 
                                 AND rtc.template_id=$temp_id
                                 AND rtc.min_point<=$point AND rtc.max_point>=$point
                                 LIMIT 0 ,1) rtc2 ON rtc2.template_type=3
                     WHERE rtcd.template_type =1 and rtcd.template_id=$temp_id";       
            return $sql;
        }
    
}

?>
