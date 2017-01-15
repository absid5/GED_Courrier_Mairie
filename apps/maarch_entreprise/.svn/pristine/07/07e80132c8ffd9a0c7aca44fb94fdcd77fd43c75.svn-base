<?php
/*
*    Copyright 2014 Maarch
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
* @brief List of users for autocompletion filter
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("apps".DIRECTORY_SEPARATOR."maarch_entreprise".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");
$contact = new contacts_v2();
$db = new Database();
$listArray = array();
$query = "SELECT contact_type, society, lastname, firstname, contact_id, is_corporate_person, society_short FROM "
	.$_SESSION['tablename']['contacts_v2']." WHERE ((lower(lastname) like lower(:what) "
	."or lower(firstname) like lower(:what) "
    ."or lower(society_short) like lower(:what) "
	."or lower(society) like lower(:what))) ";

$arrayPDO = array(":what" => '%'.$_REQUEST['what'].'%');

if(isset($_GET['id']) &&  $_GET['id'] <> ''){
    $query .= ' and contact_id <> :contactid';
    $arrayPDO[":contactid"] = $_GET['id'];
}

if(isset($_GET['my_contact']) &&  $_GET['my_contact'] == 'Y'){
    $query .= " and user_id = :userid";
    $arrayPDO[":userid"] = $_SESSION['user']['UserId'];
}

$query .= " order by lastname";
$stmt = $db->query($query, $arrayPDO);

if(isset($_GET['id']) &&  $_GET['id'] <> ''){
    while($line = $stmt->fetchObject())
    {
        $listArray[$line->contact_id] = $contact->get_label_contact($line->contact_type, $_SESSION['tablename']['contact_types']) . ' : ' . $line->society . ', '. $line->lastname . ' '. $line->firstname;
    }
} else {
    while ($line = $stmt->fetchObject()) {
        if($line->is_corporate_person == 'N'){
        	$listArray[$line->contact_id] = functions::show_string($line->lastname)." ".functions::show_string($line->firstname);
            if($line->society <> ''){
                $listArray[$line->contact_id] .= ' ('.$line->society.')';
            }
        } else {
            $listArray[$line->contact_id] .= $line->society;
            if($line->society_short <> ''){
                $listArray[$line->contact_id] .= ' ('.$line->society_short.')';
            }
        }
    }
}

$query = "SELECT lastname, firstname, user_id FROM users WHERE (lower(lastname) like lower(:what) "
    ."or lower(firstname) like lower(:what) "
    ."or user_id like :what) and (status = 'OK' or status = 'ABS') and enabled = 'Y' ORDER BY lastname";

$arrayPDO = array(":what" => '%'.$_REQUEST['what'].'%');

$stmt = $db->query($query, $arrayPDO);

while ($line = $stmt->fetchObject()) {
    $listArray[$line->user_id] .= $line->firstname . " " . $line->lastname;
}

echo "<ul>\n";
$authViewList = 0;
$flagAuthView = false;
foreach ($listArray as $key => $what) {
    if ($authViewList >= 10) {
        $flagAuthView = true;
    }
    echo "<li id=".$key.">".$what."</li>\n";
    if($flagAuthView) {
        echo "<li id=".$key.">...</li>\n";
        break;
    }
    $authViewList++;
}
echo "</ul>";
