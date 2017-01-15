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

$core = new core_tools();
$core->load_lang();
$core->test_service('view_attachments', 'attachments');
$func = new functions();

if (empty($_SESSION['collection_id_choice'])) {
	$_SESSION['collection_id_choice'] = $_SESSION['user']['collections'][0];
}
require_once "core/class/class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_list_show.php";
require_once 'modules/attachments/attachments_tables.php';
$func = new functions();

$select[RES_ATTACHMENTS_TABLE] = array();
array_push(
    $select[RES_ATTACHMENTS_TABLE], "res_id", "creation_date", "title",
    "format"
);

$where = " res_id_master = ? and coll_id = ? and status <> 'DEL' ";
$request = new request;
$arrayPDO = array($_SESSION['doc_id'],$_SESSION['collection_id_choice']);
$attachArr = $request->PDOselect(
    $select, $where, $arrayPDO, "", $_SESSION['config']['databasetype'], "500");
//$request->show();
$indAtt1d = '';
for ($indAtt1 = 0; $indAtt1 < count($attachArr); $indAtt1 ++) {
	$valueModify = false;
	for ($indAtt2 = 0; $indAtt2 < count($attachArr[$indAtt1]); $indAtt2 ++) {
		foreach (array_keys($attachArr[$indAtt1][$indAtt2]) as $value) {
			if ($attachArr[$indAtt1][$indAtt2][$value] == "res_id") {
				$attachArr[$indAtt1][$indAtt2]["res_id"] = $attachArr[$indAtt1][$indAtt2]['value'];
				$attachArr[$indAtt1][$indAtt2]["label"] = _ID;
				$attachArr[$indAtt1][$indAtt2]["size"] = "18";
				$attachArr[$indAtt1][$indAtt2]["label_align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["valign"] = "bottom";
				$attachArr[$indAtt1][$indAtt2]["show"] = false;
				$indAtt1d = $attachArr[$indAtt1][$indAtt2]['value'];
			}
			if ($attachArr[$indAtt1][$indAtt2][$value] == "title") {
				$attachArr[$indAtt1][$indAtt2]["title"] = $attachArr[$indAtt1][$indAtt2]['value'];
				$attachArr[$indAtt1][$indAtt2]["label"] = _TITLE;
				$attachArr[$indAtt1][$indAtt2]["size"] = "30";
				$attachArr[$indAtt1][$indAtt2]["label_align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["valign"] = "bottom";
				$attachArr[$indAtt1][$indAtt2]["show"] = true;
			}
			if ($attachArr[$indAtt1][$indAtt2][$value] == "creation_date") {
				$attachArr[$indAtt1][$indAtt2]['value'] = $request->show_string(
				    $attachArr[$indAtt1][$indAtt2]['value']
				);
				$attachArr[$indAtt1][$indAtt2]["creation_date"] = $attachArr[$indAtt1][$indAtt2]['value'];
				$attachArr[$indAtt1][$indAtt2]["label"] = _DATE;
				$attachArr[$indAtt1][$indAtt2]["size"] = "30";
				$attachArr[$indAtt1][$indAtt2]["label_align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["valign"] = "bottom";
				$attachArr[$indAtt1][$indAtt2]["show"] = true;
			}
			if ($attachArr[$indAtt1][$indAtt2][$value] == "format") {
				$attachArr[$indAtt1][$indAtt2]['value'] = $request->show_string(
				    $attachArr[$indAtt1][$indAtt2]['value']
				);
				$attachArr[$indAtt1][$indAtt2]["format"] = $attachArr[$indAtt1][$indAtt2]['value'];
				$attachArr[$indAtt1][$indAtt2]["label"] = _FORMAT;
				$attachArr[$indAtt1][$indAtt2]["size"] = "30";
				$attachArr[$indAtt1][$indAtt2]["label_align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["align"] = "left";
				$attachArr[$indAtt1][$indAtt2]["valign"] = "bottom";
				$attachArr[$indAtt1][$indAtt2]["show"] = false;

				if ($attachArr[$indAtt1][$indAtt2]['value'] == "maarch") {
					$valueModify = true;
				}
			}
		}
	}
}

?>
<h2 onclick="change(100)" id="h2100" class="categorie">
	<i class="fa fa-plus fa-2x"></i>&nbsp;<b><?php
echo _ATTACHMENTS;
?> :</b>
	<span class="lb1-details">&nbsp;</span>
</h2>
<br>
<div class="desc" id="desc100" style="display:none">
	<div class="ref-unit">
   <?php
$listAttach = new list_show();
$listAttach->list_simple(
    $attachArr, count($attachArr), '', 'res_id', 'res_id', true,
    $_SESSION['config']['businessappurl'] . "index.php?display=true"
    . "&module=attachments&page=view_attachment", 'listing2', '', 450, 500, ''
);
	?>
   </div>
</div>
