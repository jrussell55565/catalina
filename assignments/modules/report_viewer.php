<?php

class report_viewer
{
    var $report_id = -1;
    
    var $r_html = "";
    var $r_script = "";
    var $r_start_script = "";
    var $replace_qs_var=true;
    var $drow_head =true;
    var $drow_mode = 1;
    
    public function report_viewer($id)
    {
        $this->report_id = $id;          
    }
    
    public function BuildReport()
    {
        global $E_REPORTS;
        $this->r_html = "<div>";
                
        $results = db::exec_sql(orm::GetSelectQuery("reports", array(), array("id"=>$this->report_id), ""));
        if(db::num_rows($results)==0)
        {
            $this->r_html.=REPORT_NOT_FOUND."</div>";
            return;
        }
         
        $row = db::fetch($results);
      
        $shortcut =  $row['shortcut'];               
        $report_method =  $row['report_method']; 
        
        $query = $row['query'];
        if($this->replace_qs_var==true) $query = $this->replace_qs_vars($query);
        
        $report_name= $row['report_name'];        
        if(isset($E_REPORTS['R'.$shortcut]['report_name'])) $report_name = $E_REPORTS['R'.$shortcut]['report_name'];
        
        $report_name.=" (R$shortcut)";
        
        if($report_method!="")
        {                                    
            $reports_res =reports_util::$report_method($query,$report_name);     
            $query = $reports_res[0];
            $report_name = $reports_res[1];
        }
     
        $query = str_replace("[a:where]", au::get_where(true, "rep"), $query);
        $query = str_replace("[w:where]", au::get_where(false, "rep"), $query);           
        
        $results = db::exec_sql($query);
        
        if(db::num_rows($results)==0)
        {
            $this->r_html.="<div class=report_header>".$report_name."<hr /></div>".NO_DATA."</div>";
            return ;
        }
        
        $script = "";
        
        $list_html= "<table border=0 ><tr><td colspan=2>&nbsp;</td></tr>";
        $colors = util::GetRColors();
                 
        
        while($rrow = db::fetch($results))
        {
         
            if(count($colors)<3) $colors = util::GetRColors();
			
			$colors = array_values($colors);
            
            $count = $rrow['rcount'];
            $label = addslashes($rrow['rname']);
            
            if(isset($E_REPORTS['R'.$shortcut]['trans_array']))
            {                     
                $trans_array = $E_REPORTS['R'.$shortcut]['trans_array'];
                $label = isset($trans_array[$label]) ? $trans_array[$label] : $label;                   
            }
     
            
            $random_color = rand(2,count($colors));
            $keys = array_keys($colors);
            $color = $colors[$keys[$random_color]];
            
          //  $label.=" - ".$color;
            
            $list_html.="<tr><td bgcolor='$color' style='width:20px'></td><td>&nbsp; $label - $count </td></tr>";
            
            $script.=",{ value: $count,
                        color:\"$color\",
                        highlight: \"$color\",
                        label: \"$label\"
                      }";
            unset($colors[$keys[$random_color]]);
            $colors = array_values($colors);
        }
        $list_html.="</table>";
        $script = substr($script, 1);
        //$script = "<script language='javascript'>var reportData".$row['id']." = [".$script."];</script>";
        $script = "var reportData".$row['id']." = [".$script."];";
                
        $this->r_script = $script;
    
		$head = "";
		$width="300";
		$hg="400";
		if($this->drow_head==true)
		{
			$width="400";
			$hg="350";
			$head = "<tr><td class=report_header colspan=2 align=center>".$report_name."<hr /></td></tr>";
		}
    
        if($this->drow_mode==1)
        {
            $this->r_html = "<table border=0>$head<tr><td valign=top><div style='width:".$hg."px;height:".$width."px' id='canvas-holder".$row['id']."'><canvas id='chart-area".$row['id']."' /></div></td>";
            $this->r_html.="<td valign=top>$list_html<td/></table>";
        }
        else
        {
            $this->r_html = "<div id='div_rep'><table border=0>$head<tr><td valign=top><div style='width:".$hg."px;height:".$width."px' id='canvas-holder".$row['id']."'><canvas id='chart-area".$row['id']."' /></div></td>";
            $this->r_html.="</table><div id='div_list' >$list_html</div></div>";
        }
        
        $this->r_start_script="var ctx = document.getElementById('chart-area".$row['id']."').getContext('2d');
				window.myDoughnut = new Chart(ctx).Doughnut(reportData".$row['id'].", {responsive : true});";
        
    }
    
    function replace_qs_vars($str)
    {
        foreach($_GET as $key=>$value)
        {            
            $str = str_replace("[$key]", $value, $str);
        }
        return $str;
    }
    
}

?>
