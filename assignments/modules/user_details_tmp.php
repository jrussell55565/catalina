<?php if(!isset($RUN)) { exit(); } ?>

<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>

<div class="tabbable"> 
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1R" data-toggle="tab"><?php echo USER_PROFILE ?></a></li>
    <li><a href="#tab2R" data-toggle="tab" onclick="LoadAsgs()" style="display:<?php echo $asg_display ?>" ><?php echo ASSIGNMENTS ?></a></li>
    <li><a href="#tab3R" data-toggle="tab" onclick="LoadHistory()" style="display:<?php echo $ph_display ?>" ><?php echo PAYMENT_HISTORY ?></a></li>    
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1R">            
            

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

<h5><font color="red"><?php echo CURRENT_BALANCE ?> : <span id="spBalance"><?php echo $current_balance ?></span> <?php echo PAYPAL_CURRENCY ?></font><br /><br />
    <form class="form-inline">
        <input class='form-control' type="text" id="txtBal" class="input-small" /><input onclick="ChangeBalance(2)" class="btn green" type="button" id="btnAdd" value="+" /><input onclick="ChangeBalance(1)" class="btn green" type="button" id="btnAdd" value="-" />
    </form>    


<br /><br /><br />
<div class="tabbable">   
  <ul class="nav nav-tabs">
    <?php for($i=0;$i<count($payment_methods);$i++) { 
     $class = $i==0 ? "class=\"active\"" : "";
    ?>
    <li <?php echo $class ?>><a href="#tab<?php echo $payment_methods[$i]['id'] ?>" data-toggle="tab"><?php echo $payment_methods[$i]['display_name'] ?></a></li>    
    <?php } ?>
  </ul>
  <div class="tab-content">
    <?php for($i=0;$i<count($payment_methods);$i++) { 
     $class = $i==0 ? "class=\"tab-pane active\"" : "class=\"tab-pane\"";
    ?>
    <div <?php echo $class ?> id="tab<?php echo $payment_methods[$i]['id'] ?>">
        <p>
            <?php include "payment_methods/".$payment_methods[$i]['short_name']."/".$payment_methods[$i]['page_name']."_admin_tmp.php"; ?>
        </p>
    </div>
   <?php } ?>
  </div>
</div>

  </div>
    <div class="tab-pane" id="tab2R">
        <div id="dvAsg"></div>
    </div>
       <div class="tab-pane" id="tab3R">
        <div id="dvPH"></div>
    </div>
  </div>
</div>


<script language="javascript">
function ChangeBalance(change_type)
{
    var bal = $("#txtBal").val();      
    var page = "index.php?module=user_details&id=<?php echo $id ?>"; 
    $.post(page, {  ajax: "yes" , add_balance : "yes" , change_type : change_type , change_balance : "yes" , bal : bal},
    function(data){                         
        document.getElementById('spBalance').innerHTML = data;
        $("#txtBal").val(""); 
   });
}

function LoadAsgs()
{
    var page = "index.php?module=old_assignments&id=<?php echo $id ?>";   
     $.post(page, {  content : "yes" },
    function(data){                         
         document.getElementById('dvAsg').innerHTML = data;          
   });
}

function LoadHistory()
{
    var page = "index.php?module=payment_history&id=<?php echo $id ?>";   
     $.post(page, {  content : "yes" },
    function(data){                         
         document.getElementById('dvPH').innerHTML = data;          
   });
}

</script>
