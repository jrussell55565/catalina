<?php

class rating_viewer 
{
    var $html = "" ;
    var $rate_id = -1;
    var $web_site = "";
    var $product_id= -1;
    var $preview = false;
	var $main_mouseout = "";
    
    public function rating_viewer($id,$product_id)
    {
        $this->rate_id = $id;  
        $this->product_id = $product_id;
    }
    
    public function BuildRating()
    {        
        $query = ratings_db::GetRatingsQueryByID($this->rate_id,$this->product_id,"","");        
        $results = db::exec_sql($query);                                
        //$count = db::num_rows($results);
        $row = db::fetch($results); 
        //if($count==0)
        if($row['id']=="")
        {
            $this->html="<span>".$this->GetDeletedRateHtml();
            return;
        }
                       
        if($row["bgcolor"]!="-1") $this->html="<table  bgcolor='".$row["bgcolor"]."'>";
                      
        $header="";
        if(trim($row['header_text'])!="") $header="<span class=rateheader colspan=2>".$row['header_text']."</span>";
        
        $this->html=$this->html.$header;
        
        $img_count = intval($row["img_count"]);
    //    $this->html.= "<tr>";
        for($i=0;$i<$img_count;$i++)
        {              
            $img_src=$this->GetImg($row,$i);            
            $onmouseover = $this->GetMouseOverScript($row,$i+1);;
            $onmouseout = $this->GetMouseOutScript($row,$i+1);;
            $onclick = $this->GetClickScript($row,$i+1);     
			$this->main_mouseout=$onmouseout;
            if($row['status']=="0")
            {
                $onmouseover=$onmouseout=$onclick="";
            }
            $this->html.="<span><img id='ratelsimg".$this->product_id.($i+1)."' $onclick  $onmouseover src=\"$img_src\"></span>";
        }
                
        
        $rated="";
        if($row["show_results"]==1)
        {
            $rated ="&nbsp;".RATED." ".round($row["avarage_point"],2)." ".OUT_OFF." $img_count (".$row["total_rate"]." ".INT_RATINGS.")";        
        }
      
        $this->html.=$td_rate_results="<span class='rated'>&nbsp;<img style='display:none' id='imgajaxloader".$this->product_id."' src=".$this->web_site."style/i/ajax_loader2.gif>$rated</span>";        
                
        $footer="";
        if(trim($row['footer_text'])!="") $footer="<span class=ratefooter colspan=2>".$row['footer_text']."</span>";
        
      //  $this->html.= "</tr>";
                
        $this->html=$this->html.$footer;

        return $row;
    }
    
    private function has_dot($number)
    {
        $number = round($number,2);
        if(($number*100)%100!=0)
        {
            return true;
        }
        return false;
    }
    
    public function RateIt($product_id,$rate_id,$clicked,$user_id)
    {
        $res_rat = db::exec_sql(orm::GetSelectQuery("ratings", "*", array("id"=>$rate_id), ""));
        $rat_row = db::fetch($res_rat);
        if(intval($rat_row['img_count'])<intval($clicked) || intval($clicked)<1) return "-2";
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $insert_arr=array("rate_id"=>$rate_id,"product_id"=>$product_id,
                                  "point"=>$clicked,
                                  "ip_address"=>$ip
              );
               
        
        if($rat_row['restrict_user']=="2") 
        {
            $check_value=$user_id;
            $insert_arr["user_id"]=$user_id;
        }
        else if($rat_row['restrict_user']=="1") 
        {
            $check_value=$ip;            
        }
        else
        {
            $check_value=-100;
        }
                
        if($check_value!=-100)
        {
            $results = ratings_db::HasRated($product_id, $rate_id, $check_value);
            $count = db::num_rows($results);        
            if($count>0) return "-1";             
        }
               
        
        $insert = orm::GetInsertQuery("user_ratings", $insert_arr);
        db::exec_sql($insert);
        
    }   
    
    private function GetMouseOutScript($row,$i)
    {
        $onout = " onmouseout = \"Rebuild('".$this->product_id ."','".$this->rate_id."')\"";		
        return $onout;
    }
    
    private function GetMouseOverScript($row,$i)
    {     
        $onover = " onmouseover = \"ChangeImage('".$this->product_id."',$i,$row[img_count],'$row[active_img]','$row[inactive_img]')\" ";
        return $onover; 
    }
    
    private function GetClickScript($row,$i)
    {
        if($this->preview==true) return "";
        
        global $DEFAULT_LANGUAGE_FILE,$LANGUAGES;
        
        if(defined('RATINGS_BOX_MODE')) $rate_lang = $LANGUAGES[$DEFAULT_LANGUAGE_FILE];
        else $rate_lang=$row['lang'];
        
        $onclick = " onclick=\"RateIt('".RATINGS_BOX_MODE."','".$row['restrict_user']."','".$this->product_id."', '".$this->rate_id."',$i,'".$this->web_site."',event.pageX,event.pageY,'".$rate_lang."','".ALREADY_RATED."' )\" ";                                
 
        return $onclick;
    }
    
    private function GetImg($row,$i)
    {
        $img_column="";
        if(($i+1)<intval($row['avarage_point'])) $img_column = "active_img";
        else if(($i+1)>intval($row['avarage_point'])) $img_column = "inactive_img";                        
        elseif(($i+1)==intval($row['avarage_point'])) $img_column = "active_img";            
            
        if(($i+1)==intval($row['avarage_point']+1) && $this->has_dot($row['avarage_point'])) $img_column = "half_active_img";            
        return $this->web_site."rating_img/".$row[$img_column];
    }  
    
    public function GetHtml()
    {
		$html="<span ".$this->main_mouseout." >";
        $html.= $this->html;
        $html.="</span>";
        return $html;
    }
    
    private function GetDeletedRateHtml()
    {
        $html = "<span align=center class='deleted_rate'>".DELETED_RATE."</span>";
        return $html;
    }
    
}

?>
