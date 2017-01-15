<?php
/*
*
*   Copyright 2016 Maarch
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
* @brief    Script to return ajax result
*
* @author   Alex ORLUC <dev@maarch.org>
* @date     $date$
* @version  $Revision$
*/

$filename = $_SESSION['config']['tmppath'].$_POST['lck_name'].".lck";

if (file_exists($filename)) {
    echo "{status : 1, status_txt : 'LCK FOUND !'}";
} else {
    echo "{status : 0, status_txt : 'LCK NOT FOUND !'}";
}