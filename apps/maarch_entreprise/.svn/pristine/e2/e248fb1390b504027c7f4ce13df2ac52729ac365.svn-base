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

require_once('modules/entities/class/class_manage_entities.php');
$ent = new entity();
$my_tab_entities_id = $ent->get_all_entities_id_user($_SESSION['user']['entities']);

if ($_SESSION['user']['UserId'] != 'superadmin') {
    $whereSecurityOnEntities = " and (users.user_id != 'superadmin' and (users_entities.entity_id in (" 
        . join(',', $my_tab_entities_id) . ")))";
} else {
    $whereSecurityOnEntities = " and (users.user_id != 'superadmin')";
}

if ($whereSecurityOnEntities == '') {
    $whereSecurityOnEntities = " and 1=1 ";
}

$db = new Database();
$stmt = $db->query(
    "SELECT DISTINCT(users.user_id), CONCAT(users.lastname,' ',users.firstname) as tag FROM users, users_entities "
    . " WHERE ("
        . "lower(users.lastname) like lower(:what) "
        . " or lower(users.user_id) like lower(:what) "
        . " or lower(users.firstname) like lower(:what) "
    . ") and users.status <> 'DEL' " . $whereSecurityOnEntities . " and (users.user_id = users_entities.user_id) "
    . " order by tag",
    array(':what' => '%'.$_REQUEST['what'].'%')
);

$listArray = array();
while ($line = $stmt->fetchObject()) {
    array_push($listArray, $line->tag);
}
echo "<ul>\n";
$authViewList = 0;
$flagAuthView = false;
foreach ($listArray as $what) {
    if (isset($authViewList ) && $authViewList>= 10) {
        $flagAuthView = true;
    }
    //if(stripos($what, $_REQUEST['what']) === 0)
    //{
        echo "<li>".$what."</li>\n";
        if ($flagAuthView) {
            echo "<li>...</li>\n";
            break;
        }
        $authViewList++;
    //}
}
echo "</ul>";
