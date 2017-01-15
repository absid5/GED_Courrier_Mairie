<?php
/*
*
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
* @brief    Displays documents list in details folder tree
*
* @file     list_doc.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  folder
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
            
$core_tools = new core_tools();
$request    = new request();
$list       = new lists();

$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);

?><body><?php

$core_tools->load_js();

//Init
$list_id = "";
if(isset($_REQUEST['listid']) && $_REQUEST['listid'] <> "") {

	$list_id = substr($_REQUEST['listid'], 0, strlen($_REQUEST['listid'])-1);
} elseif(isset($_SESSION['where_list_doc']) && $_SESSION['where_list_doc'] <> "") {

	$list_id = $_SESSION['where_list_doc'];
}

//Load list
if (!empty($list_id)) {

    $target = $_SESSION['config']['businessappurl'].'index.php?module=folder&page=documents_list_in_details&listid='.$list_id;
    $listContent = $list->loadList($target);
    echo $listContent;
}
 
//Reset 
$_SESSION['where_list_doc'] = "";
?>
</body>
</html>