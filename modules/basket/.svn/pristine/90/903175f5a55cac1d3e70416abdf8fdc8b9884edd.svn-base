<?php
/*
 *   Copyright 2008-2016 Maarch
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
 * @defgroup basket Basket Module
 */

/**
 * @brief   Module Basket :  Module Tools Class
 *
 * <ul>
 * <li>Set the session variables needed to run the basket module</li>
 * <li>Loads the baskets for the current user</li>
 * <li>Manage the current basket with its actions (if any)</li>
 *</ul>
 *
 * @file
 * @author Claire Figueras <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup basket
 */


require_once 'core/class/SecurityControler.php';
require_once 'core/class/class_security.php';
require_once 'core/core_tables.php';
require_once 'modules/basket/basket_tables.php';
require_once 'modules/entities/entities_tables.php';


abstract class basket_Abstract extends Database
{
    /**
     * Loads basket  tables into sessions vars from the basket/xml/config.xml
     * Loads basket log setting into sessions vars from the basket/xml/config.xml
     */
    public function build_modules_tables()
    {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'basket' . DIRECTORY_SEPARATOR . 'xml'
            . DIRECTORY_SEPARATOR . 'config.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
            . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
            . 'basket' . DIRECTORY_SEPARATOR . 'xml' .DIRECTORY_SEPARATOR
            . 'config.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'basket'
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'config.xml';
        }
        $xmlconfig = simplexml_load_file($path);

        $config = $xmlconfig->CONFIG;
        $_SESSION['config']['basket_reserving_time'] = (string) $config->reserving_time;

        // Loads the tables of the module basket  into session
        // ($_SESSION['tablename'] array)
        $tablename = $xmlconfig->TABLENAME;
        $_SESSION['tablename']['bask_baskets'] = (string) $tablename->bask_baskets;
        $_SESSION['tablename']['bask_groupbasket'] = (string) $tablename->bask_groupbasket;
        $_SESSION['tablename']['bask_users_abs'] = (string) $tablename->bask_users_abs;
        $_SESSION['tablename']['bask_actions_groupbaskets'] = (string) $tablename->bask_actions_groupbaskets;

        // Loads the log setting of the module basket  into session
        // ($_SESSION['history'] array)
        $history = $xmlconfig->HISTORY;
        $_SESSION['history']['basketup'] = (string) $history->basketup;
        $_SESSION['history']['basketadd'] = (string) $history->basketadd;
        $_SESSION['history']['basketdel'] = (string) $history->basketdel;
        $_SESSION['history']['basketval'] = (string) $history->basketval;
        $_SESSION['history']['basketban'] = (string) $history->basketban;
        $_SESSION['history']['userabs'] = (string) $history->userabs;
    }

    /**
     * Load into session vars all the basket specific vars : calls private
     * methods
     */
    public function load_module_var_session($userData)
    {
        //$this->show_array($userData);
        $_SESSION['user']['baskets'] = array();
        $this->_loadActivityUser($userData['UserId']);
        $this->_loadBasketsPages();

        if (isset($userData['primarygroup']) && isset($userData['UserId'])) {
            $_SESSION['user']['baskets'] = $this->load_basket(
                $userData['primarygroup'], $userData['UserId']
            );
            
        }
        //secondary baskets
        $db = new Database();
        $checkedBasket = false;
        $query = "select ubs.group_id, ubs.basket_id, ug.group_desc"
               . " from user_baskets_secondary ubs, usergroups ug"
               . " where ubs.group_id = ug.group_id and ubs.user_id = ? order by ug.group_desc";
        $stmt = $db->query($query, array($userData['UserId']));
        while($resQuery = $stmt->fetchObject()) {
            array_push($_SESSION['user']['baskets'], $this->loadBasketSecondary(
                    $userData['UserId'], $resQuery->group_id, $resQuery->basket_id
                )
            );
        }

        if (isset($userData['primarygroup']) && isset($userData['UserId'])) {
            $absBasketsArr = $this->load_basket_abs($userData['UserId']);
            $_SESSION['user']['baskets'] = array_merge(
               $_SESSION['user']['baskets'], $absBasketsArr
            );
        }
        /*echo '<pre>';
        print_r($_SESSION['user']['baskets']);
        echo '</pre>';
        exit;*/
    }

    /**
     * Return the url of the basket result page  given an basket identifier.
     *
     * @param   $basketIdPage  string  Basket results page identifier
     * @param   $mode_page   string "frame" or "no_frame"
     * @return string url of the basket results page or empty string in error
     * case
     */
    public function retrieve_path_page($basketIdPage, $mode)
    {
        // Gets the indice of the $basketIdPage in the
        // $_SESSION['basket_page'] to access all the informations on this page
        $path = '';
        $ind = -1;
        for ($i = 0; $i < count($_SESSION['basket_page']); $i ++) {
            if (trim($_SESSION['basket_page'][$i]['ID']) == trim(
                $basketIdPage
            )
            ) {
                $ind = $i;
                break;
            }
        }
        // If the page identifier is not found return an empty string
        if ($ind == -1) {
            return '';
        } else {// building the url
            // The page is in the apps
            if (strtoupper($_SESSION['basket_page'][$ind]['ORIGIN']) == 'APPS'
            ) {
                if (strtoupper($mode) == 'NO_FRAME') {
                    $path = $_SESSION['config']['businessappurl']
                    . 'index.php?page='
                    . $_SESSION['basket_page'][$ind]['NAME'];
                } else if (strtoupper($mode) == 'FRAME') {
                    $path = $_SESSION['config']['businessappurl']
                    . $_SESSION['basket_page'][$ind]['NAME'] . '.php';
                } else if (strtoupper($mode) == 'INCLUDE') {
                    $path = 'apps/' . $_SESSION['config']['app_id'] . '/'
                    . $_SESSION['basket_page'][$ind]['NAME'] . '.php';
                } else {
                    return '';
                }
            } else if (strtoupper(
                $_SESSION['basket_page'][$ind]['ORIGIN']
            ) == "MODULE"
            ) { // The page is in a module
                $core = new core_tools();
                // Error : The module name is empty or the module is not loaded
                if (empty($_SESSION['basket_page'][$ind]['MODULE'])
                    || ! $core->is_module_loaded(
                        $_SESSION['basket_page'][$ind]['MODULE']
                    )
                ) {
                    return '';
                } else {
                    if (strtoupper($mode) == 'NO_FRAME') {
                        $path = $_SESSION['config']['businessappurl']
                        . 'index.php?page='
                        . $_SESSION['basket_page'][$ind]['NAME']
                        . '&module='
                        . $_SESSION['basket_page'][$ind]['MODULE'];
                    } else if (strtoupper($mode) == 'FRAME') {
                        $path = $_SESSION['config']['businessappurl']
                        . 'index.php?display=true&module='
                        . $_SESSION['basket_page'][$ind]['MODULE']
                        . '&page='
                        . $_SESSION['basket_page'][$ind]['NAME'];

                    } else if (strtoupper($mode) == 'INCLUDE') {
                        $path = 'modules' . DIRECTORY_SEPARATOR
                        . $_SESSION['basket_page'][$ind]['MODULE']
                        . DIRECTORY_SEPARATOR
                        . $_SESSION['basket_page'][$ind]['NAME'] . '.php';
                    } else {
                        return '';
                    }
                }
            } else { // Error
                return '';
            }
        }
        return $path;
    }

    /**
     * Loads in session ($_SESSION['basket_page'] array) the informations on the
     *  baskets results page from the basket/xml/basketpage.xml
     *
     */
    protected function _loadBasketsPages()
    {
        $_SESSION['basket_page'] = array();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'basket' . DIRECTORY_SEPARATOR . 'xml'
            . DIRECTORY_SEPARATOR . 'basketpage.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
            . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
            . 'basket' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'basketpage.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'basket'
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'basketpage.xml';
        }
        $xmlfile = simplexml_load_file($path);
        include_once 'modules' . DIRECTORY_SEPARATOR . 'basket'
        . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
        . $_SESSION['config']['lang'] . '.php';
        $i = 0;
        foreach ($xmlfile->BASKETPAGE as $basketPage) {
            $desc = (string) $basketPage->LABEL;
            if (!empty($desc) && defined($desc) && constant($desc) <> NULL) {
                $desc = constant($desc);
            }
            $collections = array();
            $collectionsTag = $basketPage->COLLECTIONS;
            foreach ($collectionsTag->COLL_ID as $collection) {
                array_push($collections, (string) $collection);
            }
            $_SESSION['basket_page'][$i] = array(
                'ID'     => (string) $basketPage->ID,
                'LABEL'  => $desc,
                'NAME'   => (string) $basketPage->NAME,
                'ORIGIN' => (string) $basketPage->ORIGIN,
                'MODULE' => (string) $basketPage->MODULE,
                'COLLECTIONS' => $collections,
            );
            $i++;
        }
    }

    /**
     * Loads the baskets datas into session variables
     *
     */
    public function load_basket($primaryGroup, $userId)
    { 
        $arr = array();
        $db = new Database();
        $stmt = $db->query(
            "SELECT gb.basket_id FROM " . GROUPBASKET_TABLE . " gb, "
            . BASKET_TABLE . " b WHERE gb.group_id = ? and gb.basket_id = b.basket_id order by b.basket_order, b.basket_name ", array($primaryGroup));
        // $db->show();
        while ($res = $stmt->fetchObject()) {
            $tmp = $this->get_baskets_data(
                $res->basket_id, $userId, $primaryGroup
            );
            //$this->show_array($tmp);
            array_push($arr, $tmp);
        }
        return $arr;
    }

    /**
     * Loads the baskets secondary datas into session variables
     *
     */
    public function loadBasketSecondary($userId, $groupId, $basketId)
    {
        $isSecondary = true;
        $tmp = $this->get_baskets_data(
            $basketId, $userId, $groupId, $isSecondary
        );
        
        return $tmp;
    }

    public function load_basket_abs($userId)
    { 
	$db = new Database();
        $arr = array();
        $stmt = $db->query(
            "select system_id, basket_id, user_abs from " . USER_ABS_TABLE
            . " where new_user = ?",array($userId));
        //$db->show();
        while ($res = $stmt->fetchObject()) {
            $stmt2 = $db->query(
            "select status from users"
            . " where user_id = ?",array($res->user_abs));

            $res2 = $stmt2->fetchObject();

            if($res2->status <> 'DEL'){
               array_push(
                    $arr ,
                    $this->get_abs_baskets_data(
                        $res->basket_id, $userId, $res->system_id
                    )
                ); 
            }
        }
        return $arr;
    }


    /**
     * Get the actions for a group in a basket.
     *
     * @param   $basketId   string  Basket identifier
     * @param   $groupId string  Users group identifier
     * @return array actions
     */
    protected function _getActionsFromGroupbaket($basketId, $groupId, $userId = '')
    {
        $actions = array();
        $db = new Database();

        $stmt = $db->query(
            "select agb.id_action, agb.where_clause, agb.used_in_basketlist, "
            . "agb.used_in_action_page, a.label_action, a.id_status, "
            . "a.action_page, a.is_folder_action from " . ACTIONS_TABLE . " a, "
            . ACTIONS_GROUPBASKET_TABLE . " agb where a.id = agb.id_action and "
            . "agb.group_id = ? and agb.basket_id = ? and a.enabled = 'Y' and "
            . "agb.default_action_list ='N'",array($groupId,$basketId));
        require_once('core/class/ActionControler.php');
        $actionControler = new actionControler();
        $sec = new security();
        $secCtrl = new SecurityControler();

        while ($res = $stmt->fetchObject()) {
            if ($res->where_clause <> '') {
                $whereClause = $secCtrl->process_security_where_clause(
                    $res->where_clause, $userId
                );
                $whereClause = substr($whereClause,-strlen($whereClause)+6); 
            } else {
                $whereClause = $res->where_clause;
            }
            $categories = array();
            $categories = $actionControler->getAllCategoriesLinkedToAction($res->id_action);
            array_push(
                $actions,
                array(
                    'ID' => $res->id_action,
                    'LABEL' => $res->label_action,
                    'WHERE' => $whereClause,
                    'MASS_USE' => $res->used_in_basketlist,
                    'PAGE_USE' => $res->used_in_action_page,
                    'ID_STATUS' => $res->id_status,
                    'ACTION_PAGE' => $res->action_page,
                    'IS_FOLDER_ACTION' => $res->is_folder_action,
                    'CATEGORIES' => $categories
                )
            );
        }
        return $actions;
    }

    /**
     * Get the default action in a basket for a group
     *
     * @param  $basketId   string  Basket identifier
     * @param   $groupId  string  Users group identifier
     * @return string action identifier or empty string in error case
     */
    protected function _getDefaultAction($basketId, $groupId)
    {
        $db = new Database();
        $stmt = $db->query(
            "select agb.id_action from " . ACTIONS_TABLE . " a, "
            . ACTIONS_GROUPBASKET_TABLE . " agb where a.id = agb.id_action "
            . "and agb.group_id = ? and agb.basket_id = ? and a.enabled = 'Y' "
            . "and agb.default_action_list ='Y'",array($groupId,$basketId));

        if ($stmt->rowCount() < 1) {
            return '';
        } else {
            $res = $stmt->fetchObject();
            return $res->id_action;
        }
    }


    /**
     * Make a given basket the current basket
     * (using $_SESSION['current_basket'] array)
     *
     * @param   $basketId   string Basket identifier
     */
    public function load_current_basket($basketId)
    {
        $_SESSION['current_basket'] = array();
        $_SESSION['current_basket']['id'] = trim($basketId);
        $ind = -1;
        for ($i = 0; $i < count($_SESSION['user']['baskets']); $i ++) {
            if ($_SESSION['user']['baskets'][$i]['id'] == $_SESSION['current_basket']['id']) {
                $ind = $i;
                break;
            }
        }
        if ($ind > -1) {
            //selection du type de diffusion
            $db = new Database();
            $stmt = $db->query(
                "select difflist_type_id from groupbasket_difflist_types "
                . " where basket_id = ? "
                . "and group_id = ? "
                . "and action_id = ?",array($basketId,$_SESSION['user']['baskets'][$ind]['group_id'],$_SESSION['user']['baskets'][$ind]['default_action']));

            if ($stmt->rowCount() <= 0) {
                $_SESSION['current_basket']['difflist_type'] = 'entity_id';
            } else {
                $res = $stmt->fetchObject();
                $_SESSION['current_basket']['difflist_type'] = $res->difflist_type_id;
            }

            $_SESSION['current_basket']['table'] = $_SESSION['user']['baskets'][$ind]['table'];
            $_SESSION['current_basket']['view'] = $_SESSION['user']['baskets'][$ind]['view'];
            $_SESSION['current_basket']['coll_id'] = $_SESSION['user']['baskets'][$ind]['coll_id'];
            $_SESSION['current_basket']['page_frame'] = $_SESSION['user']['baskets'][$ind]['page_frame'];
            $_SESSION['current_basket']['page_no_frame'] = $_SESSION['user']['baskets'][$ind]['page_no_frame'];
            $_SESSION['current_basket']['page_include'] = $_SESSION['user']['baskets'][$ind]['page_include'];
            $_SESSION['current_basket']['default_action'] = $_SESSION['user']['baskets'][$ind]['default_action'];
            $_SESSION['current_basket']['label'] = $_SESSION['user']['baskets'][$ind]['name'];
            $_SESSION['current_basket']['clause'] = $_SESSION['user']['baskets'][$ind]['clause'];
            $_SESSION['current_basket']['actions'] = $_SESSION['user']['baskets'][$ind]['actions'];
            // $_SESSION['current_basket']['redirect_services'] = $_SESSION['user']['baskets'][$ind]['redirect_services'];
           // $_SESSION['current_basket']['redirect_users'] = $_SESSION['user']['baskets'][$ind]['redirect_users'];
            $_SESSION['current_basket']['basket_owner'] = $_SESSION['user']['baskets'][$ind]['basket_owner'];
            $_SESSION['current_basket']['abs_basket'] = $_SESSION['user']['baskets'][$ind]['abs_basket'];
            $_SESSION['current_basket']['is_folder_basket'] = $_SESSION['user']['baskets'][$ind]['is_folder_basket'];
            $_SESSION['current_basket']['lock_list'] = $_SESSION['user']['baskets'][$ind]['lock_list'];
            $_SESSION['current_basket']['lock_sublist'] = $_SESSION['user']['baskets'][$ind]['lock_suvlist'];
            $_SESSION['current_basket']['group_id'] = $_SESSION['user']['baskets'][$ind]['group_id'];
            $_SESSION['current_basket']['group_desc'] = $_SESSION['user']['baskets'][$ind]['group_desc'];
        }
    }

    /**
     * Loads status from users and create var when he's missing.
     *
     */
    protected function _loadActivityUser($userId)
    {
        if ( isset($userId) ) {
            $db = new Database();
            $stmt = $db->query(
                "SELECT status from " . USERS_TABLE . " where user_id=?",array($userId));
            $line = $stmt-> fetchObject();

            if ($line->status == 'ABS') {
                $_SESSION['abs_user_status'] = true;
            } else {
                $_SESSION['abs_user_status'] = false;
            }
        }
    }

    public function translates_actions_to_json($actions = array())
    {
        $jsonActions = '{';

        if (count($actions) > 0) {
            for ($i = 0; $i < count($actions); $i ++) {
                $jsonActions .= "'"  . $actions[$i]['ID'] . "' : { 'where' : '"
                . addslashes($actions[$i]['WHERE']) . "',";
                $jsonActions .= "'id_status' : '" . $actions[$i]['ID_STATUS']
                . "', 'confirm' : '" ;
                if (isset($actions[$i]['CONFIRM'])) {
                    $jsonActions .= $actions[$i]['CONFIRM'];
                } else {
                    $jsonActions .= 'false';
                }
                $jsonActions .= "', ";
                $jsonActions .= "'id_action_page' : '"
                . $actions[$i]['ACTION_PAGE'] . "'}, ";
            }
            $jsonActions = preg_replace('/, $/', '}', $jsonActions);
        }

        if ($jsonActions == '{') {
            $jsonActions = '{}';
        }
        return $jsonActions;
    }
    /**
     * Builds the basket results list (using class_list_show.php method)
     *
     * @param   $paramsList  array  Parameters array used to display the result
     *                              list
     * @param   $actions actions  Array to be displayed in the list
     * @param   $lineTxt  string String to be displayed at the bottom of the
     *                       list to describe the default action
     */
    public function basket_list_doc($paramsList, $actions, $lineTxt)
    {
        ////////////////////////////////////////////////////////////////////////
        //$this->show_array($paramsList);
        ////////////////////////////////////////////////////////////////////////
        $actionForm = '';
        $boolCheckForm = false;
        $method = '';
        $actionsList = array();
        // Browse the actions array to build the jason string that will be used
        // to display the actions in the list
        if (count($actions) > 0) {
            for ($i = 0; $i < count($actions); $i ++) {
                if ($actions[$i]['MASS_USE'] == 'Y') {
                    array_push(
                        $actionsList,
                        array(
                            'VALUE' => $actions[$i]['ID'],
                            'LABEL' => addslashes($actions[$i]['LABEL']),
                        )
                    );
                }
            }

        }

        $jsonActions = $this->translates_actions_to_json($actions);

        if (count($actionsList) > 0) {
            $actionForm = $_SESSION['config']['businessappurl']
            . 'index.php?display=true&page=manage_action'
            . '&module=core';
            $boolCheckForm = true;
            $method = 'get';
        }

        $doAction = false;
        if (! empty($_SESSION['current_basket']['default_action'])) {
            $doAction = true;
        }

        $list = new list_show();
        if (! isset($paramsList['link_in_line'])) {
            $paramsList['link_in_line'] = false;
        }
        if (! isset($paramsList['template'])) {
            $paramsList['template'] = false;
        }
        if (! isset($paramsList['template_list'])) {
            $paramsList['template_list'] = array();
        }
        if (! isset($paramsList['actual_template'])) {
            $paramsList['actual_template'] = '';
        }
        if (! isset($paramsList['bool_export'])) {
            $paramsList['bool_export'] = false;
        }
        if (! isset($paramsList['comp_link'])) {
            $paramsList['comp_link'] = '';
        }
        $str = '';
        // Displays the list using list_doc method from class_list_shows
        $str .= $list->list_doc(
            $paramsList['values'], count($paramsList['values']),
            $paramsList['title'], $paramsList['what'], $paramsList['page_name'],
            $paramsList['key'], $paramsList['detail_destination'],
            $paramsList['view_doc'], false, $method, $actionForm , '',
            $paramsList['bool_details'], $paramsList['bool_order'],
            $paramsList['bool_frame'], $paramsList['bool_export'], false, false,
            true, $boolCheckForm, '', $paramsList['module'], false, '', '',
            $paramsList['css'], $paramsList['comp_link'],
            $paramsList['link_in_line'], true, $actionsList,
            $paramsList['hidden_fields'], $jsonActions, $doAction,
            $_SESSION['current_basket']['default_action'],
            $paramsList['open_details_popup'], $paramsList['do_actions_arr'],
            $paramsList['template'], $paramsList['template_list'],
            $paramsList['actual_template'], true
        );

        // Displays the text line if needed
        if (count($paramsList['values']) > 0 && ($paramsList['link_in_line']
        || $doAction )
        ) {
            $str .= "<em>".$lineTxt."</em>";
        }
        if (! isset($paramsList['mode_string'])
        || $paramsList['mode_string'] == false
        ) {
            echo $str;
        } else {
            return $str;
        }
    }

    /**
     * Returns the actions for the current basket for a given mode.
     * The mode can be "MASS_USE" or "PAGE_USE".
     *
     * @param   $resId  string  Resource identifier
     *   (used in PAGE_USE mode to test the action where_clause)
     * @param   $collId  string Collection identifier
     *   (used in PAGE_USE mode to test the action where_clause)
     * @param   $mode  string  "PAGE_USE" or "MASS_USE"
     * @return array  Actions to be displayed
     */
    public function get_actions_from_current_basket($resId, $collId, $mode,$testWhere = true)
    {
        if ($_SESSION['category_id'] == '') {
            $_SESSION['category_id'] = $_SESSION['coll_categories'][$collId]['default_category'];
        }
        //var_dump($_SESSION['category_id']);
        $arr = array();
        // If parameters error return an empty array
        if (empty($resId) || empty($collId)
        || (strtoupper($mode) <> 'MASS_USE'
        && strtoupper($mode) <> 'PAGE_USE')
        ) {
            return $arr;
        } else {
            $sec = new security();
            $db = new Database();
            $table = $sec->retrieve_view_from_coll_id($collId);
            if (empty($table)) {
                $table = $sec->retrieve_table_from_coll_id($collId);
            }
            // If the view and the table of the collection is empty,
            // return an empty array
            if (empty($table)) {
                return $arr;
            }
            // If mode "PAGE_USE", add the action 'end_action' to validate
            // the current action
            if ($mode == 'PAGE_USE') {
                $db = new Database();
                $stmt = $db->query(
                    "SELECT label_action FROM actions WHERE id= ?",array($_SESSION['current_basket']['default_action']));
                $label_action = $stmt->fetchObject();
                array_push(
                    $arr,
                    array(
                        'VALUE' => 'end_action',
                        'LABEL' => $label_action->label_action.' (par défaut)',
                    )
                );
            }
            // Browsing the current basket actions to build the actions array
            for ($i = 0; $i < count($_SESSION['current_basket']['actions']);
            $i ++
            ) {
                $noFilterOnCat = true;
                if (count($_SESSION['current_basket']['actions'][$i]['CATEGORIES']) > 0) {
                    $noFilterOnCat = false;
                }
                $categoryIdForActions = '';
                for ($cptCat=0;$cptCat<count($_SESSION['current_basket']['actions'][$i]['CATEGORIES']);$cptCat++) {
                    if ($_SESSION['current_basket']['actions'][$i]['CATEGORIES'][$cptCat] == $_SESSION['category_id']) {
                        $categoryIdForActions = $_SESSION['category_id'];
                    }
                }
                if (
                    $noFilterOnCat || $categoryIdForActions <> ''
                ) {
                    // If in mode "PAGE_USE", testing the action where clause
                    // on the res_id before adding the action
                    if (strtoupper($mode) == 'PAGE_USE'
                    && $_SESSION['current_basket']['actions'][$i]['PAGE_USE'] == 'Y'
                    && $testWhere
                    ) {
                        $where = ' where res_id = ' . $resId;
                        if (! empty(
                        $_SESSION['current_basket']['actions'][$i]['WHERE']
                        )
                        ) {
                            $where = $where . ' and '
                            . $_SESSION['current_basket']['actions'][$i]
                            ['WHERE'];
                        }
                        $stmt = $db->query('select res_id from ' . $table . ' ' . $where);
                        if ($stmt->rowCount() > 0) {
                            array_push(
                                $arr,
                                array(
                                    'VALUE' => $_SESSION['current_basket']['actions']
                                    [$i]['ID'],
                                    'LABEL' => $_SESSION['current_basket']
                                    ['actions'][$i]['LABEL'],
                                )
                            );
                        }
                    } else if (strtoupper($mode) == 'PAGE_USE'
                    && $_SESSION['current_basket']['actions'][$i]['PAGE_USE'] == 'Y'
                    && ! $testWhere
                    ) {
                        array_push(
                            $arr,
                            array(
                                'VALUE' => $_SESSION['current_basket']['actions']
                                [$i]['ID'],
                                'LABEL' => $_SESSION['current_basket']['actions']
                                [$i]['LABEL'],
                            )
                        );
                    } else if (strtoupper($mode) == 'MASS_USE'
                    && $_SESSION['current_basket']['actions'][$i]['MASS_USE'] == 'Y'
                    ) { // If "MASS_USE" adding the actions in the array
                        array_push(
                            $arr,
                            array(
                                'VALUE' => $_SESSION['current_basket']['actions']
                                [$i]['ID'],
                                'LABEL' => $_SESSION['current_basket']['actions']
                                [$i]['LABEL'],
                            )
                        );
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * Returns in an array the baskets of a given user
     *  (Including the redirected baskets)
     *
     * @param  $userId string Owner of the baskets (identifier)
     */
    public function get_baskets($userId)
    {   
        $db = new Database();
        $stmt = $db->query(
            "select b.basket_id, b.basket_name from " . BASKET_TABLE . " b, "
            . USERGROUP_CONTENT_TABLE . " uc, " . GROUPBASKET_TABLE . " gb, "
            . USERGROUPS_TABLE . " u where uc.user_id = ? and uc.primary_group = 'Y' and gb.group_id = uc.group_id "
            . "and b.basket_id = gb.basket_id and u.group_id = gb.group_id "
            . "and u.enabled = 'Y' ",array($userId));

        $arr = array();
        while ($res = $stmt->fetchObject()) {
            array_push(
                $arr,
                array(
                    'id'           => $res->basket_id,
                    'name'         => $res->basket_name,
                    'is_virtual'   => 'N',
                    'basket_owner' => '',
                    'abs_basket'   => false,
                )
            );
        }
        $absBaskets = $this->get_abs_baskets($userId);
        if (isset($absBaskets)) {
            return array_merge($arr, $absBaskets);
        }
        return $arr;
    }

    /**
     * Returns in an array the redirected baskets of a given user
     *
     * @param  $userId string Owner of the baskets (identifier)
     */
    public function get_abs_baskets($userId)
    {//var_dump('get_abs_baskets');exit;
        $db = new Database();
        $stmt = $db->query(
            "select basket_id, is_virtual, basket_owner from "
            . USER_ABS_TABLE . " mu where user_abs = ?",array($userId));
        $arr = array();
        while ($res = $stmt->fetchObject()) {
            $basketId = $res->basket_id;
            $basketOwner = $res->basket_owner;
            $isVirtual = $res->is_virtual;
            $stmt2 = $db->query(
                "select basket_name from " . BASKET_TABLE
                . " where basket_id = ?",array($basketId));
            $res2 = $stmt2->fetchObject();
            $basketName = $res2->basket_name;
            if ($isVirtual == 'Y' && $basketOwner <> '') {
                $stmt3 = $db->query(
                    "select firstname, lastname from " . USERS_TABLE
                    ." where user_id = ?", array($basketOwner));
                $res2 = $stmt3->fetchObject();
                $userName = $res2->firstname . ' ' . $res2->lastname;
                $basketName .= "(".$userName.")";
            } else {
                $basketOwner = $userId;
            }
            array_push(
                $arr,
                array(
                    'id' => $basketId,
                    'name' => $basketName,
                    'is_virtual' => $isVirtual,
                    'basket_owner' => $basketOwner,
                    'abs_basket' => true,
                )
            );
        }
        return $arr;
    }

    /**
     * Returns in an array all the data of a basket for a user
     *(checks if the basket is a redirected one and then if already a virtual one)
     *
     * @param  $basketId string Basket identifier
     * @param  $userId string User identifier
     */
    public function get_baskets_data($basketId, $userId, $groupId, $isSecondary = false)
    {
        $tab = array();
        $db = new Database();

        $sec = new security();
        $secCtrl = new SecurityControler();
        $stmt = $db->query(
            "select basket_id, coll_id, basket_name, basket_desc, "
            . "basket_clause, is_visible, is_generic, is_folder_basket from "
            . BASKET_TABLE . " where basket_id = ? and enabled = 'Y'",array($basketId));
        $res = $stmt->fetchObject();
        $tab['id'] = $res->basket_id;
        $tab['coll_id'] = $res->coll_id;
        $core = new core_tools();
        if ($core->is_module_loaded('folder') === true && $res->is_folder_basket == 'Y') {
            $tab['table'] = 'folders';
            $tab['view'] = 'view_folders';
        } else {
            $tab['table'] =  $sec->retrieve_table_from_coll($tab['coll_id']);
            $tab['view'] = $sec->retrieve_view_from_coll_id($tab['coll_id']);
        }
        $tab['is_generic'] = $res->is_generic;
        $tab['desc'] = $this->show_string($res->basket_desc);
        $tab['name'] = $this->show_string($res->basket_name);
        $tab['clause'] = $res->basket_clause;
        $tab['is_visible'] = $res->is_visible;
        $tab['is_folder_basket'] = $res->is_folder_basket;
        $isVirtual = 'N';
        $basketOwner = '';
        $absBasket = false;

        if (!$isSecondary) {
            $stmt = $db->query(
                "select group_id from "
                . $_SESSION['tablename']['usergroup_content']
                . " where primary_group = 'Y' and user_id = ?",array($userId));
            $res = $stmt->fetchObject();
            $groupId = $res->group_id;
        }
        $stmt = $db->query(
            "select sequence, can_redirect, can_delete, can_insert, "
            . "result_page, list_lock_clause, sublist_lock_clause, "
            ."redirect_basketlist, redirect_grouplist from "
            . GROUPBASKET_TABLE . " where group_id = ? and basket_id = ?",array($groupId,$basketId));
        $res = $stmt->fetchObject();

        $basketIdPage = $res->result_page;
        $tab['id_page'] = $basketIdPage;
        // Retrieves the basket url (frame and no_frame modes)
        $basketPathPageNoFrame = $this->retrieve_path_page(
            $basketIdPage, 'no_frame'
        );
        $basketPathPageFrame = $this->retrieve_path_page(
            $basketIdPage, 'frame'
        );
        $basketPathPageInclude = $this->retrieve_path_page(
            $basketIdPage, 'include'
        );
        $tab['page_no_frame'] = $basketPathPageNoFrame;
        $tab['page_frame'] = $basketPathPageFrame;
        $tab['page_include'] = $basketPathPageInclude;
        // Gets actions of the basket
        // #TODO : make one method to get all actions : merge _getDefaultAction and _getActionsFromGroupbaket
        $tab['default_action'] = $this->_getDefaultAction(
            $basketId, $groupId
        );
        $tab['actions'] = $this->_getActionsFromGroupbaket(
            $basketId, $groupId, $userId
        );

        $tab['abs_basket'] = $absBasket;
        $tab['is_virtual'] = $isVirtual;
        $tab['basket_owner'] = $basketOwner;
        $tab['clause'] = $secCtrl->process_security_where_clause(
            $tab['clause'], $userId
        );
        $tab['clause'] = str_replace('where', '', $tab['clause']);
        
        $tab['lock_list'] = $secCtrl->process_security_where_clause(
            $res->list_lock_clause, $userId
        );
		$tab['lock_list'] = str_replace('where', '', $tab['lock_list']);
		
        $tab['lock_sublist'] = $secCtrl->process_security_where_clause(
            $res->sublist_lock_clause, $userId
        );
        
		$tab['lock_sublist'] = str_replace('where', '', $tab['lock_sublist']);

        $db = new Database();
        $stmt = $db->query(
            "select group_desc from "
            . USERGROUPS_TABLE
            . " where group_id = ?",array($groupId));
        $res = $stmt->fetchObject();
        $groupDesc = $res->group_desc;

        $tab['group_id'] = $groupId;

        $tab['group_desc'] = $groupDesc;

        $tab['is_secondary'] = $isSecondary;
		
        return $tab;
    }

    /**
     * Returns in an array all the data of a basket for a user
     * (checks if the basket is a redirected one and then if already a virtual one)
     *
     * @param  $basketId string Basket identifier
     * @param  $userId string User identifier
     */
    public function get_abs_baskets_data($basketId, $userId, $systemId)
    {
        $tab = array();
        $db = new Database();
        $sec = new security();
        $secCtrl = new SecurityControler();
        $stmt = $db->query(
            "select basket_id, coll_id, basket_name, basket_desc, basket_clause, is_visible"
            . ", is_folder_basket from " . BASKET_TABLE . " where basket_id = ? and enabled = 'Y'",array($basketId));

        $res = $stmt->fetchObject();
        $tab['id'] = $res->basket_id;
        $tab['coll_id'] = $res->coll_id;
        $tab['table'] = $sec->retrieve_table_from_coll($tab['coll_id']);
        if($res->is_folder_basket == 'Y'){
            $tab['view'] = "view_folders";
        }else{
            $tab['view'] = $sec->retrieve_view_from_coll_id($tab['coll_id']);
        }
        $tab['is_generic'] = 'NO';

        $tab['desc'] = $res->basket_desc;
        $tab['name'] = $res->basket_name;
        $tab['clause'] = $res->basket_clause;
        $tab['is_visible'] = $res->is_visible;
        $tab['is_folder_basket'] = $res->is_folder_basket;
        $stmt = $db->query(
            "select user_abs, is_virtual, basket_owner from " . USER_ABS_TABLE
            . " where basket_id = ? and new_user = ? and system_id = ?", array($basketId,$userId,$systemId));

        $absBasket = true;
        $res = $stmt->fetchObject();
        $isVirtual = $res->is_virtual;
        $basketOwner = $res->basket_owner;
        $userAbs = $res->user_abs;

        if (empty($basketOwner)) {
            $basketOwner = $userAbs;
        }
        if ($isVirtual == 'N') {
            $tmpUser = $userAbs;
            $stmt = $db->query(
                "select firstname, lastname from " . USERS_TABLE
                . " where user_id = ? ",array($userAbs));
            $res = $stmt->fetchObject();
            $nameUserAbs = $res->firstname . ' ' . $res->lastname;
            $tab['name'] .= " (" . $nameUserAbs . ")";
            $tab['desc'] .= " (" . $nameUserAbs . ")";
            $tab['id'] .= "_" . $userAbs;
        } else {
            $tmpUser = $basketOwner;  /// TO DO : test if basket_owner empty
            $stmt = $db->query(
                "select firstname, lastname from " . USERS_TABLE
                ." where user_id = ?",array($basketOwner));
            $res = $stmt->fetchObject();
            $nameBasketOwner = $res->firstname . ' ' . $res->lastname;
            $tab['name'] .= " (" . $nameBasketOwner . ")";
            $tab['desc'] .= " (" . $nameBasketOwner . ")";
            $tab['id'] .= "_" . $basketOwner;
        }

        /// TO DO : Test if tmp_user is empty
        if ((isset($_SESSION['user']['UserId'])
            && $tmpUser <> $_SESSION['user']['UserId'])
            || (!isset($_SESSION['user']['UserId']))
        ) {
            $stmt = $db->query(
                "select group_id from " . USERGROUP_CONTENT_TABLE
                . " where primary_group = 'Y' and user_id = ?",array($tmpUser));

            $res = $stmt->fetchObject();
            $primaryGroup = $res->group_id;
        } else {
            $primaryGroup = $_SESSION['user']['primarygroup'];
        }
        $stmt = $db->query(
            "select  sequence, can_redirect, can_delete, can_insert, "
            . "result_page, redirect_basketlist, list_lock_clause, "
            ."sublist_lock_clause, redirect_grouplist from "
            . GROUPBASKET_TABLE . " where group_id = ? and basket_id = ? ",array($primaryGroup,$basketId));

        $res = $stmt->fetchObject();

        $basketIdPage = $res->result_page;
        $tab['id_page'] = $basketIdPage;
        // Retrieves the basket url (frame and no_frame modes)
        $basketPathPageNoFrame = $this->retrieve_path_page(
            $basketIdPage, 'no_frame'
        );
        $basketPathPageFrame = $this->retrieve_path_page(
            $basketIdPage, 'frame'
        );
        $basketPathPageInclude = $this->retrieve_path_page(
            $basketIdPage, 'include'
        );
        $tab['page_no_frame'] = $basketPathPageNoFrame;
        $tab['page_frame'] = $basketPathPageFrame;
        $tab['page_include'] = $basketPathPageInclude;
        // Gets actions of the basket
        $tab['default_action'] = $this->_getDefaultAction(
            $basketId, $primaryGroup
        );
        $tab['actions'] = $this->_getActionsFromGroupbaket(
            $basketId, $primaryGroup, $userId
        );

        $tab['is_virtual'] = $isVirtual;
        $tab['basket_owner'] = $basketOwner;
        $tab['redirect_services'] = trim(
            stripslashes($res->redirect_basketlist)
        );
        $tab['redirect_users'] = trim(stripslashes($res->redirect_grouplist));
        $tab['abs_basket'] = $absBasket;

        $tab['clause'] = $secCtrl->process_security_where_clause(
            $tab['clause'], $basketOwner
        );
        $tab['clause'] = str_replace('where', '', $tab['clause']);
        
        $tab['lock_list'] = $secCtrl->process_security_where_clause(
            $res->list_lock_clause, $userId
        );
        $tab['lock_sublist'] = $secCtrl->process_security_where_clause(
            $res->sublist_lock_clause, $userId
        );
        
        return $tab;
    }

    /**
     * Returns the number of baskets of a given user
     * (Including the redirected baskets)
     *
     * @param  $userId string Owner of the baskets (identifier)
     */
    public function get_numbers_of_baskets($userId)
    {
        if ($userId == $_SESSION['user']['UserId']) {
            return count($_SESSION['user']['baskets']);
        } else {
            $db = new Database();
            $stmt = $db->query(
                "SELECT gb.basket_id  FROM " . USERGROUP_CONTENT_TABLE . " uc, "
                . GROUPBASKET_TABLE . " gb WHERE uc.user_id = ? AND uc.primary_group = 'Y' AND uc.group_id = gb.group_id",array($userId));
            $nb = $stmt->rowCount();
            $stmt = $db->query(
                "select basket_id from " . USER_ABS_TABLE
                . " mu where new_user = ?",array($userId));

            return $nb + $stmt->rowCount();
        }
    }

    /**
     * Returns in a string the form to redirect baskets to users during leaving
     *
     * @param  $result array Array of the baskets to redirect
     * @param  $nbTotal integer Number of baskets to redirect
     * @param  $userId string Owner of the baskets (identifier)
     * @param  $used_css string CSS to use in displaying
     */
    public function redirect_my_baskets_list($result, $nbTotal, $userId,
    $used_css='listing spec')
    {
        $nbShow = $_SESSION['config']['nblinetoshow'];
        if ($nbTotal > 0) {
            ob_start();
            ?><h2><?php
            echo _REDIRECT_MY_BASKETS;
            ?></h2><div align="center"><form name="redirect_my_baskets_to" id="redirect_my_baskets_to" method="post" action="<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?display=true&amp;module=basket&amp;page=manage_redirect_my_basket"><input type="hidden" name="display" id="display" value="true" /><input type="hidden" name="page" id="page" value="manage_redirect_my_basket" /><input type="hidden" name="module" id="module" value="basket" /><input type="hidden" name="baskets_owner" id="baskets_owner" value="<?php functions::xecho($userId);?>" /><table border="0" cellspacing="0" class="<?php functions::xecho($used_css);?>"><thead><tr><th><?php echo _ID;?></th><th><?php echo _NAME;?></th><th><?php echo _REDIRECT_TO;?></th></tr></thead><tbody><?php
            $color = "";
            for ($theline = 0; $theline < $nbTotal ; $theline ++) {
                if ($color == ' class="col"') {
                    $color = '';
                } else {
                    $color = ' class="col"';
                }
                ?><tr <?php echo $color;?>><td> <?php
                functions::xecho($result[$theline]['id']);
                ?></td><td><?php
                functions::xecho($result[$theline]['name']);
                ?></td><td><input type="hidden" name="basket_<?php
                echo $theline;
                ?>" id="basket_<?php functions::xecho($theline);?>" value="<?php
                functions::xecho($result[$theline]['id']);
                ?>" /><input type="hidden" name="virtual_<?php
                echo $theline;
                ?>" id="virtual_<?php functions::xecho($theline);?>" value="<?php
                if ($result[$theline]['abs_basket'] == true) {
                    echo 'Y';
                } else {
                    echo 'N';
                }
                ?>"/><input type="hidden" name="originalowner_<?php
                echo $theline;
                ?>" id="originalowner_<?php functions::xecho($theline);?>" value="<?php
                functions::xecho($result[$theline]['basket_owner']);
                 ?>" /><input type="text" id="user_<?php
                 echo $theline;
                 ?>" name="user_<?php
                 echo $theline;
                 ?>" class="users_to redirect" /><span id="indicator_<?php
                 echo $theline;
                 ?>" style="display: none"><i class="fa fa-spinner fa-2x"></i></span><div id="options_<?php
                 echo $theline;?>" class="autocomplete"></div></td></tr><?php
            }
            ?></tbody></table><p class="buttons"><input type="button" onclick="test_form();" name="valid" value="<?php
            echo _VALIDATE;
            ?>" class="button"/> <input type="button" name="cancel" value="<?php
            echo _CANCEL;
            ?>" onclick="destroyModal('modal_redirect');" class="button"/></p></form></div><?php

             $content = ob_get_clean();
        } else {
            ob_start();
            ?><h2><?php
            echo _ABS_MODE;
            ?></h2><div align="center"><form name="abs_mode" id="abs_mode" method="get" action="<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?display=true&amp;module=basket&amp;page=manage_abs_mode"><input type="hidden" name="display" value="true"/><input type="hidden" name="module" value="basket"/><input type="hidden" name="page" value="manage_abs_mode"/><input type="hidden" name="user_id" value="<?php functions::xecho($userId) ;?>"/><p><?php echo _REALLY_ABS_MODE;?></p><input type="submit" name="submit" value="<?php echo _VALIDATE;?>" class="button" /> <input type="button" name="cancel" value="<?php echo _CANCEL;?>" onclick="destroyModal('modal_redirect');" class="button" /></form></div><?php
            $content = ob_get_clean();
        }
         return $content;
    }

    /**
     * Cancel leaving for a user
     *
     * @param  $userId string user identifier
     *
     */
    public function cancel_abs($userId)
    {
        $db = new Database();
        $stmt = $db->query(
            "delete from " . USER_ABS_TABLE . " where is_virtual = 'Y' "
            . "and basket_owner = ?",array($userId));
        //Then we search all the virtual baskets assigned to the user
        $stmt = $db->query(
            "select basket_owner, basket_id from " . USER_ABS_TABLE
            . " where is_virtual='Y' and user_abs = ?",array($userId));
        // and delete this baskets if they were reassigned to someone else
        $i = 0;
        while ($res = $stmt->fetchObject()) {
            $stmt2 = $db->query(
                "delete from " . USER_ABS_TABLE . " where is_virtual ='Y' "
                . " and basket_id = ? and basket_owner = ? ",array($res->basket_id,$res->basket_owner));
            $i ++;
        }
        // then we delete all baskets where the user was the missing user
        $stmt = $db->query(
            "DELETE  from " . USER_ABS_TABLE . " WHERE user_abs=?",array($userId)
        );
        $stmt = $db->query(
            "update " . USERS_TABLE . " set status = 'OK' where user_id = ?",array($userId));
    }

    public function check_reserved_time($resId, $collId)
    {
        $sec = new security();
        $table = $sec->retrieve_table_from_coll($collId);
        $db = new Database();
        if (! empty($table) && ! empty($resId)) {
            $stmt = $db->query(
                "select video_time, video_user, destination from " . $table
                . " where res_id = ?",array($resId));
            $res = $stmt->fetchObject();
            $timestamp = $res->video_time;
            $videoUser = $res-> video_user;
            $dest = $res->destination;

            if (trim($videoUser) <> ''
            && ($timestamp - mktime(
                date("H") , date("i")  , date("s") , date("m") , date("d"),
                date("Y")
            ) < 0
            )
            ) {
                $db->query(
                    "update " . $table . " set video_user = '' where res_id = ?",array($resId));
                return false;
            } else { // Reserved time not yet expired
                if ($videoUser == $_SESSION['user']['UserId']
                || empty($videoUser)
                ) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function reserve_doc( $userId, $resId, $collId, $delay = 60)
    {
        if (empty($userId) || empty($resId) || empty($collId)) {
            return false;
        }
        $sec = new security();
        $table = $sec->retrieve_table_from_coll($collId);
        if (empty($table)) {
            return false;
        }
        $db = new Database();
        $stmt = $db->query(
            "select video_user, video_time from " . $table . " where res_id = ?", array($resId));

        if ($stmt->rowCount() == 0) {
            return false;
        }
        $res = $stmt->fetchObject();
        $user = $res->video_user;

        if ($delay > 1) {
            $delayStr = "+" . $delay . " minutes ";
        } else if ($delay == 1) {
            $delayStr = "+1 minute ";
        } else {
            return false;
        }

        if ($user <> $userId && ! empty($user)) {
            return false;
        } else {
            $stmt = $db->query(
                "update " . $table . " set video_time = ?, video_user = ? where res_id = ?", array(strtotime($delayStr),$userId,$resId));
            return true;
        }
    }
    
    public function is_redirect_to_action_basket($basketId, $groupId) 
    {
        $db = new Database();
        $stmt = $db->query(
                "select result_page from " . GROUPBASKET_TABLE 
                . " where group_id = ? and basket_id = ?",array($groupId,$basketId));
        $res = $stmt->fetchObject();

        $basketIdPage = $res->result_page;
        if ($basketIdPage == 'redirect_to_action') {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Return true if the user has a basket in the required collection
     *
     * @param  $collId string the collection
     * @return boolean
     */
    public function isUserHasBasketInCollection($collId)
    {
        $cpt = count($_SESSION['user']['baskets']);
        for ($i=0;$i<$cpt;$i++) {
            if ($_SESSION['user']['baskets'][$i]['coll_id'] == $collId) {
                return true;
            }
        }
        return false;
    }
    
    /************** WF MANAGEMENT **************/
    
    /**
     * Return true if it is the turn of the user in the WF
     *
     * @param $userId string the user ID
     * @param $resId long the res ID
     * @param $collId string the collection
     * @param $role string role in the WF
     * @return boolean
     */
    public function isItMyTurnInTheWF($userId, $resId, $collId, $role='')
    {
        if ($role <> '') {
            $itemMode = " and item_mode = '" . $role . "'";
        }
        $db = new Database();
        $stmt = $db->query(
            "select res_id from " . ENT_LISTINSTANCE 
            . " where coll_id = ? and res_id = ? and item_id = "
            . " and item_mode <> 'dest'"
            . " and item_mode <> 'cc' ?"
            . " and visible = 'Y'",array($collId,$resId,$userId,$itemMode)
        );
        $line = $stmt->fetchObject();
        if ($line->res_id <> '') {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Return array of roles of the user in the WF
     *
     * @param $userId string the user ID
     * @param $resId long the res ID
     * @param $collId string the collection
     * @return array of roles
     */
    public function whatAreMyRoleInTheWF($userId, $resId, $collId)
    {
        $roles = array();
        $db = new Database();
        $stmt = $db->query(
            "select item_mode from " . ENT_LISTINSTANCE 
            . " where coll_id = ?"
            . " and res_id = ? and item_id = ?"
            . " and visible = 'Y'",array($collId,$resId,$userId)
        );
        while ($line = $stmt->fetchObject()) {
            array_push($roles, $line->item_mode);
        }
        return $roles;
    }
    
     /**
     * Return the sequence of the user in the WF for a role
     *
     * @param $userId string the user ID
     * @param $resId long the res ID
     * @param $collId string the collection
     * @param $role string role in the WF
     * @return integer sequence
     */
    public function whatIsMySequenceForMyRole($userId, $resId, $collId, $role)
    {
        $db = new Database();
        $stmt = $db->query(
            "select sequence from " . ENT_LISTINSTANCE 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_id = ?"
            . " and item_mode = ?",array($collId,$resId,$userId,$role));
        $line = $stmt->fetchObject();
        if ($line->sequence <> '') {
            return $line->sequence;
        } else {
            return false;
        }
    }
    
    /**
     * Return true if the there is someone before me in the WF
     *
     * @param $resId long the res ID
     * @param $collId string the collection
     * @param $role string role in the WF
     * @param $sequence integer sequence of the actual user in the WF
     * @return boolean
     */
    public function isThereSomeoneBeforeMeInTheWF($resId, $collId, $role, $sequence)
    {
        $db = new Database();
        $stmt = $db->query(
            "select res_id from " . ENT_LISTINSTANCE 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and sequence < ?"
            . " and (visible = 'N' or visible = '' or visible is null)",array($collId,$resId,$role,$sequence));
        $line = $stmt->fetchObject();
        if ($line->res_id <> '') {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Return true if the there is someone after me in the WF
     *
     * @param $resId long the res ID
     * @param $collId string the collection
     * @param $role string role in the WF
     * @param $sequence integer sequence of the actual user in the WF
     * @return boolean
     */
    public function isThereSomeoneAfterMeInTheWF($resId, $collId, $role, $sequence)
    {
        $db = new Database();
        $stmt = $db->query(
            "select res_id from " . ENT_LISTINSTANCE 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and sequence > ?"
            . " and (visible = 'N' or visible = '' or visible is null)",array($collId,$resId,$role,$sequence));
        $line = $stmt->fetchObject();
        if ($line->res_id <> '') {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Return array of the agent who is the next in the WF for the role
     *
     * @param $resId long the res ID
     * @param $collId string the collection
     * @param $role string role in the WF
     * @param $sequence integer sequence of the actual user in the WF
     * @return string the agent
     */
    public function whoseTheNextInTheWF($resId, $collId, $role, $sequence)
    {
        $db = new Database();
        $stmt = $db->query(
            "select item_id, sequence from " . ENT_LISTINSTANCE 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and sequence > ?"
            . " and (visible = 'N' or visible = '' or visible is null) order by sequence",array($collId,$resId,$role,$sequence));
        $line = $stmt->fetchObject();
        if ($line->item_id <> '') {
            return $line->item_id;
        } else {
            return false;
        }
    }
    
    /**
     * Return array of the agent who is the previous in the WF for the role
     *
     * @param $resId long the res ID
     * @param $collId string the collection
     * @param $role string role in the WF
     * @param $sequence integer sequence of the actual user in the WF
     * @return string the agent
     */
    public function whoseThePreviousInTheWF($resId, $collId, $role, $sequence)
    {
        $db = new Database();
        $stmt = $db->query(
            "select item_id, sequence from " . ENT_LISTINSTANCE
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and sequence < ?"
            . " and (visible = 'N' or visible = '' or visible is null) order by sequence desc",array($collId,$resId,$role,$sequence));
        $line = $stmt->fetchObject();
        if ($line->item_id <> '') {
            return $line->item_id;
        } else {
            return false;
        }
    }
    
    /**
     * move agent in the WF
     *
     * @param $way string way to move in the WF : forward, back
     * @param $collId string the collection
     * @param $resId long the res ID
     * @param $role string role in the WF
     * @param $userId user ID to move
     * @return nothing
     */
    public function moveInTheWF($way, $collId, $resId, $role, $userId)
    {
        if ($way == 'forward') {
            $way = '>';
            $oppositeWay = '<';
        } elseif ($way == 'back') {
            $way = '<';
            $oppositeWay = '>';
            $order = ' desc';
        }
        $db = new Database();
        $stmt = $db->query(
            "select item_id from " . ENT_LISTINSTANCE 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and (visible = 'N' or visible = '' or visible is null) "
            . " and sequence ? ("
                . " select sequence from " . ENT_LISTINSTANCE 
                . " where coll_id = ?"
                . " and res_id = ?"
                . " and item_mode = ?"
                . " and item_id = ?)"
            . " order by sequence ?", array($collId,$resId,$role,$way,$collId,$resId,$role,$userId,$order));
        //$db->show();exit;
        $line = $stmt->fetchObject();
        if ($line->item_id <> '') {
            $stmt2 = $db->query(
                "update " . ENT_LISTINSTANCE . " set visible = 'Y'" 
                . " where coll_id = ?"
                . " and res_id = ?"
                . " and item_mode = ?"
                . " and item_id = ?",array($collId,$resId,$role,$line->item_id));
        }
        $stmt = $db->query(
            "update " . ENT_LISTINSTANCE . " set visible = 'N'" 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and item_id = ?",array($collId,$resId,$role,$userId));
        //update to visible N everyone before or after me
        $stmt = $db->query(
            "update " . ENT_LISTINSTANCE . " set visible = 'N'" 
            . " where coll_id = ?"
            . " and res_id = ?"
            . " and item_mode = ?"
            . " and sequence ? ("
                . " select sequence from " . ENT_LISTINSTANCE 
                . " where coll_id = ?"
                . " and res_id = ?"
                . " and item_mode = ?"
                . " and item_id = ?)",array($collId,$resId,$role,$oppositeWay,$collId,$resId,$role,$userId));
        //$db->show();exit;
    }

    /**
     * Returns in an array the baskets of a given user
     *  (Including the redirected baskets)
     *
     * @param  $userId string Owner of the baskets (identifier)
     */
    public function getBasketsOfSecondaryProfiles($userId)
    {
        $db = new Database();
        $query = "select b.basket_id, b.basket_name, u.group_desc, u.group_id from " . BASKET_TABLE . " b, "
                . USERGROUP_CONTENT_TABLE . " uc, " . GROUPBASKET_TABLE . " gb, "
                . USERGROUPS_TABLE . " u where uc.user_id = ? and uc.primary_group <> 'Y' and gb.group_id = uc.group_id "
                . "and b.basket_id = gb.basket_id and u.group_id = gb.group_id "
                . "and u.enabled = 'Y' "
                . "and b.basket_id not in ("
                . "select b.basket_id from " . BASKET_TABLE . " b, "
                . USERGROUP_CONTENT_TABLE . " uc, " . GROUPBASKET_TABLE . " gb, "
                . USERGROUPS_TABLE . " u where uc.user_id = ? and uc.primary_group = 'Y' and gb.group_id = uc.group_id "
                . "and b.basket_id = gb.basket_id and u.group_id = gb.group_id "
                . "and u.enabled = 'Y')";
        $stmt = $db->query($query,array($userId,$userId));
        $arr = array();
        while ($res = $stmt->fetchObject()) {
            $checkedBasket = false;
            $query = "select count(system_id) as result from user_baskets_secondary where user_id = ? and group_id = ? and basket_id = ?";
            $stmt2 = $db->query($query,array($userId,$res->group_id,$res->basket_id));
            $resQuery = $stmt2->fetchObject();
            if ($resQuery->result == 1) {
                $checkedBasket = true;
            }
            array_push(
                $arr,
                array(
                    'id'            => $res->basket_id,
                    'name'          => $res->basket_name,
                    'group_id'      => $res->group_id,
                    'group_desc'    => $res->group_desc,
                    'checked_basket'=> $checkedBasket,
                )
            );
        }
        return $arr;
    }

    /**
     * Returns in a string the form to choose secondary baskets to users
     *
     * @param  $result array Array of the baskets to choose
     * @param  $nbTotal integer Number of baskets to choose
     * @param  $userId string Owner of the baskets (identifier)
     * @param  $used_css string CSS to use in displaying
     */
    public function chooseSecondaryBasketsList($result, $nbTotal, $userId,
    $used_css='listing spec')
    {
        $nbShow = $_SESSION['config']['nblinetoshow'];
        if ($nbTotal > 0) {
            ob_start();
            ?><h2><?php
            echo _MANAGE_SECONDARY_USER_BASKETS;
            ?></h2><div align="center"><form name="secondary_baskets" id="secondary_baskets" method="post" action="<?php
            echo $_SESSION['config']['businessappurl'];
            ?>index.php?display=true&amp;module=basket&amp;page=manage_user_baskets_secondary"><input type="hidden" name="display" id="display" value="true" /><input type="hidden" name="page" id="page" value="manage_redirect_my_basket" /><input type="hidden" name="module" id="module" value="basket" /><input type="hidden" name="baskets_owner" id="baskets_owner" value="<?php functions::xecho($userId);?>" /><table border="0" cellspacing="0" class="<?php functions::xecho($used_css);?>"><thead><tr><th><?php echo _ID;?></th><th><?php echo _NAME;?></th><th><?php echo _GROUP;?></th><th><?php echo _CHOOSE;?></th></tr></thead><tbody><?php
            $color = "";
            for ($theline = 0; $theline < $nbTotal ; $theline ++) {
                if ($color == ' class="col"') {
                    $color = '';
                } else {
                    $color = ' class="col"';
                }
                ?><tr <?php echo $color;?>><td> <?php
                functions::xecho($result[$theline]['id']);
                ?></td><td><?php
                functions::xecho($result[$theline]['name']);
                ?></td><td><?php
                functions::xecho($result[$theline]['group_desc']);
                ?></td><td><input type="checkbox" value="<?php
                functions::xecho(($result[$theline]['id']) . "##" 
                    . $result[$theline]['group_id']);
                ?>" name="basketId[]" <?php
                if ($result[$theline]['checked_basket']) { 
                    echo 'checked="checked"';
                }
                ?> /><div id="options_<?php
                echo $theline;?>" ></div></td></tr><?php
            }
            ?></tbody></table><p class="buttons"><input type="button" onclick="test_form_secondary();" name="valid" value="<?php
            echo _VALIDATE;
            ?>" class="button"/> <input type="button" name="cancel" value="<?php
            echo _CANCEL;
            ?>" onclick="destroyModal('modal_secondary_baskets');" class="button"/></p></form></div><?php

            $content = ob_get_clean();
        } else {
            ob_start();
            ?><h2><?php
            echo _NO_BASKET;
            ?></h2><?php
            $content = ob_get_clean();
        }
        return $content;
    }
}
