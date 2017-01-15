<?php

/*
*
*   Copyright 2008,2015 Maarch
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

/*var_dump($_REQUEST);
var_dump($_SESSION['mailAccounts'][$_REQUEST['mailBoxIndex']]);*/
$uri = $_SESSION['mailAccounts'][$_REQUEST['mailBoxIndex']]['mailBoxUri'];
$login = $_SESSION['mailAccounts'][$_REQUEST['mailBoxIndex']]['mailBoxUsername'];
$password = $_SESSION['mailAccounts'][$_REQUEST['mailBoxIndex']]['mailBoxPassword'];

try {
    $imap = @imap_open(
        $uri, 
        $login, 
        $password
    );    
} catch (Exception $e) {
    echo 'Exception : ',  $e->getMessage(), "";
}

$errorDetails = '';
$status = 'ok';
if(!$imap) {
    $status = 'ko';
    $errors = imap_errors();
    $alerts = imap_alerts();
    $errorDetails = $errors;
} else {
    $status = 'ok';
}

echo "{status : '" . $status . "', errorDetails : '"
    . json_encode($errorDetails) . "'}";
exit;


