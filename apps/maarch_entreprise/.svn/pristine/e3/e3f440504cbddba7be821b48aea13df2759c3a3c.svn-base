<?php
/*
*    Copyright 2008,2009 Maarch
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
* @brief List of users for autocompletion
*
*
* @file
* @author  Laurent Giovannoni <dev@maarch.org>
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
	.$_SESSION['tablename']['contacts_v2']." WHERE ((lower(lastname) like lower(?) "
	."or lower(firstname) like lower(?) "
    ."or lower(society_short) like lower(?) "
	."or lower(society) like lower(?))) ";

$arrayPDO = array('%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%');

if(isset($_GET['id']) &&  $_GET['id'] <> ''){
    $query .= ' and contact_id <> ?';
    $arrayPDO = array_merge($arrayPDO, array($_GET['id']));
}

if(isset($_GET['my_contact']) &&  $_GET['my_contact'] == 'Y'){
    $query .= " and user_id = ?";
    $arrayPDO = array_merge($arrayPDO, array($_SESSION['user']['UserId']));
}

$query .= " order by lastname";
$stmt = $db->query($query, $arrayPDO);

if(isset($_GET['id']) &&  $_GET['id'] <> ''){
    while($line = $stmt->fetchObject())
    {
        $listArray[$line->contact_id] = $contact->get_label_contact($line->contact_type, $_SESSION['tablename']['contact_types']) . ' : ';
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

echo "<ul>\n";
$authViewList = 0;
$flagAuthView = false;
foreach ($listArray as $key => $what) {
    if ($authViewList >= 10) {
        $flagAuthView = true;
    }
    echo "<li id=".functions::xssafe($key).">".functions::xssafe($what)."</li>\n";
    if($flagAuthView) {
        echo "<li id=".functions::xssafe($key).">...</li>\n";
        break;
    }
    $authViewList++;
}
echo "</ul>";
