<?php
/*
*    Copyright 2008,2009 Maarch
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
*
*
* @file
* @author Loic Vinet <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

include_once 'modules/tags/route.php';
include_once 'modules/tags/templates/init.php';

if (!$core_tools)
{
	$core_tools = new core_tools();
}

$_SESSION['tagsuser'] = array();
$tags = new tag_controler();
$tags->load_sessiontag($res_id,$coll_id);	
//--------------------------------------


$frm_str .= '<tr id="tag_tr">';
$frm_str .= '<td>'._TAGS.'</td>';
$frm_str .= '</tr><tr><td colspan="3">';
 

$tag_customsize = '360px';
$tag_customcols = '53';

if ($core->test_service('add_tag_to_res', 'tags',false) == 1)
{
	$modify_keyword = true;
}
include_once 'modules/tags/templates/addtag_userform.php'; //CHARGEMENT DU FORMULAIRE D'AJOUT DE DROITS	
$frm_str .= '</td></tr>';

$frm_str .='<style>#tag_userform_chosen{width:100% !important;}</style>';

//$frm_str .= '<td><label for="tag" class="tag_title" ></label></td>';

$frm_str .= '<script type="text/javascript">load_tags('.$route_tag_ui_script.', \''.$res_id.'\', \''.$coll_id.'\');';
$frm_str .= '</script>';
   
?>