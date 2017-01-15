<?php

/*
*   Copyright 2010 Maarch
*
*      This file is part of Maarch Framework.
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
* @brief  List of docservers for autocompletion
*
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

require_once(
    'core/class/class_request.php'
);
require_once(
    'core/core_tables.php'
);

$query = 'SELECT ';
    $query .= 'docserver_id as tag ';
$query .= 'FROM ';
    $query .= _DOCSERVERS_TABLE_NAME . ' ';
$query .= 'WHERE ';
        $query .= "lower(docserver_id) ";
    $query .= "like ";
        $query .= "lower(?) ";
$query .= 'ORDER BY ';
    $query .= 'docserver_id';

$db = new Database();
$stmt = $db->query($query, array($_REQUEST['what'] . '%'));

$listArray = array();
while($line = $stmt->fetchObject()) {
    array_push($listArray, $line->tag);
}

echo '<ul style="z-index: 9998;">';
$authViewList = 0;
$flagAuthView = false;
foreach($listArray as $what) {
    if($authViewList >= 10)
        $flagAuthView = true;
    
    if(stripos($what, $_REQUEST['what']) === 0) {
        echo '<li style="z-index: 9999;">' . $what . '</li>';
        if($flagAuthView) {
            echo '<li style="z-index: 9999;">...</li>';
            break;
        }
        $authViewList++;
    }
}
echo '</ul>';
