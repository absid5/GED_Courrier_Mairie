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

/**
* @brief   Action : mark as read
*
* mark as read a mail so that it doesn't appear anymore in the basket
*
* @file
* @date $date$
* @version $Revision$
* @ingroup apps
*/

/**
* $confirm  bool true
*/
 $confirm = true;

/**
* $etapes  array Contains only one etap, the status modification
*/
 $etapes = array('markAsRead');


function manage_markAsRead($arr_id, $history, $id_action, $label_action, $status)
{
    $db = new Database();
    $result = '';
    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_security.php');
    require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
    $sec = new security();

    $ind_coll = $sec->get_ind_collection($_POST['coll_id']);
    $ext_table = $_SESSION['collections'][$ind_coll]['extensions'][0];

    for($i=0; $i<count($arr_id );$i++)
    {
        $result .= $arr_id[$i].'#';

        $stmt = $db->query("SELECT * FROM res_mark_as_read WHERE res_id = ? AND user_id = ? AND basket_id = ? AND coll_id = ?"
                            , array($arr_id[$i], $_SESSION['user']['UserId'], $_SESSION['current_basket']['id'], $_POST['coll_id']));

        $lineExist = false;
        while ($result1 = $stmt->fetchObject()) {
            $lineExist = true;
        }
        if (!$lineExist) {
            $query = "INSERT INTO res_mark_as_read VALUES(?, ?, ?, ?)";
            $db->query($query, array($_POST['coll_id'], $arr_id[$i], $_SESSION['user']['UserId'], $_SESSION['current_basket']['id']));
        }

    }
    return array('result' => $result, 'history_msg' => '');
 }
