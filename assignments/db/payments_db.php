<?php

class payments_db
{
    public static function GetPaymentsHistory($where = "",$orderby="added_date desc")
    {
        $sql = "SELECT po.*,pm.display_name,a.assignment_name,a.id AS asg_id, val.FullName
                FROM payment_orders po
                LEFT JOIN payment_methods pm ON pm.id = po.payment_type_id 
                INNER JOIN v_all_users val on val.UserID=po.user_id
                LEFT JOIN assignments a ON a.id= po.payment_type_id                 
                $where order by $orderby";
        
        return $sql;
        
    }
    
    public static function GetPayorDetailsByEmail($email,$where="")
    {
        $sql = "SELECT * FROM user_payment_accounts upa
                INNER JOIN v_all_users vau ON vau.UserID = upa.user_id
                WHERE upa.p_account ='".$email."' $where ";
        return $sql;
    }
    
    public static function MakePayment($asg_id,$email,$user_id)
    {
        $txn_id = payments_db::GetTxID();
        $inserted_by = access::UserInfo()->user_id;
        $inserted_date = util::Now();
        $currency = PAYPAL_CURRENCY;
        $branch_id =access::UserInfo()->branch_id;        
                
        $sql = "INSERT into payment_orders (txn_id,payer_email,mc_gross,inserted_by,inserted_date,payment_type,currency,payment_type_id,dbt_crd,user_id,branch_id)
                SELECT '$txn_id', '$email', asg_cost , $inserted_by, '$inserted_date',2,'$currency', id,1, $user_id, $branch_id 
                FROM assignments WHERE id=$asg_id ";
     //   return $sql;
       db::exec_sql($sql);
    }
    
    public static function GetTotalIncomeByExamID($asg_id)
    {
        $sql = "SELECT ifnull(SUM(po.mc_gross),0) total FROM payment_orders po WHERE po.payment_type=2 and po.payment_type_id = $asg_id";
        return $sql;
    }
    
    public static function GetTxID()
    {
       return "2".date('YmdHis');
    }
    
    
}

?>