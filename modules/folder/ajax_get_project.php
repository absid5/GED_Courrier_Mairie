<?php
/*
 * Copyright (C) 2008-2015 Maarch
 *
 * This file is part of Maarch.
 *
 * Maarch is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Maarch is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maarch.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
* File : ajax_get_project.php
*
* Script called by an ajax object to get the project id  given a market id (index_mlb.php)
*
* @package  maarch
* @version 1
* @since 10/2005
* @license GPL v3
* @author  Claire Figueras  <dev@maarch.org>
*/

$db = new Database();
$core = new core_tools();
$core->load_lang();

if(!isset($_REQUEST['id_market']) || empty($_REQUEST['id_market']))
{
	//$_SESSION['error'] = _MARKET.' '._IS_EMPTY;
	echo "{status : 1, error_txt : '".addslashes( _MARKET.' '._IS_EMPTY)."'}";
	exit();
}
$stmt = $db->query('SELECT parent_id FROM '.$_SESSION['tablename']['fold_folders'].' WHERE folders_system_id = ?', array($_REQUEST['id_market']));

if($stmt->rowCount() < 1)
{
	//$_SESSION['error'] = _MARKET.' '._IS_EMPTY;
	echo "{status : 1, error_txt : '".addslashes( _MARKET.' '._IS_EMPTY)."'}";
	exit();
}
$res = $stmt->fetchObject();
$parent_id = $res->parent_id;
$stmt = $db->query('SELECT folder_name, subject, folders_system_id FROM '.$_SESSION['tablename']['fold_folders'].' WHERE folders_system_id = ?', array($parent_id));

if($stmt->rowCount() < 1)
{
	//$_SESSION['error'] = _MARKET.' '._IS_EMPTY;
	echo "{status : 1, error_txt : '".addslashes( _MARKET.' '._IS_EMPTY)."'}";
	exit();
}
$res = $stmt->fetchObject();
echo "{status : 0, value : '".functions::show_string($res->folder_name).', '.functions::show_string($res->subject).' ('.functions::show_string($res->folders_system_id).')'."'}";
exit();
?>
