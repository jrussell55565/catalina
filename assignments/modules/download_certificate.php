<?php if(!isset($RUN)) { exit(); } ?>
<?php

 if(!access::menu("user_assignments", false) && !access::has("download_cert"))
 {
      util::redirect("login.php");
      exit();
 }

 require "db/asg_db.php";
 require "lib/rtemplates.php";
 
 isset($_GET["id"]) ? $key = "id" : $key ="user_quiz_id";
 

 $id=util::GetKeyID($key,"login.php"); 
   
 $uq_res = asgDB::GetUserQuizById($id, access::menu("old_assignments",false));
 
 if(db::num_rows($uq_res)==0) util::redirect("?module=old_assignments");
 $row = db::fetch($uq_res);
    
 if(!access::has("download_cert") && access::menu("user_assignments",false)) 
 {
     if($row['user_id']!=Access::UserInfo()->user_id) util::redirect("?module=old_assignments");
 }   
 
 if($row["success"]!="1")
 {
     echo "<meta charset=\"utf-8\" />";
     echo CERT_DOWNLOAD_FAIL;     
     exit();
 }

 if($row['cert_enabled']!="1") exit();
 
    $asg_results = asgDB::GetUserInfoByAsgId($row['asg_id'],$row['user_id'],$row['user_type'],$row['user_quiz_id']);
    $asg_row = $asg_results[0];
 
    $cert_name = $row['cert_name'];

    $content  = file_get_contents(dirname(__FILE__).'/../certificates_folder/'.$cert_name.'/index.php', true);
    
    $content=rtemplates::replace_values($content, $asg_row);    

    // convert in PDF
    require_once(dirname(__FILE__).'/../html_to_pdf/html2pdf.class.php');
    try
    {
        ob_clean();
        $html2pdf = new HTML2PDF('P', 'A4', 'fr',true, 'utf-8');
            try {
                        $html2pdf->setDefaultFont('arialunicid0'); //add this line
                        } catch(Exception $e) { }
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('cert01.pdf');
        header("Content-Disposition: attachment; filename=cert".$id.".pdf");
		
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        
    }
 

 function desc_func()
 {
        return "";
 }
 
?>
