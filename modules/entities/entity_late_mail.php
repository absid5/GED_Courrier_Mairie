<?php
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
            	$content .='<span>'._SHOW_FORM_RESULT.' : </span> <input type="radio" name="form_report" id="report_graph"  value="graph" checked="checked" /><label for="report_graph"> '._GRAPH.'</label><input type="radio" name="form_report" id="report_array" value="array" /><label for="report_array"> '. _ARRAY . '</label>' ;
            $content .='</p>';
            $content .='<br/>';


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
          

          $content .='<input type="radio" name="type_period" id="period_by_year" value="year" checked="checked" style="display:none;"/>';
 
        $content .='</td>';
        $content .='<td><input type="button" name="validate" value="'._VALIDATE.'" class="button" onclick="valid_report_by_period(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=get_entity_late_mail\');" /></td>';
        $content .='</tr>';
       $content .='</table>';
	$content .='</form>';
$content .='</div>';
$content .='<div id="result_period_report"></div>';
$js ='valid_report_by_period(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=get_entity_late_mail\');';


echo "{content : '".addslashes($content)."', exec_js : '".addslashes($js)."'}";
exit();
