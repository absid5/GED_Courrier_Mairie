<?php
/**
* File : change_doctype.php
*
* Script called by an ajax object to process the document type change during
* indexing (index_mlb.php)
*
* @package  maarch
* @version 1
* @since 10/2005
* @license GPL v3
* @author  Cyril Vazquez  <dev@maarch.org>
*/
require_once 'modules/entities/class/class_manage_listdiff.php';
require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";


	$db = new Database();
	$core = new core_tools();
	$core->load_lang();
	$diffList = new diffusion_list();

	$objectType = $_REQUEST['objectType'];
	$objectId = $_REQUEST['objectId'];
	$origin = 'visa';

	// Get listmodel_parameters
	$_SESSION[$origin]['difflist_type'] = $diffList->get_difflist_type($objectType);

	if ($objectId <> '') {
		$_SESSION[$origin]['difflist_object']['object_id'] = $objectId;
		if ($objectType == 'entity_id') {
			$stmt = $db->query("select entity_label from entities where entity_id = ?",array($objectId));
			$res = $stmt->fetchObject();
			if ($res->entity_label <> '') {
				$_SESSION[$origin]['difflist_object']['object_label'] = $res->entity_label;
			}
		}
	}

	// Fill session with listmodel
	$_SESSION[$origin]['diff_list'] = $diffList->get_listmodel($objectType, $objectId);
	$_SESSION[$origin]['diff_list']['difflist_type'] = $_SESSION[$origin]['diff_list']['object_type'];
	$roles = $diffList->list_difflist_roles();
	$circuit = $_SESSION[$origin]['diff_list'];
	if (!isset($circuit['visa']['users']) && !isset($circuit['sign']['users'])){
		echo "{status : 1, error_txt : 'Modèle inexistant'}";
		exit();
	}
	if ( $circuit['object_type'] == 'VISA_CIRCUIT'){
		$id_tab="tab_visaSetWorkflow";
		$id_form="form_visaSetWorkflow";
	}
	else{
		$id_tab="tab_avisSetWorkflow";
		$id_form="form_avisSetWorkflow";
	}

	$content = "";

	$content .= '<thead><tr>';
	$content .= '<th style="width:40%;" align="left" valign="bottom"><span>Visa</span></th>';
	$content .= '<th style="width:5%;"></th>';
	$content .= '<th style="width:5%;"></th>';
	$content .= '<th style="width:5%;"></th>';
	$content .= '<th style="width:5%;"></th>';
	$content .= '<th style="width:45%;" align="left" valign="bottom"><span>Consigne</span></th>';
	$content .= '<th style="width:0;display:none" align="left" valign="bottom"></th>';
	$content .= '<th style="width:0;display:none" align="center" valign="bottom"></th>';

	$content .= '</tr></thead>';
	$content .= '<tbody>';
	$color = "";
	$visa = new visa();
	if (isset($circuit['visa']['users'])){
		foreach($circuit['visa']['users'] as $seq=>$step){
			if($color == ' class="col"') {
				$color = '';
			} else {
				$color = ' class="col"';
			}

			$content .= '<tr ' . $color . '>';
			$content .= '<td>';

			$content .= '<span id="rank_' . $seq . '"> <span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) .'</span> </span>';
			$content .= '<select id="conseiller_'.$seq.'" name="conseiller_'.$seq.'" >';
			$content .= '<option value="" >Sélectionnez un utilisateur</option>';
			
			$tab_userentities = $visa->getEntityVis();

			/** Order by parent entity **/
			foreach ($tab_userentities as $key => $value) {
				$content .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
				$tab_users = $visa->getUsersVis($tab_usergroups[$key]['group_id']);
				foreach($tab_users as $user){
					if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
						$selected = " ";
						if ($user['id'] == $step['user_id'])
							$selected = " selected";
						$content .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
					}
					
				}
				$content .= '</optgroup>';
			}
			$content .= '</select>';

			$content .= "</select>";
			$content .= "<span id=\"signatory_" . $seq . "\">";
			if (empty($circuit['sign']['users']) && $seq == count ($circuit['visa']['users'])-1)
				$content .= " <i title=\"Signataire\" style=\"color : #fdd16c\" class=\"fa fa-certificate fa-lg fa-fw\"></i>";
			$content .= "</span></td>";

			$up = ' style="visibility:visible"';
			$down = ' style="visibility:visible"';
			if (empty($circuit['sign']['users']) && $seq == count ($circuit['visa']['users'])-1){
				$add = ' style="visibility:visible"';
				$down = ' style="visibility:hidden"';
			} else {
				$add = ' style="visibility:hidden"';
			}
			if ($seq == 0)
				$up = ' style="visibility:hidden"';

			$content .= '<td><a href="javascript://"  '.$down.' id="down_'.$seq.'" name="down_'.$seq.'" onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x"></i></a></td>';
			$content .= '<td><a href="javascript://"   '.$up.' id="up_'.$seq.'" name="up_'.$seq.'" onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x"></i></a></td>';
			$content .= '<td><a href="javascript://" onclick="delRow(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="suppr_'.$j.'" name="suppr_'.$j.'" style="visibility:visible;" ><i class="fa fa-user-times fa-2x"></i></a></td>';
			$content .= '<td><a href="javascript://" '.$add.'  id="add_'.$seq.'" name="add_'.$seq.'" onclick="addRow(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x"></i></a></td>';
			$content .= '<td><input type="text" id="consigne_'.$seq.'" name="consigne_'.$seq.'" value="'.$step['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;"/></td>';
			$content .= '<td style="display:none"><input type="hidden" value="'.$step['process_date'].'" id="date_'.$seq.'" name="date_'.$seq.'"/></td>';
			$content .= '<td style="display:none"><input type="checkbox" style="visibility:hidden" id="isSign_'.$seq.'" name="isSign_'.$seq.'" /></td>';
			$content .= '<td><i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i></td>';
			$content .= "</tr>";
		}
	}

//ajout signataire
	if (!empty($circuit['sign']['users'])){				
		$seq = count ($circuit['visa']['users']);
		
		if($color == ' class="col"') {
			$color = '';
		} else {
			$color = ' class="col"';
		}

		$content .= '<tr ' . $color . '>';

		$content .= '<td>';
		$tab_users = $visa->getUsersVis();
		$content .= '<span id="rank_' . $seq . '"> <span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) .'</span> </span>';
		$content .= '<select id="conseiller_'.$seq.'" name="conseiller_'.$seq.'" >';
		$content .= '<option value="" >Sélectionnez un utilisateur</option>';
		
		$tab_userentities = $visa->getEntityVis();

		/** Order by parent entity **/
		foreach ($tab_userentities as $key => $value) {
			$content .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
			$tab_users = $visa->getUsersVis($tab_usergroups[$key]['group_id']);
			foreach($tab_users as $user){
				if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
					$selected = " ";
					if ($user['id'] == $circuit['sign']['users'][0]['user_id'])
						$selected = " selected";
					$content .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
				}
				
			}
			$content .= '</optgroup>';
		}
		$content .= '</select>';
		$content .= "<span id=\"signatory_' . $j . '\"> <i title=\"Signataire\" style=\"color : #fdd16c\" class=\"fa fa-certificate fa-lg fa-fw\"></i></span>";
		$content .= "</td>";

		$up 	= 'style="visibility:visible"';
		$down 	= 'style="visibility:hidden"';
		$add 	= 'style="visibility:visible"';
		$del 	= 'style="visibility:visible"';
		if (count ($circuit['visa']['users']) == 0){
			$up 	= 'style="visibility:hidden"';
			$del 	= 'style="visibility:hidden"';
		}

		$content .= '<td><a href="javascript://" ' . $down ." id=\"down_$seq\" name=\"down_$seq\""	 .' onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x"></i></a></td>';
		$content .= '<td><a href="javascript://" ' . $up   ." id=\"up_$seq\" name=\"up_$seq\""		 .' onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x"></i></a></td>';
		$content .= '<td><a href="javascript://" ' . $del  ." id=\"suppr_$seq\" name=\"suppr_$seq\"" .' onclick="delRow(this.parentNode.parentNode.rowIndex,\''.$id_tab. "')\" ><i class='fa fa-user-times fa-2x'></i></a></td>";
		$content .= '<td><a href="javascript://" ' . $add  ." id=\"add_$seq\" name=\"add_$seq\""	 .' onclick="addRow(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x"></i></a></td>';
		$content .= '<td><input type="text" id="consigne_'.$seq.'" name="consigne_'.$seq.'" value="'.$circuit['sign']['users'][0]['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;"/></td>';
		$content .= '<td style="display:none"><input type="hidden" id="date_'.$seq.'" name="date_'.$seq.'" value="'.$circuit['sign']['users'][0]['process_date'].'" /></td>';
		$content .= '<td style="display:none"><input type="checkbox" style="visibility:hidden" id="isSign_'.$seq.'" name="isSign_'.$seq.'" checked/></td>';
		$content .= '<td><i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i></td>';
		$content .= "</tr>";
	}

	$content .= '</tbody>';

	echo "{status : 0, div_content : '" . addslashes($content.'<br>') . "'}";
	exit();
