<?php
/*
 *
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
* File : entity_process_delay.php
*
* @brief Display form to search in stats
*
* @package  Maarch Entreprise 1.1
* @version 1
* @since 03/2009
* @license GPL
* @author  Loic Vinet  <dev@maarch.org>
*/


require_once('modules'.DIRECTORY_SEPARATOR."reports".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
require_once('modules'.DIRECTORY_SEPARATOR."entities".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_entities.php");




$core_tools = new core_tools();
$rep = new reports();
$core_tools->load_lang();
$id = '';
if(isset($_REQUEST['arguments']) && !empty($_REQUEST['arguments']))
{
	$id = $rep->get_arguments_for_report($_REQUEST['arguments'], 'id');
}

$content = '';
$content .='<div id="params">';
	$content .='<form id="report_by_period_form" name="report_by_period_form" method="get" action="">';
	$content .='<input type="hidden" name="id_report" id="id_report" value="'.$id.'" />';
	$content .='<table width="100%" border="0">';
        $content .='<tr>';
          $content .='<td align="left">';
          $content .='<p>';
            	$content .='<span>'._SHOW_FORM_RESULT.' : </span> <input type="radio" name="form_report" id="report_graph"  value="graph" checked="checked" /><label for="report_graph"> ' . _GRAPH . ' </label><input type="radio" name="form_report" id="report_array" value="array" /><label for="report_array"> '. _ARRAY . '</label>' ;
            $content .='</p>';
            $content .='<br/>';


          /*$content.='<p class="double" style="margin-left:10px">';
          $content.= _CHOOSE_ONE_ENTITY.' :<br /><br />';
          $content.='<select style="width:300px" name="entitieslist[]" id="entitieslist" size="7" ondblclick="moveclick($(entitieslist), $(entities_chosen))" multiple="multiple">';

          $db = new Database();
          $stmt = $db->query("select entity_id, short_label from entities where enabled = 'Y' order by short_label");
          
          while($res = $stmt->fetchObject()){                             
            $content.="<option value='".$res->entity_id."'>".$res->short_label."</option>";
          }
          $content.='</select>';

          $content.='<input style="margin-left:10px;margin-right:5px" type="button" class="button" value="Ajouter >>" onclick="Move($(entitieslist), $(entities_chosen));" />';
          //$content.='<br />';
          $content.='<input style="margin-left:5px;margin-right:10px" type="button" class="button" value="<< Enlever" onclick="Move($(entities_chosen), $(entitieslist));" />';

          $content.='<select style="width:300px" name="entities_chosen[]" id="entities_chosen" size="7" ondblclick="moveclick($(entities_chosen), $(entitieslist))" multiple="multiple"></select>';
          $content.='</p>'; 
          $content.='<br/><br/>';*/


          $content.='<p class="double" style="margin-left:10px">';
          $content.= _CHOOSE_FILTER_ENTITY.' :<br /><br />';

          $entities = array();
          $ent = new entity();
            $except[] = $_SESSION['m_admin']['entity']['entityId'];
    
          $entities=$ent->getShortEntityTree($entities, 'all', '', $except );

          $content.='<select name="entitieslist"  size="10" style="width:300px; height:150px" ondblclick="moveclick($(entitieslist), $(entities_chosen))" multiple="multiple">';
          for($i=0; $i<count($entities);$i++)
          {
            $content.="<option";
            $content.=" value='".$entities[$i]['ID']."'>";
            $content.=$entities[$i]['LABEL']."</option>";                 
          }             
          $content.='</select>';

          $content.='<input style="margin-left:10px;margin-right:5px" type="button" class="button" value="Ajouter >>" onclick="Move($(entitieslist), $(entities_chosen));" />';
          //$content.='<br />';
          $content.='<input style="margin-left:5px;margin-right:10px" type="button" class="button" value="<< Enlever" onclick="Move($(entities_chosen), $(entitieslist));" />';

          $content.='<select style="width:300px; height:150px" name="entities_chosen[]" id="entities_chosen" size="7" ondblclick="moveclick($(entities_chosen), $(entitieslist))" multiple="multiple"></select>';
          $content.='</p>'; 
          $content.='<br/><br/>';
          








           $content .='<p class="double">';
             $content .='<input type="radio" name="type_period" id="period_by_year" value="year" checked="checked" />';
            $content .= _SHOW_YEAR_GRAPH;
	 		$content .='<select name="the_year" id="the_year">';
            $year=date("Y");
			$i_current=date("Y'");
			while ($year <> ($i_current-5))
			{
             	$content .= '<option value = "'.$year.'">'.$year.'</option>';
             	$year= $year-1;
			}
            $content .='</select>';
            $content .='</p>';

             $content .='<p class="double">';
               $content .='<input type="radio" name="type_period" id="period_by_month" value="month" />';
               $content .= _SHOW_GRAPH_MONTH;
   				$content .='<select name="the_month" id="the_month">';
              		$content .='<option value ="01"> '. _JANUARY.' </option>';
                  	$content .='<option value ="02"> '._FEBRUARY.' </option>';
                 	$content .='<option value ="03"> '._MARCH.' </option>';
                 	$content .='<option value ="04"> '._APRIL.' </option>';
                 	$content .='<option value ="05"> '._MAY.' </option>';
                 	$content .='<option value ="06"> '._JUNE.' </option>';
                 	$content .='<option value ="07"> '._JULY.' </option>';
                 	$content .='<option value ="08"> '._AUGUST.' </option>';
                	$content .='<option value ="09"> '._SEPTEMBER.' </option>';
                	$content .='<option value ="10"> '._OCTOBER.'</option>';
                 	$content .='<option value ="11"> '._NOVEMBER.' </option>';
                 	$content .='<option value ="12"> '._DECEMBER.' </option>';
               	$content .='</select>';
	          $content .= _OF_THIS_YEAR.'.</p>';
	   		
        $content .='</td>';
        $content .='<td><input type="button" name="validate" value="'._VALIDATE.'" class="button" onclick="valid_report_by_period(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=get_entity_process_delay\');" /></td>';
        $content .='</tr>';
       $content .='</table>';
	$content .='</form>';
$content .='</div>';
$content .='<div id="result_period_report"></div>';
$js ='valid_report_by_period(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=get_entity_process_delay\');';

/*
echo "{content : '".addslashes($content)."', exec_js : '".addslashes($js)."'}";
$js ='valid_viewfolder(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=folder&page=get_folder_view_stats_val\');';
*/

echo "{content : '".addslashes($content)."', exec_js : '".addslashes($js)."'}";
exit();
