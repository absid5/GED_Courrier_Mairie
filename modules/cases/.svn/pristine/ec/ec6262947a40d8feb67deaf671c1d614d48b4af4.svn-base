<?php
/**
* File : details_cases.php
*
* Detailed informations on an selected cases
*
* @package  Maarch Entreprise 1.0
* @version 1.0
* @since 10/2005
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*/
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$detailsExport = '';
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl']
           . 'index.php?page=details&dir=indexing_searching'
           . '&id=' . $_REQUEST['id'];
if (isset($_REQUEST['coll_id'])) {
    $page_path .= '&coll_id=' . $_REQUEST['coll_id'];
}
$page_label = _DETAILS_CASES;
$page_id = "details_cases";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);

if (isset($_GET['id'])) {
    $_SESSION['cases']['actual_case_id'] = $_GET['id'];
}

?>
<div id="details_div" style="display:none;">
    <h1 class="titdetail">
        <i class="fa fa-info-circle fa-2x"></i>&nbsp;<?php echo _CASE_DETAIL." ".strtolower(_NUM);?>
        <?php functions::xecho($_SESSION['cases']['actual_case_id']);?> <span></span>
    </h1>
    <div id="inner_content" class="clearfix">
        <dl id="tabricator1">
            <?php $detailsExport .= "<h1>"._DOCUMENTS_LIST_IN_THIS_CASE."</h1>";?>

            <dt><?php echo _INDEX_CASES;?></dt>
            <dd>
                <iframe name="index_cases" id="index_cases" src="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=detail_index_cases" frameborder="0" width="100%" height="520px"></iframe>
            </dd>
            <dt><?php echo _DOCUMENTS_LIST_IN_THIS_CASE;?></dt>
            <dd>
                <h2><?php echo _DOCUMENTS_LIST_IN_THIS_CASE;?></h2>
                <iframe name="list_document" id="list_document" src="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=cases_documents_list" frameborder="0" width="100%" height="520px"></iframe>
            </dd>
            <dt><?php echo _NOTES_FOR_THIS_CASES;?></dt>
            <dd>
                    <h2><?php echo _NOTES_FOR_THIS_CASES;?></h2>
                    <iframe name="list_notes" id="list_notes" src="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=cases_notes_list&size=full" frameborder="0" width="100%" height="520px"></iframe>
            </dd>
            <dt><?php echo _HISTORY_CASES;?></dt>
            <dd>
                    <h2><?php echo _HISTORY_CASES;?></h2>
                    <iframe name="history_cases" id="history_cases" src="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=cases&page=cases_history_list" frameborder="1" width="100%" height="520px"></iframe>
            </dd>
        </dl>
    </div>
</div>

<script type="text/javascript">
 var item  = $('details_div');
  var tabricator1 = new Tabricator('tabricator1', 'DT');
  if(item)
    {
     item.style.display='block';
    }
</script>
