function upload_my_file(id) {
    
    var uploader = document.getElementById(id);

    upclick(
     {
         element: uploader,
         action: '../AjaxHandlers/FileUploadHandler.ashx',
         onstart:
        function (filename) {
            alert('da11');
            //alert('Start upload: ' + filename);
           // $("#t" + id).val(filename);
           // document.getElementById("l" + id).innerHTML = "Yüklənir ...";
        },
         oncomplete:
        function (response_data) {
            //$("#t" + id).val(response_data);
           // document.getElementById("l" + id).innerHTML = "<a href='../AjaxHandlers/FileUploadHandler.ashx?m=d&file_name=" + response_data + "'>Əlavə olunmuş fayl</a>";
        }
     });
}

        var counters = new Array();

        function addRow(tableID, fileID ) {

            if (typeof counters[tableID] === 'undefined') {
                counters[tableID] = 1;
            }
            counters[tableID]++;            			
                        
            var table = document.getElementById(tableID);
  
            var rowCount = table.rows.length;            
            var row = table.insertRow(rowCount);

            var colCount = table.rows[0].cells.length;
            
            for(var i=0; i<colCount; i++) {

                var newcell = row.insertCell(i);

                newcell.innerHTML = table.rows[0].cells[i].innerHTML.replace(new RegExp("1",'g'),counters[tableID]);
           
                switch(newcell.childNodes[0].type) {
                   
                    case "text":                            
                            newcell.childNodes[0].value = "";
                            var txtname=newcell.childNodes[0].name;
                            var newname=txtname.substr(0,txtname.length-1)+counters[tableID];
                            newcell.childNodes[0].id=newname;
                            newcell.childNodes[0].name=newname;
                            break;
                   case "file":                                                        
                            newcell.childNodes[0].id="file"+fileID+counters[tableID];                          
                            newcell.childNodes[0].name="file"+fileID+counters[tableID];
                           // alert(newcell.childNodes[0].name);
                            break;       
                    case "checkbox":					
                            newcell.childNodes[0].checked = false;
                            newcell.childNodes[0].id="chkMulti"+counters[tableID];
                            newcell.childNodes[0].name="chkMulti"+counters[tableID];
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            newcell.childNodes[0].value=counters[tableID];
                            break;
                    
                }
            }
            
        }

        function deleteRow(tableID) {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
            if(rowCount==1)
                {
                    alert('Cannot delete last one');
                    return;
                }
            table.deleteRow(rowCount-1);
            counters[tableID]--;
        }
        
function checkform()
{    
    var validated = validate();
    if(validated==true) 
    {
        document.getElementById('btnSave').style.display="none";
        document.getElementById('btnWait').style.display="";
        return true;
    }
    else 
    {
        document.getElementById('btnSave').style.display="";
        document.getElementById('btnWait').style.display="none";
        return false;
    }
}