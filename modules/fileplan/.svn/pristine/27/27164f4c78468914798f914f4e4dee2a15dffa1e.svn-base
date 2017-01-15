<?php
/*
*
*   Copyright 2013 Maarch
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
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief    List of positions for autocompletion
*
* @file     positions_list_autocompletion.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
$db       = new Database();
$fileplan = new fileplan();


if (strlen(trim($_REQUEST['what'])) > 0) {
    $label = $_REQUEST['what'];

    $stmt = $db->query(
               "select  fileplan_id, fileplan_label from "
               . FILEPLAN_TABLE." where enabled = ?"
               ." and lower(fileplan_label) like lower(?) order by fileplan_label"
              ,array('Y','%'.$label.'%'));
                    
}


$authViewList = 0;
$content = "";
$content .= "<ul>\n";
while($line = $stmt->fetchObject())
{
    if($authViewList < 10)
	{
        $content .= "<li>".functions::xecho($line->fileplan_label)."</li>\n";
        
	} else  {
        $content .= "<li>...</li>\n";
        break;
    }
    $authViewList++;
}
$content .=  "</ul>";

echo $content;
