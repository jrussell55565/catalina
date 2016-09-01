<?php

class validations {

    var $is_valid = true;
    var $_event;
    var $controls;
    var $modes;
    var $errmsgs;
    var $errors;
    var $regexps;
    var $values;
    var $def_value = "";

    var $i = 0;

    public function validations($event)
    {
        $this->_event = $event;
    }

    public function AddValidator($control_to_validate, $validation_mode, $errmsg, $value_to_compare,$settings="", $regexp="")
    {
        $this->values[$this->i] = $value_to_compare;
        $this->controls[$this->i] = $control_to_validate;
        $this->modes[$this->i] = $validation_mode;
        $this->errmsgs[$this->i] = $errmsg;
        $this->settings[$this->i] = $settings;
	$this->regexps[$this->i] = $regexp;
        $this->i++;
    }
    
    var $removed = array();
    public function RemoveValidator($i)
    {
        $this->removed[] = $i;
    }

    public function Check()
    {        
        if(isset($_POST[$this->_event]))
        {
		
            $this->is_valid = true;            
            for($i=0;$i<count($this->controls);$i++)
            {
                $check_empty = strlen(trim($_POST[$this->controls[$i]]))==0 && $this->settings[$i]=="0" ? false : true ;
                $control_value = isset($_POST[$this->controls[$i]]) ? $_POST[$this->controls[$i]] : $this->def_value;
                
                if(strlen(trim($_POST[$this->controls[$i]]))==1 && trim($control_value)=="")
                {
                    $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                    $this->is_valid = false;
                }
                
                switch ($this->modes[$i])
                {
                                        
                    case "empty":                        
                        if(strlen(trim($control_value))==0)
                        {
                           $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                           $this->is_valid = false;
                        }
                        break ;
                    case "numeric":
                        if(!is_numeric(trim($control_value)))
                        {
                            $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                            $this->is_valid = false;
                        }
                        break;
                    case "notequal":
                        if(trim($control_value)==$this->values[$i])
                        {
                            $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                            $this->is_valid = false;
                        }
                        break;
					case "email":
                        if($check_empty == true && validations::VerifyEmail(trim($control_value))==false)
                        {
                            $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                            $this->is_valid = false;
                        }
                        break;
                    case "an":
                        if(strlen(trim($control_value))==0 || validations::IsAlphanumeric(trim($_POST[$this->controls[$i]]))==false)
                        {
                            $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                            $this->is_valid = false;
                        }
                        break;
                    case "regexp" :
                        if($this->regexps[$i]!="")
                        {
                            if($check_empty == true && !preg_match($this->regexps[$i], $control_value))
                            {
                                $this->errors[$this->controls[$i]] = $this->errmsgs[$i];
                                $this->is_valid = false;
                            }
                        }
                    break;

                }
            }
        }
    }

    public function IsValid()
    {
        $this->Check();
        return $this->is_valid;
    }

    public function GetMsgs()
    {
        $text = "";        
        foreach($this->errors as $key=>$value)
        {
            $text.=$value."\n";
        }
        return $text;
    }

    public function DrowJsArrays()
    {
        $js = "<script language=javascript>";
        $controls="";
        $modes="";
        $errmsgs="";
        $values="";
        $settings="";
	$regexps="";
        
        for($i=0;$i<count($this->controls);$i++)
        {            
            $controls.=",'".$this->controls[$i]."'";
            $modes.=",'".$this->modes[$i]."'";
            $errmsgs.=",'".$this->errmsgs[$i]."'";
            $values.=",'".$this->values[$i]."'";
            $settings.=",'".$this->settings[$i]."'";
            $regexps.=",'".$this->regexps[$i]."'";
        }
        $js.="var controls = new Array(".substr($controls,1).");";
        $js.="var modes = new Array(".substr($modes,1).");";
        $js.="var errmsgs = new Array(".substr($errmsgs,1).");";
        $js.="var values = new Array(".substr($values,1).");";
        $js.="var settings = new Array(".substr($settings,1).");";
		$js.="var regexps = new Array(".substr($regexps,1).");";
        $js.="</script>";
        return $js;
    }
    
    public static function VerifyEmail($email)
    {
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
	{
   		return FALSE;
	}
	return true;
    }

    public static function IsAlphanumeric($inputtxt)
    {
	if (!ctype_alnum($inputtxt))
	{
   		return FALSE;
	}
	return true;
    }

}
?>
