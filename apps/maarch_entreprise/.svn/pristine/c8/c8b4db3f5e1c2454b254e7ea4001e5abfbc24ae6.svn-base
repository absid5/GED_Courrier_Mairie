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
* @brief  Script called by an ajax object to process the document type change from the details page
*
* @file change_doctype_details.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_types.php");

$core = new core_tools();
$core->load_lang();
$type = new types();

if(!isset($_REQUEST['type_id']) || empty($_REQUEST['type_id']))
{
	$_SESSION['error'] = _DOCTYPE.' '._IS_EMPTY;
	echo "{status : 1, error_txt : '".addslashes($_SESSION['error'])."'}";
	exit();
}

$mandatory_indexes = $type->get_mandatory_indexes($_REQUEST['type_id'], 'letterbox_coll');
$indexes = $type->get_indexes($_REQUEST['type_id'], 'letterbox_coll');

$opt_indexes = '';
if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"]))
{
	$browser_ie = true;
	$display_value = 'block';
}
elseif(preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) && !preg_match('/opera/i', $HTTP_USER_AGENT) )
{
	$browser_ie = true;
	$display_value = 'block';
}
else
{
	$browser_ie = false;
	$display_value = 'table-row';
}
$opt_indexes  = '';
if(count($indexes) > 0)
{
	$opt_indexes .= '<br/>';
	$opt_indexes .= '<h2><span class="date"><b>'._OPT_INDEXES.'</b></span></h2>';
	$opt_indexes .= '<br/>';
	$opt_indexes .= '<table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">';
	$i=0;
	foreach(array_keys($indexes) as $key)
	{
		$mandatory = false;
		if(in_array($key, $mandatory_indexes))
		{
			$mandatory = true;
		}

		if($i%2 != 1 || $i==0) // pair
		{
			$opt_indexes .= '<tr class="col">';
		}
		$opt_indexes .= '<th align="left" class="picto" >';

		if(isset($indexes[$key]['img']))
		{
			$opt_indexes .= '<i title="'.$indexes[$key]['label'].'" class="fa fa-'.$indexes[$key]['img'].' fa-2x"></i></a>';
		}
		$opt_indexes .= '</th>';
		$opt_indexes .= '<td align="left" width="200px">';
			$opt_indexes .= $indexes[$key]['label'].' :';
		$opt_indexes .= '</td>';

		$opt_indexes .= '<td>';

		if($indexes[$key]['type_field'] == 'input')
		{
			if($indexes[$key]['type'] == 'string' || $indexes[$key]['type'] == 'float' || $indexes[$key]['type'] == 'integer')
			{
				$opt_indexes .= '<input type="text" name="'.$key.'" id="'.$key.'" value="';
				if($indexes[$key]['default_value'] <> false)
				{
					$opt_indexes .= functions::show_string($indexes[$key]['default_value'], true);
				}
				$opt_indexes .='" size="40"  />';
			}
			else if($indexes[$key]['type'] == 'date')
			{
				$opt_indexes .= '<input type="text" name="'.$key.'" id="'.$key.'" value="';
				if($indexes[$key]['default_value'] <> false)
				{
					$opt_indexes .= functions::format_date_db($indexes[$key]['default_value'], true);
				}
				$opt_indexes .='" size="40"  onclick="showCalender(this);" />';
			}
		}
		else
		{
			$opt_indexes .= '<select name="'.$key.'" id="'.$key.'" >';
					$opt_indexes .= '<option value="">'._CHOOSE.'...</option>';
					for($i=0; $i<count($indexes[$key]['values']);$i++)
					{
						$opt_indexes .= '<option value="'.$indexes[$key]['values'][$i]['id'].'"';
						if($indexes[$key]['default_value'] <> false)
						{
							$opt_indexes .=  'selected="selected"';
						}
						$opt_indexes .= '>'.$indexes[$key]['values'][$i]['label'].'</option>';
					}
			$opt_indexes .= '</select>';
		}
		$opt_indexes .= '</td>';

		if($i%2 == 1 && $i!=0) // impair
		{
			$opt_indexes .= '</tr>';
		}
		else
		{
			{
			if($i+1 == count($indexes))
				$opt_indexes .= '<td  colspan="2">&nbsp;</td></tr>';
			}
		}
		$i++;
	}
	$opt_indexes .= '</table>';
}

echo "{status : 0,  new_opt_indexes : '".addslashes($opt_indexes)."'}";
exit();
?>
