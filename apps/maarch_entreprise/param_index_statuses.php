<?php

/*
*    Copyright 2008,2015 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

// Group - Basket Form : actions params
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'apps_tables.php';
    
if($_SESSION['service_tag'] == 'group_basket')
{
    $current_groupbasket = $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']];
    $current_compteur = $_SESSION['m_admin']['compteur'];
    // This param is only for the actions with the keyword : indexing
    if(trim($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['KEYWORD']) == 'indexing') // Indexing case
    {
        $_SESSION['m_admin']['show_where_clause'] = false;
        $is_default_action = false;
        // Is the action the default action for the group on this basket ?
        if( isset($current_groupbasket['DEFAULT_ACTION']) && $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'] == $current_groupbasket['DEFAULT_ACTION'])
        {
            $is_default_action = true;
        }
        // indexing statuses list
        ?>
    <p>
        <label><?php echo _INDEXING_STATUSES;?> :</label>
    </p>
    <table align="center" width="100%" id="index_status_baskets" >
        <tr>
            <td width="40%" align="center">
                <select data-placeholder=" " name="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID']);?>_statuses_chosen[]" id="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID']);?>_statuses_chosen" size="7" multiple="multiple"  class="statuses_list" style="width:100%;">
                <?php
                // Browse all the statuses
                for ($cpt = 0; $cpt < count($_SESSION['m_admin']['statuses']); $cpt ++) {
                    $state_status = "";
                    if (! $is_default_action ) {
                        if (isset($current_groupbasket['ACTIONS'])) {
                            for ($j = 0; $j < count($current_groupbasket['ACTIONS']); $j ++) {
                                for ($k = 0; $k < count($current_groupbasket['ACTIONS'][$j]['STATUSES_LIST']); $k ++) {
                                    if ($_SESSION['m_admin']['statuses'][$cpt]['id'] == $current_groupbasket['ACTIONS'][$j]['STATUSES_LIST'][$k]['ID']) {

                                        $state_status = 'selected="selected"';
                                    }
                                }
                            }
                        }
                    } else {
                        for ($k = 0; $k < count($current_groupbasket['PARAM_DEFAULT_ACTION']['STATUSES_LIST']); $k ++)
                        {
                            if ($_SESSION['m_admin']['statuses'][$cpt]['id'] == $current_groupbasket['PARAM_DEFAULT_ACTION']['STATUSES_LIST'][$k]['ID']) {
                                $state_status = 'selected="selected"';
                            }
                        }
                    }
                    ?>
                    <option <?php echo $state_status; ?> value="<?php functions::xecho($_SESSION['m_admin']['statuses'][$cpt]['id']);?>"><?php functions::xecho($_SESSION['m_admin']['statuses'][$cpt]['label']); ?></option>
                <?php
                }
                ?>
                </select>
                <style>#index_status_baskets .chosen-container{width:95% !important;}</style>
            </td>
        </tr>
    </table>
    <?php
    }
}
elseif($_SESSION['service_tag'] == 'manage_groupbasket')
{
    $db = new Database();
    $groupe = $_REQUEST['group'];
    if(isset($_REQUEST['old_group']) && !empty($_REQUEST['old_group']))
    {
        $old_group = $_REQUEST['old_group'];
    }
    $ind = -1;
    $find = false;
    for ($cpt=0;$cpt<count($_SESSION['m_admin']['basket']['groups']);$cpt++) {
        if (
            $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'] == $groupe 
            || $old_group == $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']) {
            for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++) {
                $chosen_statuses = array();
                if (isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen']) > 0) {
                    for ($k=0; $k < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen']); $k++) {
                        $statusId = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_statuses_chosen'][$k];
                        $stmt = $db->query("SELECT label_status FROM " .$_SESSION['tablename']['status']. " WHERE id = ?", array($statusId));
                        $res = $stmt->fetchObject();
                        $label = $res->label_status;
                        array_push($chosen_statuses , array( 'ID' => $statusId, 'LABEL' => $label));
                    }
                }
                $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['STATUSES_LIST'] = $chosen_statuses ;
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
        //$_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION'] = array();
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] = array();

        if (isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen']) > 0) {
            for ($l=0; $l < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen']); $l++) {
                $statusId = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_statuses_chosen'][$l];
                $stmt = $db->query("SELECT label_status FROM " .$_SESSION['tablename']['status']. " WHERE id = ?", array($statusId));
                $res = $stmt->fetchObject();
                $label = $res->label_status;
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] , array( 'ID' =>$statusId, 'LABEL' => $label));
            }
        }
    }
    $_SESSION['m_admin']['load_groupbasket'] = false;
}
elseif($_SESSION['service_tag'] == 'load_basket_session')
{
    $db = new Database();
    
    for($cpt=0; $cpt < count($_SESSION['m_admin']['basket']['groups'] ); $cpt++)
    {
        $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] = array();
        if(!empty($_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'] ))
        {
            $query = "SELECT status_id, label_status FROM " . GROUPBASKET_STATUS . " left join " . $_SESSION['tablename']['status'] 
                . " on status_id = id "
                . " WHERE basket_id= ? and group_id = ? and action_id = ?";
            $arrayPDO = array(trim($_SESSION['m_admin']['basket']['basketId']), trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']), $_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION']);
            $stmt = $db->query($query, $arrayPDO);
            $array = array();
            while($status = $stmt->fetchObject()) {
                $array[] = array('ID' => $status->status_id, 'LABEL' => $status->label_status);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['STATUSES_LIST'] = $array;
        }
        for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++)
        {
            
            $query = "SELECT status_id, label_status FROM " . GROUPBASKET_STATUS . " left join " . $_SESSION['tablename']['status'] 
                . " on status_id = id "
                . " where basket_id= ? and group_id = ? and action_id = ?";
            $arrayPDO = array(trim($_SESSION['m_admin']['basket']['basketId']), trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']), $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION']);
            $stmt = $db->query($query, $arrayPDO);
            $array = array();
            while($status = $stmt->fetchObject()) {
                $array[] = array('ID' => $status->status_id, 'LABEL' => $status->label_status);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['STATUSES_LIST'] = $array;
        }
    }
}
elseif($_SESSION['service_tag'] == 'load_basket_db')
{
    $db = new Database();

    $indexing_actions = array();
    for($cpt=0; $cpt<count($_SESSION['m_admin']['basket']['all_actions']);$cpt++ )
    {
        if($_SESSION['m_admin']['basket']['all_actions'][$cpt]['KEYWORD'] == 'indexing')
        {
            array_push($indexing_actions,$_SESSION['m_admin']['basket']['all_actions'][$cpt]['ID']);
        }
    }

    for($cpt=0; $cpt < count($_SESSION['m_admin']['basket']['groups'] ); $cpt++)
    {
        $GroupBasket = $_SESSION['m_admin']['basket']['groups'][$cpt];
        if(!empty($GroupBasket['DEFAULT_ACTION']) && in_array($GroupBasket['DEFAULT_ACTION'], $indexing_actions))
        {   
            $db->query(
            "DELETE FROM " . GROUPBASKET_STATUS
            . " WHERE basket_id= ? and group_id = ? and action_id = ?", array(trim($_SESSION['m_admin']['basket']['basketId']), trim($GroupBasket['GROUP_ID']), $GroupBasket['DEFAULT_ACTION']));
            
            for ($k = 0; $k < count($GroupBasket['PARAM_DEFAULT_ACTION']['STATUSES_LIST']); $k++) {
                $Status = $GroupBasket['PARAM_DEFAULT_ACTION']['STATUSES_LIST'][$k];
                $db->query(
                    "INSERT INTO " . GROUPBASKET_STATUS
                    . " (group_id, basket_id, action_id, status_id) values (?, ?, ?, ?)",
                    array(trim($GroupBasket['GROUP_ID']), trim($_SESSION['m_admin']['basket']['basketId']), $GroupBasket['DEFAULT_ACTION'], $Status['ID'])
                );
            }
        }
        for($j=0;$j<count($GroupBasket['ACTIONS']);$j++)
        {
            $GroupBasketAction = $GroupBasket['ACTIONS'][$j];
            if(in_array($GroupBasketAction['ID_ACTION'], $indexing_actions)) {
                //$ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'],$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['STATUSES_LIST']);
                $db->query(
                "DELETE FROM " . GROUPBASKET_STATUS
                . " where basket_id= ? and group_id = ? and action_id = ?",
                array(trim($_SESSION['m_admin']['basket']['basketId']), trim($GroupBasket['GROUP_ID']), $GroupBasketAction['ID_ACTION']));
                if (isset($GroupBasketAction['STATUSES_LIST'])) {
                    for ($k = 0; $k < count($GroupBasketAction['STATUSES_LIST']); $k++) {
                        $Status = $GroupBasketAction['STATUSES_LIST'][$k];
                        $db->query(
                            "INSERT INTO " . GROUPBASKET_STATUS
                            . " (group_id, basket_id, action_id, status_id) values (?, ?, ?, ?)",
                        array(trim($GroupBasket['GROUP_ID']), trim($_SESSION['m_admin']['basket']['basketId']), $GroupBasketAction['ID_ACTION'], $Status['ID'])
                        );
                    }
                }
            }
        }
    }
}
else if($_SESSION['service_tag'] == 'del_basket' && !empty($_SESSION['temp_basket_id']))
{
    $db = new Database();
    $db->query("DELETE FROM ".GROUPBASKET_STATUS." WHERE basket_id = ?", array($_SESSION['temp_basket_id']));
    unset($_SESSION['temp_basket_id']);
}
