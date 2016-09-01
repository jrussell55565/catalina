<?php

class orm {

    public static function GetSelectQuery($table, $columns,$where_clause,$order_by, $auto_search=false,$use_quotes=true, $add_where="")
    {
        $columns_select ="";
        for($i=0;$i<count($columns);$i++)
        {
            $columns_select.=",".$columns[$i];
        }        

        if(count($columns)==0)
        {
            $columns_select="*";
        }
        else
        {
            $columns_select=substr($columns_select,1);
        }

        $where = "";
        foreach($where_clause as $key=>$value)
        {
            $operator= "=";
            if(is_array($value))
            {
                 $operator = $value[1];
                 $value = $value[0];                               
            }
            $quotes = "'";
            if($use_quotes==false )$quotes = "";
            $where.=" AND $key $operator $quotes".$value."$quotes";
        }
        if(strlen($where)>0)
        {
            $where = " where ".substr($where , 4);
        }
        
        if($order_by!="")
        {
            $order_by = "order by $order_by";
        }

	if($auto_search == true) $where.=" [{where}] ";
		
        $sql = "select $columns_select from $table $where $add_where $order_by";
        
        return $sql;
    }

    public static function Select($table, $columns,$where_clause,$order_by)
    {        
        $sql = orm::GetSelectQuery($table, $columns,$where_clause,$order_by);        
        return db::exec_sql($sql);
    }
    
     public static function SelectAsArray($table, $columns,$where_clause,$order_by)
    {        
        $sql = orm::GetSelectQuery($table, $columns,$where_clause,$order_by);        
        return db::GetResultsAsArray($sql);
    }

    public static function GetInsertQuery($table,$columns,$clear_anyway=false)
    {
        $columns_str="";
        $values_str="";
        foreach($columns as $key=>$value)
        {            
            $quotes = "'"; 
            if(is_array($value))
            {
                $value = $value[0];
                if($value[1]==true ) $quotes= "";
            }                       
            $columns_str.=",$key";            
            $values_str.=",$quotes".db::esp($value,$clear_anyway)."$quotes";
        }
        
        $columns_str=substr($columns_str,1);
        $values_str=substr($values_str,1);
        $sql = "insert into $table ($columns_str) values($values_str)";
        
        return $sql;
    }

    public static function Insert($table,$columns)
    {
        $sql = orm::GetInsertQuery($table, $columns);
        db::exec_sql($sql);
    }

    public static function GetDeleteQuery($table,$columns)
    {
        $where = "";
        foreach($columns as $key=>$value)
        {
            $check_operator = "=";
            if(is_array($value))
            {
                $check_operator = $value[1];
                $value = $value[0];
            }
            $where.="AND $key $check_operator'".db::clear($value)."'";
        }
        $where = substr($where , 3);
        if($where!="")
        {
            $sql = "delete from $table where $where";
            //db::exec_sql($sql);
        }
        return $sql;
    }

    public static function Delete($table,$columns)
    {
        $sql = orm::GetDeleteQuery($table, $columns);
        db::exec_sql($sql);
    }

    public static function GetUpdateQuery($table,$columns,$where_clause, $add_where = "")
    {

        $update_str = "";
        foreach($columns as $key=>$value)
        {
            $quotes = "'"; 
            if(is_array($value))
            {
                $value = $value[0];
                if($value[1]==true ) $quotes= "";
            }   
            $update_str .= ", $key=$quotes".db::clear($value)."$quotes";
        }
        $update_str=substr($update_str,1);

        $where = "";
        foreach($where_clause as $key=>$value)
        {
            $where.=" AND $key='".db::clear($value)."' $add_where ";
        }        
        if($where!="") 
        {
            $where = substr($where , 4);   
            $where = " where $where";
        }
        
        $sql = "update $table set $update_str $where"; 
     
        return $sql;
    }

    public static function Update($table,$columns,$where_clause, $add_where = "")
    {
        $sql = orm::GetUpdateQuery($table, $columns, $where_clause,$add_where);        
       
        db::exec_sql($sql);
    }
	
	public static function GetPagedQuery($query, $page)
	{
		$sql = "select * from ($query) LIMIT $page,".PAGING ;
		return $sql;
	}
	
	public static function GetPagingCountQuery($query)
	{
		$sql = "select count(*) as page_count from ($query) as table1 " ;		
		return $sql;
	}

	public static function GetEditRes($table,$redirect)
	{
		$results;
		if(isset($_GET["id"]))
    		{
        		$id = util::GetID($redirect);
        		$results=orm::Select($table, array(), array("id"=>$id), "");
        
        		if(db::num_rows($results) == 0 ) util::redirect("".$redirect);
		}
		$row = db::fetch($results);
		return $row;
	}

	public static function GetVar($row,$name)
	{
		if(isset($row))
		{
			return $row[$name];
		}	
		return "";
	}

}
?>
