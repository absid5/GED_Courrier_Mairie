<?php
$core_tools = new core_tools();
$core_tools->test_admin('admin_users', 'apps');

core_tools::load_lang();
// var_dump($_REQUEST['mode']);
// var_dump($_REQUEST['page']);
$entities_loaded = false;
if(core_tools::is_module_loaded('entities')){
    $entities_loaded = true;
}
// Default mode is add
$mode = 'add';
if(isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])){
    $mode = $_REQUEST['mode'];
}

// Include files
try{
    require_once("core/class/usergroups_controler.php");
    require_once("core/class/users_controler.php");
    if($mode == 'list'){
        require_once("core/class/class_request.php");
        require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
    }
    if($mode == 'del' && $entities_loaded){
        require_once("modules/entities/class/EntityControler.php");
    }

} catch (Exception $e){
    functions::xecho($e->getMessage());
}

if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
    $user_id = $_REQUEST['id'];
}

if(isset($_REQUEST['user_submit'])){
    // Action to do with db
    validate_user_submit();

} else {
    // Display to do
    $ugc = new usergroups_controler();
    $state = true;
    switch ($mode) {
        case "up" :
            $state=display_up($user_id);
            $_SESSION['service_tag'] = 'user_init';
            core_tools::execute_modules_services($_SESSION['modules_services'], 'user_init', "include");
            $_SESSION['m_admin']['nbgroups']  = $ugc->getUsergroupsCount();
            location_bar_management($mode);
            break;
        case "add" :
            display_add();  
            $_SESSION['service_tag'] = 'user_init';
            core_tools::execute_modules_services($_SESSION['modules_services'], 'user_init', "include");
            $_SESSION['m_admin']['nbgroups']  = $ugc->getUsergroupsCount();
            location_bar_management($mode);
            break;
        case "del" :
            display_del($user_id);
            break;
        case "allow" :
            display_enable($user_id);
            break;
        case "ban" :
            $result_Check_Dest = check_dest_listmodels($user_id);
            if($result_Check_Dest == true){
               display_disable($user_id); 
           }elseif($result_Check_Dest == false){

            ?><script type="text/javascript">window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=users_management_controler&mode=list&admin=users&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what'];?>';</script>
        <?php
        exit();

           }
            
            break;
        case "list" :
            $users_list=display_list();
            $_SESSION['m_admin']['nbgroups']  = $ugc->getUsergroupsCount();
            location_bar_management($mode);
            break;
        case "check_del" :
            display_del_check($user_id);
            break;
    }
    include('apps/maarch_entreprise/admin/users/users_management.php');
}


function check_dest_listmodels($user_id){
    //permet de vérifier si l'utilisateur fait partie d'une liste de diffusion. Si il fait parti d'une liste de diffusion, dans l'administration, il ne pourra etre mis en pause sauf si il n'est plus destinataire.
    $db = new Database();
    $stmt = $db->query("select item_id, item_mode from  listmodels where item_id = ? and item_mode = 'dest'",array($user_id));
    $res = $stmt->fetchObject();
        if($res->item_mode == 'dest'){
            return false;
        }else{
            return true;
        }

    }
/**
 * Management of the location bar
 */
function location_bar_management($mode){
    $page_labels = array('add' => _ADDITION, 'up' => _MODIFICATION, 'list' => _USERS_LIST);
    $page_ids = array('add' => 'user_add', 'up' => 'user_up', 'list' => 'users_list');
    $init = false;
    if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true"){
        $init = true;
    }

    $level = "";
    if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1)){
        $level = $_REQUEST['level'];
    }

    $page_path = $_SESSION['config']['businessappurl'].'index.php?page=users_management_controler&admin=users&mode='.$mode;
    $page_label = $page_labels[$mode];
    $page_id = $page_ids[$mode];
    $ct=new core_tools();
    $ct->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
}

/**
 * Initialize session parameters for update display
 * @param String $user_id
 */
function display_up($user_id){
    $uc = new users_controler();
    $ugc = new usergroups_controler();
    $state=true;
    $user = $uc->get($user_id );
    if (empty($user)){
        $state = false;
    } else {
        if ($user->signature_path <> '' 
            && $user->signature_file_name <> '' 
        ) {
            $db = new Database();
            $query = "select path_template from " 
                . _DOCSERVERS_TABLE_NAME 
                . " where docserver_id = 'TEMPLATES'";
            $stmt = $db->query($query);
            $resDs = $stmt->fetchObject();
            $pathToDs = $resDs->path_template;
            $user->pathToSignature = $pathToDs . str_replace(
                    "#", 
                    DIRECTORY_SEPARATOR, 
                    $user->signature_path
                )
                . $user->signature_file_name;
        }
        put_in_session("users",$user->getArray());
    }

    if (
        ($_SESSION['m_admin']['load_group'] == true || ! isset($_SESSION['m_admin']['load_group'] )) 
        && $_SESSION['m_admin']['users']['user_id'] <> "superadmin"){
        $tmp_array = $uc->getGroups($_SESSION['m_admin']['users']['user_id']);
        for($i=0; $i<count($tmp_array);$i++){
            $group = $ugc->get($tmp_array[$i]['GROUP_ID']);
            $tmp_array[$i]['LABEL'] = $group->__get('group_desc');
        }
        $_SESSION['m_admin']['users']['groups'] = $tmp_array;
        unset($tmp_array);
    }
    return $state;
}

/**
 * Initialize session parameters for add display
 */
function display_add(){
    if(!isset($_SESSION['m_admin']['init'])){
        init_session();
    }
}

/**
 * Initialize session parameters for list display
 */
function display_list(){

    $_SESSION['m_admin'] = array();
    $list = new list_show();
    $func = new functions();
    init_session();

    $select[USERS_TABLE] = array();
    array_push($select[USERS_TABLE],'user_id','lastname','firstname','enabled','status','mail');
    $where = " ((status = 'OK' or status = 'ABS') and user_id != 'superadmin')";
    $what = '';
    $arrayPDO = array();
    if(isset($_REQUEST['what'])){
        $what = $_REQUEST['what'];
		$where .= " and (lower(lastname) like lower(?) or lower(users.user_id) like lower(?) or CONCAT(users.lastname,' ',users.firstname) like ?)";
        $arrayPDO = array($what.'%', $what.'%', $what);
    }

    // Checking order and order_field values
    $order = 'asc';
    if(isset($_REQUEST['order']) && !empty($_REQUEST['order'])){
        $order = trim($_REQUEST['order']);
    }

    $field = 'lastname';
    if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
        $field = trim($_REQUEST['order_field']);

    $orderstr = $list->define_order($order, $field);
    $request = new request();
	
	if($entities_loaded == true ){
		$tab=$request->PDOselect($select,$where,$arrayPDO,$orderstr,$_SESSION['config']['databasetype']);
    } else {
		require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php');
		$ent = new entity();
		$my_tab_entities_id = $ent->get_all_entities_id_user($_SESSION['user']['entities']);
		
		if($_SESSION['user']['UserId'] != 'superadmin'){
			$where = " ((status = 'OK' or status = 'ABS') and users.user_id != 'superadmin') and ((users_entities.entity_id is NULL) or users_entities.entity_id in (".join(',', $my_tab_entities_id)."))";
		}else{
			$where = " ((status = 'OK' or status = 'ABS') and users.user_id != 'superadmin')";
		}
		
		$what = '';
		if(isset($_REQUEST['what'])){
			$what = $_REQUEST['what'];
			$where .= " and (lower(lastname) like lower(?) or CONCAT(users.lastname,' ',users.firstname) like ?)";
            $arrayPDO = array($what.'%',$what);
		}

		// Checking order and order_field values
		$order = 'asc';
		if(isset($_REQUEST['order']) && !empty($_REQUEST['order'])){
			$order = trim($_REQUEST['order']);
		}

		$field = 'lastname';
		if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
			$field = trim($_REQUEST['order_field']);

		$orderstr = $list->define_order($order, $field);
		$tab=$request->PDOselect($select,$where,$arrayPDO,$orderstr,$_SESSION['config']['databasetype'], 'default', 'users_entities', 'users','users_entities', 'user_id', true, false, true);
	
	}
    for ($i=0;$i<count($tab);$i++) {
        foreach($tab[$i] as &$item) {
            switch ($item['column']){
                case "user_id":
                    format_item($item,_ID,"20","left","left","bottom",true); break;
                case "lastname":
                    format_item($item,_LASTNAME,"20","left","left","bottom",true); break;
                case "firstname":
                    format_item($item,_FIRSTNAME,"20","left","left","bottom",true); break;
                case "enabled":
                    format_item($item,_STATUS,"3","left","center","bottom",true); break;
                case "mail":
                    format_item($item,_MAIL,"27","left","left","bottom",true); break;
                case "status":
                    if($item['value'] == "ABS")
                        $item['value'] = "<em>("._MISSING.")</em>";
                    else
                        $item['value'] = '';
                    format_item($item,'',"5","left","left","bottom",true, false); break;
            }
        }
    }

    /*
     * TODO Pour éviter les actions suivantes, il y a 2 solutions :
     * - La plus propre : créer un objet "PageList"
     * - La plus locale : si cela ne sert que pour admin_list dans classification_scheme_management.php,
     *                    il est possible d'en construire directement la string et de la récupérer en return.
     */
    $result = array();
    $result['tab']=$tab;
    $result['what']=$what;
    $result['page_name'] = "users_management_controler&mode=list";
    $result['page_name_up'] = "users_management_controler&mode=up";
    $result['page_name_del'] = "users_management_controler&mode=del";
    $result['page_name_val']= "users_management_controler&mode=allow";
    $result['page_name_ban'] = "users_management_controler&mode=ban";
    $result['page_name_add'] = "users_management_controler&mode=add";
    $result['label_add'] = _USER_ADDITION;
    $_SESSION['m_admin']['init'] = true;
    $result['title'] = _USERS_LIST." : ".$i." "._USERS;
    $result['autoCompletionArray'] = array();
    $result['autoCompletionArray']["list_script_url"] = $_SESSION['config']['businessappurl']."index.php?display=true&admin=users&page=users_list_by_name";
    $result['autoCompletionArray']["number_to_begin"] = 1;
    return $result;
}

/**
 * Delete given user if exists and initialize session parameters
 * @param unknown_type $user_id
 */
function display_del($user_id){
    $uc = new users_controler();
    

    // information liste(s) de diffusion exists in users
    $listDiffusion=array();
    $db = new Database();
    $stmt = $db->query(
        "select * from listmodels WHERE item_id=? AND item_mode='dest'",
        array($user_id)
    );
    while ($res = $stmt->fetchObject()) {
        array_push($listDiffusion, $res->description);
    }

    if(!empty($listDiffusion)){ ?>
        <script type="text/javascript">window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?page='
            . 'users_management_controler&mode=check_del&admin=users&id=' . $user_id;
        ?>';</script>   
    <?php exit(); }




    $user = $uc->get($user_id);
    if(isset($user)) {
        // Deletion
        $control = array();
        $params = array( 'log_user_del' => $_SESSION['history']['usersdel'],
                         'databasetype' => $_SESSION['config']['databasetype']
                        );
        $control = $uc->delete($user, $params);
        if(!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _DELETED_USER.' : '.$user_id;
        }

        ?><script type="text/javascript">window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=users_management_controler&mode=list&admin=users&order=".functions::xssafe($_REQUEST['order'])."&order_field=".functions::xssafe($_REQUEST['order_field'])."&start=".functions::xssafe($_REQUEST['start'])."&what=".functions::xssafe($_REQUEST['what']);?>';</script>
        <?php 
        exit;
    }
    else{
        // Error management
        $_SESSION['error'] = _USER.' '._UNKNOWN;
    }
}


function display_del_check($user_id){


/****************Management of the location bar  ************/
        $admin = new core_tools();
        $db = new Database();
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
        $pagePath = $_SESSION['config']['businessappurl'] . 'index.php?page=users';
        $pageLabel = _DELETION;
        $pageId = "users";
        $admin->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);

        /***********************************************************/
        if(isset($_POST['user_id'])){
            $old_user=$_POST['id'];
            $new_user=$_POST['user_id'];

            $listDiffusion=array();
            
            $stmt = $db->query(
                "select * from listmodels WHERE item_id=? AND item_mode='dest'",
                array($user_id)
            );
            while ($res = $stmt->fetchObject()) {
                array_push($listDiffusion, $res->object_id);
            }

            // Mise à jour des enregistrements (egal suppression puis insertion)
            $listDiffusion_sql = "'".implode("','", $listDiffusion)."'";
            $db->query(
                "update listmodels set item_id=:newItemId where item_id=:oldItemId and object_id in (" .$listDiffusion_sql. ")",
                array(
                    ':newItemId' => $new_user,
                    ':oldItemId' => $old_user,
                    )
                );

            $_SESSION['info'] = _DELETED_USER.' : '.$old_user;

           ?>


            <script type="text/javascript">window.top.location='<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?page='
            . 'users_management_controler&mode=del&admin=users&id=' . $user_id;
        ?>';</script> <?php
        }

        $listDiffusion=array();
        $stmt = $db->query(
            "select * from listmodels list, entities it WHERE list.object_id = it.entity_id and item_id=? AND item_mode='dest'",
            array($user_id)
        );
        while ($res = $stmt->fetchObject()) {
            array_push($listDiffusion, $res->entity_label);
        }

        echo '<h1><i class="fa fa-users fa-2x"></i>'._USER_DELETION.': <i>'.$user_id.'</i></h1>';
        echo "<div class='error' id='main_error'>".$_SESSION['error']."</div>";
        $_SESSION['error'] = "";
        ?>
        <br>
        <div class="block">
        <div id="main_error" style="text-align:center;">
            <b><?php
            echo _WARNING_MESSAGE_DEL_USER;
            ?></b>
        </div>
        <br/>
        <form name="user_del" id="user_del" style="width: 350px;margin:auto;" method="post" class="forms">
            <input type="hidden" value="<?php functions::xecho($user_id);?>" name="id">
            <?php

                echo "<h3>".count($listDiffusion)." "._LISTE_DIFFUSION_IN_USER .":</h3>";
                echo "<ul>";
                foreach ($listDiffusion as $key => $value) {
                   echo "<li>".$value."</li>";
                }
                echo "</ul>";
                ?>
                <br>
                <br>
                <select name="user_id" id="user_id" onchange=''>
                    <option value="no_user"><?php echo _NO_REPLACEMENT;?></option>
                    <?php
                    $stmt = $db->query("select * from users order by user_id ASC");
                    while($users = $stmt->fetchObject())
                    {
                        if($users->user_id != $user_id){
                         ?>
                        <option value="<?php functions::xecho($users->user_id);?>"><?php functions::xecho($users->lastname . " " . $users->firstname);?></option>
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
 * Enable given user if exists and initialize session parameters
 * @param unknown_type $user_id
 */
function display_enable($user_id){
    $uc = new users_controler();
    $user = $uc->get($user_id);
    if(isset($user)){
        $control = array();
        $params = array();
        if(isset($_SESSION['history']['usersval'])){
            $params['log_user_enabled'] = $_SESSION['history']['usersval'];
        }
        if(isset($_SESSION['config']['databasetype'])){
            $params['databasetype'] = $_SESSION['config']['databasetype'];
        }
        else{
            $params['databasetype'] = 'POSTGRESQL';
        }

        $control = $uc->enable($user, $params);
        $_SESSION['error'] = '';
        if(!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _AUTORIZED_USER.' : '.$user_id;
        }

        ?><script type="text/javascript">
        window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=users_management_controler&mode=list&admin=users&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what'];?>';</script>
        <?php
        exit();
    }
    else{
        // Error management
        $_SESSION['error'] = _USER.' '._UNKNOWN;
    }
}

/**
 * Disable given user if exists and initialize session parameters
 * @param unknown_type $user_id
 */
function display_disable($user_id){
    $uc = new users_controler();
    $user = $uc->get($user_id);
    if(isset($user)){
        $control = array();
        $params = array();
        if(isset($_SESSION['history']['usersban'])){
            $params['log_user_disabled'] = $_SESSION['history']['usersban'];
        }
        if(isset($_SESSION['config']['databasetype'])){
            $params['databasetype'] = $_SESSION['config']['databasetype'];
        }
        else{
            $params['databasetype'] = 'POSTGRESQL';
        }

        $control = $uc->disable($user, $params);
        if(!empty($control['error']) && $control['error'] <> 1) {
            $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        } else {
            $_SESSION['info'] = _SUSPENDED_USER.' : '.$user_id;
        }

        ?><script type="text/javascript">window.top.location='<?php echo $_SESSION['config']['businessappurl']."index.php?page=users_management_controler&mode=list&admin=users&order=".$_REQUEST['order']."&order_field=".$_REQUEST['order_field']."&start=".$_REQUEST['start']."&what=".$_REQUEST['what'];?>';</script>
        <?php
        exit();
    }
    else{
        // Error management
        $_SESSION['error'] = _USER.' '._UNKNOWN;
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
 * @param $label_align
 * @param $align
 * @param $valign
 * @param $show
 */
function format_item(&$item,$label,$size,$label_align,$align,$valign,$show,$order= true){
    $func = new functions();
    $item['value']=$func->show_string($item['value']);
    $item[$item['column']]=$item['value'];
    $item["label"]=$label;
    $item["size"]=$size;
    $item["label_align"]=$label_align;
    $item["align"]=$align;
    $item["valign"]=$valign;
    $item["show"]=$show;
    if($order){
        $item["order"]=$item['value'];
    }
    else{
        $item["order"]='';
    }
}

/**
 * Validate a submit (add or up),
 * up to saving object
 */
function validate_user_submit()
{
    $uc = new users_controler();
    $pageName = "users_management_controler";

    $mode = $_REQUEST['mode'];
    $user = new users();
    $user->user_id=$_REQUEST['user_id'];
    $_SESSION['m_admin']['users']['user_id']=$_REQUEST['user_id'];

    if(isset($_REQUEST['reactivate'])){
        $mode='up';
        $uc->reactivate($user);
    }

    if($mode == "add"){
        if(isset($_SESSION['config']['userdefaultpassword']) && !empty($_SESSION['config']['userdefaultpassword'])){
            $user->password = $_SESSION['config']['userdefaultpassword'];
        }
        else{
            $user->password = 'maarch';
        }
    }
    $user->firstname = $_REQUEST['FirstName'];
    $user->lastname = $_REQUEST['LastName'];
    if(isset($_REQUEST['Department']) && !empty($_REQUEST['Department'])){
        $user->department  = $_REQUEST['Department'];
    }
    if(isset($_REQUEST['Phone']) && !empty($_REQUEST['Phone'])){
        $user->phone  = $_REQUEST['Phone'];
    }
    if(isset($_REQUEST['LoginMode']) && !empty($_REQUEST['LoginMode'])){
        $user->loginmode  = $_REQUEST['LoginMode'];
    }
    if(isset($_REQUEST['Mail']) && !empty($_REQUEST['Mail'])){
        $user->mail  = $_REQUEST['Mail'];
    }
	if(isset($_REQUEST['thumbprint']) && !empty($_REQUEST['thumbprint'])){
        $user->thumbprint  = trim($_REQUEST['thumbprint']);
    }

    if (isset($_FILES['signature']['name']) && !empty($_FILES['signature']['name'])) {
        $extension = explode(".", $_FILES['signature']['name']);
        $count_level = count($extension)-1;
        $the_ext = $extension[$count_level];
        $fileNameOnTmp = 'tmp_file_' . $_SESSION['user']['UserId']
            . '_' . rand() . '.' . strtolower($the_ext);
        $filePathOnTmp = $_SESSION['config']['tmppath'] . $fileNameOnTmp;
        
        if (!is_uploaded_file($_FILES['signature']['tmp_name'])) {
                $_SESSION['error'] = _FILE_NOT_SEND . ". " . _TRY_AGAIN
                    . ". " . _MORE_INFOS . " (<a href=\"mailto:"
                    . $_SESSION['config']['adminmail'] . "\">"
                    . $_SESSION['config']['adminname'] . "</a>)";
        } elseif (!@move_uploaded_file($_FILES['signature']['tmp_name'], $filePathOnTmp)) {
            $_SESSION['error'] = _FILE_NOT_SEND . ". " . _TRY_AGAIN . ". "
                . _MORE_INFOS . " (<a href=\"mailto:"
                . $_SESSION['config']['adminmail'] . "\">"
                . $_SESSION['config']['adminname'] . "</a>)";
        } else {
            require_once 'core/docservers_tools.php';
            $arrayIsAllowed = array();
            $arrayIsAllowed = Ds_isFileTypeAllowed($filePathOnTmp);
            if (strtolower($the_ext) <> 'jpg' && strtolower($the_ext) <> 'jpeg') {
                $_SESSION['error'] = _WRONG_FILE_TYPE . ' ' . $arrayIsAllowed['mime_type'];
                $_SESSION['upfile'] = array();
            } else if ($arrayIsAllowed['status'] == false) {
                $_SESSION['error'] = _WRONG_FILE_TYPE . ' ' . $arrayIsAllowed['mime_type'];
                $_SESSION['upfile'] = array();
            } else {
                include_once 'core/class/docservers_controler.php';
                $docservers_controler = new docservers_controler();
                $fileTemplateInfos = array(
                    'tmpDir'      => $_SESSION['config']['tmppath'],
                    'size'        => $_FILES['signature']['size'],
                    'format'      => $the_ext,
                    'tmpFileName' => $fileNameOnTmp,
                );
                $storeInfos = $docservers_controler->storeResourceOnDocserver(
                    'templates',
                    $fileTemplateInfos
                );
                if (!file_exists(
                        $storeInfos['path_template']
                        .  str_replace("#", DIRECTORY_SEPARATOR, $storeInfos['destination_dir'])
                        . $storeInfos['file_destination_name']
                    )
                ) {
                    $_SESSION['error'] = _FILE_NOT_EXISTS . ' : ' . $storeInfos['path_template']
                        .  str_replace("#", DIRECTORY_SEPARATOR, $storeInfos['destination_dir'])
                        . $storeInfos['file_destination_name'];
                    return false;
                } else {
                    $user->signature_path  = $storeInfos['destination_dir'];
                    $user->signature_file_name  = $storeInfos['file_destination_name'];
                }
            }
        }
    }
    
    $status= array();
    $status['order']=$_REQUEST['order'];
    $status['order_field']=$_REQUEST['order_field'];
    $status['what']=$_REQUEST['what'];
    $status['start']=$_REQUEST['start'];

    if(isset($_SESSION['config']['userdefaultpassword']) && !empty($_SESSION['config']['userdefaultpassword'])){
        $tmp_pass = $_SESSION['config']['userdefaultpassword'];
    }
    else{
        $tmp_pass = 'maarch';
    }

    $control = array();
    $params = array('modules_services' => $_SESSION['modules_services'],
                    'log_user_up' => $_SESSION['history']['usersup'],
                    'log_user_add' => $_SESSION['history']['usersadd'],
                    'databasetype' => $_SESSION['config']['databasetype'],
                    'userdefaultpassword' => $tmp_pass,
                    );

    if (isset($_SESSION['m_admin']['users']['groups'])) {
        $control = $uc->save($user, $_SESSION['m_admin']['users']['groups'], $mode, $params);
    }
    if (!empty($control['error']) && $control['error'] <> 1) {
        // Error management depending of mode
        $_SESSION['error'] = str_replace("#", "<br />", $control['error']);
        put_in_session("status", $status);
        put_in_session("users",$user->getArray());
        switch ($mode) {
            case "up":
                if(!empty($user->user_id)) {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=up&id=".$user->user_id."&admin=users");
                } else {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=users&order=".$status['order']."&order_field=".$status['order_field']."&start=".$status['start']."&what=".$status['what']);
                }
                exit;
            case "add":
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=add&admin=users");
                exit;
        }
    } else {
        if($mode == "add"){
            $_SESSION['info'] = _USER_ADDED;
        }
         else{
            $_SESSION['info'] = _USER_UPDATED;
        }
        unset($_SESSION['m_admin']);
        header("location: ".$_SESSION['config']['businessappurl']."index.php?page=".$pageName."&mode=list&admin=users&order=".$status['order']."&order_field=".$status['order_field']."&start=".$status['start']."&what=".$status['what']);
    }
}

function init_session(){
    $_SESSION['m_admin']['users'] = array();
    $_SESSION['m_admin']['users']['groups'] = array();
    $_SESSION['m_admin']['users']['nbbelonginggroups'] = 0;
    $_SESSION['m_admin']['init'] = false ;
    $_SESSION['m_admin']['load_group']  = true;
}

/**
 * Put given object in session, according with given type
 * NOTE: given object needs to be at least hashable
 * @param string $type
 * @param hashable $hashable
 */
function put_in_session($type,$hashable, $show_string = true){
    $func = new functions();
    foreach($hashable as $key=>$value){
        if ($show_string){
            $_SESSION['m_admin'][$type][$key]=$func->show_string($value);
        }
        else{
            $_SESSION['m_admin'][$type][$key]=$value;
        }
    }
}
?>
