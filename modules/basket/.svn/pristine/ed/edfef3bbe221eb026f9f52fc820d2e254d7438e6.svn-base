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
* @brief   Basket administration : form to modify a basket
*
* Calls the formbasket() function in up mode
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/
$admin = new core_tools();
$admin->test_admin('admin_baskets', 'basket');

require_once 'modules/basket/class/class_admin_basket.php';
 /****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
    $init = true;
}
$level = '';
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=basket_up&module=basket';
$page_label = _MODIFICATION;
$page_id = 'basket_up';
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
$func = new functions();

if(isset($_GET['id']))
{
    $id = addslashes($func->wash($_GET['id'], "nick", _THE_BASKET));
}
else
{
    $id = "";
}
$_SESSION['origin'] = 'basket_up';
$bask = new admin_basket();
$bask->formbasket("up",$id);
?>
