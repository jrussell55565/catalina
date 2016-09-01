function jsPostGrid(evt_arg,evt_mode, page,control_name)
{
    
    $("#hdnEventArgs").val(evt_arg);
    $("#hdnEventMode").val(evt_mode); 
    var _sort_by = $("#hdnSortBy").val();
    var _sort_direc = $("#hdnSortDirec").val();
 
    $.post(page, { hdnEventArgs: evt_arg, hdnEventMode: evt_mode, ajax: "yes" , mypage : page,control_name:control_name, sort_by:_sort_by,sort_direc:_sort_direc },
         function(data){                         
             document.getElementById(control_name).innerHTML=data;
             try{
                 grid_post_finished(data);
             }catch(e){}
        });
}

function jsProcessCommand(id,page,control_name,command_name,confirm_msg)
{        
  if(confirm_msg!="")
  {
      if(confirm(confirm_msg))
      {          
          jsPostGrid(id,command_name,page,control_name);
      }
  }
  else jsPostGrid(id,command_name,page,control_name);
  
}

function jsProcessDelete(msg,id,page,control_name)
{        
    if(confirm(msg))
        {
            jsPostGrid(id,"delete",page,control_name);
        }
}

function jsSortGrid(sort_by,page,control_name)
{            
    if($("#hdnSortBy").val()==sort_by)
    {
        $("#hdnSortDirec").val()=="desc" ? $("#hdnSortDirec").val("asc") : $("#hdnSortDirec").val("desc");
    }
    else $("#hdnSortDirec").val("asc");
    $("#hdnSortBy").val(sort_by);
   
    jsPostGrid(-1,"sort",page,control_name);
}

function grd_select_all(theForm, cName, status) 
{                
	for (i=0,n=theForm.elements.length;i<n;i++)
	{                
            if (theForm.elements[i].className.indexOf(cName) !=-1) 
            {		                  
                if(theForm.elements[i].checked) theForm.elements[i].checked ="" ;
                 else theForm.elements[i].checked ="checked";
            }
	}
}

function grd_get_checkboxes(theForm, cName, status) 
{       
    var arr_checked = new Array();
    var y = 0;
    for (i=0,n=theForm.elements.length;i<n;i++)
    {                     
        if (theForm.elements[i].className.indexOf(cName) !=-1) 
        {		                          
            if(theForm.elements[i].checked) 
            {
                arr_checked[y] = theForm.elements[i].value;                            
                y++;                            
            }

        }
    }
    return arr_checked;
}

function grd_search(page,control_name, senddata)
{
        var str = "";
        var arr = senddata.split(',');
        for(var i =0;i<arr.length;i++)
        {            
            str+="&"+arr[i]+"="+encodeURIComponent($("#"+arr[i]).val());
        }
	
        if(str.length>0) str = str.substring(1);
                
        var _sort_by = $("#hdnSortBy").val();
        var _sort_direc = $("#hdnSortDirec").val();
    
    	 $.post(page, { hdnEventArgs: "-1", hdnEventMode: "search", ajax: "yes" , search_grd : str, mypage : page, sort_by:_sort_by,sort_direc:_sort_direc },
         function(data){                  
             document.getElementById(control_name).innerHTML=data;
        });
}


function grd_show_all(page,control_name,senddata)
{
	jsPostGrid(-1,"grd_show_all",page,control_name);

        var arr = senddata.split(',');
        for(var i =0;i<arr.length;i++)
        {            
            $("#"+arr[i]).val("");
        }
}

function grd_go_to_page(page_number, page, control_name)
{    
	jsPostGrid(page_number,"pager",page,control_name);
}

function LoadRowInfo(id,page,identity)
{                    
    document.getElementById('divRowInfo'+identity).innerHTML="<img src='style/i/ajax_loader.gif' />";    
    $.post(page, {ajax: "yes" , mypage : page, info_id : id , identity : identity},
         function(data){                    
             document.getElementById('divRowInfo'+identity).innerHTML=data;
        });
}
