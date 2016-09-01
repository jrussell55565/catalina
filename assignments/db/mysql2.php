<?php

class db
{
    var $link;

    public function connect()
    {
       $this->link=mysqli_connect(SQL_IP,SQL_USER,SQL_PWD,SQL_DATABASE) or die(db::error($this->link));
       mysqli_query($this->link,"SET @@SESSION.sql_mode = ''") or die(db::error($this->link));
    }

    public function begin()
    {
        $query = "SET AUTOCOMMIT=0";
        mysqli_query($this->link,$query) or die(db::error($this->link));
        $query = "BEGIN";
        mysqli_query($this->link,$query) or die(db::error($this->link));

    }

    public function query($query)
    {       
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        mysqli_query($this->link,"SET NAMES 'utf8'") ;
        $results = mysqli_query($this->link,$query) ;
        if(!$results) throw new Exception(db::myerror($this->link,$query));
        return $results;
    }
    
    public function query_single_value($query,$column)
    {
        $value = "";
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        mysqli_query($this->link,"SET NAMES 'utf8'") ;
        $results = mysqli_query($this->link,$query) ;
        if(!$results) throw new Exception(db::myerror($this->link,$query));
        
        if(db::num_rows($results)>0)
        {
            $row = db::fetch($results);
            $value = $row[$column];
        }
        
        return $value;
    }
    
    public static function exec_sql_single_value($query,$column,$def_value="")
    {        
	$link=mysqli_connect(SQL_IP, SQL_USER, SQL_PWD,SQL_DATABASE) or die(db::error($link));
	mysqli_query($link,"SET @@SESSION.sql_mode = ''") or die(db::error($link));
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        mysqli_query($link,"SET NAMES 'utf8'") ;
	$results=mysqli_query($link,$query) or die(db::myerror($link,$query));
	mysqli_close($link);
        
        $value=$def_value;
        if(db::num_rows($results)>0)
        {
            $row = db::fetch($results);
            $value = $row[$column];
        }
	return $value;
    }
    
    public function query_as_array($query)
    {
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
                             
        $res=array();
        mysqli_query($this->link,"SET NAMES 'utf8'") ;
	$results = mysqli_query($this->link,$query) ;
        if(!$results) throw new Exception(db::myerror($this->link,$query));
	
        while($rows=mysqli_fetch_array($results))
	{                
		$res[]=$rows;
	}
	return $res;
    }

    public function insert_query($query)
    { 
        //echo $query."<br>";
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        mysqli_query($this->link,"SET NAMES 'utf8'") ;
        mysqli_query($this->link,$query) ;        
        $results = mysqli_insert_id($this->link);
        if(!$results) throw new Exception(db::myerror($this->link,$query));
        return $results;
    }

    public function free($result)
    {
        mysqli_free_result($result);
    }

    public function multi_query($query)
    {
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        $results = mysqli_multi_query($query,$this->link) ;
        if(!$results) throw new Exception(db::myerror($this->link,$query));
        return $results;
    }

    public function rollback()
    {
        mysqli_query($this->link,"ROLLBACK");
    }

    public function commit()
    {
        mysqli_query($this->link,"COMMIT");
    }

    public function last_id()
    {
        return mysqli_insert_id($this->link);
    }

    public static function exec_sql($query)
    {        
	$link=mysqli_connect(SQL_IP, SQL_USER, SQL_PWD,SQL_DATABASE) or die(db::error($link));
	mysqli_query($link,"SET @@SESSION.sql_mode = ''") or die(db::error($link));
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        mysqli_query($link,"SET NAMES 'utf8'") ;
	$results=mysqli_query($link,$query) or die(db::myerror($link,$query));
	mysqli_close($link);
	return $results;
    }

    public static function exec_insert($query)
    {
	$link=mysqli_connect(SQL_IP, SQL_USER, SQL_PWD,SQL_DATABASE) or die(db::error($link));
	mysqli_query($link,"SET @@SESSION.sql_mode = ''") or die(db::error($link));
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
        mysqli_query($link,"SET NAMES 'utf8'") ;
	mysqli_query($link,$query) or die(db::myerror($link,$query));
        $results = mysqli_insert_id($link);
	mysqli_close($link);
	return $results;
    }

    public static function GetResultsAsArray($query)
    {
        $res=array();
	$results=db::exec_sql($query);

	while($rows=mysqli_fetch_array($results))
	{                
		$res[]=$rows;
	}
	return $res;
    }
    
    public static function Select($array,$column,$value,$single_value = false, $single_value_column="", $default_value="-1")
    {
        $res=array();        
        for($i=0;$i<count($array);$i++)
	{
            $rows=$array[$i];
            if($rows[$column]==$value)
            {
                $res[]=$rows;
            }
            
	}
        $single_res = $default_value;
        if($single_value==true)
        {
            if(sizeof($res)>0) $single_res = $res[0][$single_value_column];            
            return $single_res;
        }
        return $res;
    }

    public static function GetResultsAsArrayByColumn($query, $column)
    {
        $res=array();
	$results=db::exec_sql($query);
        $i=0;
	while($rows=mysqli_fetch_array($results))
	{
		$res[$i]=$rows[$column];
                $i++;
	}
	return $res;
    }
    
    public static function GetResultsByColumn($myarray, $column)
    {
        $res=array();

	for($i=0;$i<sizeof($myarray);$i++)
	{
                $row = $myarray[$i];
		$res[$i]=$row[$column];                
	}
	return $res;
    }

    public static function exec_multi_sql($query)
    {
	$link=mysqli_connect(SQL_IP, SQL_USER, SQL_PWD,SQL_DATABASE) or die(db::error($link));
        if(DEBUG_SQL=="yes") debug::AddSQL ($query);
	$results=mysqli_multi_query($link,$query) or die(db::myerror($link,$query));
	mysqli_close($link);
	return $results;
    }

    public static function error($lnk)
    {
        die (mysqli_error($lnk));
    }

    public static function myerror($lnk,$query)
    {
        $msg=mysqli_error($lnk);
        if(DEBUG_SQL=="yes")
        {
            $msg.=" - ".$query;
        }
        die ($msg);
    }
 

    public static function clear($str)
    {
        return db::esp(trim($str));
    }

    public static function fetch($results)
    {
       $row=mysqli_fetch_array($results);
       return $row;
    }

    public static function num_fields($results)
    {
        return mysqli_num_fields($results);
    }

     public static function num_rows($results)
    {
        return mysqli_num_rows($results);
    }

    public static function esp($str,$anyway=false)
    {
		if(!get_magic_quotes_gpc() || $anyway==true)
		{
			$str = addslashes($str);
		}
		return $str;
    }

    public function close_connection()
    {
       mysqli_close($this->link);
    }
    
    
    public static function arr_to_in($arr)
    {
       
        $where = "";
        for($i=0;$i<count($arr);$i++)
        {
            $where.=",".$arr[$i];
        }
        if($where!="")
        {
            $where = substr($where, 1);
        }
        return $where;
    }
    
    public static function arr_to_in_arrres($arr,$index)
    {
       
        $where = "";
        for($i=0;$i<count($arr);$i++)
        {
            $where.=",".$arr[$i][$index];
        }
        if($where!="")
        {
            $where = substr($where, 1);
        }
        return $where;
    }
    
    public static function arr_to_in_multi($arr,$index)
    {
       
        $where = "";
        for($i=0;$i<count($arr);$i++)
        {            
            $arr_m = explode(",",$arr[$i]);    
            if(trim($arr_m[$index])!="")
            $where.=",".$arr_m[$index];
        }
        if($where!="")
        {
            $where = substr($where, 1);
        }
        return $where;
    }
}

class debug
{
    public static function AddSQL($sql)
    {
        $file = basename($_SERVER["SCRIPT_NAME"]);
        if($file=="login.php") return;

        if(!isset($_SESSION['sql']))
        {
            $_SESSION['sql'] = array();
            $_SESSION['sql_i'] = 0;
        }

        $queries = $_SESSION['sql'];
        $i = intval($_SESSION['sql_i']);

        $queries[$i] = $sql;
        $i++ ;

        $_SESSION['sql']= $queries;
        $_SESSION['sql_i'] = $i;
    }
    
    public static function GetSQLs()
    {
        if(!isset($_SESSION['sql']))
        {
            $_SESSION['sql'] = array();
            $_SESSION['sql_i'] = 0;
        }
        $queries = $_SESSION['sql'];
        //$_SESSION['sql'] = array();
        //$_SESSION['sql_i'] =0;
        return $queries;
    }
}

?>