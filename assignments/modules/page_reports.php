<?php

    require "../lib/util.php";
    require '../config.php';        
    require "../db/orm.php";
    require '../db/mysql2.php';    
    require '../db/access_db.php';
    require '../db/reports_db.php';
    require '../lib/access.php';
    require "../modules/report_viewer.php";
    include "../lib/reports_util.php";
      

    $module_id = util::GetKeyID("r_id");
    $rep_and = "";
    if(isset($_GET['rep_id']))
    {		
            $rep_id = util::GetKeyID("rep_id");
            $rep_and =" and mr.report_id =$rep_id ";
    }

    $drow_head = 1;
    if(isset($_GET['drow_head']))
    {
            $drow_head = util::GetKeyID("drow_head");
    }
    
    $drow_mode = 1;
    if(isset($_GET['drowMode'])) $drow_mode=$_GET['drowMode'];
   
    $results = db::exec_sql(reports_db::GetReportsByRoleID("where rr.role_id=".access::UserInfo()->role_id." and m.id=$module_id $rep_and "));
    
    $start_scripts = "";
    $rhtml = "<table border=0 style='width:100%'> <tr>";
    $i = 0;
  
    while($row = db::fetch($results))
    {           
        $i++;
        $rv = new report_viewer($row['report_id']);
	$rv->drow_head= $drow_head == "1" ? true : false;
        $rv->drow_mode=$drow_mode;
        $rv->BuildReport();        
        $rhtml.="<td valign=top align=center style='height:300px;width:50%'>".$rv->r_html."</td>";
        $start_scripts.=$rv->r_script."\n".$rv->r_start_script." \n";
        if($i%2==0)
        {
            $rhtml.="</tr><tr>";
        }
    }
    $rhtml.="</table>";
    
    echo json_encode(array("rhtml"=>$rhtml, "scripts"=>$start_scripts));
    
   
    
?>
