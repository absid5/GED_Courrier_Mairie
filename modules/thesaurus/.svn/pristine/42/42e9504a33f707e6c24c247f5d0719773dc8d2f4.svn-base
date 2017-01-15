<?php
/*
*   Copyright 2008, 2016 Maarch
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
* @brief Script used by an Ajax autocompleter object to get the contacts data (from users or contacts)
*
* @file get_thesaurus_info.php
* @author Alex Orluc <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup get_thesaurus_info
*/
$db = new Database();

$arrayPDO = array();

$query = "SELECT * from thesaurus where (thesaurus_parent_id IS NULL OR thesaurus_parent_id = '') ORDER BY thesaurus_name DESC";

$stmt = $db->query($query, array());

while($res = $stmt->fetchObject()){
    $result[] = $res;
}

echo json_encode($result);