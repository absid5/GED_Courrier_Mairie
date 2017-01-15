<?php

/*
*
*    Copyright 2008,2015 Maarch
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
*
*   @author <dev@maarch.org>
*/

if (empty($_REQUEST['uniqueId']))
    $i = 1;
else
    $i = $_REQUEST['uniqueId'];

if (!isset($_SESSION['upfileTransmission']))
    $_SESSION['upfileTransmission'] = [];

$_SESSION['upfileTransmission'][$i]['tmp_name']             = $_SESSION['config']['tmppath'] . $tmpFileName;
$_SESSION['upfileTransmission'][$i]['size']                 = filesize($_SESSION['config']['tmppath'] . $tmpFileName);
$_SESSION['upfileTransmission'][$i]['error']                = "";
$_SESSION['upfileTransmission'][$i]['fileNameOnTmp']        = $tmpFileName;
$_SESSION['upfileTransmission'][$i]['format']               = $fileExtension;
$_SESSION['upfileTransmission'][$i]['upAttachment']         = true;
$_SESSION['m_admin']['templates']['applet']                 = true;


if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == true) {
    $_SESSION['upfileTransmission'][$i]['fileNamePdfOnTmp'] = $tmpFilePdfName;
}
