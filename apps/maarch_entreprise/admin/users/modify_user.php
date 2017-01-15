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
* @brief Form to modify user data
*
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

$core = new core_tools();
//here we loading the lang vars
$core->load_lang();
 /****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
    $init = true;
}
$level = '';
if (isset($_REQUEST['level'])
    && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3
        || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl']
          . 'index.php?page=modify_user&admin=users';
$pageLabel = _MY_INFO;
$pageId = 'modify_users';
$core->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'class_users.php';
$users = new class_users();

if ($_GET['reinit']) {
	$userInfos = functions::infouser($_SESSION['user']['UserId']);
	$_SESSION['user']['UserId'] = $userInfos['UserId'];
	$_SESSION['user']['FirstName'] = $userInfos['FirstName'];
	$_SESSION['user']['LastName'] = $userInfos['LastName'];
	$_SESSION['user']['Phone'] = $userInfos['Phone'];
	$_SESSION['user']['Mail'] = $userInfos['Mail'];
	$_SESSION['user']['department'] = $userInfos['department'];
	$_SESSION['user']['thumbprint'] = $userInfos['thumbprint'];
	$_SESSION['user']['pathToSignature'] = $userInfos['pathToSignature'];
}

$users->change_info_user();
