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

require_once('core/class/class_core_tools.php');

$db = new Database();

$purpose = $_REQUEST['contact_purpose'];
$purpose_id = $_REQUEST['contact_purpose_id'];

if ($purpose_id <> "") {
	$stmt = $db->query("SELECT label FROM contact_purposes WHERE id= ?", array($purpose_id));
	$res = $stmt->fetchObject();
	if ($res->label == $purpose) {
		echo "{status : 0}";  // le user a clique sur la liste en autocompletion
		exit();
	} else {
		echo "{status : 1}"; // le label a été modifié apres autocompletion : on cree la nouvelle denomination
		exit();
	}
} else {
	echo "{status : 1}"; // nouvelle denomination
	exit ();

}