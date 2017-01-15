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

if (!$tag_customsize)
{
	$tag_customsize = '400px';
}

if (!$tag_customsize)
{
	$tag_customcols = '35';
}

if (!is_array($_SESSION['tagsuser'])) {
	$_SESSION['tagsuser'] = array();
}

//if ($core_tools->test_service('add_tag_to_res', 'tags',false) == 1)
//{
	$tags_list=$tags->get_all_tags();
	//print_r($tags_list);
	
	if($modify_keyword){
		$frm_str .='<select  id="tag_userform" name="tag_userform[]" multiple="" data-placeholder="Aucun mot clé">';
	}else{
		$frm_str .='<select disabled="disabled" id="tag_userform" title="Vous n\'avez pas le droit d\'associer de mots clés" name="tag_userform[]" multiple="" data-placeholder="Aucun mot clé">';
	}

	if (!empty($tags_list)) {
		foreach ($tags_list as $key => $value) {
			if (in_array($value['tag_label'], $_SESSION['tagsuser'])) {
				$frm_str .= '<option selected="selected" value="'.$value['tag_label'].'">'.$value['tag_label'].'</option>';
			}else{
				$frm_str .= '<option value="'.$value['tag_label'].'">'.$value['tag_label'].'</option>';
			}
		}
	}


	$frm_str .='</select>';
	/*$frm_str .='<textarea rows="2" cols="'.$tag_customcols.'" id="tag_userform" '
			 .'style="width:'.$tag_customsize.';" >'.$tag.'</textarea>&nbsp;';
	$frm_str .='<div id="show_tags" class="autocomplete"></div>';
	if($_SESSION['user']['services']['create_tag'] == 1){
		$frm_str .='<input type="button" class="button tagbutton" value="'._ADD.'" onclick="add_this_tags('.$route_tag_add_tags_from_res.', '.$route_tag_ui_script.')">';
	}else{
		$frm_str .='<input type="button" class="button tagbutton" value="'._ADD.'" onclick="add_this_tags('.$route_tag_just_add_tags_from_res.', '.$route_tag_ui_script.')">';
}

	$frm_str .='<p class="tinyminihelp" align="center">'._TAG_SEPARATOR_HELP.'</p>';
	
	$frm_str .= '<script type="text/javascript">launch_autocompleter_tags(\''
                . $_SESSION['config']['businessappurl'] . 'index.php?display='
                . 'true&module=tags&page=autocomplete_tags\','
                . ' \'tag_userform\');</script>';*/
//}

?>