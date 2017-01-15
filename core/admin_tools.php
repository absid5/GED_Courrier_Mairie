<?php

/*
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
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief API to manage admin 
*
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/


/**
 * Format given item with given values, according with HTML formating.
 * NOTE: given item needs to be an array with at least 2 keys: 
 * 'column' and 'value'.
 * NOTE: given item is modified consequently.  
 * @param $item
 * @param $label
 * @param $size
 * @param $label_align
 * @param $align
 * @param $valign
 * @param $show
 */
function At_formatItem(
    &$item, $label , $size , $labelAlign, $align, $valign, $show
) {
    $func = new functions();
    $item['value'] = $func->show_string($item['value']);    
    $item[$item['column']] = $item['value'];
    $item["label"] = $label;
    $item["size"] = $size;
    $item["label_align"] = $labelAlign;
    $item["align"] = $align;
    $item["valign"] = $valign;
    $item["show"] = $show;
    $item["order"] = $item['column'];    
}

/**
 * Put given object in session, according with given type
 * NOTE: given object needs to be at least hashable
 * @param string $type
 * @param hashable $hashable
 */
function At_putInSession($type, $hashable)
{
    $func = new functions();
    foreach ($hashable as $key => $value) {
        $_SESSION['m_admin'][$type][$key] = $func->show_string($value);
    }
}

/**
 * Show the admin list of an Ajax request 
 * @param object $db database request object
 * @param string $whatRequest request string
 */
function At_showAjaxList($stmt, $whatRequest)
{
    $listArray = array();
    while ($line = $stmt->fetchObject()) {
        array_push($listArray, $line->tag);
    }
    echo "<ul>\n";
    $authViewList = 0;
    $flagAuthView = false;
    foreach ($listArray as $what) {
        if (isset($authViewList) && $authViewList >= 10) {
            $flagAuthView = true;
        }
        if (stripos($what, $whatRequest) === 0) {
            echo "<li>" . functions::xssafe($what) . "</li>\n";
            if ($flagAuthView) {
                echo "<li>...</li>\n";
                break;
            }
            $authViewList++;
        }
    }
    echo "</ul>";
}

