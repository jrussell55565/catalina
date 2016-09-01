<?php
    access::menu("test_cert");
    
    require "lib/cert.php";
    require "lib/rtemplates.php";
    
    if(isset($_POST["btnSubmit"]))
    {        
        $cert_name = $_POST["drpCert"];
		if($cert_name=="-1")
		{
			util::redirect("index.php?module=test_certificates");
			exit();
		}
        $selected_cert_name=$cert_name;
        $content  = file_get_contents(dirname(__FILE__).'/../certificates_folder/'.$cert_name.'/index.php', true);
        
        $row["assignment_name"] = "Test assignment";
        $row["uq_added_date"] = util::Now();
        $row["finish_date"] = util::Now();
        $row["pass_score"] = "10";
        $row["results_mode"] = 1;
        $row["pass_score_point"] = 10;
        $row["user_score"] = "5";
        $row["UserName"] = "Login";
        $row["Name"] = "Test";
        $row["Surname"] = "Test";
        $row["email"] = "Test@test.com";
        $row["level_name"] = "Good";
        $row["user_photo"] = "nophoto.jpg";
        $row["url"] = WEB_SITE_URL;
        

        $content=rtemplates::replace_values($content, $row);    

        // convert in PDF
        require_once(dirname(__FILE__).'/../html_to_pdf/html2pdf.class.php');
        try
        {

            $html2pdf = new HTML2PDF('P', 'A4', 'fr',true, 'utf-8');
            try {
                        $html2pdf->setDefaultFont('arialunicid0'); //add this line
                        } catch(Exception $e) { }
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output('cert01.pdf');
            header("Content-Disposition: attachment; filename=cert_test.pdf");
            exit();
            
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    
    
    }
    
?>
