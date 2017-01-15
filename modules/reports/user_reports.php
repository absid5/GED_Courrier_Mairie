<?php
/*
*    Copyright 2009 Maarch
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

/**
* @brief list available reports
*
* @file
* @author Yves Christian KPAKPO <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup reports
*/

require_once("modules".DIRECTORY_SEPARATOR."reports".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_admin_reports.php");
require_once("modules".DIRECTORY_SEPARATOR."reports".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header();

$core_tools->test_admin('admin_reports', 'reports');

$func = new functions();
$userReports = array();
$user_id = $_SESSION['user']['UserId'];

$admin_reports = new admin_reports();
$report = new reports();

$enabled_reports = $report->get_reports_from_xml();

$userReports = $admin_reports->load_user_reports($user_id, '');
//$func->show_array($userReports);
$authorized_reports_sort_by_parent = array();

//Sort reports by parents
$j=0;
$last_val = '';
//for($i=0; $i<count($userReports);$i++)
foreach(array_keys($userReports)as $key)
{
	//if($enabled_reports[$key]['module'] <> $userReports[$i - 1]['module'])
	if($enabled_reports[$key]['module'] <> $last_val)
	{
		$j=0;
	}
	//$authorized_reports_sort_by_parent[$userReports[$i]['module']][$j] = $userReports[$i];
	$authorized_reports_sort_by_parent[$enabled_reports[$key]['module']][$j] = $enabled_reports[$key];
	$j++;
	$last_val = $enabled_reports[$key]['module'];
}

if(count($userReports) > 0)
{
?>
	<span class="form_title"><?php echo _CLICK_LINE_BELOW_TO_SEE_REPORT;?></span><br/><br/>

		<div class="block">
	<?php
	//$func->show_array($_SESSION['authorized_reports']);
	//$func->show_array($authorized_reports_sort_by_parent);
	$_SESSION['cpt']=0;
	foreach(array_keys($authorized_reports_sort_by_parent) as $value)
	{
	?>
		<h5 onmouseover="" style="cursor: pointer;" onclick="new Effect.toggle('div_<?php functions::xecho($authorized_reports_sort_by_parent[$value][0]['module_label']);?>', 'blind', {delay:0.2});return false;"  >
			<i class="fa fa-plus fa-2x"></i>&nbsp;<b><?php functions::xecho($authorized_reports_sort_by_parent[$value][0]['module_label']);?></b>
		</h5>
		<br/>
		<div class="block_light" id="div_<?php functions::xecho($authorized_reports_sort_by_parent[$value][0]['module_label']);?>"  style="display:none">
			<div >
				<ul class="reports_list">
				<table>
					<?php
					for($i=0; $i<count($authorized_reports_sort_by_parent[$value]); $i++)
					{
						?>
						<tr>
							<!--<td nowrap align="left" title="<?php functions::xecho($authorized_reports_sort_by_parent[$value][$i]['desc']);?>">-->
							<td nowrap align="left">
								<li><a class="printlink" href="#" onclick="fill_report_result('<?php functions::xecho($authorized_reports_sort_by_parent[$value][$i]['url']);?>');return false;"><?php functions::xecho($authorized_reports_sort_by_parent[$value][$i]['label']);?> </a></li>

							</td>

						</tr>
						<?php
					}
					?>
				</table>
				</ul>
			</div>
		</div>
		<?php
		$_SESSION['cpt']++;
	}
	?>
	</div>
		<div class="block_end">&nbsp;</div>
		<br/>
		<div id="result_report"></div>
	<?php
}
else
{
?>
	<span class="form_title"><?php echo _NO_REPORTS_FOR_USER;?></span><br/><br/>
<?php
}
?>
