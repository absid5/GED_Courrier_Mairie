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

include_once '../core/init.php';

//write semaphore installed.lck

$inF = fopen('installed.lck','w');
fclose($inF);
$nomCustom = 'cs_'.$_SESSION['config']['databasename'];
unset($_SESSION);
$_SESSION = array();
session_unset();
session_destroy();
if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
header('Location: ../'.$nomCustom);
}elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
header('Location: ../');
}
exit;
