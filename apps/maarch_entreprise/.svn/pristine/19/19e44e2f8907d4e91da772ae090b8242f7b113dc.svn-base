<?php
/*
*    Copyright 2015 Maarch
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
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

if(isset($_REQUEST['branch_id']) && !empty($_REQUEST['branch_id']) && isset($_REQUEST['tree_id']) && !empty($_REQUEST['tree_id']))
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

	require_once 'core/class/class_functions.php';
	require_once 'core/class/class_core_tools.php';
	require_once("apps".DIRECTORY_SEPARATOR."maarch_entreprise".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_contacts_v2.php");

	$core_tools = new core_tools();
	$core_tools->load_lang();
	$core_tools->test_user();
	$func = new functions();
	$tree_id = $_REQUEST['tree_id'];
	$db = new Database();
	$contactv2 = new contacts_v2();
	$where = "";

	if($branch_level_id == "1") {
		$stmt = $db->query("SELECT contact_id, society, society_short, lastname, firstname, is_corporate_person, enabled 
						FROM ".$_SESSION['tablename']['contacts_v2']." WHERE contact_type = ? order by society, lastname ",
						array($_REQUEST['branch_id']));
		$children = array();
		while($res = $stmt->fetchObject()) {
	        $contact = '';
	        if($res->is_corporate_person == 'Y'){
	            $contact = ucfirst($func->show_string($res->society, true));
	            if ($res->society_short <> '') {
	               $contact .= ' ('.$res->society_short.')';
	            }
	        } else {
	            $contact = strtoupper($func->show_string($res->lastname, true)) . ' ' . $func->show_string($res->firstname, true);
	            if ($res->society <> '') {
	               $contact .= ' ('.$res->society.')';
	            }
	        }
	        array_push($children, array('id' => $res->contact_id, 'contact_label' => $contact, 'enabled' => $res->enabled));
		}
		if(count($children) > 0) {
			$openlink =  $_SESSION['config']['businessappurl']."index.php?display=true&page=get_tree_children_contact";
			echo "[";
			for($cpt_level2=0; $cpt_level2< count($children); $cpt_level2++) {
				$color = "";
				if ($children[$cpt_level2]['enabled'] ==  'N') {
					$color = "style=\"color:red;\"";
				}
				echo "{'id':'contact_".functions::xssafe($children[$cpt_level2]['id'])."', 
						'txt':'<a ".$color." onmouseover=\'this.style.cursor=\"pointer\";\' onclick=\"window.top.location.href=\'". $_SESSION['config']['businessappurl']."index.php?page=contacts_v2_up&id="
							.functions::xssafe($children[$cpt_level2]['id'])."&fromContactTree\';\">"
							.addslashes(functions::xssafe($children[$cpt_level2]['contact_label']))."</a>',
						'onopenpopulate' : funcOpen, 
						'openlink' : '".$openlink."', 
						'canhavechildren' : true,
						'branch_level_id' : 2}";

				if(isset($children[$cpt_level2+1]['id']) && !empty($children[$cpt_level2+1]['id'])) {
					echo ',';
				}
			}
			echo "]";
		}
	}
	if($branch_level_id == "2") {
		$branchIdContact = substr($_REQUEST['branch_id'], 8);

		$stmt = $db->query("SELECT id, contact_purpose_id, lastname, firstname, address_num, address_street, address_town, address_postal_code, enabled 
							FROM ".$_SESSION['tablename']['contact_addresses']." where contact_id = ? order by lastname, firstname, address_num",
							array($branchIdContact));
		
		$children = array();
		while($res = $stmt->fetchObject()) {
			$address = '';
			$address = '('.$contactv2->get_label_contact($res->contact_purpose_id, $_SESSION['tablename']['contact_purposes']).') ';
			if ($res->lastname <> '' || $res->firstname <> ''){
			    $address .= strtoupper($func->show_string($res->lastname, true)) . ' ' . $func->show_string($res->firstname, true) . ' : ';
			}
			$address .= $func->show_string($res->address_num, true) . ' ' . $func->show_string($res->address_street, true) . ' ' . $func->show_string($res->address_postal_code, true) . ' ' . $func->show_string($res->address_town, true);
			array_push($children, array('id' => $res->id, 'address_label' => $address, 'enabled' => $res->enabled));
		}
		if(count($children) > 0) {
			echo "[";
			for($cpt_level3=0; $cpt_level3< count($children); $cpt_level3++) {
				$color = "";
				if ($children[$cpt_level3]['enabled'] ==  'N') {
					$color = "style=\"color:red;\"";
				}
				
				echo "{'id':'address_".$children[$cpt_level3]['id']."', 
						'txt':'<span ".$color.">".trim(addslashes(functions::xssafe($children[$cpt_level3]['address_label'])))."</span>', 
						'canhavechildren' : false, 
						'img' : 'page.gif'}";
				if(isset($children[$cpt_level3+1]['id']) && !empty($children[$cpt_level3+1]['id'])) {
					echo ',';
				}
			}
			echo "]";
		}
	}
}
