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

// FOR ADD, UP TEMPLATES

/*$_SESSION['m_admin']['templates']['current_style'] 
        = $_SESSION['config']['tmppath'] . $tmpFileName;
$_SESSION['m_admin']['templates']['applet'] = true;*/

$_SESSION['upfile']['tmp_name'] = $_SESSION['config']['tmppath'] . $tmpFileName;

$_SESSION['upfile']['size'] = filesize($_SESSION['config']['tmppath'] . $tmpFileName);

$_SESSION['upfile']['error'] = "";

$_SESSION['upfile']['fileNameOnTmp'] = $tmpFileName;

$_SESSION['upfile']['format'] = $fileExtension;

$_SESSION['m_admin']['templates']['applet'] = true;

$_SESSION['upfile']['upAttachment'] = true;

$_SESSION['upfile']['outgoingMail'] = true;

if ($_SESSION['modules_loaded']['attachments']['convertPdf'] == true){
	$_SESSION['upfile']['fileNamePdfOnTmp'] = $tmpFilePdfName;
}