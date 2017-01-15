<?php

// Group - Basket Form : actions params
require_once 'apps/' . $_SESSION['config']['app_id'] . '/apps_tables.php';
require_once 'modules/entities/class/class_manage_listdiff.php';

$diffListObj = new diffusion_list();

if ($_SESSION['service_tag'] == 'group_basket') {
    $current_groupbasket = $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']];
    $current_compteur = $_SESSION['m_admin']['compteur'];
    // This param is only for the actions with the keyword : workflow
    if (trim($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['KEYWORD']) == 'workflow') {
        $_SESSION['m_admin']['show_where_clause'] = false;
        $is_default_action = false;
        // Is the action the default action for the group on this basket ?
        if (
            isset($current_groupbasket['DEFAULT_ACTION'])
            && $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'] == $current_groupbasket['DEFAULT_ACTION']
        ) {
            $is_default_action = true;
        }
        //retrieve roles
        $diffListRoles = $diffListObj->list_difflist_roles();
        ?>
        <br />
        <table>
            <tr>
                <td>
                    <b><?php echo _TARGET_STATUS;?> :</b>
                </td>
                <td>
                    <select name ="<?php
                        echo $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'];
                        ?>_statuses_chosen[]" id ="<?php
                        echo $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'];
                        ?>_statuses_chosen[]">
                        <option value="_NOSTATUS_"><?php echo _UNCHANGED;?></option>
                        <?php
                        // Browse all the statuses
                        for ($cpt=0;$cpt<count($_SESSION['m_admin']['statuses']);$cpt++) {
                            $selected= '';
                            if (isset($current_groupbasket['ACTIONS'])) {
                                for ($j=0;$j<count($current_groupbasket['ACTIONS']);$j ++) {
                                    for ($k=0;$k<count($current_groupbasket['ACTIONS'][$j]['STATUSES_LIST']);$k ++) {
                                        if ($_SESSION['m_admin']['statuses'][$cpt]['id'] == $current_groupbasket['ACTIONS'][$j]['STATUSES_LIST'][$k]['ID']) {
                                            $state_status = true;
                                            $selected = ' selected="selected" ';
                                        }
                                    }
                                }
                            }
                            ?>
                            <option value=<?php
                                echo $_SESSION['m_admin']['statuses'][$cpt]['id'] . ' ' . $selected;?> ><?php
                                echo $_SESSION['m_admin']['statuses'][$cpt]['label'];?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <b><?php echo _TARGET_ROLE;?> :</b>
                </td>
                <td>
                    <select name ="<?php
                        echo $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'];
                        ?>_roles_chosen[]" id ="<?php
                        echo $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'];
                        ?>_roles_chosen[]">
                        <?php
                        // Browse all the roles
                        foreach ($diffListRoles as $diffListRolesKey => $diffListRolesValue) {
                            if ($diffListRolesKey <> 'dest' && $diffListRolesKey <> 'copy') {
                                $selected= '';
                                if (isset($current_groupbasket['ACTIONS'])) {
                                    for ($j = 0;$j<count($current_groupbasket['ACTIONS']);$j++) {
                                        for ($k=0;$k<count($current_groupbasket['ACTIONS'][$j]['ROLES_LIST']);$k ++) {
                                            if ($diffListRolesKey == $current_groupbasket['ACTIONS'][$j]['ROLES_LIST'][$k]['ID']) {
                                                $state_roles = true;
                                                $selected = ' selected="selected" ';
                                            }
                                        }
                                    }
                                }
                                ?>
                                <option value=<?php
                                    echo $diffListRolesKey . ' ' . $selected;?> ><?php
                                    echo $diffListRolesValue;?>
                                </option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
} elseif ($_SESSION['service_tag'] == 'manage_groupbasket') {
    $db = new Database();

/*
    echo 'before<br>';
    echo 'param workflow';
    $db->show_array($_SESSION['m_admin']['basket']['groups']);
    exit;
*/
    $groupe = $_REQUEST['group'];
    if (isset($_REQUEST['old_group']) && !empty($_REQUEST['old_group'])) {
        $old_group = $_REQUEST['old_group'];
    }
    $ind = -1;
    $find = false;
    for ($cpt=0;$cpt<count($_SESSION['m_admin']['basket']['groups']);$cpt++) {
        if (
            $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'] == $groupe
            || $old_group == $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']) {
            for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++) {
                //STATUS
                $chosen_statuses = array();
                if (
                    isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen'])
                    && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen']) > 0
                ) {
                    for ($k=0; $k < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen']); $k++) {
                        $statusId = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen'][$k];
                        $stmt = $db->query("SELECT label_status FROM " .$_SESSION['tablename']['status']. " WHERE id = ?",array($statusId));
                        $res = $stmt->fetchObject();
                        $label = $res->label_status;
                        array_push($chosen_statuses , array('ID' => $statusId, 'LABEL' => $label));
                    }
                }
                //ROLES
                $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ROLES_LIST'] = $chosen_roles ;
                 $chosen_roles = array();
                if (
                    isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_roles_chosen'])
                    && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_roles_chosen']) > 0
                ) {
                    for ($k=0;$k<count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_roles_chosen']);$k++) {
                        $roleId = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_roles_chosen'][$k];
                        array_push($chosen_roles , array('ID' => $roleId));
                    }
                }
                $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ROLES_LIST'] = $chosen_roles ;
            }
            if ($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'] == $groupe) {
                $ind = $cpt;
                $find = true;
                break;
            }
            if ($old_group == $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']) {
                $ind = $cpt;
                $find = true;
                break;
            }
        }
    }

    if ($find && $ind >= 0) {
        //STATUS
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] = array();
        if (
            isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen'])
            && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen']) > 0
        ) {
            for ($l=0; $l < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen']); $l++) {
                $statusId = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen'][$l];
                $stmt = $db->query("SELECT label_status FROM " .$_SESSION['tablename']['status']. " WHERE id = ?",array($statusId));
                $res = $stmt->fetchObject();
                $label = $res->label_status;
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] , array('ID' =>$statusId, 'LABEL' => $label));
            }
        }
        //ROLES
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['ROLES_LIST'] = array();
        if (
            isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_roles_chosen'])
            && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_roles_chosen']) > 0
        ) {
            for ($l=0;$l<count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_roles_chosen']);$l++) {
                $roleId = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_roles_chosen'][$l];
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['ROLES_LIST'] , array('ID' =>$roleId));
            }
        }
    }
    $_SESSION['m_admin']['load_groupbasket'] = false;

/*
    echo 'after<br>';
    echo 'param workflow';
    $ent->show_array($_SESSION['m_admin']['basket']['groups']);
    exit;
*/

} elseif ($_SESSION['service_tag'] == 'load_basket_session') {
    $db = new Database();
    for ($cpt=0;$cpt<count($_SESSION['m_admin']['basket']['groups']);$cpt++) {
        //STATUS
        $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] = array();
        if (!empty($_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'])) {
            $query = "SELECT status_id, label_status FROM " . GROUPBASKET_STATUS . " left join " . $_SESSION['tablename']['status']
                . " on status_id = id "
                . " where basket_id= ?"
                . " and group_id = ?"
                . " and action_id = ?";
            $stmt = $db->query($query,array(trim($_SESSION['m_admin']['basket']['basketId']),trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']),$_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION']));
            $array = array();
            while ($status = $stmt->fetchObject()) {
                $array[] = array('ID' => $status->status_id, 'LABEL' => $status->label_status);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] = $array;
        }
        //ROLES
        $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['ROLES_LIST'] = array();
        if (!empty($_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'])) {
            $query = "SELECT difflist_role_id FROM " . ENT_GROUPBASKET_DIFFLIST_ROLES 
                . " where basket_id= ?"
                . " and group_id = ?"
                . " and action_id = ?";
            $stmt = $db->query($query,array(trim($_SESSION['m_admin']['basket']['basketId']),trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']),$_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION']));
            $array = array();
            while ($roles = $stmt->fetchObject()) {
                $array[] = array('ID' => $roles->difflist_role_id);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['ROLES_LIST'] = $array;
        }
        //STATUS
        for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++) {
            $query = "SELECT status_id, label_status FROM " . GROUPBASKET_STATUS . " left join " . $_SESSION['tablename']['status']
                . " on status_id = id "
                . " where basket_id= ?" 
                . " and group_id = ?" 
                . " and action_id = ?";
            $stmt = $db->query($query,array(trim($_SESSION['m_admin']['basket']['basketId']),trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']),$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION']));
            $array = array();
            while ($status = $stmt->fetchObject()) {
                $array[] = array('ID' => $status->status_id, 'LABEL' => $status->label_status);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['STATUSES_LIST'] = $array;
        }
        $j=0;
        //ROLES
        for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++) {
            $query = "SELECT difflist_role_id FROM " . ENT_GROUPBASKET_DIFFLIST_ROLES
                . " where basket_id= ?"
                . " and group_id = ?"
                . " and action_id = ?";
            $stmt = $db->query($query,array(trim($_SESSION['m_admin']['basket']['basketId']),trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']),$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION']));
            $array = array();
            while ($roles = $stmt->fetchObject()) {
                $array[] = array('ID' => $roles->difflist_role_id);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ROLES_LIST'] = $array;
        }
    }
} elseif ($_SESSION['service_tag'] == 'load_basket_db') {
    $db = new Database();
    $workflow_actions = array();
    for ($cpt=0; $cpt<count($_SESSION['m_admin']['basket']['all_actions']);$cpt++ ) {
        if ($_SESSION['m_admin']['basket']['all_actions'][$cpt]['KEYWORD'] == 'workflow') {
            array_push($workflow_actions,$_SESSION['m_admin']['basket']['all_actions'][$cpt]['ID']);
        }
    }
    for ($cpt=0; $cpt < count($_SESSION['m_admin']['basket']['groups'] ); $cpt++) {
        $GroupBasket = $_SESSION['m_admin']['basket']['groups'][$cpt];
        if (!empty($GroupBasket['DEFAULT_ACTION']) && in_array($GroupBasket['DEFAULT_ACTION'], $workflow_actions)) {
            //STATUS
            $stmt = $db->query(
                "DELETE FROM " . GROUPBASKET_STATUS
                . " where basket_id= ? and group_id = ? and action_id = ?",array(trim($_SESSION['m_admin']['basket']['basketId']),trim($GroupBasket['GROUP_ID']),$GroupBasket['DEFAULT_ACTION'])
            );
            //ROLES
            $stmt = $db->query(
                "DELETE FROM " . ENT_GROUPBASKET_DIFFLIST_ROLES
                . " where basket_id= ? and group_id = ? and action_id = ?",array(trim($_SESSION['m_admin']['basket']['basketId']),trim($GroupBasket['GROUP_ID']),$GroupBasket['DEFAULT_ACTION'])
            );
            //STATUS
            for ($k = 0; $k < count($GroupBasket['PARAM_DEFAULT_ACTION']['STATUSES_LIST']); $k++) {
                $Status = $GroupBasket['PARAM_DEFAULT_ACTION']['STATUSES_LIST'][$k];
                if ($Status['ID'] <> '_NOSTATUS_') {
                    $stmt = $db->query(
                        "INSERT INTO " . GROUPBASKET_STATUS
                        . " (group_id, basket_id, action_id, status_id) values (?, ?, ?, ?)",array(trim($GroupBasket['GROUP_ID']),trim($_SESSION['m_admin']['basket']['basketId']),$GroupBasket['DEFAULT_ACTION'],$Status['ID'])
                    );
                }
            }
            //ROLES
            for ($k=0;$k<count($GroupBasket['PARAM_DEFAULT_ACTION']['ROLES_LIST']);$k++) {
                $Roles = $GroupBasket['PARAM_DEFAULT_ACTION']['ROLES_LIST'][$k];
                $stmt = $db->query(
                    "INSERT INTO " . ENT_GROUPBASKET_DIFFLIST_ROLES
                    . " (group_id, basket_id, action_id, difflist_role_id) values (?, ?, ?, ?)",array(trim($GroupBasket['GROUP_ID']),trim($_SESSION['m_admin']['basket']['basketId']),$GroupBasket['DEFAULT_ACTION'],$Roles['ID'])
                );
            }
        }
        for ($j=0;$j<count($GroupBasket['ACTIONS']);$j++) {
            $GroupBasketAction = $GroupBasket['ACTIONS'][$j];
            if (in_array($GroupBasketAction['ID_ACTION'], $workflow_actions)) {
                //STATUS
                $stmt = $db->query(
                    "DELETE FROM " . GROUPBASKET_STATUS
                    . " where basket_id= ? and group_id = ? and action_id = ?",array(trim($_SESSION['m_admin']['basket']['basketId']),trim($GroupBasket['GROUP_ID']),$GroupBasketAction['ID_ACTION'])
                );
                //ROLES
                $stmt = $db->query(
                    "DELETE FROM " . ENT_GROUPBASKET_DIFFLIST_ROLES
                    . " where basket_id= ? and group_id = ? and action_id = ?",array(trim($_SESSION['m_admin']['basket']['basketId']),trim($GroupBasket['GROUP_ID']),$GroupBasketAction['ID_ACTION'])
                );
                //STATUS
                if (isset($GroupBasketAction['STATUSES_LIST'])) {
                    for ($k = 0; $k < count($GroupBasketAction['STATUSES_LIST']); $k++) {
                        $Status = $GroupBasketAction['STATUSES_LIST'][$k];
                        if ($Status['ID'] <> '_NOSTATUS_') {
                            $db->query(
                                "INSERT INTO " . GROUPBASKET_STATUS
                                . " (group_id, basket_id, action_id, status_id) values (?, ?, ?, ?)",array(trim($GroupBasket['GROUP_ID']),trim($_SESSION['m_admin']['basket']['basketId']),$GroupBasketAction['ID_ACTION'],$Status['ID'])
                            );
                        }
                    }
                }
                //ROLES
                 if (isset($GroupBasketAction['ROLES_LIST'])) {
                    for ($k = 0; $k < count($GroupBasketAction['ROLES_LIST']); $k++) {
                        $Roles = $GroupBasketAction['ROLES_LIST'][$k];
                        $stmt = $db->query(
                            "INSERT INTO " . ENT_GROUPBASKET_DIFFLIST_ROLES
                            . " (group_id, basket_id, action_id, difflist_role_id) values (?, ?, ?, ?)",array(trim($GroupBasket['GROUP_ID']),trim($_SESSION['m_admin']['basket']['basketId']),$GroupBasketAction['ID_ACTION'],$Roles['ID'])
                        );
                    }
                }
            }
        }
    }
} else if ($_SESSION['service_tag'] == 'del_basket' && !empty($_SESSION['temp_basket_id'])) {
    $db = new Database();
    $stmt = $db->query("delete from ".GROUPBASKET_STATUS." where basket_id = ?",array($_SESSION['temp_basket_id']));
    $stmt = $db->query("delete from ".ENT_GROUPBASKET_DIFFLIST_ROLES." where basket_id = ?",array($_SESSION['temp_basket_id']));
    unset($_SESSION['temp_basket_id']);
}
