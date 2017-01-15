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
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* Class for schedule notifications
*
*
* @package  maarch
* @version 1.5
* @since 01/2015
* @license GPL v3
* @author  <dev@maarch.org>
* 
*/

abstract class ScheduleNotifications_Abstract{

	function getCrontab(){
		$crontab = shell_exec('crontab -l');
		$lines = explode("\n", $crontab);
		$data = array();

		foreach ($lines as $id => $l) {
			$l = trim($l);
			if (strpos($l, '#') !== false)
				$l = substr($l, 0, strpos($l, '#'));
			if (empty($l))
				continue;
			$l = preg_replace('![ \t]+!', ' ', $l);
			if ($l[0] == '@')
				list($time, $cmd) = explode(' ',$l, 2);
			else
				list($m, $h, $dom, $mon, $dow, $cmd) = explode(' ',$l, 6);

			$data[$id] = array(
				'm' => $m,
				'h' => $h,
				'dom' => $dom,
				'mon' => $mon,
				'dow' => $dow,
				'cmd' => $cmd,
			);

	        if ($_SESSION['custom_override_id'] <> '') {
	        	$pathToFolow = $_SESSION['config']['corepath'] . 'custom/'.$_SESSION['custom_override_id'] . '/';
	        } else {
	        	$pathToFolow = $_SESSION['config']['corepath'];
	        }

			if ( strpos($data[$id]['cmd'], $pathToFolow.'modules/notifications/batch/scripts/') !== 0 ) {
				$data[$id]['state'] = 'hidden';
			}
		}

		return $data;
	}

	function saveCrontab($data, $delete=false){
		foreach($data AS $id => $d) {
			if ($d['state'] == 'deleted') {
				unset($file[$id]);
			} else
				$file[$id] = "{$d['m']}\t{$d['h']}\t{$d['dom']}\t{$d['mon']}\t{$d['dow']}\t{$d['cmd']}";
		}

		$output = '';

		if (isset($file)) {
			foreach ($file as $l)
				$output .= "$l\n";
		}

		$output = preg_replace("!\n+$!", "\n", $output);
		file_put_contents('/tmp/crontab.plain', print_r($file, true));
		file_put_contents('/tmp/crontab.txt', $output);

		exec('crontab /tmp/crontab.txt');

		$core_tools = new core_tools();
		$core_tools->load_lang();

		if (!$delete) {
			$_SESSION['info'] = _CRONTAB_SAVED;
		}

		return 0;
	}

	function getAuthorizedNotifications (){
		require_once ("core/class/class_request.php");
		$db = new Database();
		$stmt = $db->query("SELECT notification_sid, description FROM notifications WHERE is_enabled = 'Y'");
		$notificationsArray = array();

		while($result = $stmt->fetchObject()){
            $filename = "notification";
            if (isset($_SESSION['custom_override_id']) && $_SESSION['custom_override_id']<>"") {
                $filename.="_".str_replace(" ", "", $_SESSION['custom_override_id']);
            }
            $filename.="_".$result->notification_sid.".sh";

	        if ($_SESSION['custom_override_id'] <> '') {
	        	$pathToFolow = $_SESSION['config']['corepath'] . 'custom/'.$_SESSION['custom_override_id'] . '/';
	        } else {
	        	$pathToFolow = $_SESSION['config']['corepath'];
	        }

            $path = $pathToFolow.'modules/notifications/batch/scripts/'.$filename;

			if (file_exists($path)) {
				$notificationsArray[$path] = $result->description;
			}
		}
		
		return $notificationsArray;
	}

	function createScriptNotification($notification_sid, $notification_id){
        //Creer le script sh pour les notifications
        $filename = "notification";
        if (isset($_SESSION['custom_override_id']) && $_SESSION['custom_override_id']<>"") {
            $filename.="_".str_replace(" ", "", $_SESSION['custom_override_id']);
        }
        $filename.="_".$notification_sid.".sh";

        if (file_exists($_SESSION['config']['corepath']. 'custom/'.$_SESSION['custom_override_id'] .'/modules/notifications/batch/config/config.xml')) {
            $ConfigNotif = $_SESSION['config']['corepath']. 'custom/'. $_SESSION['custom_override_id'] .'/modules/notifications/batch/config/config.xml';
        } else if (file_exists($_SESSION['config']['corepath']. 'custom/'. $_SESSION['custom_override_id'] .'/modules/notifications/batch/config/config_'.$_SESSION['custom_override_id'].'.xml')) {
            $ConfigNotif = $_SESSION['config']['corepath']. 'custom/'. $_SESSION['custom_override_id'] .'/modules/notifications/batch/config/config_'.$_SESSION['custom_override_id'].'.xml';
        } else if (file_exists($_SESSION['config']['corepath']. 'modules/notifications/batch/config/config_'.$_SESSION['custom_override_id'].'.xml')) {
            $ConfigNotif = $_SESSION['config']['corepath']. 'modules/notifications/batch/config/config_'.$_SESSION['custom_override_id'].'.xml';
        } else {
            $ConfigNotif = $_SESSION['config']['corepath']. 'modules/notifications/batch/config/config.xml';
        }
        
        if ($_SESSION['custom_override_id'] <> '') {
        	$pathToFolow = $_SESSION['config']['corepath'] . 'custom/'.$_SESSION['custom_override_id'] . '/';
        	//shell_exec("mkdir " . escapeshellarg($pathToFolow.'modules/notifications/batch/scripts/'));
        	if(!file_exists($pathToFolow.'modules/notifications/batch/scripts/')){
	        	mkdir($pathToFolow.'modules/notifications/batch/scripts/',0777,true);
        	}
	        $file_open = fopen($pathToFolow.'modules/notifications/batch/scripts/'.$filename, 'w+');

        } else {
        	$pathToFolow = $_SESSION['config']['corepath'];
	        $file_open = fopen($pathToFolow.'modules/notifications/batch/scripts/'.$filename, 'w+');
        }

        fwrite($file_open, '#!/bin/sh');
        fwrite($file_open, "\n");
        fwrite($file_open, 'path=\''.$_SESSION['config']['corepath'].'modules/notifications/batch/\'');
        fwrite($file_open, "\n");
        fwrite($file_open, 'cd $path');
        fwrite($file_open, "\n");
        fwrite($file_open, 'php \'process_event_stack.php\' -c '.$ConfigNotif.' -n '.$notification_id);
        fwrite($file_open, "\n");
        fwrite($file_open, 'cd $path');
        fwrite($file_open, "\n");
        fwrite($file_open, 'php \'process_email_stack.php\' -c '.$ConfigNotif);
        fwrite($file_open, "\n");
        fclose($file_open);
        shell_exec("chmod +x " 
        	. escapeshellarg($pathToFolow . "modules/notifications/batch/scripts/" . $filename)
        );
	}

	function checkCrontab($crontabToSave){

		$crontabBeforeSave = $this->getCrontab();
		$error = 0;
		foreach ($crontabToSave as $id => $e) {
			if ($e['state'] == "deleted"){
				// nothing to do
			} else if($e['state'] == "new" || $e['state'] == "normal"){
				
		        if ($_SESSION['custom_override_id'] <> '') {
		        	$pathToFolow = $_SESSION['config']['corepath'] . 'custom/'.$_SESSION['custom_override_id'] . '/';
		        } else {
		        	$pathToFolow = $_SESSION['config']['corepath'];
		        }
				if(strpos($crontabToSave[$id]['cmd'], $pathToFolow.'modules/notifications/batch/scripts/') !== 0) {
					$error = 1;
					break;
				}
			} else if($e['state'] == "hidden"){
				if ($e['cmd'] != $crontabBeforeSave[$id]['cmd']) {
					$error = 1;
					break;
				}
			} else {
				$error = 1;
				break;
			}
		}

		return $error;
	}

}