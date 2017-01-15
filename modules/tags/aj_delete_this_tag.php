<?php
/*
*    Copyright 2008,2012 Maarch
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
* Module : Tags
* 
* This module is used to store ressources with any keywords
* V: 1.0
*
* @file
* @author Loic Vinet
* @date $date$
* @version $Revision$
*/


try{
    require_once 'core/class/ActionControler.php';
    require_once 'core/class/ObjectControlerAbstract.php';
    require_once 'core/class/ObjectControlerIF.php';
    require_once 'core/class/class_request.php' ;
   	require_once 'modules/tags/class/TagControler.php' ;
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}


$core = new core_tools();
$core->load_lang();

$p_tag_label = $_REQUEST['p_tag_label'];
if(!isset($_REQUEST['p_tag_label']) || empty($_REQUEST['p_tag_label']))
{
	echo "{status : 1, error_txt : '".addslashes( TAG_LABEL.' '._IS_EMPTY)."'}";
	exit();
}

//$p_res_id = $_REQUEST['p_res_id'];
//if(!isset($_REQUEST['p_res_id']) || empty($_REQUEST['p_res_id']))
//{
//	echo "{status : 1, error_txt : '".addslashes( RES_ID.' '._IS_EMPTY)."'}";
//	exit();
//}

//$p_coll_id = $_REQUEST['p_coll_id'];
//if(!isset($_REQUEST['p_coll_id']) || empty($_REQUEST['p_coll_id']))
//{
//	echo "{status : 1, error_txt : '".addslashes( COLL_ID.' '._IS_EMPTY)."'}";
//	exit();
//}

$tag = new tag_controler;
$result = $tag->remove_this_tags_in_session($p_tag_label);

if ($result){
	echo "{status : 0, value : '".addslashes($p_tag_label)."'}";
	exit();
}
else
{
	echo "{status : 1, error_txt : '".addslashes( _RESTAG.' '._DOESNTEXISTS)."'}";
	exit();	
}

?>
