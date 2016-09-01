<?php if(!isset($RUN)) { exit(); } ?>
<script language="javascript">
     window.setTimeout("StartTimer()", 30000);

     function StartTimer()
     {         
      //   jsPostGrid(-1, "updategrid","index.php?module=active_assignments","div_grid");
      //   window.setTimeout("StartTimer()", 30000);
     }
     var _my_id=0;
     function BuyAsg(my_id, cost, balance)
     {                  
         _my_id=my_id;
        document.getElementById('divMSG').innerHTML="";
        document.getElementById('spCost').innerHTML=cost;     
        document.getElementById('spBalance').innerHTML = balance;   
        OpenModal();
     }
     
     function MakePayment()
     {
         var page = "index.php?module=active_assignments";         
            $.post(page, {  ajax: "yes" , make_payment : "yes", my_id : _my_id },
            function(data){                        
                if(parseInt(data.mtype)==1)
                {            
                     CloseModal();
                     grd_show_all(page,"div_grid");
                     if(data.pagejs!="") exec_js(data.pagejs);
                }       
                else
                {
                    document.getElementById('divMSG').innerHTML = '<font color=red>'+data.msg+'</font>'; 
                }
           }, "json");
     }
     
    function CloseModal()
    {
        document.getElementById('tblModal').style.display="none";
    }
    
    function OpenModal()
    {       
            MoveCenterMobile('tblModal');
            document.getElementById('tblModal').style.display="";        
    }

</script>



<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    
   
    
<table id="tblModal" bgcolor="white" style="width:95%;display:none" cellpadding="5" cellspacing="5">
    <tr>
        <td><div id="divHeader"></div></td>
    </tr>
    <tr>
        <td><font color="red"><label id="lblPresTimer"></label></font></div></td>
    </tr>
    <tr>
        <td><div id="divBody">
            
                <p>
      
                    <?php echo COST ?> : <span id="spCost" ></span>&nbsp;<?php echo PAYPAL_CURRENCY ?>  <br />   <br />  
                    <?php echo YOUR_BALANCE ?> : <span id="spBalance" ><?php echo access::UserInfo()->balance ?>&nbsp;<?php echo PAYPAL_CURRENCY ?>  </span><br />   <br />     
                    <div id="divMSG"></div></br>
                    <input type="button" onclick='MakePayment()' class="btn" value="<?php echo MAKE_PAYMENT ?>" />          
                    <input onclick="javascript:window.location.href='index.php?module=my_balance'" type="button" class="btn" value="<?php echo LOAD_BALANCE ?>" />       

                </p>
                
            </div></td>
    </tr>
     <tr>
         <td><a href="#" onclick="CloseModal()" ><?php echo CLOSE ?></a></td>
    </tr>
</table>    
    
<script language="javascript"  type="text/javascript">
      <?php echo $page_js ?>          
</script>    