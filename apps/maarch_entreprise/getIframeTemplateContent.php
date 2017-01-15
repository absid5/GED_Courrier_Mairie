<?php

/*
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
*/

$sessionTemplateContent = utf8_encode(html_entity_decode(strip_tags($_SESSION['template_content'])));
$requestTemplateContent = utf8_encode(html_entity_decode(strip_tags($_REQUEST['template_content'])));

$sessionTemplateContent = trim(str_replace(
    "\n", 
    "",
    $sessionTemplateContent
));
$sessionTemplateContent = trim(str_replace(
    " ", 
    "",
    $sessionTemplateContent
));
$sessionTemplateContent = trim(str_replace(
    "\r", 
    "",
    $sessionTemplateContent
));
$sessionTemplateContent = trim(str_replace(
    "\t", 
    "",
    $sessionTemplateContent
));
$requestTemplateContent = trim(str_replace(
    "\n", 
    "", 
    $requestTemplateContent
));
$requestTemplateContent = trim(str_replace(
    " ", 
    "", 
    $requestTemplateContent
));
$requestTemplateContent = trim(str_replace(
    "\r", 
    "", 
    $requestTemplateContent
));
$requestTemplateContent = trim(str_replace(
    "\t", 
    "", 
    $requestTemplateContent
));

if ($sessionTemplateContent == $requestTemplateContent) {
    $_SESSION['template_content'] = '';
    echo "{status : '1, responseText : same content ! '}";
} else {
    $_SESSION['template_content'] = $_REQUEST['template_content'];
    $_SESSION['template_content'] = str_replace('[dates]', date('d-m-Y'), $_SESSION['template_content']);
    $_SESSION['template_content'] = str_replace('[time]', date('G:i:s'), $_SESSION['template_content']);
    echo "{status : '0, responseText : " . addslashes(functions::xssafe($_REQUEST['template_content'])) . "'}";
}

exit;
