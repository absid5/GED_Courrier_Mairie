<?php
/*
*   Copyright 2008-2012 Maarch
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
* @brief class of install tools
*
* @file
* @author Arnaud Veber
* @date $date$
* @version $Revision$
* @ingroup install
*/

$_REQUEST['docserverRoot'] = str_replace("/", DIRECTORY_SEPARATOR, $_REQUEST['docserverRoot']);

$checkDocserverRoot = $Class_Install->checkDocserverRoot(
    $_REQUEST['docserverRoot']
);

if ($checkDocserverRoot !== true) {
    $return['status'] = 0;
    $return['text'] = $checkDocserverRoot;

    $jsonReturn = json_encode($return);

    echo $jsonReturn;
    exit;
}

if (!$Class_Install->createDocservers($_REQUEST['docserverRoot'])) {
    $return['status'] = 0;
    $return['text'] = _CAN_NOT_CREATE_SUB_DOCSERVERS;

    $jsonReturn = json_encode($return);

    echo $jsonReturn;
    exit;
}

$updateDocserversDB = $Class_Install->updateDocserversDB(
    $_REQUEST['docserverRoot']
);
$Class_Install->updateDocserverForXml($_REQUEST['docserverRoot']);

$return['status'] = 1;
$return['text'] = '';

$jsonReturn = json_encode($return);

echo $jsonReturn;
exit;
