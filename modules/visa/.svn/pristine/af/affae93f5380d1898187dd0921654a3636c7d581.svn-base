<?php
/*
*   Copyright 2008-2016 Maarch and Document Image Solutions
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the functions to manage visa and notice workflow.
*
* @file
* @author Nicolas Couture <couture@docimsol.com>
* @date $date$
* @version $Revision$
* @ingroup visa
*/

define('FPDF_FONTPATH',$core_path.'apps/maarch_entreprise/tools/pdfb/fpdf_1_7/font/');
require($core_path.'apps/maarch_entreprise/tools/pdfb/fpdf_1_7/fpdf.php');
require($core_path.'apps/maarch_entreprise/tools/pdfb/fpdf_1_7/fpdi.php');

abstract class visa_Abstract extends Database
{

	var	$errorMessageVisa;

	/***
	* Build Maarch module tables into sessions vars with a xml configuration file
	*
	*
	*/
	public function build_modules_tables() {
		if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "config.xml"
        )
        ) {
            $configPath = $_SESSION['config']['corepath'] . 'custom'
                        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                        . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR
                        . "visa" . DIRECTORY_SEPARATOR . "xml"
                        . DIRECTORY_SEPARATOR . "config.xml";
        } else {
            $configPath = "modules" . DIRECTORY_SEPARATOR . "visa"
                        . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                        . "config.xml";
        }
		
		$xmlconfig = simplexml_load_file($configPath);
		$conf = $xmlconfig->CONFIG;
		$_SESSION['modules_loaded']['visa']['exeSign'] = (string) $conf->exeSign;
		$_SESSION['modules_loaded']['visa']['showAppletSign'] = (string) $conf->showAppletSign;
		$_SESSION['modules_loaded']['visa']['reason'] = (string) $conf->reason;
		$_SESSION['modules_loaded']['visa']['location'] = (string) $conf->location;
		$_SESSION['modules_loaded']['visa']['licence_number'] = (string) $conf->licence_number;
		
		$_SESSION['modules_loaded']['visa']['width_blocsign'] = (string) $conf->width_blocsign;
		$_SESSION['modules_loaded']['visa']['height_blocsign'] = (string) $conf->height_blocsign;
		
		$routing_template = (string) $conf->routing_template;
		
		if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR . "Bordereau_visa_modele.pdf"
        )
        ) {
            $routing_template = $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . "modules"
            . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR . "Bordereau_visa_modele.pdf";
        }

		$_SESSION['modules_loaded']['visa']['routing_template'] = $routing_template;
	}
	
	public function getDocsBasket(){
		require_once 'core/class/class_request.php';
		$request    = new request();
		$table = $_SESSION['current_basket']['view'];
		$select[$table]= array(); 
		array_push($select[$table],"res_id", "status", "category_id as category_img", 
                        "contact_firstname", "contact_lastname", "contact_society", "user_lastname", 
                        "user_firstname", "priority", "creation_date", "admission_date", "subject", 
                        "process_limit_date", "entity_label", "dest_user", "category_id", "type_label", 
                        "exp_user_id", "count_attachment", "alt_identifier","is_multicontacts", "locker_user_id", "locker_time");
						
		$where_tab = array();
		//From basket
		if (!empty($_SESSION['current_basket']['clause'])) $where_tab[] = stripslashes($_SESSION['current_basket']['clause']); //Basket clause
		//Order
		$orderstr = "order by creation_date desc";
		if (isset($_SESSION['last_order_basket'])) $orderstr = $_SESSION['last_order_basket'];
		//Request
		$where = implode(' and ', $where_tab);
		$tab=$request->PDOselect($select, $where, array(), $orderstr, $_SESSION['config']['databasetype'], $_SESSION['config']['databasesearchlimit'], false, "", "", "", false, false, 'distinct');
			
		$tab_docs = array();
		foreach($tab as $doc){
			array_push($tab_docs,$doc[0]['value']);
		}
		return $tab_docs;
	}
	
	public function get_rep_path($res_id, $coll_id)
	{
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
		require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."docservers_controler.php");
		$docserverControler = new docservers_controler();
		$sec =new security();
		$view = $sec->retrieve_view_from_coll_id($coll_id);
		if(empty($view))
		{
			$view = $sec->retrieve_table_from_coll($coll_id);
		}
		
		$db = new Database();
		$stmt = $db->query("select docserver_id from res_view_attachments where res_id_master = ?" 
			. "AND status <> 'DEL' order by res_id desc", 
			array($res_id));
		while ($res = $stmt->fetchObject()){
			$docserver_id = $res->docserver_id;
			break;
		}
		
		$stmt = $db->query("select path_template from ".$_SESSION['tablename']['docservers']." where docserver_id = ?", 
			array($docserver_id));
		
		$res = $stmt->fetchObject();
		$docserver_path = $res->path_template;
		
		$stmt = $db->query("select filename, format, path, title, res_id, res_id_version, attachment_type "
			. "from res_view_attachments where res_id_master = ? AND status <> 'OBS' AND status <> 'SIGN' "
			. "AND status <> 'DEL' and attachment_type NOT IN "
			. "('converted_pdf','print_folder') order by creation_date desc",
			array($res_id));

		$array_reponses = array();
		$cpt_rep = 0;
		while ($res2 = $stmt->fetchObject()) {
			$filename = $res2->filename;
			$format = "pdf";
            $filename_pdf = str_ireplace($res2->format, $format, $filename);
			$path = preg_replace('/#/', DIRECTORY_SEPARATOR, $res2->path);
			//$filename_pdf = str_replace(pathinfo($filename, PATHINFO_EXTENSION), "pdf",$filename);
			if (file_exists($docserver_path . $path . $filename_pdf)) {
				$array_reponses[$cpt_rep]['path'] = $docserver_path . $path . $filename_pdf;
				$array_reponses[$cpt_rep]['title'] = $res2->title;
				$array_reponses[$cpt_rep]['attachment_type'] = $res2->attachment_type;
				if ($res2->res_id_version == 0) {
					$array_reponses[$cpt_rep]['res_id'] = $res2->res_id;
					$array_reponses[$cpt_rep]['is_version'] = 0;
				} else {
					$array_reponses[$cpt_rep]['res_id'] = $res2->res_id_version;
					$array_reponses[$cpt_rep]['is_version'] = 1;
				}
				if ($res2->res_id_version == 0 && $array_reponses[$cpt_rep]['attachment_type'] == 'outgoing_mail') {
					$array_reponses[$cpt_rep]['is_version'] = 2;
				}
				$cpt_rep++;
			}
		}
		/*echo "<pre>";
		print_r($array_reponses);
		echo "</pre>";*/
		return $array_reponses;
	}

	protected function isSameFile($firstFile, $secondFile){
		$nb1 = strrpos($firstFile, '.');
		$nb2 = strrpos($secondFile, '.');

		return (substr($firstFile, 0, $nb1) === substr($secondFile, 0, $nb2));
	}

	protected function hasSameFileInArray($fileName, $filesArray){
		foreach($filesArray as $tmpFileName){
			if ($this->isSameFile($fileName, $tmpFileName))
				return true;
		}
		return false;
	}

	public function checkResponseProject($res_id, $coll_id) {
		$this->errorMessageVisa = null;

		$db = new Database();
		$stmt = $db->query("SELECT * FROM res_view_attachments WHERE res_id_master = ? AND coll_id = ? AND status NOT IN ('DEL','OBS','TMP') AND attachment_type NOT IN ('converted_pdf','print_folder') ", array($res_id, $coll_id));
		if ($stmt->rowCount() <= 0) {
			$this->errorMessageVisa = _NO_RESPONSE_PROJECT_VISA;
			return false;
		}

		$resFirstFiles = [];

		while($res = $stmt->fetchObject()){
			if ($res->format == 'doc' || $res->format == 'docx' || $res->format == 'odt')
				array_push($resFirstFiles, $res);
		}

		$stmt = $db->query("SELECT * FROM res_attachments WHERE res_id_master = ? AND coll_id = ? AND attachment_type IN ('converted_pdf') AND status NOT IN ('DEL','OBS','TMP')", array($res_id, $coll_id));

		$resSecondFiles = [];

		while($res = $stmt->fetchObject()){
			array_push($resSecondFiles, $res->filename);
		}
		foreach($resFirstFiles as $tmpObj){
			if ($this->hasSameFileInArray($tmpObj->filename, $resSecondFiles))
				continue;
			if (!$this->errorMessageVisa)
				$this->errorMessageVisa .= _PLEASE_CONVERT_PDF_VISA;
			$this->errorMessageVisa .= '<br/>&nbsp;&nbsp;';
			$this->errorMessageVisa .= $_SESSION['attachment_types'][$tmpObj->attachment_type] . ' : ';
			$this->errorMessageVisa .= $tmpObj->title;
		}
		return true;
	}

	public function getWorkflow($res_id, $coll_id, $typeList){
		require_once('modules/entities/class/class_manage_listdiff.php');
        $listdiff = new diffusion_list();
        $roles = $listdiff->list_difflist_roles();
        $circuit = $listdiff->get_listinstance($res_id, false, $coll_id, $typeList);
		if (isset($circuit['copy'])) unset($circuit['copy']);
		return $circuit;
	}
	
	public function saveWorkflow($res_id, $coll_id, $workflow, $typeList){
		require_once('modules/entities/class/class_manage_listdiff.php');
		$diff_list = new diffusion_list();
		
		$diff_list->save_listinstance(
            $workflow, 
            $typeList,
            $coll_id, 
            $res_id, 
            $_SESSION['user']['UserId'],
            $_SESSION['user']['primaryentity']['id']
        );    
		
	}
	
	public function saveModelWorkflow($id_list, $workflow, $typeList, $title){
		require_once('modules/entities/class/class_manage_listdiff.php');
		$diff_list = new diffusion_list();

		
		$diff_list->save_listmodel(
            $workflow, 
			$typeList,
			$id_list,
			$title
        );    
	}


	protected function getWorkflowsNumberByTitle($title){
		$db = new Database();
		$stmt = $db->query("SELECT * FROM listmodels WHERE title = ?", array($title));
		return $stmt->rowCount();
	}

	public function isWorkflowTitleFree($title){
		$nb = $this->getWorkflowsNumberByTitle($title);
		if ($nb == 0)
			return true;
		else
			return false;
	}

	public function deleteWorkflow($res_id, $coll_id){
		$db = new Database();
		$db->query("DELETE FROM visa_circuit WHERE res_id= ? AND coll_id= ?",array($res_id, $coll_id));
	}
	
	public function nbVisa($res_id, $coll_id){
		$db = new Database();
		$stmt = $db->query("SELECT listinstance_id from listinstance WHERE res_id= ? and coll_id = ? and item_mode = ?", array($res_id, $coll_id, 'visa'));
		return $stmt->rowCount();
	}
	
	public function getCurrentStep($res_id, $coll_id, $listDiffType){
		$db = new Database();
		$stmt = $db->query("SELECT sequence, item_mode from listinstance WHERE res_id= ? and coll_id = ? and difflist_type = ? and process_date ISNULL ORDER BY listinstance_id ASC LIMIT 1", array($res_id, $coll_id, $listDiffType));
		$res = $stmt->fetchObject();
		if ($res->item_mode == 'sign'){
			return $this->nbVisa($res_id, $coll_id);
		}
		return $res->sequence;
	}

	public function getUsersCurrentVis($res_id){
		$db = new Database();
		$result = array();
		$stmt = $db->query("SELECT item_id from listinstance WHERE res_id= ? and difflist_type = 'VISA_CIRCUIT'  ORDER BY sequence ASC", array($res_id));
		while ( $res = $stmt->fetchObject()) {
			$result[] = $res->item_id;
		}	
		return $result;
	}

	public function getStepDetails($res_id, $coll_id, $listDiffType, $sequence)
	{
		$stepDetails = array();
		$db = new Database();
		$stmt = $db->query("SELECT * "
			. "from listinstance WHERE res_id= ? and coll_id = ? "
			. "and difflist_type = ? and sequence = ? "
			. "ORDER BY listinstance_id ASC LIMIT 1", 
			array($res_id, $coll_id, $listDiffType, $sequence));
		
		$res = $stmt->fetchObject();
		$stepDetails['listinstance_id'] = $res->listinstance_id;
		$stepDetails['coll_id'] = $res->coll_id;
		$stepDetails['res_id'] = $res->res_id;
		$stepDetails['listinstance_type'] = $res->listinstance_type;
		$stepDetails['sequence'] = $res->sequence;
		$stepDetails['item_id'] = $res->item_id;
		$stepDetails['item_type'] = $res->item_type;
		$stepDetails['item_mode'] = $res->item_mode;
		$stepDetails['added_by_user'] = $res->added_by_user;
		$stepDetails['added_by_entity'] = $res->added_by_entity;
		$stepDetails['visible'] = $res->visible;
		$stepDetails['viewed'] = $res->viewed;
		$stepDetails['difflist_type'] = $res->difflist_type;
		$stepDetails['process_date'] = $res->process_date;
		$stepDetails['process_comment'] = $res->process_comment;
		
		return $stepDetails;
	}
	
	public function myPosVisa($res_id, $coll_id, $listDiffType){
		$db = new Database();
		$stmt = $db->query("SELECT sequence, item_mode from listinstance WHERE res_id= ? and coll_id = ? and difflist_type = ? and item_id = ? and  process_date ISNULL ORDER BY listinstance_id ASC LIMIT 1", array($res_id, $coll_id, $listDiffType, $_SESSION['user']['UserId']));
		
		$res = $stmt->fetchObject();
		if ($res->item_mode == 'sign'){
			return $this->nbVisa($res_id, $coll_id);
		}
		return $res->sequence;
	}
	
	public function getUsersVis($group_id = null){
		$db = new Database();
		
		if($group_id <> null){
			$stmt = $db->query("SELECT users.user_id, users.firstname, users.lastname, usergroup_content.group_id,entities.entity_id from users, usergroup_content, users_entities,entities WHERE users_entities.user_id = users.user_id and 
				users_entities.primary_entity = 'Y' and users.user_id = usergroup_content.user_id AND entities.entity_id = users_entities.entity_id AND group_id IN 
				(SELECT group_id FROM usergroups_services WHERE service_id = ? AND group_id = ?)  order by users.lastname", array('visa_documents',$group_id));
		}else{
			$stmt = $db->query("SELECT users.user_id, users.firstname, users.lastname, usergroup_content.group_id,entities.entity_id from users, usergroup_content, users_entities,entities WHERE users_entities.user_id = users.user_id and 
				users_entities.primary_entity = 'Y' and users.user_id = usergroup_content.user_id AND entities.entity_id = users_entities.entity_id AND group_id IN 
				(SELECT group_id FROM usergroups_services WHERE service_id = ?)  
				order by users.lastname", array('visa_documents'));
		}
		
		$tab_users = array();
		
		
		while($res = $stmt->fetchObject()){
			array_push($tab_users,array('id'=>$res->user_id, 'firstname'=>$res->firstname,'lastname'=>$res->lastname,'group_id'=>$res->group_id,'entity_id'=>$res->entity_id));
		}
		return $tab_users;
	}

	public function getGroupVis(){
		$db = new Database();
		
		$stmt = $db->query("SELECT DISTINCT(usergroup_content.group_id),group_desc from usergroups, usergroup_content WHERE usergroups.group_id = usergroup_content.group_id AND usergroup_content.group_id IN (SELECT group_id FROM usergroups_services WHERE service_id = ?)", array('visa_documents'));
		
		$tab_usergroup = array();
		
		
		while($res = $stmt->fetchObject()){
			array_push($tab_usergroup,array('group_id'=>$res->group_id,'group_desc'=>$res->group_desc));
		}
		//print_r($tab_usergroup);
		return $tab_usergroup;
	}

	public function getEntityVis(){
		$db = new Database();
		
		$stmt = $db->query("SELECT distinct(entities.entity_id) from users, usergroup_content, users_entities,entities WHERE users_entities.user_id = users.user_id and 
			users_entities.primary_entity = 'Y' and users.user_id = usergroup_content.user_id AND entities.entity_id = users_entities.entity_id AND group_id IN 
			(SELECT group_id FROM usergroups_services WHERE service_id = ?)  
			order by entities.entity_id", array('visa_documents'));
		
		$tab_userentities = array();
		
		
		while($res = $stmt->fetchObject()){
			array_push($tab_userentities,array('entity_id'=>$res->entity_id));
		}
		//print_r($tab_userentities);
		return $tab_userentities;
	}
	
	public function allUserVised($res_id, $coll_id, $typeList){
		$circuit = $this->getWorkflow($res_id, $coll_id, 'VISA_CIRCUIT');
		if (isset($circuit['visa'])) {
			foreach($circuit['visa']['users'] as $seq=>$step){
				if ($step['process_date'] == ''){
					return false;
				}
			}
		}
		return true;
	}
	
	public function getConsigne($res_id, $coll_id, $userId){
		$circuit = $this->getWorkflow($res_id, $coll_id, 'VISA_CIRCUIT');
		if (isset($circuit['visa'])) {
			foreach($circuit['visa']['users'] as $seq=>$step){
				if ($step['user_id'] == $userId){
					return $step['process_comment'];
				}
			}
		}
		if (isset($circuit['sign'])) {
		foreach($circuit['sign']['users'] as $seq=>$step){
			if ($step['user_id'] == $userId){
				return $step['process_comment'];
			}
		}
		}
		return '';
	}

	public function setStatusVisa($res_id, $coll_id){
		$curr_visa_wf = $this->getWorkflow($res_id, $coll_id, 'VISA_CIRCUIT');

		$db = new Database();
		$stmt = $db->query("SELECT sequence, item_mode from listinstance WHERE res_id= ? and coll_id = ? and difflist_type = ? and process_date ISNULL ORDER BY listinstance_id ASC LIMIT 1", array($res_id, $coll_id, 'VISA_CIRCUIT'));
		$resListDiffVisa = $stmt->fetchObject();

		// If there is only one step in the visa workflow, we set status to ESIG
		if ((count($curr_visa_wf['visa']) == 0 && count($curr_visa_wf['sign']) == 1) || $resListDiffVisa->item_mode == "sign"){
	        $mailStatus = 'ESIG';
	    } else {
	        $mailStatus = 'EVIS';
	    }

	    $db->query("UPDATE res_letterbox SET status = ? WHERE res_id = ? ", array($mailStatus, $res_id));

	}
	
	public function getList($res_id, $coll_id, $bool_modif=false, $typeList, $isVisaStep = false, $fromDetail = ""){
		$core_tools =new core_tools();
		if ( $typeList == 'VISA_CIRCUIT'){
			$id_tab="tab_visaSetWorkflow";
			$id_form="form_visaSetWorkflow";
		}
		else{
			$id_tab="tab_avisSetWorkflow";
			$id_form="form_avisSetWorkflow";
		}
		
		if ($fromDetail == "Y" && !$core_tools->test_service('config_visa_workflow_in_detail', 'visa', false)) {
			$bool_modif = false;
		}
				
		$circuit = $this->getWorkflow($res_id, $coll_id, $typeList);
		$str = "";
		if (!isset($circuit['visa']['users']) && !isset($circuit['sign']['users']) && !$core_tools->test_service('config_visa_workflow_in_detail', 'visa', false) && $fromDetail == "Y"){
			$str .= "<div class='error' id='divErrorVisa' name='divErrorVisa' onclick='this.hide();'>" . _EMPTY_USER_LIST . "</div>";
			$str .= "<div><strong><em>" . _EMPTY_VISA_WORKFLOW . "</em></strong></div>";
		}
		else {
			require_once("modules/entities/class/class_manage_listdiff.php");
			$diff_list = new diffusion_list();
			$listModels = $diff_list->select_listmodels($typeList);
		
			$str .= '<div align="center">';
		
			$str .= '<div class="error" id="divErrorVisa" onclick="this.hide();"></div>';
			$str .= '<div class="info" id="divInfoVisa" onclick="this.hide();"></div>';

			if (!empty($listModels) && $bool_modif && !$isVisaStep){
				$str .= '<select name="modelList" id="modelList" onchange="load_listmodel_visa(this.options[this.selectedIndex], \''.$typeList.'\', \''.$id_tab.'\');">';
				$str .= '<option value="">Sélectionnez un modèle</option>';
				foreach($listModels as $lm){
					$str .= '<option value="'.$lm['object_id'].'">'.$lm['title'].'</option>';
				}
				$str .= '</select>';
			}

			$str .= '<table class="listing spec detailtabricator" cellspacing="0" border="0" id="'.$id_tab.'" style="width:100%;">';
			$str .= '<thead><tr>';
			$str .= '<th style="width:40%;" align="left" valign="bottom"><span>Visa</span></th>';
			if ($bool_modif){
				$str .= '<th style="width:5%;"></th>';
				$str .= '<th style="width:5%;"></th>';
				$str .= '<th style="width:5%;"></th>';
				$str .= '<th style="width:5%;"></th>';
				$str .= '<th style="width:45%;" align="left" valign="bottom"><span>Consigne</span></th>';
				$str .= '<th style="width:0;display:none" align="left" valign="bottom"></th>';
				$str .= '<th style="width:0;display:none" align="center" valign="bottom"></th>';
			}
			else {
				$str .= '<th style="width:55%;" align="left" valign="bottom"><span>Consigne</span></th>';
				$str .= '<th style="width:10%;" align="left" valign="bottom"><span>Etat</span></th>';
			}
			$str .= '</tr></thead>';
			$str .= '<tbody>';
			$color = "";
		
			if ($typeList == 'VISA_CIRCUIT'){
				if (!isset($circuit['visa']['users']) && !isset($circuit['sign']['users'])){
					$j=0;
					$str .= '<tr class="col" id="lineVisaWorkflow_'.$j.'">';
					$str .= '<td>';
					$str .= '<span id="rank_' . $j . '"><span class="nbResZero" style="font-weight:bold;opacity:0.5;">1</span> </span>';
					if ($bool_modif){
						$str .= '<select id="conseiller_'.$j.'" name="conseiller_'.$j.'" >';
						$str .= '<option value="" >Sélectionnez un utilisateur</option>';
						
						$tab_userentities = $this->getEntityVis();

						/** Order by parent entity **/
						foreach ($tab_userentities as $key => $value) {
							$str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
							$tab_users = $this->getUsersVis($tab_usergroups[$key]['group_id']);
							foreach($tab_users as $user){
								if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
									$str .= '<option value="'.$user['id'].'" >'.$user['lastname'].', '.$user['firstname'].'</option>';
								}
								
							}
							$str .= '</optgroup>';
						}
						/**************/
						/*$tab_usergroups = $this->getGroupVis();
						foreach ($tab_usergroups as $key => $value) {
							$str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
							$tab_users = $this->getUsersVis($tab_usergroups[$key]['group_id']);
							foreach($tab_users as $user){
								$str .= '<option value="'.$user['id'].'" >'.$user['lastname'].', '.$user['firstname'].' ('.$user['entity_id'].')</option>';
							}
						}
						$str .= '</optgroup>';*/
						$str .= '</select>';
					}
					$str .= '<span id="signatory_' . $j . '"> <i title="Signataire" style="color : #fdd16c" class="fa fa-certificate fa-lg fa-fw"></i></span></td>';
					$str .= '<td><a href="javascript://" id="down_'.$j.'" name="down_'.$j.'" style="visibility:hidden;" onclick="deplacerLigne(0,1,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
					$str .= '<td><a href="javascript://" id="up_'.$j.'" name="up_'.$j.'" style="visibility:hidden;" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
					$str .= '<td><a href="javascript://" onclick="delRow(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="suppr_'.$j.'" name="suppr_'.$j.'" style="visibility:hidden;" ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
					$str .= '<td><a href="javascript://" style="visibility:visible;"  id="add_'.$j.'" name="add_'.$j.'" onclick="addRow(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
					$str .= '<td><input type="text" id="consigne_'.$j.'" name="consigne_'.$j.'" onmouseover="setTitle(this);" style="width:95%;"/></td>';
					$str .= '<td style="display:none"><input type="hidden" id="date_'.$j.'" name="date_'.$j.'"/></td>';

					$str .= '<td style="display:none"><input type="checkbox" id="isSign_'.$j.'" name="isSign_'.$j.'" style="visibility:hidden;" /></td>';
					$str .= '<td><i class="fa fa-plus fa-lg" title="Nouvel utilisateur ajouté"></i></td>';
					$str .= '</tr>';
				}
				else{

					if ($isVisaStep)
						$myPosVisa = $this->myPosVisa($res_id, $coll_id, $typeList);
					if (isset($circuit['visa']['users'])){
						foreach($circuit['visa']['users'] as $seq=>$step){
							if($color == ' class="col"') {
								$color = '';
							} else {
								$color = ' class="col"';
							}

							$str .= '<tr ' . $color . '>';

							if ($bool_modif){
								$str .= '<td>';
								$tab_users = $this->getUsersVis();

								if (($isVisaStep && !is_null($myPosVisa) && $myPosVisa >= $seq) || ($step['process_date'] && $step['process_date'] != ''))
									$disabled = ' disabled ';
								else
									$disabled = '';

								$str .= '<span id="rank_' . $seq . '">'.$actual_vis.' <span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) .'</span> </span>';
								$str .= '<select id="conseiller_'.$seq.'" name="conseiller_'.$seq.'" '.$disabled.'>';
								$str .= '<option value="" >Sélectionnez un utilisateur</option>';
								
								$tab_userentities = $this->getEntityVis();
								/** Order by parent entity **/
								foreach ($tab_userentities as $key => $value) {
									$str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
									$tab_users = $this->getUsersVis($tab_usergroups[$key]['group_id']);
									foreach($tab_users as $user){
										if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
											$selected = " ";
											if ($user['id'] == $step['user_id'])
												$selected = " selected";
											$str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
										}
										
									}
									$str .= '</optgroup>';
								}

								/*$tab_usergroups = $this->getGroupVis();
								foreach ($tab_usergroups as $key => $value) {
									$str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
									$tab_users = $this->getUsersVis($tab_usergroups[$key]['group_id']);
									foreach($tab_users as $user){
										$selected = " ";
										if ($user['id'] == $step['user_id'])
											$selected = " selected";
										$str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';

									}
									$str .= '</optgroup>';
								}
								$str .= '</select>';*/

								$str .= "<span id=\"signatory_" . $seq . "\">";
								if (empty($circuit['sign']['users']) && $seq == count ($circuit['visa']['users'])-1)
									$str .= " <i title=\"Signataire\" style=\"color : #fdd16c\" class=\"fa fa-certificate fa-lg fa-fw\"></i>";
								$str .= "</span></td>";
								$up = ' style="visibility:visible"';
								$displayCB = ' style="visibility:hidden"';
								$checkCB = '';
								if ($isVisaStep && !is_null($myPosVisa) && $myPosVisa >= $seq || $step['process_date'] != '')
									$down = ' style="visibility:hidden"';
								else
									$down = ' style="visibility:visible"';
								if ($isVisaStep && !is_null($myPosVisa) && $myPosVisa >= $seq || $step['process_date'] != '')
									$del = ' style="visibility:hidden"';
								else
									$del = ' style="visibility:visible"';
								if (empty($circuit['sign']['users']) && $seq == count ($circuit['visa']['users'])-1){
									$add = ' style="visibility:visible"';
									$down = ' style="visibility:hidden"';
									$displayCB = ' style="visibility:hidden"';
									$checkCB = ' checked';
								}
								else{
									$add = ' style="visibility:hidden"';
								}
								if ($isVisaStep && $myPosVisa >= $seq || $step['process_date'] != '')
									$displayCB = ' style="visibility:hidden"';

								if ($seq == 0 || ($isVisaStep && $myPosVisa+1 >= $seq) || $circuit['visa']['users'][$seq-1]['process_date'] != ''){
									$up = ' style="visibility:hidden"';
								}

								$str .= '<td><a href="javascript://"  '.$down.' id="down_'.$seq.'" name="down_'.$seq.'" onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
								$str .= '<td><a href="javascript://"   '.$up.' id="up_'.$seq.'" name="up_'.$seq.'" onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
								$str .= '<td id="allez"><a href="javascript://" onclick="delRow(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="suppr_'.$seq.'" name="suppr_'.$seq.'" '.$del.' ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
								$str .= '<td><a href="javascript://" '.$add.'  id="add_'.$seq.'" name="add_'.$seq.'" onclick="addRow(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
								$str .= '<td><input type="text" id="consigne_'.$seq.'" name="consigne_'.$seq.'" value="'.$step['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;" '.$disabled.'/></td>';
								$str .= '<td style="display:none"><input type="hidden" value="'.$step['process_date'].'" id="date_'.$seq.'" name="date_'.$seq.'"/></td>';


								$str .= '<td style="display:none"><input type="checkbox" id="isSign_'.$seq.'" name="isSign_'.$seq.'" '.$displayCB.' '.$checkCB.'/></td>';
								if ($step['process_date'] != '')
									$str .= '<td><i class="fa fa-check fa-2x" title="'._VISED.'"></i></td>';
								else
									$str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_VISA.'"></i></td>';

							}
							else{
								$str .= '<td><span><span class="nbResZero" style="font-weight:bold;opacity:0.5;">' . ($seq + 1) . '</span> </span>'.$step['firstname'].' '.$step['lastname'];
								$str .= '</td>';
								$str .= '<td>'.$step['process_comment'].'</td>';
								if ($step['process_date'] != '')
									$str .= '<td><i class="fa fa-check fa-2x" title="'._VISED.'"></i></td>';
								else
									$str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_VISA.'"></i></td>';
								// else $str .= '<td></td>';
							}
							$str .= '</tr>';
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

						$str .= '<tr ' . $color . '>';
						if ($bool_modif){
							if (($isVisaStep && $myPosVisa >= $seq) || $circuit['sign']['users'][0]['process_date'] != '')
								$disabled = ' disabled ';
							else
								$disabled = '';

							$str .= '<td style="white-space: nowrap;">';
							$tab_users = $this->getUsersVis();
							$str .= '<span id="rank_' . $seq . '">'.$actual_sign.' <span class="nbResZero" style="font-weight:bold;opacity:0.5;">'. ($seq + 1) . '</span> </span>';
							$str .= '<select id="conseiller_'.$seq.'" name="conseiller_'.$seq.'" '.$disabled.'>';
							$str .= '<option value="" >Sélectionnez un utilisateur</option>';
							

							$tab_userentities = $this->getEntityVis();
							/** Order by parent entity **/
							foreach ($tab_userentities as $key => $value) {
								$str .= '<optgroup label="'.$tab_userentities[$key]['entity_id'].'">';
								$tab_users = $this->getUsersVis($tab_usergroups[$key]['group_id']);
								foreach($tab_users as $user){
									if($tab_userentities[$key]['entity_id'] == $user['entity_id']){
										$selected = " ";
										if ($user['id'] == $circuit['sign']['users'][0]['user_id'])
											$selected = " selected";
										$str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
									}
									
								}
								$str .= '</optgroup>';
							}
							/*$tab_usergroups = $this->getGroupVis();
							foreach ($tab_usergroups as $key => $value) {
								$str .= '<optgroup label="'.$tab_usergroups[$key]['group_desc'].'">';
								$tab_users = $this->getUsersVis($tab_usergroups[$key]['group_id']);
								foreach($tab_users as $user){
									$selected = " ";
									if ($user['id'] == $circuit['sign']['users'][0]['user_id'])
										$selected = " selected";
									$str .= '<option value="'.$user['id'].'" '.$selected.'>'.$user['lastname'].', '.$user['firstname'].'</option>';
								}
								$str .= '</optgroup>';
							}*/
							$str .= '</select>';

							$str .= '<span id="signatory_' . $seq . '"><i title="Signataire" style="color : #fdd16c" class="fa fa-certificate fa-lg fa-fw"></i></span></td>';

							if (($isVisaStep && ($myPosVisa+1 == $seq || $myPosVisa == $seq)) || $circuit['sign']['users'][0]['process_date'] != '' || $circuit['visa']['users'][$seq-1]['process_date'] != '')
								$up = ' style="visibility:hidden"';
							else
								$up = ' style="visibility:visible"';
							$down = ' style="visibility:hidden"';

							// if ($isVisaStep && $myPosVisa == $seq) $add = ' style="visibility:hidden"';
							// else $add = ' style="visibility:visible"';
							$add = ' style="visibility:visible"';

							if (($isVisaStep && $myPosVisa == $seq) || $circuit['sign']['users'][0]['process_date'] != '' || $circuit['visa']['users'][$seq-1]['process_date'] != '')
								$del = ' style="visibility:hidden"';
							else
								$del = ' style="visibility:visible"';

							if ($circuit['sign']['users'][0]['process_date'] != '')
                                                                $add = ' style="visibility:hidden"';
                                                        else
                                                                $add = ' style="visibility:visible"';


							if (count ($circuit['visa']['users']) == 0){
								$up 	= 'style="visibility:hidden"';
								$del 	= 'style="visibility:hidden"';
							}
							$displayCB = ' style="visibility:hidden"';
							if ($isVisaStep && $myPosVisa == $seq)
								$displayCB = ' style="visibility:hidden"';

							$str .= '<td><a href="javascript://"  '.$down.' id="down_'.$seq.'" name="down_'.$seq.'" onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex+2,\''.$id_tab.'\')" ><i class="fa fa-arrow-down fa-2x" title="'.DOWN_USER_WORKFLOW.'"></i></a></td>';
							$str .= '<td><a href="javascript://"   '.$up.' id="up_'.$seq.'" name="up_'.$seq.'" onclick="deplacerLigne(this.parentNode.parentNode.rowIndex, this.parentNode.parentNode.rowIndex-1,\''.$id_tab.'\')" ><i class="fa fa-arrow-up fa-2x" title="'.UP_USER_WORKFLOW.'"></i></a></td>';
							$str .= '<td><a href="javascript://" onclick="delRow(this.parentNode.parentNode.rowIndex,\''.$id_tab.'\')" id="suppr_'.$seq.'" name="suppr_'.$seq.'" '.$del.' ><i class="fa fa-user-times fa-2x" title="'.DEL_USER_WORKFLOW.'"></i></a></td>';
							$str .= '<td><a href="javascript://" '.$add.'  id="add_'.$seq.'" name="add_'.$seq.'" onclick="addRow(\''.$id_tab.'\')" ><i class="fa fa-user-plus fa-2x" title="'.ADD_USER_WORKFLOW.'"></i></a></td>';
							$str .= '<td><input type="text" id="consigne_'.$seq.'" name="consigne_'.$seq.'" value="'.$circuit['sign']['users'][0]['process_comment'].'" onmouseover="setTitle(this);" style="width:95%;" '.$disabled.'/></td>';
							$str .= '<td style="display:none"><input type="hidden" id="date_'.$seq.'" name="date_'.$seq.'" value="'.$circuit['sign']['users'][0]['process_date'].'" /></td>';

							$str .= '<td style="display:none"><input type="checkbox" id="isSign_'.$seq.'" name="isSign_'.$seq.'" '.$displayCB.' checked/></td>';
							if ($circuit['sign']['users'][0]['process_date'] != '')
								$str .= '<td><i class="fa fa-check fa-2x" title="'._SIGNED.'"></i></td>';
							else
								$str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_SIGN.'"></i></td>';
						} else {
							$str .= '<td><span><span class="nbResZero" style="font-weight:bold;opacity:0.5;">' . ($seq + 1) .'</span> </span>' . $circuit['sign']['users'][0]['firstname'].' '.$circuit['sign']['users'][0]['lastname'];
							$str .= ' <i title="Signataire" style="color : #fdd16c" class="fa fa-certificate fa-lg fa-fw"></i></td>';
							$str .= '<td>'.$circuit['sign']['users'][0]['process_comment'].'</td>';	
							if ($circuit['sign']['users'][0]['process_date'] != '')
								$str .= '<td><i class="fa fa-check fa-2x" title="'._SIGNED.'"></i></td>';
							else
								$str .= '<td><i class="fa fa-hourglass-half fa-lg" title="'._WAITING_FOR_SIGN.'"></i></td>';
								
						}
						$str .= '</tr>';
					}
				}
			}
		
			$str .= '</tbody>';
			$str .= '</table>';
			if ($bool_modif){
				$str .= '<input type="button" name="send" id="send" value="Sauvegarder" class="button" ';

					if ($fromDetail == "Y") {
						$str .= 'onclick="saveVisaWorkflow(\''.$res_id.'\', \''.$coll_id.'\', \''.$id_tab.'\', \'Y\');" /> ';
					} else {
						$str .= 'onclick="saveVisaWorkflow(\''.$res_id.'\', \''.$coll_id.'\', \''.$id_tab.'\', \'N\');" /> ';
					}


				if ($fromDetail == "Y") {
					$str .= '<input type="button" name="reset" id="reset" value="Reinitialiser" class="button" ';
			
					$str .= 'onclick="if(confirm(\'Voulez-vous réinitialiser le circuit ?\')){resetVisaWorkflow(\''.$res_id.'\', \''.$coll_id.'\', \''.$id_tab.'\', \'Y\');}" /> ';
				}

					$str .= '<input type="button" name="save" id="save" value="Enregistrer comme modèle" class="button" onclick="$(\'modalSaveVisaModel\').style.display = \'block\';" />';
					$str .= '<div id="modalSaveVisaModel" >';
					$str .= '<h3>Sauvegarder le circuit de visa</h3>';
					$str .= '<input type="hidden" value="'.$typeList . '_' . strtoupper(base_convert(date('U'), 10, 36)).'" name="objectId_input" id="objectId_input"/><br/>';
					$str .= '<label for="titleModel">Titre</label> ';
					$str .= '<input type="text" name="titleModel" id="titleModel"/><br/>';
					$str .= '<input type="button" name="saveModel" id="saveModel" value="'._VALIDATE.'" class="button" onclick="saveVisaModel(\''.$id_tab.'\');" /> ';
					$str .= '<input type="button" name="cancelModel" id="cancelModel" value="'._CANCEL.'" class="button" onclick="$(\'modalSaveVisaModel\').style.display = \'none\';" />';
					$str .= '</div>';
			}
			$str .= '</div>';
		}
		return $str;
	}
	
	
	
	/* DOSSIER IMPRESSION */
	public function getJoinedFiles($coll_id, $table, $id, $from_res_attachment=false, $filter_attach_type='all') {
        $joinedFiles = array();
        $db = new Database();
        if ($from_res_attachment === false) {
			require_once('core/class/class_security.php');
			$sec = new security();	
			$versionTable = $sec->retrieve_version_table_from_coll_id(
				$coll_id
			);
			
			//Have version table
			if ($versionTable <> '') {
				$stmt = $db->query("select res_id from " 
							. $versionTable 
							. " where res_id_master = ? and status <> 'DEL' order by res_id desc", 
							array($id));
				$line = $stmt->fetchObject();
				$lastVersion = $line->res_id;
				//Have new version
				if ($lastVersion <> '') {
					$stmt = $db->query(
						"select res_id, description, subject, title, format, filesize, relation, creation_date, typist from "
						. $versionTable . " where res_id = ? and status <> 'DEL'",array($lastVersion)
					);
					// $db->show();
					//Get infos
					while($res = $stmt->fetchObject()) {
						$label = '';
						//Tile, or subject or description
						if (strlen(trim($res->title)) > 0)
							$label = $res->title;
						elseif (strlen(trim($res->subject)) > 0)
							$label = $res->subject;
						elseif (strlen(trim($res->description)) > 0)
							$label = $res->description;
						
						if (isset($res->typist) && $res->typist != '')
							$typist = $res->typist;
						else $typist = '';
						array_push($joinedFiles,
							array('id' => $res->res_id, //ID
								  'label' => $label, //Label
								  'format' => $res->format, //Format 
								  'filesize' => $res->filesize, //Filesize
								  'creation_date' => $res->creation_date, //creation_date
								  'typist' => $typist, //typist
								  'is_version' => true, //Have version bool
								  'version' => $res->relation //Version
								)
						);
					}
				}
			}
			
            $stmt = $db->query(
                "select res_id, description, subject, title, format, filesize, relation, creation_date from "
                . $table . " where res_id = ? and status <> 'DEL'", array($id )
            );
        } else {
			require_once 'modules/attachments/attachments_tables.php';
			if ($filter_attach_type == 'all') {
				$stmt = $db->query(
					"select res_id, description, subject, title, format, filesize, res_id_master, attachment_type, creation_date, typist from " 
					.  RES_ATTACHMENTS_TABLE 
					. " where res_id_master = ? and coll_id = ? and attachment_type <> 'converted_pdf' and attachment_type <> 'print_folder' and status <> 'DEL' order by attachment_type, creation_date",
					array($id, $coll_id)
					);
			} else {
				$stmt = $db->query(
					"select res_id, res_id_version, description, subject, title, format, filesize, res_id_master, attachment_type, creation_date, typist from " 
					. " res_view_attachments "
					. " where res_id_master = ? and coll_id = ? and attachment_type = '" 
					. $filter_attach_type . "' and status not in ('DEL', 'OBS') order by creation_date",
					array($id, $coll_id)
				);
			}
        }
        
        while($res = $stmt->fetchObject()) {
			$pdf_exist = true;
			if ($from_res_attachment){
				require_once 'modules/attachments/class/attachments_controler.php';
				$ac = new attachments_controler();
				if ($res->res_id <> 0) {
					$idFile = $res->res_id;
				} else {
					$idFile = $res->res_id_version;
				}
				$infos_attach = $ac->getAttachmentInfos($idFile);

				$viewLink = $_SESSION['config']['businessappurl']
	        		. 'index.php?display=true&module=attachments&page=view_attachment&res_id_master=' 
	        		. $id . '&id=' . $res->res_id;
				if (!file_exists($infos_attach['pathfile_pdf'])) $pdf_exist = false;
			} else {
				$viewLink = $_SESSION['config']['businessappurl']
					. 'index.php?display=true&dir=indexing_searching&page=view_resource_controler&id=' 
        			. $id;
        		$idFile = $res->res_id;
			}
            $label = '';
            //Tile, or subject or description
            if (strlen(trim($res->title)) > 0)
                $label = $res->title;
            elseif (strlen(trim($res->subject)) > 0)
                $label = $res->subject;
            elseif (strlen(trim($res->description)) > 0)
                $label = $res->description;
			
            if (isset($res->attachment_type) && $res->attachment_type != '')
				$attachment_type = $res->attachment_type;
			else $attachment_type = '';
			
			if (isset($res->typist) && $res->typist != '')
				$typist = $res->typist;
			else $typist = '';
			
			if (
				($from_res_attachment && $pdf_exist)
				|| strtoupper($res->format) == 'PDF'
			) {
				//nothing
			} else {
				$viewLinkHtml = '<a title="' . _PRINT_DOCUMENT 
	              	. '" target="_blank" ' 
					. 'href="' . $viewLink . '">'
					. '<i class="fa fa-print fa-2x" title="' 
					. _PRINT_DOCUMENT . '"></i>'
					. '</a>';
			}
            array_push($joinedFiles,
                array('id' => $idFile, //ID
                      'label' => $label, //Label
                      'format' => $res->format, //Format 
                      'filesize' => $res->filesize, //Filesize
                      'creation_date' => $res->creation_date, //Filesize
                      'attachment_type' => $attachment_type, //attachment_type
                      'typist' => $typist, //attachment_type
                      'is_version' => false, //
					  'pdf_exist' => $pdf_exist,
                      'version' => '',
                      'viewLink' => $viewLinkHtml
                    )
            );
        }
        return $joinedFiles;
    }
	

	public function showPrintFolder($coll_id, $table, $id)
	{
		require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
		. DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
		. 'class_indexing_searching_app.php';
		$is = new indexing_searching_app();
		
		require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
			. DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
			. 'class_users.php';
			
			$users_tools    = new class_users();
		
		require_once 'core/class/class_request.php';
			
		$request = new request();
			
		require_once('core/class/class_security.php');
		$sec = new security();
		$view = $sec->retrieve_view_from_coll_id($coll_id);
		$stmt = $this->query("select subject, contact_society, category_id from $view where res_id = ?",array($id));
		$res = $stmt->fetchObject();
		$str = '';
		$str .= '<div align="left" class="block">';
		$str .= '<div class="error" id="divErrorPrint" name="divErrorPrint" onclick="this.hide();"></div>';
	
		$str .= '<p><b>Requérant</b> : '.$res->contact_society.'</p>';
		$str .= '<p><b>Objet</b> : '.$res->subject.'</p>';
		$str .= '<hr/>';
		$str .= '<form style="width:99%;" name="print_folder_form" id="print_folder_form" action="#" method="post">';
		$str .= '<table style="width:99%;" name="print_folder" id="print_folder" >';
		$str .= '<thead><tr><th style="width:25%;"></th><th style="width:40%;">Titre</th><th style="width:20%;">Rédacteur</th><th style="width:10%;">Date</th><th style="width:5%;"></th></tr></thead>';
		$str .= '<tbody>';
		
		if ($res->category_id == "outgoing"){
			$str .= '<tr><td><h3>+ Courrier sortant</h3></td><td></td><td></td><td></td><td></td></tr>';
			$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'outgoing_mail');
			for($i=0; $i < count($joined_files); $i++) {
				//Get data
				$id_doc = $joined_files[$i]['id']; 
				$description = $joined_files[$i]['label'];
				$format = $joined_files[$i]['format'];
				$contact = $users_tools->get_user($joined_files[$i]['typist']);
                $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
				$creation_date = $request->dateformat($dateFormat[0]);
				if ($joined_files[$i]['pdf_exist'])
					$check = 'class="check" checked="checked"';
				else
					$check = ' disabled title="' . _NO_PDF_FILE . '"';
				//Show data
				$str .= '<tr><td>'  
					 . '</td><td>' . $description 
					 . '</td><td>' . $contact['firstname']
					 . " " . $contact['lastname'] . '</td><td>' 
					 . $creation_date . '</td><td><input id="join_file_' 
					 . $id_doc . '" type="checkbox" name="join_attachment[]"  value="' 
					 . $id_doc . '"  '.$check.'/>' . $joined_files[$i]['viewLink']. '</td></tr>';
			}
		}
		else {
			$str .= '<tr><td><h3>+ Courrier entrant</h3></td><td></td><td></td><td></td><td></td></tr>';
			$joined_files = $this->getJoinedFiles($coll_id, $table, $id, false);
			for($i=0; $i < count($joined_files); $i++) {
				//Get data
				$id_doc = $joined_files[$i]['id']; 
				$description = $joined_files[$i]['label'];
				$format = $joined_files[$i]['format'];

				$contact = $users_tools->get_user($joined_files[$i]['typist']);
                $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
				$creation_date = $request->dateformat($dateFormat[0]);
				
				
				if ($format == 'pdf') $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
				//Show data
				if($joined_files[$i]['is_version'] === true){
					//Version
					$version = ' - '._VERSION.' '.$joined_files[$i]['version'] ;
					$str .= '<tr><td>' 
						. '</td><td>'.$description.$version.'</td><td>'.$contact['firstname']." "
						. $contact['lastname'].'</td><td>'.$creation_date
						. '</td><td><input id="join_file_'.$id_doc.'_V'.$joined_files[$i]['version']
						. '" type="checkbox" name="join_version[]"  value="'.$id_doc
						. '"/>' . $joined_files[$i]['viewLink'] . '</td></tr>';
				} else {
					$str .= '<tr><td></td><td>'.$description.'</td><td>'.$res->contact_society
						. '</td><td>'.$creation_date.'</td><td><input id="join_file_'
						. $id_doc.'" type="checkbox" name="join_file[]" value="'.$id_doc.'"  '.$check
						. '/>' . $joined_files[$i]['viewLink'] . '</td></tr>';
				}
			}
		}
		// Bordereau
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'waybill');
		if (count ($joined_files) > 0)
		$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['waybill'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
            $contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." "
				. $contact['lastname'].'</td><td>'.$creation_date.'</td><td><input id="join_file_'
				. $id_doc.'" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check
				. '/>' . $joined_files[$i]['viewLink'] . '</td></tr>';
        }
		
		// Fiche de circulation
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'record_traffic');
		if (count ($joined_files) > 0)
		$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['record_traffic'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
            $contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." "
				. $contact['lastname'].'</td><td>'.$creation_date.'</td><td><input id="join_file_'
				. $id_doc.'" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check
				. '/>' . $joined_files[$i]['viewLink'] . '</td></tr>';
        }
		
		// PROJETS DE REPONSE
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'response_project');
		if (count ($joined_files) > 0)
			$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['response_project'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." "
				. $contact['lastname'].'</td><td>'.$creation_date.'</td><td><input id="join_file_'.$id_doc
				. '" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check
				. '/>' . $joined_files[$i]['viewLink'] . '</td></tr>';
        }

		// TRANSMISSIONS
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'transmission');
		if (count ($joined_files) > 0)
			$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['transmission'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id'];
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." "
				. $contact['lastname'].'</td><td>'.$creation_date.'</td><td><input id="join_file_'.$id_doc
				. '" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check
				. '/>' . $joined_files[$i]['viewLink'] . '</td></tr>';
        }

		// REPONSES SIGNEES
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'signed_response');
		if (count ($joined_files) > 0)
			$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['signed_response'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist'])
				$check = 'class="check" checked="checked"';
			else
				$check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= "<tr><td></td><td>" . $description . "</td><td>" . $contact["firstname"] . " " . $contact["lastname"] . "</td>";
			$str .= "<td>" . $creation_date . "</td><td>";
			$str .= "<input id='join_file_" . $id_doc . "' type='checkbox' name='join_attachment[]' value='" . $id_doc ."' " . $check . "></input>";
			$str .= $joined_files[$i]['viewLink'] . "</td></tr>";
        }
		
		// AIHP IF NOT CUSTOM USELESS ! 
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'aihp');
		if (count ($joined_files) > 0)
		$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['aihp'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." ".$contact['lastname']
				. '</td><td>'.$creation_date.'</td><td><input id="join_file_'.$id_doc 
				. '" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check 
				. '/>' . $joined_files[$i]['viewLink']. '</td></tr>';
        }

		// SIMPLE ATTACHMENT
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'simple_attachment');
		if (count ($joined_files) > 0)
		$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['simple_attachment'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." ".$contact['lastname']
				. '</td><td>'.$creation_date.'</td><td><input id="join_file_'.$id_doc 
				. '" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check 
				. '/>' . $joined_files[$i]['viewLink']. '</td></tr>';
        }

		// ENVELOPE
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'envelope');
		if (count ($joined_files) > 0)
			$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['envelope'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
			//Get data
			$id_doc = $joined_files[$i]['id'];
			$description = $joined_files[$i]['label'];
			$format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
			$dateFormat = explode(" ",$joined_files[$i]['creation_date']);
			$creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." ".$contact['lastname']
				. '</td><td>'.$creation_date.'</td><td><input id="join_file_'.$id_doc
				. '" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check
				. '/>' . $joined_files[$i]['viewLink']. '</td></tr>';
		}

		// TRANSMISSION
		$joined_files = $this->getJoinedFiles($coll_id, $table, $id, true, 'transfer');
		if (count ($joined_files) > 0)
		$str .= '<tr><td><h3>+ '.$_SESSION['attachment_types']['transfer'].'</h3></td><td></td><td></td><td></td><td></td></tr>';
		for($i=0; $i < count($joined_files); $i++) {
            //Get data
            $id_doc = $joined_files[$i]['id']; 
            $description = $joined_files[$i]['label'];
            $format = $joined_files[$i]['format'];
			$contact = $users_tools->get_user($joined_files[$i]['typist']);
            $dateFormat = explode(" ",$joined_files[$i]['creation_date']);
            $creation_date = $request->dateformat($dateFormat[0]);
			if ($joined_files[$i]['pdf_exist']) $check = 'class="check" checked="checked"'; else $check = ' disabled title="' . _NO_PDF_FILE . '"';
			//Show data
			$str .= '<tr><td></td><td>'.$description.'</td><td>'.$contact['firstname']." ".$contact['lastname']
				. '</td><td>'.$creation_date.'</td><td><input id="join_file_'.$id_doc 
				. '" type="checkbox" name="join_attachment[]"  value="'.$id_doc.'"  '.$check 
				. '/>' . $joined_files[$i]['viewLink']. '</td></tr>';
        }
		
		//Notes         
		$core_tools     = new core_tools();		
		if ($core_tools->is_module_loaded('notes')) {
			require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
			
			$notes_tools    = new notes();
			$user_notes = $notes_tools->getUserNotes($id, $coll_id);
			if (count($user_notes) >0) {
				$str .= '<tr><td><h3>+ '._NOTES.'</h3></td><td></td><td></td><td></td><td></td></tr>';
				for($i=0; $i < count($user_notes); $i++) {
					//Get data
					$idNote = $user_notes[$i]['id']; 
					//$noteShort = $request->cut_string($user_notes[$i]['label'], 50);
       					$noteShort = $request->cut_string(str_replace(array("'", "\r", "\n","\""),array("'", " ", " ", "&quot;"),
                                            $user_notes[$i]['label']), 50);

					$note = $user_notes[$i]['label'];
					$userArray = $users_tools->get_user($user_notes[$i]['author']);
					$date = $request->dateformat($user_notes[$i]['date']);
					
					$check = ' ';
					
					$str .= '<tr><td></td><td>'.$noteShort.'</td><td>'
                                             .$userArray['firstname']." ".$userArray['lastname']
                                             .'</td><td>'.$date.'</td><td><input id="note_'.$idNote.'" type="checkbox" name="notes[]"  value="'
                                             .$idNote.'"  '.$check.'/></td></tr>';
				}
			}
		}
		
		$str .= '</body>';
		$str .= '</table>';
		
		$path_to_script = $_SESSION['config']['businessappurl']
		."index.php?display=true&module=visa&page=printFolder_ajax";
	
		$str .= '<hr/>';
		$str .= '<input style="margin-left:44%" type="button" name="send" id="send" value="Imprimer" class="button" onclick="printFolder(\''.$id.'\', \''.$coll_id.'\', \'print_folder_form\', \''.$path_to_script.'\');" /> ';
		$str .= '</form>';
		$str .= '</div>';
		
		return $str;
	}
	
	
}


abstract class PdfNotes_Abstract extends FPDI
{
	function LoadData($tab, $collId)
	{
		require_once 'modules/notes/notes_tables.php';
		require_once 'core/class/class_request.php';
		$request    = new request();
		// Lecture des lignes du fichier
		$data = array();
		
		$db2 = new Database();
		foreach($tab as $id){
            //Check if ID exists
            $arrayPDO = array();
            if (! empty($collId)) {
                $where = " and coll_id = :collId";
                $arrayPDO = array_merge($arrayPDO, array(":collId" => $collId));
            } 
            $arrayPDO = array_merge($arrayPDO, array(":Id" => $id));
            $stmt2 = $db2->query(
                "SELECT n.identifier, n.date_note, n.user_id, n.note_text, u.lastname, "
                . "u.firstname FROM " . NOTES_TABLE . " n inner join ". USERS_TABLE
                . " u on n.user_id  = u.user_id WHERE n.id = :Id " . $where, $arrayPDO
            );
            
			
            if($stmt2->rowCount() > 0) {
                
                $line = $stmt2->fetchObject();
                $user = $request->show_string($line->lastname . " " . $line->firstname);
                $notes = $line->note_text;
                $userId = $line->user_id;
                $date = explode("-",date("d-m-Y", strtotime($line->date_note)));
                $date = $date[0]."/".$date[1]."/".$date[2]." ".date("H:i", strtotime($line->date_note));
                $identifier = $line->identifier;
			}
			$data[] = array(utf8_decode($user),$date,utf8_decode($notes));
		}
		return $data;
	}

	var $widths;
	var $aligns;

	function SetWidths($w)
	{
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		$this->aligns=$a;
	}

	function Row($data)
	{
		//Calcule la hauteur de la ligne
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		$this->CheckPageBreak($h);
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];	
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			$x=$this->GetX();$y=$this->GetY();
			$this->Rect($x,$y,$w,$h);
			$this->MultiCell($w,5,$data[$i],0,$a);
			$this->SetXY($x+$w,$y);
		}
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		if($this->GetY()+$h>$this->PageBreakTrigger)$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")	$nb--;
		$sep=-1;$i=0;$j=0;$l=0;$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;$sep=-1;$j=$i;$l=0;$nl++;
				continue;
			}
			if($c==' ')	$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)	$i++;
				}
				else
					$i=$sep+1;$sep=-1;$j=$i;$l=0;$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}

abstract class ConcatPdf_Abstract extends FPDI
{
    public $files = array();

    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function concat()
    {
        foreach($this->files AS $file) {
            $pageCount = $this->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                 $tplIdx = $this->ImportPage($pageNo);
                 $s = $this->getTemplatesize($tplIdx);
                 $this->AddPage($s['w'] > $s['h'] ? 'L' : 'P', array($s['w'], $s['h']));
                 $this->useTemplate($tplIdx);
            }
        }
    }
}

/* EXEMPLE TAB VISA_CIRCUIT

Array
(
    [coll_id] => letterbox_coll
    [res_id] => 190
    [difflist_type] => entity_id
    [sign] => Array
        (
            [users] => Array
                (
                    [0] => Array
                        (
                            [user_id] => sgros
                            [lastname] => GROS
                            [firstname] => Sébastien
                            [entity_id] => CHEFCABINET
                            [entity_label] => Chefferie
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                )

        )

    [visa] => Array
        (
            [users] => Array
                (
                    [0] => Array
                        (
                            [user_id] => sbes
                            [lastname] => BES
                            [firstname] => Stéphanie
                            [entity_id] => CHEFCABINET
                            [entity_label] => Chefferie
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                    [1] => Array
                        (
                            [user_id] => fbenrabia
                            [lastname] => BENRABIA
                            [firstname] => Fadela
                            [entity_id] => POLESOCIAL
                            [entity_label] => Pôle social
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                    [2] => Array
                        (
                            [user_id] => bpont
                            [lastname] => PONT
                            [firstname] => Brieuc
                            [entity_id] => POLEAFFAIRESETRANGERES
                            [entity_label] => Pôle affaires étrangères
                            [visible] => Y
                            [viewed] => 0
                            [difflist_type] => VISA_CIRCUIT
                            [process_date] => 
                            [process_comment] => 
                        )

                )

        )

)





<h3>Document</h3><pre>Array
(
    [0] => Array
        (
            [id] => 197
            [label] => 123456
            [format] => pdf
            [filesize] => 46468
            [attachment_type] => 
            [is_version] => 
            [version] => 
        )

)
</pre><h3>Document</h3><pre>Array
(
    [0] => Array
        (
            [id] => 400
            [label] => reponse 1 v5
            [format] => docx
            [filesize] => 36219
            [attachment_type] => response_project
            [is_version] => 
            [version] => 
        )

    [1] => Array
        (
            [id] => 409
            [label] => Nouvelle PJ
            [format] => pdf
            [filesize] => 1204460
            [attachment_type] => simple_attachment
            [is_version] => 
            [version] => 
        )

    [2] => Array
        (
            [id] => 410
            [label] => pj 2
            [format] => pdf
            [filesize] => 361365
            [attachment_type] => simple_attachment
            [is_version] => 
            [version] => 
        )

)

*/
?>
