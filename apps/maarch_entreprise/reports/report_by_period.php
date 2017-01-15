<?php

/*
*    Copyright 2008-2015 Maarch
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

require_once('modules'.DIRECTORY_SEPARATOR."reports".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
$core_tools = new core_tools();
$rep = new reports();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->test_service('reports', 'reports');
$id = '';
if(isset($_REQUEST['arguments']) && !empty($_REQUEST['arguments']))
{
	$id = $rep->get_arguments_for_report($_REQUEST['arguments'], 'id');
}
if(empty($id))
{
	echo _ID.' '._IS_MISSING;
}

$content = '';
$content .='<div id="params">';
	$content .='<form id="report_by_period_form" name="report_by_period_form" method="get" action="">';
	$content .='<input type="hidden" name="id_report" id="id_report" value="'.$id.'" />';
	$content .='<table width="100%" border="0">';
        $content .='<tr>';
          $content .='<td><i class="fa fa-clock-o fa-4x"></i></td>';
          $content .='<td align="left">';
          $content .='<p>';
            	$content .='<span>'._SHOW_FORM_RESULT.' : </span> <input type="radio" name="form_report" id="report_graph"  value="graph" checked="checked" /><label for="report_graph"> '._GRAPH.'</label><input type="radio" name="form_report" id="report_array" value="array" /><label for="report_array"> '. _ARRAY . '</label>' ;
            $content .='</p>';
            $content .='<br/>';

          if($id <> 'mail_vol_by_cat' && $id <> 'process_delay_generic_evaluation'){
          $content.='<p class="double" style="margin-left:10px">';
          $content.= _CHOOSE.' :<br /><br />';
          $content.='<select style="width:300px" name="entitieslist[]" id="entitieslist" size="7" ondblclick="moveclick($(entitieslist), $(entities_chosen))" multiple="multiple">';

          $db = new Database();
          $stmt = $db->query("SELECT type_id, description FROM ".$_SESSION['tablename']['doctypes']." WHERE enabled = 'Y' order by description");
          
          while($res = $stmt->fetchObject()){                             
            $content.="<option value='".$res->type_id."'>".$res->description."</option>";
          }
          $content.='</select>';

          $content.='<input style="margin-left:10px;margin-right:5px" type="button" class="button" value="Ajouter >>" onclick="Move($(entitieslist), $(entities_chosen));" />';
          //$content.='<br />';
          $content.='<input style="margin-left:5px;margin-right:10px" type="button" class="button" value="<< Enlever" onclick="Move($(entities_chosen), $(entitieslist));" />';

          $content.='<select style="width:300px" name="entities_chosen[]" id="entities_chosen" size="7" ondblclick="moveclick($(entities_chosen), $(entitieslist))" multiple="multiple"></select>';
          $content.='</p>'; 
          $content.='<br/><br/>';
          }


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


	   		if($id <> 'process_delay' && $id <> 'process_delay_generic_evaluation')
	    	{
	           	$content .='<p class="double">';
              	$content .='<input type="radio" id="custom_period" name="type_period" value="custom_period" /><label for="period">'._TITLE_STATS_CHOICE_PERIOD.'.&nbsp;</label>'._SINCE.'&nbsp;:&nbsp;<input name="datestart" type="text"  id="datestart" onclick="showCalender(this);" />&nbsp;'._FOR.'&nbsp;:&nbsp;<input name="dateend" type="text"  id="dateend" onclick="showCalender(this);" /></p>';
        	}
        $content .='</td>';
        $content .='<td><input type="button" name="validate" value="'._VALIDATE.'" class="button" onclick="valid_report_by_period(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=reports&page=get_report_by_period_val\');" /></td>';
        $content .='</tr>';
       $content .='</table>';
	$content .='</form>';
$content .='</div>';
$content .='<div id="result_period_report"></div>';
$js ='valid_report_by_period(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=reports&page=get_report_by_period_val\');';

echo "{content : '".addslashes($content)."', exec_js : '".addslashes($js)."'}";
exit();