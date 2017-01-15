<?php


$res_id = $_REQUEST['res_id'];
$coll_id = $_REQUEST['coll_id'];

require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";
include('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'definition_mail_categories.php');
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
$sec =new security();
$core =new core_tools();

$data = get_general_data($coll_id, $res_id, 'minimal');
			


/* Partie centrale*/
$left_html = '';

if ($data['category_id']['value'] != 'outgoing'){
	$left_html .= '<dt id="onglet_entrant">'._INCOMING.'</dt><dd style="overflow-y: hidden;">';
	$left_html .= '<iframe src="'.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=view_resource_controler&visu&id='. $res_id.'&collid='.$coll_id.'" name="viewframevalidDoc" id="viewframevalidDoc"  scrolling="auto" frameborder="0"  style="width:100%;height:100%;" ></iframe></dd>';
	
	$left_html .= '</dd>';
}
	

//CIRCUIT 
$left_html .= '<dt id="onglet_circuit">'._VISA_WORKFLOW.'</dt><dd id="page_circuit" style="overflow-x: hidden;">';
$left_html .= '<h2>'. _VISA_WORKFLOW .'</h2>';
	
$modifVisaWorkflow = false;
if ($core->test_service('config_visa_workflow', 'visa', false)) {
	$modifVisaWorkflow = true;
}
$visa = new visa();

$left_html .= '<div class="error" id="divError" name="divError"></div>';
$left_html .= '<div style="text-align:center;">';
$left_html .= $visa->getList($res_id, $coll_id, $modifVisaWorkflow, 'VISA_CIRCUIT', true);
			
$left_html .= '</div><br>';
$left_html .= '<br/>'; 
	$left_html .= '<br/>';                
	$left_html .= '<span class="diff_list_visa_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor=\'pointer\';" onclick="new Effect.toggle(\'diff_list_visa_history_div\', \'blind\', {delay:0.2});whatIsTheDivStatus(\'diff_list_visa_history_div\', \'divStatus_diff_list_visa_history_div\');return false;">';
		$left_html .= '<span id="divStatus_diff_list_visa_history_div" style="color:#1C99C5;"><<</span>';
		$left_html .= '<b>&nbsp;<small>Historique du circuit de visa</small></b>';
	$left_html .= '</span>';

	$left_html .= '<div id="diff_list_visa_history_div" style="display:none">';

		$s_id = $res_id;
		$return_mode = true;
		$diffListType = 'VISA_CIRCUIT';
		require_once('modules/entities/difflist_visa_history_display.php');
					
$left_html .= '</div>';
$left_html .= '</dd>';

// AVANCEMENT
$left_html .= '<dt id="onglet_avancement">Avancement</dt><dd id="page_avancement" style="overflow-x: hidden;">';
$left_html .= '<h2>'. _WF .'</h2>';
$left_html .= '<iframe src="' . $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=document_workflow_history&id='. $res_id .'&coll_id='. $coll_id.'&load&size=full" name="workflow_history_document" width="100%" height="620px" align="left" scrolling="yes" frameborder="0" id="workflow_history_document"></iframe>';
$left_html .= '<br/>';
$left_html .= '<br/>';

$left_html .= '<span style="cursor: pointer;" onmouseover="this.style.cursor=\'pointer\';" onclick="new Effect.toggle(\'history_document\', \'blind\', {delay:0.2});whatIsTheDivStatus(\'history_document\', \'divStatus_all_history_div\');return false;">';
$left_html .= '<span id="divStatus_all_history_div" style="color:#1C99C5;"><<</span>';
$left_html .= '<b>&nbsp;'. _ALL_HISTORY .'</b>';
$left_html .= '</span>';

$left_html .= '<iframe src="' . $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=document_history&id='. $res_id .'&coll_id='. $coll_id.'&load&size=full" name="history_document" width="100%" height="620px;" align="left" scrolling="yes" frameborder="0" id="history_document" style="display:none;"></iframe>';
$left_html .= '</dd>';

//NOTES	
if ($core->is_module_loaded('notes')){
	require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
						. "class" . DIRECTORY_SEPARATOR
						. "class_modules_tools.php";
	$notes_tools    = new notes();
					
	//Count notes
	$nbr_notes = $notes_tools->countUserNotes($res_id, $coll_id);
	if ($nbr_notes > 0 ) $nbr_notes = ' ('.$nbr_notes.')';  else $nbr_notes = '';
	$left_html .= '<dt id="onglet_notes">'. _NOTES.$nbr_notes .'</dt><dd id="page_notes" style="overflow-x: hidden;"><h2>'. _NOTES .'</h2><iframe name="list_notes_doc" id="list_notes_doc" src="'. $_SESSION['config']['businessappurl'].'index.php?display=true&module=notes&page=notes&identifier='. $res_id .'&origin=document&coll_id='.$coll_id.'&load&size=full" frameborder="0" scrolling="no" width="99%" height="570px"></iframe></dd> ';	
}

/* Partie droite */
$right_html = '';
$tab_path_rep_file = $visa->get_rep_path($res_id, $coll_id);
$cptAttach = count($tab_path_rep_file);
if ($cptAttach < 6) {
	$viewMode = 'extended';
} elseif ($cptAttach < 10) {
	$viewMode = 'small';
} else {
	$viewMode = 'verysmall';
}
for ($i=0; $i<count($tab_path_rep_file);$i++) {
	$num_rep = $i+1;
	if ($viewMode == 'verysmall') {
		$titleRep = $i + 1;
	} elseif ($viewMode == 'small') {
		$titleRep = substr($_SESSION['attachment_types'][$tab_path_rep_file[$i]['attachment_type']],0,10);
	} else {
		if (strlen($tab_path_rep_file[$i]['title']) > 15) $titleRep = substr($_SESSION['attachment_types'][$tab_path_rep_file[$i]['attachment_type']],0,15) . '...';
		else $titleRep = $_SESSION['attachment_types'][$tab_path_rep_file[$i]['attachment_type']];
	}
	if ($tab_path_rep_file[$i]['attachment_type'] == 'signed_response') {
		$titleRep = '<i style="color:#fdd16c" class="fa fa-certificate fa-lg fa-fw"></i>' . $_SESSION['attachment_types'][$tab_path_rep_file[$i]['attachment_type']];
	}
	//if (strlen($tab_path_rep_file[$i]['title']) > 20) $titleRep = substr($tab_path_rep_file[$i]['title'],0,20).'...';
	//else $titleRep = $tab_path_rep_file[$i]['title'];
	$titleRep = str_replace("'", "'",$titleRep);
	$right_html .= '<dt title="'  
				. $tab_path_rep_file[$i]['title'] . '" id="ans_' . $num_rep . '_' 
				. $tab_path_rep_file[$i]['res_id'] 
				. '" onclick="updateFunctionModifRep(\''.$tab_path_rep_file[$i]['res_id'].'\', '.$num_rep.', '.$tab_path_rep_file[$i]['is_version'].');">'
				. $titleRep . '</dt><dd id="content_' . $num_rep .'_'.$tab_path_rep_file[$i]['res_id'].'">';
	$right_html .= '<iframe src="'.$_SESSION['config']['businessappurl'].'index.php?display=true&module=visa&page=view_pdf_attachement&res_id_master='.$res_id.'&id='.$tab_path_rep_file[$i]['res_id'].'" name="viewframevalidRep'.$num_rep.'" id="viewframevalidRep'.$num_rep.'_'.$tab_path_rep_file[$i]['res_id'].'"  scrolling="auto" frameborder="0" style="width:100%;height:100%;" ></iframe>';
	$right_html .= '</dd>';
}
	
		$db = new Database();
		$stmt = $db->query("select res_id from res_view_attachments where status NOT IN ('DEL','OBS','TMP') and attachment_type NOT IN ('converted_pdf','print_folder') and res_id_master = ? and coll_id = ?",array($res_id,$coll_id));
		if ($stmt->rowCount() > 0) {
			$nb_attach = ' (<span id="nb_attach"><b>' . $stmt->rowCount(). '</b></span>)';
		}
	
		$right_html .= '<dt id="onglet_pj" onclick="$(\'cur_idAffich\').value=0;updateFunctionModifRep(0,0,0);">PJ ' . $nb_attach.'</dt><dd id="page_pj">';
		
		if ($core->is_module_loaded('attachments')) {
	        require 'modules/templates/class/templates_controler.php';
	        $templatesControler = new templates_controler();
	        $templates = array();
	        $templates = $templatesControler->getAllTemplatesForProcess($curdest);
	        $_SESSION['destination_entity'] = $curdest;
	        //var_dump($templates);
	        $right_html .= '<div id="list_answers_div" onmouseover="this.style.cursor=\'pointer\';" style="width:100%;">';
            $right_html .= '<div class="block" style="margin-top:-2px;">';
                $right_html .= '<div id="processframe" name="processframe">';
                    $right_html .= '<center><h2>' . _PJ . ', ' . _ATTACHEMENTS . '</h2></center>';
                    
                    $stmt = $db->query("select res_id from ".$_SESSION['tablename']['attach_res_attachments']
                        . " where (status = 'A_TRA' or status = 'TRA' or status = 'SIGN') and attachment_type <> 'converted_pdf' and attachment_type <> 'print_folder' and res_id_master = ? and coll_id = ?", array($res_id,$coll_id));
                    $nb_attach = 0;
                    if ($stmt->rowCount() > 0) {
                        $nb_attach = $stmt->rowCount();
                    }
                    $right_html .= '<div class="ref-unit">';
                    $right_html .= '<center>';
                    if ($core->is_module_loaded('templates')) {
                        $right_html .= '<input type="button" name="attach" id="attach" class="button" value="'
                            . _CREATE_PJ
                            .'" onclick="showAttachmentsForm(\'' . $_SESSION['config']['businessappurl']
                            . 'index.php?display=true&module=attachments&page=attachments_content\')" />';
                    }
                    $right_html .= '</center><iframe name="list_attach" id="list_attach" src="'
                    . $_SESSION['config']['businessappurl']
                    . 'index.php?display=true&module=attachments&page=frame_list_attachments&load&attach_type_exclude=converted_pdf,print_folder&template_selected=documents_list_attachments_simple&resId='.$res_id.'" '
                    . 'frameborder="0" width="100%" scrolling="yes" height="600px"></iframe>';
                    $right_html .= '</div>';
                $right_html .= '</div>';
            $right_html .= '</div>';
            //$right_html .= '<hr />';
        	$right_html .= '</div>';
    	}
	
	
		$right_html .= '</dd>';
					
		

	$valid_but = 'valid_action_form( \'index_file\', \'index.php?display=true&page=manage_action&module=core\', \''.$_REQUEST['action'].'\', \''.$res_id.'\', \'res_letterbox\', \'null\', \''.$coll_id.'\', \'page\');';

//echo "{status : 1,avancement:'".$avancement_html."',circuit:'".$circuit_html."',notes_dt:'".$notes_html_dt."',notes_dd:'".$notes_html_dd."'}";
echo "{status : 1,left_html:'".addslashes($left_html)."',right_html:'".addslashes($right_html)."',valid_button:'".addslashes($valid_but)."',id_rep:'".$tab_path_rep_file[0]['res_id']."',is_vers_rep:'".$tab_path_rep_file[0]['is_version']."'}";
exit();
