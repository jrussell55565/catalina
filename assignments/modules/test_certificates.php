<?php if(!isset($RUN)) { exit(); } ?>   
<?php
    access::menu("test_cert");

    require "lib/cert.php";
    require "lib/rtemplates.php";

    
    $selected_cert_name = "";
    
    $certs = cert::get_all_certs();
    $certificate_options = webcontrols::AddOptions(webcontrols::GetSimpleArrayOptions($certs, "text", "text", $selected_cert_name, false), "-1", NO_CERTIFICATE, "-1") ;
 
    

 function desc_func()
 {
        return "";
 }
?>
