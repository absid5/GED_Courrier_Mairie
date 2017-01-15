<?php
    /*
    *   Copyright 2008-2015 Maarch and Document Image Solutions
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

require_once 'modules' . DIRECTORY_SEPARATOR . 'visa' . DIRECTORY_SEPARATOR
            . 'class' . DIRECTORY_SEPARATOR . 'class_modules_tools.php';

$core = new core_tools();
$core->test_user();

$title = $_REQUEST['title'];

$visa = new visa();
$isFree = $visa->isWorkflowTitleFree($title);
$response = ['isWorkflowTitleFree' => $isFree];

echo json_encode($response);
exit();