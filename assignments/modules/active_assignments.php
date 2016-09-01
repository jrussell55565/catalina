<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("active_assignments");

    require "grid.php";
    require "db/users_db.php";
    require "db/asg_db.php";
    require "db/payments_db.php";

    $hedaers = array(ASSIGNMENT_NAME);
    $columns = array("assignment_name"=>"text");

    $grd = new grid($hedaers,$columns, "index.php?module=active_assignments");
    if($mobile) $grd->table = "<table class=tblGrid  border=0 class=\"table table-striped table-bordered mediaTable\" id='table-3'>";
    
    $asg_arr = array();

  //  $grd->process_html_command="process_quiz_status";
    
    $page_js = "";
    
    $grd->column_override = array("assignment_name"=>"assignment_name_override");
     
    function assignment_name_override($row)
    {
        global $page_js,$DEFAULT_LANGUAGE_FILE,$LANGUAGES,$mobile;
        
        $rate_html = "";
        $exam_link = process_quiz_status($row);        
        $cost_info= "";
        $assignment_name=$row['assignment_name'];
        $img_src = $row['asg_image'];
        if($img_src=="") $img_src = "no.jpg";
        $desc = $row['short_desc'];
        $share_link = "";
        $user_quiz_id=$row['user_quiz_id'];
        $id = $row['asg_id'];
        
        $rowspan=3;
        if($row['asg_cost']!=0)
        {
            $rowspan  ++ ;
            $cost_info = show_cost_info($row);
        }
        
        if($row['asg_rate_id']!="-1" && $mobile==false)
        {
            $page_js.="DrowRating('a".$id."',".$row['asg_rate_id'].", '','rating','".$LANGUAGES[$DEFAULT_LANGUAGE_FILE]."','local'); \n";
            $rate_html = "<tr><td><div id=\"a$id\"></div></td></tr>";
            $rowspan  ++ ;
        }
    
        if($row['fb_share']!=0 && $row['finish_date']!="") // access::UserInfo()->imported==2 && 
        {
            $share_link = "<a href='javascript:post_to_wall($user_quiz_id)'>".util::get_fb_button()."</a>";
        }                
         
        $style = "style='height:100px'";        
        if($mobile==true) $style ="style='width:80px'";
         
         $img = "<div align=center><img ".$style." align='center'  src='asg_images/$img_src' /></div>";
         $html = "<table><tr><TH style='".util::get_width(200, 100).";border-width:0px' ROWSPAN=$rowspan align='center' >$img</TH><td><b>$assignment_name</b></td></tr>$cost_info<tr><td>$desc</td></tr>$rate_html<tr><td>". //<tr><td>$cost_info</td></tr>
                "$exam_link &nbsp; $share_link</td></tr></table>";
       
         return $html;
    }
    
    function show_cost_info($row)
    {
        $paid_button = $row['is_paid']=="1" ? PAID : "";
        $html = COST." : ".$row['asg_cost']." ".PAYPAL_CURRENCY;
        $html = webcontrols::AddColor($html, "red");
        //$html="<tr><td>$html <a href='#' />$paid_button</a></td></tr>";
        $paid_button = $paid_button == "" ? $paid_button : " - ".$paid_button;
        $html="<tr><td>$html $paid_button</td></tr>";
        return $html;
    }

    function process_quiz_status($row)
    {
        global $asg_arr;
        $html ="";      
	$status = intval($row['user_quiz_status']);  
	if(intval($row['limited'])<=intval($row['uq_count']))
	{
        	if($status<2)
        	{
			$status == 0 ? $text = START : $text = CCONTINUE;
            		$html.="<a href='?module=show_intro&id=".$row['asg_id']."'>".$text."</a>";
       		}
        	else
        	{
            		$html.=ALREADY_FINISHED;
        	}
	}
	else 
	{
		if($status<2)
        	{
			$status == 0 ? $text = START : $text = CCONTINUE;
            		$html.="<a href='?module=show_intro&id=".$row['asg_id']."'>".$text."</a>";
       		}
        	else
        	{
            		$html.="<a href='?module=show_intro&id=".$row['asg_id']."'>".TAKE_AGAIN."</a>";
        	}
	}
        
        if($row['is_paid']=="0" && $row['asg_cost']>0)
        {
            $cost = $row['asg_cost'];
            $balance = access::UserInfo()->balance;
            $html="<a style='cursor:pointer' href='#myModal' data-toggle='modal' data-target='#myModal' onclick='BuyAsg(".$row['asg_id'].",\"$cost\",\"$balance\")')>".BUY_NOW."</a>";
        }
        
        $asg_arr[$row['asg_id']] = $row;
        
        return $html;
    }


    $grd->id_column="asg_id";
    
    $grd->edit=false;
    $grd->delete=false;
    $grd->empty_data_text=NO_ACTIVE_ASG;


    $user_id = access::UserInfo()->user_id;
    $query = asgDB::GetActAsgByUserIDQuery($user_id);
    
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    if(isset($_POST["ajax"]))
    {
        if(isset($_POST['make_payment']))
        {
            $asg_id = db::clear($_POST['my_id']);
            if(isset($asg_arr[$asg_id]) && $asg_id!="0")
            {
                $arow = $asg_arr[$asg_id];
                if($arow['asg_cost']>access::UserInfo()->balance)
                {
                    echo json_encode(array("mtype"=>0,"msg"=>PLZ_LOAD_BALANCE));
                }
                else
                {
                    payments_db::MakePayment($asg_id, access::UserInfo()->email, access::UserInfo()->user_id);
                    access::UserInfo()->UpdateBalance();
                    echo json_encode(array("mtype"=>1,"msg"=>SUCCESS_PAYMENT,"pagejs"=>$page_js));
                }
            }
            else echo json_encode(array("mtype"=>0,"msg"=>ASG_ALREADY_PAID));
        }        
        else echo $grid_html;
    }

    function desc_func()
    {
        return ACTIVE_ASSIGNMENTS;
    }

?>
