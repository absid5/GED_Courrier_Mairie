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
* @brief   Manage basket order
*
*
* @file
* @author  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$admin = new core_tools();
$db = new Database();

$admin->test_admin('admin_baskets', 'basket');

$tmpArray = array();

if ($_GET['mode'] == "up") {
	$tmpArray = $_SESSION['basket_order'][$_GET['basketIndex']-1];
	$_SESSION['basket_order'][$_GET['basketIndex']-1] = $_SESSION['basket_order'][$_GET['basketIndex']];
	$_SESSION['basket_order'][$_GET['basketIndex']] = $tmpArray;

} else if ($_GET['mode'] == "topup") {
	$tmpArray = $_SESSION['basket_order'][$_GET['basketIndex']];
	unset($_SESSION['basket_order'][$_GET['basketIndex']]);
	$_SESSION['basket_order'] = array_values($_SESSION['basket_order']);
	$_SESSION['basket_order'] = array_merge(array($tmpArray), $_SESSION['basket_order']);

} else if ($_GET['mode'] == "down") {
	$tmpArray = $_SESSION['basket_order'][$_GET['basketIndex']+1];
	$_SESSION['basket_order'][$_GET['basketIndex']+1] = $_SESSION['basket_order'][$_GET['basketIndex']];
	$_SESSION['basket_order'][$_GET['basketIndex']] = $tmpArray;

} else if ($_GET['mode'] == "topdown") {
	$tmpArray = $_SESSION['basket_order'][$_GET['basketIndex']];
	unset($_SESSION['basket_order'][$_GET['basketIndex']]);
	$_SESSION['basket_order'] = array_values($_SESSION['basket_order']);
	array_push($_SESSION['basket_order'], $tmpArray);

} else if ($_GET['mode'] == "save") {
	foreach ($_SESSION['basket_order'] as $key => $value) {
		$db->query("UPDATE baskets SET basket_order = ? WHERE basket_id = ?", array($key, $value['basket_id']));
	}
	
    // Log in database 
    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
    $hist = new history();
    $hist->add("basket_order", $_SESSION['user']['UserId'],"UP",'basketorderup', _BASKET_ORDER_EDITED, $_SESSION['config']['databasetype'], 'basket');

    $_SESSION['info'] = _BASKET_ORDER_EDITED;
	?>
	<script>
		window.location.href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?module=basket&page=basket";
	</script>
	<?php
	exit;
}

