<?php

/*
*    Copyright 2008,2015 Maarch
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

$core = new core_tools();
$core->test_user();

require_once 'core/class/class_db_pdo.php';
$db = new Database();

if ($_POST['contact_target'] == "") {
    $_POST['contact_target'] = "corporate";
}
if ($_POST['can_add_contact'] == "") {
    $stmt = $db->query("SELECT id, label FROM contact_types WHERE contact_target = ? or contact_target = 'both' or contact_target is null ORDER BY label", array($_POST['contact_target']));
} else {
    $stmt = $db->query("SELECT id, label FROM contact_types WHERE can_add_contact = ? AND (contact_target = ? or contact_target = 'both' or contact_target is null) ORDER BY label", array($_POST['can_add_contact'], $_POST['contact_target']));
}


$frmStr = '';

$frmStr .= '<option value="">' . _CHOOSE_CONTACT_TYPES . '</option>';

$iCount = 0;

while($res = $stmt->fetchObject()){
   	$frmStr .= '<option value="'. functions::xssafe($res->id).'"';

    if(isset($_SESSION['m_admin']['contact']['CONTACT_TYPE']) && $res->id == $_SESSION['m_admin']['contact']['CONTACT_TYPE'] )
    {
        $frmStr .= 'selected="selected"';
    } else if ($_POST['contact_target'] == "no_corporate" && $iCount == 0) {
        $frmStr .= 'selected="selected"';
    }

    $frmStr .= '>';
       $frmStr .= functions::xssafe($res->label);
    $frmStr .= '</option>';
    
    $iCount++;
}

echo $frmStr;
exit;
