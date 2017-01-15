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

$query = "SELECT * from thesaurus where thesaurus_name = ?";

$stmt = $db->query($query, array($_REQUEST['thesaurus_name']));

$res = $stmt->fetchObject();

$result['info'] = $res;

if($res->thesaurus_parent_id != ''){
    $query = "SELECT thesaurus_name,thesaurus_id from thesaurus where thesaurus_parent_id = ? ORDER BY thesaurus_name DESC";

    $stmt = $db->query($query, array($res->thesaurus_parent_id));

    while($res = $stmt->fetchObject()){
        $query = "SELECT count(*) as total from thesaurus where thesaurus_parent_id = ?";
        $stmt2 = $db->query($query, array($res->thesaurus_name));
        $res2 = $stmt2->fetchObject();
        $res->total = $res2->total;
        $result['info_annexe'][] = $res;
    }
}

$query = "SELECT thesaurus_name,thesaurus_id from thesaurus where thesaurus_parent_id = ? ORDER BY thesaurus_name DESC";

    $stmt = $db->query($query, array($_REQUEST['thesaurus_name']));

    while($res = $stmt->fetchObject()){
        $query = "SELECT count(*) as total from thesaurus where thesaurus_parent_id = ?";
        $stmt2 = $db->query($query, array($res->thesaurus_name));
        $res2 = $stmt2->fetchObject();
        $res->total = $res2->total;
        $result['info_children'][] = $res;
    }

echo json_encode($result);
//$_SESSION['is_multi_contact'] = '';