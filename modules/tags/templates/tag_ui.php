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
//--------------------------------------

if ($_REQUEST['opt'])
{
	$_dev_option = $_REQUEST['opt'];
}


$core_tools =new core_tools();
$core_tools->load_lang();

$tag_list = $_SESSION['tagsuser']; 


if ($tag_list){

	$aja_str .='<td>';
	foreach($tag_list as $this_tag)
	{
		$tabrr = array( CHR(13) => "", CHR(10) => "" ); 
		$this_tag = strtr($this_tag,$tabrr); 

		
		$styledisplay="";
		$aja_str .= '<div class="tag_element_word" id="taglabel_'.$this_tag.'" onmouseover="this.style.cursor=\'pointer\';">'
			.$this_tag;
			
		if ($core_tools->test_service('delete_tag_to_res', 'tags',false) == 1)
		{
			
			if ($_dev_option == "hide_deletebutton"){}
			else{
				$aja_str .= ' &nbsp;<div class="tag_delete_button" onclick="delete_this_tag('
				.$route_tag_delete_tags_from_res.', \''.addslashes($this_tag).'\', '.$route_tag_ui_script.');" alt="'._TAGCLICKTODEL.'" title="'._TAGCLICKTODEL.'">x</div>';
			}
			}
		$aja_str .= '</div>';
		
	}
}
// else
// {
// 	$aja_str .='<td><div class="notag" align="center">'._NO_TAG.'</div>';
// }


$aja_str .='</td>';


//----------------------------------------------
echo "{status : 0, value : '".addslashes($aja_str)."'}";
exit();

?>
