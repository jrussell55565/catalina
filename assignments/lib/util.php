<?php
	
    class util
    {
        public static function Now()
        {
            return date('Y-m-d H:i:s');
        }
        
        public static function redirect($url)
        {
            if(isset($_GET['r']))
            {                
                $operator = strpos($url, "?")=== false ? "?" : "&";
                $url = $url.$operator."u=".urlencode(util::GetCurrentUrl());                
            }
            header("location: $url");
            exit();
        }
        
        public static function GetID($invalid_location="login.php")
        {
            if(!is_numeric($_GET["id"]))
            {
                util::redirect(" $invalid_location");              
                exit();
            }
            return $_GET["id"];
        }

         public static function GetCurrentUrl($add_url="")
        {
             $pageURL = 'http';             
             if(isset($_SERVER["HTTPS"]))
             {
             if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            // $pageURL .= "://";
             }
             if ($_SERVER["SERVER_PORT"] != "80") { //$_SERVER["SERVER_PORT"]
              $pageURL .= '://'.$_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
             } else {                 
              $pageURL .= '://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
             }
             return $pageURL.$add_url;
        }
        
         public static function GetCurrentPage()
        {
            return $_SERVER["REQUEST_URI"];
        }
        
        public static function isMobile2() {
            //return true;
            return preg_match("/(IEMobile|iPhone|BlackBerry|Android|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        }
        
         public static function isMobile() {
            $detect = new Mobile_Detect;
          
            return $detect->isMobile()==true ? "true" : "false";
        }
        
        public static function new_guid()
        {
            $guid = util::GUID().date('YmdHi0');
            return $guid;
        }
        
        public static function get_arr_value($arr,$value)
        {
            $res = $value;
            if(isset($arr[$value])) $res = $arr[$value];
            return $res;
        }
        
        public static function get_value($value1,$value2)
        {
            if(isset($value1)) return $value1;
            else return $value2;
        }
        
        public static function startsWith($haystack, $needle)
        {
             $length = strlen($needle);
             return (substr($haystack, 0, $length) === $needle);
        }

        public static function endsWith($haystack, $needle)
        {
            $length = strlen($needle);
            if ($length == 0) {
                return true;
            }

            return (substr($haystack, -$length) === $needle);
        }
        public static function get_width($pc,$mob)
        {
            global $mobile;            
            if($mobile==false)
            {
                return "width:".$pc."px";
            }
            else 
            {
                 return "width:".$mob."px";
            }
        }
        
        public static function get_fb_button()
        {
            return "<img style='width:15px' src='style/i/facebook_small.png' />&nbsp;".POST_ON_WALL;
        }
        
        public static function get_asg_image($img)
        {
            if($img=="")
            {
                $img = "no.jpg";
            }
            
            return WEB_SITE_URL."asg_images/".$img;
        }
        
        public static function get_url_vars()
        {
            $str="";
            foreach($_GET as $key=>$value)
            {
                $str.="&$key=$value";
            }            
            return $str;
        }
        
        public static function GetKeyID($key,$invalid_location="login.php")
        {
            if(!isset($_GET[$key])) util::redirect(" $invalid_location");
                        
            if(!is_numeric($_GET[$key]))
            {
                util::redirect(" $invalid_location");
            }
            return $_GET[$key];
        }
        public static function replace_n($text,$add_space=false)
        {
            $replace = "<br />";
            if($add_space==true) $replace = "<br />&nbsp;";
            $text = str_replace("\n", $replace, $text);
            return $text;
        }
        public static function GetPost($control_id, $default_value)
        {      
            if(isset($_POST[$control_id])) return trim($_POST[$control_id]);
            else return $default_value;
        }
        
        public static function post($control_id,$default_value="0")
        {
            return util::GetPost($control_id, $default_value);
        }

        public static function GetData($control_id,$default_value="")
        {
            return htmlspecialchars(util::GetHtmlData($control_id,$default_value=""));
        }

         public static function GetPostData($control_id,$default_value="")
        {
            if(isset($_POST[$control_id]))
            {
                return htmlspecialchars($_POST[$control_id]);
            }
            else return $default_value;
        }
        
        public static function GetShortText($text, $symbols=60)
        {
            if(strlen($text)>$symbols) $text = substr($text,0, $symbols-1)." ...";
            return $text;
        }
        
        public static function GetWords($text, $count)
        {
            $mytext = "";
            $text = str_replace('&nbsp;',' ',$text);
            $words_arr = explode(' ', $text);
            for($i=0;$i<count($words_arr);$i++)
            {                
                if($i==$count) 
                {                    
                    if($count!=count($words_arr)) $mytext.="...";
                    break;
                }
                $mytext.=$words_arr[$i]." ";
            }
            return $mytext;
        }
        
        public static function SendMail($to, $cc , $subject, $body)
        {
            try
            {
                $m= new Mail; 
                $m->From(MAIL_FROM ); 
                $m->To($to);
                if(count($cc)>0)
                {
                   $m->Cc($cc); 
                }
                $m->Subject( $subject);
                $m->Body( $body);    	
                $m->Priority(3) ;    
                //$m->Attach( "asd.gif","", "image/gif" ) ;

                if(MAIL_USE_SMTP=="yes")
                {
                        $m->smtp_on(MAIL_SERVER, MAIL_USER_NAME, MAIL_PASSWORD ) ;    
                }
                $m->Send(); 

            }
            catch(Exception $e)
            {
            //    echo $e->getMessage();
            //    $db->rollback();
            }
        }
        
        public static function GetHtmlData($control_id,$default_value="")
        {
            global $$control_id;
            if(!isset($$control_id))
            {
                return $default_value;
            }
            else
            {
                return $$control_id;
            }

        }
        
        public static function translate_array($arr)
        {
            $tarr ;
            foreach($arr as $key=>$value)
            {      
                $tarr[$value] = $key;
            }
            return $tarr;
        }

	public static function GUID()
	{
    		if (function_exists('com_create_guid') === true)
    		{
        		return trim(com_create_guid(), '{}');
    		}

   	 	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), 	mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
        
        public static function GetInt($id,$invalid_location="login.php")
        {
            if(!is_numeric($id))
            {
                util::redirect(" $invalid_location");              
                exit();
            }
            return $id;
        }
        
        public static function TestLog($content,$file="1")
        {       
            $myfile = fopen("C:\\wamp\\www\\mylogs\\".$file.".txt", "w");        
            fwrite($myfile, $content);        
            fclose($myfile);
        }
		

       public static function getFormattedSQL($sql_raw)
        {
         if( empty($sql_raw) || !is_string($sql_raw) )
         {
          return false;
         }

         $sql_reserved_all = array (
             'ACCESSIBLE', 'ACTION', 'ADD', 'AFTER', 'AGAINST', 'AGGREGATE', 'ALGORITHM', 'ALL', 'ALTER', 'ANALYSE', 'ANALYZE', 'AND', 'AS', 'ASC',
             'AUTOCOMMIT', 'AUTO_INCREMENT', 'AVG_ROW_LENGTH', 'BACKUP', 'BEGIN', 'BETWEEN', 'BINLOG', 'BOTH', 'BY', 'CASCADE', 'CASE', 'CHANGE', 'CHANGED',
             'CHARSET', 'CHECK', 'CHECKSUM', 'COLLATE', 'COLLATION', 'COLUMN', 'COLUMNS', 'COMMENT', 'COMMIT', 'COMMITTED', 'COMPRESSED', 'CONCURRENT',
             'CONSTRAINT', 'CONTAINS', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_TIMESTAMP', 'DATABASE', 'DATABASES', 'DAY', 'DAY_HOUR', 'DAY_MINUTE',
             'DAY_SECOND', 'DEFINER', 'DELAYED', 'DELAY_KEY_WRITE', 'DELETE', 'DESC', 'DESCRIBE', 'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV',
             'DO', 'DROP', 'DUMPFILE', 'DUPLICATE', 'DYNAMIC', 'ELSE', 'ENCLOSED', 'END', 'ENGINE', 'ENGINES', 'ESCAPE', 'ESCAPED', 'EVENTS', 'EXECUTE',
             'EXISTS', 'EXPLAIN', 'EXTENDED', 'FAST', 'FIELDS', 'FILE', 'FIRST', 'FIXED', 'FLUSH', 'FOR', 'FORCE', 'FOREIGN', 'FROM', 'FULL', 'FULLTEXT',
             'FUNCTION', 'GEMINI', 'GEMINI_SPIN_RETRIES', 'GLOBAL', 'GRANT', 'GRANTS', 'GROUP', 'HAVING', 'HEAP', 'HIGH_PRIORITY', 'HOSTS', 'HOUR', 'HOUR_MINUTE',
             'HOUR_SECOND', 'IDENTIFIED', 'IF', 'IGNORE', 'IN', 'INDEX', 'INDEXES', 'INFILE', 'INNER', 'INSERT', 'INSERT_ID', 'INSERT_METHOD', 'INTERVAL',
             'INTO', 'INVOKER', 'IS', 'ISOLATION', 'JOIN', 'KEY', 'KEYS', 'KILL', 'LAST_INSERT_ID', 'LEADING', 'LEFT', 'LEVEL', 'LIKE', 'LIMIT', 'LINEAR',
             'LINES', 'LOAD', 'LOCAL', 'LOCK', 'LOCKS', 'LOGS', 'LOW_PRIORITY', 'MARIA', 'MASTER', 'MASTER_CONNECT_RETRY', 'MASTER_HOST', 'MASTER_LOG_FILE',
             'MASTER_LOG_POS', 'MASTER_PASSWORD', 'MASTER_PORT', 'MASTER_USER', 'MATCH', 'MAX_CONNECTIONS_PER_HOUR', 'MAX_QUERIES_PER_HOUR',
             'MAX_ROWS', 'MAX_UPDATES_PER_HOUR', 'MAX_USER_CONNECTIONS', 'MEDIUM', 'MERGE', 'MINUTE', 'MINUTE_SECOND', 'MIN_ROWS', 'MODE', 'MODIFY',
             'MONTH', 'MRG_MYISAM', 'MYISAM', 'NAMES', 'NATURAL', 'NOT', 'NULL', 'OFFSET', 'ON', 'OPEN', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR',
             'ORDER', 'OUTER', 'OUTFILE', 'PACK_KEYS', 'PAGE', 'PARTIAL', 'PARTITION', 'PARTITIONS', 'PASSWORD', 'PRIMARY', 'PRIVILEGES', 'PROCEDURE',
             'PROCESS', 'PROCESSLIST', 'PURGE', 'QUICK', 'RAID0', 'RAID_CHUNKS', 'RAID_CHUNKSIZE', 'RAID_TYPE', 'RANGE', 'READ', 'READ_ONLY',
             'READ_WRITE', 'REFERENCES', 'REGEXP', 'RELOAD', 'RENAME', 'REPAIR', 'REPEATABLE', 'REPLACE', 'REPLICATION', 'RESET', 'RESTORE', 'RESTRICT',
             'RETURN', 'RETURNS', 'REVOKE', 'RIGHT', 'RLIKE', 'ROLLBACK', 'ROW', 'ROWS', 'ROW_FORMAT', 'SECOND', 'SECURITY', 'SELECT', 'SEPARATOR',
             'SERIALIZABLE', 'SESSION', 'SET', 'SHARE', 'SHOW', 'SHUTDOWN', 'SLAVE', 'SONAME', 'SOUNDS', 'SQL', 'SQL_AUTO_IS_NULL', 'SQL_BIG_RESULT',
             'SQL_BIG_SELECTS', 'SQL_BIG_TABLES', 'SQL_BUFFER_RESULT', 'SQL_CACHE', 'SQL_CALC_FOUND_ROWS', 'SQL_LOG_BIN', 'SQL_LOG_OFF',
             'SQL_LOG_UPDATE', 'SQL_LOW_PRIORITY_UPDATES', 'SQL_MAX_JOIN_SIZE', 'SQL_NO_CACHE', 'SQL_QUOTE_SHOW_CREATE', 'SQL_SAFE_UPDATES',
             'SQL_SELECT_LIMIT', 'SQL_SLAVE_SKIP_COUNTER', 'SQL_SMALL_RESULT', 'SQL_WARNINGS', 'START', 'STARTING', 'STATUS', 'STOP', 'STORAGE',
             'STRAIGHT_JOIN', 'STRING', 'STRIPED', 'SUPER', 'TABLE', 'TABLES', 'TEMPORARY', 'TERMINATED', 'THEN', 'TO', 'TRAILING', 'TRANSACTIONAL',
             'TRUNCATE', 'TYPE', 'TYPES', 'UNCOMMITTED', 'UNION', 'UNIQUE', 'UNLOCK', 'UPDATE', 'USAGE', 'USE', 'USING', 'VALUES', 'VARIABLES',
             'VIEW', 'WHEN', 'WHERE', 'WITH', 'WORK', 'WRITE', 'XOR', 'YEAR_MONTH'
         );

         $sql_skip_reserved_words = array('AS', 'ON', 'USING');
         $sql_special_reserved_words = array('(', ')');

         $sql_raw = str_replace("\n", " ", $sql_raw);

         $sql_formatted = "";

         $prev_word = "";
         $word = "";

         for( $i=0, $j = strlen($sql_raw); $i < $j; $i++ )
         {
          $word .= $sql_raw[$i];

          $word_trimmed = trim($word);

          if($sql_raw[$i] == " " || in_array($sql_raw[$i], $sql_special_reserved_words))
          {
           $word_trimmed = trim($word);

           $trimmed_special = false;

           if( in_array($sql_raw[$i], $sql_special_reserved_words) )
           {
            $word_trimmed = substr($word_trimmed, 0, -1);
            $trimmed_special = true;
           }

           $word_trimmed = strtoupper($word_trimmed);

           if( in_array($word_trimmed, $sql_reserved_all) && !in_array($word_trimmed, $sql_skip_reserved_words) )
           {
            if(in_array($prev_word, $sql_reserved_all))
            {
             $sql_formatted .= '<b>'.strtoupper(trim($word)).'</b>'.'&nbsp;';
            }
            else
            {
             $sql_formatted .= '<br/>&nbsp;';
             $sql_formatted .= '<b>'.strtoupper(trim($word)).'</b>'.'&nbsp;';
            }

            $prev_word = $word_trimmed;
            $word = "";
           }
           else
           {
            $sql_formatted .= trim($word).'&nbsp;';

            $prev_word = $word_trimmed;
            $word = "";
           }
          }
         }

         $sql_formatted .= trim($word);

         return $sql_formatted;
        }
        
        public static function GetWidth($wd="500px")
        {
            $wd = "500px";
            global $mobile;
            if($mobile==true) $wd="100px";
            return $wd;
        }

        public static function get_img($user_photo_file,$thumb=true,$folder='user_photos',$wd=200)
        {
            global $mobile;
            if($mobile==true) $wd=100;
            $add = "";
            $img = $user_photo_file;    
            if($thumb==true)
            $img = util::get_thumb($user_photo_file);     
            else $add = "style='width:".$wd."px'";            
            
            if($user_photo_file!="")
            return "<img border=0 $add src='$folder/".$img."'>";
            else 
            return  "<img border=0 $add src='$folder/nophoto.jpg'>";   
        }
        
          public static function get_img_file($user_photo_file)
        {
            if($user_photo_file!="")
            return $user_photo_file;
            else 
            return  "nophoto.jpg";   
        }
         public static function get_thumb($filename)
        {            
                $file = $filename;
                if($file=="") $file = "nophoto.jpg";
                $ext = explode(".", $file);
                $ext_last = end($ext);
                return $file.".thumb.".$ext_last ;
        }
        
    public static function createThumbnail($pathToImage, $thumbWidth = 180) {
    $result = 'Failed';
    if (is_file($pathToImage)) {
        $info = pathinfo($pathToImage);

        $extension = strtolower($info['extension']);
        if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {

            switch ($extension) {
                case 'jpg':
                    $img = imagecreatefromjpeg("{$pathToImage}");
                    break;
                case 'jpeg':
                    $img = imagecreatefromjpeg("{$pathToImage}");
                    break;
                case 'png':
                    $img = imagecreatefrompng("{$pathToImage}");
                    break;
                case 'gif':
                    $img = imagecreatefromgif("{$pathToImage}");
                    break;
                default:
                    $img = imagecreatefromjpeg("{$pathToImage}");
            }
            // load image and get image size

            $width = imagesx($img);
            $height = imagesy($img);

            // calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($height * ( $thumbWidth / $width ));

            // create a new temporary image
            $tmp_img = imagecreatetruecolor($new_width, $new_height);

            // copy and resize old image into new image
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                $pathToImage = $pathToImage . '.thumb.' . $extension;
            // save thumbnail into a file
            imagejpeg($tmp_img, "{$pathToImage}");
            $result = $pathToImage;
        } else {
            $result = 'Failed|Not an accepted image type (JPG, PNG, GIF).';
        }
    } else {
        $result = 'Failed|Image file does not exist.';
    }
    return $result;
}

    public static function GetRColors()
    {
        $colors = array(
            "#000000"=>"#000000",
"#0C090A"=>"#0C090A",
"#2C3539"=>"#2C3539",
"#2B1B17"=>"#2B1B17",
"#34282C"=>"#34282C",
"#25383C"=>"#25383C",
"#3B3131"=>"#3B3131",
"#413839"=>"#413839",
"#3D3C3A"=>"#3D3C3A",
"#463E3F"=>"#463E3F",
"#4C4646"=>"#4C4646",
"#504A4B"=>"#504A4B",
"#565051"=>"#565051",
"#5C5858"=>"#5C5858",
"#625D5D"=>"#625D5D",
"#666362"=>"#666362",
"#6D6968"=>"#6D6968",
"#726E6D"=>"#726E6D",
"#736F6E"=>"#736F6E",
"#837E7C"=>"#837E7C",
"#848482"=>"#848482",
"#B6B6B4"=>"#B6B6B4",
"#D1D0CE"=>"#D1D0CE",
"#E5E4E2"=>"#E5E4E2",
"#BCC6CC"=>"#BCC6CC",
"#98AFC7"=>"#98AFC7",
"#6D7B8D"=>"#6D7B8D",
"#657383"=>"#657383",
"#616D7E"=>"#616D7E",
"#646D7E"=>"#646D7E",
"#566D7E"=>"#566D7E",
"#737CA1"=>"#737CA1",
"#4863A0"=>"#4863A0",
"#2B547E"=>"#2B547E",
"#2B3856"=>"#2B3856",
"#151B54"=>"#151B54",
"#000080"=>"#000080",
"#342D7E"=>"#342D7E",
"#15317E"=>"#15317E",
"#151B8D"=>"#151B8D",
"#0000A0"=>"#0000A0",
"#0020C2"=>"#0020C2",
"#0041C2"=>"#0041C2",
"#2554C7"=>"#2554C7",
"#1569C7"=>"#1569C7",
"#2B60DE"=>"#2B60DE",
"#1F45FC"=>"#1F45FC",
"#6960EC"=>"#6960EC",
"#736AFF"=>"#736AFF",
"#357EC7"=>"#357EC7",
"#368BC1"=>"#368BC1",
"#488AC7"=>"#488AC7",
"#3090C7"=>"#3090C7",
"#659EC7"=>"#659EC7",
"#87AFC7"=>"#87AFC7",
"#95B9C7"=>"#95B9C7",
"#728FCE"=>"#728FCE",
"#2B65EC"=>"#2B65EC",
"#306EFF"=>"#306EFF",
"#157DEC"=>"#157DEC",
"#1589FF"=>"#1589FF",
"#6495ED"=>"#6495ED",
"#6698FF"=>"#6698FF",
"#38ACEC"=>"#38ACEC",
"#56A5EC"=>"#56A5EC",
"#5CB3FF"=>"#5CB3FF",
"#3BB9FF"=>"#3BB9FF",
"#79BAEC"=>"#79BAEC",
"#82CAFA"=>"#82CAFA",
"#82CAFF"=>"#82CAFF",
"#A0CFEC"=>"#A0CFEC",
"#B7CEEC"=>"#B7CEEC",
"#B4CFEC"=>"#B4CFEC",
"#C2DFFF"=>"#C2DFFF",
"#C6DEFF"=>"#C6DEFF",
"#AFDCEC"=>"#AFDCEC",
"#ADDFFF"=>"#ADDFFF",
"#BDEDFF"=>"#BDEDFF",
"#CFECEC"=>"#CFECEC",
"#E0FFFF"=>"#E0FFFF",
"#EBF4FA"=>"#EBF4FA",
"#F0F8FF"=>"#F0F8FF",
"#F0FFFF"=>"#F0FFFF",
"#CCFFFF"=>"#CCFFFF",
"#93FFE8"=>"#93FFE8",
"#9AFEFF"=>"#9AFEFF",
"#7FFFD4"=>"#7FFFD4",
"#00FFFF"=>"#00FFFF",
"#7DFDFE"=>"#7DFDFE",
"#57FEFF"=>"#57FEFF",
"#8EEBEC"=>"#8EEBEC",
"#50EBEC"=>"#50EBEC",
"#4EE2EC"=>"#4EE2EC",
"#81D8D0"=>"#81D8D0",
"#92C7C7"=>"#92C7C7",
"#77BFC7"=>"#77BFC7",
"#78C7C7"=>"#78C7C7",
"#48CCCD"=>"#48CCCD",
"#43C6DB"=>"#43C6DB",
"#46C7C7"=>"#46C7C7",
"#43BFC7"=>"#43BFC7",
"#3EA99F"=>"#3EA99F",
"#3B9C9C"=>"#3B9C9C",
"#438D80"=>"#438D80",
"#348781"=>"#348781",
"#307D7E"=>"#307D7E",
"#5E7D7E"=>"#5E7D7E",
"#4C787E"=>"#4C787E",
"#008080"=>"#008080",
"#4E8975"=>"#4E8975",
"#78866B"=>"#78866B",
"#848b79"=>"#848b79",
"#617C58"=>"#617C58",
"#728C00"=>"#728C00",
"#667C26"=>"#667C26",
"#254117"=>"#254117",
"#306754"=>"#306754",
"#347235"=>"#347235",
"#437C17"=>"#437C17",
"#387C44"=>"#387C44",
"#347C2C"=>"#347C2C",
"#347C17"=>"#347C17",
"#348017"=>"#348017",
"#4E9258"=>"#4E9258",
"#6AA121"=>"#6AA121",
"#4AA02C"=>"#4AA02C",
"#41A317"=>"#41A317",
"#3EA055"=>"#3EA055",
"#6CBB3C"=>"#6CBB3C",
"#6CC417"=>"#6CC417",
"#4CC417"=>"#4CC417",
"#52D017"=>"#52D017",
"#4CC552"=>"#4CC552",
"#54C571"=>"#54C571",
"#99C68E"=>"#99C68E",
"#89C35C"=>"#89C35C",
"#85BB65"=>"#85BB65",
"#8BB381"=>"#8BB381",
"#9CB071"=>"#9CB071",
"#B2C248"=>"#B2C248",
"#9DC209"=>"#9DC209",
"#A1C935"=>"#A1C935",
"#7FE817"=>"#7FE817",
"#59E817"=>"#59E817",
"#57E964"=>"#57E964",
"#64E986"=>"#64E986",
"#5EFB6E"=>"#5EFB6E",
"#00FF00"=>"#00FF00",
"#5FFB17"=>"#5FFB17",
"#87F717"=>"#87F717",
"#8AFB17"=>"#8AFB17",
"#6AFB92"=>"#6AFB92",
"#98FF98"=>"#98FF98",
"#B5EAAA"=>"#B5EAAA",
"#C3FDB8"=>"#C3FDB8",
"#CCFB5D"=>"#CCFB5D",
"#B1FB17"=>"#B1FB17",
"#BCE954"=>"#BCE954",
"#EDDA74"=>"#EDDA74",
"#EDE275"=>"#EDE275",
"#FFE87C"=>"#FFE87C",
"#FFFF00"=>"#FFFF00",
"#FFF380"=>"#FFF380",
"#FFFFC2"=>"#FFFFC2",
"#FFFFCC"=>"#FFFFCC",
"#FFF8C6"=>"#FFF8C6",
"#FFF8DC"=>"#FFF8DC",
"#F5F5DC"=>"#F5F5DC",
"#FBF6D9"=>"#FBF6D9",
"#FAEBD7"=>"#FAEBD7",
"#F7E7CE"=>"#F7E7CE",
"#FFEBCD"=>"#FFEBCD",
"#F3E5AB"=>"#F3E5AB",
"#ECE5B6"=>"#ECE5B6",
"#FFE5B4"=>"#FFE5B4",
"#FFDB58"=>"#FFDB58",
"#FFD801"=>"#FFD801",
"#FDD017"=>"#FDD017",
"#EAC117"=>"#EAC117",
"#F2BB66"=>"#F2BB66",
"#FBB917"=>"#FBB917",
"#FBB117"=>"#FBB117",
"#FFA62F"=>"#FFA62F",
"#E9AB17"=>"#E9AB17",
"#E2A76F"=>"#E2A76F",
"#DEB887"=>"#DEB887",
"#FFCBA4"=>"#FFCBA4",
"#C9BE62"=>"#C9BE62",
"#E8A317"=>"#E8A317",
"#EE9A4D"=>"#EE9A4D",
"#C8B560"=>"#C8B560",
"#D4A017"=>"#D4A017",
"#C2B280"=>"#C2B280",
"#C7A317"=>"#C7A317",
"#C68E17"=>"#C68E17",
"#B5A642"=>"#B5A642",
"#ADA96E"=>"#ADA96E",
"#C19A6B"=>"#C19A6B",
"#CD7F32"=>"#CD7F32",
"#C88141"=>"#C88141",
"#C58917"=>"#C58917",
"#AF9B60"=>"#AF9B60",
"#AF7817"=>"#AF7817",
"#B87333"=>"#B87333",
"#966F33"=>"#966F33",
"#806517"=>"#806517",
"#827839"=>"#827839",
"#827B60"=>"#827B60",
"#786D5F"=>"#786D5F",
"#493D26"=>"#493D26",
"#483C32"=>"#483C32",
"#6F4E37"=>"#6F4E37",
"#835C3B"=>"#835C3B",
"#7F5217"=>"#7F5217",
"#7F462C"=>"#7F462C",
"#C47451"=>"#C47451",
"#C36241"=>"#C36241",
"#C35817"=>"#C35817",
"#C85A17"=>"#C85A17",
"#CC6600"=>"#CC6600",
"#E56717"=>"#E56717",
"#E66C2C"=>"#E66C2C",
"#F87217"=>"#F87217",
"#F87431"=>"#F87431",
"#E67451"=>"#E67451",
"#FF8040"=>"#FF8040",
"#F88017"=>"#F88017",
"#FF7F50"=>"#FF7F50",
"#F88158"=>"#F88158",
"#F9966B"=>"#F9966B",
"#E78A61"=>"#E78A61",
"#E18B6B"=>"#E18B6B",
"#E77471"=>"#E77471",
"#F75D59"=>"#F75D59",
"#E55451"=>"#E55451",
"#E55B3C"=>"#E55B3C",
"#FF0000"=>"#FF0000",
"#FF2400"=>"#FF2400",
"#F62217"=>"#F62217",
"#F70D1A"=>"#F70D1A",
"#F62817"=>"#F62817",
"#E42217"=>"#E42217",
"#E41B17"=>"#E41B17",
"#DC381F"=>"#DC381F",
"#C34A2C"=>"#C34A2C",
"#C24641"=>"#C24641",
"#C04000"=>"#C04000",
"#C11B17"=>"#C11B17",
"#9F000F"=>"#9F000F",
"#990012"=>"#990012",
"#8C001A"=>"#8C001A",
"#954535"=>"#954535",
"#7E3517"=>"#7E3517",
"#8A4117"=>"#8A4117",
"#7E3817"=>"#7E3817",
"#800517"=>"#800517",
"#810541"=>"#810541",
"#7D0541"=>"#7D0541",
"#7E354D"=>"#7E354D",
"#7D0552"=>"#7D0552",
"#7F4E52"=>"#7F4E52",
"#7F5A58"=>"#7F5A58",
"#7F525D"=>"#7F525D",
"#B38481"=>"#B38481",
"#C5908E"=>"#C5908E",
"#C48189"=>"#C48189",
"#C48793"=>"#C48793",
"#E8ADAA"=>"#E8ADAA",
"#EDC9AF"=>"#EDC9AF",
"#FDD7E4"=>"#FDD7E4",
"#FCDFFF"=>"#FCDFFF",
"#FFDFDD"=>"#FFDFDD",
"#FBBBB9"=>"#FBBBB9",
"#FAAFBE"=>"#FAAFBE",
"#FAAFBA"=>"#FAAFBA",
"#F9A7B0"=>"#F9A7B0",
"#E7A1B0"=>"#E7A1B0",
"#E799A3"=>"#E799A3",
"#E38AAE"=>"#E38AAE",
"#F778A1"=>"#F778A1",
"#E56E94"=>"#E56E94",
"#F660AB"=>"#F660AB",
"#FC6C85"=>"#FC6C85",
"#F6358A"=>"#F6358A",
"#F52887"=>"#F52887",
"#E45E9D"=>"#E45E9D",
"#E4287C"=>"#E4287C",
"#F535AA"=>"#F535AA",
"#FF00FF"=>"#FF00FF",
"#E3319D"=>"#E3319D",
"#F433FF"=>"#F433FF",
"#D16587"=>"#D16587",
"#C25A7C"=>"#C25A7C",
"#CA226B"=>"#CA226B",
"#C12869"=>"#C12869",
"#C12267"=>"#C12267",
"#C25283"=>"#C25283",
"#C12283"=>"#C12283",
"#B93B8F"=>"#B93B8F",
"#7E587E"=>"#7E587E",
"#571B7E"=>"#571B7E",
"#583759"=>"#583759",
"#4B0082"=>"#4B0082",
"#461B7E"=>"#461B7E",
"#4E387E"=>"#4E387E",
"#614051"=>"#614051",
"#5E5A80"=>"#5E5A80",
"#6A287E"=>"#6A287E",
"#7D1B7E"=>"#7D1B7E",
"#A74AC7"=>"#A74AC7",
"#B048B5"=>"#B048B5",
"#6C2DC7"=>"#6C2DC7",
"#842DCE"=>"#842DCE",
"#8D38C9"=>"#8D38C9",
"#7A5DC7"=>"#7A5DC7",
"#7F38EC"=>"#7F38EC",
"#8E35EF"=>"#8E35EF",
"#893BFF"=>"#893BFF",
"#8467D7"=>"#8467D7",
"#A23BEC"=>"#A23BEC",
"#B041FF"=>"#B041FF",
"#C45AEC"=>"#C45AEC",
"#9172EC"=>"#9172EC",
"#9E7BFF"=>"#9E7BFF",
"#D462FF"=>"#D462FF",
"#E238EC"=>"#E238EC",
"#C38EC7"=>"#C38EC7",
"#C8A2C8"=>"#C8A2C8",
"#E6A9EC"=>"#E6A9EC",
"#E0B0FF"=>"#E0B0FF"

        );
                return $colors;
    }

    
    public static function GetColors()
    {
        $colors = array("-1"=>"Default color","AliceBlue"=>"AliceBlue",
"Aqua"=>"Aqua",
"Aquamarine"=>"Aquamarine",
"Bisque"=>"Bisque",
"Black"=>"Black",
"BlanchedAlmond"=>"BlanchedAlmond",
"Blue"=>"Blue",
"BlueViolet"=>"BlueViolet",
"Brown"=>"Brown",
"BurlyWood"=>"BurlyWood",
"CadetBlue"=>"CadetBlue",
"Chartreuse"=>"Chartreuse",
"Chocolate"=>"Chocolate",
"Coral"=>"Coral",
"CornflowerBlue"=>"CornflowerBlue",
"Cornsilk"=>"Cornsilk",
"Crimson"=>"Crimson",
"Cyan"=>"Cyan",
"DarkBlue"=>"DarkBlue",
"DarkCyan"=>"DarkCyan",
"DarkGoldenRod"=>"DarkGoldenRod",
"DarkGray"=>"DarkGray",
"DarkGreen"=>"DarkGreen",
"DarkKhaki"=>"DarkKhaki",
"DarkMagenta"=>"DarkMagenta",
"DarkOliveGreen"=>"DarkOliveGreen",
"Darkorange"=>"Darkorange",
"DarkOrchid"=>"DarkOrchid",
"DarkRed"=>"DarkRed",
"DarkSalmon"=>"DarkSalmon",
"DarkSeaGreen"=>"DarkSeaGreen",
"DarkSlateBlue"=>"DarkSlateBlue",
"DarkSlateGray"=>"DarkSlateGray",
"DarkTurquoise"=>"DarkTurquoise",
"DarkViolet"=>"DarkViolet",
"DeepPink"=>"DeepPink",
"DeepSkyBlue"=>"DeepSkyBlue",
"DimGray"=>"DimGray",
"DodgerBlue"=>"DodgerBlue",
"FireBrick"=>"FireBrick",
"ForestGreen"=>"ForestGreen",
"Fuchsia"=>"Fuchsia",
"Gainsboro"=>"Gainsboro",
"Gold"=>"Gold",
"GoldenRod"=>"GoldenRod",
"Gray"=>"Gray",
"Green"=>"Green",
"GreenYellow"=>"GreenYellow",
"HotPink"=>"HotPink",
"Khaki"=>"Khaki",
"Lavender"=>"Lavender",
"LavenderBlush"=>"LavenderBlush",
"LawnGreen"=>"LawnGreen",
"Lime"=>"Lime",
"LimeGreen"=>"LimeGreen",
"Magenta"=>"Magenta",
"Maroon"=>"Maroon",
"MediumAquaMarine"=>"MediumAquaMarine",
"MediumBlue"=>"MediumBlue",
"MediumOrchid"=>"MediumOrchid",
"MediumPurple"=>"MediumPurple",
"MediumSeaGreen"=>"MediumSeaGreen",
"MediumSlateBlue"=>"MediumSlateBlue",
"MediumSpringGreen"=>"MediumSpringGreen",
"MediumTurquoise"=>"MediumTurquoise",
"MediumVioletRed"=>"MediumVioletRed",
"MidnightBlue"=>"MidnightBlue",
"MistyRose"=>"MistyRose",
"Moccasin"=>"Moccasin",
"Navy"=>"Navy",
"Olive"=>"Olive",
"OliveDrab"=>"OliveDrab",
"Orange"=>"Orange",
"OrangeRed"=>"OrangeRed",
"Orchid"=>"Orchid",
"PaleGoldenRod"=>"PaleGoldenRod",
"PaleGreen"=>"PaleGreen",
"PaleTurquoise"=>"PaleTurquoise",
"PaleVioletRed"=>"PaleVioletRed",
"PapayaWhip"=>"PapayaWhip",
"PeachPuff"=>"PeachPuff",
"Peru"=>"Peru",
"Pink"=>"Pink",
"Plum"=>"Plum",
"PowderBlue"=>"PowderBlue",
"Purple"=>"Purple",
"Red"=>"Red",
"RosyBrown"=>"RosyBrown",
"RoyalBlue"=>"RoyalBlue",
"SaddleBrown"=>"SaddleBrown",
"Salmon"=>"Salmon",
"SandyBrown"=>"SandyBrown",
"SeaGreen"=>"SeaGreen",
"Sienna"=>"Sienna",
"Silver"=>"Silver",
"SkyBlue"=>"SkyBlue",
"SlateBlue"=>"SlateBlue",
"SlateGray"=>"SlateGray",
"SpringGreen"=>"SpringGreen",
"SteelBlue"=>"SteelBlue",
"Tan"=>"Tan",
"Teal"=>"Teal",
"Thistle"=>"Thistle",
"Tomato"=>"Tomato",
"Turquoise"=>"Turquoise",
"Violet"=>"Violet",
"Wheat"=>"Wheat",
"Yellow"=>"Yellow",
"YellowGreen"=>"YellowGreen"        
) ;
        return $colors;
    }
 }

?>