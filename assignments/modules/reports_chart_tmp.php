<?php if(!isset($RUN)) { exit(); } ?>

<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>
<table width="100%">
    

<?php
$i = 0;
while($row=db::fetch($res_qst))
{
    $display = "none";
    if(trim($row["is_random"])=="2") $display = "";
    
    ?>
    <tr style="display:<?php echo $display ?>">
        <td><b><?php echo VARIANT ?> : <?php echo $row["variant_name"] ?></b></td>    
    </tr>
     <tr style="display:<?php echo $display ?>">
        <td><br /></td>    
    </tr>
    <tr>
        <td>
            <font face="tahoma" size="4"><?php echo $row['question_text'] ?></font>
        </td>
    </tr>
    <tr>
        <td><br><table style="width:700px">
                <tr>
                    <td>
                    <?php
                        $res_ans = $db->query(reports_db::GetAnswersReport($row['id'],$asg_id));
                        //while ($row_ans=db::fetch($res_ans))
                       // {
                            $chart_res = get_chart_xml($res_ans);
                            echo renderChart("FusionCharts/FCF_Column3D.swf", "", $chart_res[0], "byCount".$row['id'], 800, 300);                           
                            $chart_ans = $chart_res[1];
                      //  }
                    ?>
                        <br />
                        <TABLE>
                            <?php for($z=0;$z<count($chart_ans);$z++) {  ?>
                            <tr>
                                <td><?php echo $LETTERS[$z] ?> - <?php echo $chart_ans[$z][1] ?></td>
                            </tr>
                            <?php } ?>
                        </TABLE>
                        
                    </td>
                </tr>
                 <tr>
                    <td><br /></td>
                </tr>
                 <tr>
                     <td>
                         <table>
                             <tr><td><?php echo TOTAL_SUCCESS ?> : </td><td><?php echo $row['qst_correct_count'] ?></td></tr>
                             <tr><td><?php echo TOTAL_FAILS ?> : </td><td><?php echo $row['qst_fail_count'] ?></td></tr>
                         </table>
                     </td>
                </tr>
            </table>
            <br><hr>
        </td>
    </tr>
   
   <?php
  $i++;
}

$db->close_connection();

?>

    </table>
<br>
<br>
<br>