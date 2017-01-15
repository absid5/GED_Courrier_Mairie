<?php
/*
*
*    Copyright 2013 Maarch
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
* @brief    List of email adress for autocompletion
*
* @file     adresss_autocomletion.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  sendmail
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$db = new Database();

    $timestart=microtime(true);
   
    $args = explode(' ', $_REQUEST['what']);
    $args[] = $_REQUEST['what'];
    $num_args = count($args);
    if($num_args == 0) return "<ul></ul>"; 
       
    $query = "SELECT result, SUM(confidence) AS score, count(1) AS num FROM (";
    $subQuery = array();
    $subQuery[1] = "SELECT UPPER(lastname) || ' ' || firstname || ' (' || mail || ')' AS result, "
                . ' %d AS confidence, mail AS email '
                . "FROM users"
                . " WHERE enabled ='Y' AND "
		. "(LOWER(lastname) LIKE LOWER('%s') OR LOWER(firstname) LIKE LOWER('%s') OR LOWER(user_id) LIKE LOWER('%s') OR LOWER(mail) LIKE LOWER('%s'))";

    $subQuery[2]= 
        "SELECT "
            . "( "
                . " UPPER(lastname)) || ' '|| firstname || "
            . "' (' || email || ')' AS result, "
            . ' %d AS confidence, email'
        . " FROM contact_addresses"
        . " WHERE  "
            . " enabled = 'Y' AND email <> ''"
            . " AND ("
                . " LOWER(lastname) LIKE LOWER('%s')"
                . " OR LOWER(firstname) LIKE LOWER('%s')"
        . " OR LOWER(email) LIKE LOWER('%s')"
            .")"
        ."and (is_private = 'N' or ( user_id = '".$_SESSION['user']['UserId']."' and is_private = 'Y'))";



    $queryParts = array();
for($i=1;$i<3;$i++){
    foreach($args as $arg) {
        if(strlen($arg) == 0) continue;
        # Full match of one given arg
        $expr = $arg;
        $conf = 100;
        $queryParts[] = sprintf($subQuery[$i], $conf, $expr, $expr, $expr, $expr); 

        # Partial match (starts with)
        $expr = $arg . "%"; ;
        $conf = 34; # If found, partial match contains will also be so score is sum of both confidences, i.e. 67)
        $queryParts[] = sprintf($subQuery[$i], $conf, $expr, $expr, $expr, $expr);
      
        # Partial match (contains)
        $expr = "%" . $arg . "%";
        $conf = 33;
        $queryParts[] = sprintf($subQuery[$i], $conf, $expr, $expr, $expr, $expr);
    }
}
    $query .= implode (' UNION ALL ', $queryParts);
    $query .= ") matches" 
        . " GROUP BY result "
        . " ORDER BY score DESC, result ASC";
    
    $stmt = $db->query($query);
    $nb = $stmt->rowCount();
    $m = 30;
    if($nb >= $m) $l = $m;
    else $l = $nb;
    
    $timeend=microtime(true);
    $time = number_format(($timeend-$timestart), 3);

    $found = false;
    echo "<ul title='$l contacts found in " . $time."sec'>";
    for($i=0; $i<$l; $i++) {
        $res = $stmt->fetchObject();
        $score = round($res->score / $num_args);
        if($i%2==1) $color = 'LightYellow';
        else $color = 'white';
        echo "<li style='font-size: 8pt; background-color:$color;' title='confiance:".$score."%' id='".$res->email."'>". $res->result ."</li>";
    }
    if($nb == 0) echo "<li></li>";
    echo "</ul>";
    if($nb == 0) echo "<p align='left' style='background-color:LemonChiffon;' title=\"Aucun résultat trouvé, veuillez compléter votre recherche.\" >...</p>"; 
    if($nb > $m) echo "<p align='left' style='background-color:LemonChiffon;' title=\"La liste n'a pas pu être affichée intégralement, veuillez compléter votre recherche.\" >...</p>";
        
