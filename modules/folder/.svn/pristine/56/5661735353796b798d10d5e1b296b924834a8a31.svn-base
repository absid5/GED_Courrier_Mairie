<?php

$core_tools = new core_tools();
$core_tools->load_lang();
$core_tools->test_service('reports', 'reports');
$content = '';
$content .='<div id="params">';
	$content .='<form id="folderviewform" name="folderviewform" method="get" action="">';
		$content .='<table width="95%"  border="0" align="center">';
			$content .='<tr>';
				$content .='<td rowspan="4" width="25"><!--IMAGE--></td>';
				$content .='<td align="left"><input type="radio" id="foldertype" name="type_choice" checked="checked" class="checked" value="byFoldertype" /><label for="foldertype">'._TITLE_STATS_CHOICE_FOLDER_TYPE.'</label></td>';
				$content .='<td rowspan="4" align="right" valign="middle"><input type="button" onclick="valid_viewfolder( \''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=folder&page=get_folder_view_stats_val\');" class="button" name="Submit1" value="'._VALIDATE.'" /></td>';
			$content .='</tr>';
			$content .='<tr>';
				$content .='<td align="left"><input type="radio" id="usergroup" name="type_choice" value="byGroup" /><label for="usergroup">'._TITLE_STATS_CHOICE_GROUP.'</label></td>';
			$content .='</tr>';
			$content .='<tr>';
				$content .='<td align="left"><input type="radio" id="user" name="type_choice" value="byUser" /><label for="user">'._TITLE_STATS_CHOICE_USER.'</label> ';
					$content .='<input type="text" name="user_id" id="user_id" value="" />&nbsp;<em><label for="user_id"><a href="javascript://" onclick="window.open(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=folder&page=select_user_report\',\'select_user\',\'width=800,height=700,resizable=yes\');" >'._CHOOSE_USER2.'</a></label></em></td>';
			$content .='</tr>';
			$content .='<tr>';
				$content .='<td align="left"><input type="radio" id="period" name="type_choice" value="byPeriod" /><label for="period">'._TITLE_STATS_CHOICE_PERIOD.'.&nbsp;</label>'._SINCE.'&nbsp;:&nbsp;<input name="datestart" type="text"  id="datestart" onclick="showCalender(this);" />&nbsp;'._FOR.'&nbsp;:&nbsp;<input name="datefin" type="text"  id="datefin" onclick="showCalender(this);" /></td>';
			$content .='</tr>';
		$content .='</table>';
	$content .='</form>';
$content .='</div>';
$content .='<div id="result_folderviewstat"></div>';
$js ='valid_viewfolder(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=folder&page=get_folder_view_stats_val\');';

echo "{content : '".addslashes($content)."', exec_js : '".addslashes($js)."'}";
exit();
