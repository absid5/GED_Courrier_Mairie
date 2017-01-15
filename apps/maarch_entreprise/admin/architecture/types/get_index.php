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

require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_types.php");

$core_tools = new core_tools();
//here we loading the lang vars
$core_tools->load_lang();
$type = new types();
$content = '';

if(!isset($_REQUEST['coll_id']) || empty($_REQUEST['coll_id']))
{
    echo _COLLECTION.' '._IS_EMPTY;
    exit();
}

$indexes = $type->get_all_indexes($_REQUEST['coll_id']);
//$core_tools->show_array($indexes);
if(count($indexes) > 0)
{
    $content .= '<hr/>';
    $content .= '<table border="1" rules="rows" style="width:100%;">';
        $content .= '<tr>';
            $content .= '<th width="400px">'._FIELD.'</th>';
            $content .= '<th align="center" width="100px">'._USED.'</th>';
            $content .= '<th align="center" width="100px">'._MANDATORY.'</th>';
            $content .= '<th align="center" width="100px">'._TYPE_FIELD.'</th>';
            $content .= '<th align="center" width="100px">'._NATURE_FIELD.'</th>';
            $content .= '<th align="center" width="100px">'._DB_COLUMN.'</th>';
            $content .= '<th align="center" width="300px">'._FIELD_VALUES.'</th>';
        $content .= '</tr>';
    for($i=0;$i<count($indexes);$i++)
    {
        $content .= '<tr>';
            $content .= '<td width="150px"> '.$indexes[$i]['label'].'</td>';
            $content .= '<td align="center">';
                $content .= '<input name="fields[]" id="field_'.$indexes[$i]['column'].'" type="checkbox" class="check" value="'.$indexes[$i]['column'].'"';

                if (in_array($indexes[$i]['column'], $_SESSION['m_admin']['doctypes']['indexes']))
                {
                    $content .= 'checked="checked"';
                }
                $content .= '/>';
            $content .= '</td>';
            $content.= '<td align="center" width="100px">';
                $content .= '<input name="mandatory_fields[]" id="mandatory_field_'.$indexes[$i]['column'].'" type="checkbox" class="check" value="'.$indexes[$i]['column'].'"';
                if (in_array($indexes[$i]['column'], $_SESSION['m_admin']['doctypes']['mandatory_indexes']) )
                {
                    $content .= ' checked="checked"';
                }
                $content .= ' onclick="$(\'field_'.$indexes[$i]['column'].'\').checked=true;"/>';
            $content .= '</td>';
            $content.= '<td align="center" width="100px">';
                $content .= $indexes[$i]['type'];
            $content .= '</td>';
            $content.= '<td align="center" width="100px">';
                $content .= $indexes[$i]['type_field'];
            $content .= '</td>';
            $content.= '<td align="center" width="100px">';
                $content .= $indexes[$i]['column'];
            $content .= '</td>';
            $content.= '<td align="left" width="300px">';
                if(isset($indexes[$i]['values']) && count($indexes[$i]['values']) > 0)
                {
                    $content.= '<p id="valuesList'.$indexes[$i]['column'].'" name="valuesList'.$indexes[$i]['column'].'" style="display:none">';
                        for($cptValues=0;$cptValues<count($indexes[$i]['values']);$cptValues++)
                        {
                            $content .= '&nbsp;&nbsp;&nbsp;<a onclick="showValuesList(\'valuesList'.$indexes[$i]['column'].'\', \'valuesSpan'.$indexes[$i]['column'].'\');">'.$indexes[$i]['values'][$cptValues]['id'].' : '.$indexes[$i]['values'][$cptValues]['label'].'</a><br>';
                        }
                    $content.= '<p>';
                    $content.= '<span id="valuesSpan'.$indexes[$i]['column'].'" name="valuesSpan'.$indexes[$i]['column'].'" onclick="showValuesList(\'valuesList'.$indexes[$i]['column'].'\', \'valuesSpan'.$indexes[$i]['column'].'\');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;............................................................</span>';
                }
            $content .= '</td>';
        $content .= '</tr>';
    }
    $content .= '</table>';
    $content .= '<hr/>';
}
echo $content;
