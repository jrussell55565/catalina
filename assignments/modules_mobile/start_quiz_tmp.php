<?php if(!isset($RUN)) { exit(); } ?>
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script language=javascript src='tooltip_files/tooltip.js'></script>
<script language="javascript">
    function NextQst(page,question_type, nextPriority,currentPriority, qst_id, finish)
    {
        
        if(finish=="1")
        {
            var conf = confirm('<?php echo ARE_YOU_SURE ?>');
            if(!conf) return false;           
        }
        
        var qst_type = question_type;
        var post_string = get_post_string(qst_type);        

         $.post(page, { ajax:"yes" , data_post: "yes", btnNext : "yes", post_data : post_string , next_priority:nextPriority,current_priority:currentPriority, qst_type: question_type , qstID:qst_id , finish_quiz:finish },
         function(data){             
             document.getElementById('divQst').innerHTML="";
             ShowData(data);           
        });
    }

    function PrevQst(page,question_type, prevPriority)
    {       
         $.post(page, { ajax:"yes" , data_post: "yes", btnPrev : "yes", prev_priority:prevPriority },
         function(data){             
             ShowData(data);          
        });
    }

    
    var _ajax=false;
    function ShowData(data)
    {                     
        data = $.trim(data);
        _ajax = true;
        var data_arr = data.split('[{sep}]');
        data = data_arr[0];        
        document.getElementById('divPager').innerHTML=data_arr[1];        
        
        if(data.substring(0,6)=="error:")
        {            
            data= data.substring(6,data.length);
            document.getElementById('divMsg').innerHTML="<font color=red>"+data+"</font>";
            document.getElementById('divQst').style.display="none";
            document.getElementById('divMsg').style.display="";
            document.getElementById('divTimer').style.display="none";
            document.getElementById('divPager').style.display="none";
            document.getElementById('tblPager').style.display="none";
            document.getElementById('trLine').style.display="none";
            document.getElementById('divSbj').style.display="none";
        }
        else if (data.substring(0,6)=="warni:")
        {            
            data= data.substring(6,data.length);
            document.getElementById('divMsg').innerHTML="<font color=green>"+data+"</font>";
            document.getElementById('divQst').style.display="none";
            document.getElementById('divMsg').style.display="";
            document.getElementById('divTimer').style.display="none";
            document.getElementById('divPager').style.display="none";
            document.getElementById('tblPager').style.display="none";
            document.getElementById('trLine').style.display="none";
            document.getElementById('divSbj').style.display="none";
            StopTimer();
            exec_js(data_arr[1]);
        }
        else
        {            
            document.getElementById('divQst').innerHTML=data;
            document.getElementById('divQst').style.display="";
            document.getElementById('divMsg').style.display="none";
            document.getElementById('divTimer').style.display="";
            document.getElementById('divPager').style.display="";
            document.getElementById('tblPager').style.display="";
            document.getElementById('trLine').style.display="";
            document.getElementById('divSbj').innerHTML = data_arr[3];
            
            if(parseInt(data_arr[9])==1) Init_Timer(parseInt(data_arr[7]),parseInt(data_arr[8]));
            
            LoadPresentation(data_arr[4]);
        }
       

   //    ConfigureTooltips(data_arr[2]);   
       LoadVideo();
    }       
    
    function LoadPresentation(mydata1)
    {                   
        var aMyUTF8Output = base64DecToArr(mydata1);
        var mydata = UTF8ArrToStr(aMyUTF8Output);
        
      //  mydata = base64_decode(mydata);
        if(mydata=="") return;
        var mydata_arr = mydata.split('[{psep}]');
        document.getElementById('divHeader').innerHTML = mydata_arr[0];
        document.getElementById('divBody').innerHTML = mydata_arr[1];

        OpenModal();

        Init_Pres_Timer(mydata_arr[2],0);

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

    function LoadVideo()
    {
        var templ_id= document.getElementById('hdnTempID').value;          
        if(templ_id=="2")
        {            
            var video_file= document.getElementById('hdnVideoFile').value;            
            var rid= document.getElementById('hdnRID').value;            
            if(video_file!="")
            {                                              
                Uppod({m:"video",uid:"p"+rid,file:"video_files/"+video_file,poster:"ссылка"});
            }
        }
    }

    function ShowQst(x,y,id,ran,qz,ao,rndm,aqi,vqi,aid,uid)
    {             
        document.getElementById('divText').innerHTML="<?php echo PLEASE_WAIT ?> ";
        MoveObject(x,y, "tblTip");
        $.post("modules/qst_previwer.php", {qst_preview:"yes", qst_id:id, ran_:ran, qz_:qz,ao_:ao,rndm:rndm,aqi:aqi,vqi:vqi,aid:aid ,uid:uid},
         function(data){
             //alert(data);             
             document.getElementById('divText').innerHTML="&nbsp;&nbsp;"+data;
           // alert(data);
        });
    }

    function LoadQst(page,question_type, priority, qst_id, finish)
    {
        document.getElementById('divText').innerHTML="<?php echo PLEASE_WAIT ?> ";
          $.post(page, { ajax:"yes" ,data_post:"yes", load_priority:priority , load_question:"yes"},
         function(data){             
             ShowData(data);
             HideObject('tblTip');                         
             
        });
    }
    
    function MarkQst(page,qst_id)
    {
     
          $.post(page, { ajax:"yes" ,mark_for_review:"yes", mark_for_review_id:qst_id },
         function(data){             
       
        });
    }

</script>

<form method="post" name="form1">
    <div id="dvCont">
    <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td colspan="2" style="display:<?php echo $timer_display ?>" align="left">
                <div class="c_timer" align="left" id="divTimer" style="display:<?php echo $timer_display ?>">
                    <table width="100%" border="0">
                           <tr>
                                <td  style="width:25%" valign="middle">&nbsp;&nbsp;<?php echo TIME ?> :</td>
                                <td valign="middle"><B><span style="color:red" id="lblTimer"></span></B></td>
                            </tr>
                    </table>                 
                </div>
            </td>        
                            
        </tr>
        <tr id="trLine" style="display:<?php echo $timer_display ?>">
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td valign="top">
                  <div align="center" id="divQst" style="display:<?php echo $app_display ?>">
                        <?php echo $qst_html ?>
                  </div>
            </td>
			</tr>
			<tr>
            <td class="desc_text_bg2" align="center" style="width:150px" valign="top">
                <table id="tblPager" border="1" cellpadding="10" cellspacing="10" style="width:70%;display:<?php echo $pager_display ?>" >
                    <tr>
                        <td align="center">
                            <?php echo QUESTIONS ?>   
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                             <div id="divPager" style="display:<?php echo $pager_display ?>">
                                <?php echo $pager_html ?>
                             </div>  
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo C_ANSWERED ?>- <?php echo ANSWERED ?><br />
                            <?php echo C_NOTANSWERED ?> - <?php echo NOTANSWERED ?><br />
                            <?php echo C_CURRENT ?> - <?php echo CURRENT ?><br />
                            <?php echo C_MARK_REV ?> - <?php echo MARKED_REV ?><br />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="center"><br />
                              <div id="divSbj" style="display:<?php echo $subject_display ?>">
                                  <?php echo $subjects_html ?>
                              </div>
                        </td>
                    </tr>
                </table>

            </td> 
        </tr>
    </table>
    </div>
    &nbsp;
    <div align="left" id="divMsg" style="display:<?php echo $msg_display ?>">
        <p><label class="empty_data"><?php echo $msg_text ?></label></p>
    </div>    
  
</form>

<table id="tblTip" style="display:none" cellspacing="0" cellpadding="0" border=0>
  <tr>
    <td align="right" style="width:1px;height:1px;line-height:5px;"><img border="0" src="tooltip_files/t1.gif" /></td>
  </tr>
	<tr >
		<td align=center>		
			<table  width="900px" cellspacing="0" cellpadding="0" style="border:0;border-width:2;border-color:#ED7C36" border=0 >
				<tr >
					<td style="width:1px;height:1px;line-height:5px;" align=left valign=bottom ><img src="i/tool/tb_l.gif"></img></td>
					<td style="width:900px;height:1px;line-height:5px;" bgcolor="#F9DD93"></td>
					<td style="width:2px;height:1px;line-height:5px;"  align="right" ><img src="i/tool/tb_r.gif"></img></td>
				</tr>
				<tr bgcolor="#F9DD93">
					<td colspan=3 align=center>
                                            <font face="arial" size="2" color="#63665B">&nbsp;&nbsp;<span id="divText"></span></font>
					</td>
				</tr>
				<tr valign="top">
					<td style="width:1px;height:1px;line-height:5px;" align="left"><img src="i/tool/tb_l_b.gif"></img></td>
					<td style="width:400px;height:1px;line-height:5px;" bgcolor="#F9DD93"></td>
					<td style="width:2px;line-height:5px;" align="left" ><img src="i/tool/tb_r_b.gif"></img></td>
				</tr>
			</table>
		</td>
	</tr>	
</table>


<div  style="display:none;width:400px;-webkit-border-radius: 24px;-moz-border-radius: 24px;border-radius: 24px;background:rgba(249,221,147,0.6);-webkit-box-shadow: #B3887D 4px 4px 4px;-moz-box-shadow: #B3887D 4px 4px 4px; box-shadow: #B3887D 4px 4px 4px;"><br><br><span id="divText2"></span><br><br></div>

<br />

<table id="tblModal" bgcolor="white" style="width:95%;display:none" cellpadding="5" cellspacing="5">
    <tr>
        <td><div id="divHeader"></div></td>
    </tr>
    <tr>
        <td><font color="red"><label id="lblPresTimer"></label></font></div></td>
    </tr>
    <tr>
        <td><div id="divBody"></div></td>
    </tr>
     <tr>
         <td><a href="#" onclick="CloseModal()" ><?php echo CLOSE ?></a></td>
    </tr>
</table>


<script language="javascript">
        TimerRunning = false;

        function Init_Timer(quiz_time, seconds) //call the Init function when u need to start the timer
        {            
            mins = quiz_time;
            secs = seconds;
            StopTimer();
            StartTimer();
        }

        function StopTimer() {
            if (TimerRunning)
                clearTimeout(TimerID);
            TimerRunning = false;
        }

        function StartTimer() {

            TimerRunning = true;
            document.getElementById('lblTimer').innerHTML = Pad(mins) + ":" + Pad(secs);
            TimerID = setTimeout("StartTimer()", 1000);

            Check();

            if (mins == 0 && secs == 0)
                StopTimer();

            if (secs == 0) {
                mins--;
                secs = 60;
            }
            secs--;

        }

        function Check() {
            if (mins == 5 && secs == 0) {

            }
            else if (mins == 0 && secs == 0)
            {
                alert("<?php echo TIME_ENDED ?>");
                StopTimer();
                TimerRunning = false;
               // HideTable();        
             //  alert('before reload');
                window.location.reload(false);          
              // alert('after reload');
             //  window.location.href='index.php?module=start_quiz&id=8&tmend=true';
            }
        }

        function Pad(number) //pads the mins/secs with a 0 if its less than 10
        {
            if (number < 10)
                number = 0 + "" + number;
            return number;
        }
</script>

<script language="javascript">
        PresTimerRunning = false;

        function Init_Pres_Timer(pres_time, pres_seconds) //call the Init function when u need to start the timer
        {            
            pres_mins = pres_time;
            pres_secs = pres_seconds;
            StopPresTimer();
            StartPresTimer();
        }

        function StopPresTimer() {
            if (PresTimerRunning)
                clearTimeout(PresTimerID);
            PresTimerRunning = false;
        }

        function StartPresTimer() {

            PresTimerRunning = true;
            document.getElementById('lblPresTimer').innerHTML = Pad(pres_mins) + ":" + Pad(pres_secs);
            PresTimerID = setTimeout("StartPresTimer()", 1000);

            CheckPres();

            if (pres_mins == 0 && pres_secs == 0)
                StopPresTimer();

            if (pres_secs == 0) {
                pres_mins--;
                pres_secs = 60;
            }
            pres_secs--;

        }

        function CheckPres() {
            if (pres_mins == 0 && pres_secs == 0)
            {                
                force_stop_pres_timer();
            }
        }
        
        function force_stop_pres_timer()
        {
                 StopPresTimer();
                PresTimerRunning = false;
             
                CloseModal();
        }
     
</script>

<script language="JavaScript">
function onlyNumbers1(evt)
{        
	var e = event || evt; // for trans-browser compatibility        
	var charCode = e.which || e.keyCode;
 
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;

}

function onlyNumbers(evt) {
var e = evt
if(window.event){ // IE
var charCode = e.keyCode;
} else if (e.which) { // Safari 4, Firefox 3.0.4
var charCode = e.which
}
if (charCode > 31 && (charCode < 48 || charCode > 57))
return false;
return true;
}


</script>

<?php echo $timer_script ?>

<script language="javascript">
                      StartTimerNot();

                      function StartTimerNot()
                      {                                                                            
                        
                        window.setTimeout("StartTimerNot()", 10000);
                        
                        LoadMessages();
                      }
                      var message_count = 0;
                      function LoadMessages()
                      {
                          $.post("utils/nots.php?id=<?php echo $ID ?>", {  ajax: "yes",get_nots:1, id:<?php echo $ID ?> },
                            function(data){
                                document.getElementById('divNots').innerHTML=data;             
                                var msg_count = parseInt($("#hdnCount").val());
                              //  alert(message_count+msg_count);
                                if(message_count!=msg_count)
                                {
                                    var msg = "<?php echo YOUHAVE ?>"+" "+msg_count+" "+"<?php echo NOTIFICATIONS ?>";
                                    $.sticky(msg, {autoclose : 4000, position: "top-right", type: "st-error" }); 
                                    message_count = msg_count;                                              
                                    document.getElementById('lblNotsCount').innerHTML=msg_count
                                }
                            });
                      }
                      
                      function ViewMessage(id)
                      {
                          var body = $("#hdnBody"+id).val();
                          document.getElementById('divNotBody').innerHTML=body;
                           $("#divNots").hide();                           
                           $("#divNotBody").show();   
                      }
                      
                      function ShowNotList()
                      {
                          $("#divNots").show();                           
                          $("#divNotBody").hide();   
                      }
                      
</script>
<script language="javascript">
function ConfigureTooltips(ids)
{   
    
        
    var ids_arr = ids.split(',');
    for(var i=0;i<ids_arr.length;i++)
    {
        var title=$("#s"+ids_arr[i]).attr('title');
        if(title!="")
        {
            var cid=ids_arr[i];                        
            $("#s"+cid).tooltip();         
        }
    }
  
    
}


function RunPageScripts()
{
    <?php echo $additional_scripts ?> 
    if($("#fbshare").val()=="2") setTimeout(function(){document.getElementById('btnFBPost').click()},2000);
}
</script>