<?php

/*
*   Copyright 2008-2015 Maarch
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

require_once 'modules/templates/class/templates_controler.php';
$templateController = new templates_controler();

if ((! isset($_REQUEST['templateId']) || empty($_REQUEST['templateId']))) {
    $error = _TEMPLATE_ID . ' ' . _EMPTY;
    echo "{status : 1, error_txt : '" . addslashes($error) . "'}";
    exit();
}
$template = $templateController->get($_REQUEST['templateId']);

$template->template_content = str_replace("\r\n", "\n", $template->template_content);
$template->template_content = str_replace("\r", "\n", $template->template_content);
$template->template_content = str_replace("\n", "\\n ", $template->template_content);

echo "{status : 0, content : '" . addslashes($template->template_content) . "'}";
exit();
