<?php
/*
*   Copyright 2008, 2015 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Script used by an Ajax autocompleter object to get the contacts data (from users or contacts)
*
* @file autocomplete_contacts.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
require_once('core/class/class_request.php');

$req = new request();
$db = new Database();

if (empty($_REQUEST['table'])) {
    exit();
}
$table = $_REQUEST['table'];
$_SESSION['is_multi_contact'] = 'OK';
$multi_sessions_address_id = $_SESSION['adresses']['addressid'];
$user_ids = array();
// $user_ids = '';
$address_ids = array();
// $address_ids = '';

if(count($multi_sessions_address_id) > 0){
    for ($imulti=0; $imulti <= count($multi_sessions_address_id); $imulti++) { 
        if (is_numeric($multi_sessions_address_id[$imulti])) {
            array_push($address_ids, $multi_sessions_address_id[$imulti]);
        } else {
            array_push($user_ids, "'".$multi_sessions_address_id[$imulti]."'");
        }
    }

    if (!empty($address_ids)) {
        $addresses = implode(' ,', $address_ids);
        $request_contact = " and ca_id not in (".$addresses.")";
    } else {
        $request_contact = ''; 
    }

    if (!empty($user_ids)) {
        $users = implode(' ,', $user_ids);
        $request_user = " and user_id not in (".$users.")";
    } else {
        $request_user = ''; 
    }
} else{
    $request_user = '';
    $request_contact = ''; 
}

if ($_SESSION['is_multi_contact'] == 'OK') {
    $noResultUsers = false;
    $noResultContacts = false;

    //USERS
    $select = array();
    $select[$_SESSION['tablename']['users']]= array('lastname', 'firstname', 'user_id');
    $where = " (lower(lastname) like lower(:input) "
        ."or lower(firstname) like lower(:input) "
        ."or user_id like :input) and (status = 'OK' or status = 'ABS') and enabled = 'Y'".$request_user;
    $other = 'order by lastname, firstname';
    $arrayPDO = array(":input" => "%".$_REQUEST['Input']."%");
    $res = $req->PDOselect($select, $where, $arrayPDO, $other, $_SESSION['config']['databasetype'], 11,false,"","","", false);
    //echo "<ul>\n";
    echo "<ul id=\"autocomplete_contacts_ul\" title='".$_REQUEST['contact_type'] . " [".$_REQUEST['Input']."] = $nb contacts'>";
    for ($i=0; $i< min(count($res), 5)  ;$i++) {
        echo "<li id='".$res[$i][2]['value'].", '><i class='fa fa-users fa-1x'></i> ".$req->show_string($res[$i][0]['value'])." ".$req->show_string($res[$i][1]['value'])."</li>\n";
    }
    if($i == 0){
        $noResultUsers = true;
    }

    //CONTACTS
    $timestart=microtime(true);
   
   $arrayPDO = array();
   if ((isset($_REQUEST['contact_type']) && $_REQUEST['contact_type'] <> '') && $_SESSION['is_multi_contact'] =! 'OK') {
       $contactTypeRequest = " AND contact_type = ?";
       $arrayPDOtype = array($_REQUEST['contact_type']);
   }
   
    $Input = $_REQUEST['Input'];
    $boldInput = strtoupper($Input);
    $ucwordsInput = ucwords($Input);
    $Input = $Input . ' ' . $boldInput . ' ' . $ucwordsInput;
    
    $args = explode(' ', $Input);
    $args[] = $Input;
    $args_bold = array();
    foreach ($args as $key => $value) {
        $args_bold[$key] = '<b>'. $value . '</b>';
    }
    $num_args = count($args);
    if ($num_args == 0) return "<ul></ul>"; 
       
    $query = "SELECT result, SUM(confidence) AS score, count(1) AS num, address, contact_id, ca_id FROM (";

    $subQuery = 
        "SELECT "
            . "(CASE "
                . " WHEN is_corporate_person = 'Y' THEN society ||" 
                    . "(CASE "
                        . " WHEN society_short <> '' THEN  ' (' || society_short || ') -'"
                        . " WHEN society_short = '' THEN ' -'"
                    . " END)"
                . " WHEN is_corporate_person = 'N' THEN UPPER(contact_lastname) || ' ' || contact_firstname ||"
                    . "(CASE "
                        . " WHEN society_short <> '' THEN  ' (' || society_short || ') -'"
                        . " WHEN society <> '' THEN  ' (' || society || ') -'"
                        . " WHEN society = '' THEN  '  -'"
                    . " END)"
            . " END) AS result, "
            . " %d AS confidence, "
            . " contact_id, ca_id, "
            ."(CASE "
                . " WHEN is_private = 'N' THEN contact_purpose_label || ' :' ||" 
                    . "(CASE "
                        . " WHEN departement <> '' THEN ' ' || departement"
                        . " WHEN departement = '' THEN ('')"
                    . " END) || " 
                    . "(CASE "
                        . " WHEN is_corporate_person = 'Y' THEN '' ||" 
                            . "(CASE "
                                . " WHEN lastname <> '' THEN ' ' || UPPER(lastname)"
                                . " WHEN lastname = '' THEN ('')"                           
                            . " END)"
                        . " WHEN is_corporate_person = 'N' THEN '' "
                    . " END) || "
                    . "(CASE "
                        . " WHEN is_corporate_person = 'Y' THEN '' ||" 
                            . "(CASE "
                                . " WHEN firstname <> '' THEN ' ' || UPPER(firstname)"
                                . " WHEN firstname = '' THEN ('')"                           
                            . " END)"
                        . " WHEN is_corporate_person = 'N' THEN '' "
                    . " END) || "
                    . "(CASE "
                        . " WHEN address_num <> '' THEN ' ' || address_num"
                        . " WHEN address_num = '' THEN ('')"
                    . " END) || "
                    . "(CASE "
                        . " WHEN address_street <> '' THEN ' ' || address_street"
                        . " WHEN address_street = '' THEN ('')"
                    . " END) || " 
                    . "(CASE "
                        . " WHEN address_postal_code <> '' THEN ' ' || address_postal_code"
                        . " WHEN address_postal_code = '' THEN ('')"
                    . " END) || " 
                    . "(CASE "
                        . " WHEN address_town <> '' THEN ' ' || address_town"
                        . " WHEN address_town = '' THEN ('')"
                    . " END) || "                     

                    . "(CASE "
                        . " WHEN address_country <> '' THEN ' - ' || UPPER(address_country)"
                        . " WHEN address_country = '' THEN ('')"
                    . " END)"
                . " WHEN is_private = 'Y' THEN ' (coordonnees confidentielles) '"
            ." END)"   
            . "AS address"
        . " FROM view_contacts"
        . " WHERE (1=1) and enabled = 'Y' "
            . $contactTypeRequest
            . " AND ("
                . " LOWER(contact_lastname) LIKE LOWER('%s')"
                . " OR LOWER(contact_firstname) LIKE LOWER('%s')"
                . " OR LOWER(society) LIKE LOWER('%s')"
                . " OR LOWER(society_short) LIKE LOWER('%s')"
                . " OR LOWER(lastname) LIKE LOWER('%s')"
                . " OR LOWER(firstname) LIKE LOWER('%s')"
                . " OR LOWER(contact_purpose_label) LIKE LOWER('%s')"
                . " OR LOWER(departement) LIKE LOWER('%s')"
                . " OR LOWER(address_town) LIKE LOWER('%s')"
            .")".$request_contact;
     
    $queryParts = array();
    foreach($args as $arg) {
        $arg = $req->protect_string_db($arg);
        if(strlen($arg) == 0) continue;
        # Full match of one given arg
        $expr = $arg;
        $conf = 100;
        $queryParts[] = sprintf($subQuery, $conf, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr); 

        # Partial match (starts with)
        $expr = $arg . "%"; ;
        $conf = 34; # If found, partial match contains will also be so score is sum of both confidences, i.e. 67)
        $queryParts[] = sprintf($subQuery, $conf, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr); 
      
        # Partial match (contains)
        $expr = "%" . $arg . "%";
        $conf = 33;
        $queryParts[] = sprintf($subQuery, $conf, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr); 
    }
    $query .= implode (' UNION ALL ', $queryParts);
    $query .= ") matches" 
        . " GROUP BY result, contact_id, address, ca_id "
        . " ORDER BY score DESC, result ASC";
    $stmt = $db->query($query, $arrayPDO);
    $nb = $stmt->rowCount();
    
    $m = 30;
    if ($nb >= $m) $l = $m;
    else $l = $nb;
    
    $timeend=microtime(true);
    $time = number_format(($timeend-$timestart), 3);

    $found = false;
    // echo "<ul id=\"autocomplete_contacts_ul\" title='".$_REQUEST['contact_type'] . " [".$_REQUEST['Input']."] = $nb contacts'>";
    for ($i=0; $i<$l; $i++) {
        $res = $stmt->fetchObject();
        $score = round($res->score / $num_args);
        if ($score > 100) {
            $score = 100;
        }
        if ($i%2==1) $color = 'LightYellow';
        else $color = 'white';
        echo "<li id='".$res->contact_id.",".$res->ca_id."' style='font-size:8pt; background-color:$color;' title='confiance:".$score."%'><i class='fa fa-book fa-1x'></i> "
                . str_replace($args, $args_bold, $res->result) 
                ."<br/> "
                . str_replace($args, $args_bold, $res->address)
            ."</li>";
    }
    if($nb == 0) echo "<li></li>";
    echo "</ul>";
    //if($nb == 0) echo "<p align='left' style='background-color:LemonChiffon;' title=\"Aucun résultat trouvé, veuillez compléter votre recherche.\" >...</p>"; 
    if($nb == 0){
        $noResultContacts = true;
    }
    if ($nb > $m) echo "<p align='left' style='background-color:LemonChiffon;' title=\"La liste n'a pas pu être affichée intégralement, veuillez compléter votre recherche.\" >...</p>";

    if($noResultUsers && $noResultContacts){
        echo "<p align='left' style='background-color:LemonChiffon;text-align:center;color:grey;font-style:italic;' title=\"Aucun résultat trouvé, veuillez compléter votre recherche.\" >Aucun résultat trouvé.</p>";
    }

} else {
    if ($table == 'users') {
        $select = array();
        $select[$_SESSION['tablename']['users']]= array('lastname', 'firstname', 'user_id');
        $where = " (lower(lastname) like lower(:input) "
            ."or lower(firstname) like lower(:input) "
            ."or user_id like :input) and (status = 'OK' or status = 'ABS') and enabled = 'Y'";
        $other = 'order by lastname, firstname';
        $arrayPDO = array(":input" => "%".$_REQUEST['Input']."%");
        $res = $req->PDOselect($select, $where, $arrayPDO, $other, $_SESSION['config']['databasetype'], 11,false,"","","", false);
        echo "<ul>\n";
        for ($i=0; $i< min(count($res), 10)  ;$i++) {
            echo "<li id='".$res[$i][2]['value'].", '>".$req->show_string($res[$i][0]['value'])." ".$req->show_string($res[$i][1]['value'])."</li>?><i class='fa fa-users fa-1x'></i>\n";
        }
        if (count($res) == 11) {
                echo "<li>...</li>\n";
        }
        echo "</ul>";
    } elseif ($table == 'contacts') {
        $timestart=microtime(true);
       
        $Input = $_REQUEST['Input'];
        $boldInput = strtoupper($Input);
        $ucwordsInput = ucwords($Input);
        $Input = $Input . ' ' . $boldInput . ' ' . $ucwordsInput;

        $args = explode(' ', $Input);
        $args[] = $Input;
        $args_bold = array();
        foreach ($args as $key => $value) {
            $args_bold[$key] = '<b>'. $value . '</b>';
        }
        $num_args = count($args);

        if ($num_args == 0) return "<ul></ul>"; 
           
        $query = "SELECT result, SUM(confidence) AS score, count(1) AS num, address, contact_id, ca_id FROM (";
        
        $subQuery = 
        "SELECT "
            . "(CASE "
                . " WHEN is_corporate_person = 'Y' THEN society ||" 
                    . "(CASE "
                        . " WHEN society_short <> '' THEN  ' (' || society_short || ') -'"
                        . " WHEN society_short = '' THEN ' -'"
                    . " END)"
                . " WHEN is_corporate_person = 'N' THEN UPPER(contact_lastname) || ' ' || contact_firstname ||"
                    . "(CASE "
                        . " WHEN society_short <> '' THEN  ' (' || society_short || ') -'"
                        . " WHEN society <> '' THEN  ' (' || society || ') -'"
                        . " WHEN society = '' THEN  '  -'"
                    . " END)"
            . " END) AS result, "
            . " %d AS confidence, "
            . " contact_id, ca_id, "
            ."(CASE "
                . " WHEN is_private = 'N' THEN contact_purpose_label || ' :' ||" 
                    . "(CASE "
                        . " WHEN departement <> '' THEN ' ' || departement"
                        . " WHEN departement = '' THEN ('')"
                    . " END) || " 
                    . "(CASE "
                        . " WHEN is_corporate_person = 'Y' THEN '' ||" 
                            . "(CASE "
                                . " WHEN lastname <> '' THEN ' ' || UPPER(lastname)"
                                . " WHEN lastname = '' THEN ('')"                           
                            . " END)"
                        . " WHEN is_corporate_person = 'N' THEN '' "
                    . " END) || "
                    . "(CASE "
                        . " WHEN is_corporate_person = 'Y' THEN '' ||" 
                            . "(CASE "
                                . " WHEN firstname <> '' THEN ' ' || UPPER(firstname)"
                                . " WHEN firstname = '' THEN ('')"                           
                            . " END)"
                        . " WHEN is_corporate_person = 'N' THEN '' "
                    . " END) || "
                    . "(CASE "
                        . " WHEN address_num <> '' THEN ' ' || address_num"
                        . " WHEN address_num = '' THEN ('')"
                    . " END) || "
                    . "(CASE "
                        . " WHEN address_street <> '' THEN ' ' || address_street"
                        . " WHEN address_street = '' THEN ('')"
                    . " END) || " 
                    . "(CASE "
                        . " WHEN address_postal_code <> '' THEN ' ' || address_postal_code"
                        . " WHEN address_postal_code = '' THEN ('')"
                    . " END) || " 
                    . "(CASE "
                        . " WHEN address_town <> '' THEN ' ' || address_town"
                        . " WHEN address_town = '' THEN ('')"
                    . " END) || "                     

                    . "(CASE "
                        . " WHEN address_country <> '' THEN ' - ' || UPPER(address_country)"
                        . " WHEN address_country = '' THEN ('')"
                    . " END)"
                . " WHEN is_private = 'Y' THEN ' (coordonnees confidentielles) '"
            ." END)"   
            . "AS address"
        . " FROM view_contacts"
            . " WHERE (1=1) and enabled = 'Y' "
                . $contactTypeRequest
                . " AND ("
                    . " LOWER(contact_lastname) LIKE LOWER('%s')"
                    . " OR LOWER(contact_firstname) LIKE LOWER('%s')"
                    . " OR LOWER(society) LIKE LOWER('%s')"
                    . " OR LOWER(society_short) LIKE LOWER('%s')"
                    . " OR LOWER(lastname) LIKE LOWER('%s')"
                    . " OR LOWER(firstname) LIKE LOWER('%s')"
                    . " OR LOWER(contact_purpose_label) LIKE LOWER('%s')"
                    . " OR LOWER(departement) LIKE LOWER('%s')"
                    . " OR LOWER(address_town) LIKE LOWER('%s')"
                .")";
        
        $queryParts = array();
        foreach($args as $arg) {
            $arg = $req->protect_string_db($arg);
            if(strlen($arg) == 0) continue;
            # Full match of one given arg
            $expr = $arg;
            $conf = 100;
            $queryParts[] = sprintf($subQuery, $conf, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr); 

            # Partial match (starts with)
            $expr = $arg . "%"; ;
            $conf = 34; # If found, partial match contains will also be so score is sum of both confidences, i.e. 67)
            $queryParts[] = sprintf($subQuery, $conf, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr); 
          
            # Partial match (contains)
            $expr = "%" . $arg . "%";
            $conf = 33;
            $queryParts[] = sprintf($subQuery, $conf, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr, $expr); 
        }
        $query .= implode (' UNION ALL ', $queryParts);
        $query .= ") matches" 
            . " GROUP BY result, contact_id, address, ca_id "
            . " ORDER BY score DESC, result ASC";
        
        $stmt = $db->query($query, $arrayPDOtype);
        $nb = $stmt->rowCount();
        
        $m = 30;
        if ($nb >= $m) $l = $m;
        else $l = $nb;
        
        $timeend=microtime(true);
        $time = number_format(($timeend-$timestart), 3);

        $found = false;
        echo "<ul id=\"autocomplete_contacts_ul\" title='".$_REQUEST['contact_type'] . " [".$_REQUEST['Input']."] = $nb contacts'>";
        for ($i=0; $i<$l; $i++) {
            $res = $stmt->fetchObject();
            $score = round($res->score / $num_args);
            if ($score > 100) {
                $score = 100;
            }
            if ($i%2==1) $color = 'LightYellow';
            else $color = 'white';
            echo "<li id='".$res->contact_id.",".$res->ca_id."' style='font-size:8pt; background-color:$color;' title='confiance:".$score."%'>"
                    . str_replace($args, $args_bold, $res->result) 
                    ."<br/> "
                    . str_replace($args, $args_bold, $res->address)
                ."<i class='fa fa-book fa-1x'></i></li>";
        }
        if($nb == 0) echo "<li></li>";
        echo "</ul>";
        if($nb == 0) echo "<p align='left' style='background-color:LemonChiffon;' title=\"Aucun résultat trouvé, veuillez compléter votre recherche.\" >...</p>"; 
        if ($nb > $m) echo "<p align='left' style='background-color:LemonChiffon;' title=\"La liste n'a pas pu être affichée intégralement, veuillez compléter votre recherche.\" >...</p>";
    }
}

//$_SESSION['is_multi_contact'] = '';
