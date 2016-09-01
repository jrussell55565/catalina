<?php 

class d_controls
{
	var $page_id;
	var $db;	
	var $chtml;
	
	var $control_list = array();
	var $pages_row;
	var $edit_mode = false;
	var $edit_id = -1;
	//var $edit_column = "id";
	
	var $current_user_id = -1;
	var $current_branch_id = -1;
	//var $insert_mode = 2 ; // table based =1 , 2 = control based
	
	var $save_event = "btnSave";
	var $edit_rows;	
	var $val;
        var $insert_user_info =true;
        var $tx_id = -1;
        
        var $after_save_event = "";
	
	var $on_save_function = "";
	var $auto_commit_transaction = true;
        
        var $edit_query = "";
        
        var $before_insert_override = "";

	function d_controls($db,$page_id,$val, $auto_detect_edit=true)
	{
                if($auto_detect_edit==true)
                {
                    $this->edit_mode = isset($_GET['id']) ? true : false;
                    $this->edit_id = isset($_GET['id']) ? $_GET['id'] : -1;
                }
		$this->db = $db;
		$this->page_id = $page_id;		
		$this->val=$val;
	}
	
	private function LoadEditValues()
	{
		if($this->pages_row[0]['page_type'] == "1")
		{                    
			$table_name = $this->pages_row[0]['page_type_value'];       
                        if($this->edit_query=="") $this->edit_query = orm::GetSelectQuery($table_name,array(),array("tx_id"=>$this->edit_id), "");                        
			$this->edit_rows = $this->db->query_as_array($this->edit_query);                       
		}
		else
		{
			$this->edit_rows = $this->db->query_as_array(orm::GetSelectQuery("d_values",array(),array("tx_id"=>$this->edit_id), ""));
		}
                if(count($this->edit_rows)==0) util::redirect ($this->ReplaceUrl($this->pages_row[0]['success_url'])) ;
	}
	
	public function DrowHtml($drow_buttons=false)
	{	
                global $D_CONTROLS;
		$this->pages_row = $this->db->query_as_array(d_queries::get_page($this->page_id));		
			
		if($this->edit_mode)
		{
			$this->LoadEditValues();
		}
		
		$html = "<table class=d_main_table border=0>";
		$this->_rows= $rows = $this->db->query_as_array(d_queries::get_controls($this->page_id));		
		for($i=0;$i<sizeof($rows);$i++)
		{		
			$row= $rows[$i];			
                        $control_text = $row['c_text'];
                        if(isset($D_CONTROLS[$control_text])) $control_text = $D_CONTROLS[$control_text];       
                        
                        $display = "1";
                        $display_html = "";
                        if($row['display_fm']!="") $display = eval($row['display_fm']);
                      
                        if($display=="2") $display_html = "none";
                        
			$html.="<tr style='display:$display_html'><td  class='".$row['t_style_name']."'>".$control_text." : </td><td>".$this->get_control_html($row)."</td></tr>";					
		}
				
		if($drow_buttons==true)
		{
			$html.="<tr><td colspan=2 align=left><br /><input style='display:none' class='btn green' id=btnWait type=button value='".WAIT."' /><input class='btn green' type=submit class=btn name=btnSave id=btnSave value='".SAVE."' onclick='return checkform();'>&nbsp;<input class='btn green' onclick='javascript:history.back(1)' type=button value='".CANCEL."' ></td></tr>";
		}
		$html.="</table>";
		$this->chtml = $html;
		
	}

	
	private function GetValueFromDB($row)
	{
		$control_value="";
		if($this->pages_row[0]['page_type'] == "1")
		{
			$control_value = $this->edit_rows[0][$row['c_key']];			
		}
		else
		{
			$control_value = db::Select($this->edit_rows,"control_id",$row['id'],true,"control_value");
		}
		return $control_value;
	}
	
	private function get_default_value($row)
	{
		$control_value = "";
		if($row['def_value_type']=="1")
		{
			$control_value = $row['def_value_text'];
		}
		else if($row['def_value_type']=="2")
		{
			eval('$control_value = '.$row['def_value_text']);
		}
		
		return $control_value;
	}
              
	
	private function get_control_html($row)
	{
		$control_list = array();
		$html = "";
		$c_type = $row['c_type'];
		if(!$this->edit_mode) $control_value = $this->get_default_value($row);
		if($c_type=="1") // if dropdown
		{			
			$control_id = "drp".$row['id'];
			if($this->edit_mode) $control_value = $this->GetValueFromDB($row); 
                        //orm::GetSelectQuery("d_dics", array(), array("dic_id"=>$row['c_type_param1']), "position")
			$results = $this->db->query_as_array(d_queries::get_dics($row['c_type_param1']));
                        $add_not_selected=true;
                        if($row['c_type_param2']=="1") $add_not_selected=false;
                        $translate_array = array();
                        if(count($results)>0)
                        {
                            if($results[0]['translate_array']) 
                            {
                                global ${$results[0]['translate_array']};
                                $translate_array = ${$results[0]['translate_array']};                                
                            }
                        }
			$html.= webcontrols::GetDropDown2($control_id, $results , "id", "value_text",$control_value,"",$row['c_style_name'],$add_not_selected,$translate_array);
			$this->control_list[$control_id] = $row; 
		}
		else if($c_type=="2") // if textbox 
		{                    
			$control_id = "txt".$row['id'];
			if($this->edit_mode) $control_value = $this->GetValueFromDB($row); 
			$html.="<input class='".$row['c_style_name']."' type=text id='".$control_id."' name='".$control_id."' value='".htmlspecialchars($control_value)."'  />";
			$this->control_list[$control_id ] = $row;
		}
		else if($c_type=="3") // if textarea 
		{
			$control_id = "txta".$row['id'];
			if($this->edit_mode) $control_value = $this->GetValueFromDB($row); 
			$html.="<textarea class='".$row['c_style_name']."' id='".$control_id ."' name='".$control_id ."' >".htmlspecialchars($control_value)."</textarea>";
			$this->control_list[$control_id ] = $row;
		}
		else if($c_type=="4") // if checkbox 
		{
			$control_id = "chk".$row['id'];
			if($this->edit_mode) $control_value = $this->GetValueFromDB($row) == "1" ? "checked" : ""; 
			$html.="<input class='".$row['c_style_name']."' type=checkbox id='".$control_id."' name='".$control_id."' $control_value />";
			$this->control_list[$control_id] = $row;
		}
		else if($c_type=="5") // if label 
		{
			$html.="<label class='".$row['c_style_name']."' id='lbl".$row['id']."' name='lbl".$row['id']."'  />";
		}
                else if($c_type=="6") // if file 
                {
                        $control_id = "upl".$row['id'];
                        $file_list="";
                        if($this->edit_mode) $file_list = $this->GetFileList($row);
                       // $html.="<input type=button id=$control_id onmouseover='upload_my_file(\"$control_id\")' value='Select file' />";
                        $html = $file_list."<table cellspacing=1 cellpadding=1 id='tbl".$control_id."' class='desc_text'><tr><td><input class='".$row['c_style_name']."' type=file name=file".$control_id."1 id=file".$control_id."1 /></td></tr></table>
                            &nbsp;<input style='width:25px' type='button' value=' + ' onclick=\"addRow('tbl".$control_id."','".$control_id."')\" />
                        <input style='width:25px' type='button' value=' - ' onclick=\"deleteRow('tbl".$control_id."')\" />";
                        $this->control_list[$control_id] = $row;
                }
		
		if($c_type!=6) $this->AddValidator($row,$control_id);
		
		//$this->control_list = $control_list;
		
		return $html;
	}
        
        private function GetFileList($row)
        {
            $file_list = "<table>";
            $rows = $this->db->query(orm::GetSelectQuery("d_files", array(), array("tx_id"=>$this->edit_id), "id"));
            while($row = db::fetch($rows))
            {
                $file_list.="<tr><td class=remove_file><a href='d_download.php?id=".$row["id"]."'>".$row["file_name"]."</a>&nbsp;<input type='checkbox' name='chkRmv[]' value='".$row['id']."'>".REMOVE."</td></tr>";
            }
            $file_list.="</table>";
            return $file_list;
        }
	
	private function AddValidator($row,$control_id)
	{
		if($row['val_id'] == "") return;
		
                global $D_CONTROLS_VAL;                
		$error_msg = $row['val_err_msg'];
		if($error_msg=="") $error_msg = $row['c_text'].' - please, enter in correct format ';
                
                if(isset($D_CONTROLS_VAL[$row['c_text']])) $error_msg = $D_CONTROLS_VAL[$row['c_text']];
                
		$this->val->AddValidator($control_id, "regexp", $error_msg,"",$row['check_empty'],$row['regexp']);
	}
	
	public function IsSaveBtnClicked()
	{
		if(isset($_POST[$this->save_event])) return true;
		return false;
	}
        
        public function ReplaceUrl($url)
        {
            foreach($_GET as $key => $value)
            {
                $url = str_replace("[$key]", $value, $url);
            }
            return $url;
        }
	
	public function SaveData($check_btn_click=true, $redirect=true)
	{				
		if($check_btn_click)
		{
			if(!$this->IsSaveBtnClicked())
			{
				return;
			}
		}
              
                if(!$this->val->IsValid()) return;
		
		$row = $this->pages_row[0];				
		
		try
                {                    
                        if(!$this->edit_mode)
                        {                    
                            $sql = orm::GetInsertQuery("d_txs", array("page_id"=>$this->page_id ,"branch_id"=>$this->current_branch_id, "inserted_by"=>$this->current_user_id, "inserted_date"=>util::Now()));			
                            $last_id = $this->db->insert_query($sql);                            
                        }
                        else
                        {
                            $sql = orm::GetUpdateQuery("d_txs", array("updated_by"=>$this->current_user_id, "updated_date"=>util::Now()), array("id"=>$this->edit_id));
                            $this->db->query($sql);
                            $last_id = $this->edit_id; 
                        }
                        $this->tx_id = $last_id;
                        
			if($row['page_type']=="1")
			{
				$this->SaveTableBasedValues();                                
			}
			else
			{
				$this->SaveControlBasedValues();
			}
			
			if($this->auto_commit_transaction) $this->db->commit();
                                                
		}
                catch(Exception $e)
                {
                    //echo $e->getMessage();
                    if($this->auto_commit_transaction) $this->db->rollback();
                }
                                
                if($this->after_save_event!="") 
                {
                    $override = $this->after_save_event;
                    $override();
                }
                
                if($redirect) util::redirect($this->ReplaceUrl($row['success_url']));
		
	}
	
	private function SaveTableBasedValues()
	{
            
		$table_name = $this->pages_row[0]['page_type_value'];
		
		foreach($this->control_list as $key=>$value)
                {                    
                        if($value['c_type']==6) $this->SaveAttachments($key,$value);
                        else {
			$control_value = $this->get_control_value($key,$value);
			$columns_arr[$value['c_key']] = $control_value;	
                        }
		}
		
		$sql = "";		
                
                /*
                if($this->insert_user_info)
                {
                     $key = $this->edit_mode ? "updated" : "inserted" ;
                     $columns_arr[$key.'_by'] = $this->current_user_id;
                     $columns_arr[$key.'_date'] = util::Now();
                }
                */
		if(!$this->edit_mode)
		{                    
                    if($this->before_insert_override!="")
                    {
                        $before_insert_override = $this->before_insert_override;
                        $columns_arr = $before_insert_override($columns_arr);
                    }
                    $columns_arr['tx_id'] = $this->tx_id;
                    $sql = orm::GetInsertQuery($table_name,$columns_arr);
		}
		else
		{       
                    $sql = orm::GetUpdateQuery($table_name,$columns_arr, array("tx_id"=>$this->tx_id));
		}                
		
		$this->db->query($sql);
		
	}
	
	private function SaveControlBasedValues()
	{            
				
		if($this->edit_mode)
		{						
			$this->db->query(orm::GetDeleteQuery("d_values",array("tx_id"=>$this->tx_id)));			                     
		}	                
		
		foreach($this->control_list as $key=>$value)
                {
			//$control_value = trim($_POST[$key]);
			//$columns_arr[$value['c_key']] = $control_value;	
			
                      //  if($row['c_type']==6) $this->SaveAttachments($key,$value);
			$control_value = $this->get_control_value($key,$value);
			$sql = orm::GetInsertQuery("d_values", array("control_id"=>$value['id'],"control_value"=>$control_value, "tx_id"=>$this->tx_id));
		
			$this->db->query($sql);
		}
		
		
	}
        
        private function SaveAttachments($key,$row)
        {
            if(isset($_POST['chkRmv']))
            {
                $chkRmv = $_POST['chkRmv'];
                for($i=0;$i<sizeof($chkRmv);$i++)
                {
                    $results = $this->db->query(orm::GetSelectQuery("d_files", array(), array("tx_id"=>$this->tx_id, "id"=>$chkRmv[$i]), ""));
                    if($this->db->num_rows($results)>0)
                    {
                        $row = db::fetch($results);                        
                        @unlink("uploads".DIRECTORY_SEPARATOR."d_controls".DIRECTORY_SEPARATOR.$row["real_file_name"]);                        
                        $this->db->query(orm::GetDeleteQuery("d_files", array("id"=>$chkRmv[$i])));
                    }
                }
            }
            for($i=1;;$i++)
            {
                
                $file_name = 'fileupl'.$row['id'].$i;
                
                if(!isset($_FILES[$file_name]['tmp_name'])) break;
               
                $f_name = $_FILES[$file_name]['name'];
             //   $ext = pathinfo($f_name, PATHINFO_EXTENSION);                
            //    $real_file_name = md5($f_name).".".$ext;
                if($f_name!="")
                {
                    $real_file_name = md5($f_name). md5(util::Guid());        
                    move_uploaded_file($_FILES[$file_name]['tmp_name'], 'uploads/d_controls/'.$real_file_name);                
                    $this->db->query(orm::GetInsertQuery("d_files", array("tx_id"=>$this->tx_id, "file_name"=>$f_name, "real_file_name"=>$real_file_name)));
                }
            }
           
        }
	
	private function get_control_value($key, $row)
	{
		if($row['c_type'] == "1" || $row['c_type'] == "2" || $row['c_type'] == "3")
		{
			$control_value = trim($_POST[$key]);
		}
		else if($row['c_type'] == "4")
		{
			$control_value = isset($_POST[$key]) == true ? "1" : "0";
		}
                //else if($row['c_type'] == "6")
		//{
		//	$control_value = isset($_POST[$key]) == true ? "1" : "0";
		//}
		return $control_value;
	}

}

class d_queries
{

	public static function get_controls($page_id)
	{
		$sql = "SELECT c.* , v.regexp
				FROM d_controls c
				INNER JOIN d_page_control_xreff pcx ON pcx.control_id = c.id
				INNER JOIN d_pages p ON p.id = pcx.page_id
				LEFT JOIN d_vals v ON v.id = c.val_id
				WHERE p.id = $page_id and c.enabled=1 order by c.position ";
		return $sql;
	}
	
	public static function get_page($page_id)
	{
		$sql = "SELECT * FROM d_pages p where p.id = $page_id";
		return $sql;
	}
        
        public static function get_dics($dic_id)
        {
            $sql = "select d.*,dn.translate_array from d_dics d 
                    inner join d_dic_names dn on d.dic_id=dn.id
                    where d.dic_id=$dic_id order by d.position" ;
            return $sql;
        }

}

?>