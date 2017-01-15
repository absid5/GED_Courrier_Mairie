<?php
/*
*    Copyright 2008,2009,2010 Maarch
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
* @brief  Contains the controler of the Service Object
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/


// To activate de debug mode of the class
$_ENV['DEBUG'] = false;
/*
define("_CODE_SEPARATOR","/");
define("_CODE_INCREMENT",1);
*/

// Loads the required class
try {
	require_once("core/class/Service.php");
	require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."usergroups_controler.php");
	require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."users_controler.php");
	require_once 'core/core_tables.php';
} catch (Exception $e){
	echo functions::xssafe($e->getMessage()).' // ';
}

/**
* @brief  Controler of the Service Object
*
* @ingroup core
*/
class ServiceControler
{
	/**
	* Database object used to connnect to the database
    */
	private static $db;

	/**
	* usergroups_services table
    */
	private static $usergroups_services_table;


	/**
	* Opens a database connexion and values the tables variables
	*/
	public function connect()
	{
		$db = new Database();

		self::$usergroups_services_table = USERGROUPS_SERVICES_TABLE;
		self::$db=$db;
	}

	public function loadEnabledServices()
	{
		$_SESSION['enabled_services'] = array();
		for($i=0; $i<count($_SESSION['app_services']);$i++)
		{
			if($_SESSION['app_services'][$i]['enabled'] == "true")
			{
				array_push($_SESSION['enabled_services'], array('id' => $_SESSION['app_services'][$i]['id'], 'label' => $_SESSION['app_services'][$i]['name'], 'comment' =>$_SESSION['app_services'][$i]['comment'], 'type' => $_SESSION['app_services'][$i]['servicetype'],'parent' => 'application', 'system' => $_SESSION['app_services'][$i]['system_service']));
			}
		}
		foreach(array_keys($_SESSION['modules_services']) as $value)
		{
			for($i=0; $i < count($_SESSION['modules_services'][$value]); $i++)
			{
				if($_SESSION['modules_services'][$value][$i]['enabled'] == "true")
				{
					array_push($_SESSION['enabled_services'], array('id' => $_SESSION['modules_services'][$value][$i]['id'], 'label' => $_SESSION['modules_services'][$value][$i]['name'], 'comment' => $_SESSION['modules_services'][$value][$i]['comment'], 'type' => $_SESSION['modules_services'][$value][$i]['servicetype'],'parent' => $value, 'system' =>$_SESSION['modules_services'][$value][$i]['system_service'] ));
				}
			}
		}
	}

	/**
	* Loads into session all the services for a user
	*
	* @param  $user_id  string User identifier
	* @param  $include_system  bool If true include the system services, false otherwise (true by default)
	*/
	public function loadUserServices($user_id)
	{
		$services = array();
		
		// #TODO : Au lieu de partir des services, partir plutot des groupes de l'utilisateur et récuperer tous les services 
		// associés aux groupes
		if($user_id == "superadmin")
		{
			$services = self::getAllServices();
		}
		else
		{
			$tmpServices = array();
			for ($i = 0; $i < count($_SESSION['enabled_services']); $i ++) {
				if ($_SESSION['enabled_services'][$i]['system'] == true ) {
					$services[$_SESSION['enabled_services'][$i]['id']] = true;
				} else {
					$tmpServices[] = $_SESSION['enabled_services'][$i]['id'];
				}
			}
			$ugc = new usergroups_controler();
			self::connect();
			$stmt = self::$db->query(
				'select distinct us.service_id from ' . USERGROUPS_SERVICES_TABLE
				. ' us, ' . USERGROUP_CONTENT_TABLE 
				. " uc where us.group_id = uc.group_id and uc.user_id = ?", 
				array($user_id)
			);
			
			while($res = $stmt->fetchObject()) {
				$serviceId = $res->service_id;
				if (in_array($serviceId, $tmpServices)) {
					$services[$serviceId] = true;
				} else {
					$services[$serviceId] = false;
				}
			}
		}
		return $services;
	}

		/**
	* Loads into session all the services for the superadmin
	*
	*/
	private function getAllServices()
	{
		$services = array();
		for($i=0; $i< count($_SESSION['enabled_services']);$i++)
		{
				$services[$_SESSION['enabled_services'][$i]['id']] = true;
		}
		return $services;
	}
}
?>
