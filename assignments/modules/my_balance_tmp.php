<?php if(!isset($RUN)) { exit(); } ?>

<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>


<table border="0">
    <tr>
        <td valign="top">
            <table>
                 <tr>
                    <td align="center"><?php echo $img ?></td>
                </tr> 
            </table>
        </td>
        <td>&nbsp;&nbsp;</td>
        <td valign="top">
            <table border="0" style="width:<?php echo util::GetWidth() ?>">                       
                   <tr>
                      <td><h5 class="heading"><?php echo LOGIN ?></h5></td>
                       <td><h5 class="heading"><?php echo $login ?></h5></td>
                  </tr>
                  <tr>
                      <td><h5 class="heading"><?php echo USER_NAME ?></h5></td>
                       <td><h5 class="heading"><?php echo $name ?></h5></td>
                  </tr>
                   <tr>
                      <td><h5 class="heading"><?php echo USER_SURNAME ?></h5></td>
                       <td><h5 class="heading"><?php echo $surname ?></h5></td>
                  </tr>
                     <tr>
                      <td><h5 class="heading"><?php echo EMAIL ?></h5></td>
                      <td><h5 class="heading"><?php echo $email ?></h5></td>
                  </tr>
                  <tr>
                      <td><h5 class="heading"><?php echo BRANCH ?></h5></td>
                      <td><h5 class="heading"><?php echo $branch_name ?></h5></td>
                  </tr>


              </table>
        </td>
    </tr>
</table>    

<hr />

<h5><font color="red"><?php echo YOUR_CURR_BALANCE ?> : <span id="spBalance"><?php echo access::UserInfo()->balance ?></span> <?php echo PAYPAL_CURRENCY ?></font>&nbsp;<img id="imgAj" style="display:none" src="style/i/ajax_loader2.gif" /> <input type="button" id="btnupdatebalance" value="<?php echo REFRESH_BALANCE ?>" class="btn green" style="display:<?php echo $display_update ?>" onclick="UpdateBalance()" ></h5> <br />

<?php echo HAVE_QUESTIONS ?> &nbsp;<input class="btn green" type="button" style="width:200px" value="<?php echo ASK_IT ?> !" onclick='window.location.href="?module=add_edit_ticket"' />
<br /><br /><br />

<?php if(!$mobile) { ?>
<div class="tabbable">   
  <ul class="nav nav-tabs">
    <?php for($i=0;$i<count($payment_methods);$i++) { 
     $class = $i==0 ? "class=\"active\"" : "";
     if($payment_methods[$i]['id']==1 && PAYPAL_ENABLED=="no") continue;
    ?>
    <li <?php echo $class ?>><a href="#tab<?php echo $payment_methods[$i]['id'] ?>" data-toggle="tab"><?php echo $payment_methods[$i]['display_name'] ?></a></li>    
    <?php } ?>
  </ul>
  <div class="tab-content">
    <?php for($i=0;$i<count($payment_methods);$i++) { 
     $class = $i==0 ? "class=\"tab-pane active\"" : "class=\"tab-pane\"";
     if($payment_methods[$i]['id']==1 && PAYPAL_ENABLED=="no") continue;
    ?>
    <div <?php echo $class ?> id="tab<?php echo $payment_methods[$i]['id'] ?>">
        <p>
            <?php include "payment_methods/".$payment_methods[$i]['short_name']."/".$payment_methods[$i]['page_name']."_tmp.php"; ?>
        </p>
    </div>
   <?php } ?>
  </div>
</div>
<?php } else { ?>
 <?php for($i=0;$i<count($payment_methods);$i++) {    
     if($payment_methods[$i]['id']==1 && PAYPAL_ENABLED=="no") continue;
     ?>
    <div>
        <?php echo $payment_methods[$i]['display_name']  ?> <br />
        <p>
            <?php include "payment_methods/".$payment_methods[$i]['short_name']."/".$payment_methods[$i]['page_name']."_tmp.php"; ?>
        </p>
    </div><hr />
   <?php } ?>
<?php } ?>


<script language="javascript">
   
     if(querySt("check")=="1")
     {          
            window.setTimeout("StartTimer()", 5000);
            document.getElementById('imgAj').style.display="";
            function StartTimer()
            {     
                var page = "index.php?module=my_balance";
                $.post(page, {  ajax: "yes" , check_b : "yes"},
                function(data){                         
                    if(parseInt(data.mtype)==1)
                    {
                        document.getElementById('imgAj').style.display="none";
                        document.getElementById('spBalance').innerHTML = data.balance;
                        alert('<?php echo BALANCE_UPDATED ?>');
                    }
                    else
                    {
                        window.setTimeout("StartTimer()", 5000);
                    }
               }, "json");
                                   
            }
     }
         

</script>

<script language="javascript">
function UpdateBalance()
{
     var page = "index.php?module=my_balance";
    $.post(page, {  ajax: "yes" , update_b : "yes"},
    function(data){                         
        if(parseInt(data.mtype)==1)
        {            
            document.getElementById('spBalance').innerHTML = data.balance;            
        }       
   }, "json");
}
</script>



