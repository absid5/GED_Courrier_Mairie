<?php
core_tools::load_lang();
$core_tools = new core_tools();
$core_tools->test_admin('admin_status', 'apps');

// Default mode is add
$mode = 'add';
if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
}

try{
    require_once 'core/class/StatusControler.php' ;
    if ($mode == 'list') {
        require_once 'core/class/class_request.php' ;
        require_once 'apps' . DIRECTORY_SEPARATOR
                     . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                     . 'class' . DIRECTORY_SEPARATOR . 'class_list_show.php' ;
    }
} catch (Exception $e) {
    functions::xecho($e->getMessage());
}

if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $statusId = $_REQUEST['id'];
}

if (isset($_REQUEST['status_submit'])) {
    // Action to do with db
    validate_status_submit();

} else {
    // Display to do
    $state = true;
    switch ($mode) {
        case 'up' :
            $state = display_up($statusId);
            $_SESSION['service_tag'] = 'status_init';
            core_tools::execute_modules_services(
                $_SESSION['modules_services'], 'status_init', 'include'
            );
            location_bar_management($mode);
            break;
        case 'add' :
            display_add();
            $_SESSION['service_tag'] = 'status_init';
            core_tools::execute_modules_services(
                $_SESSION['modules_services'], 'status_init', 'include'
            );
            location_bar_management($mode);
            break;
        case 'del' :
            display_del($statusId);
            break;
        case 'list' :
            $statusList = display_list();
            location_bar_management($mode);
            break;
    }
    include('status_management.php');
}

/**
 * Management of the location bar
 */
function location_bar_management($mode)
{
    $pageLabels = array('add'  => _ADDITION,
                    'up'   => _MODIFICATION,
                    'list' => _STATUS_LIST
               );
    $pageIds = array('add' => 'status_add',
                  'up' => 'status_up',
                  'list' => 'status_list'
            );
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

    $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page='
               . 'status_management_controler&admin=status&mode=' . $mode ;
    $pageLabel = $pageLabels[$mode];
    $pageId = $pageIds[$mode];
    $ct = new core_tools();
    $ct->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
}

/**
 * Initialize session parameters for update display
 * @param String $statusId
 */
function display_up($statusId)
{
    $statusCtrl = new Maarch_Core_Class_StatusControler();
    $state = true;
    $status = $statusCtrl->get($statusId);

    if (empty($status)) {
        $state = false;
    } else {
        put_in_session('status', $status->getArray());
    }
    return $state;
}

/**
 * Initialize session parameters for add display
 */
function display_add()
{
    if (!isset($_SESSION['m_admin']['init'])) {
        init_session();
    }
}

/**
 * Initialize session parameters for list display
 */
function display_list()
{
    $_SESSION['m_admin'] = array();
    $list = new list_show();
    $func = new functions();
    init_session();

    $select[STATUS_TABLE] = array();
    array_push($select[STATUS_TABLE], 'id', 'label_status','img_filename');
    $where = '';
    $what = '';
    $arrayPDO = array();
    if (isset($_REQUEST['what'])) {
        $what = $_REQUEST['what'];
		$where .= " (lower(label_status) like lower(?)  or id like ?) ";
        $arrayPDO = array($what.'%', $what.'%');
    }

    // Checking order and order_field values
    $order = 'asc';
    if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
        $order = trim($_REQUEST['order']);
    }

    $field = 'label_status';
    if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) {
        $field = trim($_REQUEST['order_field']);
    }

    $orderstr = $list->define_order($order, $field);
    $request = new request();
    $tab = $request->PDOselect(
        $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
    );


    for ($i=0;$i<count($tab);$i++) {
        foreach ($tab[$i] as &$item) {
            switch ($item['column']) {
                case 'id':
                    format_item(
                        $item, _ID, '18', 'left', 'left', 'bottom', true
                    );
                    break;
                case 'label_status':
                    format_item(
                        $item, _DESC, '55', 'left', 'left', 'bottom', true
                    );
                    break;
            }
        }
    }
    $_SESSION['m_admin']['init'] = true;
    $result = array(
        'tab'                 => $tab,
        'what'                => $what,
        'page_name'           => 'status_management_controler&mode=list',
        'page_name_add'       => 'status_management_controler&mode=add',
        'page_name_up'        => 'status_management_controler&mode=up',
        'page_name_del'       => 'status_management_controler&mode=del',
        'page_name_val'       => '',
        'page_name_ban'       => '',
        'label_add'           => _ADD_STATUS,
        'title'               => _STATUS_LIST . ' : ' . $i . ' ' . _STATUS_PLUR,
        'autoCompletionArray' => array(
                                     'list_script_url'  =>
                                        $_SESSION['config']['businessappurl']
                                        . 'index.php?display=true&admin=status'
                                        . '&page=status_list_by_name',
                                     'number_to_begin'  => 1
                                 ),

    );
    return $result;
}

/**
 * Delete given status if exists and initialize session parameters
 * @param string $statusId
 */
function display_del($statusId)
{
    $statusCtrl = new Maarch_Core_Class_StatusControler();
    $status = $statusCtrl->get($statusId);
    if (isset($status)) {
        // Deletion
        $control = array();
        $params  = array( 'log_status_del' => $_SESSION['history']['statusdel'],
                         'databasetype' => $_SESSION['config']['databasetype']
                        );
        $control = $statusCtrl->delete($status, $params);
        if (!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _STATUS_DELETED.' : '.$statusId;
        }
        ?><script type="text/javascript">window.top.location='<?php
            echo $_SESSION['config']['businessappurl']
                . 'index.php?page=status_management_controler&mode=list&admin='
                . 'status&order=' . $_REQUEST['order'] . '&order_field='
                . $_REQUEST['order_field'] . '&start=' . $_REQUEST['start']
                . '&what=' . addslashes($_REQUEST['what']);
        ?>';</script>
        <?php
        exit();
    } else {
        // Error management
        $_SESSION['error'] = _STATUS.' '._UNKNOWN;
    }
}

/**
 * Format given item with given values, according with HTML formating.
 * NOTE: given item needs to be an array with at least 2 keys:
 * 'column' and 'value'.
 * NOTE: given item is modified consequently.
 * @param $item
 * @param $label
 * @param $size
 * @param $labelAlign
 * @param $align
 * @param $valign
 * @param $show
 */
function format_item(
    &$item, $label, $size, $labelAlign, $align, $valign, $show, $order = true
)
{
    $func = new functions();
    $item['value'] = $func->show_string($item['value']);
    $item[$item['column']] = $item['value'];
    $item['label'] = $label;
    $item['size'] = $size;
    $item['label_align'] = $labelAlign;
    $item['align'] = $align;
    $item['valign'] = $valign;
    $item['show'] = $show;
    if ($order) {
        $item['order'] = $item['value'];
    } else {
        $item['order'] = '';
    }
}

/**
 * Validate a submit (add or up),
 * up to saving object
 */
function validate_status_submit()
{
    $statusCtrl = new Maarch_Core_Class_StatusControler();
    $pageName = 'status_management_controler';

    $mode = $_REQUEST['mode'];
    $statusObj = new Status();
    $statusObj->id = $_REQUEST['status_id'];
    $statusObj->label_status = $_REQUEST['label'];
    $statusObj->is_system = 'N';
    if (isset($_REQUEST['is_system']) && !empty($_REQUEST['is_system'])) {
        $statusObj->is_system = $_REQUEST['is_system'];
    }
    $statusObj->img_filename = $_REQUEST['img_related'];
    $statusObj->maarch_module = 'apps';
    $statusObj->can_be_searched = 'Y';
    if (isset($_REQUEST['can_be_searched'])) {
        $statusObj->can_be_searched = $_REQUEST['can_be_searched'];
    }
    $statusObj->can_be_modified = 'Y';
    if (isset($_REQUEST['can_be_modified'])) {
        $statusObj->can_be_modified = $_REQUEST['can_be_modified'];
    }
    $statusObj->is_folder_status = 'N';
    if (isset($_REQUEST['is_folder_status'])) {
        $statusObj->is_folder_status = $_REQUEST['is_folder_status'];
    }
    if (isset($_REQUEST['img_related'])) {
        $statusObj->img_filename = $_REQUEST['img_related'];
    }

	//print_r($statusObj);exit;
    $status = array();
    $status['order'] = $_REQUEST['order'];
    $status['order_field'] = $_REQUEST['order_field'];
    $status['what'] = $_REQUEST['what'];
    $status['start'] = $_REQUEST['start'];

    $control = array();
    $params = array('modules_services' => $_SESSION['modules_services'],
                    'log_status_up' => $_SESSION['history']['statusup'],
                    'log_status_add' => $_SESSION['history']['statusadd'],
                    'databasetype' => $_SESSION['config']['databasetype']
               );

    $control = $statusCtrl->save($statusObj, $mode, $params);

    if (!empty($control['error']) && $control['error'] <> 1) {
        // Error management depending of mode
        $_SESSION['error'] = str_replace("#", "<br />", $control['error']);

        put_in_session('status', $status);
        put_in_session('status', $statusObj->getArray());

        switch ($mode) {
            case 'up':
                if (!empty($status->id)) {
                    header(
                        'location: ' . $_SESSION['config']['businessappurl']
                        . 'index.php?page=' . $pageName . '&mode=up&id='
                        . $statusObj->id . '&admin=status'
                    );
                } else {
                    header(
                        'location: ' . $_SESSION['config']['businessappurl']
                        . 'index.php?page=' . $pageName . '&mode=list&admin='
                        .'status&order=' . $status['order'] . '&order_field='
                        . $status['order_field'] . '&start=' . $status['start']
                        . '&what=' . $status['what']
                    );
                }
                exit();
            case 'add':
                header(
                    'location: ' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=' . $pageName . '&mode=add&admin=status'
                );
                exit();
        }
    } else {
        if ($mode == 'add') {
            $_SESSION['info'] = _STATUS_ADDED;
        } else {
            $_SESSION['info'] = _STATUS_MODIFIED;
        }
        unset($_SESSION['m_admin']);

        header(
            'location: ' . $_SESSION['config']['businessappurl']
            . 'index.php?page=' . $pageName . '&mode=list&admin=status&order='
            . $status['order'] . '&order_field=' . $status['order_field']
            . '&start=' . $status['start'] . '&what=' . $status['what']
        );
    }
}

function init_session()
{
    $_SESSION['m_admin']['status'] = array(
        'id'              => '',
        'label_status'    => '',
        'is_system'       => 'N',
        'img_filename'    => '',
        'module'          => 'apps',
        'can_be_searched' => 'Y',
        'can_be_modified' => 'Y',
        'is_folder_status'=> 'N'
    );
}

/**
 * Put given object in session, according with given type
 * NOTE: given object needs to be at least hashable
 * @param string $type
 * @param hashable $hashable
 */
function put_in_session($type, $hashable, $showString = true)
{

    $func = new functions();
    foreach ($hashable as $key=>$value) {
        if ($showString) {
            $_SESSION['m_admin'][$type][$key]=$func->show_string($value);
        } else {
            $_SESSION['m_admin'][$type][$key]=$value;
        }
			

    }

}
