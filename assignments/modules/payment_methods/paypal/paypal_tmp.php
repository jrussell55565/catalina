<?php if(!isset($RUN)) { exit(); } ?>
<div style="display:<?php echo $txt_display ?>" id="divTxt">
<script language="javascript">
    function SaveEmail()
    {
        var email = $("#txtEmail").val();
        var page = 'index.php?module=my_balance';
        $.post(page, {  ajax: "yes" , email : email,savemail:"yes"},
         function(data){                                     
             if(data.mtype=="1" || data.mtype==1)
             {
                 document.getElementById('divTxt').style.display="none";
                 document.getElementById('divBuy').style.display="";
                 document.getElementById('spAC').innerHTML=data.msg;
             }
             else
             {
                 document.getElementById('spMsg').innerHTML=data.msg;
             }
        }, "json");
        
    }
</script>

<?php echo YOUR_PAYPAL_EMAIL ?> : <br />
<input class='form-control input-medium' type="text" id="txtEmail" name="txtEmail" />&nbsp;<span id="spMsg"></span><br />
<input class='btn green' type="button" id="btnAdd" name="btnAdd" value="<?php echo PAYPAL_CONTINUE ?>" class="btn" onclick="SaveEmail()" />

</div>
<div style="display:<?php echo $buy_display ?>" id="divBuy">
<?php echo YOUR_PAYPAL_EMAIL ?> : <span id="spAC"><?php echo $p_account ?></span> <br /><br />
<font face="tahoma"><?php echo LOAD_VIA_PAYPAL ?> </font>
<br />
<br />
<script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=els11@yandex.ru" 
    data-button="buynow" 
    data-name="<?php echo PAYPAL_DATA_NAME ?>"     
    data-currency="<?php echo PAYPAL_CURRENCY ?>" 
    <?php if(PAYPAL_USE_SANDBOX=="yes") echo 'data-env="sandbox"';  ?>
></script>

</div>