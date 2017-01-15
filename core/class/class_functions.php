<?php
/*
*    Copyright 2008-2015 Maarch
*
*  This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief   Contains all the various functions of this application.
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   Contains all the various functions of this application.
*
* <ul>
*  <li>The toolkit of the Maarch framework</li>
*  <li>Management of variables format</li>
*  <li>Management of date format</li>
* </ul>
* @ingroup core
*/
class functions
{
    /**
    *
    * @deprecated
         */
    private $f_page;

    /**
    * To calculate the page generation time
    * Integer
         */
    private $start_page;

    /**
    * Loads in the start_page variable the start time of the page loading
    *
    */
    public function start_page_stat()
    {
        $this->start_page = microtime(true);
    }

    public function normalize ($string)
    {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ'
            . 'ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuy'
            . 'bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        $string = strtolower($string);

        return utf8_encode($string);
    }

    /**
    * Cuts a string at the maximum number of char to displayed
    *
    * @param     $string string String value
    * @param     $max integer Maximum character number
    */
    public function cut_string($string, $max)
    {
        if (strlen($string) >= $max)
        {
          $string = substr($string, 0, $max);
          $espace = strrpos($string, " ");
          $string = substr($string, 0, $espace)."...";
          return $string;
        }
        else
        {
            return $string;
        }
    }

    /**
    * Ends the page loading time and displays it
    *
    */
    public function show_page_stat()
    {
        $end_page = microtime(true);
        $page_total = round($end_page - $this->start_page,3);
        if($page_total > 1)
        {
            $page_seconds = _SECONDS;
        }
        else
        {
            $page_seconds = _SECOND;
        }
        echo _PAGE_GENERATED_IN." <b>".$page_total."</b> ".$page_seconds;
    }

    /**
    * Configures the actual position of the visitor with all query strings to go to the right page after the logging action
    *
    * @param     $index string "index.php?" by default
    */
    public function configPosition($index ="index.php?")
    {
        $querystring = $_SERVER['QUERY_STRING'];
        $tab_query = explode("&",$querystring);
        $querystring = "";

        for($i=0;$i<count($tab_query);$i++)
        {
            if(substr($tab_query[$i],0,3) <> "css" && substr($tab_query[$i],0,3) <> "CSS")
            {
                $querystring .= $tab_query[$i]."&";
            }
        }
        $querystring = substr($querystring,0,strlen($querystring)-1);
        $_SESSION['position'] = $index.$querystring;
    }

    /**
    * Adds en error to the errors log
    *
    * @param     $msg  string Message to add
    * @param  $var  string Language dependant message
    */
    public function add_error($msg, $var)
    {
        $msg = trim($msg);
        if(!empty($msg))
        {
            $_SESSION['error'] .= $msg." ".$var . ' ';
            if(strlen(str_replace(array("<br />","<br />"),"",$_SESSION['error'])) < 6)
            {
                $_SESSION['error'] = "";
            }
        }
    }

    /**
    * Cleans a variable with multiple possibility
    *
    * @param     $what  string Variable to clean
    * @param  $mask  string Mask, "no" by default
    * @param     $msg_error string Error message, empty by default
    * @param     $empty  string "yes" by default
    * @param     $min_limit integer Empty by default
    * @param     $max_limit integer Empty by default
    * @return   string Cleaned variable or empty string
    */
    public function wash($what, $mask = "no", $msg_error = "", $empty = "yes", $min_limit = "", $max_limit = "", $custom_pattern = '', $custom_error_msg = '')
    {

        //$w_var = addslashes(trim(strip_tags($what)));

        $w_var = trim(strip_tags($what));
        $test_empty = "ok";

        if($empty == "yes")
        {
            // We use strlen instead of the php's empty function because for a var containing 0 return by a form (in string format)
            // the empty function return that the var is empty but it contains à 0
            if(strlen($w_var) == 0)
            {
                $test_empty = "no";
            }
            else
            {
                $test_empty = "ok";
            }
        }
        if($test_empty == "no")
        {
            $this->add_error($msg_error, _IS_EMPTY);
            return "";
        }
        else
        {
            if($msg_error <> '')
            {
                if($min_limit <> "")
                {
                    if(strlen($w_var) < $min_limit)
                    {
                        if($min_limit > 1)
                        {
                            $this->add_error($msg_error,  _MUST_MAKE_AT_LEAST." ".$min_limit." "._CHARACTERS);
                        }
                        else
                        {
                            $this->add_error($msg_error, _MUST_MAKE_AT_LEAST." ".$min_limit." "._CHARACTERS);
                        }
                        return "";
                    }
                }
            }

            if($max_limit <> "")
            {
                if(strlen($w_var) > $max_limit)
                {
                    if($min_limit > 1)
                    {
                        $this->add_error($msg_error, MUST_BE_LESS_THAN." ".$max_limit." "._CHARACTERS);
                    }
                    else
                    {
                        $this->add_error($msg_error,  MUST_BE_LESS_THAN." ".$max_limit." "._CHARACTERS);
                    }

                    return "";
                }
            }

            switch ($mask)
            {
                case "no":
                    return $w_var;

                case "num":
                    if (preg_match("/^[0-9]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT." :<br/>"._WAITING_INTEGER);
                        return "";
                    }

                case "float":
                    if (preg_match("/^[0-9.,]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT." "._WAITING_FLOAT);
                        return "";
                    }

                case "letter":
                    if (preg_match("/^[a-zA-Z]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT);
                        $this->add_error(_ONLY_ALPHABETIC, '');
                        return "";
                    }

                case "alphanum":
                    if (preg_match("/^[a-zA-Z0-9]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error,_WRONG_FORMAT);
                        $this->add_error(_ONLY_ALPHANUM,  '');
                        return "";
                    }

                case "alphanumunderscore":
                    if (preg_match("/^[a-zA-Z0-9_]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error,_WRONG_FORMAT);
                        return "";
                    }   

                case "nick":
                    if (preg_match("/^[_a-zA-Z0-9.-]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error,_WRONG_FORMAT);
                        return "";
                    }

                case "mail":
                    if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,4}$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT);
                        return "";
                    }

                case "url":
                    if (preg_match("/^[www.]+[_a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT);
                        return "";
                    }

                case "file":
                    if (preg_match("/^[_a-zA-Z0-9.-? é&\/]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT);
                        return "";
                    }

                case "name":
                    if (preg_match("/^[_a-zA-Z0-9.-? \'\/&éea]+$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT);
                        return "";
                    }
                case "phone":
                    if (preg_match("/^[\+0-9\(\)\s\.]*$/",$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT);
                        return "";
                    }
                case "date":
                    $date_pattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
                    if(preg_match($date_pattern,$w_var))
                    {
                        return $w_var;
                    }
                    else
                    {
                        $this->add_error($msg_error, _WRONG_FORMAT." "._WAITING_DATE);
                        return "";
                    }
                case "custom":
                    if(preg_match($custom_pattern,$w_var) == 0)
                    {
                        $this->add_error($msg_error, $custom_error_msg.' '.$custom_pattern.' '.$w_var);
                        return "";
                    }
                    else
                    {
                        return $w_var;
                    }
            }
        }
    }

    /**
    * Returns a variable with personnal formating. It allows you to add formating action when you displays the variable the var
    *
    * @param     $what string Variable to format
    * @return string  Formated variable
    */
    public static function show_str($what)
    {
        return stripslashes($what);
    }

    /**
    * Manages the location bar in session (4 levels max), then calls the where_am_i() function.
    *
    * @param     $path  string Url (empty by default)
    * @param   $label string Label to show in the location bar (empty by default)
    * @param   $id_pagestring  Page identifier (empty by default)
    * @param   $init bool If true reinits the location bar (true by default)
    * @param   $level string Level in the location bar (empty by default)
    */
    public function manage_location_bar($path = '', $label = '', $id_page = '', $init = true, $level = '')
    {
        //Fix un little php bug
        if(strpos($label,"&rsquo;")!== false)
        {
            $label = str_replace("&rsquo;" , "\'", $label);
        }

        $_SESSION['location_bar']['level1']['path'] = "index.php?reinit=true";
        $_SESSION['location_bar']['level1']['label'] = $_SESSION['config']['applicationname'];
        $_SESSION['location_bar']['level1']['id'] = "welcome";

        if(!empty($level))
        {
            if($level == 1)
            {
                $_SESSION['location_bar']['level2']['path'] = "";
                $_SESSION['location_bar']['level2']['label'] = "";
                $_SESSION['location_bar']['level2']['id'] = "" ;

                $_SESSION['location_bar']['level3']['path'] = "";
                $_SESSION['location_bar']['level3']['label'] = "";
                $_SESSION['location_bar']['level3']['id'] = "" ;

                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
            elseif($level == 2)
            {
                $_SESSION['location_bar']['level3']['path'] = "";
                $_SESSION['location_bar']['level3']['label'] = "";
                $_SESSION['location_bar']['level3']['id'] = "" ;

                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
            elseif($level == 3)
            {
                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
        }
        else
        {

            if(isset($_SESSION['location_bar']['level1']['id']) && trim($id_page) == trim($_SESSION['location_bar']['level1']['id']))
            {
                $_SESSION['location_bar']['level2']['path'] = "";
                $_SESSION['location_bar']['level2']['label'] = "";
                $_SESSION['location_bar']['level2']['id'] = "" ;

                $_SESSION['location_bar']['level3']['path'] = "";
                $_SESSION['location_bar']['level3']['label'] = "";
                $_SESSION['location_bar']['level3']['id'] = "" ;

                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
            elseif( isset($_SESSION['location_bar']['level2']['id']) && trim($id_page) == trim($_SESSION['location_bar']['level2']['id']))
            {
                $_SESSION['location_bar']['level3']['path'] = "";
                $_SESSION['location_bar']['level3']['label'] = "";
                $_SESSION['location_bar']['level3']['id'] = "" ;

                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
            elseif(isset($_SESSION['location_bar']['level3']['id']) && trim($id_page) == trim($_SESSION['location_bar']['level3']['id']))
            {
                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
            elseif($init || empty($_SESSION['location_bar']['level2']['id']))
            {
                $_SESSION['location_bar']['level2']['path'] = $path;
                $_SESSION['location_bar']['level2']['path'] .= "&level=2";
                $_SESSION['location_bar']['level2']['label'] = $this->wash_html($label);
                $_SESSION['location_bar']['level2']['id'] = $id_page ;

                $_SESSION['location_bar']['level3']['path'] = "";
                $_SESSION['location_bar']['level3']['label'] = "";
                $_SESSION['location_bar']['level3']['id'] = "" ;

                $_SESSION['location_bar']['level4']['path'] = "";
                $_SESSION['location_bar']['level4']['label'] = "";
                $_SESSION['location_bar']['level4']['id'] = "" ;
            }
            else
            {
                if(empty($_SESSION['location_bar']['level3']['path']))
                {
                    $_SESSION['location_bar']['level3']['path'] = $path."&level=3";
                    $_SESSION['location_bar']['level3']['label'] = $this->wash_html($label);
                    $_SESSION['location_bar']['level3']['id'] = $id_page ;

                    $_SESSION['location_bar']['level4']['path'] = "";
                    $_SESSION['location_bar']['level4']['label'] = "";
                    $_SESSION['location_bar']['level4']['id'] = "" ;
                }
                else
                {
                    $_SESSION['location_bar']['level4']['path'] = $path."&level=4";
                    $_SESSION['location_bar']['level4']['label'] = $this->wash_html($label);
                    $_SESSION['location_bar']['level4']['id'] = $id_page ;
                }
            }
        }
        $this->where_am_i();
    }

    /**
    * Uses javascript to rewrite the location bar
    *
    */
    private function where_am_i()
    {
        if(empty($_SESSION['location_bar']['level2']['path']))
        {
        ?><script  type="text/javascript">
            var bar = window.document.getElementById('ariane');
            if(bar != null)
            {
                var link1 = document.createElement("a");
                link1.href='<?php echo($_SESSION['location_bar']['level1']['path']);?>';
                var label1 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level1']['label']);?>");
                link1.appendChild(label1);
                bar.appendChild(link1);
            }
        </script><?php
        }
        else
        {
            if(empty($_SESSION['location_bar']['level3']['path']))
            {
                ?><script  type="text/javascript">
                    var bar = window.document.getElementById('ariane');
                    if(bar != null)
                    {
                        var link1 = document.createElement("a");
                        link1.href='<?php echo($_SESSION['location_bar']['level1']['path']);?>';
                        var label1 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level1']['label']);?>");
                        link1.appendChild(label1);
                        bar.appendChild(link1);
                        var text1 = document.createTextNode(" > <?php functions::xecho($_SESSION['location_bar']['level2']['label']);?>");
                        bar.appendChild(text1);
                    }
                </script><?php
            }
            else
            {
                if(empty($_SESSION['location_bar']['level4']['path']))
                {
                    ?><script type="text/javascript">
                        var bar = window.document.getElementById('ariane');
                        if(bar != null)
                        {
                            var link1 = document.createElement("a");
                            link1.href='<?php echo($_SESSION['location_bar']['level1']['path']);?>';
                            var label1 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level1']['label']);?>");
                            link1.appendChild(label1);
                            bar.appendChild(link1);
                            var text1 = document.createTextNode(" > ");
                            bar.appendChild(text1);
                            var link2 = document.createElement("a");
                            link2.href='<?php echo($_SESSION['location_bar']['level2']['path']);?>';
                            var label2 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level2']['label']);?>");
                            link2.appendChild(label2);
                            bar.appendChild(link2);
                            var text2 = document.createTextNode(" > <?php echo $_SESSION['location_bar']['level3']['label'];?>");
                            bar.appendChild(text2);
                        }
                    </script><?php
                }
                else
                {
                    ?><script  type="text/javascript">
                        var bar = window.document.getElementById('ariane');
                        if(bar != null)
                        {
                            var link1 = document.createElement("a");
                            link1.href='<?php echo($_SESSION['location_bar']['level1']['path']);?>';
                            var label1 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level1']['label']);?>");
                            link1.appendChild(label1);
                            bar.appendChild(link1);
                            var text1 = document.createTextNode(" > ");
                            bar.appendChild(text1);
                            var link2 = document.createElement("a");
                            link2.href='<?php echo($_SESSION['location_bar']['level2']['path']);?>';
                            var label2 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level2']['label']);?>");
                            link2.appendChild(label2);
                            bar.appendChild(link2);
                            var text2 = document.createTextNode(" > ");
                            bar.appendChild(text2);
                            var link3 = document.createElement("a");
                            link3.href='<?php echo($_SESSION['location_bar']['level3']['path']);?>';
                            var label3 = document.createTextNode("<?php functions::xecho($_SESSION['location_bar']['level3']['label']);?>");
                            link3.appendChild(label3);
                            bar.appendChild(link3);
                            var text3 = document.createTextNode(" > <?php echo $_SESSION['location_bar']['level4']['label'];?>");
                            bar.appendChild(text3);
                        }
                    </script><?php
                }
            }
        }
    }

    /**
    * For debug, displays an array in a more readable way
    *
    * @param   $arr array Array to display
    */
    public function show_array($arr)
    {
        echo "<table width=\"550\"><tr><td align=\"left\">";
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        echo "</td></tr></table>";
    }

    /**
    * Formats a datetime to a dd/mm/yyyy format (date)
    *
    * @param   $date datetime The date to format
    * @return   datetime  The formated date
    */
    public function format_date($date)
    {
        $last_date = '';
        if($date <> "")
        {
            if(strpos($date," "))
            {
                $date_ex = explode(" ",$date);
                $the_date = explode("-",$date_ex[0]);
                $last_date = $the_date[2]."-".$the_date[1]."-".$the_date[0];
            }
            else
            {
                $the_date = explode("-",$date);
                $last_date = $the_date[2]."-".$the_date[1]."-".$the_date[0];
            }
        }
        return $last_date;
    }

    /**
    * Formats a datetime to a dd/mm/yyyy hh:ii:ss format (timestamp)
    *
    * @param   $date  datetime The date to format
    * @return   datetime  The formatted date
    */
    public function dateformat($realDate, $sep='/')
    {
        if ($realDate <> '') {
            if (preg_match('/ /', $realDate)) {
                $hasTime = true;
                $tmpArr = explode(" ", $realDate);
                $date = $tmpArr[0];
                $time = $tmpArr[1];
                if (preg_match('/\./', $time)) {  // POSTGRES date
                    $tmp = explode('.', $time);
                    $time = $tmp[0];
                } else if (preg_match('/,/', $time)) { // ORACLE date
                    $tmp = explode(',', $time);
                    $time = $tmp[0];
                }
            } else {
                $hasTime = false;
                $date = $realDate;
            }
            if (preg_match('/-/', $date)) {
                $dateArr = explode("-", $date);
            } else if (preg_match('@\/@', $date)) {
                $dateArr = explode("/", $date);
            }
            if (! $hasTime || substr($tmpArr[1], 0, 2) == "00") {
                return $dateArr[2] . $sep . $dateArr[1] . $sep . $dateArr[0];
            } else {
                return $dateArr[2] . $sep . $dateArr[1] . $sep . $dateArr[0]
                    . " " . $time;
            }
        }
        return '';
    }

    /**
    * Writes an error in pre formating format with header and footer
    *
    * @param   $title string Error title
    * @param      $message  string Error message
    * @param      $type string If 'title' then displays the title otherwise do not displays it (empty by default)
    * @param      $img_src string Source of the image to show (empty by default)
    */
    public function echo_error($title,$message, $type = '', $img_src = '')
    {
        if ($type == 'title' || $type <> '')
        {
            if($img_src <> '')
            {
                echo '<h1><img src="'.$img_src.'" alt="" />'.$title.'</h1>';
            }
            else
            {
                echo "<h1>".$title."</h1>";
            }
            echo '<div id="inner_content">';
        } ?>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <?php functions::xecho($message);
        if ($type <> '')
        {
            echo '</div>';
        }
    }

    /**
    *  Extracts the user informations from database and puts the result in an array
    *
    * @param  $id integer User identifier
    */
    public function infouser($id)
    {
        $conn = new Database();

        $stmt = $conn->query("SELECT * FROM ".$_SESSION['tablename']['users']." WHERE user_id = ?", array($id));
        if($stmt->rowCount() == 0)
        {
            return array("UserId" => "",
                "FirstName" => "",
                "LastName" => "",
                "Initials" => "",
                "Phone" => "",
                "Mail" => "",
                "department" => "",
                "thumbprint" => "",
                "pathToSignature" => ""
            );
        }
        else
        {
            $line = $stmt->fetchObject();

            $query = "SELECT path_template FROM docservers WHERE docserver_id = 'TEMPLATES'";
            $stmt2 = $conn->query($query);
            $resDs = $stmt2->fetchObject();
            $pathToDs = $resDs->path_template;
            if ($line->signature_file_name <> "") {
                $_SESSION['user']['pathToSignature'] = $pathToDs . str_replace(
                        "#", 
                        DIRECTORY_SEPARATOR, 
                        $line->signature_path
                    )
                    . $line->signature_file_name;
            } else {
                $_SESSION['user']['pathToSignature'] = "";
            }

            return array("UserId" => $line->user_id,
                "FirstName" => $line->firstname,
                "LastName" => $line->lastname,
                "Initials" => $line->initials,
                "Phone" => $line->phone,
                "Mail" => $line->mail ,
                "department" => $line->department,
                "thumbprint" => $line->thumbprint,
                "pathToSignature" => $_SESSION['user']['pathToSignature']
            );
        }
    }


    /**
    * Returns a formated date for SQL queries
    *
    * @param  $date date Date to format
    * @param  $insert bool If true format the date to insert in the database (true by default)
    * @return Formated date or empty string if any error
    */
    public static function format_date_db($date, $insert=true, $databasetype= '', $withTimeZone=false)
    {
        if (isset($_SESSION['config']['databasetype'])
            && ! empty($_SESSION['config']['databasetype'])) {
            $databasetype = $_SESSION['config']['databasetype'];
        }

       

        if ($date <> "" ) {
            $var = explode('-', $date) ;

            if (preg_match('/\s/', $var[2])) {
                $tmp = explode(' ', $var[2]);
                $var[2] = $tmp[0];
                $var[3] = substr($tmp[1],0,8);

            }

            if (preg_match('/^[0-3][0-9]$/', $var[0])) {
                $day = $var[0];
                $month = $var[1];
                $year = $var[2];
                $hours = $var[3];
            } else {
                $year = $var[0];
                $month = $var[1];
                $day = substr($var[2], 0, 2);
                $hours = $var[3];

            }
            if ($year <= "1900") {
                return '';
            } else {
                if ($databasetype == "SQLSERVER") {
                    if ($withTimeZone) {
                        return  $day . "-" . $month . "-" . $year . " " . $hours;
                    }else{
                        return  $day . "-" . $month . "-" . $year;
                    }
                    
                } else if ($databasetype == "POSTGRESQL") {
                    if ($_SESSION['config']['lang'] == "fr") {
                        if ($withTimeZone) {
                            return $day . "-" . $month . "-" . $year . " " . $hours;
                        }else{
                            return $day . "-" . $month . "-" . $year;
                        }
                    } else {
                        if ($withTimeZone) {
                            return $year . "-" . $month . "-" . $day . " " . $hours;
                        }else{
                            return $year . "-" . $month . "-" . $day;
                        }
                    }
                } else if ($databasetype == "ORACLE") {
                    
                    return  $day . "-" . $month . "-" . $year;
                } else if ($databasetype == "MYSQL" && $insert) {
                    return $year . "-" . $month . "-" . $day;
                } else if ($databasetype == "MYSQL" && !$insert) {
                    return  $day . "-" . $month . "-" . $year;
                }
            }
        } else {
            return '';
        }
    }

    /**
    * Protects string to insert in the database
    *
    * @param  $string string String to format
    * @return Formated date
    */
    public function protect_string_db($string, $databasetype = '', $full='yes')
    {
        if (isset($_SESSION['config']['databasetype']) && !empty($_SESSION['config']['databasetype']))
        {
            $databasetype = $_SESSION['config']['databasetype'];
        }
        if ($databasetype  == "SQLSERVER")
        {
            $string = str_replace("'", "''", $string);
            $string = str_replace("\\", "", $string);
        } else if($databasetype  == "ORACLE") {
            $string = str_replace("'", "''", $string);
            $string = str_replace("\\", "", $string);
        } else if(($databasetype  == "MYSQL")  && !get_magic_quotes_runtime()) {
            $string = addslashes($string);
        } else if(($databasetype  == "POSTGRESQL")  && !get_magic_quotes_runtime()) {
            $string = str_replace("&#039;", "'", $string);
            $string = pg_escape_string($string);
        }

        if ($full == 'yes') {
            $string=str_replace(';', ' ', $string);
            $string=str_replace('--', '-', $string);  
        }
        
        return $string;
    }

    /**
    * Returns a string without the escaping characters
    *
    * @param  $string string String to format
    * @return Formated string
    */
    public static function show_string($string, $replace_CR = false, $chars_to_escape = array(), $databasetype = '', $escape_quote = true)
    {
        if(isset($string) && !empty($string) && is_string($string))
        {
            if(isset($_SESSION['config']['databasetype']) && !empty($_SESSION['config']['databasetype']))
            {
                $databasetype = $_SESSION['config']['databasetype'];
            }
            if($databasetype == "SQLSERVER")
            {
                $string = str_replace("''", "'", $string);
                $string = str_replace("\\", "", $string);
            }
            else if($databasetype == "MYSQL" || $databasetype == "POSTGRESQL" && (ini_get('magic_quotes_gpc') <> true || phpversion() >= 6))
            {
                $string = stripslashes($string);
                $string = str_replace("\\'", "'", $string);
                $string = str_replace('\\"', '"', $string);
            }
            else if($databasetype == "ORACLE")
            {
                $string = str_replace("''", "'", $string);
                $string = str_replace("\\", "", $string);
            }
            if($replace_CR)
            {
                $to_del = array("\t", "\n", "&#0A;", "&#0D;", "\r");
                $string = str_replace($to_del, ' ', $string);
            }
            for($i=0;$i<count($chars_to_escape);$i++)
            {
                $string = str_replace($chars_to_escape[$i], '\\'.$chars_to_escape, $string);
            }

            if ($escape_quote) {
                $string = str_replace('"', "'", $string);
            }
            
            $string = trim($string);
        }
        return $string;
    }

    /**
    * Cleans html string, replacing entities by utf-8 code
    *
    * @param  $var string  String to clean
    * @return Cleaned string
    */
    public function wash_html($var, $mode="UNICODE")
    {
        if($mode == "UNICODE")
        {
            $var = str_replace("<br/>","\\n",$var);
            $var = str_replace("<br />","\\n",$var);
            $var = str_replace("<br/>","\\n",$var);
            $var = str_replace("&nbsp;"," ",$var);
            $var = str_replace("&eacute;", "\u00e9",$var);
            $var = str_replace("&egrave;","\u00e8",$var);
            $var = str_replace("&ecirc;","\00ea",$var);
            $var = str_replace("&agrave;","\u00e0",$var);
            $var = str_replace("&acirc;","\u00e2",$var);
            $var = str_replace("&icirc;","\u00ee",$var);
            $var = str_replace("&ocirc;","\u00f4",$var);
            $var = str_replace("&ucirc;","\u00fb",$var);
            $var = str_replace("&acute;","\u0027",$var);
            $var = str_replace("&deg;","\u00b0",$var);
            $var = str_replace("&rsquo;", "\u2019",$var);
        }
        else if($mode == 'NO_ACCENT')
        {
            $var = str_replace("<br/>","\\n",$var);
            $var = str_replace("<br />","\\n",$var);
            $var = str_replace("<br/>","\\n",$var);
            $var = str_replace("&nbsp;"," ",$var);
            $var = str_replace("&eacute;", "e",$var);
            $var = str_replace("&egrave;","e",$var);
            $var = str_replace("&ecirc;","e",$var);
            $var = str_replace("&agrave;","a",$var);
            $var = str_replace("&acirc;","a",$var);
            $var = str_replace("&icirc;","i",$var);
            $var = str_replace("&ocirc;","o",$var);
            $var = str_replace("&ucirc;","u",$var);
            $var = str_replace("&acute;","",$var);
            $var = str_replace("&deg;","o",$var);
            $var = str_replace("&rsquo;", "'",$var);

            // AT LAST
            $var = str_replace("&", " et ",$var);
        }
        else
        {
            $var = str_replace("<br/>","\\n",$var);
            $var = str_replace("<br />","\\n",$var);
            $var = str_replace("<br/>","\\n",$var);
            $var = str_replace("&nbsp;"," ",$var);
            $var = str_replace("&eacute;", "é",$var);
            $var = str_replace("&egrave;","è",$var);
            $var = str_replace("&ecirc;","ê",$var);
            $var = str_replace("&agrave;","à",$var);
            $var = str_replace("&acirc;","â",$var);
            $var = str_replace("&icirc;","î",$var);
            $var = str_replace("&ocirc;","ô",$var);
            $var = str_replace("&ucirc;","û",$var);
            $var = str_replace("&acute;","",$var);
            $var = str_replace("&deg;","°",$var);
            $var = str_replace("&rsquo;", "'",$var);
        }
        return $var;
    }

    /**
    * Converts a value (from the php.ini) into bytes
    *
    * @param   $val string Value to convert
    * @return   integer The converted value
    */
    public function return_bytes($val)
    {
        $val = trim($val);
        $last = strtolower($val{strlen($val)-1});
        switch($last) {
            // 'G' modifier available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    /**
    *  Compares to date
    *
    * @param  $date1 date First date
    * @param  $date2 date Second date
    * @return "date1" if the first date is the greater, "date2" if the second date or "equal" otherwise
    */
    public function compare_date($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        if($date1 > $date2)
        {
            $result = "date1";
        }
        elseif($date1 < $date2)
        {
            $result = "date2";
        }
        elseif($date1 = $date2)
        {
            $result = "equal";
        }
        return $result;
    }

    /**
    *  Compares to date and return dif between 2 dates
    *
    * @param  $date1 date First date
    * @param  $date2 date Second date
    * @return dif between 2 dates in days
    */
    public function nbDaysBetween2Dates($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        if($date2 > $date1)
        {
            $result = round((($date2 - $date1) / (3600)) / 24, 0);
        }
        elseif($date2 < $date1)
        {
            $result = round((($date1 - $date2) / (3600)) / 24, 0);
        }
        else
        {
            $result = 0;
        }
        return $result;
    }

    /**
    *  Checks if a directory is empty
    *
    * @param  $dir string The directory to check
    * @return bool True if empty, False otherwise
    */
    function isDirEmpty($dir)
    {
        $dir = opendir($dir);
        $isEmpty = true;
        while(($entry = readdir($dir)) !== false)
        {
            if($entry !== '.' && $entry !== '..'  && $entry !== '.svn')
            {
                $isEmpty = false;
                break;
            }
        }
        closedir($dir);
        return $isEmpty;
    }
    
    /**
    * Convert an object to an array
    * @param  $object object to convert
    */
    public function object2array($object)
    {
        $return = NULL;
        if(is_array($object))
        {
            foreach($object as $key => $value)
            {
                $return[$key] = $this->object2array($value);
            }
        }
        else
        {
            if(is_object($object))
            {
                $var = get_object_vars($object);
                if($var)
                {
                    foreach($var as $key => $value)
                    {
                        $return[$key] = ($key && !$value) ? NULL : $this->object2array($value);
                    }
                }
                else return $object;
            }
            else return $object;
        }
        return $return;
    }

    /**
    * Function to encode an url in base64
    */
    function base64UrlEncode($data) {
        return strtr(base64_encode($data), '+/', '-_,');
    }

    /**
    * Function to decode an url encoded in base64
    */
    function base64UrlDecode($base64) {
        return base64_decode(strtr($base64, '-_,', '+/'));
    }

    /**
    * Method to generates private and public keys
    */
    public function generatePrivatePublicKey() {
        $privateKeyPath = $this->getPrivateKeyPath();
        $publicKeyPath = $this->getPublicKeyPath();
        if(!file_exists($privateKeyPath)) {
            $inF = fopen($privateKeyPath,"w");
            fclose($inF);
        }
        if(!file_exists($publicKeyPath)) {
            $inF = fopen($publicKeyPath,"w");
            fclose($inF);
        }
        $privateKey = openssl_pkey_new(array(
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ));
        $passphrase = "";
        openssl_pkey_export_to_file($privateKey, $privateKeyPath, $passphrase);
        $keyDetails = openssl_pkey_get_details($privateKey);
        file_put_contents($publicKeyPath, $keyDetails['key']);
    }

    /**
    * Encrypt a text
    * @param $text string to encrypt
    */
    public function encrypt($sensitiveData) {
        $publicKeyPath = $this->getPublicKeyPath();
        if(file_exists($publicKeyPath)) {
            $pubKey = openssl_pkey_get_public('file://'.$publicKeyPath);
            if(!$pubKey) {
                return false;
            } else {
                $encryptedData = "";
                openssl_public_encrypt($sensitiveData, $encryptedData, $pubKey);
                //base 64 encode to use it in url
                return $this->base64UrlEncode($encryptedData);
            }
        } else{
            return false;
        }
    }

    /**
    * Decrypt a text
    * @param $text string to decrypt
    */
    public function decrypt($encryptedData) {
        $privateKeyPath = $this->getPrivateKeyPath();
        if(file_exists($privateKeyPath)) {
            $passphrase = "";
            $privateKey = openssl_pkey_get_private('file://'.$privateKeyPath, $passphrase);
            if(!$privateKey) {
                return false;
            } else {
                $decryptedData = "";
                openssl_private_decrypt($this->base64UrlDecode($encryptedData), $decryptedData, $privateKey);
                return $decryptedData;
            }
        } else {
            return false;
        }
    }

    /**
    * return the path of the private key path
    */
    public function getPrivateKeyPath() {
        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml')) {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml';
        } else {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml';
        }
        $xmlconfig = simplexml_load_file($path);
        $CRYPT = $xmlconfig->CRYPT;
        return (string) $CRYPT->pathtoprivatekey;
    }

    /**
    * return the path of the public key path
    */
    public function getPublicKeyPath() {
        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml')) {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml';
        } else {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml';
        }
        $xmlconfig = simplexml_load_file($path);
        $CRYPT = $xmlconfig->CRYPT;
        return $CRYPT->pathtopublickey;
    }

    public function isEncrypted()
    {
        if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml')) {
            $path = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml';
        } else {
            $path = 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR.'config.xml';
        }
        $xmlconfig = simplexml_load_file($path);
        $CRYPT = $xmlconfig->CRYPT;
        return $CRYPT->encrypt;
    }

    /**
    * Return the file's extention of a file
    * @param  $sFullPath string path of the file
    */
    function extractFileExt($sFullPath)
    {
        $sName = $sFullPath;
        if (strpos($sName, ".") == 0) {
            $extractFileExt = "";
        } else {
            $extractFileExt = explode(".", $sName);
        }
        if ($extractFileExt <> '') {
            return $extractFileExt[count($extractFileExt) - 1];
        }
        return '';
    }

    /**
    * Browse each file and folder in the folder and return true if the folder is not empty
    * @param  $folder path string of the folder
    */
    function isDirNotEmpty($folder)
    {
        $foundDoc = false;
        $classScan = dir($folder);
        while (($fileScan = $classScan->read()) != false) {
            if($fileScan == '.' || $fileScan == '..' || $fileScan == '.svn') {
                continue;
            } else {
                $foundDoc = true;break;
            }
        }
        return $foundDoc;
    }
    
    /**
    * Generate an UUID v4
    * @return string UUID
    */
    function gen_uuid() 
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
    
    /**
    * Return the amount corresponding to the currency
    * @param  $currency string currency of the amount
    * @param  $amount float the amount
    */
    function formatAmount($currency, $amount)
    {
        $formattedAmount = '';
        if ($currency == 'EUR') {
            $formattedAmount = '€' . number_format($amount , 2 , ',' , '.' );
        } elseif  ($currency == 'DOL') {
            $formattedAmount = '$' . number_format($amount , 2 , ',' , '.' );
        } elseif  ($currency == 'YEN') {
            $formattedAmount = '¥' . number_format($amount , 2 , ',' , '.' );
        } elseif  ($currency == 'POU') {
            $formattedAmount = '£' . number_format($amount , 2 , ',' , '.' );
        } else {
            $formattedAmount = ' ' . number_format($amount , 2 , ',' , '.' );
        }
        return $formattedAmount;
    }

    /**
    * xss mitigation functions
    * Return protected chars
    * @param  $data to encode
    * @param  $encoding ut8 by default
    */
    static function xssafe($data, $encoding='UTF-8')
    {
        if (!is_array($data)) {
            return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
        } else {
            return $data;
        }
    }

    /**
    * xss mitigation functions
    * Return protected chars
    * @param  $data to encode
    */
    static function xecho($data)
    {
       echo functions::xssafe($data);
    }

    /*************************************************************************
    * Returns an empty list for SELECT X WHERE Y IN (------)
    *
    * Return
    *   (string) Empty list
    *
    *************************************************************************/
    public function empty_list()
    {
        switch($_SESSION['config']['databasetype'])  {
            case 'MYSQL'        : return "''";
            case 'POSTGRESQL'   : return "''";
            case 'SQLSERVER'    : return "''''";
            case 'ORACLE'       : return "''''";
            default             : return "''";
        }
    }
}
