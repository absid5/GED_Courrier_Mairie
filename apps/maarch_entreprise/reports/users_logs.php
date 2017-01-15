<?php

/*
*    Copyright 2008-2015 Maarch
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

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->test_service('reports', 'reports');
$content = '';
$content .='<div id="params">';
$content .='<form id="form" name="form" method="get" action="">';
	$content .='<table width="95%"  border="0" align="center">';
		$content .= '<tr>';
			$content .= '<td width="25">&nbsp;</td>';
			$content .= '<td align="left"><input type="text" class="readonly" disabled="disabled" name="user_id" id="user_id" value="" />&nbsp;<em><label for="user_id"><a href="javascript://" onclick="window.open(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=reports&page=select_user_report\',\'select_user_report\',\'scrollbars=yes,width=800,height=700,resizable=yes\');" > <i class="fa fa-search" aria-hidden="true" title="'._CHOOSE_USER2.'"></i></a></label></em></td>';
			$content .= '<td align="right" valign="middle"><input type="button" onclick="valid_userlogs(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=reports&page=get_user_logs_stats_val\');" class="button" name="Submit1" value="'._VALIDATE.'" /></td>';
		$content .= '</tr>';
	$content .= '</table>';
$content .= '</form>';
$content .='</div>';
$content .='<div id="result_userlogsstat"></div>';
$js ='';

echo "{content : '".addslashes($content)."', exec_js : '".addslashes($js)."'}";
exit();
