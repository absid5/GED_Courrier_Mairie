<?php
/*
*    Copyright 2008,2016 Maarch
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
* @brief  List of thesaurus for autocompletion
*
*
* @file
* @author Alex Orluc <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/
$db = new Database();
$stmt = $db->query(
    "SELECT distinct thesaurus_name,thesaurus_name_associate FROM thesaurus WHERE lower(thesaurus_name) like lower(?) OR lower(thesaurus_name_associate) like lower(?) order by thesaurus_name",
    array('%'.$_REQUEST['what'].'%','%'.$_REQUEST['what'].'%')
);

$listArray = array();
echo "<ul>\n";
$authViewList = 0;

while($line = $stmt->fetchObject())
{

    $line->thesaurus_name = str_replace(strtolower($_REQUEST['what']), '<b>'.strtolower($_REQUEST['what']).'</b>', strtolower($line->thesaurus_name));

    $line->thesaurus_name_associate = str_replace($_REQUEST['what'], '<b>'.$_REQUEST['what'].'</b>', strtolower($line->thesaurus_name_associate));


    if($authViewList >= 10)
    {
        $flagAuthView = true;
    }
    echo "<li><span title='terme'>".$line->thesaurus_name."</span>";

    if($line->thesaurus_name_associate != ""){
        echo " <i title='terme(s) associé(s)' style='color: #009dc5;font-size:10px;'>(".$line->thesaurus_name_associate.")</i></li>\n";
    }

    if(isset($flagAuthView))
    {
        echo "<li>...</li>\n";
        break;
    }
    $authViewList++;

}
echo "</ul>";
