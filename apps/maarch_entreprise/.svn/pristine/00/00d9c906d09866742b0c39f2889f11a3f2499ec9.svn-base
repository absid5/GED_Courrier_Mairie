<?php
$GLOBALS['basket_loaded'] = false;
$GLOBALS['entities_loaded'] = false;
$func = new functions();
$core = new core_tools();
$core_tools = new core_tools();

$core_tools->test_admin('admin_groups', 'apps');

if ($core->is_module_loaded('basket')) {
    $GLOBALS['basket_loaded'] = true;
}
if ($core->is_module_loaded('entities')) {
    $GLOBALS['entities_loaded'] = true;
}

$mode = 'add';
if (isset($_REQUEST['mode']) && ! empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
}

try {
    require_once 'core/class/usergroups_controler.php';
    require_once 'core/class/users_controler.php';
    require_once 'core/class/SecurityControler.php';
    require_once 'core/class/class_security.php';
    if ($mode == 'list') {
        require_once 'core/class/class_request.php';
        require_once 'apps' . DIRECTORY_SEPARATOR
            . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'class'
            . DIRECTORY_SEPARATOR . 'class_list_show.php';
    }
    if ($GLOBALS['basket_loaded']) {
        require_once 'modules/basket/class/BasketControler.php';
    }
    if ($mode == 'del' && $GLOBALS['entities_loaded']) {
        require_once 'modules/entities/class/EntityControler.php';
    }

} catch (Exception $e){
    functions::xecho($e->getMessage());
}

$core->load_lang();

if (isset($_REQUEST['id']) && ! empty($_REQUEST['id'])) {
    $groupId = $_REQUEST['id'];
}

if (isset($_REQUEST['group_submit'])) {
    // Action to do with db
    validateGroupSubmit();

} else {
    // Display to do
    $users = array();
    $baskets = array();
    $access = array();
    $services = array();
    $state = true;
    switch ($mode) {
        case "up" :
            $res = displayUp($groupId);
            $state = $res['state'];
            $users = $res['users'];
            $baskets = $res['baskets'];
            $access = $res['access'];
            $services = $res['services'];
            locationBarManagement($mode);
            break;
        case "add" :
            displayAdd();
            locationBarManagement($mode);
            break;
        case "del" :
            displayDel($groupId);
            break;
        case "allow" :
            displayEnable($groupId);
            break;
        case "ban" :
            displayDisable($groupId);
            break;
        case "list" :
            $groupsList = displayList();
            locationBarManagement($mode);
            break;
        case "check_del" :
            displayDelCheck($groupId);
            break;
    }
    include('usergroups_management.php');
}

///////////// FUNCTIONS
/**
 * Management of the location bar
 */
function locationBarManagement($mode)
{
    $pageLabels = array(
    	'add' => _ADDITION,
    	'up' => _MODIFICATION,
    	'list' => _GROUPS_LIST,
    );
    $pagesIds = array(
    	'add' => 'group_add',
    	'up' => 'group_up',
    	'list' => 'groups_list',
    );
    $init = false;
    if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == 'true') {
        $init = true;
    }
    $level = '';
    if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
        || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
        || $_REQUEST['level'] == 1)
    ) {
        $level = $_REQUEST['level'];
    }
    $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page='
              . 'usergroups_management_controler&admin=groups&mode=' . $mode;
    $pageLabel = $pageLabels[$mode];
    $pageId = $pagesIds[$mode];
    $core = new core_tools();
    $core->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
}


function initSession()
{
    $_SESSION['m_admin']['groups'] = array(
        'group_id'      => '',
    	'group_desc'    => '',
        'security'      => array(),
    	'services'      => array(),
    	'init'          => false,
        'load_security' => true,
        'load_services' => true,
    );
}

function transformSecurityObjectIntoArray($security)
{
    if (! isset($security)) {
        return array();
    }

    $securityId = $security->__get('security_id');
    $groupId = $security->__get('group_id');
    $comment = $security->__get('maarch_comment');
    $collId = $security->__get('coll_id');
    $where = $security->__get('where_clause');
    $target = $security->__get('where_target');
    $startDate = $security->__get('mr_start_date');
    $stopDate = $security->__get('mr_stop_date');
    $rightsBitmask = $security->__get('rights_bitmask');
    $sec = new security();
    $ind = $sec->get_ind_collection($collId);

    return array(
    	'SECURITY_ID'      => $securityId ,
    	'GROUP_ID'         => $groupId,
    	'COLL_ID'          => $collId,
    	'IND_COLL_SESSION' => $ind,
    	'WHERE_CLAUSE'     => $where,
    	'COMMENT'          => $comment,
    	'WHERE_TARGET'     => $target,
    	'START_DATE'       => $startDate,
    	'STOP_DATE'        => $stopDate,
    	'RIGHTS_BITMASK'   => $rightsBitmask
    );
}

function transformArrayOfSecurityObject($securityArray){
    $res = array();
    for ($i = 0; $i < count($securityArray); $i ++) {
        array_push($res, transformSecurityObjectIntoArray($securityArray[$i]));
    }
    return $res;
}

/**
 * Initialize session parameters for update display
 * @param Long $scheme_id
 */
function displayUp($groupId)
{
    $core = new core_tools();
    $users = array();
    $baskets = array();
    $access = array();
    $services = array();
    $state = true;
    $ugc = new usergroups_controler();
    $uc = new users_controler();
    $group = $ugc->get($groupId);
    $secCtrl = new SecurityControler();
    if (! isset($group)) {
        $state = false;
    } else {
        putInSession('groups', $group->getArray());
    }
    if ( ! isset($_SESSION['m_admin']['load_security'])
        || $_SESSION['m_admin']['load_security'] == true
    ) {
        // Get security accesses in an array
        $access = $secCtrl->getAccessForGroup($groupId);
        $_SESSION['m_admin']['groups']['security'] = transformArrayOfSecurityObject($access);
        $_SESSION['m_admin']['load_security'] = false ;
    }
    if ( ! isset($_SESSION['m_admin']['load_services'])
        || $_SESSION['m_admin']['load_services'] == true
    ) {
        $services = $ugc->getServices($groupId);  // Get services array
        $_SESSION['m_admin']['groups']['services'] = $services;
        $_SESSION['m_admin']['load_services'] = false ;
    }
    //Get all user_id of all members of the group
    $usersIds = $ugc->getUsers($groupId);
    // Get all basket_id linked to the group
    $basketsIds = $ugc->getBaskets($groupId);
    for ($i = 0; $i < count($usersIds); $i ++) {
        //$tmpUser = $uc ->get($usersIds[$i]);
        if (isset($usersIds)) {
            array_push($users, $usersIds);
        }
    }

    //unset($tmpUser);

    if (isset($GLOBALS['basket_loaded']) && $GLOBALS['basket_loaded'] == true
        && count($basketsIds) > 0
    ) {
        $bc = new BasketControler();
        for ($i = 0; $i < count($basketsIds); $i ++) {
            $tmpBasket = $bc->get($basketsIds[$i]);
            if (isset($tmpBasket)) {
                $baskets[] = $tmpBasket;
            }
        }
    }

    $res['state'] = $state;
    $res['users'] = $users;
    $res['baskets'] = $baskets;
    $res['services'] = $services;
    $res['access'] = $access;
    return $res;
}

/**
 * Initialize session parameters for add display with given scheme
 */
function displayAdd(){
    if ($_SESSION['m_admin']['init'] == true
        || ! isset($_SESSION['m_admin']['init'])
    ) {
        initSession();
    }
}

/**
 * Initialize session parameters for list display
 */
function displayList(){
    $_SESSION['m_admin'] = array();
    initSession();
    $func = new functions();
    $select[USERGROUPS_TABLE] = array();
    array_push($select[USERGROUPS_TABLE], 'group_id', 'group_desc', 'enabled');
    $where = '';
    $what = '';
    $arrayPDO = array();
    if (isset($_REQUEST['what']) && ! empty($_REQUEST['what'])) {
        $what = $_REQUEST['what'];
		$where = "lower(group_desc) like lower(?)";
        $arrayPDO = array($what.'%');
	}
    // Checking order and order_field values
    $order = 'asc';
    if (isset($_REQUEST['order']) && ! empty($_REQUEST['order'])) {
        $order = trim($_REQUEST['order']);
    }

    $field = 'group_id';
    if (isset($_REQUEST['order_field']) && ! empty($_REQUEST['order_field'])) {
        $field = trim($_REQUEST['order_field']);
    }
    $list = new list_show();
    $orderstr = $list->define_order($order, $field);
    $request = new request();
    $arr = $request->PDOselect(
        $select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype']
    );
    for ($i = 0; $i < count($arr); $i ++) {
        foreach ($arr[$i] as &$item) {
            switch ($item['column']){
                case 'group_id':
                    formatItem(
                        $item, _ID, '18', 'left', 'left', 'bottom', true
                    );
                    break;
                case 'group_desc':
                    formatItem(
                        $item, _DESC, '50', 'left', 'left', 'bottom', true
                    );
                    break;
                case 'enabled':
                    formatItem(
                        $item, _STATUS, '6', 'center', 'center', 'bottom', true
                    );
                    break;
            }
        }
    }

    $autoCompletionArray = array(
        'list_script_url' =>  $_SESSION['config']['businessappurl']
                              . 'index.php?display=true&admin=groups&page='
                              . 'groups_list_by_name',
        'number_to_begin' => 1,
    );
    $result = array(
        'tab' => $arr,
        'what' => $what,
        'page_name' => 'usergroups_management_controler&mode=list',
        'page_name_up' => 'usergroups_management_controler&mode=up',
        'page_name_del' => 'usergroups_management_controler&mode=del',
        'page_name_val' => 'usergroups_management_controler&mode=allow',
        'page_name_ban' => 'usergroups_management_controler&mode=ban',
        'page_name_add' => 'usergroups_management_controler&mode=add',
        'label_add'     => _GROUP_ADDITION,
        'title'         => _GROUPS_LIST . ' : ' . $i . ' ' . _GROUPS,
        'autoCompletionArray' => $autoCompletionArray,
    );

    $_SESSION['m_admin']['load_security']  = true;
    $_SESSION['m_admin']['load_services'] = true;
    $_SESSION['m_admin']['init'] = true;

    return $result;
}

/**
 * Delete given usergroup if exists and initialize session parameters
 * @param unknown_type $groupId
 */
function displayDel($groupId)
{
    $ugc = new usergroups_controler();

    //information users exists in groups
    $userExists = $ugc->getUsers($groupId);
    if(!empty($userExists)){
        $usersGroups=implode(",", $ugc->getUsers($groupId));?>
        <script type="text/javascript">window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?page='
            . 'usergroups_management_controler&mode=check_del&admin=groups&id=' . $groupId;
        ?>';</script>
    <?php exit(); }

    $group = $ugc->get($groupId);
    if (isset($group) && isset($groupId) && ! empty($groupId)) {
        $control = array();
        $params = array();
        if (isset($_SESSION['history']['usergroupsdel'])) {
            $params['log_group_del'] = $_SESSION['history']['usergroupsdel'];
        }
        if (isset($_SESSION['config']['databasetype'])) {
            $params['databasetype'] = $_SESSION['config']['databasetype'];
        } else {
            $params['databasetype'] = 'POSTGRESQL';
        }
        $control = $ugc->delete($group, $params);
        if ($GLOBALS['basket_loaded']) {
            $bc = new BasketControler();
            $bc->cleanFullGroupbasket($groupId, 'group_id');
        }
        if ($GLOBALS['entities_loaded']) {
            $ec = new EntityControler();
            $ec->cleanGroupbasketRedirect($groupId, 'group_id');
        }
        if (! empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _DELETED_GROUP.' : '.$groupId;
        }

        ?><script type="text/javascript">window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?page='
            . 'usergroups_management_controler&mode=list&admin=groups&order='
            . $_REQUEST['order'] . '&order_field=' . $_REQUEST['order_field']
            . '&start=' . $_REQUEST['start'] . '&what=' . addslashes($_REQUEST['what']);
        ?>';</script>
        <?php
        exit();
    } else {
        // Error management
        $_SESSION['error'] = _GROUP . ' ' . _UNKNOWN;
    }
}


/**
 * Delete given usergroup if exists and initialize session parameters
 * @param unknown_type $groupId
 */
function displayDelCheck($groupId)
{
    /****************Management of the location bar  ************/
        $admin = new core_tools();
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
        $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=types';
        $pageLabel = _DELETION;
        $pageId = "types";
        $admin->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
        /***********************************************************/
        if(isset($_POST['group_id'])){
            $old_group=$_POST['id'];
            $new_group=$_POST['group_id'];
            $ugc = new usergroups_controler();
            $users = $ugc->getUsers($old_group);
            //$users_sql = "'".implode("','", $users)."'";
            $db = new Database();
            $db->query(
                "delete from usergroup_content WHERE group_id=? AND user_id in (?)",
                array($old_group, $users)
            );
            if($_POST['group_id'] != 'no_group'){
                $stmt = $db->query("select * from usergroup_content WHERE group_id = ?", array($new_group));
                $usersPresentInGroup = [];
                while($res = $stmt->fetchObject())
                    array_push($usersPresentInGroup, $res->user_id);
                foreach ($users as $key => $value) {
                    if (!in_array($value, $usersPresentInGroup)){
                        $db->query(
                            "INSERT INTO usergroup_content(group_id, user_id, primary_group) values (?, ?, 'N')",
                            array($new_group, $value)
                        );
                    }
                }

                $_SESSION['info'] = _DELETED_GROUP.' : '.$old_group;
             } ?>
            <script type="text/javascript">window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?page='
            . 'usergroups_management_controler&mode=del&admin=groups&id=' . $groupId;
        ?>';</script> <?php
        }
        $ugc = new usergroups_controler();
        $userExists = $ugc->getUsers($groupId);
        echo '<h1><i class="fa fa-users fa-2x"></i>'._GROUP_DELETION.': <i>'.$groupId.'</i></h1>';
        echo "<div class='error' id='main_error'>".$_SESSION['error']."</div>";
        $_SESSION['error'] = "";
        ?>
        <br>
        <div class="block">
        <div id="main_error" style="text-align:center;">
            <b><?php
            echo _WARNING_MESSAGE_DEL_GROUP;
            ?></b>
        </div>
        <br/>
        <form name="entity_del" id="entity_del" style="width: 250px;margin:auto;" method="post" class="forms">
            <input type="hidden" value="<?php functions::xecho($groupId);?>" name="id">
            <?php

                echo "<h3>".count($userExists)." "._USERS_IN_GROUPS .":</h3>";
                echo "<ul>";
                foreach ($userExists as $key => $value) {
                   echo "<li>".$value."</li>";
                }
                echo "</ul>";
                ?>
                <br>
                <br>
                <select name="group_id" id="group_id" onchange=''>
                    <option value="no_group"><?php echo _NO_REPLACEMENT;?></option>
                    <?php
                    $db = new Database();
                    $stmt = $db->query("select * from usergroups order by group_desc ASC");
                    while($groups = $stmt->fetchObject())
                    {
                        if($groups->group_id != $groupId){
                         ?>
                        <option value="<?php functions::xecho($groups->group_id);?>"><?php functions::xecho($groups->group_desc);?></option>
                        <?php
                        }
                       
                    }
                    ?>
                </select>
                 <p class="buttons">
                    <input type="submit" value="<?php echo _DEL_AND_REAFFECT;?>" name="valid" class="button" onclick='if(document.getElementById("doc_type_id").options[document.getElementById("doc_type_id").selectedIndex].value == ""){alert("<?php echo _CHOOSE_REPLACEMENT_DOCTYPES ?> !");return false;}else{return(confirm("<?php echo _REALLY_DELETE.$s_id;?> \n\r\n\r<?php echo _DEFINITIVE_ACTION?>"));}'/>
                    <input type="button" value="<?php echo _CANCEL;?>" class="button" onclick="window.location.href='<?php echo $_SESSION['config']['businessappurl'] ?>index.php?page=usergroups_management_controler&mode=list&admin=groups&order=<?php functions::xecho($_REQUEST['order']);?>&order_field=<?php functions::xecho($_REQUEST['order_field']);?>&start=<?php functions::xecho($_REQUEST['start']);?>&what=<?php functions::xecho($_REQUEST['what']);?>';"/>
                </p>
            </form>
            </div>
            <script type="text/javascript"></script>
        <?php
        exit();
}

/**
 * Enable given usergroup if exists and initialize session parameters
 * @param unknown_type $user_id
 */
function displayEnable($groupId)
{

    $ugc = new usergroups_controler();
    $group = $ugc->get($groupId);
    if (isset($group)) {
        $control = array();
        $params = array();
        if (isset($_SESSION['history']['usergroupsval'])) {
            $params['log_group_enabled'] = $_SESSION['history']['usergroupsval'];
        }
        if (isset($_SESSION['config']['databasetype'])) {
            $params['databasetype'] = $_SESSION['config']['databasetype'];
        } else {
            $params['databasetype'] = 'POSTGRESQL';
        }
        $control = $ugc->enable($group, $params);
        if ( ! empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _AUTORIZED_GROUP.' : '.$groupId;
        }
        $url = $_SESSION['config']['businessappurl'] . 'index.php?page='
             . 'usergroups_management_controler&mode=list&admin=groups&order='
             . $_REQUEST['order'] . '&order_field=' . $_REQUEST['order_field']
             . '&start=' . $_REQUEST['start'] . '&what=' . $_REQUEST['what'];
        ?><script type="text/javascript">window.top.location='<?php functions::xecho($url);?>';</script>
        <?php
        exit();
    } else {
        // Error management
        $_SESSION['error'] = _GROUP.' '._UNKNOWN;
    }
}

/**
 * Disable given user if exists and initialize session parameters
 * @param unknown_type $user_id
 */
function displayDisable($groupId)
{
    $ugc = new usergroups_controler();
    $group = $ugc->get($groupId);
    if (isset($group)) {
        $control = array();
        $params = array();
        if (isset($_SESSION['history']['usergroupsban'])) {
            $params['log_group_disabled'] = $_SESSION['history']['usergroupsban'];
        }
        if (isset($_SESSION['config']['databasetype'])) {
            $params['databasetype'] = $_SESSION['config']['databasetype'];
        } else {
            $params['databasetype'] = 'POSTGRESQL';
        }

        $control = $ugc->disable($group, $params);
        if (! empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _SUSPENDED_GROUP.' : '.$groupId;
        }
        $url = $_SESSION['config']['businessappurl'] . 'index.php?page='
             . 'usergroups_management_controler&mode=list&admin=groups&order='
             . $order . '&order_field=' . $orderField . '&start=' . $start
             . '&what=' . $what;
        ?><script type="text/javascript">window.top.location='<?php functions::xecho($url);?>';</script>
        <?php
        exit();
    } else {
        // Error management
        $_SESSION['error'] = _GROUP . ' ' . _UNKNOWN;
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
function formatItem(&$item, $label, $size, $labelAlign, $align, $valign, $show,
    $order= true)
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
        $item['order' ]= '';
    }
}

/**
 * Validate a submit (add or up),
 * up to saving object
 */
function validateGroupSubmit()
{
    $ugc = new usergroups_controler();
    $pageName = 'usergroups_management_controler';
    $group = new usergroups();
    $mode = $_REQUEST['mode'];

    $group->group_id = $_REQUEST['group_id'];
    if (isset($_REQUEST['desc']) && ! empty($_REQUEST['desc'])) {
        $group->group_desc = $_REQUEST['desc'];
    }

    $status = array(
        'order'       => $_REQUEST['order'],
        'order_field' => $_REQUEST['order_field'],
        'what'		  => $_REQUEST['what'],
        'start'       => $_REQUEST['start'],
    );

    $control = array();
    $params = array(
    	'modules_services' => $_SESSION['modules_services'],
        'log_group_up'     => $_SESSION['history']['usergroupsup'],
        'log_group_add'    => $_SESSION['history']['usergroupsadd'],
        'databasetype'     => $_SESSION['config']['databasetype'],
        'user_id'          => $_SESSION['user']['UserId']
    );

    $services = array();
    if (isset($_REQUEST['services'])) {
        $services = $_REQUEST['services'];
    }
    if (isset($_SESSION['m_admin']['groups']['security'])) {
        $control = $ugc->save(
            $group, $_SESSION['m_admin']['groups']['security'], $services,
            $mode, $params
        );
    }
    if (! empty($control['error']) && $control['error'] <> 1) {
        // Error management depending of mode
        $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        putInSession('status', $status);
        putInSession('groups', $group->getArray());

        switch ($mode) {
            case 'up':
                if (! empty($group->group_id)) {
                    header(
                    	'location: ' . $_SESSION['config']['businessappurl']
                        . 'index.php?page=' . $pageName . '&mode=up&id='
                        . $group->group_id . '&admin=groups'
                    );
                } else {
                    header(
                    	'location: ' . $_SESSION['config']['businessappurl']
                        . 'index.php?page=' . $pageName . '&mode=list&admin='
                        . 'groups&order=' . $status['order'] . '&order_field='
                        . $status['order_field'] . '&start=' . $status['start']
                        . '&what=' . $status['what']
                    );
                }
                exit;
            case 'add':
                $_SESSION['m_admin']['load_group'] = false;
                header(
                	'location: ' . $_SESSION['config']['businessappurl']
                    . 'index.php?page=' . $pageName . '&mode=add&admin=groups'
                );
                exit;
        }
    } else {
        if ($mode == 'add') {
            $_SESSION['info'] = _GROUP_ADDED;
        } else {
            $_SESSION['info'] = _GROUP_UPDATED;
        }
        unset($_SESSION['m_admin']);
        header(
        	'location: ' . $_SESSION['config']['businessappurl']
            . 'index.php?page=' . $pageName . '&mode=list&admin=groups&order='
            . $status['order'] . '&order_field=' . $status['order_field']
            . '&start=' . $status['start'] . '&what=' . $status['what']
        );
    }
}

/**
 * Put given object in session, according with given type
 * NOTE: given object needs to be at least hashable
 * @param string $type
 * @param hashable $hashable
 */
function putInSession($type, $hashable, $showString = true)
{
    $func = new functions();
    foreach ($hashable as $key => $value) {
        if ($showString) {
            $_SESSION['m_admin'][$type][$key] = $func->show_string($value);
        } else {
            $_SESSION['m_admin'][$type][$key] = $value;
        }
    }
}
////////////////////////////////////////

