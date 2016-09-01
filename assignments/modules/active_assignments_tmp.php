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
     }
     var mypage_js="";
     function MakePayment()
     {
         var page = "index.php?module=active_assignments";         
            $.post(page, {  ajax: "yes" , make_payment : "yes", my_id : _my_id },
            function(data){                        
                if(parseInt(data.mtype)==1)
                {            
                     $('#myModal').modal('hide');
                      if(data.pagejs!="") mypage_js = data.pagejs;
                     grd_show_all(page,"div_grid");                    
                }       
                else
                {
                    document.getElementById('divMSG').innerHTML = '<font color=red>'+data.msg+'</font>'; 
                }
           }, "json");
     }
     
     function grid_post_finished(grid_data)
     {
          if(mypage_js!="") exec_js(mypage_js);
          mypage_js="";
     }

</script>



<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    

    
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       <h3 id="myModalLabel"><?php echo PAYMENT_PROCESS ?></h3>
      </div>
      <div class="modal-body">
        <p>
      
          <?php echo COST ?> : <span id="spCost" ></span>&nbsp;<?php echo PAYPAL_CURRENCY ?>  <br />   <br />  
          <?php echo YOUR_BALANCE ?> : <span id="spBalance" ><?php echo access::UserInfo()->balance ?>&nbsp;<?php echo PAYPAL_CURRENCY ?>  </span><br />   <br />     
          <div id="divMSG"></div></br>
          <input type="button" onclick='MakePayment()' class="btn green" value="<?php echo MAKE_PAYMENT ?>" />          
          <input onclick="javascript:window.location.href='index.php?module=my_balance'" type="button" class="btn green" value="<?php echo LOAD_BALANCE ?>" />       
      
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo CLOSE ?></button>        
      </div>
    </div>
  </div>
</div>
    
<script language="javascript"  type="text/javascript">
      <?php echo $page_js ?>          
</script>    