<?php

/*
*    Copyright 2014-2015 Maarch
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
*
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/

$db = new Database();

$query = "SELECT contact_id, society, firstname, lastname, is_corporate_person FROM contacts_v2 WHERE enabled = 'Y'";
$arrayPDO = array();

if ($_REQUEST['type_id'] <> "all") {
	$query .= " AND contact_type = ? ";
	$arrayPDO = array($_REQUEST['type_id']);
}

$query .= " ORDER BY is_corporate_person desc, society, lastname";
$stmt = $db->query($query, $arrayPDO);

$contact_selected = array();

while($res = $stmt->fetchObject()){
	$contact = "";
	if ($res->is_corporate_person == "Y") {
		$contact = $res->society;
	} else if ($res->is_corporate_person == "N") {
		$contact = $res->lastname .' '. $res->firstname;
	}
	array_push($contact_selected, array('id' => $res->contact_id, 'name' => $contact ));
}

$frmStr = '';

$countsContact = count($contact_selected);

if ($countsContact == 0) {
	$frmStr .= '<option value="">Aucun contact</option>'; 
} else {
	if ($_REQUEST['mode'] != "view") {
		$frmStr .= '<option value="">Choisissez un contact</option>'; 		
	} else if($_REQUEST['mode'] == "view"){
		$frmStr .= '<option value="">Voir les contacts</option>'; 
	}
}
 
for ($cptsContacts = 0;$cptsContacts< $countsContact;$cptsContacts++) {
	$frmStr .= '<option value="'.functions::xssafe($contact_selected[$cptsContacts]['id']).'"';
	if ($_REQUEST['mode'] == "view") {
		$frmStr .= ' disabled ';
	}
	$frmStr .= '>'
	.  functions::xssafe($contact_selected[$cptsContacts]['name'])
	. '</option>';
}

echo $frmStr;
exit;
