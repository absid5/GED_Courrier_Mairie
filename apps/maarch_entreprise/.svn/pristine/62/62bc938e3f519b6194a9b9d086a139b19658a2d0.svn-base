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
* @brief   Action : simple confirm
*
* Open a modal box to confirm a status modification. Used by the core (manage_action.php page).
*
* @file
* @author Claire Figueras <dev@maarch.org>
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
 $etapes = array('close');


function manage_close($arr_id, $history, $id_action, $label_action, $status)
{
	$result = '';
	require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_security.php');
	require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_request.php');
	$sec = new security();
	$db = new Database();

	$ind_coll = $sec->get_ind_collection($_POST['coll_id']);
	$ext_table = $_SESSION['collections'][$ind_coll]['extensions'][0];

	for($i=0; $i<count($arr_id );$i++)
	{
		$result .= $arr_id[$i].'#';
		$db->query("UPDATE ".$ext_table. " SET closing_date = CURRENT_TIMESTAMP WHERE res_id = ?", array($arr_id[$i]));

	}
    $_SESSION['indexing']['category_id'] = 'outgoing';
    return array(
        'result' => $result,
        'history_msg' => '',
        'page_result' => $_SESSION['config']['businessappurl']
                         . 'index.php?page=view_baskets&module=basket&baskets=IndexingBasket'
    );
 }
