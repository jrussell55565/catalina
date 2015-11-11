<?php 
function accessorials($accessorialType,$srcPage,$username)
{
   if ($accessorialType == 'Truck')
   {
      $ckPrefix = 'truck_';
   }elseif($accessorialType == 'Trailer'){
      $ckPrefix = 'trailer_';
   }

	$sql = mysql_query("select * FROM accessorials WHERE acc_type = \"$accessorialType\" ORDER BY revenue_charge");
        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
        	echo "<tr>\n";
                $input_type = '';
                $input_value = '';
                if (preg_match('/^ck_/',$row['input_type']))
                {
                	$visibility = '';
                	$input_type = "type=\"checkbox\" name=\"".$ckPrefix."ck_accessorials[]\" id=\"".$ckPrefix."ck_accessorials[]\" value=\"$row[revenue_charge]\" autocomplete=\"off\"";
                }elseif (preg_match('/^txt_/',$row['input_type'])){
                        $visibility = '';
                        $input_type = "type=\"text\" name=\"bx_accessorials[$row[revenue_charge]]\" id=\"bx_accessorials[$row[revenue_charge]]\"\" value=\"\"";
                }else{
			# If the input is hidden and the page matches then
			# we set the input type html.  Otherwise
			# we just skip this part.
			if ($row['src_page'] == $srcPage)
			{
                $visibility = 'hidden';
                $input_type = "type=\"checkbox\" name=\"ck_accessorials[]\" id=\"ck_accessorials[]\" value=\"$row[revenue_charge]\" checked";
			}else{
				$visibility = 'hidden';
				$input_type = "type=\"checkbox\" name=\"NULL\" id=\"NULL\"";
			}
                }
                echo "<td $visibility>\n";
                if (preg_match('/checkbox/', $input_type))
                {
                    echo "<div class=\"btn-group\" data-toggle=\"buttons\">";
                    echo "<label class=\"btn btn-primary btn-sm\" $colorOverride>";
                }
                echo "<input $input_type/>$row[revenue_charge]\n";
                if (preg_match('/checkbox/', $input_type))
                {
                    echo "</label>";
                    echo "</div>";
                }
		echo "</td>\n";
        	echo "</tr>\n";
	}
	# Send in a hidden field with username
	echo "<tr><td><input type=hidden name=username value=$username ></td></tr>\n";
}

function sendEmail($to, $subject, $body, $cc)
{
  $headers = "From: noreply@catalinacartage.com" . "\r\n" .
             'X-Mailer: PHP/' . phpversion() . "\r\n";
  if (isset($cc))
  {
    $headers .= "CC: $cc\r\n";
  }
	mail($to, $subject, $body, $headers);
}

function pageErrors($error)
{
    $array = array(
      "podName" => "POD Name must not be empty",
      "pieces" => "Pieces must be greater than zero",
    );

    return ($array["$error"]);
}

?>

