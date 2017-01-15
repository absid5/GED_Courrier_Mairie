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
* @brief  Script used by an Ajax object to manage saved queries(create, modify and delete)
*
* @file manage_query.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
require_once 'core/class/class_request.php';
$core_tools = new core_tools();
$core_tools->load_lang();
$db = new Database();
$req = new request();
$tmp = false;
// var_dump($_POST['action']);
// var_dump($_POST['name']);

if ($_POST['action'] == 'creation') {
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = preg_replace('/[\'"]/', '', $_POST['name']);

        $stmt = $db->query(
            'SELECT query_id FROM ' . $_SESSION['tablename']['saved_queries']
            . " WHERE user_id = ? and query_name= ?",
            array($_SESSION['user']['UserId'], $_POST['name'])
        );

        if ($stmt->rowCount() < 1) {
            $tmp = $db->query(
                'INSERT INTO ' . $_SESSION['tablename']['saved_queries']
                . ' (user_id, query_name, creation_date, created_by, '
                . " query_type, query_txt) VALUES (?, ?, CURRENT_TIMESTAMP, ?, 'my_search', ? )", 
                array($_SESSION['user']['UserId'], $_POST['name'], $_SESSION['user']['UserId'], $_SESSION['current_search_query']), true
            );
        } else {
            if($stmt->rowCount() >= 1){
                //si il existe déjà une ligne dans la base avec les mêmes infos, on va demander confirmation
                $_SESSION['seekName'] = $_POST['name'];
                echo '{status : 4}';
                exit(); 
            }
            $res = $stmt->fetchObject();
            $id = $res->query_id;
            $tmp = $db->query(
                'UPDATE ' . $_SESSION['tablename']['saved_queries']
                . " SET query_txt = ?, last_modification_date = CURRENT_TIMESTAMP WHERE user_id = ? and query_name= ?"
                , array($_SESSION['current_search_query'], $_SESSION['user']['UserId'], $_POST['name']), true
            );
        }
        if (!$tmp) {
            echo "{status : 2, 'query':'".$tmp->debugDumpParams()."'}";
            exit();
        } else {
            echo '{status : 0}';
            exit();
        }
    } else {
        echo '{status : 3}';
    }
} else if ($_POST['action'] == 'load') {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $tmp = $db->query(
            'SELECT query_txt FROM ' . $_SESSION['tablename']['saved_queries']
            . " WHERE query_id = ?", array($_POST['id']), true
        );
    }
    if (!$tmp) {
        echo "{'status' : 2, 'query':'".$tmp->debugDumpParams()."'}";
    } else {
        $res = $tmp->fetchObject();
        echo "{'status' : 0, 'query':".$res->query_txt."}";
    }
} else if($_POST['action'] == 'delete') {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $tmp = $db->query(
            'DELETE FROM ' . $_SESSION['tablename']['saved_queries']
            . " WHERE query_id = ?", array($_POST['id']), true
        );
    }
    if (!$tmp) {
        echo "{'status' : 2, 'query':'".$tmp->debugDumpParams()."'}";
    } else {
        echo "{'status' : 0}";
    }
} else if($_POST['action'] == 'creation_ok') {

            $tmp = $db->query(
                'UPDATE ' . $_SESSION['tablename']['saved_queries']
                . " SET query_txt = ?, last_modification_date = CURRENT_TIMESTAMP WHERE user_id = ? and query_name= ?"
                , array($_SESSION['current_search_query'], $_SESSION['user']['UserId'], $_SESSION['seekName']), true
            );
          $_SESSION['seekName'] = null;  
        echo "{'status' : 0}";
} else {
    echo "{status : 1}";
 }
exit();