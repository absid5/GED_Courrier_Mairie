<?php
/*
*    Copyright 2008,2012 Maarch
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
* Module : Tags
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Loic Vinet
* @date $date$
* @version $Revision$
*/
require_once("core/class/class_db.php");
require_once("modules/tags/tags_tables_definition.php");

$table = _TAG_TABLE_NAME;

$where_what = array();

if($_SESSION['config']['databasetype'] == "POSTGRESQL")
{
	// Pourquoi il y a deux tag_label sur la même chose?
	//$where .= " (tag_label ilike ? or tag_label ilike ? ) ";
	$where .= " (tag_label ilike ? ) ";
	$limit = " limit 10";
	$where_what[] = '%'.$_REQUEST['Input'].'%';
	//$where_what[] = '%'.$_REQUEST['Input'].'%';
}
else
{
	// Pourquoi il y a deux tag_label sur la même chose?
	//$where .= " (tag_label like ? or tag_label like ? ) ";
	$where .= " (tag_label like ? ) ";
	$where_what[] = '%'.$_REQUEST['Input'].'%';
	//$where_what[] = '%'.$_REQUEST['Input'].'%';
	$limit = "";
}
$other = 'order by tag_label';

$db = new Database(); 
$stmt = $db->query(
    	"SELECT DISTINCT tag_label as label from " ._TAG_TABLE_NAME
        . " where ".$where." ".
        $other." ".$limit
	,$where_what);

$list = "<ul>\n";
$imax = 0;
while($result=$stmt->fetchObject())
{
	$imax++;
	if ($imax > 9){
		$list .= "<li>...</li>\n";
		break;
	}
	$list .= "<li>".functions::xssafe($result->label)."</li>\n";
}
$list .= "</ul>";
echo $list;
