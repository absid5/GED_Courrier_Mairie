<?php
/*
*
*   Copyright 2008-2015 Maarch
*
*   This file is part of Maarch Framework.
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
* @brief Manages the secondary baskets
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/


$_SESSION['error'] = "";
$core_tools = new core_tools();
$core_tools->load_lang();
require_once('modules/basket/class/class_modules_tools.php');
$db = new Database();
$bask = new basket();
$res = array();
//print_r($_REQUEST);
if (
	isset($_REQUEST['baskets_owner']) 
	&& !empty($_REQUEST['baskets_owner'])
) {
	$basketOwner = $_REQUEST['baskets_owner'];
} else {
	$_SESSION['error'] = _BASKETS_OWNER.' '._MISSING;
}

if(
	isset($_REQUEST['basketId']) 
	&& is_array($_REQUEST['basketId'])
	&& count($_REQUEST['basketId']) >0
) {
	$arrayOfBaskets = array();
	for ($cpt=0;$cpt<count($_REQUEST['basketId']);$cpt++) {
		$values = explode('##', $_REQUEST['basketId'][$cpt]);
		array_push(
			$arrayOfBaskets, 
			array(
				'basketId' => $values[0],
				'groupId' => $values[1],
			)
		);
	}
} elseif($_REQUEST['basketId'] != NULL) {
	$_SESSION['error'] = _NO_CHOOSEN_BASKET;
}
if (!empty($_SESSION['error'])) {
	functions::xecho( $_SESSION['error']);
} else {
	$stmt = $db->query("delete from user_baskets_secondary where user_id = ?",array($basketOwner));	
	for ($i=0;$i<count($arrayOfBaskets);$i++) {
		$stmt = $db->query("insert into user_baskets_secondary (user_id, group_id, basket_id) VALUES (?,?,?)",array($basketOwner,$arrayOfBaskets[$i]['groupId'],$arrayOfBaskets[$i]['basketId']));
	}	
	?>
	<script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=users_management_controler&admin=users&mode=list';</script>
	<?php
	exit();
}
