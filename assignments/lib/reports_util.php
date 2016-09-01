<?php

class reports_util 
{
    public static function add_access($query,$report_name)
    {     
        $report_res = array();
        $report_res[0] = str_replace("[where]"," where tx.inserted_by=".access::UserInfo()->user_id." ",$query);
        $report_res[1] = $report_name;
        return $report_res;
    }
    
    public static function r20($query,$report_name)
    {
        $report_res = array();
        $report_res[0] = str_replace("[where]"," where po.user_id=".access::UserInfo()->user_id." ",$query);
        $report_res[1] = $report_name;
        $report_res = reports_util::add_currency($report_res[0], $report_res[1]);
        return $report_res;
    }
    
    public static function add_currency($query,$report_name)
    {
        return array($query,$report_name." - ".PAYPAL_CURRENCY);
    }
}

?>
