<?php
/*
*   Copyright 2015 Maarch
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

$core = new core_tools();
//here we loading the lang vars
$core->load_lang();
$core->test_service('manage_attachments', 'attachments');

require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
$list       = new lists(); 

if (empty($_SESSION['collection_id_choice'])) {
    $_SESSION['collection_id_choice'] = $_SESSION['user']['collections'][0];
}

if (isset($_REQUEST['resId']) && $_REQUEST['resId'] <> '') {
   $resId = $_REQUEST['resId'];
   $_SESSION['doc_id'] = $resId;
} else {
    $resId = $_SESSION['doc_id'];
}

//test si le paramètre attach_type existe. Possible de mettre plusieurs types separes par une virgule
if (isset($_REQUEST['attach_type']) && $_REQUEST['attach_type'] <> ''){
	$table_type = explode(",", $_REQUEST['attach_type']);
	$whereAttach = " and (";
	foreach ($table_type as $value) {
		$whereAttach .= "attachment_type = '".$value."' or ";
	}
	$whereAttach = substr($whereAttach, 0, -4) . ") ";
}
if (isset($_REQUEST['attach_type_exclude']) && $_REQUEST['attach_type_exclude'] <> ''){
	$table_type = explode(",", $_REQUEST['attach_type_exclude']);
	$whereAttach = " and (";
	foreach ($table_type as $value) {
		$whereAttach .= "attachment_type <> '".$value."' and ";
	}
	$whereAttach = substr($whereAttach, 0, -4) . ") ";
}

$viewOnly = false;
if (isset($_REQUEST['view_only'])) {
    $viewOnly = true;
}
require_once 'core/class/class_request.php';
require_once 'apps/' . $_SESSION['config']['app_id']
    . '/class/class_list_show.php';
require_once 'modules/attachments/attachments_tables.php';
$func = new functions();

$db = new Database();

//Templates
if(isset($_REQUEST['template_selected'])){
	$template_select = $_REQUEST['template_selected'];
}else{
	$template_select = 'documents_list_attachments';
}
$defaultTemplate = $template_select;
$selectedTemplate = $list->getTemplate();
if  (empty($selectedTemplate)) {
    if (!empty($defaultTemplate)) {
        $list->setTemplate($defaultTemplate);
        $selectedTemplate = $list->getTemplate();
    }
}
$template_list = array();

array_push($template_list, $template_select);

$select['res_view_attachments'] = array();

// Important de laisser cet ordre : 'res_id', 'res_id_version', 'relation', 'status'
array_push(
    $select['res_view_attachments'], 'res_id', 'res_id_version', 'relation', 'status', 'identifier', 'attachment_type', 'title', 'dest_user', 'dest_contact_id', 'creation_date', 'typist', 'doc_date', 'updated_by', 'validation_date', 'format');

$where = " (res_id_master = ? and coll_id = ? and status <> 'DEL' and status <> 'OBS' and (status <> 'TMP' or (typist = ? and status = 'TMP')))";
$arrayPDO = array($resId, $_SESSION['collection_id_choice'], $_SESSION['user']['UserId']);	   
//Filtre sur le type
if (isset($whereAttach) && $whereAttach <> '') $where .= $whereAttach;

//Order
    $order = $order_field = '';
    $order = $list->getOrder();
    $order_field = $list->getOrderField();
    if (!empty($order_field) && !empty($order)) 
        $orderstr = "order by ".$order_field." ".$order;
    else  {
        $list->setOrder();
        $list->setOrderField('identifier');
        $orderstr = "order by attachment_type asc, identifier desc";
    }

$parameters = '';
//Extra parameters
if (isset($_REQUEST['size']) && !empty($_REQUEST['size'])) $parameters .= '&size='.$_REQUEST['size'];
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) $parameters .= '&order='.$_REQUEST['order'];
if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters .= '&order_field='.$_REQUEST['order_field'];
if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];
if (isset($_REQUEST['template_selected']) && !empty($_REQUEST['template_selected'])) $parameters .= '&template_selected='.$_REQUEST['template_selected'];

//test si le paramètre attach_type existe
if (isset($_REQUEST['attach_type']) && $_REQUEST['attach_type'] <> '')	$parameters .= "&attach_type=".$_REQUEST['attach_type'];
if (isset($_REQUEST['attach_type_exclude']) && $_REQUEST['attach_type_exclude'] <> '')	$parameters .= "&attach_type_exclude=".$_REQUEST['attach_type_exclude'];
if (isset($_REQUEST['fromDetail']) && $_REQUEST['fromDetail'] <> '') $parameters .= "&fromDetail=".$_REQUEST['fromDetail'];

if (isset($_REQUEST['load'])) {
    $core->load_lang();
    $core->load_html();
    $core->load_header('', true, false);
    ?><body><?php
    $core->load_js();

    //Load list
    $target = $_SESSION['config']['businessappurl']
        .'index.php?module=attachments&page=frame_list_attachments'.$parameters;
    
    $listContent = $list->loadList($target);
    echo $listContent;

    ?><div id="container" style="width:100%;min-height:0px;height:0px;"></div></body></html><?php
} else {

	$request = new request;
	$attachArr = $request->PDOselect(
	    $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype'], '500'
	);
	 // $request->show();

	foreach ($attachArr as $key => $value) {
		if ($attachArr[$key][0]["value"] == "0") {
			array_shift($attachArr[$key]);
			$attachArr[$key][0]["column"] = "res_id";
		} else {
			unset($attachArr[$key][1]);
			$attachArr[$key] = array_values($attachArr[$key]);
		}
	}

	for ($i = 0; $i < count($attachArr); $i ++) {
	    //$modifyValue = false;
	    if ($attachArr[$i][2]['value'] == 'TMP'){
	    	$is_tmp=true;
	    }else{
			$is_tmp=false;
	    }
	    for ($j = 0; $j < count($attachArr[$i]); $j ++) {
	        foreach (array_keys($attachArr[$i][$j]) as $value) {
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'res_id') {
	                $attachArr[$i][$j]['res_id'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _ID;
	                $attachArr[$i][$j]['size'] = '18';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = false;
	                $attachArr[$i][$j]['order'] = 'res_id';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'relation') {
	                $attachArr[$i][$j]['relation'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _VERSION;
	                $attachArr[$i][$j]['size'] = '18';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	                $attachArr[$i][$j]['order'] = 'relation';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'identifier') {
	                $attachArr[$i][$j]['identifier'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _CHRONO_NUMBER;
	                $attachArr[$i][$j]['size'] = '18';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = false;
	                $attachArr[$i][$j]['order'] = 'identifier';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'attachment_type') {
	                $attachArr[$i][$j]['value'] = $_SESSION['attachment_types'][$attachArr[$i][$j]['value']];
	                $attachArr[$i][$j]['attachment_type'] = $_SESSION['attachment_types'][$attachArr[$i][$j]['value']];
	                $attachArr[$i][$j]['label'] = _ATTACHMENT_TYPES;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'dest_contact_id') {
	                if ($attachArr[$i][$j]['value'] <> 0 && $attachArr[$i][$j]['value'] <> '' ) {
	                	$stmt = $db->query("SELECT firstname, lastname, society FROM contacts_v2 WHERE contact_id = ? ",array($attachArr[$i][$j]['value']));
	                    $res = $stmt->fetchObject();
	                    $attachArr[$i][$j]['value'] = functions::protect_string_db($res->firstname) .' '. functions::protect_string_db($res->lastname) . ' '. functions::protect_string_db($res->society);
	                }
	                    $attachArr[$i][$j]['dest_contact_id'] = $attachArr[$i][$j]['value'];
	                    $attachArr[$i][$j]['label'] = _DEST;
	                    $attachArr[$i][$j]['size'] = '30';
	                    $attachArr[$i][$j]['label_align'] = 'left';
	                    $attachArr[$i][$j]['align'] = 'left';
	                    $attachArr[$i][$j]['valign'] = 'bottom';
	                    $attachArr[$i][$j]['show'] = true;
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'dest_user') {
	                if ($attachArr[$i][$j]['value'] != '') {
	                	$stmt = $db->query("SELECT firstname, lastname FROM users WHERE user_id = ? ", [$attachArr[$i][$j]['value']]);
	                    $res = $stmt->fetchObject();
	                    $attachArr[$i][$j]['value'] = functions::protect_string_db($res->firstname) .' '. functions::protect_string_db($res->lastname);
	                }
					$attachArr[$i][$j]['dest_user'] = $attachArr[$i][$j]['value'];
					$attachArr[$i][$j]['label'] = _DEST;
					$attachArr[$i][$j]['size'] = '30';
					$attachArr[$i][$j]['label_align'] = 'left';
					$attachArr[$i][$j]['align'] = 'left';
					$attachArr[$i][$j]['valign'] = 'bottom';
					$attachArr[$i][$j]['show'] = true;
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'updated_by') {
	                $stmt = $db->query("SELECT lastname FROM users WHERE user_id = ?",array($attachArr[$i][$j]['value']));
	                $res = $stmt->fetchObject();
	                $attachArr[$i][$j]['value'] = functions::protect_string_db($res->lastname);
	                $attachArr[$i][$j]['updated_by'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _BBY;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	                $attachArr[$i][$j]['order'] = 'updated_by';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'typist') {
	                $stmt = $db->query("SELECT lastname FROM users WHERE user_id = ?",array($attachArr[$i][$j]['value']));
	                $res = $stmt->fetchObject();
	                $attachArr[$i][$j]['typist_id'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['value'] = functions::protect_string_db($res->lastname);
	                $attachArr[$i][$j]['typist'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _BBY;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	                $attachArr[$i][$j]['order'] = 'typist';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'title') {
	                $attachArr[$i][$j]['title'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _OBJECT;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	                $attachArr[$i][$j]['order'] = 'title';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'creation_date') {
	                $attachArr[$i][$j]['value'] = $request->format_date_db(
	                    $attachArr[$i][$j]['value'], true
	                );
	                $attachArr[$i][$j]['creation_date'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _CREATED;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'doc_date') {
	                $attachArr[$i][$j]['value'] = $request->format_date_db(
	                    $attachArr[$i][$j]['value'], true
	                );
	                $attachArr[$i][$j]['doc_date'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _UPDATED_DATE;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'validation_date') {
	                $attachArr[$i][$j]['value'] = $request->format_date_db(
	                    $attachArr[$i][$j]['value'], true
	                );
	                $attachArr[$i][$j]['validation_date'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _BACK_DATE;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'status') {
	                $stmt = $db->query("SELECT label_status FROM status WHERE id = ?", array($attachArr[$i][$j]['value']));
	                $res = $stmt->fetchObject();
	                $attachArr[$i][$j]['value_bis'] = $attachArr[$i][$j]['value'];
	                if($is_tmp == true){
	                	$attachArr[$i][$j]['value'] = '<span style="color:green;">'.functions::protect_string_db($res->label_status).'</span>';
	                }else{
	                	$attachArr[$i][$j]['value'] = functions::protect_string_db($res->label_status);
	                }
	                $attachArr[$i][$j]['status'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _STATUS;
	                $attachArr[$i][$j]['size'] = '30';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	                $attachArr[$i][$j]['order'] = 'status';
	            }
	            if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j][$value] == 'format') {
	                $attachArr[$i][$j]['value'] = $request->show_string(
	                    $attachArr[$i][$j]['value']
	                );
	                $attachArr[$i][$j]['format'] = $attachArr[$i][$j]['value'];
	                $attachArr[$i][$j]['label'] = _FORMAT;
	                $attachArr[$i][$j]['size'] = '5';
	                $attachArr[$i][$j]['label_align'] = 'left';
	                $attachArr[$i][$j]['align'] = 'left';
	                $attachArr[$i][$j]['valign'] = 'bottom';
	                $attachArr[$i][$j]['show'] = true;
	                $attachArr[$i][$j]['order'] = true;
	                $attachArr[$i][$j]['order'] = 'format';

	                if (isset($attachArr[$i][$j][$value]) && $attachArr[$i][$j]['value'] == 'maarch') {
	                    //$modifyValue = true;
	                }
	            }
	        }
	    }
		if (isset($_REQUEST['fromDetail']) && $_REQUEST['fromDetail'] <> ''){
			array_push($attachArr[$i], array("fromDetail" => $_REQUEST['fromDetail'], "show" => false));
		}
	}

    //List
    $listKey = 'res_id';                                                                    //Clé de la liste
    $paramsTab = array();                                                               //Initialiser le tableau de paramètres
    $paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
    $paramsTab['pageTitle'] ='';                                                        //Titre de la page
    $paramsTab['bool_bigPageTitle'] = false;                                            //Affichage du titre en grand
    $paramsTab['bool_showIconDocument'] =  true;                                        //Affichage de l'icone du document
    $paramsTab['listHeight'] = '100%';                                                 //Hauteur de la liste
    $paramsTab['listCss'] = 'listing largerList spec';                                                       //CSS
    $paramsTab['urlParameters'] =  'display=true'.$parameters;            //Parametres supplémentaires
    $paramsTab['defaultTemplate'] = $defaultTemplate;                                   //Default template
    if ($useTemplate && count($template_list) >0 ) {                                    //Templates
        $paramsTab['templates'] = array();
        $paramsTab['templates'] = $template_list;
    }
    $paramsTab['bool_showTemplateDefaultList'] = true;                                  //Default list (no template)
    $paramsTab['viewDocumentLink'] = $_SESSION['config']['businessappurl'].'index.php?display=true&module=attachments&page=view_attachment&res_id_master='.$_SESSION['doc_id'];

	$content = $list->showList($attachArr, $paramsTab, $listKey);
	$status = 0;

	echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
}