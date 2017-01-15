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
$tags->load_sessiontag($s_id,$coll_id);	
//--------------------------------------
$frm_str .= '<tr><th class="picto" align="left">';
$frm_str .= '<i class="fa fa-tags fa-2x"></i></th>';

$frm_str .= '<td align="left" width="200px">'._TAGS.'</td>';
                                               


$frm_str .= '<td colspan="6">';
if ($modify_doc)
{
	$modify_keyword = true;
}
$tag_customsize = '950px';
$tag_customcols = '120';
include_once 'modules/tags/templates/addtag_userform.php'; //CHARGEMENT DU FORMULAIRE D'AJOUT DE DROITS		

$frm_str .= '</td></tr><style>#tag_userform_chosen{width:auto;}</style>';



if (!$modify_doc){
	$rttagfinaldetail = $route_tag_ui_script_without_modif;
}
else{
	$rttagfinaldetail = $route_tag_ui_script;
}

$frm_str .= '<script type="text/javascript">load_tags('.$rttagfinaldetail.', \''.$s_id.'\', \''.$coll_id.'\');';
$frm_str .= '</script>';

echo $frm_str;
?>
