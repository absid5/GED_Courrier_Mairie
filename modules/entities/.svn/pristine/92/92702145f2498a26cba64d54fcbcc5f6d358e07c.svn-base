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

$db = new Database();
$core = new core_tools();
$core->load_lang();
$diffList = new diffusion_list();

$objectType = $_REQUEST['objectType'];
$objectId = $_REQUEST['objectId'];
$origin = $_REQUEST['origin'];
$category = $_REQUEST['category'];

// Get listmodel_parameters
$_SESSION[$origin]['difflist_type'] = $diffList->get_difflist_type($objectType);

if ($objectId <> '') {
    $_SESSION[$origin]['difflist_object']['object_id'] = $objectId;
    if ($objectType == 'entity_id') {
        $query = "SELECT entity_label FROM entities WHERE entity_id = ?";
        $stmt = $db->query($query,array($objectId));
        $res = $stmt->fetchObject();
        if ($res->entity_label <> '') {
            $_SESSION[$origin]['difflist_object']['object_label'] = $res->entity_label;
        }
    }
}

// Fill session with listmodel
$_SESSION[$origin]['diff_list'] = $diffList->get_listmodel($objectType, $objectId);
//Permet de bloquer la liste diffusion avec celle de l'utilisateur qui enregistre le courrier. Si il change le service expéditeur la liste de diffusion ne changera pas.
// if($category == 'outgoing' && $origin == 'indexing'){
//     $_SESSION[$origin]['diff_list']['dest']['users'] = array();
//     $diffListOutgoing = array(
//         'user_id' => $_SESSION['user']['UserId'],
//         'lastname' => $_SESSION['user']['LastName'],
//         'firstname' => $_SESSION['user']['FirstName'],
//         'entity_id' => $_SESSION['user']['entities'][0]['ENTITY_ID'],
//         'entity_label' => $_SESSION['user']['entities'][0]['ENTITY_LABEL'],
//         'visible' => 'Y',
//         'process_comment' => ''
//     );

//     $_SESSION[$origin]['diff_list']['dest']['users'][]=$diffListOutgoing;
//     $_SESSION[$origin]['diff_list']['copy'] = array();
// }

$_SESSION[$origin]['diff_list']['difflist_type'] = $_SESSION[$origin]['diff_list']['object_type'];
$roles = $diffList->list_difflist_roles();
$difflist = $_SESSION[$origin]['diff_list'];

$content = '';
if (! $onlyCC) {
    if (isset($_SESSION['validStep']) && $_SESSION['validStep'] == 'ok') {
        $content .= "";
    } 
}

# Get content from buffer of difflist_display 
ob_start();
require_once 'modules/entities/difflist_display.php';
$content .= str_replace(array("\r", "\n", "\t"), array("", "", ""), ob_get_contents());
ob_end_clean();

$labelButton = _UPDATE_LIST_DIFF;
$arg = '&mode=up';

if( $core->test_service('add_copy_in_indexing_validation', 'entities', false) && $origin == 'indexing' ){
    $onlyCC = true;
}

if ($onlyCC) {
    $arg .= '&only_cc';
}
$content_standard = '<center><b>' . _DIFF_LIST . '</b> | ';
$content_standard .= '<span class="button" >';
$content_standard .= '<small><input type="button" style="margin-top:0px;" class="button" title="'.$labelButton.'" value="'.$labelButton.'" '
         . 'onclick="window.open(\''
         . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
         . '&module=entities&page=manage_listinstance&cat='.$category.'&origin=' . $origin . $arg
         . '\', \'\', \'scrollbars=yes,menubar=no,toolbar=no,status=no,'
         . 'resizable=yes,width=1280,height=800,location=no\');"/></small>';
$content_standard .= '</span></center>';

echo "{status : 0, div_content : '" . addslashes($content_standard . $content . '<br>') 
    . "', div_content_action : '" . addslashes($content) . "'}";
exit();
