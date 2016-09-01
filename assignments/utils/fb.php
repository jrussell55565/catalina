<?php 

  require "../lib/util.php";
  require '../config.php';
  require "../lib/access.php";
  require '../db/mysql2.php';
  require "../db/orm.php";
  require "../db/asg_db.php";
  require "../lib/rtemplates.php";
  
  access::check_autorize();
  
  if(isset($_POST['get_post_js']))
  {
 	
      $id = util::GetInt($_POST['id']);
   			  
      $uq_res = asgDB::GetUserQuizById($id, false);
 
       if(db::num_rows($uq_res)==0) util::redirect("../index.php");
       $row = db::fetch($uq_res);
     
       if($row['user_id']!=Access::UserInfo()->user_id) util::redirect("?module=old_assignments");
      
       $asg_results = asgDB::GetUserInfoByAsgId($row['asg_id'],$row['user_id'],$row['user_type'],$row['user_quiz_id']);
       $asg_row = $asg_results[0];
       $temp_type = $row['success'] == 0 ? 2 : 1;
       $result_template_id = $asg_row['results_template_id'];
       $result_template = orm::Select("result_template_contents", array(), array("template_id"=>$result_template_id,"template_type"=>$temp_type), "1");
       $tmp_row = db::fetch($result_template);     
       
       
        $fb_message = addslashes(db::clear(rtemplates::replace_values($tmp_row["fb_message"], $asg_row))); 
        $fb_name = addslashes(db::clear(rtemplates::replace_values($tmp_row["fb_name"], $asg_row))); 
        //$fb_description = db::clear(rtemplates::replace_values($tmp_row["fb_description"], $asg_row)); 
        $fb_description=addslashes($asg_row['short_desc']);
        $fb_link=addslashes(db::clear(rtemplates::replace_values($tmp_row["fb_link"], $asg_row))); 
        $asg_img = util::get_asg_image($asg_row['asg_image']);
        $js_post_wall = "postToWall('$fb_message','$fb_name','$fb_link','$asg_img','$fb_description')";
       
           
       echo $js_post_wall;
  }

?>