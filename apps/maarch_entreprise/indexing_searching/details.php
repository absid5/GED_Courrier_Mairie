<?php


/*
*   Copyright 2008-2015 Maarch
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
* File : details.php
*
* Detailed informations on an indexed document
*
* @package  indexing_searching
* @version 1.3
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/

$core = new core_tools();
$core->test_user();
$core->load_lang();
require_once 'core/manage_bitmask.php';
require_once 'core/class/class_request.php';
require_once 'core/class/class_security.php';
require_once 'apps/' . $_SESSION['config']['app_id'] . '/security_bitmask.php';
require_once 'apps/' . $_SESSION['config']['app_id'] . '/class/class_list_show.php';
require_once 'core/class/class_history.php';
require_once 'core/class/LinkController.php';
require_once 'apps/' . $_SESSION['config']['app_id'] . '/class/class_indexing_searching_app.php';
require_once 'apps/' . $_SESSION['config']['app_id'] . '/class/class_types.php';

$_SESSION['basket_used'] = $_SESSION['current_basket']['id'];

if (file_exists(
    $_SESSION['config']['corepath'] . 'custom/apps/' . $_SESSION['config']['app_id']
    . '/definition_mail_categories.php'
)
) {
    $path = $_SESSION['config']['corepath'] . 'custom/apps/' . $_SESSION['config']['app_id']
          . '/definition_mail_categories.php';
} else {
    $path = 'apps/' . $_SESSION['config']['app_id'] . '/definition_mail_categories.php';
}
include_once $path;

//test service print_details
$printDetails = false;
if ($core->test_service('print_details', 'apps', false)) {
    $printDetails = true;
}
//test service put_in_validation
$putInValid = false;
if ($core->test_service('put_in_validation', 'apps', false)) {
    $putInValid = true;
}
//test service view technical infos
$viewTechnicalInfos = false;
if ($core->test_service('view_technical_infos', 'apps', false)) {
    $viewTechnicalInfos = true;
}

//test service view doc history
$viewDocHistory = false;
if ($core->test_service('view_doc_history', 'apps', false)) {
    $viewDocHistory = true;
}

//test service add new version
$addNewVersion = false;
if ($core->test_service('add_new_version', 'apps', false)) {
    $addNewVersion = true;
}

//test service view_emails_notifs
// $viewEmailsNotifs = false;
// if ($core->test_service('view_emails_notifs', 'notifications', false)) {
    // $viewEmailsNotifs = true;
// }

if (!isset($_REQUEST['coll_id'])) {
    $_REQUEST['coll_id'] = '';
}
$_SESSION['doc_convert'] = array();
$_SESSION['save_list']['fromDetail'] = "true";

/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
    $init = true;
}
if (isset($_SESSION['indexation'] ) && $_SESSION['indexation'] == true) {
    $init = true;
}
$level = '';
if (
    isset($_REQUEST['level'])
    && (
        $_REQUEST['level'] == 2
        || $_REQUEST['level'] == 3
        || $_REQUEST['level'] == 4
        || $_REQUEST['level'] == 1
    )
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl']
    . 'index.php?page=details&dir=indexing_searching&coll_id='
    . $_REQUEST['coll_id']
    . '&id=' . $_REQUEST['id'];
$page_label = _DETAILS;
$page_id = 'details';
$core->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/
$hist = new history();
$security = new security();
$func = new functions();
$request= new request;
$type = new types();
$s_id = '';
$_SESSION['req'] ='details';
$_SESSION['indexing'] = array();
$is = new indexing_searching_app();
$coll_id = '';
$table = '';
if (!isset($_REQUEST['coll_id']) || empty($_REQUEST['coll_id'])) {
    //$_SESSION['error'] = _COLL_ID.' '._IS_MISSING;
    $coll_id = $_SESSION['collections'][0]['id'];
    $table = $_SESSION['collections'][0]['view'];
    $is_view = true;
} else {
    $coll_id = $_REQUEST['coll_id'];
    $table = $security->retrieve_view_from_coll_id($coll_id);
    $is_view = true;
    if (empty($table)) {
        $table = $security->retrieve_table_from_coll($coll_id);
        $is_view = false;
    }
}
$_SESSION['collection_id_choice'] = $coll_id;
$_SESSION['current_basket']['coll_id'] = $coll_id;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $s_id = addslashes($func->wash($_GET['id'], 'num', _THE_DOC));
}

$db = new Database();

$stmt = $db->query("SELECT res_id FROM mlb_coll_ext WHERE res_id = ?", array($s_id));
if ($stmt->rowCount() <= 0) {
    $_SESSION['error'] = _QUALIFY_FIRST;
    ?>
        <script type="text/javascript">window.top.location.href='<?php
            echo $_SESSION['config']['businessappurl'];?>index.php';</script>
    <?php
    exit();
}
$_SESSION['doc_id'] = $s_id;
$right = $security->test_right_doc($coll_id, $s_id);
//$_SESSION['error'] = 'coll '.$coll_id.', res_id : '.$s_id;

$stmt = $db->query("SELECT typist, creation_date FROM ".$table." WHERE res_id = ?", array($s_id));
$info_mail = $stmt->fetchObject();

$date1 = new DateTime($info_mail->creation_date);
$date2 = new DateTime();
$date2->sub(new DateInterval('PT1M'));

if (!$right && $_SESSION['user']['UserId'] == $info_mail->typist && $date1 > $date2) {
    $right = true;
    $_SESSION['info'] = _MAIL_WILL_DISAPPEAR;
}

if (!$right) {
    $_SESSION['error'] = _NO_RIGHT_TXT;
    ?>
    <script type="text/javascript">
    window.top.location.href = '<?php
        echo $_SESSION['config']['businessappurl'];
        ?>index.php';
    </script>
    <?php
    exit();
}
if (isset($s_id) && !empty($s_id) && $_SESSION['history']['resview'] == 'true') {
    $hist->add(
        $table,
        $s_id ,
        'VIEW',
        'resview',
        _VIEW_DETAILS_NUM . $s_id,
        $_SESSION['config']['databasetype'],
        'apps'
    );
}

$modify_doc = check_right(
    $_SESSION['user']['security'][$coll_id]['DOC']['securityBitmask'],
    DATA_MODIFICATION
);
$delete_doc = check_right(
    $_SESSION['user']['security'][$coll_id]['DOC']['securityBitmask'],
    DELETE_RECORD
);

//update index with the doctype
if (isset($_POST['submit_index_doc'])) {
    /*if (
        $core->is_module_loaded('entities')
        && is_array($_SESSION['details']['diff_list'])
    ) {
        require_once('modules/entities/class/class_manage_listdiff.php');
        $list = new diffusion_list();
        $params = array(
            'mode'=> 'listinstance',
            'table' => $_SESSION['tablename']['ent_listinstance'],
            'coll_id' => $coll_id,
            'res_id' => $s_id,
            'user_id' => $_SESSION['user']['UserId'],
            'concat_list' => true,
            'only_cc' => false
        );
        $list->load_list_db(
            $_SESSION['details']['diff_list'],
            $params
        ); //pb enchainement avec action redirect
        $_SESSION['details']['diff_list']['key_value'] = md5($res_id);
    }*/
    $is->update_mail($_POST, 'POST', $s_id, $coll_id);

    if ($core->is_module_loaded('tags')) {
        $tags = $_POST['tag_userform'];
        $tags_list = $tags;
        include_once("modules".DIRECTORY_SEPARATOR."tags".DIRECTORY_SEPARATOR."tags_update.php");
    }

    //thesaurus
    if ($core->is_module_loaded('thesaurus')) {
        require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                    . 'class_modules_tools.php';
        $thesaurus = new thesaurus();

        if (! empty($_POST['thesaurus'])) {
            $thesaurusList = implode('__',$_POST['thesaurus']);
        }else{
	       $thesaurusList = '';
        }
	$thesaurus->updateResThesaurusList($thesaurusList,$s_id);
    }
}

//delete the doctype
if (isset($_POST['delete_doc'])) {
    $is ->delete_doc($s_id, $coll_id);
    ?>
        <script type="text/javascript">window.top.location.href='<?php
            echo $_SESSION['config']['businessappurl']
                . 'index.php?page=search_adv&dir=indexing_searching';
            ?>';</script>
    <?php
    exit();
}
if (isset($_POST['put_doc_on_validation'])) {
    $is ->update_doc_status($s_id, $coll_id, 'VAL');
    ?>
        <script language="javascript" type="text/javascript">window.top.location.href='<?php
            echo $_SESSION['config']['businessappurl']
            . 'index.php?page=search_adv&dir=indexing_searching';
            ?>';</script>
    <?php
    exit();
}

//Load multicontacts
	$query = "SELECT c.contact_firstname, c.contact_lastname, c.firstname, c.lastname, c.society 
                FROM view_contacts c, contacts_res cres 
                WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (c.contact_id as varchar) = cres.contact_id AND c.ca_id = cres.address_id 
                GROUP BY c.firstname, c.lastname, c.society, c.contact_firstname, c.contact_lastname";
			
	$stmt = $db->query($query, array($_REQUEST['id']));
	$nbContacts = 0;
	$frameContacts = "";
	$frameContacts = "{";
	while($res = $stmt->fetchObject()){
		$nbContacts = $nbContacts + 1;
        $contact_firstname = str_replace("'","\'", $res->contact_firstname);
        $contact_firstname = str_replace('"'," ", $contact_firstname);
        $contact_lastname = str_replace("'","\'", $res->contact_lastname);
        $contact_lastname = str_replace('"'," ", $contact_lastname);
		$firstname = str_replace("'","\'", $res->firstname);
		$firstname = str_replace('"'," ", $firstname);
		$lastname = str_replace("'","\'", $res->lastname);
		$lastname = str_replace('"'," ", $lastname);
		$society = str_replace("'","\'", $res->society);
		$society = str_replace('"'," ", $society);
		$frameContacts .= "'contact ".$nbContacts."' : '" . $contact_firstname . " " . $contact_lastname . " " . $firstname . " " . $lastname . " " . $society . " (contact)', ";
	}
    $query = "SELECT u.firstname, u.lastname, u.user_id ";
			$query .= "FROM users u, contacts_res cres  ";
			$query .= "WHERE cres.coll_id = 'letterbox_coll' AND cres.res_id = ? AND cast (u.user_id as varchar) = cres.contact_id ";
			$query .= "GROUP BY u.firstname, u.lastname, u.user_id";
			
	$stmt = $db->query($query, array($_REQUEST['id']));
	
	while($res = $stmt->fetchObject()){
		$nbContacts = $nbContacts + 1;
		$firstname = str_replace("'","\'", $res->firstname);
		$firstname = str_replace('"'," ", $firstname);
		$lastname = str_replace("'","\'", $res->lastname);
		$lastname = str_replace('"'," ", $lastname);
		$frameContacts .= "'contact ".$nbContacts."' : '" . $firstname . " " . $lastname . " (utilisateur)', ";
	}
	$frameContacts = substr($frameContacts, 0, -2);
	$frameContacts .= "}";
if (empty($_SESSION['error']) || $_SESSION['indexation']) {
    $comp_fields = '';
    $stmt = $db->query("SELECT type_id FROM ".$table." WHERE res_id = ?", array($s_id));
    if ($stmt->rowCount() > 0) {
        $res = $stmt->fetchObject();
        $type_id = $res->type_id;
        $indexes = $type->get_indexes($type_id, $coll_id, 'minimal');
        for($i=0;$i<count($indexes);$i++) {
            // In the view all custom from res table begin with doc_
            if (preg_match('/^custom_/', $indexes[$i])) {
                $comp_fields .= ', doc_'.$indexes[$i];
            } else {
                $comp_fields .= ', '.$indexes[$i];
            }
        }
    }
    $case_sql_complementary = '';
    if ($core->is_module_loaded('cases') == true) {
        $case_sql_complementary = " , case_id";
    }
    $stmt = $db->query(
        "SELECT status, format, typist, creation_date, fingerprint, filesize, "
        . "res_id, work_batch, page_count, is_paper, scan_date, scan_user, "
        . "scan_location, scan_wkstation, scan_batch, source, doc_language, "
        . "description, closing_date, alt_identifier, initiator, entity_label " . $comp_fields
        . $case_sql_complementary . " FROM " . $table . " WHERE res_id = ?",
        array($s_id)
    );

}
?>
<div id="details_div" style="display:block;">
<h1 class="titdetail">
    <i class="fa fa-info-circle fa-2x"></i>&nbsp;<?php
        echo _DETAILS . " : " . _DOC . ' ' . strtolower(_NUM);
        ?><?php
        functions::xecho($s_id);
        ?> <span>(<?php
        echo  $security->retrieve_coll_label_from_coll_id($coll_id);
        ?>)</span>
</h1>
<div id="inner_content" class="clearfix">
<?php 
if ((!empty($_SESSION['error']) && ! ($_SESSION['indexation'] ))  )
{
    ?>
    <div class="error">
        <br />
        <br />
        <br />
        <?php echo $_SESSION['error'];  $_SESSION['error'] = "";?>
        <br />
        <br />
        <br />
    </div>
    <?php
} else {
    if ($stmt->rowCount() == 0) {
        ?>
        <div align="center">
                <br />
                <br />
                <?php echo _NO_DOCUMENT_CORRESPOND_TO_IDENTIFIER;?>.
                <br />
                <br />
                <br />
            </div>
            <?php
        } else {
            ?>
            <div id="info_detail" class="info" onclick="this.hide();">
                <?php echo $_SESSION['info'] ;?>
                <br />
                <br />
            </div>
            <?php
            if(isset($_SESSION['info']) && $_SESSION['info'] <> '') {
                ?>

                <script>
                    var info_detail = $('info_detail');
                    if (info_detail != null) {
                        info_detail.style.display = 'table-cell';
                        Element.hide.delay(10, 'info_detail');
                    }
                </script>
                <?php
                $_SESSION['info'] = "";
            }
    
            $param_data = array(
                'img_category_id' => true,
                'img_priority' => true,
                'img_type_id' => true,
                'img_doc_date' => true,
                'img_admission_date' => true,
                'img_nature_id' => true,
                'img_reference_number' => true,
                'img_subject' => true,
                'img_process_limit_date' => true,
                'img_author' => true,
                'img_destination' => true,
                'img_folder' => true,
                'img_contact' => true,
                'img_confidentiality' => true,
                );

            $res = $stmt->fetchObject();
            $typist = $res->typist;
            $format = $res->format;
            $filesize = $res->filesize;
            $creation_date = functions::format_date_db($res->creation_date, false);
            $chrono_number = $res->alt_identifier;
            $initiator = $res->initiator;
            $fingerprint = $res->fingerprint;
            $work_batch = $res->work_batch;
            $page_count = $res->page_count;
            $is_paper = $res->is_paper;
            $scan_date = functions::format_date_db($res->scan_date);
            $scan_user = $res->scan_user;
            $scan_location = $res->scan_location;
            $scan_wkstation = $res->scan_wkstation;
            $scan_batch = $res->scan_batch;
            $doc_language = $res->doc_language;
            $closing_date = functions::format_date_db($res->closing_date, false);
            $indexes = $type->get_indexes($type_id, $coll_id);
            $entityLabel = $res->entity_label;

            $queryUser = "SELECT firstname, lastname FROM users WHERE user_id = ?";
            $stmt = $db->query($queryUser, array($typist));
            $resultUser = $stmt->fetchObject();

            $queryEntities = "SELECT entity_label FROM entities WHERE entity_id = ?";
            $stmt = $db->query($queryEntities, array($initiator));
            $resultEntities = $stmt->fetchObject();
            $entities = $resultEntities->entity_label;


            if ($resultUser->lastname <> '') {
                $typistLabel = $resultUser->firstname . ' ' . $resultUser->lastname;
            } else {
                $typistLabel = $typist;
            }

            if ($core->is_module_loaded('cases') == true) {
                require_once('modules/cases/class/class_modules_tools.php');
                $case = new cases();
                if ($res->case_id <> '')
                    $case_properties = $case->get_case_info($res->case_id);
            }

            //$db->show_array($indexes);
            foreach (array_keys($indexes) as $key) {
                if (preg_match('/^custom/', $key)) {
                    $tmp = 'doc_' . $key;
                } else{
                    $tmp = $key;
                }
                if ($indexes[$key]['type'] == "date") {
                    $res->{$tmp} = functions::format_date_db($res->{$tmp}, false);
                }
                $indexes[$key]['value'] = $res->{$tmp};
                $indexes[$key]['show_value'] = $res->{$tmp};
                if ($indexes[$key]['type'] == "string") {
                    $indexes[$key]['show_value'] = functions::show_string($res->{$tmp});
                } elseif ($indexes[$key]['type'] == "date") {
                    $indexes[$key]['show_value'] = functions::format_date_db($res->{$tmp}, true);
                }
            }
            //$db->show_array($indexes);
            $process_data = $is->get_process_data($coll_id, $s_id);
            $status = $res->status;
            if (!empty($status))
            {
                require_once('core/class/class_manage_status.php');
                $status_obj = new manage_status();
                $res_status = $status_obj->get_status_data($status);
                if ($modify_doc) {
                    $can_be_modified = $status_obj->can_be_modified($status);
                    if (!$can_be_modified) {
                        $modify_doc = false;
                    }
                }
            }
            $mode_data = 'full';
            if ($modify_doc) {
                $mode_data = 'form';
            }
            foreach (array_keys($indexes) as $key) {
                $indexes[$key]['opt_index'] = true;
                if ($indexes[$key]['type_field'] == 'select') {
                    for($i=0;$i<count($indexes[$key]['values']);$i++) {
                        if ($indexes[$key]['values'][$i]['id'] == $indexes[$key]['value']) {
                            $indexes[$key]['show_value'] = $indexes[$key]['values'][$i]['label'] ;
                            break;
                        }
                    }
                }
                if (!$modify_doc) {
                    $indexes[$key]['readonly'] = true;
                    $indexes[$key]['type_field'] = 'input';
                } else {
                    $indexes[$key]['readonly'] = false;
                }
            }
            $data = get_general_data($coll_id, $s_id, $mode_data, $param_data );
            //$data = array_merge($data, $indexes);
            //$db->show_array($indexes);
            $detailsExport = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >';
            $detailsExport .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">';
            $detailsExport .= "<head><title>Maarch Details</title><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><meta content='fr' http-equiv='Content-Language'/><meta http-equiv='cache-control' content='no-cache'/><meta http-equiv='pragma' content='no-cache'><meta http-equiv='Expires' content='0'></head>";
            $detailsExport .= "<body onload='javascript:window.print();' style='font-size:8pt'>";
            ?>
            <div class="block">
                <b>
                <p id="back_list">
                    <?php
                    if (! isset($_POST['up_res_id']) || ! $_POST['up_res_id']) {
                        if ($_SESSION['indexation'] == false) {
						
							/*if($_SESSION['origin'] == 'show_folder' || $_SESSION['origin'] == 'search_folder_tree'){
								echo '<a href="#" onclick="history.back();return false;" class="back">' .  _BACK . '</a>';
								
							}else{
								echo '<a href="#" onclick="history.go(';
								if ($_SESSION['origin'] == 'basket' ) {
									echo '-2';
								} else {
									echo '-1';
								}
								echo ');" class="back">' .  _BACK . '</a>';
							}*/
	 		    echo '<a href="#" onclick="document.getElementById(\'ariane\').childNodes[document.getElementById(\'ariane\').childNodes.length-2].click();"><i class="fa fa-arrow-circle-left fa-2x" title="' .  _BACK . '"></i></a>';
                        }
                    }
		    
                    ?>
                </p>
                <p id="viewdoc">
                    <!--<a href="<?php
                        echo $_SESSION['config']['businessappurl'];
                        ?>index.php?page=view_baskets&module=basket&baskets=MyBasket&directLinkToAction&resid=<?php
                        functions::xecho($s_id);
                        ?>" target="_blank"><i class="fa fa-gears fa-2x" title="<?php 
                        echo _PROCESS;?>"></i></a>&nbsp;-->
                    <a href="<?php
                        echo $_SESSION['config']['businessappurl'];
                        ?>index.php?display=true&dir=indexing_searching&page=view_resource_controler&id=<?php
                        functions::xecho($s_id);
                        ?>" target="_blank"><i class="fa fa-download fa-2x" title="<?php
                        echo _VIEW_DOC;
                        ?>"></i></a>&nbsp;&nbsp;&nbsp;
                </p>
                </b>&nbsp;
            </div>
            <br/>
            <dl id="tabricator1">
                <?php $detailsExport .= "<h1><center>"._DETAILS_PRINT." : ".$s_id."</center></h1><hr>";?>
                <dt class="fa fa-tachometer" style="font-size:2em;padding-left: 15px;padding-right: 15px;" title="<?php echo _PROPERTIES;?>"> <sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt>
                <dd>
                    
                    <br/>
                <form method="post" name="index_doc" id="index_doc" action="index.php?page=details&dir=indexing_searching&id=<?php functions::xecho($s_id);?>">
                    <div align="center">
                        <?php if ($printDetails) {
                            /*if (
                              isset($_SESSION['custom_override_id'])
                              && $_SESSION['custom_override_id'] <> ''
                            ) {
                               $path = $_SESSION['config']['coreurl']
                                . '/custom/'
                                . $_SESSION['custom_override_id']
                                . '/apps/'
                                . $_SESSION['config']['app_id'];
                            } else {*/
                              $path = $_SESSION['config']['businessappurl'];
                            //}
                            ?>
                            <!-- OLD PRINT DETAILS VERSION -->
                            <!--<input type="button" class="button" name="print_details" id="print_details" value="<?php echo _PRINT_DETAILS;?>" onclick="window.open('<?php functions::xecho($path . "/tmp/export_details_".$_SESSION['user']['UserId']."_export.html");?>', '_blank');" />-->
                            <!-- NEW PRINT DETAILS VERSION -->
                            <input type="button" class="button" name="print_details" id="print_details" value="<?php echo _PRINT_DETAILS;?>" onclick="window.open('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=print&id=<?php functions::xecho($s_id);?>', '_blank');" />
                            <?php
                            }
                        ?>
                        <?php if ($putInValid) {
                            ?>
                            <input type="submit" class="button"  value="<?php echo _PUT_DOC_ON_VALIDATION;?>" name="put_doc_on_validation" onclick="return(confirm('<?php echo _REALLY_PUT_DOC_ON_VALIDATION;?>\n\r\n\r'));" />
                            <?php
                            }
                        ?>
                        <?php if ($delete_doc)
                        {?>
                        <input type="submit" class="button"  value="<?php echo _DELETE_DOC;?>" name="delete_doc" onclick="return(confirm('<?php echo _REALLY_DELETE.' '._THIS_DOC;?> ?\n\r\n\r'));" />
                        <?php }
                        if ($modify_doc)
                        {?>
                        <input type="submit" class="button"  value="<?php echo _SAVE_MODIFICATION;?>" name="submit_index_doc" />
                        <?php  } ?>
                            <input type="button" class="button" name="back_welcome" id="back_welcome" value="<?php echo _BACK_TO_WELCOME;?>" onclick="window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';" />

                            </div>
                    <h2>
                        <span class="date">
                            <?php $detailsExport .= "<h2>"._FILE_DATA."</h2>";?>
                            <b><?php echo _FILE_DATA;?></b>
                        </span>
                    </h2>
                    <?php $detailsExport .= "<table cellpadding='2' cellspacing='0' border='1' class='block forms details' width='100%'>";?>
                    <table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
                        <?php
                        $i=0;
                        if (!$modify_doc) {
                            $data['process_limit_date']['readonly'] = true;
                        }
                        foreach(array_keys($data) as $key)
                        {
							if($key != 'is_multicontacts' || ($key == 'is_multicontacts' && $data[$key]['show_value'] == 'Y')){
								if ($i%2 != 1 || $i==0) // pair
								{
									$detailsExport .= "<tr class='col'>";
									?>
									<tr class="col">
									<?php
								}
								$folder_id = "";
								if ($key == "folder" && $data[$key]['show_value'] <> "")
								{
									$folderTmp = $data[$key]['show_value'];
									$find1 = strpos($folderTmp, '(');
									$folder_id = substr($folderTmp, $find1, strlen($folderTmp));
									$folder_id = str_replace("(", "", $folder_id);
									$folder_id = str_replace(")", "", $folder_id);
								}
								//$detailsExport .= "<th align='left' width='50px'>";
								?>
								<th align="left" class="picto" >
									<?php
									if (isset($data[$key]['addon']))
									{
										echo $data[$key]['addon'];
										//$detailsExport .= $data[$key]['addon'];
									}
									elseif (isset($data[$key]['img']))
									{
										//$detailsExport .= "<img alt='".$data[$key]['label']."' title='".$data[$key]['label']."' src='".$data[$key]['img']."'  />";
										if ($folder_id <> "")
										{
											echo "<a href='".$_SESSION['config']['businessappurl']."index.php?page=show_folder&module=folder&id=".$folder_id."'>";
											?>
											<i class="fa fa-<?php functions::xecho($data[$key]['img']);?> fa-2x" title="<?php functions::xecho($data[$key]['label']);?>"></i>
                                            </a>
											<?php
										} else if($key == 'is_multicontacts'){
											?>
											
												<i class="fa fa-<?php functions::xecho($data[$key]['img']);?> fa-2x" title="<?php functions::xecho($data[$key]['label']);?>"
													onclick = "previsualiseAdminRead(event, <?php functions::xecho($frameContacts);?>);" style="cursor: pointer;"></i>
											</a>
											<?php
										}
										else
										{
											?>
                                            <i class="fa fa-<?php functions::xecho($data[$key]['img']);?> fa-2x" title="<?php functions::xecho($data[$key]['label']);?>"></i>
											<?php
										}
										?>



										<?php
									}
                                //$detailsExport .= "</th>";
                                ?>
                            </th>
                            <?php
							
                        $detailsExport .= "<td align='left' width='200px'>";
						
									?>
								<td align="left" width="200px">
									<?php									
										$detailsExport .= $data[$key]['label'];
										echo $data[$key]['label'];
										
									?>
									
								</td>
								<?php
							
                            $detailsExport .=  "</td>";
                            $detailsExport .=  "<td>";
							
                            ?>
                            <td>
							
                                <?php
                                $detailsExport .=  $data[$key]['show_value'];
                            if (!isset($data[$key]['readonly']) || $data[$key]['readonly'] == true)
                            {
							
								if($key == 'is_multicontacts') {
									if($data[$key]['show_value'] == 'Y'){
									?>
											<input type="hidden" name="<?php functions::xecho($key);?>" id="<?php functions::xecho($key);?>" value="<?php functions::xecho($data[$key]['show_value']);?>" readonly="readonly" class="readonly" size="40"  title="<?php functions::xecho($data[$key]['show_value']);?>" alt="<?php functions::xecho($data[$key]['show_value']);?>" />
														
											<div onClick="$('return_previsualise').style.display='none';" id="return_previsualise" style="cursor: pointer; display: none; border-radius: 10px; box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.4); padding: 10px; width: auto; height: auto; position: absolute; top: 0; left: 0; z-index: 999; background-color: rgba(255, 255, 255, 0.9); border: 3px solid #459ed1;">';
												<input type="hidden" id="identifierDetailFrame" value="" />
											</div>
											
											<input type="text" value="<?php functions::xecho($nbContacts . ' ' ._CONTACTS);?>" readonly="readonly" class="readonly" size="40"  title="<?php echo _SHOW_MULTI_CONTACT;?>" alt="<?php functions::xecho($data[$key]['show_value']);?>" 
														onclick = "previsualiseAdminRead(event, <?php functions::xecho($frameContacts);?>);" style="cursor: pointer;"
														 
											/>
									<?php
									}
	
								}elseif ($data[$key]['display'] == 'textinput')
                                {
                                    if($key == 'type_id'){
                                        ?>
                                        <input type="text" name="<?php echo $key;?>"  value="<?php echo $data[$key]['show_value'];?>" readonly="readonly" class="readonly" size="40"  title="<?php  echo $data[$key]['show_value']; ?>" alt="<?php  echo $data[$key]['show_value']; ?>" />
                                        <input type="hidden" name="<?php echo $key;?>" id="<?php echo $data[$key]['value'];?>" value="<?php echo $data[$key]['value'];?>" readonly="readonly" class="readonly" size="40"  title="<?php  echo $data[$key]['show_value']; ?>" alt="<?php  echo $data[$key]['show_value']; ?>" />
                                        <?php
                                        }else{
                                        ?>
                                        <input type="text" name="<?php echo $key;?>" id="<?php echo $key;?>" value="<?php echo $data[$key]['show_value'];?>" readonly="readonly" class="readonly" size="40"  title="<?php  echo $data[$key]['show_value']; ?>" alt="<?php  echo $data[$key]['show_value']; ?>" />
                                        <?php
                                        }
                                }
                                elseif ($data[$key]['display'] == 'textarea')
								
                                {
                                    echo '<textarea name="'.$key.'" id="'.$key.'" rows="3" readonly="readonly" class="readonly" style="width: 200px; max-width: 200px;">'
                                        .$data[$key]['show_value']
                                    .'</textarea>';								
                                } else if ($data[$key]['field_type'] == 'radio') {
                                    for($k=0; $k<count($data[$key]['radio']);$k++) {
                                        ?><input name ="<?php functions::xecho($key);?>" <?php if ($data[$key]['value'] ==$data[$key]['radio'][$k]['ID']){ echo 'checked';}?> type="radio" id="<?php functions::xecho($key) .'_' . $data[$key]['radio'][$k]['ID'];?>" value="<?php functions::xecho($data[$key]['radio'][$k]['ID']);?>" disabled ><?php functions::xecho($data[$key]['radio'][$k]['LABEL']);
                                    }
                                }
                                else
								
                                {
                                    ?>
                                    <input type="text" name="<?php functions::xecho($key);?>" id="<?php functions::xecho($key);?>" value="<?php functions::xecho($data[$key]['show_value']);?>" readonly="readonly" class="readonly" size="40" title="<?php functions::xecho($data[$key]['show_value']);?>" alt="<?php functions::xecho($data[$key]['show_value']);?>" />
                                    <?php
                                    if (isset($data[$key]['addon']))
									
									
                                    {
                                        $frm_str .= $data[$key]['addon'];
                                    }
                                }
                            }
                            else
                            {
                                if ($data[$key]['field_type'] == 'textfield')
                                {
                                    ?>
									
									
                                    <input type="text" name="<?php functions::xecho($key);?>" id="<?php functions::xecho($key);?>" value="<?php echo $data[$key]['show_value'];?>" size="40"  title="<?php echo $data[$key]['show_value'];?>" alt="<?php echo $data[$key]['show_value'];?>" />
                                    <?php
                                }
                                elseif ($data[$key]['display'] == 'textarea') 
                                {
                                    echo '<textarea name="'.$key.'" id="'.$key.'" rows="3" style="width: 200px; max-width: 200px;">'
                                        .$data[$key]['show_value']
                                    .'</textarea>';
                                }
                                else if ($data[$key]['field_type'] == 'date')
                                {
								
								
                                    ?>
                                    <input type="text" name="<?php functions::xecho($key);?>" id="<?php functions::xecho($key);?>" value="<?php functions::xecho($data[$key]['show_value']);?>" size="40"  title="<?php functions::xecho($data[$key]['show_value']);?>" alt="<?php functions::xecho($data[$key]['show_value']);?>" onclick="showCalender(this);" />
                                    
									
									
									
									
									<?php
                                }
                                else if ($data[$key]['field_type'] == 'select')
                                {
                                    ?>
                                    <select id="<?php functions::xecho($key);?>" name="<?php functions::xecho($key);?>"
                                    <?php if ($key == 'type_id'){
                                        echo 'onchange="change_doctype_details(this.options[this.options.selectedIndex].value, \''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=change_doctype_details\' , \''._DOCTYPE.' '._MISSING.'\');"';
                                    } else if ($key == 'priority') {
                                        echo 'onchange="updateProcessDate(\'' . $_SESSION['config']['businessappurl'] . 'index.php?display=true&dir=indexing_searching&page=update_process_date\', ' . $s_id . ')"';
                                    }?>
                                    >
                                    <?php
                                        if ($key == 'type_id')
                                        {
                                            if ($_SESSION['features']['show_types_tree'] == 'true')
                                            {

                                                for($k=0; $k<count($data[$key]['select']);$k++)
                                                {
                                                ?><option value="" class="doctype_level1"><?php functions::xecho($data[$key]['select'][$k]['label']);?></option><?php
                                                    for($j=0; $j<count($data[$key]['select'][$k]['level2']);$j++)
                                                    {
                                                        ?><option value="" class="doctype_level2">&nbsp;&nbsp;<?php functions::xecho($data[$key]['select'][$k]['level2'][$j]['label']);?></option><?php
                                                        for($l=0; $l<count($data[$key]['select'][$k]['level2'][$j]['types']);$l++)
                                                        {
                                                            ?><option
                                                            <?php if ($data[$key]['value'] ==$data[$key]['select'][$k]['level2'][$j]['types'][$l]['id']){ echo 'selected="selected"';}?>
                                                             value="<?php functions::xecho($data[$key]['select'][$k]['level2'][$j]['types'][$l]['id']);?>" >&nbsp;&nbsp;&nbsp;&nbsp;<?php functions::xecho($data[$key]['select'][$k]['level2'][$j]['types'][$l]['label']);?></option><?php
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                for($k=0; $k<count($data[$key]['select']);$k++)
                                                {
                                                    ?><option <?php if ($data[$key]['value'] ==$data[$key]['select'][$k]['ID']){ echo 'selected="selected"';}?> value="<?php functions::xecho($data[$key]['select'][$k]['ID']);?>" ><?php functions::xecho($data[$key]['select'][$k]['LABEL']);?></option><?php
                                                }
                                            }
                                        }
                                        else
                                        {
                                            for($k=0; $k<count($data[$key]['select']);$k++)
                                            {
                                                ?><option value="<?php functions::xecho($data[$key]['select'][$k]['ID']);?>" <?php if ($data[$key]['value'] == $data[$key]['select'][$k]['ID']){echo 'selected="selected"';}?>><?php functions::xecho($data[$key]['select'][$k]['LABEL']);?></option><?php
                                            }
                                        }
                                    ?>
                                    </select>
                                    <?php
                                } else if ($data[$key]['field_type'] == 'radio') {
                                    for($k=0; $k<count($data[$key]['radio']);$k++) {
                                        ?><input name ="<?php functions::xecho($key);?>" <?php if ($data[$key]['value'] ==$data[$key]['radio'][$k]['ID']){ echo 'checked';}?> type="radio" id="<?php functions::xecho($key) .'_' . $data[$key]['radio'][$k]['ID'];?>" value="<?php functions::xecho($data[$key]['radio'][$k]['ID']);?>" ><?php functions::xecho($data[$key]['radio'][$k]['LABEL']);
                                    }
                                } 
                                else if ($data[$key]['field_type'] == 'autocomplete')
                                {
                                    if ($key == 'folder' && $core->is_module_loaded('folder') && ($core->test_service('associate_folder', 'folder',false) == 1))
                                    {
                                    ?>  
                                        <input type="text" name="folder" id="folder" onblur="" value="<?php echo $data['folder']['show_value']; ?>" />
                                        <div id="show_folder" class="autocomplete"></div>
                                        <script type="text/javascript">initList('folder', 'show_folder','<?php echo $_SESSION['config']['businessappurl'];
                                        ?>index.php?display=true&module=folder&page=autocomplete_folders&mode=folder',  'Input', '2');</script>
                                        <?php
                                    } else { ?>
                                         <input class="readonly" type="text" name="folder" id="folder" onblur="" value="<?php echo $data['folder']['show_value']; ?>" readonly="readonly"/>
                                    <?php }
                                }
                            }
                                $detailsExport .=  "</td>";
                                ?>
                            </td>
                            <?php
                            if ($i%2 == 1 && $i!=0) // impair
                            {
                                $detailsExport .=  "</td>";
                                ?>
                                </tr>
                                <?php
                            }
                            else
                            {
                                if ($i+1 == count($data))
                                {
                                    $detailsExport .= "<td  colspan='2'>&nbsp;</td></tr>";
                                    echo '<td  colspan="2">&nbsp;</td></tr>';
                                }
                            }
                            $i++;
							}
                        }
                        $detailsExport .=  "<tr class='col'>";
                        $detailsExport .=  "<td align='left' width='200px'>";
                        $detailsExport .=  _STATUS;
                        $detailsExport .=  "</td>";
                        $detailsExport .=  "<td>";
                        $detailsExport .=  $res_status['LABEL'];
                        $detailsExport .=  "</td>";
                        $detailsExport .=  "<td align='left' width='200px'>";
                        $detailsExport .=  _CHRONO_NUMBER;
                        $detailsExport .=  "</td>";
                        $detailsExport .=  "<td>";
                        $detailsExport .=  $chrono_number;
                        $detailsExport .=  "</td>";
                        $detailsExport .=  "</tr>";

                        ?>
                        <tr class="col">
                            <th align="left" class="picto">
                                <!--img alt="<?php echo _STATUS.' : '.$res_status['LABEL'];?>" src="<?php functions::xecho($res_status['IMG_SRC']);?>" title="<?php functions::xecho($res_status['LABEL']);?>" alt="<?php functions::xecho($res_status['LABEL']);?>"/-->
                                <?php 
                                $img_class = substr($res_status['IMG_SRC'], 0, 2);
                                ?>
                                <i class = "<?php echo $img_class; ?> <?php functions::xecho($res_status['IMG_SRC']);?> <?php echo $img_class; ?>-2x" alt = "<?php functions::xecho($res_status['LABEL']);?>" title = "<?php functions::xecho($res_status['LABEL']);?>"></i>
                            </th>
                            <td align="left" width="200px">
                                <?php echo _STATUS;?>
                            </td>
                            <td>
                                <input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($res_status['LABEL']);?>" size="40"  />
                            </td>
                        <!--</tr>
                        <tr class="col">-->
                            <th align="left" class="picto">
                                <i class="fa fa-compass fa-2x" title="<?php echo _CHRONO_NUMBER;?>" ></i>
                            </th>
                            <td align="left" width="200px">
                                <?php echo _CHRONO_NUMBER;?>
                            </td>
                            <td>
                                <input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($chrono_number);?>" size="40" title="<?php functions::xecho($chrono_number);?>" alt="<?php functions::xecho($chrono_number);?>" />
                            </td>
                        </tr>
                        <tr class="col">
                            <th align="left" class="picto">
                                <i class="fa fa-sitemap fa-2x" title="<?php echo _INITIATOR;?>" ></i>
                            </th>
                            <td align="left" width="200px">
                                <?php echo _INITIATOR;?>
                            </td>
                            <td>
                                <textarea rows="2" style="width: 200px; max-width: 200px;" class="readonly" readonly="readonly"><?php functions::xecho($entities);?></textarea>
                            </td>
                            <!-- typist -->
                            <th align="left" class="picto">
                                <i class="fa fa-user fa-2x"></i>
                            </th>
                            <td align="left" width="200px">
                                <?php echo _TYPIST;?>
                            </td>
                            <td>
                                <input type="text" class="readonly" readonly="readonly" value="<?php functions::xecho($typistLabel); 
                                ?>" size="40" title="<?php functions::xecho($typistLabel);?>" alt="<?php functions::xecho($typistLabel);?>" />
                            </td>
                        </tr>

                    </table>
                    <?php
                    $detailsExport .=  "</table>";
                    ?>

                    <?php
                    /*if ($core->is_module_loaded('tags') &&
                        ($core->test_service('tag_view', 'tags',false) == 1))
                    {
                        include_once("modules".DIRECTORY_SEPARATOR."tags".DIRECTORY_SEPARATOR
                        ."templates/details/index.php");
                    }*/
                    ?>

                    <div id="opt_indexes">
                    <?php if (count($indexes) > 0 || ($core->is_module_loaded('tags') && ($core->test_service('tag_view', 'tags', false) == 1)) || ($core->is_module_loaded('thesaurus') && ($core->test_service('thesaurus_view', 'thesaurus', false) == 1)))
                    {
                        ?><br/>
                        <h2>
                        <span class="date">
                            <b><?php echo _OPT_INDEXES;?></b>
                        </span>
                        </h2>
                        <br/>
                        <table cellpadding="2" cellspacing="2" border="0" class="block forms details" width="100%">
                            <?php
                            $i=0;
                            foreach(array_keys($indexes) as $key)
                            {

                                if ($i%2 != 1 || $i==0) // pair
                                {
                                    $detailsExport .= "<tr class='col'>";
                                    ?>
                                    <tr class="col">
                                    <?php
                                }
                                $detailsExport .= "<th align='left' width='50px'>";
                                ?>
                                <th align="left" class="picto" >
                                    <?php
                                    if (isset($indexes[$key]['img']))
                                    {
                                        //$detailsExport .= "<img alt='".$indexes[$key]['label']."' title='".$indexes[$key]['label']."' src='".$indexes[$key]['img']."'  />";
                                        ?>
                                        <i class="fa fa-<?php functions::xecho($indexes[$key]['img']);?> fa-2x" title="<?php functions::xecho($indexes[$key]['label']);?>" ></i>
                                        <?php
                                    }
                                    $detailsExport .= "</th>";
                                    ?>
                                </th>
                                <?php
                                $detailsExport .= "<td align='left' width='200px'>";
                                ?>
                                <td align="left" width="200px">
                                    <?php
                                    $detailsExport .= $indexes[$key]['label'];
                                    functions::xecho($indexes[$key]['label']);?> :
                                </td>
                                <?php
                                $detailsExport .=  "</td>";
                                $detailsExport .=  "<td>";
                                ?>
                                <td>
                                    <?php
                                    $detailsExport .=  $indexes[$key]['show_value'];
                                    if ($indexes[$key]['type_field'] == 'input')
                                    {
                                        ?>
                                        <input type="text" name="<?php functions::xecho($key);?>" id="<?php functions::xecho($key);?>" value="<?php functions::xecho($indexes[$key]['show_value']);?>" <?php if (!isset($indexes[$key]['readonly']) || $indexes[$key]['readonly'] == true){ echo 'readonly="readonly" class="readonly"';}else if ($indexes[$key]['type'] == 'date'){echo 'onclick="showCalender(this);"';}?> size="40"  title="<?php functions::xecho($indexes[$key]['show_value']);?>" alt="<?php functions::xecho($indexes[$key]['show_value']);?>"   />
                                        <?php
                                    }
                                    else
                                    {?>
                                        <select name="<?php functions::xecho($key);?>" id="<?php functions::xecho($key);?>" >
                                            <option value=""><?php echo _CHOOSE;?>...</option>
                                            <?php
                                            for ($j = 0; $j < count($indexes[$key]['values']); $j ++)
                                            {?>
                                                <option value="<?php functions::xecho($indexes[$key]['values'][$j]['id']);?>" <?php
                                                if ($indexes[$key]['values'][$j]['id'] == $indexes[$key]['value']) {
                                                    echo 'selected="selected"';
                                                }?>><?php functions::xecho($indexes[$key]['values'][$j]['label']);?></option><?php
                                            }?>
                                        </select><?php
                                    }

                                    $detailsExport .=  "</td>";
                                    ?>
                                </td>
                                <?php
                                if ($i%2 == 1 && $i!=0) // impair
                                {
                                    $detailsExport .=  "</td>";
                                    ?>
                                    </tr>
                                    <?php
                                }
                                else
                                {
                                    if ($i+1 == count($indexes))
                                    {
                                        $detailsExport .= "<td  colspan='2'>&nbsp;</td></tr>";
                                        echo '<td  colspan="2">&nbsp;</td></tr>';
                                    }
                                }
                                $i++;
                            }
                            ?>
                            <?php
                            if ($core->is_module_loaded('tags') && ($core->test_service('tag_view', 'tags', false) == 1)) {
                                    include_once('modules/tags/templates/details/index.php');
                                    echo '<script>new Chosen($(\'tag_userform\'),{width: "95%", disable_search_threshold: 10, search_contains: true});</script>';
                            }

                            if ($core->is_module_loaded('thesaurus') && ($core->test_service('thesaurus_view', 'thesaurus', false) == 1))   
                            {  
                                require_once 'modules' . DIRECTORY_SEPARATOR . 'thesaurus'
                                . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
                                . 'class_modules_tools.php';
                                $thesaurus = new thesaurus();

                                $thesaurusListRes = array();

                                $thesaurusListRes = $thesaurus->getThesaursusListRes($s_id);

                                echo '<tr id="thesaurus_tr_label" >';
                                echo '<th align="left" class="picto" ><i class="fa fa-bookmark fa-2x" title="' . _THESAURUS . '"></i></th>';
                                echo '<td style="font-weight:bold;width:200px;">'._THESAURUS.'</td>';
                                echo '<td id="thesaurus_field" colspan="6"><select multiple="multiple" id="thesaurus" name="thesaurus[]" data-placeholder=" "';

                                if (!$core->test_service('add_thesaurus_to_res', 'thesaurus', false)) {
                                    echo 'disabled="disabled"';
                                }

                                echo '>';
                                if(!empty($thesaurusListRes)){
                                    foreach ($thesaurusListRes as $key => $value) {

                                        echo '<option title="'.functions::show_string($value['LABEL']).'" data-object_type="thesaurus_id" id="thesaurus_'.$value['ID'].'"  value="' . $value['ID'] . '"';
                                            echo ' selected="selected"'; 
                                        echo '>' 
                                            .  functions::show_string($value['LABEL']) 
                                            . '</option>';

                                    }
                                }

                                echo '</select> <i onclick="lauch_thesaurus_list(this);" class="fa fa-search" title="parcourir le thésaurus" aria-hidden="true" style="cursor:pointer;"></i></td>';
                                echo '</tr>';
                                echo '<div onClick="$(\'return_previsualise_thes\').style.display=\'none\';" id="return_previsualise_thes" style="cursor: pointer; display: none; border-radius: 10px; box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.4); padding: 10px; width: auto; height: auto; position: absolute; top: 0; left: 0; z-index: 999; color: #4f4b47; text-shadow: -1px -1px 0px rgba(255,255,255,0.2);background:#FFF18F;border-radius:5px;overflow:auto;">\';
                                                <input type="hidden" id="identifierDetailFrame" value="" />
                                            </div>';
                                echo '<script>new Chosen($(\'thesaurus\'),{width: "95%", disable_search_threshold: 10});</script>';
                                echo '<style>#thesaurus_chosen .chosen-drop{display:none;}</style>';

                                /*****************/
                            }

                        //Identifiant du courrier en cours
                        $idCourrier=$_GET['id'];
                        //Requete pour récupérer position_label
                        $stmt = $db->query("SELECT position_label FROM fp_fileplan_positions INNER JOIN fp_res_fileplan_positions 
                                    ON fp_fileplan_positions.position_id = fp_res_fileplan_positions.position_id
                                    WHERE fp_res_fileplan_positions.res_id=?",array($idCourrier));

                        while($res_fileplan= $stmt->fetchObject()){
                            if(!isset($positionLabel)){
                                $positionLabel=$res_fileplan->position_label;
                            }else{
                                $positionLabel=$positionLabel." / ".$res_fileplan->position_label;
                            }  
                        }

                        //Requete pour récuperer fileplan_label
                        $stmt = $db->query("SELECT fileplan_label FROM fp_fileplan INNER JOIN fp_res_fileplan_positions
                                    ON fp_fileplan.fileplan_id = fp_res_fileplan_positions.fileplan_id
                                    WHERE fp_res_fileplan_positions.res_id=? AND fp_fileplan.user_id = ?", array($idCourrier,$_SESSION['user']['UserId']));
                        $res2 = $stmt->fetchObject();
                        $fileplanLabel=$res2->fileplan_label;
                        $planClassement= $fileplanLabel." / ".$positionLabel;
                    ?>
             
                    <?php if ($core->is_module_loaded('fileplan') && ($core->test_service('put_doc_in_fileplan', 'fileplan', false) == 1) && $fileplanLabel <> "") { ?>
                            <tr class="col">
                                <th align="left" class="picto">
                                    <i class="fa fa-bookmark fa-2x" title="<?php echo _FILEPLAN;?>"></i>
                                </th>
                                <td align="left" width="200px">
                                    <?php echo _FILEPLAN;?> :
                                </td>
                                <td colspan="6">
                                    <input type="text" class="readonly" readonly="readonly" style="width:95%;" value="<?php functions::xecho($planClassement);?>" size="110"  />
                                </td>
                            </tr>
                    <?php } ?>
                        </table>
                        <?php  } ?>
                    </div>
							</form><br><br>
         
                    <?php
                    if ($core->is_module_loaded('tags') && ($core->test_service('tag_view', 'tags', false) == 1)) {
                            include_once('modules/tags/templates/details/index.php');
                    }
        }
		
        ?>
                </dd>
                <?php
                //SERVICE TO VIEW TECHNICAL INDEX
                if ($viewTechnicalInfos) {
                    include_once('apps/' . $_SESSION['config']['app_id'] . '/view_technical_infos.php');
                }
                //$core->execute_app_services($_SESSION['app_services'], 'details.php');
                $detailsExport .= "<h2>"._NOTES."</h2>";
                $detailsExport .= "<table cellpadding='4' cellspacing='0' border='1' width='100%'>";
                $detailsExport .= "<tr height='130px'>";
                $detailsExport .= "<td width='15%'>";
                $detailsExport .= "<h3>"._NOTES_1."</h3>";
                $detailsExport .= "</td>";
                $detailsExport .= "<td width='85%'>";
                $detailsExport .= "&nbsp;";
                $detailsExport .= "</td>";
                $detailsExport .= "</tr>";
                $detailsExport .= "<tr height='130px'>";
                $detailsExport .= "<td width='15%'>";
                $detailsExport .= "<h3>"._NOTES_2."</h3>";
                $detailsExport .= "</td>";
                $detailsExport .= "<td width='85%'>";
                $detailsExport .= "&nbsp;";
                $detailsExport .= "</td>";
                $detailsExport .= "</tr>";
                $detailsExport .= "<tr height='130px'>";
                $detailsExport .= "<td width='15%'>";
                $detailsExport .= "<h3>"._NOTES_3."</h3>";
                $detailsExport .= "</td>";
                $detailsExport .= "<td width='85%'>";
                $detailsExport .= "&nbsp;";
                $detailsExport .= "</td>";
                $detailsExport .= "</tr>";
                $detailsExport .= "</table>";
                if ($core->is_module_loaded('entities'))
                {
                    $category = $data['category_id']['value'];
                    $detailsExport .= "<h2>"._DIFF_LIST."</h2>";
                    ?>
                    <dt class="fa fa-gear" style="font-size:2em;padding-left: 15px;padding-right: 15px;" title="<?php echo _DIFF_LIST;?>"> <sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt>
                    <dd>
                        <?php
                            $onlyCC = '';
                            if ($core->test_service('update_list_diff_in_details', 'entities', false) || $core->test_service('add_copy_in_indexing_validation', 'entities', false)) {
                                if($core->test_service('add_copy_in_indexing_validation', 'entities', false) && $_SESSION['user']['UserId'] != 'superadmin'){
                                    $onlyCC = '&only_cc';
                                }
                                echo '<br />';
                                echo '<div style="text-align:center;">';
                                
                                echo '<input type="button" class="button" title="'._UPDATE_LIST_DIFF.'" value="'._UPDATE_LIST_DIFF.'" onclick="window.open(\''
                                    .$_SESSION['config']['businessappurl']
                                    .'index.php?display=true&module=entities&page=manage_listinstance&cat='.$category.'&origin=details'.$onlyCC.'\', \'\', \'scrollbars=yes,menubar=no,toolbar=no,status=no,resizable=yes,width=1280,height=980,location=no\');" />';
                                ?>
                                <input type="button" class="button" style="display:none;" id="save_list_diff" onClick="saveListDiff('listinstance', '<?php 
                                    functions::xecho($_SESSION['tablename']['ent_listinstance']);?>', '<?php 
                                    functions::xecho($coll_id);?>', '<?php 
                                    functions::xecho($s_id);?>','<?php 
                                    functions::xecho($_SESSION['user']['UserId']);?>', '<?php 
                                    echo true;?>','<?php 
                                    echo false;?>');$('div_diff_list_message').show();" value="<?php 
                                    echo _STORE_DIFF_LIST;?>" />
                                </div>
                                <?php
                                }
                                ?>
                            <br />
                            <div id="div_diff_list_message" style="color:red;text-align: center;"></div>
                            <br />
                            
                        <div id="diff_list_div">
                            <?php
                            require_once('modules/entities/class/class_manage_listdiff.php');
                            $diff_list = new diffusion_list();
                            $_SESSION['details']['diff_list'] = $diff_list->get_listinstance($s_id, false, $coll_id);
                            $_SESSION['details']['difflist_type'] = $diff_list->get_difflist_type($_SESSION['details']['diff_list']['difflist_type']);
                            # Include display of list
                            $roles = $diff_list->list_difflist_roles();
                            $difflist = $_SESSION['details']['diff_list'];

                            require_once 'modules/entities/difflist_display.php';  
                            
                            //if (($core->test_service('update_list_diff_in_details', 'entities', false)) && (!$core->test_service('add_copy_in_process', 'entities', false))) {
                            ?>
                        </div>
                        <div>
                
                            <br/>
                            <span class="diff_list_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor='pointer';" onclick="new Effect.toggle('diff_list_history_div', 'blind', {delay:0.2});whatIsTheDivStatus('diff_list_history_div', 'divStatus_diff_list_history_div');return false;">
                                <span id="divStatus_diff_list_history_div" style="color:#1C99C5;"><i class="fa fa-plus-square-o"></i></span>
                                <b>&nbsp;<small><?php echo _DIFF_LIST_HISTORY;?></small></b>
                            </span>
                        </div>
                        
                        <br />
                        <div id="diff_list_history_div" style="display:none">
                            <?php 
                            $diffListType = 'entity_id';
                            require_once('modules/entities/difflist_history_display.php');?>
                        </div>
                    </dd>
                <?php
                }
				
				if ($core->test_service('print_folder_doc', 'visa', false))
                {
					require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
					. "class" . DIRECTORY_SEPARATOR
					. "class_modules_tools.php";
                    ?>
                    <dt class="fa fa-print" style="font-size:2em;padding-left: 15px;padding-right: 15px;" title="<?php echo _PRINTFOLDER;?>"> <sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt>
                    <dd>
                        <br/>
						<?php
                        $print_folder = new visa();
						echo $print_folder->showPrintFolder($coll_id, $table, $_SESSION['doc_id']);
						?>
                    </dd>
                <?php
                }
				
				if ($core->is_module_loaded('visa')) {
					require_once "modules" . DIRECTORY_SEPARATOR . "visa" . DIRECTORY_SEPARATOR
					. "class" . DIRECTORY_SEPARATOR
					. "class_modules_tools.php";
					
                        $visa = new visa();
                        if($visa->nbVisa($s_id,$coll_id) == 0){
                            $style = 'font-size:2em;color:#9AA7AB;padding-left: 15px;padding-right: 15px;';
                        }else{
                            $style = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                        }
                    ?>
				<dt id="onglet_circuit" class="fa fa-certificate" style="<?php echo $style; ?>" title="<?php echo _VISA_WORKFLOW;?>"> <sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt><dd id="page_circuit" style="overflow-x: hidden;">
				<h2><?php echo _VISA_WORKFLOW;?></h2>
				<?php
				$modifVisaWorkflow = false;
				if ($core->test_service('config_visa_workflow', 'visa', false)) {
					$modifVisaWorkflow = true;
				}
				?>
				<div class="error" id="divError" name="divError"></div>
				<div style="text-align:center;">
				<?php
				echo $visa->getList($s_id, $coll_id, $modifVisaWorkflow, 'VISA_CIRCUIT', '','Y');
				?>
				</div>
				
				<br/>
				<br/>
					<br/>
					<span class="diff_list_visa_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor='pointer';" onclick="new Effect.toggle('diff_list_visa_history_div', 'blind', {delay:0.2});whatIsTheDivStatus('diff_list_visa_history_div', 'divStatus_diff_list_visa_history_div');return false;">
						<span id="divStatus_diff_list_visa_history_div" style="color:#1C99C5;"><<</span>
						<b>&nbsp;<small><?php echo _DIFF_LIST_VISA_HISTORY;?></small></b>
					</span>

					<div id="diff_list_visa_history_div" style="display:none">
						<?php
						//$return_mode = true;
						$diffListType = 'VISA_CIRCUIT';
						require_once('modules/entities/difflist_visa_history_display.php');
						?>
									
					</div>
				</dd>
				<?php
				 }
				 
				if ($core->is_module_loaded('avis')) {
                    require_once "modules" . DIRECTORY_SEPARATOR . "avis" . DIRECTORY_SEPARATOR
                    . "class" . DIRECTORY_SEPARATOR
                    . "avis_controler.php";

                    $avis = new avis_controler();
                    if($avis->nbAvis($s_id,$coll_id) == 0){
                        $style = 'font-size:2em;color:#9AA7AB;padding-left: 15px;padding-right: 15px;';
                    }else{
                        $style = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                    }
                    ?>
                <dt id="onglet_circuit" class="fa fa-check-square" style="<?php echo $style; ?>" title="<?php echo _AVIS_WORKFLOW;?>"> <sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt><dd id="page_circuit_avis" style="overflow-x: hidden;">
                <h2><?php echo _AVIS_WORKFLOW;?></h2>
                <?php
                if ($core->test_service('config_avis_workflow_in_detail', 'avis', false)) {
                    $modifAvisWorkflow = true;
                }
                ?>
                <div class="error" id="divError" name="divError"></div>
                <div style="text-align:center;">
                <?php
                echo $avis->getList($s_id, $coll_id, $modifAvisWorkflow, 'AVIS_CIRCUIT', '','Y');
                ?>
                </div>
                
                <br/>
                <br/>
                <br/>
                    <span class="diff_list_avis_history" style="width: 90%; cursor: pointer;" onmouseover="this.style.cursor='pointer';" onclick="new Effect.toggle('diff_list_avis_history_div', 'blind', {delay:0.2});whatIsTheDivStatus('diff_list_avis_history_div', 'divStatus_diff_list_avis_history_div');return false;">
                        <span id="divStatus_diff_list_avis_history_div" style="color:#1C99C5;"><<</span>
                        <b>&nbsp;<small><?php echo _DIFF_LIST_AVIS_HISTORY;?></small></b>
                    </span>

                    <div id="diff_list_avis_history_div" style="display:none">
                        <?php
                        //$return_mode = true;
                        $diffListType = 'AVIS_CIRCUIT';
                        require_once('modules/avis/difflist_avis_history_display.php');
                        ?>
                                    
                    </div>
                </dd>
                <?php
                }

                //$detailsExport .= "<h2>"._PROCESS."</h2>";
                $nb_attach = '';
                if ($core->is_module_loaded('attachments'))
                {
                    $countAttachments = "SELECT res_id, creation_date, title, format FROM " 
                        . $_SESSION['tablename']['attach_res_attachments'] 
                        . " WHERE res_id_master = ? and coll_id = ? and status <> 'DEL' and attachment_type NOT IN ('response_project','signed_response','outgoing_mail_signed','converted_pdf','outgoing_mail','print_folder') and (status <> 'TMP' or (typist = ? and status = 'TMP'))";
                    $db = new Database();
                    $stmt = $db->query($countAttachments, array($_SESSION['doc_id'], $_SESSION['collection_id_choice'], $_SESSION['user']['UserId']));
                    if ($stmt->rowCount() > 0) {
                        $nb_attach = $stmt->rowCount();
                        $class = 'nbRes';
                        $style = 'font-size: 10px;';
                        $style2 = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                    } else {
                        $nb_attach = '0';
                        $class = 'nbResZero';
                        $style = 'display:none;font-size: 10px;';
                        $style2 = 'color:#9AA7AB;font-size:2em;padding-left: 15px;padding-right: 15px;';
                    }
                }
                ?>
                <dt class="fa fa-paperclip" id="other_attachments_tab" style="<?php echo $style2; ?>" title="<?php echo _ATTACHMENTS; ?>"> <sup><span id="nb_attach" class="<?php echo $class; ?>" style="<?php echo $style; ?>"><?php echo $nb_attach; ?></span></sup></dt>
                <dd id="other_attachments">
                    <?php
                    if ($core->is_module_loaded('attachments'))
                    {

		            require 'modules/templates/class/templates_controler.php';
		            $templatesControler = new templates_controler();
		            $templates = array();
		            $templates = $templatesControler->getAllTemplatesForProcess($data['destination']['value']);

                        $detailsExport .= "<h3>"._ATTACHED_DOC." : </h3>";
                        $selectAttachments = "SELECT res_id, creation_date, title, format FROM ".$_SESSION['tablename']['attach_res_attachments']
                                ." WHERE res_id_master = ? and coll_id = ? and status <> 'DEL' and attachment_type NOT IN ('response_project','outgoing_mail_signed', 'signed_response','converted_pdf','outgoing_mail','print_folder') and (status <> 'TMP' or (typist = ? and status = 'TMP'))";
                        $stmt = $db->query($selectAttachments, array($_SESSION['doc_id'], $_SESSION['collection_id_choice'], $_SESSION['user']['UserId']));

                        ?>
                        <div>
				<center>
					<?php
                /*if ($core->is_module_loaded('templates') && (!isset($_SESSION['current_basket']['id']) && $core->test_service('edit_attachments_from_detail', 'attachments', false)) || isset($_SESSION['current_basket']['id'])) { */
                if ($core->is_module_loaded('templates') && ($core->test_service('edit_attachments_from_detail', 'attachments', false))) {

                        ?><input type="button" name="attach" id="attach" class="button" value="<?php echo _CREATE_PJ;?>"
                             onclick="showAttachmentsForm('<?php echo $_SESSION['config']['businessappurl']
                            . 'index.php?display=true&module=attachments&page=attachments_content&fromDetail=create';?>','98%','auto')" />
             
                <?php } ?>
                </center>
                    <iframe name="list_attach" id="list_attach" src="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=attachments&page=frame_list_attachments&view_only=true&load&attach_type_exclude=response_project,signed_response,outgoing_mail_signed,converted_pdf,outgoing_mail,print_folder&fromDetail=attachments" frameborder="0" width="100%" style="height:510px"></iframe>
                    <script type="text/javascript">
                        var height = $('other_attachments').offsetHeight;
                        $('list_attach').style.height = height+"px";
                    </script>
                        </div>
                        <?php
 					}
                    $detailsExport .= "<br><br><br>";
                    ?>
                </dd>
                <?php
                        
                        $countAttachments = "SELECT res_id, creation_date, title, format FROM " 
                                . $_SESSION['tablename']['attach_res_attachments'] 
                                . " WHERE res_id_master = ? and coll_id = ? and status <> 'DEL' and (attachment_type = 'response_project' or attachment_type = 'outgoing_mail_signed' or attachment_type = 'outgoing_mail' or attachment_type = 'signed_response') and (status <> 'TMP' or (typist = ? and status = 'TMP'))";
                            $stmt = $db->query($countAttachments, array($_SESSION['doc_id'], $_SESSION['collection_id_choice'], $_SESSION['user']['UserId']));
                            if ($stmt->rowCount() > 0) {
                                $nb_rep = $stmt->rowCount();
                                $class = 'nbRes';
                                $style = 'font-size: 10px;';
                                $style2 = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                            }else{
                                $nb_rep = '0';
                                $class = 'nbResZero';
                                $style = 'display:none;font-size: 10px;';
                                $style2 = 'color:#9AA7AB;font-size:2em;padding-left: 15px;padding-right: 15px;';
                            }
                    
                        ?>
                <dt id="onglet_rep" class="fa fa-mail-reply" style="<?php echo $style2; ?>" title="<?php echo _DONE_ANSWERS; ?>"> <sup><span id="answer_number" style="<?php echo $style; ?>" class="<?php echo $class; ?>"><?php echo $nb_rep; ?></span></sup></dt>
                <dd id="page_rep">
                    <center>
                        <?php
                    if ($core->is_module_loaded('templates') && ($core->test_service('edit_attachments_from_detail', 'attachments', false))) {
                            ?><input type="button" name="attach" id="attach" class="button" value="<?php echo _CREATE_PJ;?>"
                                 onclick="showAttachmentsForm('<?php echo $_SESSION['config']['businessappurl']
                                . 'index.php?display=true&module=attachments&page=attachments_content&fromDetail=create';?>','98%','auto')" />

                    <?php } ?>
                    </center>
                    <iframe name="list_attach" id="list_attach2" src="<?php echo
                     $_SESSION['config']['businessappurl'] . 'index.php?display=true&module=attachments&page=frame_list_attachments&load&attach_type=response_project,outgoing_mail_signed,signed_response,outgoing_mail&fromDetail=response';?>" 
                    frameborder="0" width="100%" height="600px">
                    </iframe>
                    <script type="text/javascript">
                        var height = $('page_rep').offsetHeight-70;
                        $('list_attach2').style.height = height+"px";
                    </script>
                </dd>

                <?php
                    //SERVICE TO VIEW DOC HISTORY
                    if ($viewDocHistory) {
                ?>
                <dt class="fa fa-line-chart" style="font-size:2em;padding-left: 15px;padding-right: 15px;" title="<?php echo _DOC_HISTORY;?>"> <sup><span style="font-size: 10px;display: none;" class="nbResZero"></span></sup></dt>
                <dd>
                    <!--<h2><?php echo _HISTORY;?></h2>-->
                    <h2><?php echo _WF ;?></h2>
                    <iframe src="<?php echo $_SESSION['config']['businessappurl'];
                    ?>index.php?display=true&dir=indexing_searching&page=document_workflow_history&id=<?php
                    functions::xecho($s_id);?>&coll_id=<?php functions::xecho($coll_id);?>&load&size=full" name="workflow_history_document" width="100%" 
                    height="530px" align="left" scrolling="yes" frameborder="0" id="workflow_history_document"></iframe>
                    <br/> 
                    <br/> 
              
                    <span style="cursor: pointer;" onmouseover="this.style.cursor='pointer';" onclick="new Effect.toggle('history_document', 'blind', {delay:0.2});whatIsTheDivStatus('history_document', 'divStatus_all_history_div');return false;">
                        <span id="divStatus_all_history_div" style="color:#1C99C5;"><<</span>
                        <b>&nbsp;<?php echo _ALL_HISTORY;?></b>
                    </span>
                    <iframe src="<?php echo $_SESSION['config']['businessappurl'];
                    ?>index.php?display=true&dir=indexing_searching&page=document_history&id=<?php
                    functions::xecho($s_id);?>&coll_id=<?php functions::xecho($coll_id);?>&load&size=full" name="history_document" width="100%" 
                    height="620px" align="left" scrolling="yes" frameborder="0" id="history_document" style="display:none;"></iframe>
                </dd>
                <?php
                    }
                if ($core->is_module_loaded('notes')) {
                    require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
                        . "class" . DIRECTORY_SEPARATOR
                        . "class_modules_tools.php";
                    $notes_tools    = new notes();
                    
                    //Count notes
                    $nbr_notes = $notes_tools->countUserNotes($s_id, $coll_id);
                    // if ($nbr_notes > 0 ) $nbr_notes = ' ('.$nbr_notes.')';  else $nbr_notes = '';
                    if ($nbr_notes == 0){
                        $class = 'nbResZero';
                        $style = 'display:none;font-size: 10px;';
                        $style2 = 'color:#9AA7AB;font-size:2em;padding-left: 15px;padding-right: 15px;';
                    }else{
                        $class = 'nbRes';
                        $style = 'font-size: 10px;';
                        $style2 = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                    }
                    //Notes iframe
                    ?>
                    <dt class="fa fa-pencil" style="<?php echo $style2; ?>" title="<?php echo _NOTES; ?>"> <sup><span id="nb_note" style="<?php echo $style; ?>" class="<?php echo $class; ?>"><?php echo $nbr_notes; ?></span></sup></dt>
                    <dd>
                        <!--<h2><?php echo _NOTES;?></h2>-->
                        <iframe name="list_notes_doc" id="list_notes_doc" src="<?php
                            echo $_SESSION['config']['businessappurl'];
                            ?>index.php?display=true&module=notes&page=notes&identifier=<?php 
                            functions::xecho($s_id);?>&origin=document&coll_id=<?php functions::xecho($coll_id);?>&load&size=full" 
                            frameborder="0" scrolling="yes" width="99%" height="570px"></iframe>
                    </dd> 
                    <?php
                }
                
                //CASES
                if ($core->is_module_loaded('cases') == true)
                {
                    if ($res->case_id <> '') {
                        $style = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                    }else{
                        $style = 'font-size:2em;color:#9AA7AB;padding-left: 15px;padding-right: 15px;';
                    }
                    ?>
                    <dt class="fa fa-suitcase" style="<?php echo $style; ?>" title="<?php echo _CASE;?>"> <sup><span class="nbResZero" style="display: none;"></span></sup></dt>
                    <dd>
                    <?php
                        include('modules'.DIRECTORY_SEPARATOR.'cases'.DIRECTORY_SEPARATOR.'including_detail_cases.php');
                        if ($core->test_service('join_res_case', 'cases',false) == 1) {
                        ?><div align="center">
                            <input type="button" class="button" name="back_welcome" id="back_welcome" value="<?php if ($res->case_id<>'') echo _MODIFY_CASE; else echo _JOIN_CASE;?>" onclick="window.open('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=search_adv_for_cases&searched_item=res_id&searched_value=<?php functions::xecho($s_id);?>','', 'scrollbars=yes,menubar=no,toolbar=no,resizable=yes,status=no,width=1020,height=710');"/>
                            <?php if ($res->case_id<>''){ ?>
                                <input type="button" class="button" name="unlink_case" id="unlink_case" value="<?php echo _UNLINK_CASE;?>" onclick="if(confirm('<?php echo addslashes(_UNLINK_CASE);?> ?')){unlink_case('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=unlink_case','<?php functions::xecho($res->case_id);?>','<?php functions::xecho($res->res_id);?>');}"/>

                            <?php } ?>
                            </div>
                        <?php
                        }
                        ?>
                    </dd>
                    <?php
                }
				
                //SENDMAILS                
                if ($core->test_service('sendmail', 'sendmail', false) === true) {
                    require_once "modules" . DIRECTORY_SEPARATOR . "sendmail" . DIRECTORY_SEPARATOR
                        . "class" . DIRECTORY_SEPARATOR
                        . "class_modules_tools.php";
                    $sendmail_tools    = new sendmail();
                     //Count mails
                    $nbr_emails = $sendmail_tools->countUserEmails($s_id, $coll_id);
                    if ($nbr_emails > 0 ){
                        $class = 'nbRes';
                        $style = 'font-size: 10px;';
                        $style2 = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                    } else {
                        $class = 'nbResZero';
                        $style = 'display:none;font-size: 10px;';
                        $style2 = 'color:#9AA7AB;font-size:2em;padding-left: 15px;padding-right: 15px;';
                    } 
                   
                    ?>
                    <dt class="fa fa-envelope" style="<?php echo $style2; ?>" title="<?php echo _SENDED_EMAILS;?>"> <sup><span style="<?php echo $style; ?>" class="<?php echo $class; ?>"><?php echo $nbr_emails; ?></span></sup></dt>
                    <dd>
                    <?php
                    //Emails iframe
                    echo $core->execute_modules_services(
                        $_SESSION['modules_services'], 'details', 'frame', 'sendmail', 'sendmail'
                    );
                    ?>
                    </dd>
                <?php
                }
                
                //VERSIONS
                $versionTable = $security->retrieve_version_table_from_coll_id(
                    $coll_id
                );
                $selectVersions = "SELECT res_id FROM "
                    . $versionTable . " WHERE res_id_master = ? and status <> 'DEL' order by res_id desc";

                $stmt = $db->query($selectVersions, array($s_id));
                $nb_versions_for_title = $stmt->rowCount();
                $lineLastVersion = $stmt->fetchObject();
                $lastVersion = $lineLastVersion->res_id;
                if ($lastVersion <> '') {
                    $objectId = $lastVersion;
                    $objectTable = $versionTable;
                } else {
                    $objectTable = $security->retrieve_table_from_coll(
                        $coll_id
                    );
                    $objectId = $s_id;
                    $_SESSION['cm']['objectId4List'] = $s_id;
                }
                if ($nb_versions_for_title == 0) {
                    $extend_title_for_versions = '0';
                    $class="nbResZero";
                    $style = 'display:none;font-size: 10px;';
                    $style2 = 'color:#9AA7AB;font-size:2em;padding-left: 15px;padding-right: 15px;';
                } else {
                    $extend_title_for_versions = $nb_versions_for_title;
                    $class="nbRes";
                    $style = 'font-size: 10px;';
                    $style2 = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                }
                $_SESSION['cm']['resMaster'] = '';
                ?>
                <dt  class="fa fa-code-fork" style="<?php echo $style2; ?>" title="<?php echo _VERSIONS; ?>">
                    <?php echo ' <sup><span id="nbVersions" class="'.$class.'" style="'.$style.'">'
                        . $extend_title_for_versions . '</span></sup>'; ?>
                </dt>
                <dd>
                    <div class="error" id="divError" name="divError"></div>
                    <div style="text-align:center;">
                        <a href="<?php
                            echo $_SESSION['config']['businessappurl'];
                            ?>index.php?display=true&dir=indexing_searching&page=view_resource_controler&id=<?php
                            functions::xecho($s_id);
                            ?>&original" target="_blank">
                            <i class="fa fa-download fa-2x" title="<?php
                                echo _VIEW_ORIGINAL;
                                ?>"></i>&nbsp;<?php
                            echo _VIEW_ORIGINAL;
                            ?></a> &nbsp;|&nbsp;
                        <?php
                        if ($addNewVersion) {
                            $_SESSION['cm']['objectTable'] = $objectTable;
                            ?>
                            <div id="createVersion" style="display: inline;"></div>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="loadVersions"></div>
                    <script language="javascript">
                        showDiv("loadVersions", "nbVersions", "createVersion", "<?php
                            echo $_SESSION['config']['businessappurl'];
                            ?>index.php?display=false&module=content_management&page=list_versions");
                    </script>
                </dd>
                <?php 
                //############# NOTIFICATIONS ##############
				/*
                $extend_title_for_notifications = 0;
                ?>
                <dt>
                    <?php
                    echo _NOTIFS . ' (' . $extend_title_for_notifications . ')';
                    ?>
                </dt>
                <dd>
                    <div class="error" id="divError" name="divError"></div>
                    <div style="text-align:center;">
                        test
                    </div>
                </dd>
                <?php 
				*/
				$Class_LinkController = new LinkController();
                
                $nbLink = $Class_LinkController->nbDirectLink(
                    $_SESSION['doc_id'],
                    $_SESSION['collection_id_choice'],
                    'all'
                );
                if($nbLink == 0){
                    $class="nbResZero";
                    $style = 'display:none;font-size: 10px;';
                    $style2 = 'color:#9AA7AB;font-size:2em;padding-left: 15px;padding-right: 15px;';
                }else{
                    $class="nbRes";
                    $style = 'font-size: 10px;';
                    $style2 = 'font-size:2em;padding-left: 15px;padding-right: 15px;';
                }
                $Links = '';

                //if ($nbLink > 0) {
                    $Links .= '<dt class="fa fa-link" style="'.$style2.'" title="'._LINK_TAB.'">';
                        $Links .= ' <sup><span id="nbLinks" class="'.$class.'" style="'.$style.'">';
                        $Links .= $nbLink;
                        $Links .= '</span></sup>';
                    $Links .= '</dt>';
                    $Links .= '<dd>';
                        // $Links .= '<h2>';
                            // $Links .= _LINK_TAB;
                        // $Links .= '</h2>';
                        if ($core->test_service('add_links', 'apps', false)) {
                            include_once 'apps/'.$_SESSION['config']['app_id'].'/add_links.php';
                        }
                        $Links .= '<div id="loadLinks">';

                            $nbLinkDesc = $Class_LinkController->nbDirectLink(
                                $_SESSION['doc_id'],
                                $_SESSION['collection_id_choice'],
                                'desc'
                            );
                            if ($nbLinkDesc > 0) {
                                $Links .= $Class_LinkController->formatMap(
                                    $Class_LinkController->getMap(
                                        $_SESSION['doc_id'],
                                        $_SESSION['collection_id_choice'],
                                        'desc'
                                    ),
                                    'desc'
                                );
                                $Links .= '<br />';
                            }

                            $nbLinkAsc = $Class_LinkController->nbDirectLink(
                                $_SESSION['doc_id'],
                                $_SESSION['collection_id_choice'],
                                'asc'
                            );
                            if ($nbLinkAsc > 0) {
                                //$Links .= '<i class="fa fa-long-arrow-left fa-2x"></i>';
                                $Links .= $Class_LinkController->formatMap(
                                    $Class_LinkController->getMap(
                                        $_SESSION['doc_id'],
                                        $_SESSION['collection_id_choice'],
                                        'asc'
                                    ),
                                    'asc'
                                );
                                $Links .= '<br />';
                            }
                        $Links .= '</div>';

                    $Links .= '</dd>';
                //}

                echo $Links;
                ?>
            </dl>
    <?php
}
?> 
</div>
</div>
<script type="text/javascript">
var item  = $('details_div');
    <?php if($_SESSION['indexation'] == true && $category == 'outgoing'){ 
            $selectAttachments = "SELECT attachment_type FROM res_view_attachments"
                                ." WHERE res_id_master = ? and coll_id = ? and status <> 'DEL' and attachment_type = 'outgoing_mail'";
            $stmt = $db->query($selectAttachments, array($_SESSION['doc_id'], $_SESSION['collection_id_choice']));
            
            if(!$stmt->fetchObject()){

            
        ?>

        if($('attach')){
            document.getElementById('attach').setAttribute("onClick","showAttachmentsForm('<?php echo $_SESSION['config']['businessappurl']
                                . 'index.php?display=true&module=attachments&page=attachments_content&fromDetail=create&cat=outgoing';?>','98%','auto')");
  
            var tabricator1 = new Tabricator('tabricator1', 'DT', 'onglet_rep');
            $('attach').click();
        }
        

    <?php }else{ ?>
        document.getElementById('attach').setAttribute("onClick","showAttachmentsForm('<?php echo $_SESSION['config']['businessappurl']
                                . 'index.php?display=true&module=attachments&page=attachments_content&fromDetail=create';?>','98%','auto')");
        var tabricator1 = new Tabricator('tabricator1', 'DT');
    <?php }
    }else{ ?>
        var tabricator1 = new Tabricator('tabricator1', 'DT');
    <?php } ?>
    
    if (item) {
        item.style.display='block';
    }
</script>
<?php
$detailsExport .= "</body></html>";
$_SESSION['doc_convert'] = array();
$_SESSION['doc_convert']['details_result'] = $detailsExport;
$core = new core_tools();

$_SESSION['info_basket'] = '';
$_SESSION['info'] = '';

/*if ($printDetails) {
    $Fnm = $_SESSION['config']['tmppath']. '/export_details_'
        . $_SESSION['user']['UserId'] . '_export.html';
    if (file_exists($Fnm)) {
        unlink($Fnm);
    }
    $inF = fopen($Fnm,"w");
    fwrite($inF, $detailsExport);
    fclose($inF);
}*/
