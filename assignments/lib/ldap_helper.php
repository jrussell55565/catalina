<?php

class ldap_helper
{
    public static function CheckAuthentication($user_name,$password)
    {
        $results = array("1","");
        $ldaprdn  = str_replace("[USER_NAME]", $user_name, LDAP_STRING);
        $ldapport = LDAP_PORT;
        $ldappass = $password;
        
        
        $ldapconn = @ldap_connect(LDAP_SERVER,$ldapport ) ; 
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTOCOL_VERSION);
        
        if(!$ldapconn)
        {
           $results[0] = "0";
           $results[1] = E_LDAP_CANNOT_CONNECT;
           return $results;
        }
        
        $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass) ; 
        
        if(!$ldapbind)
        {
           $results[0] = "0";
           $results[1] = E_LDAP_CANNOT_BIND;
           return $results;
        }
        
        $filter = str_replace("[USER_NAME]", $user_name, LDAP_FILTER_STRING);
        $read = @ldap_search($ldapconn, $ldaprdn, $filter);
        
        if(!$read)
        {
            $results[0] = "0";
            $results[1] = E_LDAP_CANNOT_SEARCH;
            return $results;
        }
        
        $info = ldap_get_entries($ldapconn, $read); 
        
        
        @ldap_close($ldapconn);
        
        $results[0] = "1";
        $results[1] = $info[0];
        return $results;
        
        
        
    }
    
    public static function TestLdap($user_name,$password)
    {
        $results = array("1","");
        $ldaprdn  = str_replace("[USER_NAME]", $user_name, LDAP_STRING);
        $ldapport = LDAP_PORT;
        $ldappass = $password;
        
        
        $ldapconn = ldap_connect(LDAP_SERVER,$ldapport ) ; 
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTOCOL_VERSION);
            
        
        $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass) ; 
        
        $filter = str_replace("[USER_NAME]", $user_name, LDAP_FILTER_STRING);
        $read = ldap_search($ldapconn, $ldaprdn, $filter);
         
        
        $info = ldap_get_entries($ldapconn, $read);                 
        @ldap_close($ldapconn);
        //$ldap_res[1][LDAP_NAME_STR][0]
        
        $ii=0;
        for ($i=0; $ii<$info[$i]["count"]; $ii++){
                $data = $info[$i][$ii];
		echo $data.":&nbsp;&nbsp;".$info[$i][$data][0]."<br>";
	}
                
    }
    
    
    public static function CheckAuthentication2($user_name,$password)
    {
        $results = array("1","");
        $ldaprdn  = str_replace("[USER_NAME]", $user_name, LDAP_STRING);
        $ldapport = LDAP_PORT;
        $ldappass = $password;
        
        try
        {
           $ldapconn = @ldap_connect(LDAP_SERVER,$ldapport ) ; 
           ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, LDAP_PROTOCOL_VERSION);
        } catch (Exception $ex) {
           $results[0] = "0";
           $results[1] = "Cannot connect to the ldap server : ".$ex->getMessage();
           return $results;
        }
        
        try {
            $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass) ;   
        } catch (Exception $ex) {
           $results[0] = "0";
           $results[1] = "Cannot bind ldap server : ".$ex->getMessage();
           return $results;
        }
        
        try
        {
            $filter = str_replace("[USER_NAME]", $user_name, LDAP_FILTER_STRING);
            $read = ldap_search($ldapconn, $ldaprdn, $filter);
            $info = ldap_get_entries($ldapconn, $read);                        
            
        } catch (Exception $ex) {
            $results[0] = "0";
            $results[1] = "Cannot search ldap server : ".$ex->getMessage();
            return $results;
        }
        
        @ldap_close($ldapconn);
        
        $results[0] = "1";
        $results[1] = $info[0];
        return $results;
        
        
        
    }
}

?>