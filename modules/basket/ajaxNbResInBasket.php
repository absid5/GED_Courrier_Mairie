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
*/

$db = new Database();
$basketId = trim(str_replace(
    'nb_', 
    '',
    $_REQUEST['id_basket']
));

for ($i=0;$i<count($_SESSION['user']['baskets']);$i++) {
    if ($_SESSION['user']['baskets'][$i]['id'] == $basketId) {
        if (!empty($_SESSION['user']['baskets'][$i]['table'])) {
            if (trim($_SESSION['user']['baskets'][$i]['clause']) <> '') {
                $stmt = $db->query('select * from '
                    . $_SESSION['user']['baskets'][$i]['view']
                    . ' where ' . $_SESSION['user']['baskets'][$i]['clause']);
                $nb = $stmt->rowCount();
            }
        }
    }
}

echo json_encode(['status'=>1, 'nb'=>$nb, 'idSpan'=> functions::xssafe($_REQUEST['id_basket']), 'id_basket'=> functions::xssafe($basketId)]);
exit;

$sessionTemplateContent = trim(str_replace(
    "\n", 
    "",
    $sessionTemplateContent
));

if ($sessionTemplateContent == $requestTemplateContent) {
    $_SESSION['template_content'] = '';
    echo "{status : '1, responseText : same content ! '}";
} else {
    $_SESSION['template_content'] = $_REQUEST['template_content'];
    $_SESSION['template_content'] = str_replace('[dates]', date('d-m-Y'), $_SESSION['template_content']);
    $_SESSION['template_content'] = str_replace('[time]', date('G:i:s'), $_SESSION['template_content']);
    echo "{status : '0, responseText : " . addslashes($_REQUEST['template_content']) . "'}";
}

exit;
