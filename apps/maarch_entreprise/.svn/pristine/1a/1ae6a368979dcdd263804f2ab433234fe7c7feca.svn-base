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
* @brief Script used to populate tree branches
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

if(isset($_REQUEST['branch_id']) && !empty($_REQUEST['branch_id']) && isset($_REQUEST['IdTree']) && !empty($_REQUEST['IdTree']))
{
	$string = $_REQUEST['branch'];
	$search="'branch_level_id'";
	$search="#branch_level_id\":(.*)\,#U";
	preg_match($search,$string,$out);
	$count=count($out[0]);
	if($count == 1)
	{
		$find = true;
	}
	$branch_level_id = str_replace("branch_level_id\":", "", $out[0]);
	$branch_level_id = str_replace(",", "", $branch_level_id);
	$branch_level_id = str_replace("\"", "", $branch_level_id);

	$core_tools = new core_tools();
	$core_tools->load_lang();
	$func = new functions();
	$tree_id = $_REQUEST['IdTree'];
	$db = new Database();
	$where = "";
	if($branch_level_id == "1")
	{
		$stmt = $db->query("SELECT * FROM ".$_SESSION['tablename']['doctypes_second_level']
							." WHERE doctypes_first_level_id = ? and enabled ='Y' order by doctypes_second_level_label asc",
							array($_REQUEST['branch_id'])
							);
		$children = array();
		while($res = $stmt->fetchObject())
		{
			array_push($children, array('id' => $res->doctypes_second_level_id, 'tree' => $_SESSION['doctypes_chosen_tree'], 'key_value' => $res->doctypes_second_level_id, 'label_value' => functions::show_string($res->doctypes_second_level_label, true), 'script' => "show_doctypes"));
		}
		if(count($children) > 0)
		{
			echo "[";
			for($cpt_level2=0; $cpt_level2< count($children); $cpt_level2++)
			{
				echo "{'id':'".functions::xssafe($children[$cpt_level2]['id'])
				."', 'txt':'".functions::xssafe(trim(addslashes($children[$cpt_level2]['label_value'])))
				."', 'canhavechildren' : true, '".functions::xssafe($children[$cpt_level2]['script'])
				."' : 'other', 'key_value' : '".functions::xssafe(trim(addslashes($children[$cpt_level2]['key_value'])))
				."', 'onbeforeopen' : MyBeforeOpen}";
				if(isset($children[$cpt_level2+1]['id']) && !empty($children[$cpt_level2+1]['id']))
				{
					echo ',';
				}
			}
			echo "]";
		}
	}
	if($branch_level_id == "2")
	{
		$stmt = $db->query("SELECT * FROM ".$_SESSION['tablename']['doctypes']
					." WHERE doctypes_second_level_id = ? and enabled ='Y' order by description", 
					array($_REQUEST['branch_id']));
		$children = array();
		while($res = $stmt->fetchObject())
		{
			array_push($children, array('id' => $res->type_id, 'tree' => $_SESSION['doctypes_chosen_tree'], 'key_value' => $res->type_id, 'label_value' => functions::show_string($res->description, true), 'script' => "other"));
		}
		if(count($children) > 0)
		{
			echo "[";
			for($cpt_level3=0; $cpt_level3< count($children); $cpt_level3++)
			{
				echo "{'id':'".functions::xssafe($children[$cpt_level3]['id'])."', 'txt':'"
				.functions::xssafe(trim(addslashes($children[$cpt_level3]['label_value'])))
				."', 'canhavechildren' : false, '".functions::xssafe($children[$cpt_level3]['script'])
				."' : 'default', 'key_value' : '".functions::xssafe(trim(addslashes($children[$cpt_level3]['key_value'])))
				."', 'img' : 'page.gif'}";
				if(isset($children[$cpt_level3+1]['id']) && !empty($children[$cpt_level3+1]['id']))
				{
					echo ',';
				}
			}
			echo "]";
		}
	}
}

