<?php if(!isset($RUN)) { exit(); } ?>
<?php
require "lib/ldap_helper.php";

access::menu("test_ldap");

if(isset($_POST['ajax']))
{
    $login = trim($_POST["login"]) ;
    $pass  = trim($_POST["pass"]) ;
    
    ldap_helper::TestLdap($login, $pass);
}

function desc_func() 
{
	return TEST_LDAP;
}

?>
