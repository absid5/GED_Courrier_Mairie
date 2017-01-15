<?php
/**
* alert_engine Class
*
* Contains all the specific functions of the diff engine
* @package  core
* @version 1.0
* @since 10/2005
* @license GPL
* @author  Laurent Giovannoni  <dev@maarch.org>
*/
class alert_engine extends Database
{
    /**
    * Redefinition of the alert_engine object constructor
    */
    function __construct()
    {
	   $args = func_get_args();
	
       if (count($args) < 1) parent::__construct();
	
       else parent::__construct($args[0]);
    }

    /**
    *  Allow to know when the next Easter come
    *
    * @param date $year
    */
    public function WhenEasterCelebrates($year = null)
    {
        if (is_null($year))
        {
            $year = (int)date ('Y');
        }
        $iN = $year - 1900;
        $iA = $iN%19;
        $iB = floor (((7*$iA)+1)/19);
        $iC = ((11*$iA)-$iB+4)%29;
        $iD = floor ($iN/4);
        $iE = ($iN-$iC+$iD+31)%7;
        $time = 25-$iC-$iE;
        if($time > 0)
        {
            $WhenEasterCelebrates = strtotime ($year.'/04/'.$time);
        }
        else
        {
            $WhenEasterCelebrates = strtotime ($year.'/03/'.(31+$time));
        }
        return $WhenEasterCelebrates;
    }

    /**
    *  Allow to know when the next open day come
    *
    * @param date $Date in timestamp format
    * @param int $Delta
    * @param boolean $isMinus
    */
    public function WhenOpenDay($Date, $Delta, $isMinus = false, $calendarType = null)
    { 
        if ($calendarType <> 'calendar' && $calendarType <> 'workingDay') {
            $calendarType = $_SESSION['features']['type_calendar'];
        }
        if($calendarType == 'calendar'){
            if ($isMinus) {
                return date('Y-m-d H:i:s', $Date + (86400*-$Delta));
            } else {
                return date('Y-m-d H:i:s', $Date + (86400*$Delta));
            }

        }elseif($calendarType == 'workingDay'){

            $Hollidays = array (
                '1_1',
                '1_5',
                '8_5',
                '14_7',
                '15_8',
                '1_11',
                '11_11',
                '25_12'
            );
            require_once 'core/class/class_db_pdo.php';
            
            $db = new Database();
            $stmt = $db->query("select * from parameters where id like 'alert_stop%'");
            while ($result = $stmt->fetchObject()) {
                if ($result->param_value_date <> '') {
                    $compare = $this->compare_date($result->param_value_date, date("d-m-Y"));
                    //take the alert stop only if > now
                    if ($compare == 'date1' || $compare == 'equal') {
                        $dateExploded = explode("-", str_replace(" 00:00:00", "", $result->param_value_date));
                        array_push($Hollidays, (int)$dateExploded[2] . "_" . (int)$dateExploded[1]);
                    }
                }
            }
            //var_dump($Hollidays);
            
            if (function_exists ('easter_date')) {
                $WhenEasterCelebrates = easter_date((int)date('Y'), $Date);
            } else {
                $WhenEasterCelebrates = $this->getEaster((int)date('Y'), $Date);
            }
            $Hollidays[] = date ('j_n', $WhenEasterCelebrates);
            $Hollidays[] = date ('j_n', $WhenEasterCelebrates + (86400*39));
            $Hollidays[] = date ('j_n', $WhenEasterCelebrates + (86400*49));
            $iEnd = $Delta * 86400;

            $i = 0;
            while ($i < $iEnd) {
                $i = strtotime ('+1 day', $i);
                if ($isMinus) {
                    if (in_array(
                        date('w', $Date-$i),array (0,6)
                    ) || in_array (date ('j_n', $Date-$i), $Hollidays)
                    ) {
                        $iEnd = strtotime ('+1 day', $iEnd);
                        $Delta ++;
                    }
                } else {
                    if (
                        in_array(
                            date('w', $Date+$i),array (0,6)
                        ) || in_array (date ('j_n', $Date+$i), $Hollidays)
                    ) {
                        $iEnd = strtotime ('+1 day', $iEnd);
                        $Delta ++;
                    }
                }
            }
            if ($isMinus) {
                return date('Y-m-d H:i:s', $Date + (86400*-$Delta));
            } else {
                return date('Y-m-d H:i:s', $Date + (86400*$Delta));
            }

        }  

    }

    /**
    *  Allow to know the next date to treat
    *
    * @param int $delay Delay in days
    * @param boolean $isMinus true if minus
    */
    public function date_max_treatment($delay, $isMinus = false)
    {
        $result = $this->WhenOpenDay(
             strtotime (strftime("%Y")."-".strftime("%m")."-".strftime("%d")), 
            $delay, 
            $isMinus
        );
        return $result;
    }
    
    /**
    *  Allow to know the next date to treat
    *
    * @param int $delay Delay in days
    * @param boolean $isMinus true if minus
    */
    public function processDelayDate($date, $delay, $isMinus = false)
    {
        $date = date('Y-m-d', $date);
        $result = $this->WhenOpenDay(
            $date, 
            $delay, 
            $isMinus
        );
        return $result;
    }
    
    function dateFR2Time($date, $addHours = false)
    {
        if($addHours == false){
        list($day, $month, $year) = explode('/', $date);
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        return $timestamp;
        }elseif($addHours == true){
        list($day, $month, $year) = explode('/', $date);
        $timestamp = mktime(23, 59, 59, $month, $day, $year);
        return $timestamp;   
        }
    }
}
