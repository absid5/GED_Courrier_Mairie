<?php
require_once 'apps/' . $_SESSION['config']['app_id']
    . '/class/class_list_show.php';
require_once 'core/class/class_request.php';
$core_tools2 = new core_tools();
$core_tools2->test_admin('view_history_batch', 'apps');
/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
    $init = true;
}
$level = '';
if (isset($_REQUEST['level'])
    && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3
        || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)) {
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page='
           . 'history_batch&admin=history_batch';
$page_label = _VIEW_HISTORY_BATCH2;
$page_id = 'history_batch';
$core_tools2->manage_location_bar(
    $page_path, $page_label, $page_id, $init, $level
);
/***********************************************************/
$db = new Database();

$where = '';
$arrayPDO = array();
$label = '';
$tab = array();
$modules = array();
$stmt = $db->query(
    'SELECT DISTINCT module_name FROM '.$_SESSION['tablename']['history_batch']
);
while ($res = $stmt->fetchObject()) {
    if ($res->module_name == 'admin') {
        array_push(
            $modules, array(
                'id' => 'admin',
                'label' => _ADMIN
            )
        );
    } else if (isset($_SESSION['modules_loaded'][$res->module_name]['comment'])
        && !empty($_SESSION['modules_loaded'][$res->module_name]['comment'])) {
        array_push(
            $modules, array(
                'id' => $res->module_name,
                'label' =>
                    $_SESSION['modules_loaded'][$res->module_name]['comment']
            )
        );
    } else {
        array_push(
            $modules, array(
                'id' => $res->module_name,
                'label' => $res->module_name
            )
        );
    }
}
if ((isset($_REQUEST['search']) && $_REQUEST['search']) ||
    (! empty($_SESSION['m_admin']['history_batch_action'])
        && isset($_SESSION['m_admin']['history_batch_action']))
    || (! empty($_SESSION['m_admin']['history_batch_module'])
        && isset($_SESSION['m_admin']['history_batch_module']))
    || (!empty($_SESSION['m_admin']['onlyerrors'])
        && isset($_SESSION['m_admin']['onlyerrors']))
    || (isset($_REQUEST['datestart']) && ! empty($_REQUEST['datestart']))
    || (isset($_REQUEST['dateend']) && ! empty($_REQUEST['dateend']))) {
    $pattern = '/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/';
    if ((isset($_REQUEST['module']) && ! empty($_REQUEST['module']))
        || (! empty($_SESSION['m_admin']['history_batch_module'])
            && isset($_SESSION['m_admin']['history_batch_module']))) {
        if (isset($_REQUEST['module']) && !empty($_REQUEST['module'])) {
            $_SESSION['m_admin']['history_batch_module'] = $_REQUEST['module'];
        }
        $where .= ' ' . $_SESSION['tablename']['history_batch']
               . ".module_name = ? and";
        $arrayPDO = array_merge($arrayPDO, array($_SESSION['m_admin']['history_batch_module']));
        $_SESSION['m_admin']['history_batch_module'] = '';
    }
    if ((isset($_REQUEST['onlyerrors']) && ! empty($_REQUEST['onlyerrors']))
        || (! empty($_SESSION['m_admin']['onlyerrors'])
            && isset($_SESSION['m_admin']['onlyerrors']))) {
        if (isset($_REQUEST['onlyerrors']) && !empty($_REQUEST['onlyerrors'])) {
            $_SESSION['m_admin']['onlyerrors'] = $_REQUEST['onlyerrors'];
        }
        if ($_REQUEST['onlyerrors'] == 'yes') {
            $where .= "  " . $_SESSION['tablename']['history_batch']
                   . ".total_errors > 0 and";
        }
        $_SESSION['m_admin']['onlyerrors'] = '';
    }
    if ((isset($_REQUEST['datestart']) && ! empty($_REQUEST['datestart']))
        || (!empty($_SESSION['m_admin']['history_batch_datestart'])
            && isset($_SESSION['m_admin']['history_batch_datestart']))) {
        if (preg_match($pattern,$_REQUEST['datestart']) == false
            && (! isset($_SESSION['m_admin']['history_batch_datestart'])
                || empty($_SESSION['m_admin']['history_batch_datestart']))) {
            $_SESSION['error'] = _DATE . ' ' . _WRONG_FORMAT;
        } else {
            if (isset($_REQUEST['datestart'])
                && ! empty($_REQUEST['datestart'])) {
                $_SESSION['m_admin']['history_batch_datestart'] =
                    $core_tools2->format_date_db($_REQUEST['datestart']);
            }
            $where .= " (" . $_SESSION['tablename']['history_batch']
                   . ".event_date >= ?) and ";
            $arrayPDO = array_merge($arrayPDO, array($_SESSION['m_admin']['history_batch_datestart']));
            $_SESSION['m_admin']['history_batch_datestart'] = '';
        }
    }
    if ((isset($_REQUEST['dateend']) && ! empty($_REQUEST['dateend']))
        || (! empty($_SESSION['m_admin']['history_batch_dateend'])
            && isset($_SESSION['m_admin']['history_batch_dateend']))){
        if (preg_match($pattern, $_REQUEST['dateend']) == false
            && (! isset($_SESSION['m_admin']['history_batch_dateend'])
                || empty($_SESSION['m_admin']['history_batch_dateend']))) {
            $_SESSION['error'] = _DATE . ' ' . _WRONG_FORMAT;
        } else {
            if (isset($_REQUEST['dateend']) && ! empty($_REQUEST['dateend'])) {
                $_SESSION['m_admin']['history_batch_dateend'] =
                    $core_tools2->format_date_db($_REQUEST['dateend']);
            }
            $where .= " ( " . $_SESSION['tablename']['history_batch']
                   . ".event_date <= ?) and ";
            $arrayPDO = array_merge($arrayPDO, array($_SESSION['m_admin']['history_batch_dateend']));
            $_SESSION['m_admin']['history_batch_dateend'] = '';
        }
    }
    $where = trim($where);
    $where = preg_replace('/and$/', '', $where);
}
$select[$_SESSION['tablename']['history_batch']] = array();
array_push(
    $select[$_SESSION['tablename']['history_batch']], 'id', 'event_date',
    'batch_id', 'module_name', 'total_processed', 'total_errors', 'info'
);

$list = new list_show();
$order = 'desc';
if (isset($_REQUEST['order']) && ! empty($_REQUEST['order'])) {
    $order = trim($_REQUEST['order']);
}
$field = 'event_date';
if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field'])) {
    $field = trim($_REQUEST['order_field']);
}

$orderstr = $list->define_order($order, $field);

$req = new request();
$tab = $req->PDOselect(
    $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype'],
    '500', false, $_SESSION['tablename']['history_batch']
);
//$req->show();
for ($i = 0; $i < count($tab); $i ++) {
    for ($j = 0; $j < count($tab[$i]); $j ++) {
        foreach (array_keys($tab[$i][$j]) as $value) {
            if ($tab[$i][$j][$value] == 'id') {
                $tab[$i][$j]['id'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['show'] = false;
            }
            if ($tab[$i][$j][$value] == 'event_date') {
                $tab[$i][$j]['event_date'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['label'] = _DATE;
                $tab[$i][$j]['size'] = '12';
                $tab[$i][$j]['label_align'] = 'left';
                $tab[$i][$j]['align'] = 'left';
                $tab[$i][$j]['valign'] = 'bottom';
                $tab[$i][$j]['show'] = true;
                $tab[$i][$j]['order'] = 'event_date';
            }
            if ($tab[$i][$j][$value] == 'module_name') {
                $tab[$i][$j]['batch_id'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['label'] = _BATCH_NAME;
                $tab[$i][$j]['size'] = '10';
                $tab[$i][$j]['label_align'] = 'left';
                $tab[$i][$j]['align'] = 'left';
                $tab[$i][$j]['valign'] = 'bottom';
                $tab[$i][$j]['show'] = true;
                $tab[$i][$j]['order'] = 'module_name';
            }
            if ($tab[$i][$j][$value] == 'batch_id') {
                $tab[$i][$j]['batch_id'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['label'] =_BATCH_ID;
                $tab[$i][$j]['size'] = '10';
                $tab[$i][$j]['label_align'] = 'left';
                $tab[$i][$j]['align'] = 'left';
                $tab[$i][$j]['valign'] = 'bottom';
                $tab[$i][$j]['show'] = true;
                $tab[$i][$j]['order'] = 'batch_id';
            }
            if ($tab[$i][$j][$value] == 'total_processed') {
                $tab[$i][$j]['value'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['total_processed'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['label'] =_TOTAL_PROCESSED;
                $tab[$i][$j]['size'] = '8';
                $tab[$i][$j]['label_align'] = 'left';
                $tab[$i][$j]['align'] = 'left';
                $tab[$i][$j]['valign'] = 'bottom';
                $tab[$i][$j]['show'] = true;
                $tab[$i][$j]['order'] = 'total_processed';
            }
            if ($tab[$i][$j][$value] == 'total_errors') {
                $tab[$i][$j]['value'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['total_processed'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['label'] =_TOTAL_ERRORS;
                $tab[$i][$j]['size'] = '8';
                $tab[$i][$j]['label_align'] = 'left';
                $tab[$i][$j]['align'] = 'left';
                $tab[$i][$j]['valign'] = 'bottom';
                $tab[$i][$j]['show'] = true;
                $tab[$i][$j]['order'] = 'total_errors';
            }
            if ($tab[$i][$j][$value] == 'info') {
                $tab[$i][$j]['value'] = $this->show_string(
                    $tab[$i][$j]['value']
                );
                $tab[$i][$j]['info'] = $tab[$i][$j]['value'];
                $tab[$i][$j]['label'] = _INFOS;
                $tab[$i][$j]['size'] = '40';
                $tab[$i][$j]['label_align'] = 'left';
                $tab[$i][$j]['align'] = 'left';
                $tab[$i][$j]['valign'] = 'bottom';
                $tab[$i][$j]['show'] = true;
                $tab[$i][$j]['order'] = 'info';
            }
        }
    }
}
$list = new list_show();
$nb = count($tab);
?>
<h1><i class="fa fa-history fa-2x"></i> <?php echo _HISTORY_BATCH_TITLE.' : '. $nb.' '._RESULTS;?></h1>
<div id="inner_content">
<?php
$list->admin_list(
    $tab, $nb, '', 'id', 'history_batch', 'history_batch', 'id', true, '', '',
    '', '', '', '', TRUE, FALSE, '', '', '', false, false
);
?>
<br/>
<div id="search_hist" class="block">
    <form name="search_hist" action="<?php
    echo $_SESSION['config']['businessappurl'];
    ?>index.php?page=history_batch&amp;admin=history_batch" method="post" class="forms">
        <input type="hidden" name="page" value="history_batch"/>
        <input type="hidden" name="admin" value="history_batch" />
        <p>
            <label for="module"><?php echo _BATCH_NAME;?> :</label>
            <select name="module">
                <option value=""><?php echo _CHOOSE_BATCH;?></option>
                <?php
                for ($i = 0; $i < count($modules); $i ++)
                {
                    ?>
                    <option value="<?php functions::xecho($modules[$i]['id']);?>"><?php
                        functions::xecho($modules[$i]['label']);?></option>
                    <?php
                }
                ?>
            </select>
        </p>
        <p>
            <label for="datestart"><?php echo _SINCE;?> :</label>
            <input name="datestart" type="text" id="datestart" onclick='showCalender(this);'/>
        </p>
        <p>
            <label for="dateend"><?php echo _FOR;?> :</label>
            <input name="dateend" type="text" id="dateend" onclick="showCalender(this);"/>
        </p>
        <p>
            <label for="onlyerrors"><?php echo _ONLY_ERRORS;?> :</label>
            <?php echo _YES;?><input name="onlyerrors"  class="check" type="radio" id="onlyerrors" value="yes" checked="checked"/> &nbsp;
            <?php echo _NO;?><input name="onlyerrors"  class="check" type="radio" id="onlyerrors" value="no"/>
        </p>
        <p class="button">
            <input type="submit" name="search" value="<?php echo _SEARCH;?>" class="button"/>
            <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=admin';"/>
        </p>
    </form>
</div>
<div class="block_end">&nbsp;</div>
    <br/>
</div>
