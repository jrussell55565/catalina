function ShowPreview(ID,pageY,leftC)
{
            var sw = window.screen.width;
            var sh = window.screen.height;

            var top_position=0;

            if (navigator.appName == "Microsoft Internet Explorer")
            {
                y=document.documentElement.scrollTop ;
                mouseY=event.clientY;
                top_position=parseInt(y)+parseInt(mouseY);

            }
            else
            {
                y=window.pageYOffset;
                top_position=parseInt(pageY);
            }
                   

            document.getElementById('test_div').style.position="absolute";
            document.getElementById('test_div').style.top=(y+220)+'px';
            
            document.getElementById('test_div').style.zIndex = "19999";
            
            if(leftC!=-1)
            document.getElementById('test_div').style.left=((sw/2)-300)+'px';

            document.getElementById('test_hr').innerHTML=document.getElementById('templateDiv').innerHTML;
            document.getElementById('test_div').style.display="";

            $.post("modules/qst_previwer.php", {  ajax: "yes", qst_id : ID, preview: "1" },
            function(data){
                 //alert(data);
                 document.getElementById('test_hr').innerHTML=data;
                            var templ_id= document.getElementById('hdnTempID').value;  
                            if(templ_id=="2")
                            {
                                var video_file= document.getElementById('hdnVideoFile').value;
                                var rid= document.getElementById('hdnRID').value;
                                if(video_file!="")
                                {
                                        if(leftC!="-1")
                                        {
                                            flowplayer("p"+rid, "flowplayer/flowplayer-3.2.16.swf" , {
                                                     clip:  {
                                                            autoPlay: false,
                                                            autoBuffering: true
                                                        }
                                                    });
                                        }
                                        else
                                        {
                                            Uppod({m:"video",uid:"p"+rid,file:"video_files/"+video_file,poster:"link"});
                                        }
                                        
                                }
                            }
                 
            });
}

function close_window()
{    
    document.getElementById('test_hr').innerHTML="";
    document.getElementById('test_div').style.display="none";    
}

function MoveCenter(mobjectID)
{
   
            var width = $( window ).width();
            var height = "100px";

            document.getElementById(mobjectID).style.position="absolute";
            document.getElementById(mobjectID).style.top=height;
            document.getElementById(mobjectID).style.left='250px';
     
            
}

function MoveCenterMobile(mobjectID)
{
   
            var width = $( window ).width();
            var height = "100px";

            document.getElementById(mobjectID).style.position="absolute";
            document.getElementById(mobjectID).style.top=height;
            document.getElementById(mobjectID).style.left='1px';
     
            
}

var loaded = false;
function LoadReports(mid)
{    
    if(loaded) return;
    //dvRep        
     $.post("modules/page_reports.php"+window.location.search+"&r_id="+mid, {  ajax: "yes" },
         function(data){     		 
             document.getElementById('dvRep').innerHTML='<br /><br />'+data.rhtml;                              
             exec_js(data.scripts);
        },"json");
    loaded = true;
}

function LoadReport(mid,rep_id,divRep,drowHead,drowMode)
{        
	$.post("modules/page_reports.php"+window.location.search+"&r_id="+mid+"&rep_id="+rep_id+"&drow_head="+drowHead+"&drowMode="+drowMode, {  ajax: "yes" },
         function(data){    		 
             document.getElementById(divRep).innerHTML='<br /><br />'+data.rhtml;        			 
             exec_js(data.scripts);			 
        },"json");
}


function querySt(ji) {
var res = "-1"
hu = window.location.search.substring(1);
gy = hu.split("&");
for (i=0;i<gy.length;i++) {
ft = gy[i].split("=");
if (ft[0] == ji) {
res = ft[1];
}
}
return res;
}


function get_checkbox_post()
{
    var chk_values = "";        
    for(var i=0;i<document.form1.chkAns.length;i++)
    {            
        if(document.form1.chkAns[i].checked)
        {
            chk_values+=encodeURIComponent(document.form1.chkAns[i].value)+";|";
        }
    }        
    return chk_values;
}

function get_radio_post()
{
    var rd_val = "";
    
    for( i = 0; i < document.form1.rdAns.length; i++ )
    {
        if( document.form1.rdAns[i].checked == true )
        rd_val = document.form1.rdAns[i].value;
    }

    return rd_val;
}
//txtMultiAns
function get_free_text_post()
{            
    return encodeURIComponent(document.getElementById('txtFreeId').value)+";|"+encodeURIComponent(document.getElementById('txtFree').value);
}

function get_text_post()
{
    var txt_values = "";
    for(var i=0;i<document.form1.txtMultiAns.length;i++)
    {
      //  if(document.form1.txtMultiAns[i].checked)
      //  {
            txt_values+=encodeURIComponent(document.form1.txtMultiAnsId[i].value)+":|"+encodeURIComponent(document.form1.txtMultiAns[i].value)+";|";
     //   }
    }        
    return txt_values;
}

function get_post_string(qst_type)
{
    
    if(qst_type=="0")
    {
        post_string = get_checkbox_post();
    }
    else if(qst_type=="1")
    {
        post_string = get_radio_post();
    }
    else if(qst_type=="3")
    {
        post_string = get_free_text_post();
    }
      else if(qst_type=="4")
    {
        post_string = get_text_post();
    }
    return post_string;
}


function SearchInTable(search_control, tableid, search_columns) {
    
    
    var value = $.trim($("#"+search_control).val());
   
//table-tblgroups
    $("#"+tableid+" > tbody  > tr").each(function(index) {        
        
        if (index != 0) {

            $row = $(this);

            var find = false;            
            var results = search_columns.split(",");            
            for(var y =0;y<results.length;y++)
            {                                
                var id = $row.find('td:eq('+results[y]+')').text().toLowerCase();;                
                if (id.indexOf(value.toLowerCase()) > 0) 
                {
                    find = true;
                }
            }            

            if (!find) {
                $row.hide();
            }
            else {
               $row.show();
            }
        }
    });
}

function ShowAllTableRows(search_control,tableid)
{
    
    $("#"+search_control).val("");

    $("#"+tableid+" > tbody  > tr").each(function(index) {        

    if (index != 0) {

        $row = $(this);

        $row.show();
    }
    });
}

var submitHandler = function() {
  // do stuff
  return false;
}

function disable_submit()
{
    var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    
     if (keyCode == 13) return false;
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

function onlyDecs(evt) {
    var e = evt
    if(window.event){ // IE
    var charCode = e.keyCode;
    } else if (e.which) { // Safari 4, Firefox 3.0.4
    var charCode = e.which
    }
    
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46)
    return false;
    return true;
}

