<?php

/*
*   Copyright 2008-2016 Maarch
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

/**
* modules tools Class for Advanced Physical Archive
*
* Contains all the functions to load modules tables for Reports 
*
* @package  maarch
* @version 3.0
* @since 10/2005
* @license GPL v3
* @author  Yves Christian KPAKPO  <dev@maarch.org>
*
*/

abstract class reports_Abstract extends Database
{

	/**
	* Build Maarch module tables into sessions vars with a xml configuration 
	* file
	*/
	public function build_modules_tables()
	{
		if (file_exists(
			$_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
			. $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
			. DIRECTORY_SEPARATOR . "reports" . DIRECTORY_SEPARATOR . "xml"
			. DIRECTORY_SEPARATOR . "config.xml"
		)
		) {
			$path = $_SESSION['config']['corepath'] . 'custom' 
				. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
				. DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
				. "reports" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
				. "config.xml";
		} else {
			$path = "modules" . DIRECTORY_SEPARATOR . "reports"
				. DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR 
				. "config.xml";
		}
		$xmlconfig = simplexml_load_file($path);

		foreach ($xmlconfig->TABLENAME as $tableName) {
			$_SESSION['tablename']['usergroups_reports'] = (string) $tableName->usergroups_reports;
		}

		$history = $xmlconfig->HISTORY;
		$_SESSION['history']['usergroupsreportsadd'] = (string) $history->usergroupsreportsadd;
		$_SESSION['history']['viewreport'] = (string) $history->viewreport;
		$_SESSION['history']['printreport'] = (string) $history->printreport;
	}


	function get_reports_from_xml($reportId = '', $onlyEnabled = true)
	{
		$reports = array();
		if (file_exists(
			$_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
			. $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
			. DIRECTORY_SEPARATOR . "reports" . DIRECTORY_SEPARATOR . "xml"
			. DIRECTORY_SEPARATOR . "reports.xml"
		)
		) {
			$path = $_SESSION['config']['corepath'] . 'custom' 
				. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
				. DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR 
				. "reports" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
				. "reports.xml";
		} else {
			$path = "modules" . DIRECTORY_SEPARATOR . "reports"
				. DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR 
				. "reports.xml";
		}
		$xmlfile = simplexml_load_file($path);
		$i = 0;
		foreach ($xmlfile->REPORT as $report) {
			$id = (string) $report->ID;
			$enabled = (string) $report->ENABLED;
			$menu = (string) $report->IN_MENU_REPORTS;
			$url = (string) $report->URL;
			$origin = (string) $report->ORIGIN;

			if ($origin == "apps") {
				$module = "application";
				include_once 'apps' . DIRECTORY_SEPARATOR 
					. $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
					. 'lang' . DIRECTORY_SEPARATOR
					. $_SESSION['config']['lang'] . '.php';
				$moduleLabel = _APPS_COMMENT;
				if (defined($moduleLabel) && constant($moduleLabel) <> NULL) {
					$moduleLabel = constant($moduleLabel);
				}
				$label = (string) $report->LABEL;
				if (!empty($label) && defined($label) 
					&& constant($label) <> NULL
				) {
					$label = constant($label);
				}
				$desc = (string) $report->DESCRIPTION;
				if (!empty($desc) && defined($desc) 
					&& constant($desc) <> NULL
				) {
					$desc = constant($desc);
				}
				$url = $_SESSION['config']['businessappurl'] 
					. 'index.php?display=true&dir=reports&page=' . $url;
			} else {
				$module = (string) $report->MODULE;
				include_once 'modules' . DIRECTORY_SEPARATOR . $module
					. DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
					. $_SESSION['config']['lang'] . '.php';
				$moduleLabel = (string) $report->MODULE_LABEL;
				
				if (!empty($moduleLabel) && defined($moduleLabel) 
					&& constant($moduleLabel) <> NULL
				) {
					$moduleLabel = constant($moduleLabel);
				}
				$label = (string) $report->LABEL;
				if (!empty($label) && defined($label) 
					&& constant($label) <> NULL
				) {
					$label = constant($label);
				}
				$desc = (string) $report->DESCRIPTION;
				if (!empty($desc) && defined($desc) 
					&& constant($desc) <> NULL
				) {
					$label = constant($desc);
				}
				$url = $_SESSION['config']['businessappurl']
					. 'index.php?display=true&module=' . $module . '&page='
					. $url;
			}
			if (($enabled == 'true' && $onlyEnabled) || ! $onlyEnabled) {
				if (! empty($reportId) && $id == $reportId) {
					$reports[$id] = array(
						'id' => $id, 
						'label' => $label, 
						'desc' => $desc, 
						'enabled' => $enabled, 
						'menu' => $menu, 
						'url' => $url, 
						'module' => $module, 
						'module_label' => $moduleLabel,
					);
					break;
				} else if (empty($reportId)) {
					$reports[$id] = array(
						'id' => $id, 
						'label' => $label, 
						'desc' => $desc, 
						'enabled' => $enabled, 
						'menu' => $menu, 
						'url' => $url, 
						'module' => $module, 
						'module_label' => $moduleLabel,
					);
					$i ++;
				}
			}
		}
		return $reports;
	}

	public function get_arguments_for_report($strToAnalyse, $argToFind)
	{
		$str = $strToAnalyse;
		$tmpArr = preg_split('/\$\$/', $str);
		for ($i = 0; $i < count($tmpArr); $i ++) {
			$tmpArr2 = preg_split('/#/', $tmpArr[$i]);
			if ($tmpArr2[0] == $argToFind) {
				return $tmpArr2[1];
			}
		}
		return '';
	}
}

