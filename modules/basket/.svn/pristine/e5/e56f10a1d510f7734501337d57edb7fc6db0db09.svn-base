<?php
/*
*
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
* @brief   Basket administration : delete a basket
*
* Calls the adminbasket() function in del mode
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

require_once("modules".DIRECTORY_SEPARATOR."basket".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_admin_basket.php");
$func = new functions();
$core_tools = new core_tools();

$core_tools->load_lang();
$core_tools->test_admin('admin_baskets', 'basket');
if(isset($_GET['id']))
{
	$s_id = addslashes($func->wash($_GET['id'], "nick", _THE_BASKET));
}
else
{
	$s_id = "";
}
$basket = new admin_basket();
$basket->adminbasket($s_id,"del");
?>
