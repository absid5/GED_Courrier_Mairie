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
*
* @brief List of structures for autocompletion
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

$query = "SELECT ca.id, ca.lastname as tag, ca.firstname, ca.contact_purpose_id, cp.label 
			FROM ".$_SESSION['tablename']['contact_addresses']." ca
			LEFT JOIN contact_purposes cp on ca.contact_purpose_id = cp.id	
			WHERE (lower(lastname) like lower(?)
			or lower(firstname) like lower(?)
			or lower(address_town) like lower(?)
			or lower(label) like lower(?))";
	$arrayPDO = array('%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%');
if(isset($_GET['id']) &&  $_GET['id'] <> ''){
	$query .= ' and ca.id <> ? and contact_id = ?';
	$arrayPDO = array_merge($arrayPDO, array($_GET['id'], $_SESSION['contact']['current_contact_id']));
} else if (isset($_REQUEST['idContact']) &&  $_REQUEST['idContact'] <> ''){
	$query .= ' and contact_id = ?';
	$arrayPDO = array_merge($arrayPDO, array($_REQUEST['idContact']));
} else if (isset($_REQUEST['reaffectAddress']) &&  $_REQUEST['reaffectAddress'] <> ''){
	$query .= ' and contact_id = ? and ca.id <> ?';
	$arrayPDO = array_merge($arrayPDO, array($_REQUEST['reaffectAddress'], $_SESSION['contact']['current_address_id']));
}

$query .= " order by lastname";
$stmt = $db->query($query, $arrayPDO);

$listArray = array();
while($line = $stmt->fetchObject())
{
	$listArray[$line->id] = $contact->get_label_contact($line->contact_purpose_id, $_SESSION['tablename']['contact_purposes']);
	
	if ($line->tag <> "" || $line->firstname) {
		$listArray[$line->id] .= " :";
		if ($line->tag <> "") {
			$listArray[$line->id] .= " " . $line->tag;
		}
		if ($line->firstname <> "") {
			$listArray[$line->id] .= " " . $line->firstname;
		}
	}

}

$query = "SELECT ca.id, c.lastname as tag, c.firstname, ca.contact_purpose_id, cp.label 
			FROM ".$_SESSION['tablename']['contact_addresses']." ca
			LEFT JOIN contact_purposes cp on ca.contact_purpose_id = cp.id LEFT JOIN contacts_v2 c on c.contact_id = ca.contact_id 
			WHERE (lower(c.lastname) like lower(?)
			or lower(c.firstname) like lower(?)
			or lower(address_town) like lower(?)
			or lower(label) like lower(?))";
	$arrayPDO = array('%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%', '%'.$_REQUEST['what'].'%');

$query .= " order by c.lastname";
$stmt = $db->query($query, $arrayPDO);

while($line = $stmt->fetchObject())
{
	$listArray[$line->id] = $contact->get_label_contact($line->contact_purpose_id, $_SESSION['tablename']['contact_purposes']);
	
	if ($line->tag <> "" || $line->firstname) {
		$listArray[$line->id] .= " :";
		if ($line->tag <> "") {
			$listArray[$line->id] .= " " . $line->tag;
		}
		if ($line->firstname <> "") {
			$listArray[$line->id] .= " " . $line->firstname;
		}
	}

}
echo "<ul>\n";
$authViewList = 0;

foreach($listArray as $key => $what)
{
	if($authViewList >= 10)
	{
		$flagAuthView = true;
	}
    echo "<li id=".functions::xssafe($key).">".functions::xssafe($what)."</li>\n";
	if($flagAuthView)
	{
		echo "<li id=".functions::xssafe($key).">...</li>\n";
		break;
	}
	$authViewList++;
}
echo "</ul>";
