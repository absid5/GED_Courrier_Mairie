<?php

/*
*    Copyright 2008-2016 Maarch
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

/**
* modules tools Class for entities
*
*  Contains all the functions to load modules tables for entities
*
* @package  maarch
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
* @author  Claire Figueras  <dev@maarch.org>
*/
require_once 'modules/entities/entities_tables.php';
require_once 'core/core_tables.php';

abstract class entities_Abstract extends functions
{
    /**
    * Build Maarch module tables into sessions vars with a xml configuration
    * file
    */
    public function build_modules_tables()
    {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'entities'. DIRECTORY_SEPARATOR . 'xml'
            . DIRECTORY_SEPARATOR . 'config.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                  . 'entities' . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'config.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'entities'
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'config.xml';
        }
        $xmlconfig = simplexml_load_file($path);
        foreach ($xmlconfig->TABLENAME as $tablename) {

            $_SESSION['tablename']['ent_entities'] =
                (string) $tablename->ent_entities;
            $_SESSION['tablename']['ent_users_entities'] =
                (string) $tablename->ent_users_entities;
            $_SESSION['tablename']['ent_listmodels'] =
                (string) $tablename->ent_listmodels;
           	$_SESSION['tablename']['ent_listinstance'] =
           	    (string) $tablename->ent_listinstance;
           	$_SESSION['tablename']['ent_groupbasket_redirect'] =
           	     (string) $tablename->ent_groupbasket_redirect;
        }

        $history = $xmlconfig->HISTORY;
        $_SESSION['history']['entityadd'] = (string) $history->entityadd;
        $_SESSION['history']['entityup'] = (string) $history->entityup;
        $_SESSION['history']['entitydel'] = (string) $history->entitydel;
        $_SESSION['history']['entityval'] = (string) $history->entityval;
        $_SESSION['history']['entityban'] = (string) $history->entityban;
    }

    public function load_module_var_session($userData)
    {
        $_SESSION['user']['entities'] = array();
        $_SESSION['entities_types'] = array();
        $_SESSION['user']['primaryentity'] = array();
        if (isset($userData['UserId'])) {
            $type = 'root';
            $db = new Database();
            $stmt = $db->query(
            	'SELECT ue.entity_id, ue.user_role, ue.primary_entity, '
                . 'e.entity_label, e.short_label, e.entity_type FROM '
                . ENT_USERS_ENTITIES . ' ue, ' . $_SESSION['tablename']['users']
                . ' u,'. ENT_ENTITIES ." e WHERE ue.user_id = u.user_id and "
                . " ue.entity_id = e.entity_id and e.enabled = 'Y' "
                . " and ue.user_id = ? ",
                array(trim($userData['UserId']))
            );
        }
        while ($line = $stmt->fetchObject()) {
            array_push(
                $_SESSION['user']['entities'],
                array(
                	'ENTITY_ID'	   => $line -> entity_id,
                	'ENTITY_LABEL' => $line -> entity_label,
                	'SHORT_LABEL'  => $line -> short_label,
                	'ROLE'         => $line -> user_role,
                	'ENTITY_TYPE'  => $line -> entity_type
                )
            );

            if ($line->primary_entity == 'Y') {
                $_SESSION['user']['primaryentity']['id'] = $line->entity_id;
            }
        }
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'xml'
            . DIRECTORY_SEPARATOR . 'typentity.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                  . 'entities' . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'typentity.xml';
        } else {
            $path = 'modules' . DIRECTORY_SEPARATOR . 'entities'
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'typentity.xml';
        }

        $xmltype = simplexml_load_file($path);
        $entypes = array();

        foreach ($xmltype->TYPE as $type) {
            $_SESSION['entities_types'][] = array(
            	'id'    => (string) $type -> id,
            	'label' => (string) $type -> label,
            	'level' => (string) $type -> typelevel,
            );
        }
        $core = new core_tools;
        if ($core->is_module_loaded('basket')) {
            $_SESSION['user']['redirect_groupbasket'] = array();
            if (isset($userData)
            	&& isset($userData['primarygroup'])
            	&& isset($userData['UserId'])
            ) {
	            $arr1 = $this->load_redirect_groupbasket_session(
	                $userData['primarygroup'],
	                $userData['UserId']
	            );
	            $arr2 = $this->load_redirect_groupbasket_session_for_abs(
	                $userData['UserId']
	            );
                $arrSecondary = array();
                for ($cptB=0;$cptB<count($_SESSION['user']['baskets']);$cptB++) {
                    $arrTmp = array();
                    if ($_SESSION['user']['baskets'][$cptB]['is_secondary']) {
                        $arrTmp = $this->load_redirect_groupbasket_secondary_session(
                            $_SESSION['user']['baskets'][$cptB]['id'],
                            $_SESSION['user']['baskets'][$cptB]['group_id'],
                            $userData['UserId']
                        );
                        //$this->show_array($arr3);
                    }
                    if (!empty($arrTmp[$_SESSION['user']['baskets'][$cptB]['id']])) {
                        $arrSecondary = array_merge($arrSecondary, $arrTmp);
                    }
                }
                if (!empty($arrSecondary)) {
                    $_SESSION['user']['redirect_groupbasket']  = array_merge(
                        $arr1, $arr2, $arrSecondary
                    );
                } else {
                    $_SESSION['user']['redirect_groupbasket']  = array_merge(
                        $arr1, $arr2
                    );
                }
            }
        }

    }

    public function process_where_clause($whereClause, $userId)
    {
        if (! preg_match('/@/', $whereClause)) {
            return $whereClause;
        }
        $where = $whereClause;
        $tmpArr = array();
        // We must create a new object because the object connexion can already
        // be used
        $db = new Database();
        require_once 'modules' . DIRECTORY_SEPARATOR . 'entities'
            . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
            . 'class_manage_entities.php';
        $obj = new entity();
        if (preg_match('/@my_entities/', $where)) {
            $entities = '';
            $stmt = $db->query(
            	"select entity_id from " . ENT_USERS_ENTITIES
                . " where user_id = ? ",array(trim($userId))
            );
            while ($res = $stmt->fetchObject()) {
                $entities .= "'"  . $res->entity_id . "', ";
            }

            $entities = preg_replace('/, $/', '', $entities);

            if ($entities == '' && $userId == 'superadmin') {
                $entities = $this->empty_list();
            }
            $where = str_replace("@my_entities", $entities, $where);
        }
        if (preg_match('/@all_entities/', $where)) {
            $entities = '';
            $stmt = $db->query(
            	"select entity_id from " . ENT_ENTITIES . " where enabled ='Y'"
            );
            while ($res = $stmt->fetchObject()) {
                $entities .= "'" . $res->entity_id . "', ";
            }
            $entities = preg_replace("|, $|", '', $entities);
            $where = str_replace("@all_entities", $entities, $where);
        }
        if (preg_match('/@my_primary_entity/', $where)) {
            $primEntity = '';
            if (isset($_SESSION['user']['UserId'])
                && $userId == $_SESSION['user']['UserId']
                && isset($_SESSION['user']['primary_entity']['id'])
            ) {
                $primEntity = "'" . $_SESSION['user']['primary_entity']['id']
                            . "'";
            } else {
                $stmt = $db->query(
                	"select entity_id from " . ENT_USERS_ENTITIES
                    . " where user_id = ? and primary_entity = 'Y'",array(trim($userId))
                );
                //$db->show();
                $res = $stmt->fetchObject();
                if (isset($res->entity_id)) {
                    $primEntity = "'" . $res->entity_id . "'";
                }
            }
            if ($primEntity == '' && $userId == 'superadmin') {
                $primEntity = $this->empty_list();
            }
            $where = str_replace("@my_primary_entity", $primEntity, $where);
            //echo "<br>".$where."<br>";
        }

        $total = preg_match_all(
            "|@entity_type\[('[^\]]*')\]|", $where, $tmpArr,
            PREG_PATTERN_ORDER
        );
        if ($total > 0) {
            for ($i = 0; $i < $total; $i ++) {
                $tmp = str_replace("'", '', $tmpArr[1][$i]);
                $tmp = trim($tmp);
                $tmpEntities = array();

                $db = new Database();
                if(!empty($tmp))
                {
                    $stmt = $db->query('select entity_id from '.ENT_ENTITIES." where entity_type = ?", array(trim($tmp)));
                    while($res = $stmt->fetchObject())
                        array_push($tmpEntities, "'".$res->entity_id."'");
                }
                $entities = "";
                for ($j = 0; $j < count($tmpEntities); $j++) {
                    $entities .= $tmpEntities[$j].", ";
                }
                $entities = preg_replace("|, $|", '', $entities);

                if ($entities == '' && $userId == 'superadmin') {
                    $entities = $this->empty_list();
                }
                $where = preg_replace(
                    "|@entity_type\['[^\]]*'\]|", $entities, $where, 1
                );
            }
        }

        $total = preg_match_all(
        	"|@subentities\[('[^\]]*')\]|", $where, $tmpArr, PREG_PATTERN_ORDER
        );
        if ($total > 0) {
            //$this->show_array( $tmpArr);
            for ($i = 0; $i < $total; $i ++) {
                $entitiesArr = array();
                $tmp = str_replace("'", '', $tmpArr[1][$i]);
                if (preg_match('/,/', $tmp)) {
                    $entitiesArr = preg_split('/,/', $tmp);
                } else {
                    array_push($entitiesArr, $tmp);
                }

                $children = array();
                for ($j = 0; $j < count($entitiesArr); $j ++) {
                    $tabChildren = array();
                    $arr = $obj->getTabChildrenId(
                        $tabChildren, $entitiesArr[$j]
                    );
                    $children = array_merge($children, $arr);
                }
                $entities = '';
                for ($j = 0; $j < count($children); $j ++) {
                    //$entities .= "'".$children[$j]."', ";
                    $entities .= $children[$j] .  ", ";
                }
                $entities = preg_replace("|, $|", '', $entities);
                if ($entities == '' && $userId == 'superadmin') {
                    $entities = $this->empty_list();
                }
                $where = preg_replace(
                	"|@subentities\['[^\]]*'\]|", $entities, $where, 1
                );
            }
        }
        $total = preg_match_all(
        	"|@immediate_children\[('[^\]]*')\]|", $where, $tmpArr,
            PREG_PATTERN_ORDER
        );
        if ($total > 0) {
            //$this->show_array($tmpArr);
            for ($i = 0; $i < $total; $i ++) {
                $entitiesArr = array();
                $tmp = str_replace("'", '', $tmpArr[1][$i]);
                if (preg_match('/,/' , $tmp)) {
                    $entitiesArr = preg_split('/,/', $tmp);
                } else {
                    array_push($entitiesArr, $tmp);
                }

                $children = array();
                for ($j = 0; $j < count($entitiesArr); $j ++) {
                    $tabChildren = array();
                    $arr = $obj->getTabChildrenId(
                        $tabChildren, $entitiesArr[$j], '', true
                    );
                    $children = array_merge($children, $arr);
                }
                //print_r($children);
                $entities = '';
                for ($j = 0; $j < count($children); $j ++) {
                    //$entities .= "'".$children[$j]."', ";
                    $entities .= $children[$j] . ", ";
                }
                $entities = preg_replace("|, $|", '', $entities);
                if ($entities == '' && $userId == 'superadmin') {
                    $entities = $this->empty_list();
                }

                $where = preg_replace(
                	"|@immediate_children\['[^\]]*'\]|", $entities, $where, 1
                );
            }
        }

        $total = preg_match_all(
        	"|@sisters_entities\[('[^\]]*')\]|", $where, $tmpArr,
            PREG_PATTERN_ORDER
        );
        if ($total > 0) {
            //$this->show_array( $tmpArr);
            for ($i = 0; $i < $total; $i ++) {
                $tmp = str_replace("'", '', $tmpArr[1][$i]);
                $tmp = trim($tmp);
                $entities = $obj->getTabSisterEntityId($tmp);
                $sisters = '';
                for ($j = 0; $j < count($entities); $j ++) {
                    $sisters .= $entities[$j].", ";
                }
                $sisters = preg_replace("|, $|", '', $sisters);
                if ($sisters == '' && $userId == 'superadmin') {
                    $sisters = $this->empty_list();
                }
                $where = preg_replace(
                	"|@sisters_entities\['[^\]]*'\]|", $sisters, $where, 1
                );
            }
        }
        $total = preg_match_all(
        	"|@parent_entity\[('[^\]]*')\]|", $where, $tmpArr,
            PREG_PATTERN_ORDER
        );
        if ($total > 0) {
            //$this->show_array( $tmpArr);
            for ($i = 0; $i < $total; $i ++) {
                $tmp = str_replace("'", '', $tmpArr[1][$i]);
                $tmp = trim($tmp);
                $entity = $obj->getParentEntityId($tmp);
                $entity = "'" . $entity . "'";
                if ($entity == '' && $userId == 'superadmin') {
                    $entity = $this->empty_list();
                }
                $where = preg_replace(
                	"|@parent_entity\['[^\]]*'\]|", $entity, $where, 1
                );
            }
        }
        
        /* CV 1.5 : ancestors up to depth n*/        
        $total = preg_match_all(
        	"|@ancestor_entities\[('[^\]]*')\](?:\[(\d)\])?|", $where, $tmpArr,
            PREG_PATTERN_ORDER
        );
        if ($total > 0) {
            //$this->show_array( $tmpArr);
            for ($i = 0; $i < $total; $i ++) {
                $entity = trim(str_replace("'", '', $tmpArr[1][$i]));
                $max_depth = false;
                if(isset($tmpArr[2][$i]) && $tmpArr[2][$i] > 0)
                    $max_depth = $tmpArr[2][$i];
                $ancestors = array();
                while (($entity = $obj->getParentEntityId($entity)) && (!$max_depth || $depth < $max_depth)) { 
                    $ancestors[] = $entity;
                    $depth++;
                }
                if(count($ancestors))
                    $entity = "'" . implode("', '", $ancestors) . "'";
                else $entity = $this->empty_list();;
                if($userId == 'superadmin')
                    $entity = $this->empty_list();
                $where = preg_replace(
                    "|@ancestor_entities\[('[^\]]*')\](?:\[(\d)\])?|", $entity, $where, 1
                    );
            }
        }
        
        $where = str_ireplace("DESTINATION in ()", "1=2", $where);
        $where = str_ireplace("initiator in ()", "1=2", $where);
        return $where;
    }

    public function update_redirect_groupbasket_db($groupId, $basketId,
        $actionId, $entities = array(), $usersEntities = array()
    ) {
        //$this->show_array($usersEntities);
        $db = new Database();
        $stmt = $db->query(
        	"DELETE FROM " . ENT_GROUPBASKET_REDIRECT
            . " where basket_id= ? and group_id = ? and action_id = ?",array(trim($basketId), trim($groupId), $actionId)
        );
        $redirectMode = 'ENTITY';
        for ($i = 0; $i < count($entities); $i ++) {
            if ($entities[$i]['KEYWORD'] == true) {
                $keyword = $entities[$i]['ID'];
                $entityId = '';

            } else {
                $keyword = '';
                $entityId = $entities[$i]['ID'];
            }
            $stmt = $db->query(
            	"INSERT INTO " . ENT_GROUPBASKET_REDIRECT
                . " (group_id, basket_id, action_id, entity_id, keyword,"
                . " redirect_mode ) values ( ?, ?, ?, ?, ?, ?)",array(trim($groupId), trim($basketId), $actionId, trim($entityId), trim($keyword), $redirectMode)
            );
        }

        $redirectMode = 'USERS';
        for ($i = 0; $i < count($usersEntities); $i ++) {
            if ($usersEntities[$i]['KEYWORD'] == true) {
                $keyword = $usersEntities[$i]['ID'];
                $entityId = '';
            } else {
                $keyword = '';
                $entityId = $usersEntities[$i]['ID'];
            }
            $stmt = $db->query(
            	"INSERT INTO " . ENT_GROUPBASKET_REDIRECT . " (group_id, "
                . "basket_id, action_id, entity_id, keyword, redirect_mode ) "
                . "values ( ?, ?, ?, ?, ?, ?)",array(trim($groupId), trim($basketId), $actionId, trim($entityId), trim($keyword), $redirectMode)
            );
        }
    }

    public function get_values_redirect_groupbasket_db(
        $groupId, $basketId, $actionId
    ) {
        $db = new Database();
        
        

        $arr['ENTITY'] = array();
        $stmt = $db->query(
        	"select entity_id, keyword from " . ENT_GROUPBASKET_REDIRECT
            . "  where  group_id = ? and basket_id = ? and redirect_mode "
            . "= 'ENTITY' and action_id = ?",array($groupId,trim($basketId),$actionId)
        );

        while ($res = $stmt->fetchObject()) {
            if ($res->entity_id <> '') {
                $stmt2 = $db->query(
                	"select entity_label from " . ENT_ENTITIES
                    . " where entity_id = ? ",array(trim($res->entity_id))
                );
                $line = $stmt2->fetchObject();
                $label = functions::show_string($line->entity_label);
                $tab = array(
                	'ID' => $res->entity_id,
                	'LABEL' => $label,
                	'KEYWORD' => false,
                );
                array_push($arr['ENTITY'] , $tab);
            } else if ($res->keyword <> '') {
                for ($i = 0; $i < count(
                    $_SESSION['m_admin']['redirect_keywords']
                ); $i ++
                ) {
                    if ($_SESSION['m_admin']['redirect_keywords'][$i]['ID'] ==
                        $res->keyword
                    ) {
                        $label =
                            $_SESSION['m_admin']['redirect_keywords'][$i]
                                ['LABEL'];
                        break;
                    }
                }
                $tab = array(
                	'ID'      => $res->keyword,
                	'LABEL'   => $label,
                	'KEYWORD' => true,
                );
                array_push($arr['ENTITY'] , $tab);
            }
        }

        $arr['USERS'] = array();
        $stmt = $db->query(
        	"select entity_id, keyword from " . ENT_GROUPBASKET_REDIRECT
            . "  where  group_id = ? and basket_id = ? and redirect_mode = 'USERS' and action_id = ?",array(trim($groupId),trim($basketId),$actionId)
        );


        while ($res = $stmt->fetchObject()) {
            if ($res->entity_id <> '') {
                $stmt2 = $db->query(
                	"select entity_label from " . ENT_ENTITIES
                    . " where entity_id = ?",array(trim($res->entity_id))
                );
                $line = $stmt2->fetchObject();
                $label = functions::show_string($line->entity_label);
                $tab = array(
                	'ID'      => $res->entity_id,
                	'LABEL'   => $label,
                	'KEYWORD' => false,
                );
                array_push($arr['USERS'], $tab);
                array_push($arr['USERS'], $tab);
            } else if ($res->keyword <> '') {
                for ($i = 0; $i < count(
                    $_SESSION['m_admin']['redirect_keywords']
                ); $i ++
                ) {
                    if ($_SESSION['m_admin']['redirect_keywords'][$i]['ID'] ==
                        $res->keyword
                    ) {
                        $label =
                            $_SESSION['m_admin']['redirect_keywords'][$i]
                                ['LABEL'];
                        break;
                    }
                }
                $tab = array(
                	'ID'      => $res->keyword,
                	'LABEL'   => $label,
                	'KEYWORD' => true,
                );
                array_push($arr['USERS'] , $tab);
            }
        }
        return $arr;
    }

    public function get_info_entity($entityId)
    {
        $arr = array();
        $arr['label'] = '';
        $arr['keyword'] = false;
        if (empty($entityId)) {
            return $arr;
        }
        for ($i = 0; $i < count($_SESSION['m_admin']['entities']); $i ++) {
            if ($_SESSION['m_admin']['entities'][$i]['ID'] == $entityId) {
                $arr['label'] = $_SESSION['m_admin']['entities'][$i]['LABEL'];
                $arr['keyword'] =
                    $_SESSION['m_admin']['entities'][$i]['KEYWORD'];
                return $arr;
            }
        }
        return $arr;
    }

    public function load_redirect_groupbasket_session($primaryGroup, $userId)
    {
        $arr = array();
        $db = new Database();
        $stmt = $db->query(
        	'select distinct basket_id from ' . ENT_GROUPBASKET_REDIRECT
            . " where group_id = ?",array(trim($primaryGroup))
        );

     
        while ($res = $stmt->fetchObject()) {
            $basketId = $res->basket_id;
            $arr[$basketId] = array();

            $stmt2 = $db->query(
            	"select distinct action_id from " . ENT_GROUPBASKET_REDIRECT
                . " where group_id = ? and basket_id = ?",array(trim($primaryGroup),trim($basketId))
            );
            while ($line = $stmt2->fetchObject()) {
                $actionId = $line->action_id;
                $arr[$basketId][$actionId]['entities'] = '';
                $arr[$basketId][$actionId]['users_entities'] = '';
                $tmpArr = $this->get_redirect_groupbasket(
                    $primaryGroup, $basketId, $userId, $actionId
                );
                $arr[$basketId][$actionId]['entities'] = $tmpArr['entities'];
                $arr[$basketId][$actionId]['users_entities'] = $tmpArr['users'];
            }
        }
        return $arr;
    }

    public function load_redirect_groupbasket_secondary_session($basketId, $groupId, $userId)
    {
        $arr = array();
        $db = new Database();

        
        $arr[$basketId] = array();

        $stmt = $db->query(
            "select distinct action_id from " . ENT_GROUPBASKET_REDIRECT
            . " where group_id = ? and basket_id = ?",array(trim($groupId),trim($basketId))
        );
        while ($line = $stmt->fetchObject()) {
            $actionId = $line->action_id;
            $arr[$basketId][$actionId]['entities'] = '';
            $arr[$basketId][$actionId]['users_entities'] = '';
            $tmpArr = $this->get_redirect_groupbasket(
                $groupId, $basketId, $userId, $actionId
            );
            $arr[$basketId][$actionId]['entities'] = $tmpArr['entities'];
            $arr[$basketId][$actionId]['users_entities'] = $tmpArr['users'];
        }

        return $arr;
    }

    public function load_redirect_groupbasket_session_for_abs($userId)
    {
        $arr = array();
        $db = new Database();

        if (! isset($_SESSION['user']['baskets'])) {
            require_once('modules/basket/class/class_modules_tools.php');
            $bask = new basket();
            $baskAbs = $bask->load_basket_abs($userId);
        } else {
            $baskAbs = $_SESSION['user']['baskets'];
        }
        for ($i = 0; $i < count($baskAbs); $i ++) {
            if ($baskAbs[$i]['abs_basket']) {
                $stmt = $db->query(
                	"select uc.group_id from " . USERGROUP_CONTENT_TABLE
                    . " uc , " . USERGROUPS_TABLE . " u where uc.user_id = ? and u.group_id = "
                    . "uc.group_id and u.enabled= 'Y' and "
                    . "uc.primary_group = 'Y'",array($baskAbs[$i]['basket_owner'])
                );
                //$db->show();
                $res = $stmt->fetchObject();
                $primaryGroup = $res->group_id;
                $tmpBasketId = preg_replace(
                	'/_' . $baskAbs[$i]['basket_owner'] . '$/', '',
                    $baskAbs[$i]['id']
                );
                $stmt = $db->query(
                	"select distinct action_id from " . ENT_GROUPBASKET_REDIRECT
                    . " where group_id = ? and basket_id = ?",array(trim($primaryGroup),trim($tmpBasketId))
                );
                //$db->show();
                while ($line = $stmt->fetchObject()) {
                    $actionId = $line->action_id;
                    $arr[$baskAbs[$i]['id']][$actionId]['entities'] = '';
                    $arr[$baskAbs[$i]['id']][$actionId]['users_entities'] = '';

                    $tmpArr = $this->get_redirect_groupbasket(
                        $primaryGroup, $tmpBasketId,
                        $baskAbs[$i]['basket_owner'], $actionId
                    );
                    $arr[$baskAbs[$i]['id']][$actionId]['entities'] =
                        $tmpArr['entities'];
                    $arr[$baskAbs[$i]['id']][$actionId]['users_entities'] =
                        $tmpArr['users'];
                }
            }
        }
        return $arr;
    }


    public function get_redirect_groupbasket($groupId, $basketId, $userId, $actionId)
    {
        $arr = array();
        $db = new Database();
        $stmt = $db->query(
        	"select entity_id, keyword from " . ENT_GROUPBASKET_REDIRECT
            . " where basket_id = ? and group_id = ? and redirect_mode = 'ENTITY' and action_id = ?",array(trim($basketId),trim($groupId),$actionId)
        );
        
        $entities = '';
        while ($line = $stmt->fetchObject()) {
            if (empty($line->keyword)) {
                $entities .= "'" . $line->entity_id . "', ";
            } else {
                $entities .= $this->translate_entity_keyword($line->keyword)
                          . ", ";
            }
        }

        $entities = preg_replace("/, $/", '', $entities);
        $entities = $this->process_where_clause($entities, $userId);
        $entities = preg_replace("/^,/", '', $entities);
        $entities = preg_replace("/^ ,/", '', $entities);
        $entities = preg_replace("/, ,/", ',', $entities);
        $entities = preg_replace("/, $/", '', $entities);

        $stmt = $db->query(
        	"select entity_id, keyword from " . ENT_GROUPBASKET_REDIRECT
            . " where basket_id = ? and group_id = ? and redirect_mode = 'USERS' and action_id = ?",array(trim($basketId),trim($groupId),$actionId)
        );
        //$db->show();
        $users = '';
        while ($line = $stmt->fetchObject()) {
            if (empty($line->keyword)) {
                $users .= "'" . $line->entity_id . "', ";
            } else {
                $users .= $this->translate_entity_keyword($line->keyword)
                       . ", ";
            }
        }

        $users = preg_replace("/, $/", '', $users);
        $users = $this->process_where_clause($users, $userId);
        $users = preg_replace("/^,/", '', $users);
        $users = preg_replace("/, ,/", ',', $users);
        $users = preg_replace("/, $/", '', $users);

        $arr['entities'] = $entities;
        $arr['users'] = $users;
        //print_r($arr);
        return $arr;
    }

    public function translate_entity_keyword($keyword)
    {
        if ($keyword == 'ALL_ENTITIES') {
            return '@all_entities';
        } else if ($keyword == 'ENTITIES_JUST_BELOW') {
            return '@immediate_children[@my_primary_entity]';
        } else if ($keyword == 'ENTITIES_BELOW') {
            return '@subentities[@my_entities]';
        } else if ($keyword == 'ALL_ENTITIES_BELOW') {
            return '@subentities[@my_primary_entity]';
        } else if ($keyword == 'ENTITIES_JUST_UP') {
            return '@parent_entity[@my_primary_entity]';
        } else if ($keyword == 'MY_ENTITIES') {
            return '@my_entities';
        } else if ($keyword == 'MY_PRIMARY_ENTITY') {
            return '@my_primary_entity';
        } else if ($keyword == 'SAME_LEVEL_ENTITIES') {
            return '@sisters_entities[@my_primary_entity]';
        } else {
            return '';
        }
    }
}
