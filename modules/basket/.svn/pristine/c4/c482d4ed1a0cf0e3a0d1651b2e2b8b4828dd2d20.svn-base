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

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$db = new Database();
$listArray = array();
$stmt = $db->query("select basket_name from baskets where (lower(coll_id) like lower(?) "
	."or lower(basket_id) like lower(?) "
	."or lower(basket_name) like lower(?)) and enabled <> 'N'",array('%'.$_REQUEST['what'].'%',$_REQUEST['what'].'%','%'.$_REQUEST['what'].'%'));

//$db->show();
while ($line = $stmt->fetchObject()) {
	array_push($listArray, functions::show_string($line->basket_name));
}
echo "<ul>\n";
$basketViewList = 0;
$flagBasketView = false;
foreach ($listArray as $what) {
    if ($basketViewList >= 10) {
        $flagBasketView = true;
    }

    $what = str_replace("&#039;", "'", $what);
    echo "<li>".functions::xssafe($what)."</li>\n";
    if($flagBasketView) {
        echo "<li>...</li>\n";
        break;
    }
    $basketViewList++;
}
echo "</ul>";
