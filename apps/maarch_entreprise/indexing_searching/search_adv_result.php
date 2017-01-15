<?php

/*
*   Copyright 2008 - 2015 Maarch
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
* @brief  Advanced search form management
*
* @file search_adv_result.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

require_once('core/class/class_request.php');
require_once('core/class/class_security.php');
require_once('apps/' . $_SESSION['config']['app_id'] 
    . '/class/class_indexing_searching_app.php'
);
require_once('apps/' . $_SESSION['config']['app_id'] . '/class/class_types.php');
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$is = new indexing_searching_app();
$func = new functions();
$req = new request();
$type = new types();
$fields = "";
$orderby = "";

$baskets_clause = '';
$coll_id = 'letterbox_coll';
$indexes = $type->get_all_indexes($coll_id);
//$func->show_array($indexes);
$_SESSION['error_search'] = '';
$_SESSION['searching']['comp_query'] = '';
$_SESSION['save_list']['fromDetail'] = "false";
$_SESSION['fullTextAttachments'] = [];


// define the row of the start
if (isset($_REQUEST['start'])) {
    $start = $_REQUEST['start'];
} else {
    $start = 0;
}

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'frame') {
    $mode = 'frame';
} elseif (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'popup') {
    $mode = 'popup';
} else {
    $mode = 'normal';
    $core_tools->test_service('adv_search_mlb', 'apps');
}
$where_request = "";
$arrayPDO = array();
$case_view = false;
 $_ENV['date_pattern'] = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
$json_txt = '{';

/**
 * Array $_REQUEST['meta'] exemple
(
    [0] => copies#copies_false,copies_true#radio
    [1] => objet#objet#input_text
    [2] => numged#numged#input_text
    [3] => multifield#multifield#input_text
    [4] => category#category#select_simple
    [5] => doc_date#doc_date_from,doc_date_to#date_range
)
**/
//$func->show_array($_REQUEST['meta']);exit;
if (count($_REQUEST['meta']) > 0) {
    //Verif for parms sended by url
    if ($_GET['meta']) {
        for ($m=0; $m<count($_REQUEST['meta']);$m++) {
            if (strstr($_REQUEST['meta'][$m], '||') == true) {
                $_REQUEST['meta'][$m] = str_replace('||', '#', $_REQUEST['meta'][$m]);
            }
        }
    }
    $opt_indexes = array();
    $_SESSION['meta_search'] = $_REQUEST['meta'];
    for ($i=0;$i<count($_REQUEST['meta']);$i++) {
        $tab = explode('#', $_REQUEST['meta'][$i]);
        if ($tab[0] == 'welcome') {
            $tab[0] = 'multifield';
            $tab[2] = 'input_text';
        }
        $id_val = $tab[0];
        $json_txt .= "'".$tab[0]."' : { 'type' : '".$tab[2]."', 'fields' : {";
        $tab_id_fields = explode(',', $tab[1]);
        //$func->show_array($tab_id_fields);
        for ($j=0; $j<count($tab_id_fields);$j++) {
            // ENTITIES
            if ($tab_id_fields[$j] == 'services_chosen' && isset($_REQUEST['services_chosen'])) {
                $json_txt .= " 'services_chosen' : [";

                for ($get_i = 0; $get_i <count($_REQUEST['services_chosen']); $get_i++) {

                    $json_txt .= "'".$_REQUEST['services_chosen'][$get_i]."',";
                }
                $json_txt = substr($json_txt, 0, -1);

                $where_request .= " destination IN  (:serviceChosen) ";
                $where_request .=" and  ";
                $arrayPDO = array_merge($arrayPDO, array(":serviceChosen" => $_REQUEST['services_chosen']));
                $json_txt .= '],';
            } elseif ($tab_id_fields[$j] == 'multifield' && !empty($_REQUEST['multifield'])) {
                // MULTIFIELD : subject, title, doc_custom_t1, process notes
                $json_txt .= "'multifield' : ['".addslashes(trim($_REQUEST['multifield']))."'],";
                $where_request .= "(lower(translate(subject,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:multifield) "
                    ."or (lower(translate(alt_identifier,'/','')) like lower(:multifield) OR lower(alt_identifier) like lower(:multifield)) "
                    ."or lower(title) LIKE lower(:multifield) "
                    ."or lower(doc_custom_t1) LIKE lower(:multifield) "
                    ."or res_id in (select identifier from notes where lower(translate(note_text,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:multifield)) "                    
                    ."or res_id in (select res_id_master from res_view_attachments where lower(translate(identifier,'/','')) like lower(:multifield) OR lower(identifier) like lower(:multifield))) ";       
                if (ctype_digit($_REQUEST['multifield']))
                {
                    $where_request .= "or res_id = :multifield2 ";
                    $arrayPDO = array_merge($arrayPDO, array(":multifield2" => $_REQUEST['multifield'])); 
                }
                
                $arrayPDO = array_merge($arrayPDO, array(":multifield" => "%".$_REQUEST['multifield']."%")); 
                
                $where_request .=" and  ";
            } elseif ($tab_id_fields[$j] == 'numcase' && !empty($_REQUEST['numcase'])) {
                // CASE_NUMBER
                $json_txt .= "'numcase' : ['".addslashes(trim($_REQUEST['numcase']))."'],";
                //$where_request .= "res_view_letterbox.case_id = ".$func->wash($_REQUEST['numcase'], "num", _N_CASE,"no")." and ";
                $where_request .= " ".$_SESSION['collections'][0]['view'].".case_id = :numCase and ";
                $arrayPDO = array_merge($arrayPDO, array(":numCase" => $_REQUEST['numcase']));
                $case_view=true;

                if (!is_numeric($_REQUEST['numcase'])) {
                    $_SESSION['error_search'] = _CASE_NUMBER_ERROR;
                }

            } elseif ($tab_id_fields[$j] == 'labelcase' && !empty($_REQUEST['labelcase'])) {
                // CASE_LABEL
                $json_txt .= "'labelcase' : ['".addslashes(trim($_REQUEST['labelcase']))."'],";
                //$where_request .= "res_view_letterbox.case_id = ".$func->wash($_REQUEST['numcase'], "num", _N_CASE,"no")." and ";
                $where_request .= " lower(translate(".$_SESSION['collections'][0]['view'].".case_label,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:labelCase) and ";
                $arrayPDO = array_merge($arrayPDO, array(":labelCase" => "%".$func->wash($_REQUEST['labelcase'], "no", _CASE_LABEL,"no")."%"));
                $case_view=true;
            } elseif ($tab_id_fields[$j] == 'descriptioncase' && !empty($_REQUEST['descriptioncase'])) {
                // CASE_DESCRIPTION
                $json_txt .= "'descriptioncase' : ['".addslashes(trim($_REQUEST['descriptioncase']))."'],";
                //$where_request .= "res_view_letterbox.case_id = ".$func->wash($_REQUEST['numcase'], "num", _N_CASE,"no")." and ";
                $where_request .= " lower(translate(".$_SESSION['collections'][0]['view'].".case_description,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:descriptionCase) and ";
                $arrayPDO = array_merge($arrayPDO, array(":descriptionCase" => "%".$func->wash($_REQUEST['descriptioncase'], "no", _CASE_DESCRIPTION,"no")."%"));
                $case_view=true;
            } elseif ($tab_id_fields[$j] == 'chrono' && !empty($_REQUEST['chrono'])) {
                $json_txt .= " 'chrono' : ['".addslashes(trim($_REQUEST['chrono']))."'],";
                $chrono = $func->wash($_REQUEST['chrono'],"no",_CHRONO_NUMBER,"no");
                $where_request .= " (lower(alt_identifier) like lower(:chrono) or (res_id in (SELECT res_id_master FROM res_view_attachments WHERE coll_id = 'letterbox_coll' AND lower(identifier) like lower(:chrono) )))";
                $arrayPDO = array_merge($arrayPDO, array(":chrono" => "%".$chrono."%"));
                $where_request .=" and  ";
            } 
            // PRIORITY
            elseif ($tab_id_fields[$j] == 'priority' && is_numeric($_REQUEST['priority']) && $_REQUEST['priority']>=0)
            {
                $json_txt .= " 'priority' : ['".addslashes(trim($_REQUEST['priority']))."'],";
                $prio = $func->wash($_REQUEST['priority'],"num",_THE_PRIORITY,"no");
                $where_request .= " priority = :priority ";
                $arrayPDO = array_merge($arrayPDO, array(":priority" => $prio));

                $where_request .=" and  ";
            }
            // SIGNATORY GROUP
            elseif ($tab_id_fields[$j] == 'signatory_group' && !empty($_REQUEST['signatory_group']))
            {
                $json_txt .= " 'signatory_group' : ['".addslashes(trim($_REQUEST['signatory_group']))."'],";
                $where_request .= " (res_id in (select res_id from listinstance where item_id in (select user_id from usergroup_content where group_id = :signatoryGroup) "
                        ."and coll_id = '" . $coll_id . "' and item_mode = 'sign' and difflist_type = 'VISA_CIRCUIT')) ";
                $arrayPDO = array_merge($arrayPDO, array(":signatoryGroup" => $_REQUEST['signatory_group']));
                $where_request .=" and  ";
            }

            // TYPE D'ATTACHEMENT
            elseif ($tab_id_fields[$j] == 'attachment_types' && !empty($_REQUEST['attachment_types']))
            {
                $json_txt .= " 'attachment_types' : ['".addslashes(trim($_REQUEST['attachment_types']))."'],";
                $where_request .= " (res_id in (SELECT res_id_master FROM res_view_attachments WHERE attachment_type = :attachmentTypes) )";
                $arrayPDO = array_merge($arrayPDO, array(":attachmentTypes" => $_REQUEST['attachment_types']));
                $where_request .=" and  ";
            }

            // PROCESS NOTES
            elseif ($tab_id_fields[$j] == 'process_notes' && !empty($_REQUEST['process_notes']))
            {
                $json_txt .= " 'process_notes' : ['".addslashes(trim($_REQUEST['process_notes']))."'],";
                $s_process_notes = $func->wash($_REQUEST['process_notes'], "no", _PROCESS_NOTES,"no");
                $where_request .= " (lower(process_notes) LIKE lower(:processNotes) ) and ";
                $arrayPDO = array_merge($arrayPDO, array(":processNotes" => "%".$s_process_notes."%"));
            }
            // IDENTIFIER
            elseif ($tab_id_fields[$j] == 'identifier' && !empty($_REQUEST['identifier'])) {
                $json_txt .= "'identifier' : ['".addslashes(trim($_REQUEST['identifier']))."'],";
                $where_request .=" (lower(identifier) LIKE lower(:identifier) ) and ";
                $arrayPDO = array_merge($arrayPDO, array(":identifier" => "%".$_REQUEST['identifier']."%"));
            }
            // DESCRIPTION
            elseif ($tab_id_fields[$j] == 'description' && !empty($_REQUEST['description'])) {
                $json_txt .= "'description' : ['".addslashes(trim($_REQUEST['description']))."'],";
                $where_request .=" (lower(description) LIKE lower(:description) ) and ";
                $arrayPDO = array_merge($arrayPDO, array(":description" => "%".$_REQUEST['description']."%"));
            }
            // REFERENCE NUMBER
            elseif ($tab_id_fields[$j] == 'reference_number' && !empty($_REQUEST['reference_number'])) {
                $json_txt .= "'reference_number' : ['".addslashes(trim($_REQUEST['reference_number']))."'],";
                $where_request .=" (lower(reference_number) LIKE lower(:referenceNumber) ) and ";
                $arrayPDO = array_merge($arrayPDO, array(":referenceNumber" => "%".$_REQUEST['reference_number']."%"));
            }
            // NOTES
            elseif ($tab_id_fields[$j] == 'doc_notes' && !empty($_REQUEST['doc_notes']))
            {
                $json_txt .= " 'doc_notes' : ['".addslashes(trim($_REQUEST['doc_notes']))."'],";
                $s_doc_notes = $func->wash($_REQUEST['doc_notes'], "no", _NOTES,"no");
                $where_request .= " res_id in(select identifier from ".$_SESSION['tablename']['not_notes']." where lower(note_text) LIKE lower(:referenceNumber)) and ";
                $arrayPDO = array_merge($arrayPDO, array(":referenceNumber" => "%".$s_doc_notes."%"));
            }
            // CONTACT TYPE
            elseif ($tab_id_fields[$j] == 'contact_type' && !empty($_REQUEST['contact_type']))
            {
                $json_txt .= " 'contact_type' : ['".addslashes(trim($_REQUEST['contact_type']))."'],";
                $where_request .= " (res_id in (select res_id from contacts_res where contact_id in(select cast (contact_id as varchar) from view_contacts where contact_type = :contactType)) or ";
                $where_request .= " (contact_id in(select contact_id from view_contacts where contact_type = :contactType))) and ";
                $arrayPDO = array_merge($arrayPDO, array(":contactType" => $_REQUEST['contact_type']));
            }
            // FOLDER : MARKET
            elseif ($tab_id_fields[$j] == 'market' && !empty($_REQUEST['market']))
            {
                $json_txt .= " 'market' : ['".addslashes(trim($_REQUEST['market']))."'],";
                $market = $func->wash($_REQUEST['market'], "no", _MARKET,"no");
                $where_request .= " (lower(folder_name) like lower(:referenceNumber) or folder_id like :referenceNumber ) and ";
                $arrayPDO = array_merge($arrayPDO, array(":referenceNumber" => "%".$market."%"));
            }
            // FOLDER : PROJECT
            elseif ($tab_id_fields[$j] == 'project' && !empty($_REQUEST['project']))
            {
                $json_txt .= " 'project' : ['".addslashes(trim($_REQUEST['project']))."'],";
                $project = $func->wash($_REQUEST['project'], "no", _MARKET,"no");
                $where_request .= " (lower(folder_name) like lower(:project) or folder_id like :project "
                    ."or folders_system_id in (select parent_id from ".$_SESSION['tablename']['fold_folders']." where lower(folder_name) like lower(:project) or folder_id like :project)) and ";
                $arrayPDO = array_merge($arrayPDO, array(":project" => "%".$project."%"));
            }

            elseif ($tab_id_fields[$j] == 'folder_name' && !empty($_REQUEST['folder_name']))
            {
                $json_txt .= " 'folder_name' : ['".addslashes(trim($_REQUEST['folder_name']))."'],";
                $folder_name = $func->wash($_REQUEST['folder_name'], "no", _FOLDER_NAME,"no");
                 $where_request .= " (lower(folder_name) like lower(:folderName) and ";
                $arrayPDO = array_merge($arrayPDO, array(":folderName" => "%".$folder_name."%"));
            }
            // GED NUM
            elseif ($tab_id_fields[$j] == 'numged' && !empty($_REQUEST['numged']))
            {
                $json_txt .= " 'numged' : ['".addslashes(trim($_REQUEST['numged']))."'],";
                require_once('core/class/class_security.php');
                $sec = new security();
                $view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice']);
                if ($view <> '') {
                    $view .= '.';
                }
                $where_request .= $view . "res_id = :numGed and ";
                $arrayPDO = array_merge($arrayPDO, array(":numGed" => $_REQUEST['numged']));

                if (!is_numeric($_REQUEST['numged'])) {
                    $_SESSION['error_search'] = _NUMERO_GED;
                }
            }
            // DEST_USER
            elseif ($tab_id_fields[$j] == 'destinataire_chosen' && !empty($_REQUEST['destinataire_chosen']))
            {
                $json_txt .= " 'destinataire_chosen' : [";

                for ($get_i = 0; $get_i <count($_REQUEST['destinataire_chosen']); $get_i++)
                {
                    $json_txt .= "'".$_REQUEST['destinataire_chosen'][$get_i]."',";
                }

                $json_txt = substr($json_txt, 0, -1);

                $where_request .= " (dest_user IN  (:destinataireChosen) or res_id in (select res_id from ".$_SESSION['tablename']['ent_listinstance']." where item_id in (:destinataireChosen) and item_mode = 'dest')) ";
                $where_request .=" and  ";
                $arrayPDO = array_merge($arrayPDO, array(":destinataireChosen" => $_REQUEST['destinataire_chosen']));
                $json_txt .= '],';
            }
            // SUBJECT
            elseif ($tab_id_fields[$j] == 'subject' && !empty($_REQUEST['subject']))
            {
                //var_dump($_REQUEST['subject']);exit();
                $_REQUEST['subject'] = $func->normalize($_REQUEST['subject']);
                $json_txt .= " 'subject' : ['".addslashes(trim($_REQUEST['subject']))."'],";
                $where_request .= " (lower(translate(subject,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:subject) "
                    ."or (res_id in (SELECT res_id_master FROM res_view_attachments WHERE coll_id = 'letterbox_coll' AND lower(translate(title,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr'))  like lower(:subject) ))) and ";
                $arrayPDO = array_merge($arrayPDO, array(":subject" => "%".$_REQUEST['subject']."%"));
            } elseif ($tab_id_fields[$j] == 'fulltext' && !empty($_REQUEST['fulltext'])
            ) {

                $query_fulltext = explode(" ", trim($_REQUEST['fulltext']));
                $error_fulltext = false;

                foreach ($query_fulltext as $value) {
                    if (strpos($value, "*") !== false && 
                        (strlen(substr($value, 0, strpos($value, "*"))) < 3 || preg_match("([,':!+])", $value) === 1 )
                        ) {
                        $error_fulltext = true;
                        break;
                    }
                }

                if ($error_fulltext == true ) {
                    $_SESSION['error_search'] = _FULLTEXT_ERROR;
                } else {
                    // FULLTEXT
                    $fulltext_request = $func->normalize($_REQUEST['fulltext']);
                    $json_txt .= " 'fulltext' : ['" 
                        . addslashes(trim($_REQUEST['fulltext'])) . "'],";
                    set_include_path('apps' . DIRECTORY_SEPARATOR 
                        . $_SESSION['config']['app_id'] 
                        . DIRECTORY_SEPARATOR . 'tools' 
                        . DIRECTORY_SEPARATOR . PATH_SEPARATOR . get_include_path()
                    );
                    require_once('Zend/Search/Lucene.php');
                    Zend_Search_Lucene_Analysis_Analyzer::setDefault(
                        new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive() // we need utf8 for accents
                    );
                    Zend_Search_Lucene_Search_QueryParser::setDefaultOperator(Zend_Search_Lucene_Search_QueryParser::B_AND);
                    Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
                    
                    $_SESSION['search']['plain_text'] = $_REQUEST['fulltext'];

                    foreach ($_SESSION['collections'] as $key => $tmpCollection) {
                        $path_to_lucene_index = $tmpCollection['path_to_lucene_index'];

                        if (is_dir($path_to_lucene_index))
                        {
                            if (!$func->isDirEmpty($path_to_lucene_index)) {
                                $index = Zend_Search_Lucene::open($path_to_lucene_index);
                                $hits = $index->find(urldecode($fulltext_request));
                                $Liste_Ids = "0";
                                $cptIds = 0;
                                foreach ($hits as $hit) {
                                    if ($cptIds < 500) {
                                        $Liste_Ids .= ", '". $hit->Id ."'";
                                    } else {
                                        break;
                                    }
                                    $cptIds ++;
                                }

                                if ($tmpCollection['table'] == 'res_attachments') {
                                    $tmpArray = preg_split("/[,' ]/", $Liste_Ids);
                                    array_splice($tmpArray, 0, 1);
                                    $_SESSION['fullTextAttachments']['attachments'] = $tmpArray;
                                    $db = new Database();
                                    $stmt = $db->query("SELECT DISTINCT res_id_master FROM res_attachments WHERE res_id IN ($Liste_Ids)");
                                    $idMasterDatas = [];
                                    while ($tmp = $stmt->fetchObject())
                                        $idMasterDatas[] = $tmp;

                                    $Liste_Ids = '0';
                                    foreach ($idMasterDatas as $tmpIdMaster) {
                                        $Liste_Ids .= ", '{$tmpIdMaster->res_id_master}'";
                                        $_SESSION['fullTextAttachments']['letterbox'][] = $tmpIdMaster->res_id_master;
                                    }
                                } elseif ($tmpCollection['table'] == 'res_version_attachments') {
                                    $tmpArray = preg_split("/[,' ]/", $Liste_Ids);
                                    array_splice($tmpArray, 0, 1);
                                    $_SESSION['fullTextAttachments']['versionAttachments'] = $tmpArray;
                                    $db = new Database();
                                    $stmt = $db->query("SELECT DISTINCT res_id_master FROM res_version_attachments WHERE res_id IN ($Liste_Ids)");
                                    $idMasterDatas = [];
                                    while ($tmp = $stmt->fetchObject())
                                        $idMasterDatas[] = $tmp;

                                    $Liste_Ids = '0';
                                    foreach ($idMasterDatas as $tmpIdMaster) {
                                        $Liste_Ids .= ", '{$tmpIdMaster->res_id_master}'";
                                        $_SESSION['fullTextAttachments']['letterbox'][] = $tmpIdMaster->res_id_master;
                                    }
                                }

                                if ($key == 0)
                                    $where_request .= ' (';

                                $where_request .= " res_id IN ($Liste_Ids) ";

                                if (empty($_SESSION['collections'][$key + 1]))
                                    $where_request .= ') and ';
                                else
                                    $where_request .= ' or ';
                            } else {
                                if ($key == 0)
                                    $where_request .= ' (';

                                $where_request .= " 1=-1 ";

                                if (empty($_SESSION['collections'][$key + 1]))
                                    $where_request .= ') and ';
                                else
                                    $where_request .= ' or ';
                            }
                        } else {
                            if ($key == 0)
                                $where_request .= ' (';

                            $where_request .= " 1=-1 ";

                            if (empty($_SESSION['collections'][$key + 1]))
                                $where_request .= ') and ';
                            else
                                $where_request .= ' or ';
                        }
                    }
                }
            }
            // TAGS
            elseif ($tab_id_fields[$j] == 'tags_chosen' && !empty($_REQUEST['tags_chosen']))
            {
                include_once("modules".DIRECTORY_SEPARATOR."tags".
                   DIRECTORY_SEPARATOR."tags_search.php");              
            }
            // THESAURUS
            elseif ($tab_id_fields[$j] == 'thesaurus_chosen' && !empty($_REQUEST['thesaurus_chosen']))
            {
                include_once("modules".DIRECTORY_SEPARATOR."thesaurus".
                   DIRECTORY_SEPARATOR."thesaurus_search.php");              
            }
            //WELCOME PAGE
            elseif ($tab_id_fields[$j] == 'welcome'  && (!empty($_REQUEST['welcome'])))
            {
                $welcome = $_REQUEST['welcome'];
                $json_txt .= "'multifield' : ['".addslashes(trim($welcome))."'],";
                if (is_numeric($_REQUEST['welcome']))
                {
                    $where_request_welcome .= "(res_id = :resIdWelcome) or ";
                    $arrayPDO = array_merge($arrayPDO, array(":resIdWelcome" => $_REQUEST['welcome']));
                }
                $where_request_welcome .= "( lower(translate(subject,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:multifieldWelcome) "
                    ."or lower(identifier) LIKE lower(:multifieldWelcome) "
                    ."or (lower(translate(alt_identifier,'/','')) like lower(:multifieldWelcome) OR lower(alt_identifier) like lower(:multifieldWelcome)) "
                    ."or lower(title) LIKE lower(:multifieldWelcome) "
                    ."or res_id in (select identifier from notes where lower(translate(note_text,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(:multifieldWelcome)) "
                    ."or res_id in (select res_id_master from res_view_attachments where lower(translate(identifier,'/','')) like lower(:multifieldWelcome) OR lower(identifier) like lower(:multifieldWelcome)) "
                    ."or contact_id in (select contact_id from view_contacts where society ilike :multifieldWelcome or contact_firstname ilike :multifieldWelcome or contact_lastname ilike :multifieldWelcome) or (exp_user_id in (select user_id from users where firstname ilike :multifieldWelcome or lastname ilike :multifieldWelcome )))";
                $arrayPDO = array_merge($arrayPDO, array(":multifieldWelcome" => "%".$_REQUEST['welcome']."%"));
                $welcome = $_REQUEST['welcome'];
                set_include_path('apps' . DIRECTORY_SEPARATOR 
                    . $_SESSION['config']['app_id'] 
                    . DIRECTORY_SEPARATOR . 'tools' 
                    . DIRECTORY_SEPARATOR . PATH_SEPARATOR . get_include_path()
                );
            }

            // CONFIDENTIALITY
            elseif ($tab_id_fields[$j] == 'confidentiality' && ($_REQUEST['confidentiality'] <> ""))
            {
                $json_txt .= " 'confidentiality' : ['".addslashes(trim($_REQUEST['confidentiality']))."'],";
                $where_request .= " confidentiality  = :confidentiality and ";
                $arrayPDO = array_merge($arrayPDO, array(":confidentiality" => $_REQUEST['confidentiality']));
            }
            // DOCTYPES
            elseif ($tab_id_fields[$j] == 'doctypes_chosen' && !empty($_REQUEST['doctypes_chosen']))
            {
                $json_txt .= " 'doctypes_chosen' : [";

                for ($get_i = 0; $get_i <count($_REQUEST['doctypes_chosen']); $get_i++)
                {
                    $json_txt .= "'".$_REQUEST['doctypes_chosen'][$get_i]."',";
                }

                $json_txt = substr($json_txt, 0, -1);

                $where_request .= " type_id IN  (:doctypesChosen) ";
                $where_request .=" and  ";
                $arrayPDO = array_merge($arrayPDO, array(":doctypesChosen" => $_REQUEST['doctypes_chosen']));
                $json_txt .= '],';
            }

            // MAIL NATURE
            elseif ($tab_id_fields[$j] == 'mail_nature' && !empty($_REQUEST['mail_nature']))
            {
                $json_txt .= "'mail_nature' : ['".addslashes(trim($_REQUEST['mail_nature']))."'],";
                $where_request .= " nature_id = :mailNature and ";
                $arrayPDO = array_merge($arrayPDO, array(":mailNature" => $_REQUEST['mail_nature']));
            }
            // CREATION DATE PJ : FROM
            elseif ($tab_id_fields[$j] == 'creation_date_pj_from' && !empty($_REQUEST['creation_date_pj_from']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['creation_date_pj_from'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['creation_date_pj_from'];
                }
                else
                {
                    $where_request .= " res_id in (SELECT res_id_master FROM res_view_attachments WHERE (".$req->extract_date("creation_date")." >= :creationDatePjFrom) ) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":creationDatePjFrom" => $func->format_date_db($_REQUEST['creation_date_pj_from'])));
                    $json_txt .= " 'creation_date_pj_from' : ['".trim($_REQUEST['creation_date_pj_from'])."'],";
                }
            }
            // CREATION DATE PJ : TO
            elseif ($tab_id_fields[$j] == 'creation_date_pj_to' && !empty($_REQUEST['creation_date_pj_to']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['creation_date_pj_to'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['creation_date_pj_to'];
                }
                else
                {
                    $where_request .= " res_id in (SELECT res_id_master FROM res_view_attachments WHERE (".$req->extract_date("creation_date")." <= :creationDatePjTo) )and ";
                    $arrayPDO = array_merge($arrayPDO, array(":creationDatePjTo" => $func->format_date_db($_REQUEST['creation_date_pj_to'])));
                    $json_txt .= " 'creation_date_pj_to' : ['".trim($_REQUEST['creation_date_pj_to'])."'],";
                }
            }
            // CREATION DATE : FROM
            elseif ($tab_id_fields[$j] == 'creation_date_from' && !empty($_REQUEST['creation_date_from']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['creation_date_from'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['creation_date_from'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("creation_date")." >= :creationDateFrom) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":creationDateFrom" => $func->format_date_db($_REQUEST['creation_date_from'])));
                    $json_txt .= " 'creation_date_from' : ['".trim($_REQUEST['creation_date_from'])."'],";
                }
            }
            // CREATION DATE : TO
            elseif ($tab_id_fields[$j] == 'creation_date_to' && !empty($_REQUEST['creation_date_to']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['creation_date_to'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['creation_date_to'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("creation_date")." <= :creationDateTo) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":creationDateTo" => $func->format_date_db($_REQUEST['creation_date_to'])));
                    $json_txt .= " 'creation_date_to' : ['".trim($_REQUEST['creation_date_to'])."'],";
                }
            }
            // PROCESS DATE : FROM (closing_date)
            elseif ($tab_id_fields[$j] == 'closing_date_from' && !empty($_REQUEST['closing_date_from']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['closing_date_from'])==false )
                {
                    $_SESSION['error'] .=  _WRONG_DATE_FORMAT.' : '.$_REQUEST['closing_date_from'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("closing_date")." >= :closingDateFrom) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":closingDateFrom" => $func->format_date_db($_REQUEST['closing_date_from'])));
                    $json_txt .= "'closing_date_from' : ['".trim($_REQUEST['closing_date_from'])."'],";
                }
            }
            // CLOSING DATE : TO
            elseif ($tab_id_fields[$j] == 'closing_date_to' && !empty($_REQUEST['closing_date_to']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['closing_date_to'])==false )
                {
                    $_SESSION['error'] = _WRONG_DATE_FORMAT.' : '.$_REQUEST['closing_date_to'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("closing_date")." <= :closingDateTo) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":closingDateTo" => $func->format_date_db($_REQUEST['closing_date_to'])));
                    $json_txt .= "'closing_date_to' : ['".trim($_REQUEST['closing_date_to'])."'],";
                }
            }
            // PROCESS LIMIT DATE : FROM
            elseif ($tab_id_fields[$j] == 'process_limit_date_from' && !empty($_REQUEST['process_limit_date_from']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['process_limit_date_from'])==false )
                {
                    $_SESSION['error'] = _WRONG_DATE_FORMAT.' : '.$_REQUEST['process_limit_date_from'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("process_limit_date")." >= :processLimitDateFrom) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":processLimitDateFrom" => $func->format_date_db($_REQUEST['process_limit_date_from'])));
                    $json_txt .= "'process_limit_date_from' : ['".trim($_REQUEST['process_limit_date_from'])."'],";
                }
            }
            // PROCESS LIMIT DATE : TO
            elseif ($tab_id_fields[$j] == 'process_limit_date_to' && !empty($_REQUEST['process_limit_date_to']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['process_limit_date_to'])==false )
                {
                    $_SESSION['error'] = _WRONG_DATE_FORMAT.' : '.$_REQUEST['process_limit_date_to'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("process_limit_date")." <= :processLimitDateTo) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":processLimitDateTo" => $func->format_date_db($_REQUEST['process_limit_date_to'])));
                    $json_txt .= "'process_limit_date_to' : ['".trim($_REQUEST['process_limit_date_to'])."'],";
                }
            }
            // STATUS
            elseif ($tab_id_fields[$j] == 'status_chosen' && isset($_REQUEST['status_chosen']))
            {
                $json_txt .= " 'status_chosen' : [";
                $where_request .="( ";
                for ($get_i = 0; $get_i <count($_REQUEST['status_chosen']); $get_i++)
                {
                    $json_txt .= "'".$_REQUEST['status_chosen'][$get_i]."',";
                    if ($_REQUEST['status_chosen'][$get_i]=="REL1")
                    {
                        $where_request .="( ".$req->extract_date('alarm1_date')." <= CURRENT_TIMESTAMP and ".$req->extract_date('alarm2_date')." > CURRENT_TIMESTAMP and status <> 'END') or ";
                    }
                    else
                    {
                        if ($_REQUEST['status_chosen'][$get_i]=="REL2")
                        {
                            $where_request .="( ".$req->current_datetime()." >= ".$req->extract_date('alarm2_date')."  and status <> 'END') or ";
                        }
                        elseif ($_REQUEST['status_chosen'][$get_i]=="LATE")
                        {
                            $where_request .="( process_limit_date is not null and ".$req->current_datetime()." > ".$req->extract_date('process_limit_date')."  and status <> 'END') or ";
                        }
                        else
                        {
                            $where_request .= " ( status = :statusChosen_".$get_i.") or ";
                            $arrayPDO = array_merge($arrayPDO, array(":statusChosen_".$get_i => $_REQUEST['status_chosen'][$get_i]));
                        }
                    }
                }
                $where_request = preg_replace("/or $/", "", $where_request);
                $json_txt = substr($json_txt, 0, -1);
                $where_request .=") and ";
                $json_txt .= '],';
            }
            // ANSWER TYPE BITMASK
            /**
             * Answer type bitmask
             * 0 0 0 0 0 0
             * | | | | | |_ Simple Mail
             * | | | | |___ Registered mail
             * | | | |_____ Direct Contact
             * | | |_______ Email
             * | |_________ Fax
             * |___________ Other Answer
             **/
            elseif ($tab_id_fields[$j] == 'AR' && !empty($_REQUEST['AR']))
            {
                $where_request .= " answer_type_bitmask like '____1_' AND ";
                $json_txt .= " 'AR' : ['".addslashes(trim($_REQUEST['AR']))."'],";
            }
            elseif ($tab_id_fields[$j] == 'fax' && !empty($_REQUEST['fax']))
            {
                $where_request .= " answer_type_bitmask like '_1____' AND ";
                $json_txt .= " 'fax' : ['".addslashes(trim($_REQUEST['fax']))."'],";
            }
            elseif ($tab_id_fields[$j] == 'courriel' && !empty($_REQUEST['courriel']))
            {
                $where_request .= " answer_type_bitmask like '__1___' AND ";
                $json_txt .= " 'courriel' : ['".addslashes(trim($_REQUEST['courriel']))."'],";
            }
            elseif ($tab_id_fields[$j] == 'autre' && !empty($_REQUEST['autre']))
            {
                $where_request .= " answer_type_bitmask like '1_____' AND ";
                $json_txt .= " 'autre' : ['".addslashes(trim($_REQUEST['autre']))."'],";
            }
            elseif ($tab_id_fields[$j] == 'direct' && !empty($_REQUEST['direct']))
            {
                $where_request .= " answer_type_bitmask like '___1__' AND ";
                $json_txt .= " 'direct' : ['".addslashes(trim($_REQUEST['direct']))."'],";
            }
            elseif ($tab_id_fields[$j] == 'simple_mail' && !empty($_REQUEST['simple_mail']))
            {
                $where_request .= " answer_type_bitmask like '_____1' AND ";
                $json_txt .= " 'simple_mail' : ['".addslashes(trim($_REQUEST['simple_mail']))."'],";
            }
            elseif ($tab_id_fields[$j] == 'norep' && !empty($_REQUEST['norep']))
            {
                $where_request .= " answer_type_bitmask = '000000' AND ";
                $json_txt .= " 'norep' : ['".addslashes(trim($_REQUEST['norep']))."'],";
            }
            // MAIL CATEGORY
            elseif ($tab_id_fields[$j] == 'category' && !empty($_REQUEST['category']))
            {
                $where_request .= " category_id = :category AND ";
                $arrayPDO = array_merge($arrayPDO, array(":category" => $_REQUEST['category']));
                $json_txt .= "'category' : ['".addslashes($_REQUEST['category'])."'],";
            } 
            // ADMISSION DATE : FROM
            elseif ($tab_id_fields[$j] == 'admission_date_from' && !empty($_REQUEST['admission_date_from']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['admission_date_from'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['admission_date_from'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("admission_date")." >= :admissionDateFrom) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":admissionDateFrom" => $func->format_date_db($_REQUEST['admission_date_from'])));
                    $json_txt .= " 'admission_date_from' : ['".trim($_REQUEST['admission_date_from'])."'],";
                }
            }
            // ADMISSION DATE : TO
            elseif ($tab_id_fields[$j] == 'admission_date_to' && !empty($_REQUEST['admission_date_to']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['admission_date_to'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['admission_date_to'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("admission_date")." <= :admissionDateTo) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":admissionDateTo" => $func->format_date_db($_REQUEST['admission_date_to'])));
                    $json_txt .= " 'admission_date_to' : ['".trim($_REQUEST['admission_date_to'])."'],";
                }
            }
            // DOC DATE : FROM
            elseif ($tab_id_fields[$j] == 'doc_date_from' && !empty($_REQUEST['doc_date_from']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['doc_date_from'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['doc_date_from'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("doc_date")." >= :docDateFrom) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":docDateFrom" => $func->format_date_db($_REQUEST['doc_date_from'])));
                    $json_txt .= " 'doc_date_from' : ['".trim($_REQUEST['doc_date_from'])."'],";
                }
            }
            // DOC DATE : TO
            elseif ($tab_id_fields[$j] == 'doc_date_to' && !empty($_REQUEST['doc_date_to']))
            {
                if ( preg_match($_ENV['date_pattern'],$_REQUEST['doc_date_to'])==false )
                {
                    $_SESSION['error'] .= _WRONG_DATE_FORMAT.' : '.$_REQUEST['doc_date_to'];
                }
                else
                {
                    $where_request .= " (".$req->extract_date("doc_date")." <= :docDateTo) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":docDateTo" => $func->format_date_db($_REQUEST['doc_date_to'])));
                    $json_txt .= " 'doc_date_to' : ['".trim($_REQUEST['doc_date_to'])."'],";
                }
            }
            // CONTACTS EXTERNAL
            elseif ($tab_id_fields[$j] == 'contactid' && !empty($_REQUEST['contactid_external']))
            {
                $json_txt .= " 'contactid_external' : ['".addslashes(trim($_REQUEST['contactid_external']))."'], 'contactid' : ['".addslashes(trim($_REQUEST['contactid']))."'],";
                    $contact_id = $_REQUEST['contactid_external'];
					$where_request .= " (res_id in (select res_id from contacts_res where contact_id = :contactIdExternal and coll_id = '" . $coll_id . "') or ";
                    $where_request .= " (exp_contact_id = '".$contact_id."' or dest_contact_id = '".$contact_id."') or (res_id in (SELECT res_id_master FROM res_view_attachments WHERE dest_contact_id = ".$contact_id.") )) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":contactIdExternal" => $contact_id));
            }
            //recherche sur les contacts externes en fonction de ce que la personne a saisi
            elseif ($tab_id_fields[$j] == 'contactid' && empty($_REQUEST['contactid_external']) && !empty($_REQUEST['contactid']))
            {
                $json_txt .= " 'contactid_external' : ['".addslashes(trim($_REQUEST['contactid_external']))."'], 'contactid' : ['".addslashes(trim($_REQUEST['contactid']))."'],";
                    $contact_id = $_REQUEST['contactid'];
                    $where_request .= " (contact_id in (select contact_id from view_contacts where society ilike :contactId or contact_firstname ilike :contactId or contact_lastname ilike :contactId) ".
                        " or res_id in (SELECT res_id_master FROM res_view_attachments WHERE dest_contact_id in (select contact_id from view_contacts where society ilike :contactId or contact_firstname ilike :contactId or contact_lastname ilike :contactId) ) ) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":contactId" => "%".$contact_id."%"));
            }
            elseif ($tab_id_fields[$j] == 'addresses_id' && !empty($_REQUEST['addresses_id']))
            {
                $json_txt .= " 'addresses_id' : ['".addslashes(trim($_REQUEST['addresses_id']))."'], 'addresses_id' : ['".addslashes(trim($_REQUEST['addresses_id']))."'],";
                    $addresses_id = $_REQUEST['addresses_id'];
                    $where_request .= " address_id in (select ca_id from view_contacts where lastname ilike :addressId or firstname ilike :addressId ) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":addressId" => "%".$addresses_id."%"));
            }
            // CONTACTS INTERNAL
            elseif ($tab_id_fields[$j] == 'contactid_internal' && !empty($_REQUEST['contact_internal_id']))
            {
                $json_txt .= " 'contactid_internal' : ['".addslashes(trim($_REQUEST['contactid_internal']))."'], 'contact_internal_id' : ['".addslashes(trim($_REQUEST['contact_internal_id']))."']";
                	$contact_id = $_REQUEST['contact_internal_id'];
                    $where_request .= " ((exp_user_id = :contactInternalId or dest_user_id = :contactInternalId) or ";
                    $where_request .= " (res_id in (select res_id from contacts_res where contact_id = :contactInternalId and coll_id = '" . $coll_id . "'))) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":contactInternalId" => $contact_id));
            }
            //recherche sur les contacts internes en fonction de ce que la personne a saisi
            elseif ($tab_id_fields[$j] == 'contactid_internal' && empty($_REQUEST['contact_internal_id']) && !empty($_REQUEST['contactid_internal']))
            {
                $json_txt .= " 'contactid_internal' : ['".addslashes(trim($_REQUEST['contactid_internal']))."'], 'contact_internal_id' : ['".addslashes(trim($_REQUEST['contactid_internal']))."']";
                    $contactid_internal = pg_escape_string($_REQUEST['contactid_internal']);
                    //$where_request .= " ((user_firstname = '".$contactid_internal."' or user_lastname = '".$contactid_internal."') or ";
                    $where_request .= " (exp_user_id in (select user_id from users where firstname ilike :contactIdInternal or lastname ilike :contactIdInternal )) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":contactIdInternal" => "%".$contactid_internal."%"));
            }
            // Nom du signataire
            elseif ($tab_id_fields[$j] == 'signatory_name' && !empty($_REQUEST['signatory_name_id']))
            {
                $json_txt .= " 'signatory_name' : ['".addslashes(trim($_REQUEST['signatory_name']))."'], 'signatory_name_id' : ['".addslashes(trim($_REQUEST['signatory_name_id']))."']";
                    $signatory_name = $_REQUEST['signatory_name_id'];
                    $where_request .= " (res_id in (select res_id from listinstance where item_id = :signatoryNameId and coll_id = '" . $coll_id . "' and item_mode = 'sign' and difflist_type = 'VISA_CIRCUIT')) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":signatoryNameId" => $signatory_name));
            }
            //recherche sur les signataires en fonction de ce que la personne a saisi
            elseif ($tab_id_fields[$j] == 'signatory_name' && empty($_REQUEST['signatory_name_id']) && !empty($_REQUEST['signatory_name']))
            {
                $json_txt .= " 'signatory_name' : ['".addslashes(trim($_REQUEST['signatory_name']))."']";
                    $signatory_name = pg_escape_string($_REQUEST['signatory_name']);
                    //$where_request .= " ((user_firstname = '".$contactid_internal."' or user_lastname = '".$contactid_internal."') or ";
                    $where_request .= " (res_id in (select res_id from listinstance where item_id in (select user_id from users where firstname ilike :signatoryName or lastname ilike :signatoryName) "
                        ."and coll_id = '" . $coll_id . "' and item_mode = 'sign' and difflist_type = 'VISA_CIRCUIT')) and ";
                    $arrayPDO = array_merge($arrayPDO, array(":signatoryName" => "%".$signatory_name."%"));
            }
            // SEARCH IN BASKETS
            else if ($tab_id_fields[$j] == 'baskets_clause' && !empty($_REQUEST['baskets_clause'])) {
                //$func->show_array($_REQUEST);exit;
                switch($_REQUEST['baskets_clause']) {
                case 'false':
                    $baskets_clause = "false";
                    $json_txt .= "'baskets_clause' : ['false'],";
                    break;
                    
                case 'true':
                    for($ind_bask = 0; $ind_bask < count($_SESSION['user']['baskets']); $ind_bask++) {
                       if ($_SESSION['user']['baskets'][$ind_bask]['coll_id'] == $coll_id 
                        && $_SESSION['user']['baskets'][$ind_bask]['is_folder_basket'] == 'N') {
                            if(isset($_SESSION['user']['baskets'][$ind_bask]['clause']) && trim($_SESSION['user']['baskets'][$ind_bask]['clause']) <> '') {
                                $_SESSION['searching']['comp_query'] .= ' or ('.$_SESSION['user']['baskets'][$ind_bask]['clause'].')';
                            }
                         }
                    }
                    $_SESSION['searching']['comp_query'] = preg_replace('/^ or/', '', $_SESSION['searching']['comp_query']);
                    $baskets_clause = ($_REQUEST['baskets_clause']);
                    $json_txt .= " 'baskets_clause' : ['true'],";
                    break;
                
                default:
                    $json_txt .= " 'baskets_clause' : ['".addslashes(trim($_REQUEST['baskets_clause']))."'],";
                    for($ind_bask = 0; $ind_bask < count($_SESSION['user']['baskets']); $ind_bask++) {
                        if($_SESSION['user']['baskets'][$ind_bask]['id'] == $_REQUEST['baskets_clause']
                            && $_SESSION['user']['baskets'][$ind_bask]['is_folder_basket'] == 'N') {
                            if(isset($_SESSION['user']['baskets'][$ind_bask]['clause']) && trim($_SESSION['user']['baskets'][$ind_bask]['clause']) <> '') {
                                $where_request .= ' ' . $_SESSION['user']['baskets'][$ind_bask]['clause'] . ' and ' ;
                            } 
                        }
                    }
                }
            }
            else  // opt indexes check
            {
                $tmp = $type->search_checks($indexes, $tab_id_fields[$j], $_REQUEST[$tab_id_fields[$j]] );
                //$func->show_array($tmp);
                $json_txt .= $tmp['json_txt'];
                $where_request .= $tmp['where'];
            }
        }

        $json_txt = preg_replace('/,$/', '', $json_txt);
        $json_txt .= "}},";
    }
    $json_txt = preg_replace('/,$/', '', $json_txt);
}
$json_txt = preg_replace("/,$/", "", $json_txt);
$json_txt .= '}';


$_SESSION['current_search_query'] = $json_txt;
if (!empty($_SESSION['error_search'])) {
    $_SESSION['error'] = _MUST_CORRECT_ERRORS.' : '.$_SESSION['error_search'];

    if ($mode == 'normal') {
        ?>
        <script  type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'].'index.php?page=search_adv&dir=indexing_searching';?>';</script>
        <?php
    } else {
        ?>
        <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=search_adv&mode='.$mode;?>';</script>
        <?php
    }
    exit();
} else {
    if ($where_request_welcome <> '') {
        // $where_request_welcome = substr($where_request_welcome, 0, -4);
        $where_request .= '(' . $where_request_welcome . ') and ';
    }
    $where_request = trim($where_request);
    $_SESSION['searching']['where_request'] = $where_request;
    $_SESSION['searching']['where_request_parameters'] = $arrayPDO;
}
if (isset($_REQUEST['specific_case'])
    && $_REQUEST['specific_case'] == "attach_to_case"
) {
    $page = 'list_results_mlb_frame';
    ?>
    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=cases&page='.$page.'&load&searched_item='. $_REQUEST['searched_item'] .'&searched_value='.$_REQUEST['searched_value'].'&template='.$_REQUEST['template'];?>';</script>
    <?php
    exit();
}
if(!empty($_REQUEST['baskets_clause']) && $_REQUEST['baskets_clause'] != 'false' && $_REQUEST['baskets_clause'] != 'true') {
    ?>
    <script  type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl']."index.php?page=view_baskets&module=basket&baskets=". $_REQUEST['baskets_clause']."&origin=searching";?>';</script>
    <?php
    exit();
}
if (empty($_SESSION['error_search'])) {
    //specific string for search_adv cases
    $extend_link_case = "";
    if ($case_view == true) {
        $extend_link_case = "&template=group_case";
    }
    //##################
    $page = 'list_results_mlb';
    ?>
    <script type="text/javascript">window.top.location.href='<?php if ($mode == 'normal'){ echo $_SESSION['config']['businessappurl'].'index.php?page='.$page.'&dir=indexing_searching&load'.$extend_link_case;} elseif ($mode=='frame' || $mode == 'popup'){echo $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page='.$page.'&mode='.$mode.'&action_form='.$_REQUEST['action_form'].'&modulename='.$_REQUEST['modulename'];} if (isset($_REQUEST['nodetails'])){echo '&nodetails';}?>';</script>
    <?php
    exit();
}
$_SESSION['error_search'] = '';
