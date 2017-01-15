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
* @ingroup admin
*/

require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_request.php';
$db = new Database();

if (isset($_GET['mode']) && $_GET['mode'] == 'up') {
	$extra = ' AND contact_id = ? and ca_id = ? ';
	$arrayPDO = array($_SESSION['contact']['current_contact_id'], $_SESSION['contact']['current_address_id']);
} else if (isset($_GET['contactid']) && $_GET['contactid'] <> '' && isset($_GET['addressid']) && $_GET['addressid'] <> ''){
	$extra = ' AND contact_id = ? and ca_id = ? ';
	$arrayPDO = array($_GET['contactid'], $_GET['addressid']);
} 
else {
	$extra = ' ORDER BY ca_id DESC limit 1';
	$arrayPDO = array();
}

$stmt = $db->query("SELECT is_corporate_person, 
					contact_lastname, 
					contact_firstname, 
					society, 
					society_short, 
					contact_id, 
					ca_id, 
					lastname,
					firstname,
					address_num,
					address_street,
					address_town,
					address_postal_code,
					creation_date,
					contact_purpose_label,
					departement,
					update_date 
			FROM view_contacts 
			WHERE 1=1 " . $extra, $arrayPDO);
// $stmt->DebugDumpParams();
$res = $stmt->fetchObject();

$address = '';
$address = $res->address_num . ' ' . $res->address_street . ' ' . $res->address_postal_code . ' ' . strtoupper($res->address_town);

if($res->is_corporate_person == 'N') {
	$contact = $res->contact_lastname . ' ' . $res->contact_firstname;
	if($res->society_short <> '') {
		$contact .= ' (' . $res->society_short . ')';
	} else if($res->society <> '') {
		$contact .= ' (' . $res->society . ')';
	}
} else {
	$contact = $res->society;
	if($res->society_short <> '') {
		$contact .= ' (' . $res->society_short . ')';
	}
}

$contact .= ' - '. $res->contact_purpose_label ;

if ($res->departement <> '' || $res->lastname <> '' || $res->firstname <> '' || !empty($trimed)) {
	$contact .= ' :';
}

if ($res->departement <> '') {
	$contact .= ' ' . $res->departement . ' -';
}

if ($res->lastname <> '' || $res->firstname <> '') {
	$contact .= ' ' . $res->lastname . ' ' . $res->firstname;
}

$trimed = trim($address);
if (!empty($trimed)) {
	$contact .= ', ' . $address;
}

$contactId = $res->contact_id;
$addressId = $res->ca_id;

echo "{ status: 1, contactName: '" . addslashes($contact) . "', contactId: '" . $contactId . "', addressId: '" . $addressId . "'}";
exit;