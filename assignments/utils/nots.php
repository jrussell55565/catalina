<?php 

  require "../lib/util.php";
  require '../config.php';
  require '../db/mysql2.php';
  require "../db/orm.php";
  require '../db/access_db.php';  
  require "../lib/access.php";
  
  access::check_autorize();
  
  if(isset($_POST['get_nots']))
  {
      $i = 0;
      $asg_id = util::GetID();     
      $query =  "select * from nots n ".
                " left join ( ".
                " select UserID,UserName,Name,Surname,user_photo from users ".
                " union  ".
                " select UserID,UserName,Name,Surname,user_photo from v_imported_users  ".
                " ) us on us.UserID=n.sent_by where n.asg_id=$asg_id order by n.added_date desc";

      $results = db::exec_sql($query);
      $table = "<table class=\"table table-condensed table-striped\" data-provides=\"rowlink\">";
      $table.="<thead><tr><th>".SENDER."</th><th>".SUBJECT."</th><th>".ADDED_DATE."</th></tr></thead>";
      $table.="<tbody>";
      while($row=db::fetch($results))
      {
          $body = $row['body'];
          $body = str_replace("\"", "", $body);
          $body = str_replace("\n", "<br>", $body);
          $id = $row['id'];
          $i++;
          $onclick = "javascript:ViewMessage($id)";
          $table.="<tr><td><input type=hidden id=hdnBody$id value=\"$body \" />".$row['Name'].' '.$row['Surname']."</td><td><a href=\"$onclick\">".$row['subject']."</a></td><td>".$row['added_date']."</td></tr>";          
      }
      $table.="</tbody>";
      $table.="</table>";
      $table.="<input type=hidden id=hdnCount value=\"$i\" />";            
      echo $table;
  }

?>