<?php

    class webcontrols
    {
        public static function GetOptions($results, $key,$text,$selected,$add_not_selected = true,$break=100000000)
        {
                  
            $options = "";
            if($add_not_selected) $options = "<option value=-1>".NOT_SELECTED."</option>";
            $i = 0;
            while($row=db::fetch($results))
            {                           
                if($break == $i) break;
                    
                $selected_text = "";
                if($selected==$row[$key])
                {
                    $selected_text="selected";
                }                
                $vars = explode(";", $text);
                $res_text = "";
                foreach($vars as $var) 
		{
                      $res_text.=$row[$var]." ";  
                }
                $options.= "<option $selected_text value=\"$row[$key]\">$res_text</option>";
                $i++;
                
                
            }
            return $options;
        }
        public static function GetArrayOptions($myarray, $key,$text,$selected, $add_not_selected = true, $not_selected_text =NOT_SELECTED )
        {
            $options = "";
            if($add_not_selected) $options = "<option value=-1>".$not_selected_text."</option>";
            for($i=0;$i<sizeof($myarray);$i++)
            {
                $row = $myarray[$i];
                $selected_text = "";
                if($selected==$row[$key])
                {
                    $selected_text="selected";
                }                
                $options.= "<option $selected_text value=\"$row[$key]\">$row[$text]</option>";
            }
            return $options;
        }
              
        
        public static function GetSimpleArrayOptions($myarray, $key,$text,$selected, $add_not_selected = true)
        {
            $options = "";
            if($add_not_selected) $options = "<option value=-1>".NOT_SELECTED."</option>";
            for($i=0;$i<sizeof($myarray);$i++)
            {           
                $selected_text = "";
                $key = $key == "key" ? $i : $myarray[$i];
                $text = $text == "key" ? $i : $myarray[$i];
                if($selected==$key)
                {
                    $selected_text="selected";
                }                
                $options.= "<option $selected_text value=\"$key\">$text</option>";
            }
            return $options;
        }
        
        public static function AddOptions($options , $key, $text,$selected)
        {
            $selected_text="";
            if($selected==$key)
            {
                    $selected_text="selected";
            }   
            $options= "<option $selected_text value=\"$key\">$text</option>".$options;
            return $options;
        }

        public static function BuildOptions($options_arr,$selected,$attr="")
        {
            $options = "";            
            foreach($options_arr as $key=>$value)
            {
                $selected_text = "";
                if($selected==$key)
                {
                    $selected_text="selected";
                }
                $options.= "<option $attr $selected_text value=\"$key\">$value</option>";
            }

            return $options;
        }
        
        public static function BuildNumberOptions($start,$count,$selected)
        {
            $options = "";            
            for($i=$start;$i<=$count;$i++)
            {
                $selected_text = "";
                if($selected==$i)
                {
                    $selected_text="selected";
                }
                $options.= "<option $selected_text value=$i>$i</option>";
            }

            return $options;
        }
        
        public static function BuildOptionsByValue($options_arr,$selected)
        {
            $options = "";            
            foreach($options_arr as $key=>$value)
            {
                $selected_text = "";
                if($selected==$value)
                {
                    $selected_text="selected";
                }
                $options.= "<option $selected_text value=\"$value\">$value</option>";
            }

            return $options;
        }

         public static function GetDropDown($drpID,$results, $key,$text,$selected,$add_options,$attrs="")
        {
            $dropdown = "<select id=$drpID name=$drpID $attrs onchange='".$drpID."_onchange()'>";
            $options = "<option value=-1>".NOT_SELECTED."</option>".$add_options;
            while($row=db::fetch($results))
            {
                $selected_text = "";
                if($selected==$row[$key])
                {
                    $selected_text="selected";
                }
                $options.= "<option $selected_text value=\"$row[$key]\">$row[$text]</option>";
            }
            $dropdown = $dropdown.$options."</select>";
            return $dropdown;
        }
        
        
         public static function GetDropDown2($drpID,$results, $key,$text,$selected,$add_options="",$class="",$add_not_selected=true, $translate_array=array())
        {
            $dropdown = "<select class=$class id=$drpID name=$drpID onchange='".$drpID."_onchange()'>";
            $options="";
            if($add_not_selected) $options = "<option value=''>".NOT_SELECTED."</option>";
            $options.=$add_options;
            for($i=0;$i<count($results);$i++)
            {
                $row = $results[$i];
                $selected_text = "";
                if($selected==$row[$key])
                {
                    $selected_text="selected";
                }                           
                $o_text = $row[$text];
                $o_text = isset($translate_array[$o_text]) ? $translate_array[$o_text] : $o_text;
                $options.= "<option $selected_text value=\"$row[$key]\">$o_text</option>";
            }
            $dropdown = $dropdown.$options."</select>";
            return $dropdown;
        }
        
        public static function AddColor($text,$color)
        {
            return "<font color=$color>$text</font>";
        }
        
    }

?>
