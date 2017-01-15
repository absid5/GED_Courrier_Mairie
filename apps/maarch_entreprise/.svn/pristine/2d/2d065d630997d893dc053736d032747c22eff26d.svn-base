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


/**
 * @defgroup list Lists
 */

/**
 * @brief   Lists :  
 *
 *
 * @file
 * @author Yves Christian Kpakpo <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup list
 */
 
 /**
  * @brief  Parameters
  *
  * Parameters for lists             array
  
        [bool_modeReturn]                   boolean     Return or echo mode (default: true)
        [divListId]                         string      Id of the div used to contain ajax return of list (default: divList)
        [moduleName]                        string      Name of the module where the page is
        [pageName]                          string      The calling page
        [bool_pageInModule]                 boolean     The calling page is part of a module
        [urlParameters]                     string      Extra parameters for url to page
        [pageTitle]                         string      Title of the page to be displayed over the list
        [bool_bigPageTitle]                 boolean     Size of the title. If false small size
        [pagePicto]                         string      Image to be displayed near title
        [bool_showIconDetails]              boolean     Show the detail icon and link 
        [viewDetailsLink]                   string      Default details link
        [bool_showIconDocument]             boolean     Show the icon view document 
        [bool_showAddButton]                boolean     Show the Add Button
        [bool_showBottomToolbar]            boolean     Show bottom toolbar (default: true)
        [bool_showSmallToolbar]             boolean     Show toolbar in small mode (default: false)
        [addButtonLabel]                    string      Label of the Add Button, (default: _ADD)
        [addButtonLink]                     string      Url of the Add Button link
        [addButtonScript]                   string      Action or function executed on click to Add Button 
        [listCss]                           string      Css used in the list (default: listing spec)
        [bool_sortColumn]                   boolean     Show the order icons or not (default: true)
        [linesToShow]                       string      Number of rows to show in the list
        [bool_changeLinesToShow]            boolean     Show dropdown list to change number of rows to show in the list (default: true)
        [listHeight]                        string      Height of the list
        [bool_showSearchTools]              boolean     If true : show list letters filter
        [bool_showSearchBox]                boolean     If true : show search box
        [searchBoxAutoCompletionUrl]        string      Autocompletion url used by search box
        [searchBoxAutoCompletionMinChars]   integer     Number minimum of charactere to activate show autocompletion list (default: 1)
        [searchBoxAutoCompletionParamName]  string      Name of the parameter passed in autocompletion url (default: what)
        [searchBoxAutoCompletionUpdate]     boolean     Search on ID after autocompletion select
        [bool_checkBox]                     boolean     Add checkbox to row (checkbox name : field[])
        [bool_radioButton]                  boolean     Add radio button to row (radio name : field)
        [bool_standaloneForm]               boolean     Add standalone form (no MEP actions needed)
        [formId]                            string      Id of the list form (default: formList)
        [formName]                          string      Name of the list form (default: formList)
        [formMethod]                        string      Method of the list form (default: POST)
        [formAction]                        string      Action of the list form (default: #)
        [formClass]                         string      Class of the list form (default: forms)
        [disabledRules]                     string      Rules to verify to disabled a line in list (must return boolean) Use generic @@field@@ format parameter
        [hiddenFormFields]                  array       Hidden fields in the form (format:  array( 'ID' => "the_id", 'NAME' => "the_name", 'VALUE'=> "the_value"))
        [bool_actionOnLineClick]            boolean     Action on line click       
        [defaultAction]                     string      Id of the default action
        [collId]                            string      Id of the collection (used in actions management)
        [tableName]                         string      Name of the table or view (used in actions management)
        [actions]                           array       List of actions in actions dropdown list (format:  array('ID' => "the_id", 'LABEL' => "the_label"))
        [filters]                           array       List of filters (format:  array('filter_id', 'filter_id', 'filter_id'))
        [templates]                         array       List of tempkates (format:  array('template_id', 'template_id', 'template_id'))
        [defaultTemplate]                   string      Template used by default (default: first in aray)
        [bool_showTemplateDefaultList]              boolean     Show icon and link of the default lists in template list (default: false)
        [buttons]                           array       List of form buttons (format:  array('TYPE' => "the_type_or_default", 'ID' => "the_id", 'LABEL' => "the_label", 'ACTION'=> "the_onclick_action"))
        [processInstructions]               string      Process instructions text (default: _CLICK_LINE_TO_PROCESS)
        [bool_showSublist]                  boolean     Show sublist icon and action (default: false)
        [sublistUrl]                        string      Sublist content url (used in ajax function)
        (*)[actionIcons]                    array       Array of action icons in list (format:  
                                                        array(
                                                            'type'          => 'the_type', 
                                                            'href'          => 'the_href_link', (**)
                                                            'script'        => 'the_javascript_action', (**)
                                                            'class'         => 'the_icon_class', 
                                                            'icon'          => 'the_icon', 
                                                            'label'         => 'the_label', 
                                                            'tooltip'       => 'the_tooltip', 
                                                            'alertText'     => 'the_text_alert',
                                                            'disabledRules' => 'the_disabled_rules'
                                                            'alwaysVisible' => true/false
                                                            )
                                                        ) 
            * Parameters for actions          array
                [type]                              string      Type of the action Icon (switch, button, image or link)
                [on]                                array       Details of action button ON. (only for switch action) (***)
                [off]                               array       Details of action button OFF. (only for switch action) (***)
                [switchRules]                       string      Rule to active switch action (must return boolean). Use @@field_name@@ parameter. (only for switch action)
                *** Details of action button
                [href]                              string      Link for action (** can't use it with [script])
                [script]                            string      javascript for action (** can't use it with [href])
                [tooltip]                           string      Tooltip for action
                [class]                             string      Css style for action link
                [icon]                              string      Icon for action link
                [label]                             string      Label of the link
                [alertText]                         string      Text displayed in the alert box. Can use  @@field_name@@ parameter
                [disabledRules]                     string      To disabled action link (must return boolean). Use @@field_name@@ parameter
 */

require_once 'core' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
. 'class_security.php';

abstract class lists_Abstract extends Database
{
    protected $countResult;
    protected $countTd;
    protected $link;
    protected $start;
    protected $lines;
    protected $end;
    protected $order;
    protected $orderField;
    protected $params ;
    protected $actionButtons;
    protected $withForm;
    protected $formId;
    protected $whatSearch;
    protected $haveAction;
    protected $currentBasket;    
    protected $template;    
    protected $tmplt_CurrentCssLine;    
    protected $modeReturn;    
    protected $divListId;  
    protected $collId;  
    
    function __construct(){
        $this->order = $_REQUEST['order'];
        $this->orderField = $_REQUEST['order_field'];
        $this->start = $_REQUEST['start'];
        $this->whatSearch = $_REQUEST['what'];
        $this->_manageFilters();
        if (isset($_REQUEST['template'])) $this->template = $_REQUEST['template'];
        if (isset($_REQUEST['coll_id'])) $this->collId = $_REQUEST['coll_id'];
    }
    
    protected function _buildFilter($filter) {
        //Reset some values
        $filters = $filtersClause = $where = $options = '';
        
        //Db query
        $db = new Database();
        //var_dump($_SESSION['filters']['contact']);
        //Load filter's data
        switch ($filter) {
        
            case 'status':
                $stmt = $db->query(
                    "SELECT * FROM " . STATUS_TABLE . " WHERE can_be_searched = 'Y' order by label_status"
                );
                while ($res = $stmt->fetchObject()) {
                    if (isset($_SESSION['filters']['status']['VALUE']) 
                        && $_SESSION['filters']['status']['VALUE'] == $res->id
                        ) $selected = 'selected="selected"'; else $selected =  '';
                    $options .='<option value="'.$res->id.'" '.$selected.'>'.$res->label_status.'</option>';
                }
                $filters .='<select name="status_id" id="status_id" onChange="loadList(\''.$this->link
                            .'&filter=status&value=\' + document.filters.status_id.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none">'._CHOOSE_STATUS.'</option>'
                            .$options.'<option value="late">'._LATE.'</option>'
                            .'</select>&nbsp;';
            break;
                
            case 'entity':
                require_once "modules" . DIRECTORY_SEPARATOR . "entities" . DIRECTORY_SEPARATOR
                    . "class" . DIRECTORY_SEPARATOR . "class_manage_entities.php";
                require_once "modules" . DIRECTORY_SEPARATOR . "entities" . DIRECTORY_SEPARATOR
                    . "entities_tables.php";
                    
                $ent = new entity();
                $sec = new security();
                
                $view = $sec->retrieve_view_from_table($this->params['tableName']);
                if (empty($view)) {
                    $view = $this->params['tableName'];
                }
                if (!empty($view)) {
                    if (! empty($this->params['basketClause'])) {
                        $this->params['basketClause'] = str_replace('res_view_letterbox.', 'r.', $this->params['basketClause']);
                        $where = 'where (' . $this->params['basketClause'] . ')';
                    }

                    $stmt = $db->query(
                        "SELECT distinct(r.destination) as entity_id, count(distinct r.res_id)"
                        . " as total, e.entity_label , e.short_label FROM " 
                        . $view. " r left join " . ENT_ENTITIES
                        . " e on e.entity_id = r.destination " .$where . " and entity_id <> ''"
                        . " group by e.entity_label,  e.short_label, r.destination order by e.entity_label"
                    );
                    while ($res = $stmt->fetchObject()) {
                        
                        if ((isset($_SESSION['filters']['entity']['VALUE']) || isset($_SESSION['filters']['entity_subentities']))
                            && $_SESSION['filters']['entity']['VALUE'] == $res->entity_id
                            )  $selected = 'selected="selected"'; else $selected =  '';
                            
                        if ($ent->is_user_in_entity($_SESSION['user']['UserId'], $res->entity_id)) $style = 'style="font-weight:bold;"';  else $style =  '';

                        $options .='<option value="'.$res->entity_id.'" '.$selected.' '.$style.'>'.$res->short_label.' ('.$res->total.')</option>';
                    }
                }
                $filters .='<select name="entity_id" id="entity_id" onChange="loadList(\''.$this->link
                            .'&filter=entity&value=\' + document.filters.entity_id.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none" style="text-align:center;">'._ENTITY.'</option>'
                            .$options.'</select>';
                $filters .= '<script>new Chosen($(\'entity_id\'));</script>';
            break;

            case 'entity_subentities':
                require_once "modules" . DIRECTORY_SEPARATOR . "entities" . DIRECTORY_SEPARATOR
                    . "class" . DIRECTORY_SEPARATOR . "class_manage_entities.php";
                require_once "modules" . DIRECTORY_SEPARATOR . "entities" . DIRECTORY_SEPARATOR
                    . "entities_tables.php";
                    
                $ent = new entity();
                $sec = new security();

                $view = $sec->retrieve_view_from_table($this->params['tableName']);
                if (empty($view)) {
                    $view = $this->params['tableName'];
                }
                if (!empty($view)) {
                    if (! empty($this->params['basketClause'])) {
                        $this->params['basketClause'] = str_replace('res_view_letterbox.', 'r.', $this->params['basketClause']);
                        $where = 'where (' . $this->params['basketClause'] . ')';
                    }

                    $stmt = $db->query(
                        "SELECT distinct(r.destination) as entity_id, count(distinct r.res_id)"
                        . " as total, e.entity_label , e.short_label FROM " 
                        . $view. " r left join " . ENT_ENTITIES
                        . " e on e.entity_id = r.destination " .$where . " and entity_id <> ''"
                        . " group by e.entity_label,  e.short_label, r.destination order by e.entity_label"
                    );
                    while ($res = $stmt->fetchObject()) {
                        
                        if (isset($_SESSION['filters']['entity_subentities']['VALUE']) 
                            && $_SESSION['filters']['entity_subentities']['VALUE'] == $res->entity_id
                            )  $selected = 'selected="selected"'; else $selected =  '';
                            
                        if ($ent->is_user_in_entity($_SESSION['user']['UserId'], $res->entity_id)) $style = 'style="font-weight:bold;"';  else $style =  '';

                        $subEntities_tmp = array();
                        $subEntities = array();
                        $subEntities_tmp = $ent->getEntityChildrenTree($subEntities_tmp, $res->entity_id);

                        for($iSubEntities=0;$iSubEntities<count($subEntities_tmp);$iSubEntities++){
                            array_push($subEntities, "'".$subEntities_tmp[$iSubEntities]['ID']."'");
                        }
                        array_push($subEntities, "'" . $res->entity_id . "'");

                        if(isset($_SESSION['current_basket']['view']) && $_SESSION['current_basket']['view'] <> ""){
                            $view = $_SESSION['current_basket']['view'];
                        }

                        $this->params['basketClause'] = str_replace('r.', 'res_view_letterbox.', $this->params['basketClause']);
                        $stmt2 = $db->query("SELECT count(res_id) as total FROM ".$view." WHERE (".$this->params['basketClause'].") and destination in (" . implode(",",$subEntities) . ")");
                        $res2 = $stmt2->fetchObject();

                        $options .='<option value="'.$res->entity_id.'" '.$selected.' '.$style.'>'.$res->short_label.' ('.$res2->total.')</option>';
                    }
                }
                  if (isset($_SESSION['filters']['entity']['VALUE']) && $_SESSION['filters']['entity_subentities']['checked'] == true && $_SESSION['filters']['entity']['VALUE'] != ''){
                    $checked = 'checked="checked"';
                  }else{
                        $checked =  '';
                  }
                    
                $filters .='<input type="checkbox" '.$checked.' style="margin-left:0px" title="inclure les sous-entités" onclick="loadList(\''.$this->link
                            .'&filter=entity_subentities&value=\' + document.filters.entity_id.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');" />&nbsp;';
                /*$filters .='<select name="entity_subentities" id="entity_subentities" onChange="loadList(\''.$this->link
                            .'&filter=entity_subentities&value=\' + document.filters.entity_subentities.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none">'._CHOOSE_ENTITY_SUBENTITIES.'</option>'
                            .$options.'</select>&nbsp;';*/
            break;
            
            case 'typist':
                $sec = new security();
                
                $view = $sec->retrieve_view_from_table($this->params['tableName']);
                if (empty($view)) {
                    $view = $this->params['tableName'];
                }
                if (!empty($view)) {
                    if (! empty($this->params['basketClause'])) $where = 'where '.$this->params['basketClause'];

                    $stmt = $db->query(
                        "SELECT distinct(typist) as typist, count(distinct res_id)"
                        . " as total FROM res_view_letterbox r" 
                        . $view. " r " .$where
                        . " group by typist order by typist"
                    );
            
                    while ($res = $stmt->fetchObject()) {
                        
                        if (isset($_SESSION['filters']['typist']['VALUE']) 
                            && $_SESSION['filters']['typist']['VALUE'] == $res->typist
                            ) $selected = 'selected="selected"'; else $selected =  '';
                            
                        if ($_SESSION['user']['UserId'] ==  $res->typist) $style = 'style="font-weight:bold;"';  else $style =  '';

                        $options .='<option value="'.$res->typist.'" '.$selected.' '.$style.'>'.$res->typist.' ('.$res->total.')</option>';
                    }
                }
                $filters .='<select name="typist" id="typist" onChange="loadList(\''.$this->link
                            .'&filter=typist&value=\' + document.filters.typist.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none">'._CHOOSE_USER2.'</option>'
                            .$options.'</select>&nbsp;';
            break;
            
            case 'category':
                $filters .='<select name="category_id_list" id="category_id_list" onChange="loadList(\''.$this->link
                         .'&filter=category&value=\' + document.filters.category_id_list.value, \''
                         .$this->divListId.'\', '.$this->modeReturn.');">';
                $filters .='<option value="none" style="text-align:center;">'._CATEGORY.'</option>';
                foreach (array_keys($_SESSION['coll_categories'][$this->collId]) as $catId) {
                    if ($catId <> 'default_category') {
                        if (isset($_SESSION['filters']['category']['VALUE']) 
                            && $_SESSION['filters']['category']['VALUE'] == $catId
                        ) $selected = 'selected="selected"'; else $selected =  '';
                        $filters .='<option value="'.$catId.'" '.$selected.'>'.$_SESSION['coll_categories'][$this->collId][$catId].'</option>';
                    }
                }
                $filters .='</select>&nbsp;';
                $filters .= '<script>new Chosen($(\'category_id_list\'));</script>';
            break;

            case 'priority':
                $filters .='<select name="priority_id_list" id="priority_id_list" onChange="loadList(\''.$this->link
                         .'&filter=priority&value=\' + document.filters.priority_id_list.value, \''
                         .$this->divListId.'\', '.$this->modeReturn.');">';
                $filters .='<option value="none" style="text-align:center;">'._PRIORITY.'</option>';
                foreach (array_keys($_SESSION['mail_priorities']) as $priorityId) {                 
                        if (is_numeric($_SESSION['filters']['priority']['VALUE']) && $_SESSION['filters']['priority']['VALUE'] == $priorityId){
                            $selected = 'selected="selected"';
                        } else{ 
                            $selected =  '';
                        }
                        $filters .='<option value="'.$priorityId.'" '.$selected.'>'.$_SESSION['mail_priorities'][$priorityId].'</option>';
                    
                }
                $filters .='</select>&nbsp;';
                $filters .= '<script>new Chosen($(\'priority_id_list\'),{width: "150px"});</script>';
            break;
            
            case 'isViewed':
                $isViewedArray = array('yes' =>_YES, 'no' => _NO);
                $filters .='<select name="isViewed" id="isViewed" onChange="loadList(\''.$this->link
                         .'&filter=isViewed&value=\' + document.filters.isViewed.value, \''
                         .$this->divListId.'\', '.$this->modeReturn.');">';
                $filters .='<option value="none">'._VIEWED.'</option>';
                foreach ($isViewedArray as $key => $value) {
                    if (isset($_SESSION['filters']['isViewed']['VALUE']) 
                        && $_SESSION['filters']['isViewed']['VALUE'] == $key
                        ) $selected = 'selected="selected"'; else $selected =  '';
                    $filters .='<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                }
                $filters .='</select>&nbsp;';
                $filters .= '<script>new Chosen($(\'isViewed\'),{width: "auto", disable_search: true,});</script>';
            break;
            
            case 'folder':
                if(isset($_SESSION['filters']['folder']['VALUE']) && !empty($_SESSION['filters']['folder']['VALUE'])) {
                    $folder = $_SESSION['filters']['folder']['VALUE'];
                } else {
                    $folder = '['._FOLDER.']';
                }
                $filters .='<input type="text" name="folder_id" id="folder_id" placeholder="'.$folder.'" size="40" '
                            .'onfocus="if(this.value==\'['._FOLDER.']\'){this.value=\'\';}'
                            .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13) loadList(\''.$this->link
                            .'&filter=folder&value=\' + this.value, \''.$this->divListId.'\', '
                            .$this->modeReturn.');" />&nbsp;';
                //Autocompletion script and div            
                $filters .='<div id="folderListByName" class="autocomplete"></div>';
                $filters .='<script type="text/javascript">initList(\'folder_id\', \'folderListByName\', \''
                            .$_SESSION['config']['businessappurl'].'index.php?display=true&module='
                            .'folder&page=folders_list_by_name\', \'folder\', \'2\');</script>';
            break;
            
            case 'contact':
                //if(isset($_SESSION['filters']['contact']['VALUE']) && !empty($_SESSION['filters']['contact']['VALUE'])) {
                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
                    $sec = new security();
                    $pos = strpos($this->params['basketClause'], 'r.');

                    if($pos){
                        $ext = 'r';
                        $view = 'res_view_letterbox r';
                    }else {
                        $ext = 'res_view_letterbox';
                        $view = 'res_view_letterbox';
                    }

                    $query = "SELECT distinct(".$ext.".contact_id),society, firstname, lastname FROM "
                            . $_SESSION['tablename']['contacts_v2'] . " c, ".$view." WHERE c.contact_id = ".$ext.".contact_id and ".$this->params['basketClause'];
                        
                    $stmt = $db->query($query, array());
                                            
                    $filters .='<select id="contact_id_list" onChange="loadList(\''.$this->link
                                             .'&filter=contact&value=\' + document.filters.contact_id_list.value, \''
                                             .$this->divListId.'\', '.$this->modeReturn.');">';
                        $filters .='<option value="none" style="text-align:center;">' . _CONTACT . '</option>';

                    while($line = $stmt->fetchObject()){
                        $filters .='<option value="'.$line->contact_id.'" title="'.$line->society.'"';
                        if(empty($line->lastname)){
                            $person = $line->society;
                        }else{
                            $person = $line->firstname.' '.$line->lastname.' ('.$line->society.')';
                        }
                        
                        if($_SESSION['filters']['contact']['VALUE'] == $line->contact_id){
                            $filters .=' selected="selected" ';    
                        }
                        $filters .='>'.$person.'</option>';

                    }
                    $filters .='</select>&nbsp;';
                    $filters .= '<script>new Chosen($(\'contact_id_list\'),{width: "150px",max_shown_results: "10"});</script>';



                    /*if (is_numeric($_SESSION['filters']['contact']['VALUE'])) {
                        $query = "SELECT society, lastname, firstname, is_corporate_person, society_short FROM "
                            . $_SESSION['tablename']['contacts_v2'] . " WHERE contact_id = ?";
                        
                        $stmt = $db->query($query, array($_SESSION['filters']['contact']['VALUE']));
                        $line = $stmt->fetchObject();

                        if($line->is_corporate_person == 'N'){
                            $contact = functions::show_string($line->lastname)." ".functions::show_string($line->firstname);
                            if($line->society <> ''){
                                $contact .= ' ('.$line->society.')';
                            }
                        } else {
                            $contact .= $line->society;
                            if($line->society_short <> ''){
                                $contact .= ' ('.$line->society_short.')';
                            }
                        }
                    } else {
                        $query = "SELECT lastname, firstname FROM users WHERE user_id = ?";
                        
                        $stmt = $db->query($query, array($_SESSION['filters']['contact']['VALUE']));
                        $line = $stmt->fetchObject();

                        $contact .= functions::show_string($line->firstname) . " " . functions::show_string($line->lastname);
                    }

                } else {
                    $contact = '['._CONTACT.']';
                }
                $filters .='<input type="text" name="contact_id" id="contact_id" placeholder="'.$contact.'" size="25" '
                            .'onfocus="if(this.value==\'['._CONTACT.']\'){this.value=\'\';}" '
                            .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                            .'&filter=contact&value=\' + $(\'contactidFilters\').value, \''.$this->divListId.'\', '
                            .$this->modeReturn.');" />&nbsp;';
                //Autocompletion script and div 
                $filters .='<div id="contactListByName" class="autocomplete"></div>';
                $filters .='<script type="text/javascript">initList_hidden_input(\'contact_id\', \'contactListByName\', \''
                            .$_SESSION['config']['businessappurl'].'index.php?display=true&page='
                            .'contacts_v2_list_by_name_filters&dir=indexing_searching\', \'what\', \'2\', \'contactidFilters\');</script>';
                $filters .= '<input type="hidden" id="contactidFilters" name="contactidFilters" ';
                if(isset($_SESSION['filters']['contact']['VALUE']) && !empty($_SESSION['filters']['contact']['VALUE'])) {
                    $filters .= 'value="'.$_SESSION['filters']['contact']['VALUE'].'"';
                }
                $filters .='/>';*/
            break;

            case 'res_id':

                $res_id = '['._GED.']';
                $filters .='<input type="text" name="res_id" id="res_id" placeholder="'.$res_id.'" size="15" '
                            .'onChange="myFunction(), loadList(\''.$this->link
                            .'&filter=res_id&value=\' + $(\'residFilters\').value, \''.$this->divListId.'\', '
                            .$this->modeReturn.');" />&nbsp;';
                //Autocompletion script and div 
                $filters .='<script type="text/javascript">function myFunction() {';
                $filters .='var x = document.getElementById("res_id").value;';
                $filters .='document.getElementById("residFilters").value = x;';
                $filters .='}</script>';
                $filters .= '<input type="hidden" id="residFilters" name="residFilters" ';
                if(isset($_SESSION['filters']['res_id']['VALUE']) && !empty($_SESSION['filters']['res_id']['VALUE'])) {
                    $filters .= 'value="'.$_SESSION['filters']['res_id']['VALUE'].'"';
                }
                $filters .='/>';
            break;

            case 'subject':

                $pos = strpos($this->params['basketClause'], 'r.');

                if($pos){
                    $ext = 'r';
                    $view = 'res_view_letterbox r';
                }else {
                    $ext = 'res_view_letterbox';
                    $view = 'res_view_letterbox';
                }

                $query = "SELECT distinct(".$ext.".subject), res_id FROM "
                            . $view . " WHERE ".$this->params['basketClause'] . " ORDER BY subject ASC";
                        
                $stmt = $db->query($query, array());
                                        
                $filters .='<select id="subjectFilters" name="subjectFilters" onChange="loadList(\''.$this->link
                             .'&filter=subject&value=\' + document.filters.subjectFilters.value, \''
                             .$this->divListId.'\', '.$this->modeReturn.');">';
                $filters .='<option value="none" style="text-align:center;">' . _SUBJECT . '</option>';

                while($line = $stmt->fetchObject()){
                    $filters .='<option value="'.$line->subject.'" title="'.$line->subject.'"';
                    if($_SESSION['filters']['res_id']['VALUE'] == $line->res_id){
                        $filters .=' selected="selected" ';    
                    }
                    $filters .='>'.$line->subject.'</option>';

                }
                $filters .='</select>&nbsp;';
                $filters .= '<script>new Chosen($(\'subjectFilters\'),{width: "150px",max_shown_results: "10"});</script>';
                /*$subject = '['._SUBJECT.']';
                $filters .='<input type="text" name="subject" id="subject" placeholder="'.$subject.'" size="40" '
                            .'onChange="myFunction(), loadList(\''.$this->link
                            .'&filter=subject&value=\' + $(\'subjectFilters\').value, \''.$this->divListId.'\', '
                            .$this->modeReturn.');" />&nbsp;';
                //Autocompletion script and div 
                $filters .='<script type="text/javascript">function myFunction() {';
                $filters .='var x = document.getElementById("subject").value;';
                $filters .='document.getElementById("subjectFilters").value = x;';
                $filters .='}</script>';
                $filters .= '<input type="hidden" id="subjectFilters" name="subjectFilters" ';
                if(isset($_SESSION['filters']['subject']['VALUE']) && !empty($_SESSION['filters']['subject']['VALUE'])) {
                    $filters .= 'value="'.$_SESSION['filters']['subject']['VALUE'].'"';
                }
                $filters .='/>';*/
            break;
            
            case 'type':
                require_once 'core' . DIRECTORY_SEPARATOR . 'core_tables.php';
                
                if (! empty($this->params['basketClause'])) $where = 'where '.$this->params['basketClause'];
                
                $stmt = $db->query(
                    "SELECT distinct(r.type_id), t.description FROM " 
                    .$this->params['tableName']. " r left join " . DOCTYPES_TABLE
                    . " t on t.type_id = r.type_id " .$where
                    . " group by t.description, r.type_id order by t.description"
                );
                while ($res = $stmt->fetchObject()) {
                    if (isset($_SESSION['filters']['type']['VALUE']) 
                        && $_SESSION['filters']['type']['VALUE'] == $res->type_id
                        ) $selected = 'selected="selected"'; else $selected =  '';
                    $options .= '<option value="' . $res->type_id . ' ' .$selected. '">'. $res->description . '</option>';
                }
                $filters .='<select name="type_id" id="type_id" onChange="loadList(\''.$this->link
                            .'&filter=type&value=\' + document.filters.type_id.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none">'._CHOOSE_TYPE.'</option>'
                            .$options.'</select>&nbsp;';
            break;
            
            case 'user':
                if(isset($_SESSION['filters']['user']['VALUE']) && !empty($_SESSION['filters']['user']['VALUE'])) {
                    $user = $_SESSION['filters']['user']['VALUE'];
                } else {
                    $user = '['._USER.']';
                }
                $filters .='<input type="text" name="user_id" id="user_id" value="'.$user.'" size="30" '
                            .'onfocus="if(this.value==\'['._USER.']\'){this.value=\'\';}" '
                            .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                            .'&filter=user&value=\' + this.value, \''.$this->divListId.'\', '
                            .$this->modeReturn.');" />&nbsp;';
                //Autocompletion script and div 
                $filters .='<div id="userListByName" class="autocomplete"></div>';
                $filters .='<script type="text/javascript">initList(\'user_id\', \'userListByName\', \''
                            .$_SESSION['config']['businessappurl'].'index.php?display=true&page='
                            .'users_autocomplete_list\', \'Input\', \'2\');</script>';
            break;
            
            case 'action':
                $stmt = $db->query(
                    "SELECT id, label_action FROM "
                    . $_SESSION['tablename']['actions']
                    . " WHERE origin = 'folder' and enabled = 'Y' and history = 'Y'"
                );
                while ($res = $stmt->fetchObject()) {
                    $id = 'ACTION#' . $res->id;
                    if (isset($_SESSION['filters']['action']['VALUE']) 
                        && $_SESSION['filters']['action']['VALUE'] == $id
                        ) $selected = 'selected="selected"'; else $selected =  '';
                    $options .='<option value="'.urlencode($id).'" '.$selected.'>'.$res->label_action.'</option>';
                }
                $filters .='<select name="action_id" id="action_id" onChange="loadList(\''.$this->link
                            .'&filter=action&value=\' + document.filters.action_id.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none">'._CHOOSE_ACTION.'</option>'
                            .$options.'</select>&nbsp;';
            break;
            
            case 'history_action':
                for ($i=0;$i<count($_SESSION['history_keywords']);$i++) {
                
                    if (isset($_SESSION['filters']['history_action']['VALUE']) 
                        && $_SESSION['filters']['history_action']['VALUE'] == $_SESSION['history_keywords'][$i]['id']
                    ) $selected = 'selected="selected"'; else $selected =  '';
                    $options .='<option value="'.$_SESSION['history_keywords'][$i]['id'].'" '
                        .$selected.'>'.$_SESSION['history_keywords'][$i]['label'].'</option>';
                }
                $filters .='<select name="history_action_id" id="history_action_id" onChange="loadList(\''.$this->link
                            .'&filter=history_action&value=\' + document.filters.history_action_id.value, \''
                            .$this->divListId.'\', '.$this->modeReturn.');">'
                            .'<option value="none">'._CHOOSE_ACTION.'</option>'
                            .$options.'</select>&nbsp;';
            break;
            
            case 'history_date':
                if(isset($_SESSION['filters']['history_date_start']['VALUE']) && !empty($_SESSION['filters']['history_date_start']['VALUE'])) {
                    $date_start = $_SESSION['filters']['history_date_start']['VALUE'];
                }
                $filters .= '&nbsp;&nbsp;'._SINCE.': <input type="text" name="date_start" '
                    .'id="date_start" onclick="showCalender(this);" '
                    .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                    .'&filter=history_date_start&value=\' + this.value, \''.$this->divListId.'\', '
                    .$this->modeReturn.');" value="'.$date_start.'" size="15" />';
                  
                if(isset($_SESSION['filters']['history_date_end']['VALUE']) && !empty($_SESSION['filters']['history_date_end']['VALUE'])) {
                    $date_end = $_SESSION['filters']['history_date_end']['VALUE'];
                }
                $filters .= '&nbsp;&nbsp;'._FOR.': <input type="text" name="date_end" '
                    .'id="date_end" onclick="showCalender(this);" '
                    .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                    .'&filter=history_date_end&value=\' + this.value, \''.$this->divListId.'\', '
                    .$this->modeReturn.');" value="'.$date_end.'" size="15" />&nbsp;';
            break;
            
            case 'creation_date':
                if(isset($_SESSION['filters']['creation_date_start']['VALUE']) && !empty($_SESSION['filters']['creation_date_start']['VALUE'])) {
                    $date_start = $_SESSION['filters']['creation_date_start']['VALUE'];
                }
                $filters .= '&nbsp;&nbsp;'._SINCE.': <input type="text" name="date_start" '
                    .'id="date_start" onclick="showCalender(this);" '
                    .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                    .'&filter=creation_date_start&value=\' + this.value, \''.$this->divListId.'\', '
                    .$this->modeReturn.');" value="'.$date_start.'" size="15" />';
                  
                if(isset($_SESSION['filters']['creation_date_end']['VALUE']) && !empty($_SESSION['filters']['creation_date_end']['VALUE'])) {
                    $date_end = $_SESSION['filters']['creation_date_end']['VALUE'];
                }
                $filters .= '&nbsp;&nbsp;'._FOR.': <input type="text" name="date_end" '
                    .'id="date_end" onclick="showCalender(this);" '
                    .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                    .'&filter=creation_date_end&value=\' + this.value, \''.$this->divListId.'\', '
                    .$this->modeReturn.');" value="'.$date_end.'" size="15" />&nbsp;';
            break;
      
            case 'identifier':
                if(isset($_SESSION['filters']['identifier']['VALUE']) && !empty($_SESSION['filters']['identifier']['VALUE'])) {
                    $identifier = $_SESSION['filters']['identifier']['VALUE'];
                } else {
                    $identifier = '['._CHRONO_NUMBER.']';
                }
                $filters .='<input type="text" name="identifier" id="identifier" value="'.$identifier.'" size="40" '
                            .'onfocus="if(this.value==\'['._CHRONO_NUMBER.']\'){this.value=\'\';}" '
                            .'onChange="loadList(\''.$this->link
                            .'&filter=identifier&value=\' + this.value, \''.$this->divListId.'\', '.$this->modeReturn.');" '
                            .'onKeyPress="if(event.keyCode == 9 || event.keyCode == 13)loadList(\''.$this->link
                            .'&filter=identifier&value=\' + this.value, \''.$this->divListId.'\', '
                            .$this->modeReturn.');" />&nbsp;';
                break;
            
        }
        
        return $filters;
    }
    
    protected function _haveFilter() {
    
        $haveFilter = false;
        
        foreach ($_SESSION['filters'] as $key => $val) {
            if (!empty($_SESSION['filters'][$key]['VALUE'])) {
                $haveFilter = true;
                break;
            }
        }
        
        return  $haveFilter;
    }
    
    protected function _resetFilter() {

        if($_SESSION['basket_used'] != $_SESSION['current_basket']['id']){

            foreach ($_SESSION['filters'] as $key => $val) {
                $_SESSION['filters'][$key]['VALUE'] = '';
                $_SESSION['filters'][$key]['CLAUSE'] = '';
                }
        }/*elseif($_SESSION['basket_used'] == $_GET['baskets']){
            foreach ($_SESSION['filters'] as $key => $val) {
                $_SESSION['filters'][$key]['VALUE'] = '';
                $_SESSION['filters'][$key]['CLAUSE'] = '';
                }

        }*/

        
    }

    protected function _resetFilter2() {

            foreach ($_SESSION['filters'] as $key => $val) {
                $_SESSION['filters'][$key]['VALUE'] = '';
                $_SESSION['filters'][$key]['CLAUSE'] = '';
                }
           
    }
    
    protected function _manageFilters() {
    
        //Reset all filters
        if ($_REQUEST['filter'] == 'reset'){
        
           $this->_resetFilter2();
           
        } else { //Init filter value and clause
            if(isset($_REQUEST['value']) && !empty($_REQUEST['value'])) {
                
                if ($_REQUEST['value'] == 'none') {
                    //Reset if none
                    $_SESSION['filters'][$_REQUEST['filter']]['VALUE'] = '';
                    $_SESSION['filters'][$_REQUEST['filter']]['CLAUSE'] = '';
                } else {
                    //Keep value
                    $_SESSION['filters'][$_REQUEST['filter']]['VALUE'] = $_REQUEST['value'];
                    //var_dump($_REQUEST['filter']);
                    //Build where clause
                    if ($_REQUEST['filter'] == 'status') {

                        if ($_SESSION['filters']['status']['VALUE'] == 'late') {
                            require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
                            $request = new request();                      
                            $_SESSION['filters']['status']['CLAUSE'] = "(process_limit_date is not null and "
                                .$request->current_datetime()." > "
                                .$request->extract_date('process_limit_date')." ) and status <> 'END'";
                        } else {
                            $_SESSION['filters']['status']['CLAUSE'] = "status = '".$_SESSION['filters']['status']['VALUE']."'";
                        }
                        
                    } else if ($_REQUEST['filter'] == 'entity') {

                        $_SESSION['filters']['entity_subentities']['VALUE'] = '';
                        $_SESSION['filters']['entity_subentities']['CLAUSE'] = '';
                        $_SESSION['filters']['entity_subentities']['checked'] = false;
                    
                       $_SESSION['filters']['entity']['CLAUSE'] = "destination = '".$_SESSION['filters']['entity']['VALUE']."'";
                    
                    } else if ($_REQUEST['filter'] == 'entity_subentities') {

                        //$_SESSION['filters']['entity']['VALUE'] = '';
                        $_SESSION['filters']['entity']['CLAUSE'] = '';
                        $_SESSION['filters']['entity_subentities']['checked'] = true;

                        require_once "modules" . DIRECTORY_SEPARATOR . "entities" . DIRECTORY_SEPARATOR
                            . "class" . DIRECTORY_SEPARATOR . "class_manage_entities.php";

                        $entities = new entity();
                        $subEntities_tmp = array();
                        $subEntities = array();

                        $subEntities_tmp = $entities->getEntityChildrenTree($subEntities_tmp, $_SESSION['filters']['entity_subentities']['VALUE']);

                        for($iSubEntities=0;$iSubEntities<count($subEntities_tmp);$iSubEntities++){
                            array_push($subEntities, "'".$subEntities_tmp[$iSubEntities]['ID']."'");
                        }
                        array_push($subEntities, "'" . $_SESSION['filters']['entity_subentities']['VALUE'] . "'");

                        $_SESSION['filters']['entity_subentities']['CLAUSE'] = "destination in (" . implode(",",$subEntities) . ")";
                    
                    } else if ($_REQUEST['filter'] == 'typist') {
                    
                       $_SESSION['filters']['typist']['CLAUSE'] = "typist = '".$_SESSION['filters']['typist']['VALUE']."'";
                    
                    } else if ($_REQUEST['filter'] == 'category') {
                    
                       $_SESSION['filters']['category']['CLAUSE'] = "category_id = '".$_SESSION['filters']['category']['VALUE']."'";
                       
                    } else if ($_REQUEST['filter'] == 'contact') {
                    
                        if(is_numeric($_SESSION['filters']['contact']['VALUE'])){
                            $_SESSION['filters']['contact']['CLAUSE'] = "(exp_contact_id = '".$_SESSION['filters']['contact']['VALUE']."' or dest_contact_id = '".$_SESSION['filters']['contact']['VALUE']."')";
                        } else {
                            $_SESSION['filters']['contact']['CLAUSE'] = "(exp_user_id = '".$_SESSION['filters']['contact']['VALUE']."' or dest_user_id = '".$_SESSION['filters']['contact']['VALUE']."')";
                        }
                        
                    } else if ($_REQUEST['filter'] == 'folder') {
                        
                        $folderId = $this->protect_string_db(str_replace(')', '', 
                            substr($_SESSION['filters']['folder']['VALUE'], 
                            strrpos($_SESSION['filters']['folder']['VALUE'], '(') + 1)));
                        $_SESSION['filters']['folder']['CLAUSE'] = "folder_id = '".$folderId."'";

                    } else if ($_REQUEST['filter'] == 'identifier') {
                        $_SESSION['filters']['identifier']['CLAUSE'] = "alt_identifier ilike ('%".$_SESSION['filters']['identifier']['VALUE']."%')";

                    } else if ($_REQUEST['filter'] == 'type') {
                    
                        $_SESSION['filters']['type']['CLAUSE'] = "type_id = '".$_SESSION['filters']['type']['VALUE']."'";
                        
                    } else if ($_REQUEST['filter'] == 'res_id') {
                        /*Permet de filtrer sur le res_id*/
                        if(is_numeric($_SESSION['filters']['res_id']['VALUE'])){
                            $_SESSION['filters']['res_id']['CLAUSE'] = "res_id = '".$_SESSION['filters']['res_id']['VALUE']."'";
                        }  
                    } else if ($_REQUEST['filter'] == 'subject') {
                        /*Permet de filtrer sur le l'objet du courrier et si la valeur saisi est un numérique va rechercher sur le res_id*/
                            $_SESSION['filters']['subject']['CLAUSE'] = "upper(subject) like '%".strtoupper($_SESSION['filters']['subject']['VALUE'])."%'"; 
                            if(is_numeric($_SESSION['filters']['subject']['VALUE'])){
                            $_SESSION['filters']['subject']['CLAUSE'] .= "or res_id = '".$_SESSION['filters']['subject']['VALUE']."'"; 
                            }
                    } else if ($_REQUEST['filter'] == 'isViewed') {                    
                        if ($_SESSION['filters']['isViewed']['VALUE'] == 'yes') {
                            $_SESSION['filters']['isViewed']['CLAUSE'] = "res_id in (select res_id from listinstance WHERE coll_id = '".$_SESSION['collection_id_choice']."' and item_type = 'user_id' and item_id = '".$_SESSION['user']['UserId']."' and item_mode = 'cc' and viewed > 0)";
                        } else  if ($_SESSION['filters']['isViewed']['VALUE'] == 'no') {
                            $_SESSION['filters']['isViewed']['CLAUSE'] = "res_id in (select res_id from listinstance WHERE coll_id = '".$_SESSION['collection_id_choice']."' and item_type = 'user_id' and item_id = '".$_SESSION['user']['UserId']."' and item_mode = 'cc' and viewed = 0 or viewed is null)";
                        }
                        
                    } else if ($_REQUEST['filter'] == 'user') {
                    
                        $userId = $this->protect_string_db(str_replace(')', '', 
                            substr($_SESSION['filters']['user']['VALUE'], 
                            strrpos($_SESSION['filters']['user']['VALUE'], '(') + 1)));
                        $_SESSION['filters']['user']['CLAUSE'] = $_SESSION['tablename']['users'].".user_id = '".$userId."'";
                        
                    } else if ($_REQUEST['filter'] == 'action') {
                    
                        $_SESSION['filters']['action']['CLAUSE'] = "event_type = '".$_SESSION['filters']['action']['VALUE']."'";
                         
                    } else if ($_REQUEST['filter'] == 'history_action') {
                    
                        $_SESSION['filters']['history_action']['CLAUSE'] = "event_type = '".$_SESSION['filters']['history_action']['VALUE']."'";
                         
                    } else if ($_REQUEST['filter'] == 'history_date_start' || $_REQUEST['filter'] == 'history_date_end') {
                    
                        //Pattern
                        $pattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
                        //Keep the date
                        $history_date = array();
                        //date start
                        if (preg_match($pattern, $_SESSION['filters']['history_date_start']['VALUE']) == false) {
                        
                            $_SESSION['error'] = _DATE.' '._WRONG_FORMAT;
                        } else {
                        
                            $history_date['start'] = "(date(event_date) >= '"
                                .$_SESSION['filters']['history_date_start']['VALUE']."')";
                        }
                        //date end
                        if (preg_match($pattern, $_SESSION['filters']['history_date_end']['VALUE']) == false) {
                        
                            $_SESSION['error'] = _DATE.' '._WRONG_FORMAT;
                        } else {
                        
                            $history_date['end'] = "(date(event_date) <= '"
                                .$_SESSION['filters']['history_date_end']['VALUE']."')";
                        }
                        
                        $_SESSION['filters']['history_date']['CLAUSE'] = join(' and ', $history_date);
                    } else if ($_REQUEST['filter'] == 'creation_date_start' || $_REQUEST['filter'] == 'creation_date_end') {
                    
                        //Pattern
                        $pattern = "/^[0-3][0-9]-[0-1][0-9]-[1-2][0-9][0-9][0-9]$/";
                        //Keep the date
                        $creation_date = array();
                        //date start
                        if (preg_match($pattern, $_SESSION['filters']['creation_date_start']['VALUE']) == false) {
                        
                            $_SESSION['error'] = _DATE.' '._WRONG_FORMAT;
                        } else {
                        
                            $creation_date['start'] = "(creation_date >= '"
                                .$_SESSION['filters']['creation_date_start']['VALUE']."')";
                        }
                        //date end
                        if (preg_match($pattern, $_SESSION['filters']['creation_date_end']['VALUE']) == false) {
                        
                            $_SESSION['error'] = _DATE.' '._WRONG_FORMAT;
                        } else {
                        
                            $creation_date['end'] = "(creation_date <= '"
                                .$_SESSION['filters']['creation_date_end']['VALUE']."')";
                        }
                        
                        $_SESSION['filters']['creation_date']['CLAUSE'] = join(' and ', $creation_date);
                    } else if($_REQUEST['filter'] == 'priority' && isset($_REQUEST['value']) &&  $_REQUEST['value'] != 0) {
                        $_SESSION['filters']['priority']['CLAUSE'] = "priority = '".$_REQUEST['value']."'";

                    }
                }
            } elseif($_REQUEST['filter'] == 'priority' && isset($_REQUEST['value']) &&  $_REQUEST['value'] == 0) {
                //j'ai créé cette condition pour le filtre des priorités parce que lorsque la valeur de $_request[value] est égale à 0, on ne rentre pas dans la condition et donc le filtre ne fonctionne pas lorsque c'est égale à 0.
                        $_SESSION['filters']['priority']['VALUE'] = '0';
                        $_SESSION['filters']['priority']['CLAUSE'] = "priority = '".$_REQUEST['value']."'";
            }
        }
    }
    
    protected function _resetUrlTemplates() {
    
        unset($_SESSION['url_templates']);
        $_SESSION['url_templates'] = array();
    }
    
    protected function _getTemplates() {
        $templates = '';
        
        //Check the selected template
        
        
        //Show the templates
        if (isset($this->params['templates']) && count($this->params['templates']) > 0) {
            
            //Default list (no template)
            if ($this->params['bool_showTemplateDefaultList'] === true) {
                
                //Put none in template array
                if (!in_array('none', $this->params['templates'])) array_unshift($this->params['templates'], 'none'); 
            } else {
                
                //Unset none from template array
                if (isset($this->params['templates']['none'])) unset($this->params['templates']['none']);
            }
             // $this->show_array($_SESSION['html_templates']);
           
            //Build templates icon and link        
            for($i=0; $i < count($this->params['templates']); $i++) {

                //Template is defined?
                if (isset($_SESSION['html_templates'][$this->params['templates'][$i]])) {
                    
                    //Selected template
                    if ($this->template == $_SESSION['html_templates'][$this->params['templates'][$i]]['ID']) {
                        $selected = 'color: #FBC110'; 
                    } else {
                        $selected = '';
                    }
                    
                    //Template link
                   if (empty($_SESSION['url_templates'][$this->params['templates'][$i]])) {
                   
                        if (!empty($_SESSION['html_templates'][$this->params['templates'][$i]]['GOTOLIST'])) {
                            //link from template parameters                           
                            $_SESSION['url_templates'][$this->params['templates'][$i]] =  
                                $_SESSION['html_templates'][$this->params['templates'][$i]]['GOTOLIST']
                                .'&template='.$_SESSION['html_templates'][$this->params['templates'][$i]]['ID'];
                        } else {
                            //
                            // $this->link = $this->_removeUrlVar($this->link, 'template');
                            $_SESSION['url_templates'][$this->params['templates'][$i]] =  
                                $this->link.'&template='.$_SESSION['html_templates'][$this->params['templates'][$i]]['ID'];

                        }
                    }

                    //Templates
                    $templates .= '<a href="javascript://" onClick="loadList(\''
                        . $_SESSION['url_templates'][$this->params['templates'][$i]].'\', \''
                        . $this->divListId.'\', '.$this->modeReturn.');" title="'
                        . $_SESSION['html_templates'][$this->params['templates'][$i]]['LABEL'].'">'
                        . '<i class="'
                        . $_SESSION['html_templates'][$this->params['templates'][$i]]['IMG'] . '" title="'
                        . $_SESSION['html_templates'][$this->params['templates'][$i]]['LABEL'] . '" '
                        . 'style="vertical-align: middle;' . $selected . '"></i></a>&nbsp;&nbsp;';
                }
            }
        }
        
        // $this->show_array($_SESSION['url_templates']);
        return $templates;
    }
    
    protected function _loadTemplate($templateFile) {
        $templateContent = '';
        
        //Get tge filecontent
        $templateContent = file_get_contents ($templateFile);
        
        //Delete all comments
        $templateContent = preg_replace("/(<!--.*?-->)/s","", $templateContent);

        return $templateContent;
    }
    
    protected function _tmplt_defineLang($parameter) {
        $my_explode= explode ("|", $parameter);

        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else  {
            if (defined($my_explode[1]) && constant($my_explode[1]) <> NULL)
                return constant($my_explode[1]);
            else
                return $my_explode[1];
        }
    }
    
    protected function _tmplt_sortColumn($parameter) {

        $my_explode= explode ("|", $parameter);
        
        if (!isset($my_explode[1])) {
            return  _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            $column = $my_explode[1];
            ($this->order == 'desc' && (strpos($this->orderField, $column) !== false))? $sortImgDown = 'mCdarkOrange' : $sortImgDown = 'mCdarkBlue';
            ($this->order == 'asc' && (strpos($this->orderField, $column) !== false))? $sortImgUp = 'mCdarkOrange' : $sortImgUp = 'mCdarkBlue';

            $return .= '<a href="javascript://" onClick="loadList(\''.$this->link
                        .'&order=desc&order_field='.$column.'\', \''.$this->divListId.'\', '.$this->modeReturn.');" title="'
                        ._DESC_SORT.'"><i class="fa fa-angle-down fa-2x ' . $sortImgDown . '" title="'._DESC_SORT.'"></i></a>';
            $return .= '<a href="javascript://" onClick="loadList(\''.$this->link
                        .'&order=asc&order_field='.$column.'\', \''.$this->divListId.'\', '.$this->modeReturn.');" title="'
                        ._ASC_SORT.'"><i class="fa fa-angle-up fa-2x ' . $sortImgUp . '" title="'._ASC_SORT.'"></i></a>';
        }
        return $return;
    }
    
    protected function _tmplt_cssLineReload() {
        //Get last css parameter defined for the result list
        return $this->tmplt_CurrentCssLine;
    }
    
    protected function _tmplt_cssLine($parameter) {
        $my_explode= explode ("|", $parameter);

        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            //Treat
            if (count($my_explode) == 2 ) {
                return $my_explode[1];
            } else if (count($my_explode) == 3) {
                if ($this->tmplt_CurrentCssLine == '')  {
                    $this->tmplt_CurrentCssLine = $my_explode[1];
                    return $this->tmplt_CurrentCssLine;
                } else if ($this->tmplt_CurrentCssLine == $my_explode[1]) {
                    $this->tmplt_CurrentCssLine = $my_explode[2];
                    return $this->tmplt_CurrentCssLine;
                } else if ($this->tmplt_CurrentCssLine == $my_explode[2]) {
                    $this->tmplt_CurrentCssLine = $my_explode[1];
                    return $this->tmplt_CurrentCssLine;
                } else {
                    return _WRONG_PARAM_FOR_LOAD_VALUE;
                }
            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }
    
    protected function _tmplt_loadImage($parameter) {
    
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) == 2 ) {
                return '<i class="fa fa-' . $my_explode[1] . '"></i>';
            } else if (count($my_explode) >= 3) {
                 return '<i class="fa fa-' . $my_explode[1] . '"></i>';
            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }
    
    protected function _tmplt_loadValue($parameter, $resultTheLine) {
        
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]){
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            
            $column = $my_explode[1];
            for($i= 0; $i <= count($resultTheLine); $i++ ) {
                if($resultTheLine[$i]['column'] == $column) {
                   
                    if(is_bool($resultTheLine[$i]['value'])) {
                        //If boolean (convert to string)
                        if ($resultTheLine[$i]['value']) 
                            return "true";
                        else
                            return "false";
                    } else {
                        // return $this->_highlightWords($resultTheLine[$i]['value'], $this->whatSearch); //highlight mode
                        return $resultTheLine[$i]['value'];
                    }
                }
            }
        }
    }
    
    protected function _tmplt_showSmallToolbar () {
        
        $this->params['bool_showSmallToolbar'] = true;
        
        return $this->_createSmallToolbar();
    }
    
    protected function _tmplt_checkBox($resultTheLine, $listKey, $lineIsDisabled) {
        
        //Get the ListKey value
        $keyValue = '';
        for($i= 0; $i <= count($resultTheLine); $i++ ) {
            if($resultTheLine[$i]['column'] == $listKey) {
                $keyValue = $resultTheLine[$i]['value'];
            }
        }
        
         //If checkbox is activated (is it important if template???)
        if ($this->params['bool_checkBox'] === true) {

            if($_SESSION['stockCheckbox'] != null){
                $key = in_array($keyValue, $_SESSION['stockCheckbox']);
            
            //If disable or checkbox
            if($key == true){            
                if ($lineIsDisabled === true || empty($keyValue)) {
                    $return .= '<div align="center"><i class="fa fa-lock fa-2x" title="'._LOCKED.'"></i></div>';
                } else {
                    $return .= '<div align="center"><input type="checkbox" checked="yes" name="field[]" id="field" class="check" onclick="stockCheckbox(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=multiLink\','.$keyValue.');" value="'
                                .$keyValue.'" /></div>';
                }
            }else{ 
                if ($lineIsDisabled === true || empty($keyValue)) {
                    $return .= '<div align="center"><i class="fa fa-lock fa-2x" title="'._LOCKED.'"></i></div>';
                } else {
                    $return .= '<div align="center"><input type="checkbox" name="field[]" id="field" class="check" onclick="stockCheckbox(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=multiLink\','.$keyValue.');" value="'
                                .$keyValue.'" /></div>';
                }
            }
                            }else{ 

                            //If disable or checkbox
            if ($lineIsDisabled === true || empty($keyValue)) {
                $return .= '<div align="center"><i class="fa fa-lock fa-2x" title="'._LOCKED.'"></i></div>';
            } else {
                $return .= '<div align="center"><input type="checkbox" name="field[]" id="field" class="check" onclick="stockCheckbox(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=multiLink\','.$keyValue.');" value="'
                            .$keyValue.'" /></div>';
            }



            }




        

        }
        return $return;
    }
    
    protected function _tmplt_checkUncheckAll() {
     
        //If checkbox is activated
        if ($this->params['bool_checkBox'] === true) {
        
            return '<input type="checkbox" id="checkUncheck" name="checkUncheck" value="" onclick="CheckUncheckAll(this);">';
        }
    }
    
    protected function _tmplt_radioButton($resultTheLine, $listKey, $lineIsDisabled) {
        
        $return = '';
        
        //Get the ListKey value
         $keyValue = '';
        for($i= 0; $i <= count($resultTheLine); $i++ ) {
            if($resultTheLine[$i]['column'] == $listKey) {
                $keyValue = $resultTheLine[$i]['value'];
            }
        }
        //If radio button is activated (is it important if template???)
        if ($this->params['bool_radioButton'] === true) {
            //If disable or radio button
            if ($lineIsDisabled === true || empty($keyValue)) {
                $return .= '<div align="center"><i class="fa fa-lock fa-2x" title="'._LOCKED.'"></i></div>';
            } else {
                $return .= '<div align="center"><input type="radio" name="field" id="field" class="check" value="'
                            .$keyValue.'" /></div>';
            }
        }    
        return $return;
    }
    
	protected function _tmplt_showIconProcessDocument($resultTheLine, $listKey) {
        
        $return = '';
        //Show document icon
        $href = $this->_buildMyLink('index.php?page=view_baskets&module=basket&baskets=MyBasket&directLinkToAction', $resultTheLine, $listKey);
        $return .= '<div align="center"><a href="'.$href.'" target="_blank" title="'
                ._PROCESS.'"><i class="fa fa-gear fa-2x" title="'._PROCESS.'"></i></a></div>';
           
        return $return;
    }
	
    protected function _tmplt_showIconDocument($resultTheLine, $listKey) {
        $core = new core_tools();
        $return = '';
        //Show document icon
		foreach($resultTheLine as $r){
			if (isset($r['res_id'])) {
				$res_id = $r['res_id'];
				break;
			}
		}
        $isAttachment = false;
        foreach($resultTheLine as $r){
            if ($r['column'] === 'attachment_type') {
                $isAttachment = true;
                break;
            }
        }
		
        $href = $this->_buildMyLink($this->params['viewDocumentLink'], $resultTheLine, $listKey);
		if ($core->is_module_loaded('thumbnails') === true && !$isAttachment){
			require_once "modules" . DIRECTORY_SEPARATOR . "thumbnails" . DIRECTORY_SEPARATOR
			. "class" . DIRECTORY_SEPARATOR
			. "class_modules_tools.php";
						
			$tnl = new thumbnails();
			$path = $tnl->getPathTnl($res_id, 'letterbox_coll');
            if (is_file($path)) {
                $return .= '<div align="center" class="iconDoc"><a href="'.$href.'" target="_blank" title="'
                    ._VIEW_DOC.'"><i class="fa fa-download fa-2x" title="' . _VIEW_DOC . '"></i><span><img src="index.php?page=doc_thumb&module=thumbnails&res_id='.$res_id.'&coll_id=letterbox_coll&display=true"></span></a></div>';
            
            }else{
                $return .= '<div align="center" class="iconDoc"><a href="'.$href.'" target="_blank" title="'
                    ._VIEW_DOC.'"><i class="fa fa-download fa-2x" title="' . _VIEW_DOC . '"></i><span id="no_doc"><i class="fa fa-ban" style="font-size: 460px;color: grey;opacity: 0.2;margin-top: 30px;"></i>
</span></a></div>';
            
            }
			
		}
		else $return .= '<div align="center" class="iconDoc"><a href="'.$href.'" target="_blank" title="'
					._VIEW_DOC.'"><i class="fa fa-download fa-2x" title="' . _VIEW_DOC . '"></i></a></div>';
           
        return $return;
    }
    
    protected function _tmplt_showIconDetails($resultTheLine, $listKey) {
        
        $return = '';
        //Show details button

       $href = $this->_buildMyLink($this->params['viewDetailsLink'], $resultTheLine, $listKey);
       $return .= '<div align="center"><a href="javascript://" onClick="javascript:window.top.location=\''
                .$href.'\'; return false;" title="'._DETAILS.'"><i class="fa fa-info-circle fa-2x" title="' . _DETAILS . '"></i></a></div>';

        
        return $return;
    }
    
    protected function _tmplt_showActionIcon($parameter, $resultTheLine) {
    
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) >= 4 ) {
                //Init
                $actionIsDisabled = false;
                
                //Check if action is disabled
                if (isset($my_explode[4]) && !empty($my_explode[4])) {
                    $actionIsDisabled = $this->_checkDisabledRules($my_explode[4], $resultTheLine);
                }
                //If disabled, return blank
                if ($actionIsDisabled) {
                    return '&nbsp;';
                } else {
                    //return action icon
                    return '<a href="javascript://" onClick="'.$my_explode[3]
                        .'" title="'.$my_explode[1]
                        .'">'.$my_explode[2].'</a>';
                }
            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }

    protected function _tmplt_showActionFA($parameter, $resultTheLine) {
    //var_dump($parameter);exit;
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) >= 4 ) {
                //Init
                $actionIsDisabled = false;
                
                //Check if action is disabled
                if (isset($my_explode[4]) && !empty($my_explode[4])) {
                    $actionIsDisabled = $this->_checkDisabledRules($my_explode[4], $resultTheLine);
                }
                //If disabled, return blank
                if ($actionIsDisabled) {
                    return '&nbsp;';
                } else {
                    //return action icon
                    return '<a href="javascript://" onClick="'.$my_explode[3]
                        .'" title="'.$my_explode[1].'"><i class="fa fa-' 
                        . $my_explode[2] . ' fa-2x" title="' . $my_explode[1] . '"></i></a>';
                }
            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }

    protected function _tmplt_showActionBasketsFA($parameter, $resultTheLine) {
        $my_explode= explode ("|", $parameter);

        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) >= 4 ) {
                //Init
                $actionIsDisabled = false;

                //Check if action is disabled
                if (isset($my_explode[4]) && !empty($my_explode[4])) {
                    $actionIsDisabled = $this->_checkDisabledRules($my_explode[4], $resultTheLine);
                }
                //If disabled, return blank
                if ($actionIsDisabled) {
                    return '&nbsp;';
                } else {
                    $db = new Database();
                    $stmt = $db->query("SELECT res_id_master FROM res_view_attachments WHERE status = 'EXP_RTURN' AND validation_date < CURRENT_TIMESTAMP AND res_id_master = ?",
                                        [$resultTheLine[0]['res_id']]);
                    $color = '';
                    if ($stmt->rowCount() >= 1) {
                        $color = 'style="color: red"';
                    }
                    //return action icon
                    return '<a href="javascript://" onClick="'.$my_explode[3]
                    .'" title="'.$my_explode[1].'"><i class="fa fa-'
                    . $my_explode[2] . ' fa-2x" '. $color .' title="' . $my_explode[1] . '"></i></a>';
                }
            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }

    protected function _tmplt_func_isConfidential($resultTheLine) {

            $db = new Database();
            $stmt = $db->query("SELECT confidentiality FROM res_view_letterbox WHERE res_id = ?",
                                [$resultTheLine[0]['res_id']]);
            $color = '';
            $result = $stmt->fetchObject();
            if ($result->confidentiality == 'Y') {
                return '<span style="position: absolute;transform: rotate(-20deg);color: red;-ms-transform: rotate(7deg);-webkit-transform: rotate(7deg);font-weight: bold;margin-left:-80px;margin-top:10px;">'.strtoupper (_CONFIDENTIAL).'</span>';
            }
        
    }

    protected function _tmplt_func_load_case_status($resultTheLine) {
            $db = new Database();
            $stmt = $db->query("SELECT count(*) as total FROM cases WHERE case_closing_date is not NULL and case_id = ?",
                                [$resultTheLine[0]['case_id']]);
            $result = $stmt->fetchObject();
            if($result->total > 0){
                return '<i class="fa fa-briefcase fa-2x" title="'._CLOSED.'"><sup> <i class="fa fa-lock" aria-hidden="true" style="color:red;font-size:10px;"></i></sup></i>';
            }else{
                return '<i class="fa fa-briefcase fa-2x"></i>';
            }
            var_dump($resultTheLine[0]);
        
    }

    protected function _tmplt_showActionAdvResultFA($parameter, $resultTheLine) {
        $my_explode= explode ("|", $parameter);

        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) >= 4 ) {
                //Init
                $actionIsDisabled = false;

                //Check if action is disabled
                if (isset($my_explode[4]) && !empty($my_explode[4])) {
                    $actionIsDisabled = $this->_checkDisabledRules($my_explode[4], $resultTheLine);
                }
                //If disabled, return blank
                if ($actionIsDisabled) {
                    return '&nbsp;';
                } else {
                    //return action icon
                    $color = '';
                    if (!empty($_SESSION['fullTextAttachments']['letterbox']) &&
                        in_array($resultTheLine[0]['res_id'], $_SESSION['fullTextAttachments']['letterbox'])) {
			             $color = 'style="color: #009dc5;"';
                    }
                    $db = new Database();
                    $stmt = $db->query(
                        "SELECT count(*) as total FROM res_view_attachments WHERE res_id_master = ? and status not in ('DEL','OBS','TMP') and lower(translate(title,'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ','aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr')) like lower(?)", array($resultTheLine[0]['res_id'],$_SESSION['searching']['where_request_parameters'][':subject'])
                    );
                    $res_attach = $stmt->fetchObject();
                    if($res_attach->total > 0){
                        $color = 'style="color: #009dc5;"';
                    }
                    return '<a href="javascript://" onClick="'.$my_explode[3]
                    .'" title="'.$my_explode[1].'"><i class="fa fa-'
                    . $my_explode[2] . ' fa-2x" '. $color .' title="' . $my_explode[1] . '"></i></a>';

                }

            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }
    
    protected function _tmplt_clickOnLine($resultTheLine, $listKey, $lineIsDisabled) {
        
        $return = '';
        
        //If there is action on line click
        if($this->params['bool_actionOnLineClick'] && 
            isset($this->params['defaultAction']) && !empty($this->params['defaultAction']) && 
            $lineIsDisabled === false
        ) {
            //Get the ListKey value
            $keyValue = '';
            for($i= 0; $i <= count($resultTheLine); $i++ ) {
                if($resultTheLine[$i]['column'] == $listKey) {
                    $keyValue = $resultTheLine[$i]['value'];
                }
            }
            $return = 'onmouseover="this.style.cursor=\'pointer\';" onClick="validForm( \'page\', \''
                    .$keyValue.'\', \''.$this->params['defaultAction'].'\');" ';
        }
        
        return $return;      
    }
       
    protected function _tmplt_includeFile($parameter) {

        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]){
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            //File
            $file = $my_explode[1];
            
            if (count($my_explode) == 3 && isset($my_explode[2]) && !empty($my_explode[2])) {
                $module = $my_explode[2];
                include "modules".DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$file;
            } else {
                include "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$file;
            }
        }
    }
    
    protected function _tmplt_getBusinessAppUrl() {
    
        return $_SESSION['config']['businessappurl'];
    }
    
    protected function _tmplt_getListParameter($parameter) {
    
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {

            return  $this->params[$my_explode[1]];
        }
    }
    
    protected function _tmplt_setListParameter($parameter) {
    
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) == 3) {
                if ($my_explode[2] == 'true') {
                    $this->params[$my_explode[1]] = true;
                } else if ($my_explode[2] == 'false') {
                    $this->params[$my_explode[1]] = false;
                } else {
                    $this->params[$my_explode[1]] = $my_explode[2];
                }
            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }

    protected function _tmplt_ifStatement($parameter) {
    
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            if (count($my_explode) >= 4) {
                
                $condition = "return($my_explode[1]);";
                // $debug .='condition: '.$condition."<br>";
                if(@eval($condition)) {
                    return $my_explode[2];
                } else {
                    return $my_explode[3];
                }

            } else {
                return _WRONG_PARAM_FOR_LOAD_VALUE;
            }
        }
    }
    
    protected function _tmplt_isModuleLoaded($parameter) {
    
        $my_explode= explode ("|", $parameter);
        
        if (!$my_explode[1]) {
            return _WRONG_PARAM_FOR_LOAD_VALUE;
        } else {
            $core_tools = new core_tools();

            if($core_tools->is_module_loaded($my_explode[1]) === true) {
                return "true";
            } else {
                return "false";
            }
        }
    }

	public function tmplt_func_bool_see_notes($resultTheLine)
    {
        $return = '';
        if ($resultTheLine[0]['hasNotes'] || $resultTheLine[1]['hasNotes']) {
            $return .= '<i class="fa fa-pencil fa-2x" style="cursor: pointer;" title="' . _NOTES . '" onclick="loadNoteList(' . $resultTheLine[0]['value'] . ');"></i>';
        }
        return $return;
    }

    public function tmplt_func_delete($resultTheLine)
    {
        $return = "";

        $nbresult_I = count($resultTheLine);
        for($iresults=0;$iresults<$nbresult_I;$iresults++){
            if($resultTheLine[$iresults]['typist_id']){
                $typist = $resultTheLine[$iresults]['typist_id'];
            }
            if($resultTheLine[$iresults]['fromDetail']){
                $fromDetail = $resultTheLine[$iresults]['fromDetail'];
            }
        }

        $core_tools = new core_tools();
        if ($core_tools->test_service('delete_attachments', 'attachments', false) || $typist == $_SESSION['user']['UserId']) {
            $return = '<a href="' . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                            . '&module=attachments&page=del_attachment&relation=' . $resultTheLine[1]['value'] . '&id=' . $resultTheLine[0]['value'].'&fromDetail='.$fromDetail.'" class="delete"'
                            . 'onclick="return(confirm(\'' . _REALLY_DELETE . ' ?\n\r\n\r'
                            . _DEFINITIVE_ACTION . '\'));"><i class="fa fa-trash-o fa-lg" title="'._DELETE.'"></i></a>';
        }else{
            $return = '<a href="" class="delete" style="visibility:hidden;"><i class="fa fa-trash-o fa-lg" title="'._DELETE.'"></i></a>';
        }
        return $return;
    }

    public function tmplt_func_modify($resultTheLine)
    {
        $return = "";
        $nbresult_I = count($resultTheLine);
        for($iresults=0;$iresults<$nbresult_I;$iresults++){
            if($resultTheLine[$iresults]['typist_id']){
                $typist = $resultTheLine[$iresults]['typist_id'];
            }
            if($resultTheLine[$iresults]['relation']){
                $relation = $resultTheLine[$iresults]['relation'];
            }
            if($resultTheLine[$iresults]['fromDetail']){
                $fromDetail = $resultTheLine[$iresults]['fromDetail'];
            }
            if($resultTheLine[$iresults]['value_bis']){
                $status = $resultTheLine[$iresults]['value_bis'];
            }
        }

        $core_tools = new core_tools();
        if (($core_tools->test_service('modify_attachments', 'attachments', false) || $typist == $_SESSION['user']['UserId']) && $status <> "TRA") {
            $return = '<a href="javascript://" class="change" onclick="modifyAttachmentsForm(\'' . $_SESSION['config']['businessappurl']
                            . 'index.php?display=true&module=attachments&page=attachments_content&id=' . $resultTheLine[0]['value'] . '&relation='.$relation.'&fromDetail='.$fromDetail.'\',\'98%\',\'auto\');">
                                <i class="fa fa-pencil fa-lg" title="'._MODIFY.'"></i></a>';
        }else{
            $return = '<a href="javascript://" disabled="disabled" class="change" style="visibility:hidden;">
                                <i class="fa fa-pencil fa-lg" title="'._MODIFY.'"></i></a>';
        }

        return $return;
    }

    public function tmplt_func_previous_version($resultTheLine)
    {
        $return = "";
        if ((int)$resultTheLine[1]['value'] > 1) {
            $return .= '<i class="fa fa-file" onclick="showPreviousAttachments(\'' . $_SESSION['config']['businessappurl']
                . 'index.php?display=true&module=attachments&page=previous_attachments\',\''
                . $resultTheLine[0]['value'] . '\');" style="cursor: pointer;" title="'._SHOW_PREVIOUS_VERSION.'" ></i>';
        }

        return $return;
    }

    public function tmplt_func_final_version($resultTheLine)
    {
        $nbresult_I = count($resultTheLine);
        for($iresults=0;$iresults<$nbresult_I;$iresults++){
            if($resultTheLine[$iresults]['relation']){
                $relation = $resultTheLine[$iresults]['relation'];
            }
            if($resultTheLine[$iresults]['fromDetail']){
                $fromDetail = $resultTheLine[$iresults]['fromDetail'];
            }
        }
        $return = '<input type="checkbox" name="final" id="final" ';

        if ($resultTheLine[2]['value_bis'] == "TRA") {
            $return .= 'checked ';
        }

        $return .= 'onclick="setFinalVersion(\'' . $_SESSION['config']['businessappurl']
                        . 'index.php?display=true&module=attachments&page=setFinalVersion&relation='.$resultTheLine[1]['value'].'&id=' . $resultTheLine[0]['value'] . '&relation='.$relation.'&fromDetail='.$fromDetail.'\');"/>'
                            . _FINAL_VERSION;
        return $return;
    }
	
	public function tmplt_func_bool_see_multi_contacts($resultTheLine)
    {
        $return = '';
		$nbresult_I = count($resultTheLine);

		for($iresults=0;$iresults<$nbresult_I;$iresults++){
			if($resultTheLine[$iresults]['is_multi_contacts']){
				$isMultiContacts = $resultTheLine[$iresults]['is_multi_contacts'];
			}				
			if($resultTheLine[$iresults]['res_multi_contacts']){
				$resMultiContacts = $resultTheLine[$iresults]['res_multi_contacts'];
			}
		}
		
        if ($isMultiContacts == 'Y') {
            $return .= '<i class="fa fa-book fa-2x" style="cursor: pointer;" title="' 
                . _VIEW_CONTACTS . '"onclick="loadContactsList(' . $resMultiContacts . ');" ></i>';
        }

        return $return;
    }

    public function tmplt_func_cadenas($parameter)
    {
        $my_explode= explode ("|", $parameter);
        $now = date("Y-m-d H:i:s"); 
        $my_explode[2] = str_replace("'","",$my_explode[2]);
        $my_explode[1] = str_replace("'","",$my_explode[1]);

        if (!isset($my_explode[2])) {
            return '';
        } else if ($my_explode[2] == null || $my_explode[2] == '' || empty($my_explode[2])) {
            return '';
        } else if ($my_explode[1] == $_SESSION['user']['UserId'] ) {
            return '';
        } else if ($my_explode[2] > $now ) {
            return '<i class="fa fa-lock fa-2x"></i>';
        } else {
            return '';
        }
    }

    public function tmplt_showDefaultAction($parameter) 
    {
        $db = new Database();
        if($_SESSION['current_basket']['default_action'] != ""){
        //Load action name   
        $stmt = $db->query(
            "SELECT label_action FROM actions WHERE id = ?", array($_SESSION['current_basket']['default_action'])
        );
        $res = $stmt->fetchObject();

        return $res->label_action;
        }
    }
    
    public function tmplt_nbNoteAvis($parameter) 
    {
        $my_explode = explode ("|", $parameter);
        $res_id = str_replace("'","",$my_explode[1]);
        $db = new Database();
        //Load action name   
        $stmt = $db->query(
            "SELECT count(*) as total FROM notes WHERE identifier = ? and note_text like '[avis%'", array($res_id)
        );
        $note = $stmt->fetchObject();

        return $note->total;
    }

    protected function _tmplt_loadVarSys($parameter, $resultTheLine=array(), $listKey='', $lineIsDisabled=false) {
        ##loadValue|arg1##: load value in the db; arg1= column's value identifier
        if (preg_match("/^loadValue\|/", $parameter)){
            $var = $this->_tmplt_loadValue($parameter, $resultTheLine);
        ##sortColumn|arg1## : cretate sort in header; arg1 = name of the column
        } else if (preg_match("/^sortColumn\|/", $parameter)) {
            $var = $this->_tmplt_sortColumn($parameter);
        ##defineLang|arg1## : define constant by the lang file; arg1 = constant of lang.php
        } else if (preg_match("/^defineLang\|/", $parameter)){
            $var = $this->_tmplt_defineLang($parameter);
        ##cssLineReload## : reload css style for next line
        } else if (preg_match("/^cssLineReload$/", $parameter)) {
            $var = $this->_tmplt_cssLineReload($parameter);
        ##cssLine|coll|nonecoll## : load css style for line arg1,arg2 : switch beetwin style on line one or line two
        } else if (preg_match("/^cssLine\|/", $parameter)) {
            $var = $this->_tmplt_cssLine($parameter);
        ##loadImage|arg1|arg2## :load image; arg1= image name, arg2 = module name (if image in module)
        } else if (preg_match("/^loadImage\|/", $parameter)) {
            $var = $this->_tmplt_loadImage($parameter);
        ##showSmallToolbar: swhow small bar for navigation
        } else if (preg_match("/^showSmallToolbar$/", $parameter)) {
            $var = $this->_tmplt_showSmallToolbar();
        ##checkBox## : show checkbox
        } elseif (preg_match("/^checkBox$/", $parameter)) {
            $var = $this->_tmplt_checkBox($resultTheLine, $listKey, $lineIsDisabled);
        ##checkUncheckAll## : show checkbox check All /uncheck All
        } elseif (preg_match("/^checkUncheckAll$/", $parameter)) {
            $var = $this->_tmplt_checkUncheckAll();
        ##radioButton## : show radio button
        } elseif (preg_match("/^radioButton$/", $parameter)) {
            $var = $this->_tmplt_radioButton($resultTheLine, $listKey, $lineIsDisabled);
        ##showIconProcessDocument## : show process document icon and link
        } elseif (preg_match("/^showIconProcessDocument$/", $parameter)) {
            $var = $this->_tmplt_showIconProcessDocument($resultTheLine, $listKey);
		##showIconDocument## : show document icon and link
        } elseif (preg_match("/^showIconDocument$/", $parameter)) {
            $var = $this->_tmplt_showIconDocument($resultTheLine, $listKey);
        ##showIconDetails## : show details icon and link
        } elseif (preg_match("/^showIconDetails$/", $parameter)) {
            $var = $this->_tmplt_showIconDetails($resultTheLine, $listKey);
        } elseif (preg_match("/^showActionIcon\|/", $parameter)) {
            $var = $this->_tmplt_showActionIcon($parameter, $resultTheLine);
        ##showActionFA## : show action Font Awesome
        } elseif (preg_match("/^showActionFA\|/", $parameter)) {
            $var = $this->_tmplt_showActionFA($parameter, $resultTheLine);
        ##showActionBasketsFA## : show action Font Awesome
        } elseif (preg_match("/^showActionBasketsFA\|/", $parameter)) {
            $var = $this->_tmplt_showActionBasketsFA($parameter, $resultTheLine);
        ##showActionAdvResultFA## : show action Font Awesome
        } elseif (preg_match("/^showActionAdvResultFA\|/", $parameter)) {
            $var = $this->_tmplt_showActionAdvResultFA($parameter, $resultTheLine);
        ##clickOnLine## : Action on click under the line
        } elseif (preg_match("/^clickOnLine$/", $parameter)) {
            $var = $this->_tmplt_clickOnLine($resultTheLine, $listKey, $lineIsDisabled);
        ##includeFile## : Action on click under the line
        } elseif (preg_match("/^includeFile\|/", $parameter)) {
            $var = $this->_tmplt_includeFile($parameter);
        ##getBusinessAppUrl## : Action on click under the line
        } elseif (preg_match("/^getBusinessAppUrl$/", $parameter)) {
            $var = $this->_tmplt_getBusinessAppUrl();
        ##getListParameter## : 
        } elseif (preg_match("/^getListParameter\|/", $parameter)) {
            $var = $this->_tmplt_getListParameter($parameter);
        ##setListParameter## : 
        } elseif (preg_match("/^setListParameter\|/", $parameter)) {
            $var = $this->_tmplt_setListParameter($parameter);  
        ##isModuleLoaded## : 
        } elseif (preg_match("/^isModuleLoaded\|/", $parameter)) {
            $var = $this->_tmplt_isModuleLoaded($parameter);    
        ##ifStatement## : 
        } elseif (preg_match("/^ifStatement\|/", $parameter)) {
            $var = $this->_tmplt_ifStatement($parameter);   
        } elseif (preg_match("/^func_bool_see_multi_contacts$/", $parameter)){
            $var = $this->tmplt_func_bool_see_multi_contacts($resultTheLine);
        } elseif (preg_match("/^func_delete$/", $parameter)){
            $var = $this->tmplt_func_delete($resultTheLine);
        } elseif (preg_match("/^func_modify$/", $parameter)){
            $var = $this->tmplt_func_modify($resultTheLine);
        } elseif (preg_match("/^func_final_version$/", $parameter)){
            $var = $this->tmplt_func_final_version($resultTheLine);
        } elseif (preg_match("/^func_previous_version$/", $parameter)){
            $var = $this->tmplt_func_previous_version($resultTheLine);
        } elseif (preg_match("/^func_bool_see_notes$/", $parameter)){
            $var = $this->tmplt_func_bool_see_notes($resultTheLine);
        } elseif (preg_match("/^func_cadenas\|/", $parameter)){
            $var = $this->tmplt_func_cadenas($parameter);
        } elseif (preg_match("/^showDefaultAction$/", $parameter)){
            $var = $this->tmplt_showDefaultAction($parameter);
        }elseif (preg_match("/^nbNoteAvis\|/", $parameter)){
            $var = $this->tmplt_nbNoteAvis($parameter);
        } elseif (preg_match("/^func_isConfidential$/", $parameter)) {
            $var = $this->_tmplt_func_isConfidential($resultTheLine);
        ##showActionIcon## : show action icon
        } elseif (preg_match("/^func_load_case_status$/", $parameter)) {
            $var = $this->_tmplt_func_load_case_status($resultTheLine);
        } else {
            $var = _WRONG_FUNCTION_OR_WRONG_PARAMETERS;
        }
        return $var;
    }

    
    protected function _buildTemplate($templateFile, $resultArray, $listKey) {
        
        if (file_exists('custom/' . $_SESSION['custom_override_id']  . '/' . $templateFile)) {
            $templateFile = 'custom/' . $_SESSION['custom_override_id']  . '/' . $templateFile;
        }
        //Check if template file exists
        if (file_exists($templateFile)) {
            
            
            //Load template file
            $templateContent = $this->_loadTemplate($templateFile);

            //Explode template
            $templateContentArray = explode("#!#", $templateContent);
            
            //Get value from template
            foreach($templateContentArray as $templateSection) {
                    
                if (substr($templateSection , 0, 5) == "TABLE") {
                    //Get table string
                    $table = substr($templateSection, 5);
                    $trueTable = $table;
                    preg_match_all('/##(.*?)##/', $trueTable, $output);
                    
                    //Replace functions by values
                    for($i=0;$i<count($output[0]);$i++) {
                        $remplacementTable = $this->_tmplt_loadVarSys($output[1][$i]);
                        $table = str_replace($output[0][$i],$remplacementTable, $trueTable);
                    }
                } elseif (substr($templateSection , 0, 4) == "HEAD") {
                    //Get head string
                    $head = substr($templateSection, 4);
                    $trueHead = $head;
                    preg_match_all('/##(.*?)##/', $trueHead, $output);

                    for($i=0;$i<count($output[0]);$i++) {
                    
                        //If template function is called under template function
                        $_trueHead = $output[1][$i];
                        preg_match_all('/#(.*?)#/', $_trueHead, $_output);
                        for($j=0;$j<count($_output[0]);$j++) {
                            // $debug .='--> '.$_output[0][$j].'<br>';
                            $_remplacementHead = $this->_tmplt_loadVarSys($_output[1][$j]);
                            // $debug .='---> '.$_remplacementHead.'<br>';
                            $_trueHead = str_replace($_output[0][$j],$_remplacementHead,$_trueHead);
                            
                        }
                        $output[1][$i] = $_trueHead;
                    
                        $remplacementHead = $this->_tmplt_loadVarSys($output[1][$i]);
                        $trueHead = str_replace($output[0][$i], $remplacementHead, $trueHead);
                    }
                    $head = $trueHead;
                } else if (substr($templateSection , 0, 6) == "RESULT") {
                    //Get rows content
                    $content = substr($templateSection, 6);
                } elseif (substr($templateSection , 0, 6) == "FOOTER") {
                    //Get footer string
                    $footer = substr($templateSection, 6);
                }
            }
            
            $rowsContent = '';
            //Loop into the set of records
            for($theLine = $this->start; $theLine < $this->end ; $theLine++) {
               
                //Check if line is disable
                $lineIsDisabled = $this->_checkDisabledRules($this->params['disabledRules'], $resultArray[$theLine]);
               
                //Treat content
                $trueContent = $content;
                 
                preg_match_all('/##(.*?)##/', $trueContent, $output);
                
                for($i=0;$i<count($output[0]);$i++) {
                
                    // echo '-> '.$output[1][$i].'<br>';
                    $_trueContent = '';
                    
                    //If template function is called under template function
                    $_trueContent = $output[1][$i];
                    
                    preg_match_all('/#(.*?)#/', $_trueContent, $_output);
                    
                    for($j=0;$j<count($_output[0]);$j++) {
                        // echo '--> '.$_output[0][$j].'<br>';
                        $_remplacement = $this->_tmplt_loadVarSys($_output[1][$j], $resultArray[$theLine], $listKey, $lineIsDisabled);
                        // echo '---> '.$_remplacement.'<br>';
                        $_trueContent = str_replace($_output[0][$j],$_remplacement,$_trueContent);
                    }
                    $output[1][$i] = $_trueContent;
                    
                    // echo '<- '.$output[1][$i].'<br><br>';

                    $remplacement = $this->_tmplt_loadVarSys($output[1][$i], $resultArray[$theLine], $listKey, $lineIsDisabled);
                    $trueContent = str_replace($output[0][$i],$remplacement,$trueContent);
                    
                }
                
                $rowsContent .= $trueContent;
            }
            
            $buildedTemplate =   $table.$head.$rowsContent.$footer;
            
            //Fix some json line breaks issues
            $buildedTemplate = str_replace(chr(10), "", $buildedTemplate);
            $buildedTemplate = str_replace(chr(13), "", $buildedTemplate);
            
            return $buildedTemplate;
            
        } else {
        
           return _NO_TEMPLATE_FILE_AVAILABLE. ': '.$templateFile;
        }
    }
     
    protected function _highlightWords($input, $keyword, $maxLength=30, $minLength=5) {
        
        $output = $input;
        
        if(strlen(trim($keyword)) < $maxLength && strlen(trim($keyword)) > $minLength ) {
        
            // $output = preg_replace("/(>|^)([^<]+)(?=<|$)/esx", "'\\1' . str_replace('" . $keyword . "', '<span class=\"highlighted\">" . $keyword . "</span>', '\\2')", $input);
            // $output = preg_replace("/(?<!\[)(\b{$keyword}\b)(?!\])/i", '<span class="highlighted">\\1</span>', $input);
            $keywordArray = explode(" ", $keyword);
            for($i = 0; $i < count($keywordArray); $i++) {
                $save_keywordArray = "";
                $pos = stripos($input, $keywordArray[$i]);
                
                if($pos !== false) {
                    $save_keywordArray = substr($input, $pos, strlen($keywordArray[$i]));
                }
                $output = preg_replace("/(".$keywordArray[$i].")/i","<span class=\"highlighted\">".$save_keywordArray."</span>",$input);
            }
        }
        return $output;
    }
    
    protected function _buildMyLink($link, $resultTheLine, $listKey='') {    
    
        //If you want to use different key for action link
        if (strpos($link, "@@") !== false) {
            foreach(array_keys($resultTheLine) as $column) { // for every column
                $key = "@@".$resultTheLine[$column]['column']."@@"; //build the alias
                $val = $resultTheLine[$column]['value']; //get the real value
                $link = str_replace($key, $val, $link); //replace alias by real value
            }
        }
        
        //Use standard id (based on list key)
        if (!empty($listKey)) {
            //Get the ListKey value
            $keyValue = '';
            for($i= 0; $i <= count($resultTheLine); $i++ ) {
                if($resultTheLine[$i]['column'] == $listKey) {
                    $keyValue = $resultTheLine[$i]['value'];
                }
            }
            $link .= "&id=".$keyValue;
        }
        
        return $link;
    }
    
    protected function _removeUrlVar($url, $varName) {

        $url = html_entity_decode($url);
        $urlArray  = parse_url($url);
        parse_str($urlArray['query'], $output);
        unset($output[$varName]);
        $urlVar = http_build_query($output);
         
        return  strtok($url, '?') . '?' . $urlVar;
    }

    protected function _buildPageLink() {
        //Get page and module from REQUEST
        if (!isset($this->params['pageName']) || empty($this->params['pageName'])) $this->params['pageName'] = $_REQUEST['page'];
        if (!isset($this->params['moduleName']) || empty($this->params['moduleName'])) $this->params['moduleName'] = $_REQUEST['module'];
        
        //Url parameters
        if (isset($this->params['urlParameters'])) {
            $pos = strpos($this->params['urlParameters'], '&');
            //if my urlParameters string have '&'
            if ($pos !== false) {
                //at the firt position
                if ($pos <> 0) {
                    //And page is called by index page
                    if ($this->params['bool_pageInModule']) {
                        //Add '&' 
                        $this->params['urlParameters'] = '&'.$this->params['urlParameters'];
                    }
                }
            } else {//my urlParameters string dont have '&' at all
                //And page is called by index page
                if ($this->params['bool_pageInModule']) {
                    //Add '&' 
                    $this->params['urlParameters'] = '&'.$this->params['urlParameters'];
                }
            }               
        }
        
        //Page pageName
        if (isset($this->params['pageName'])){
            if ($this->params['bool_pageInModule'] && isset($this->params['moduleName'])) { //If page is called in a module by index page
                $link = $_SESSION['config']['businessappurl'].'index.php?page='.$this->params['pageName']."&module="
                    .$this->params['moduleName'].$this->params['urlParameters'];
            } elseif(isset($this->params['moduleName']) && !$this->params['bool_pageInModule']) { //Else if page is called inside the module
                $link = $_SESSION['urltomodules'].$this->params['moduleName']."/".$this->params['pageName'].".php?".$this->params['urlParameters'];
            } else {
                $link = $_SESSION['config']['businessappurl'].'index.php?page='.$this->params['pageName'].$this->params['urlParameters'];
            }
        } else { //Default link (anchor) to prevent error in link if no pageName or module name
            $link = "#";
        }
        
        //String searched in list
        if(!empty($this->whatSearch)) {
            $link = $this->_removeUrlVar($link,'what');
            $link.= '&what='.$this->whatSearch;
        }
        
        //Column order
        if(!empty($_REQUEST['order']) && !empty($_REQUEST['order_field'])) {
            //Remove some url parameters
            $link = $this->_removeUrlVar($link,'order');
            $link = $this->_removeUrlVar($link,'order_field');
            //Init
            $this->order = $_REQUEST['order'];
            $this->orderField = $_REQUEST['order_field'];
        } 
        
        //Template
        if(isset($_REQUEST['template'])) {
            //Remove some url parameters
            $link = $this->_removeUrlVar($link,'template');
            $this->template =  $_REQUEST['template'];
            $link.= '&template='.$_REQUEST['template'];
            $_SESSION['save_list']['template'] = $_REQUEST['template'];
        }
        
        //Id (used in sublist)
        if(isset($_REQUEST['id'])) {
            $link = $this->_removeUrlVar($link,'id');
            $link.= '&id='.$_REQUEST['id'];
        }
        
        //Number of lines to show
        if(isset($_REQUEST['lines']) && !empty($_REQUEST['lines'])) {
            $link = $this->_removeUrlVar($link, 'lines');
            $link.= '&lines='.$_REQUEST['lines'];
        }
        
        //Display = true
        if (isset($_REQUEST['display']) && !empty($_REQUEST['display'])) {
            $link = $this->_removeUrlVar($link,'display');
            $link.= '&display=true';
        }
        
        return $link;
    }
    
    protected function _checkDisabledRules($disabledRules, $resultTheLine=array()) {
        $disabled = false;
        
        if (!empty($disabledRules)) {
        
            if(is_array($resultTheLine) && count($resultTheLine) > 0) {
                foreach(array_keys($resultTheLine) as $column) { // for every column
                    $key = "@@".$resultTheLine[$column]['column']."@@"; //build the alias
                    $val =  "'".$resultTheLine[$column]['value']."'"; //get the real value with simple quotes
                    $disabledRules = str_replace($key, $val, $disabledRules); //replace alias by real value
                }
            }
            //Eval disabled rule
            if (!empty($disabledRules)) {
                $rules = "return($disabledRules);";
                //echo $rules."<br>\n";
                if(@eval($rules)) {
                    $disabled = true;
                }
                //var_dump($disabled);
            }
        }
        return $disabled;
    }
    
    protected function _createHeader($resultFirstRow, $listColumn, $showColumn, $sortColumn) {
        $count_td = 0;

        $column = '<tr>';

        //If sublist
        if($this->params['bool_showSublist'] && !empty($this->params['sublistUrl'])){
            $column .= '<th width="1%">&nbsp;</th>';
            $count_td ++;
        }
        
        //If checkbox 
        if( $this->params['bool_checkBox'] === true) {
            $column .= '<th width="1%" alt="' . _CHECK_ALL 
                    . ' / ' . _UNCHECK_ALL 
                    . '"><div align="center"><input type="checkbox" '
                    . 'id="checkUncheck" name="checkUncheck" value="" onclick="CheckUncheckAll(this);"></div></th>';
            $count_td ++;
        //If radio button
        } else if( $this->params['bool_radioButton'] === true) {
            $column .= '<th width="1%">&nbsp;</th>';
            $count_td ++;
        }

        //If view document
        if($this->params['bool_showIconDocument']) {
            $column .= '<th width="1%">&nbsp;</th>';
            $count_td ++;
        }

        //Print column header
        for($actualColumn = 0;$actualColumn < count($listColumn);$actualColumn++) {
            //Show column
            if($showColumn[$actualColumn] === true) {
            
                //Different background on ordered column
                (strpos($this->orderField, $sortColumn[$actualColumn]) !== false)? 
                    $columnStyle = ' style="background-image: url(static.php?filename=black_0.1.png);"' : $columnStyle = '';
                
                //column
                $column .= '<th'.$columnStyle.' width="'.$resultFirstRow[$actualColumn]['size']
                        .'%" valign="'.$resultFirstRow[$actualColumn]['valign']
                        .'"><div align="'.$resultFirstRow[$actualColumn]['label_align'].'">'
                        .$listColumn[$actualColumn]; 
                
                //Show sort icon
                if($this->params['bool_sortColumn']) {
                    if( $sortColumn[$actualColumn] !== false) {
                        //Change color of sort icon
                        ($this->order == 'desc' && (strpos($this->orderField, $sortColumn[$actualColumn]) !== false))? 
                            $sortImgDown = 'mCdarkOrange' : $sortImgDown = 'mCdarkBlue';
                        ($this->order == 'asc' && (strpos($this->orderField, $sortColumn[$actualColumn]) !== false))? 
                            $sortImgUp = 'mCdarkOrange' : $sortImgUp = 'mCdarkBlue';
                        $column .= '<br/><br/>';

                        //Build header
                        $column .= '<a href="javascript://" onClick="loadList(\''.$this->link
                        .'&order=desc&order_field='.$sortColumn[$actualColumn].'\', \''
                        .$this->divListId.'\', '.$this->modeReturn.');" title="'
                        ._DESC_SORT.'"><i class="fa fa-angle-down fa-2x ' 
                        . $sortImgDown . '" title="'._DESC_SORT.'"></i></a>';
                        $column .= '<a href="javascript://" onClick="loadList(\''.$this->link
                        .'&order=asc&order_field='.$sortColumn[$actualColumn].'\', \''
                        .$this->divListId.'\', '.$this->modeReturn.');" title="'
                        ._ASC_SORT.'"><i class="fa fa-angle-up fa-2x ' 
                        . $sortImgUp . '" title="'._ASC_SORT.'"></i></a>';
                    }
                }
                $column .= '</div></th>';
                
                $count_td ++;
            }
        }

        //Reserve space for action buttons 
        for($i = 0;$i < count($this->actionButtons);$i++) {
            $column .= '<th width="1%" valign="bottom">&nbsp;</th>';
            $count_td ++;
        }

        //Reserve space for details button
        if($this->params['bool_showIconDetails']) {
            $column .= '<th width="1%" valign="bottom">&nbsp;</th>';
            $count_td ++;
        }
        
        $column .= '</tr>';
        
        //Count the number of columns
        $this->countTd = $count_td;
        
        //Small toolbar
        $toolbar = '';
        if($this->params['bool_showSmallToolbar']){
            $toolbar = '<tr><th style="padding:0px;" colspan="'.$this->countTd.'">';
            $toolbar .= $this->_createSmallToolbar();
            $toolbar .= '</th></tr>';
        } 
        
        //Add button
        $addButton = $footer = '';
        if($this->params['bool_showAddButton']) {
            $addButton = $this->_displayAddButton(); 
            $footer = '<tfoot>'.$addButton.'</tfoot>';
        }
        
        //Header
        $header = '<thead>'.$toolbar.$column.'</thead>'.$footer;
        
        return $header;
    }
    
    protected function _getTools($resultFirstRow, $countResult) {
    
        //ADD ALWAYS VISISBLE PARAMETERS
        $tools = $urlParameters = '';
        if (isset($this->params['tools']) && count($this->params['tools']) > 0) {

            for($i=0; $i < count($this->params['tools']); $i++) {
            
                if (!isset($this->params['tools'][$i]['alwaysVisible'])) $this->params['tools'][$i]['alwaysVisible'] = false;
                
                $toolIsDisabled = $this->_checkDisabledRules($this->params['tools'][$i]['disabledRules']);
                
                if (($toolIsDisabled === false && $countResult >0) || $this->params['tools'][$i]['alwaysVisible'] === true) {
                
                    if(isset($this->params['tools'][$i]['script']) && !empty($this->params['tools'][$i]['script'])) {
  
                        $script = $this->_buildMyLink($this->params['tools'][$i]['script'], $resultFirstRow);
                        $tools .= '<a href="javascript://" onClick="'.$script
                                .'" title="'.$this->params['tools'][$i]['tooltip'].'">';
                    } else {
                        //Url parameters
                        if (isset($this->params['tools'][$i]['urlParameters'])) {
                            $pos = strpos($this->params['tools'][$i]['urlParameters'], '&');
                            //if my urlParameters string have '&'
                            if ($pos !== false) {
                                //at the firt position
                                if ($pos <> 0) {
                                    //Add '&' 
                                     $this->params['tools'][$i]['urlParameters'] = '&'.$this->params['urlParameters'];
                                }
                            }
                            $urlParameters =  $this->params['tools'][$i]['urlParameters'];
                        }
                        //Href
                        if(isset($this->params['tools'][$i]['href']) && !empty($this->params['tools'][$i]['href'])) {
                            $href = $this->params['tools'][$i]['href'];
                        } else {
                             $href = $this->link.$urlParameters;
                        }
                        // If javascript alert box
                        if(isset($this->params['tools'][$i]['alertText']) && !empty($this->params['tools'][$i]['alertText'])) {
                            $tools .= '<a href="javascript://" onClick="if(confirm(\''
                                    .addslashes($this->params['tools'][$i]['alertText']).'\')) loadList(\''
                                    .$href.'\', \''.$this->divListId.'\', '.
                                    $this->modeReturn.');" title="'.$this->params['tools'][$i]['tooltip'].'">';
                        } else {
                            $tools .= '<a href="javascript://" onClick="loadList(\''
                                .$href.'\', \''.$this->divListId.'\', '.$this->modeReturn.');" title="'
                                .$this->params['tools'][$i]['tooltip'].'">';
                        }
                    }              
                    //Image
                    if(isset($this->params['tools'][$i]['icon'])) {
                        $tools .= '<i class="fa fa-' . $this->params['tools'][$i]['icon'] . ' fa-2x" title="' . $this->params['tools'][$i]['tooltip'] . '"></i>&nbsp;'; 
                    } else {
                        $tools .= '<i class="fa fa-ban fa-2x" title="NO_IMAGE"></i>&nbsp;'; 
                    }
                    $tools .= '</a>&nbsp;';
                }
            }
        }
        return $tools;
    }
    
    protected function _displaySearchTools() {
        
        $searchTools = '';
        if ($this->params['bool_showSearchTools']) {
			//Remove old what filter
			$searchToolsLink = $this->_removeUrlVar($this->link,'what');
			//
            $searchTools .= '<div id="searchTools" class="listletter"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="forms"><tr>';
            //Alphabetical list
            $searchTools .= '<td width="65%" height="30"><strong>'._ALPHABETICAL_LIST.'</strong> : ';
            for($i=ord('A'); $i <= ord('Z');$i++) {
                //Highlight selected letter
                (chr($i) == trim($this->whatSearch))? $letter = '<span class="selectedLetter">'.chr($i).'</span>' : $letter = chr($i);
                $searchTools .= '<a href="javascript://" onClick="loadList(\''.$searchToolsLink.'&what='.chr($i)
                    .'\', \''.$this->divListId.'\', '.$this->modeReturn.');">'.$letter.'</a>&nbsp;';
            }
            $searchTools .= '-&nbsp;<a href="javascript://" onClick="loadList(\''.
                $searchToolsLink.'&what=\', \''.$this->divListId.'\', '.$this->modeReturn.');">'._ALL.'</a>';
            $searchTools .= '</td>';
            //Search box
            $searchTools .= '<td width="35%" align="right">&nbsp;';
            if ($this->params['bool_showSearchBox']) {
                $searchTools .= '<form id="frmletters" name="frmletters" method="post" action="#"><div>';
                (strlen($this->whatSearch) > 1)? $what = $this->whatSearch : $what ='';
                $searchTools .= '<input type="text" name="what" id="what" size="15" value="'.$what.'" onkeyup="erase_contact_external_id(\'what\', \'selectedObject\');"/>&nbsp;';
                if(isset($this->params['searchBoxAutoCompletionUrl']) && !empty($this->params['searchBoxAutoCompletionUrl'])) {
                    $searchTools .= '<div id="whatList" class="autocomplete"></div>';
                    $searchTools .= '<script type="text/javascript">';
                    if ($this->params['searchBoxAutoCompletionUpdate'] == true) {
                        $searchTools .= 'launch_autocompleter_update(\''
                            .$this->params['searchBoxAutoCompletionUrl'].'\', \'what\', \'whatList\', \''
                            .$this->params['searchBoxAutoCompletionMinChars'].'\', \'selectedObject\');';
                    } else {
                        $searchTools .= 'initList(\'what\', \'whatList\', \''
                            .$this->params['searchBoxAutoCompletionUrl'].'\', \''
                            .$this->params['searchBoxAutoCompletionParamName'].'\', \''
                            .$this->params['searchBoxAutoCompletionMinChars'].'\');';
                    }

                    $searchTools .= '</script>';
                    $searchTools .= '<input type="hidden" name="selectedObject" id="selectedObject" />';
                }
                $searchTools .= '<input name="submit" class="button" type="button" value="'
                    ._SEARCH.'" onClick="loadList(\''
                    .$this->link.'&what=\' + document.getElementById(\'what\').value+\'&selectedObject=\' + document.getElementById(\'selectedObject\').value, \''
                    .$this->divListId.'\', '.$this->modeReturn.');"/><div></form>';
            }
            $searchTools .= '</td>';
            $searchTools .= '</tr></table></div>';
        }
        return $searchTools;
    }
    
    protected function _createToolbar($resultFirstRow) {
        $toolbar = $tools =  $templates = $filters = '';
        $start = $end = 0;
        
        //Loading image
        $loading = '<div id="loading" style="display:none;">'
                    . '<i class="fa fa-spinner fa-2x" style="vertical-align: middle;" title="loading..."></i></div>';
        
        //Lines to show
        $nbLines = $this->params['linesToShow'];
        if(isset($_REQUEST['lines']) && !empty($_REQUEST['lines'])) {
            $nbLines = $this->params['linesToShow'] = strip_tags($_REQUEST['lines']);
            $_SESSION['save_list']['lines'] = $nbLines;
        }

        //Number of pages
        $nb_pages = ceil($this->countResult/$this->params['linesToShow']);
        // $debug .='NB total '.$this->countResult.' / NB show: '.$this->params['linesToShow'].' / Pages: '.$nb_pages.' /';
        
        if(isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $start = strip_tags($_REQUEST['start']);
        $end = $start + $this->params['linesToShow'];
        if($end > $this->countResult) $end = $this->countResult;
        
        //Get list of tools (icon and link)
        $tools = $this->_getTools($resultFirstRow, $this->countResult);
        
        //Get templates
        $templates = $this->_getTemplates();
        
         //Get Filters
        if(isset($this->params['filters']) && count($this->params['filters']) > 0) {
            $height = '60px';
            $filters = '</tr><tr><td colspan="11" class="separator2">'.($this->_displayFilters()).'</td></tr>';
        } else {
            $height = '30px';
        }
        
        //Build dropdown lines object
        $linesDropdownList = '';
        if ($this->params['bool_changeLinesToShow']) {
            $nbLinesSelect = array(
                10,
                25,
                50,
                100,
                250,
                500
            );
            if (!in_array($this->params['linesToShow'], $nbLinesSelect)) {
                array_push($nbLinesSelect, $this->params['linesToShow']);
            }
            sort($nbLinesSelect);
            
            $linesDropdownList .= '<i class="fa fa-bars fa-2x" aria-hidden="true" title="'._SHOW.'"></i> <select name="nbLines" id="nbLines" onChange="loadList(\''.$this->link
                                .'&order='.$this->order.'&order_field='
                                .$this->orderField.'&lines=\' + this.value, \''
                                .$this->divListId.'\', '.$this->modeReturn.');">';
            //Array values                           
            for ($i=0; $i<count($nbLinesSelect); $i++) {
                if ($nbLinesSelect[$i] >= $this->countResult) {
                    break;
                }
                ($nbLinesSelect[$i] == $nbLines)? $selected = 'selected="selected" ' :  $selected = '';
                $linesDropdownList .= '<option value="' . $nbLinesSelect[$i] . '" '.$selected.'>'.$nbLinesSelect[$i]._LINES.'</option>';
            }
            //Extra value
            ($this->countResult == $nbLines || $this->countResult < $nbLines)? $selected = 'selected="selected" ' :  $selected = '';
            $linesDropdownList .= '<option value="' . $this->countResult . '" '.$selected.'>'._ALL.'('.$this->countResult.')</option>';
            $linesDropdownList .= '</select>';
            $linesDropdownList .= '<script>if(!$(\'nbLines_chosen\')){new Chosen($(\'nbLines\'),{width: "auto"});}</script>';
        }
        
        //If there are more than 1 page, pagination
        if($nb_pages > 1) {
            //Build dropdown navigation object
            $next_start = 0;
            $pageDropdownList .= _GO_TO_PAGE.' <select name="startpage" onChange="loadList(\''.$this->link
                                .'&order='.$this->order.'&order_field='
                                .$this->orderField.'&start=\' + this.value, \''
                                .$this->divListId.'\', '.$this->modeReturn.');">';
            $lastpage = 0;
            for($i = 0;$i <> $nb_pages; $i++){
                $the_line = $i + 1;
                if($start == $next_start)
                    $pageDropdownList .= '<option value="'.$next_start.'" selected="selected">'.($i+1).'</option>';
                else
                    $pageDropdownList .= '<option value="'.$next_start.'">'.($i+1).'</option>';
                
                $next_start = $next_start + $this->params['linesToShow'];
                $lastpage = $next_start;
            }
            $pageDropdownList .= '</select>';
            
            //
            $lastpage = $lastpage - $this->params['linesToShow'];
            $previous = "&nbsp;";
            $next = "";
            //Previous
            if($start > 0) {
                $start_prev = $start - $this->params['linesToShow'];
                $previous = '<a href="javascript://" onClick="loadList(\''.$this->link.'&order='
                    .$this->order.'&order_field='.$this->orderField.'&start='.$start_prev
                    .'\', \''.$this->divListId.'\', '.$this->modeReturn
                    .');"><i class="fa fa-backward" title="' . _PREVIOUS . '"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';

            }
            //Next link
            if($start <> $lastpage) {
                $start_next = $start + $this->params['linesToShow'];
                $next = ' <a href="javascript://" onClick="loadList(\''.$this->link.'&order='
                    .$this->order.'&order_field='.$this->orderField.'&start='
                    .$start_next.'\', \''.$this->divListId.'\', '.$this->modeReturn
                    .');"><i class="fa fa-forward" title="' . _NEXT . '"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $toolbar .= '<div class="block" style="height:'.$height.';" align="center" >';
            $toolbar .= '<table width="100%" border="0"><tr>';
            $toolbar .= '<td align="left" width="20px" nowrap>'.$loading.'</td>';
            $toolbar .= '<td align="center" width="15%" nowrap><b>'.$previous.'</b></td>';
            $toolbar .= '<td align="center" width="15%" nowrap><b>'.$next.'</b></td>';
            $toolbar .= '<td width="10px" class="separator1">|</td>';
            $toolbar .= '<td align="center" width="15%" nowrap>'.$pageDropdownList.'</td>';
            $toolbar .= '<td width="10px" class="separator1">|</td>';
            $toolbar .= '<td align="center" width="15%" nowrap>'.$linesDropdownList.'</td>';
            $toolbar .= '<td width="10px" class="separator1">|</td>';
            $toolbar .= '<td width="210px" align="right" nowrap>'.$tools.'</td>';
            $toolbar .= '<td width="5px" class="separator1">|</td>';
            $toolbar .= '<td align="right" nowrap>'.$templates.'</td>';
            $toolbar .= '</tr>';
            $toolbar .= $filters;
            $toolbar .= '</table>';
            $toolbar .= '</div>';
        } else {
            //Show toolbar if templates, tools or filters
            if (
                !empty($templates) || 
                !empty($tools) || 
                !empty($filters) ||
                ($this->params['bool_changeLinesToShow'] && $this->countResult > 0)
                )
            {
                // $showToolbar = true;
                //if no result
                if ($this->countResult == 0) {
                    //reset templates and tools (no need if no result)
                    // $templates = '&nbsp;';
                    // $tools = '&nbsp;';
                    //if not caused by filters => list is empty
                    if($this->_haveFilter() !== true) { 
                        $filters = '';
                        // $showToolbar = false;
                    }
                }
                //Toolbar
                 if ($this->params['bool_showToolbar']) {
                    $toolbar .= '<div class="block" style="height:'.$height.';" align="center" >';
                    $toolbar .= '<table width="100%" border="0"><tr>';
                    $toolbar .= '<td align="left" width="20px" nowrap>'.$loading.'</td>';
                    $toolbar .= '<td align="center" width="15%" nowrap><b>&nbsp;</b></td>';
                    $toolbar .= '<td align="center" width="15%" nowrap><b>&nbsp;</b></td>';
                    $toolbar .= '<td width="10px" class="separator1">|</td>';
                    $toolbar .= '<td align="center" width="15%" nowrap>'.$pageDropdownList.'</td>';
                    $toolbar .= '<td width="10px" class="separator1">|</td>';
                    $toolbar .= '<td align="center" width="15%" nowrap>'.$linesDropdownList.'</td>';                
                    $toolbar .= '<td width="10px" class="separator1">|</td>';
                    $toolbar .= '<td width="210px"align="right">'.$tools.'</td>';
                    $toolbar .= '<td width="5px" class="separator1">|</td>';
                    $toolbar .= '<td align="right" nowrap>'.$templates.'</td>';
                    $toolbar .= '</tr>';
                    $toolbar .= $filters;
                    $toolbar .= '</table>';
                    $toolbar .= '</div>';
                 }
            }
        }
        
        $this->start = $start;
        $this->end = $end;
        
        return $toolbar;
    }    
    
    protected function _createBottomToolbar($resultFirstRow) {

        //Init
        $toolbar = $tools =  $templates = '';
        $start = $end = 0;
        
        //Loading image
        $loading = '<div id="loading" style="display:none;">'
        . '<i class="fa fa-spinner fa-2x" style="vertical-align: middle;" title="loading..."></i></div>';
        
        //Lines to show
        if(isset($_REQUEST['lines']) && !empty($_REQUEST['lines'])) {
            $nbLines = $this->params['linesToShow'] = strip_tags($_REQUEST['lines']);
        }
        
        //Number of pages
        $nb_pages = ceil($this->countResult/$this->params['linesToShow']);
        // $debug .='NB total '.$this->countResult.' / NB show: '.$this->params['linesToShow'].' / Pages: '.$nb_pages.' /';
        
        if(isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $start = strip_tags($_REQUEST['start']);
        $end = $start + $this->params['linesToShow'];
        if($end > $this->countResult) $end = $this->countResult;
        
        //Get list of tools (icon and link)
        $tools = $this->_getTools($resultFirstRow, $this->countResult);
        
        //Get templates
        $templates = $this->_getTemplates();
        
        //Go to top link
        $goToTop ='<a href="javascript://" onclick="new Effect.ScrollTo(\'topOfTheList\');'
            . 'return false;" alt="Top"><i class="fa fa-arrow-up fa-2x" style="vertical-align: middle;" title="Top"></a>';
        
        //Build dropdown lines object
        $linesDropdownList = '';
        if ($this->params['bool_changeLinesToShow']) {
            $nbLinesSelect = array(
                10,
                25,
                50,
                100,
                250,
                500
            );
            if (!in_array($this->params['linesToShow'], $nbLinesSelect)) {
                array_push($nbLinesSelect, $this->params['linesToShow']);
            }
            sort($nbLinesSelect);
            
            $linesDropdownList .= '<i class="fa fa-bars fa-2x" aria-hidden="true" title="'._SHOW.'"></i> <select name="nbLines" id="nbLines" onChange="loadList(\''.$this->link
                                .'&order='.$this->order.'&order_field='
                                .$this->orderField.'&lines=\' + this.value, \''
                                .$this->divListId.'\', '.$this->modeReturn.');">';
            //Array values                           
            for ($i=0; $i<count($nbLinesSelect); $i++) {
                if ($nbLinesSelect[$i] >= $this->countResult) {
                    break;
                }
                ($nbLinesSelect[$i] == $nbLines)? $selected = 'selected="selected" ' :  $selected = '';
                $linesDropdownList .= '<option value="' . $nbLinesSelect[$i] . '" '.$selected.'>'.$nbLinesSelect[$i]._LINES.'</option>';
            }
            //Extra value
            ($this->countResult == $nbLines || $this->countResult < $nbLines)? $selected = 'selected="selected" ' :  $selected = '';
            $linesDropdownList .= '<option value="' . $this->countResult . '" '.$selected.'>'._ALL.'('.$this->countResult.')</option>';
            $linesDropdownList .= '</select>';
            $linesDropdownList .= '<script>if(!$(\'nbLines_chosen\')){new Chosen($(\'nbLines\'),{width: "auto"});}</script>';
        }
        
        //If there are more than 1 page, pagination
        if($nb_pages > 1) {
        
            //Build dropdown navigation object
            $next_start = 0;
            $pageDropdownList .= _GO_TO_PAGE.' <select name="startpage" onChange="loadList(\''.$this->link
                                .'&order='.$this->order.'&order_field='
                                .$this->orderField.'&start=\' + this.value, \''
                                .$this->divListId.'\', '.$this->modeReturn.');">';
            $lastpage = 0;
            for($i = 0;$i <> $nb_pages; $i++){
                $the_line = $i + 1;
                if($start == $next_start)
                    $pageDropdownList .= '<option value="'.$next_start.'" selected="selected">'.($i+1).'</option>';
                else
                    $pageDropdownList .= '<option value="'.$next_start.'">'.($i+1).'</option>';
                
                $next_start = $next_start + $this->params['linesToShow'];
                $lastpage = $next_start;
            }
            $pageDropdownList .= '</select>' ;
            
            //
            $lastpage = $lastpage - $this->params['linesToShow'];
            $previous = "&nbsp;";
            $next = "";
            //Previous
            if($start > 0) {
                $start_prev = $start - $this->params['linesToShow'];
                $previous = '<a href="javascript://" onClick="loadList(\''.$this->link.'&order='
                    .$this->order.'&order_field='.$this->orderField.'&start='.$start_prev
                    .'\', \''.$this->divListId.'\', '.$this->modeReturn
                    .');"><i class="fa fa-backward" title="' . _PREVIOUS . '"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            
            //Next link
            if($start <> $lastpage) {
                $start_next = $start + $this->params['linesToShow'];
                $next = ' <a href="javascript://" onClick="loadList(\''.$this->link.'&order='
                    .$this->order.'&order_field='.$this->orderField.'&start='
                    .$start_next.'\', \''.$this->divListId.'\', '.$this->modeReturn
                    .');"><i class="fa fa-forward" title="' . _NEXT . '"></i></a>';
            }

            //Toolbar
            $bottomToolbar .= '<br/>';
            $bottomToolbar .= '<div class="block_bottom" align="center" >';
            $bottomToolbar .= '<table width="100%" border="0"><tr>';
            $bottomToolbar .= '<td align="left" width="20px" nowrap>'.$loading.'</td>';
            $bottomToolbar .= '<td align="center" width="15%" nowrap><b>'.$previous.'</b></td>';
            $bottomToolbar .= '<td align="center" width="15%" nowrap><b>'.$next.'</b></td>';
            $bottomToolbar .= '<td width="10px" class="separator1">|</td>';
            $bottomToolbar .= '<td align="center" width="15%" nowrap>'.$pageDropdownList.'</td>';
            $bottomToolbar .= '<td width="10px" class="separator1">|</td>';
            $bottomToolbar .= '<td align="center" width="15%" nowrap>'.$linesDropdownList.'</td>';
            $bottomToolbar .= '<td width="10px" class="separator1">|</td>';
            $bottomToolbar .= '<td width="210px" align="right" nowrap>'.$tools.'</td>';
            $bottomToolbar .= '<td width="5px" class="separator1">|</td>';
            $bottomToolbar .= '<td align="right" nowrap>'.$templates.'</td>';
            $bottomToolbar .= '<td width="5px" class="separator1">|</td>';
            $bottomToolbar .= '<td align="right" width="20px">'.$goToTop.'</td>';
            $bottomToolbar .= '</tr>';
            $bottomToolbar .= '</table>';
            $bottomToolbar .= '</div>';
        } else {
            //Show toolbar if templates or tools
            if (
                !empty($templates) || 
                !empty($tools) ||
                $this->params['bool_changeLinesToShow']
            ) 
           {
                //Toolbar
                $bottomToolbar .= '<div class="block_bottom" align="center" >';
                $bottomToolbar .= '<table width="100%" border="0"><tr>';
                $bottomToolbar .= '<td align="left" width="20px" nowrap>'.$loading.'</td>';
                $bottomToolbar .= '<td align="center" width="15%" nowrap><b>&nbsp;</b></td>';
                $bottomToolbar .= '<td align="center" width="15%" nowrap><b>&nbsp;</b></td>';
                $bottomToolbar .= '<td width="10px" class="separator1">|</td>';
                $bottomToolbar .= '<td align="center" width="15%" nowrap>'.$pageDropdownList.'</td>';
                $bottomToolbar .= '<td width="10px" class="separator1">|</td>';
                $bottomToolbar .= '<td align="center" width="15%" nowrap>'.$linesDropdownList.'</td>';                
                $bottomToolbar .= '<td width="10px" class="separator1">|</td>';
                $bottomToolbar .= '<td width="210px"align="right">'.$tools.'</td>';
                $bottomToolbar .= '<td width="5px" class="separator1">|</td>';
                $bottomToolbar .= '<td align="right" nowrap>'.$templates.'</td>';
                $bottomToolbar .= '<td width="5px" class="separator1">|</td>';
                $bottomToolbar .= '<td align="right" width="20px">'.$goToTop.'</td>';
                $bottomToolbar .= '</tr>';
                $bottomToolbar .= '</table>';
                $bottomToolbar .= '</div>';
            }
        }
        
        $this->start = $start;
        $this->end = $end;
        
        return $bottomToolbar;
    }
    
    protected function _createSmallToolbar () {
        //Init
        $toolbar = '';
        $start = $end = 0;
        
        //Lines to show
        if(isset($_REQUEST['lines']) && !empty($_REQUEST['lines'])) {
            $nbLines = $this->params['linesToShow'] = strip_tags($_REQUEST['lines']);
        }
        
        //Number of pages
        $nb_pages = ceil($this->countResult/$this->params['linesToShow']);
        
        if(isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $start = strip_tags($_REQUEST['start']);
        $end = $start + $this->params['linesToShow'];
        if($end > $this->countResult) {
            $end = $this->countResult;
        }
        
        //Build dropdown lines object
        $linesDropdownList = '';
        if ($this->params['bool_changeLinesToShow']) {
            $nbLinesSelect = array(
                10,
                25,
                50,
                100,
                250,
                500
            );
            if (!in_array($this->params['linesToShow'], $nbLinesSelect)) {
                array_push($nbLinesSelect, $this->params['linesToShow']);
            }
            sort($nbLinesSelect);
            
            $linesDropdownList = '<form name="nbLinesToShow" method="get" >';
            $linesDropdownList .= '<i class="fa fa-bars fa-2x" aria-hidden="true" title="'._SHOW.'"></i>
 <select name="nbLines" id="nbLines" onChange="loadList(\''.$this->link
                                .'&order='.$this->order.'&order_field='
                                .$this->orderField.'&lines=\' + document.nbLinesToShow.nbLines.value, \''
                                .$this->divListId.'\', '.$this->modeReturn.');">';
            //Array values                           
            for ($i=0; $i<count($nbLinesSelect); $i++) {
                if ($nbLinesSelect[$i] >= $this->countResult) {
                    break;
                }
                ($nbLinesSelect[$i] == $nbLines)? $selected = 'selected="selected" ' :  $selected = '';
                $linesDropdownList .= '<option value="' . $nbLinesSelect[$i] . '" '.$selected.'>'.$nbLinesSelect[$i]._LINES.'</option>';
            }
            //Extra value
            ($this->countResult == $nbLines || $this->countResult < $nbLines)? $selected = 'selected="selected" ' :  $selected = '';
            $linesDropdownList .= '<option value="' . $this->countResult . '" '.$selected.'>'._ALL.'('.$this->countResult.')</option>';
            $linesDropdownList .= '</select>';
            $linesDropdownList .= '<script>if(!$(\'nbLines_chosen\')){new Chosen($(\'nbLines\'),{width: "auto"});}</script>';
            $linesDropdownList .= '</form>' ;
        }
        
        //If there are more than 1 page, pagination
        if($nb_pages > 1) {
            //Build dropdown navigation object
            $next_start = 0;
            $pageDropdownList = ''
                                .'<select name="startpage" id="startpage" class ="small" onChange="loadList(\''.$this->link
                                .'&order='.$this->order.'&order_field='
                                .$this->orderField.'&start=\' + document.'.$this->formId.'.startpage.value, \''
                                .$this->divListId.'\', '.$this->modeReturn.');">';
            $lastpage = 0;
            for($i = 0;$i <> $nb_pages; $i++){
                $the_line = $i + 1;
                if($start == $next_start)
                    $pageDropdownList .= '<option value="'.$next_start.'" selected="selected">'.($i+1).'</option>';
                else
                    $pageDropdownList .= '<option value="'.$next_start.'">'.($i+1).'</option>';
                
                $next_start = $next_start + $this->params['linesToShow'];
                $lastpage = $next_start;
            }
            $pageDropdownList .= "</select>" ;
            
            $lastpage = $lastpage - $this->params['linesToShow'];
            $previous = "&nbsp;";
            $next = "";
            
            //Previous
            if($start > 0) {
                $start_prev = $start - $this->params['linesToShow'];
                $previous .= '<a href="javascript://" alt="'._PREVIOUS.'" onClick="loadList(\''.$this->link.'&order='
                    .$this->order.'&order_field='.$this->orderField.'&start='.$start_prev
                    .'\', \''.$this->divListId.'\', '.$this->modeReturn
                    .');"><i class="fa fa-backward" title="' . _PREVIOUS . '"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            
            //Next
            if($start <> $lastpage) {
                $start_next = $start + $this->params['linesToShow'];
                $next = ' <a href="javascript://" alt="'._NEXT.'" onClick="loadList(\''.$this->link.'&order='
                    .$this->order.'&order_field='.$this->orderField.'&start='
                    .$start_next.'\', \''.$this->divListId.'\', '.$this->modeReturn
                    .');"><i class="fa fa-forward" title="' . _NEXT . '"></i></a>';
            }
            
            //Loading image
            $loading = '<div id="loading" style="display:none;">'
                . '<i class="fa fa-spinner fa-2x" style="vertical-align: middle;" title="loading..."></i></div>';
                    
            //Small toolbar
            $toolbar .= '<table width="100%" border="0" cellspacing="0" class="zero_padding"><tr>';
            $toolbar .= '<td align="left" width="15px" nowrap>'.$loading.'</td>';
            $toolbar .= '<td align="left" width="10px" nowrap><b>'.$previous.'</b></td>';
            $toolbar .= '<td align="center" width="10px" nowrap><b>'.$next.'</b></td>';
            $toolbar .= '<td width="1%" class="separator1">|</td>';
            $toolbar .= '<td align="left" width="94%">'.$pageDropdownList.'</td>';
            $toolbar .= '</tr></table>';
        }
        
        $this->start = $start;
        $this->end = $end;
        
        return $toolbar;
    }
    
    protected function _displayFilters() {
        $filters = $filtersControl = '';
        if(isset($this->params['filters']) && count($this->params['filters']) > 0) {
           $found  = false;

            for ($i =0; $i<count($this->params['filters']); $i++) {
                //var_dump($this->params['filters'][$i]);
                if (isset($_SESSION['filters'][$this->params['filters'][$i]])) {
                    $filtersControl .= $this->_buildFilter($this->params['filters'][$i]);
                    $found  = true;
                }
            }
            if ($found) {
                //Display filter
                $filters .='<div style="padding-bottom: 15px;"><form name="filters" id="filters" '
                        .'onsubmit="return false;" action="#" method="post"><i class="fa fa-filter fa-2x" title="'._FILTER_BY.'" aria-hidden="true" style="position: relative;top: 3px;"></i>: ';
                $filters .= $filtersControl;
                //Clear icon
                $filters .='|&nbsp;&nbsp;<a href="javascript://"  title="'._CLEAR_SEARCH.'" onfocus="this.blur()" '
                            .'onclick="javascript:loadList2(\''.$this->link
                            .'&filter=reset\', \''.$this->divListId.'\', '
                            .$this->modeReturn.');">'
                            .'<i class="fa fa-refresh fa-2x" style="position: relative;top: 6px;" title="' . _CLEAR_SEARCH . '"></i></a>';
                $filters .='</form></div>';
            } else {
                $filters = _NO_CORRESPONDING_FILTERS;
            }
        }
        return $filters;
    }
    
    protected function _createHiddenFields() {
        $hiddenFields = '';
        //Action management hidden fields
        if ($this->withForm) {
            if (!empty($this->params['collId'])) $hiddenFields 
                .= '<input type="hidden" id="coll_id" name="coll_id" value="'.$this->params['collId'].'">';
            if (!empty($this->params['moduleName'])) $hiddenFields 
                .= '<input type="hidden" id="module" name="module" value="'.$this->params['moduleName'].'">';
            if (!empty($this->params['tableName'])) $hiddenFields 
                .= '<input type="hidden" id="table" name="table" value="'.$this->params['tableName'].'">';
        }
        //Regular hidden fields
        if(isset($this->params['hiddenFormFields']) && count($this->params['hiddenFormFields']) > 0) {
            for ($i =0; $i<count($this->params['hiddenFormFields']); $i++) {
                    $hiddenFields .= '<input type="hidden" id="'
                    .$this->params['hiddenFormFields'][$i]['ID']
                    .'" name="'.$this->params['hiddenFormFields'][$i]['NAME']
                    .'" value="'.$this->params['hiddenFormFields'][$i]['VALUE'].'">';
            }
        }
        return $hiddenFields;
    }
    
    protected function _displayAddButton() {
        $addButton = '';
        //$addButton .= '<tr><td class="price" colspan="'.$this->countTd.'"><span class="add clearfix">';
        $addButton .= '<tr><td class="price" colspan="'.$this->countTd.'">';
        if(isset($this->params['addButtonScript']) && !empty($this->params['addButtonScript'])) { //Script
            $addButtonScript = 'onClick="javascript:'.$this->params['addButtonScript'].'"';
            $addButtonLink = 'javascript://';
        } else if(isset($this->params['addButtonLink']) && !empty($this->params['addButtonLink'])) { //Link
            $addButtonScript = '';
            $addButtonLink = $this->params['addButtonLink'];
        } else { //Error
            $addButtonLink  = '#';
            //ERROR RETURN
        }
        //$addButton .= '<a href="'.$addButtonLink.'" '.$addButtonScript.'><span>'.$this->params['addButtonLabel'].'</span></a></span>';
        $addButton .= '<a href="'.$addButtonLink.'" '.$addButtonScript
            .'><span><i class="fa fa-plus-square fa-3x" title="' . $this->params['addButtonLabel'] . '"></i></span></a>';
        $addButton .= '</td></tr>';
        
        return $addButton;
    }
    
    protected function _createExtraJavascript() {
        $str .= '<script type="text/javascript">';
        $str .= ' var arr_msg_error = {\'confirm_title\' : \''.addslashes(_ACTION_CONFIRM).'\',';
        $str .= ' \'validate\' : \''.addslashes(_VALIDATE).'\',';
        $str .= ' \'cancel\' : \''.addslashes(_CANCEL).'\',';
        $str .= ' \'choose_action\' : \''.addslashes(_CHOOSE_ACTION).'\',';
        $str .= ' \'choose_one_doc\' : \''.addslashes(_CHOOSE_ONE_DOC).'\',';
        $str .= ' \'choose_one_folder\' : \''.addslashes(_CHOOSE_ONE_FOLDER).'\'';
        $str .= ' };';
        $str .= 'var validForm = function(mode, res_id, id_action) {';
        $str .= 'if(!isAlreadyClick) {';
        $str .= ' var val = \'\';';
        $str .= ' var action_id = \'\';';
        $str .= ' var table = \'\';';
        $str .= ' var coll_id = \'\';';
        $str .= ' var module = \'\';';
        $str .= ' var thisfrm = document.getElementById(\''.$this->formId.'\');';
        $str .= ' if(thisfrm) {';
        $str .= ' for(var i=0; i < thisfrm.elements.length; i++) {';
        $str .= ' if(thisfrm.elements[i].id == \'field\' && thisfrm.elements[i].checked == true) {';
        $str .= ' val += thisfrm.elements[i].value+\',\';';
        $str .= ' }';
        $str .= ' else if(thisfrm.elements[i].id == \'action\') {';
        $str .= ' action_id = thisfrm.elements[i].options[thisfrm.elements[i].selectedIndex].value;';
        $str .= ' }';
        $str .= ' else if(thisfrm.elements[i].id == \'table\') {';
        $str .= ' table = thisfrm.elements[i].value;';
        $str .= ' }';
        $str .= ' else if(thisfrm.elements[i].id == \'coll_id\') {';
        $str .= ' coll_id = thisfrm.elements[i].value;';
        $str .= ' }';
        $str .= ' else if(thisfrm.elements[i].id == \'module\') {';
        $str .= ' module = thisfrm.elements[i].value;';
        $str .= ' }';
        $str .= ' }';
        $str .= ' if(module == \'\') {';
        $str .= ' module = \'null\';';
        $str .= ' }';
        $str .= ' val = val.substr(0, val.length -1);';
        $str .= ' var val_frm = {\'values\' : val,  \'action_id\' : action_id, \'table\' : table, \'coll_id\' : coll_id, \'module\' : module};';
        $str .= ' if(res_id && res_id != \'\') {';
        $str .= ' val_frm[\'values\'] = res_id;';
        $str .= ' }';
        $str .= ' if(id_action && id_action != \'\') {';
        $str .= ' val_frm[\'action_id\'] = id_action;';
        $str .= ' }';
        $str .= ' action_send_first_request(\''.$_SESSION['config']['businessappurl']
                .'index.php?display=true&page=manage_action&module=core\', mode,  val_frm[\'action_id\'], '
                .'val_frm[\'values\'], val_frm[\'table\'], val_frm[\'module\'], val_frm[\'coll_id\']);';
        $str .= ' } else {';
        $str .= ' alert(\'Validation form error\');';
        $str .= ' }';
        $str .= ' if (mode == \'mass\') {';
        $str .= ' isAlreadyClick = false;';
        $str .= ' } else {';
        $str .= ' isAlreadyClick = true;';
        $str .= ' }';
        $str .= '}';
        $str .= '}';
        $str .= ' </script>';
            
        return $str;
    }
    
    protected function _createActionsList($currentBasket) {
        
        if (count($currentBasket) > 0 ) {
           
            //Default action
            if(!empty($currentBasket['default_action'])) {
                $this->params['bool_actionOnLineClick'] = true;
                //Enable action management
                $this->haveAction = true;
                $this->params['defaultAction'] = $currentBasket['default_action'];
            }
             
            //Collection
            if(!empty($currentBasket['coll_id'])) $this->params['collId'] = $currentBasket['coll_id'];
            //Table name
            if(!empty($currentBasket['table'])) $this->params['tableName'] = $currentBasket['table'];
            //Lock list
            if (!empty ($currentBasket['lock_list'])) $this->params['disabledRules'] = $currentBasket['lock_list'];
            //Basket clause
            if (!empty ($currentBasket['clause'])) $this->params['basketClause'] = $currentBasket['clause'];
      
            //Actions list
            if (count($currentBasket['actions']) > 0) {
                $this->params['actions'] = array();
                for($i=0; $i<count($currentBasket['actions']);$i++) {
                    if($currentBasket['actions'][$i]['MASS_USE'] == 'Y') {
                        array_push($this->params['actions'], array('ID' => $currentBasket['actions'][$i]['ID'], 
                        'LABEL' => $currentBasket['actions'][$i]['LABEL']));
                    }
                }
            }
        }
        
        //If no action disable all form input
        if ((!isset($this->params['actions']) || count($this->params['actions']) == 0)  
            && $this->params['bool_standaloneForm'] === false
        ) {
            $this->params['bool_checkBox'] = false;
            $this->params['bool_radioButton'] = false;
        } else {
            //Display checkbox if both checkbox and radio type are disabled
            if ($this->params['bool_checkBox'] === false 
                && $this->params['bool_radioButton'] === false
                && $this->params['bool_standaloneForm'] === false
            ) {
                $this->params['bool_checkBox'] = true;
            }
            
            //Enable action management
            $this->haveAction = true;
        }
    }
    
    protected function _displayButtons() {
        $buttons = '';
        if (isset($this->params['buttons']) && count($this->params['buttons']) > 0) {
            for($i=0; $i < count($this->params['buttons']); $i++) {
                //Button type
                if (isset($this->params['buttons'][$i]['TYPE'])) 
                    $type = $this->params['buttons'][$i]['TYPE']; 
                else 
                    $type = 'button';
                $buttons .= ' <input type="'
                    .$type.'" name="'
                    .$this->params['buttons'][$i]['ID'].'" id="'
                    .$this->params['buttons'][$i]['ID'].'" value="'
                    .$this->params['buttons'][$i]['LABEL'].'" onClick="'
                    .$this->params['buttons'][$i]['ACTION'].'" class="button" />';
            }
        }
        return $buttons;
    }
    
    protected function _displayActionsList() {
        $actionsList = '';
        $actionsList .= ' <p align="center">';
        if (count($this->params['actions']) > 0) {
            
            $actionsList .= ' <b>'._ACTIONS.' :</b>';
            $actionsList .= ' <select name="action" id="action">';
            $actionsList .= ' <option value="">'. _CHOOSE_ACTION.'</option>';
            for($i = 0; $i < count($this->params['actions']);$i++){
                $actionsList .= ' <option value="'.$this->params['actions'][$i]['ID'].'">'
                    .$this->params['actions'][$i]['LABEL'].'</option>';
            }
            $actionsList .= ' </select>';
            $actionsList .= ' <input type="button" name="send" id="send_mass" value="'._VALIDATE
                .'" onClick="validForm(\'mass\');window.location.href=\'#top\'" class="button" />';
        }
        
        $actionsList .= $this->_displayButtons();
        $actionsList .= ' </p>';
        return $actionsList;
    }
    
    protected function _createPreviewDiv() {
    
        $str_previsualise = '<div ';
        $str_previsualise .= 'id="return_previsualise" ';
        $str_previsualise .= 'style="';
        $str_previsualise .= 'display: none; ';
        $str_previsualise .= 'border-radius: 10px; ';
        $str_previsualise .= 'box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.4); ';
        $str_previsualise .= 'padding: 10px; ';
        $str_previsualise .= 'width: auto; ';
        $str_previsualise .= 'height: auto; ';
        $str_previsualise .= 'position: absolute; ';
        $str_previsualise .= 'top: 0; ';
        $str_previsualise .= 'left: 0; ';
        $str_previsualise .= 'z-index: 999; ';
        $str_previsualise .= 'background-color: rgba(255, 255, 255, 0.9); ';
        $str_previsualise .= 'border: 3px solid #459ed1;';
        $str_previsualise .= '" ';
        $str_previsualise .= '>';
        $str_previsualise .= '<input type="hidden" id="identifierDetailFrame" value="" />';
        $str_previsualise .= '</div>';
        
        return $str_previsualise;
    }
    
    protected function _buildPreviewContent($resultTheLine, $listKey, $jsonIdentifier = 'identifierDetailFrame') { 
        $contentArray = array();
        
        //Get the ListKey value
        $keyValue = '';
        for($i= 0; $i <= count($resultTheLine); $i++ ) {
            if($resultTheLine[$i]['column'] == $listKey) {
                $keyValue = $resultTheLine[$i]['value'];
                break;
            }
        }
        
        foreach(array_keys($resultTheLine) as $column) { // for every column
            $contentArray[$jsonIdentifier] = $keyValue;
            $contentArray[$resultTheLine[$column]['column']] = $resultTheLine[$column]['value'];
        }
        
        /*
        //If you want to use different key for action link
        if (strpos($link, "@@") !== false) {
            
                $key = "@@".$resultTheLine[$column]['column']."@@"; //build the alias
                $val = $resultTheLine[$column]['value']; //get the real value
                $link = str_replace($key, $val, $link); //replace alias by real value
            }
        }
        */
        
        return  json_encode($contentArray);
    }
    
    protected function _createActionIcon($actualLine, $actualButton, $listKey='') {
        $icon = '';
        
        if (isset($actualButton['type']) && $actualButton['type'] == 'preview') {
            if (!isset($actualButton['content']) || empty($actualButton['content'])) {
                $content = $this->_buildPreviewContent($actualLine, $listKey);
            } else {
                 $content = $this->_buildMyLink($actualButton['content'], $actualLine);
            }
            // $icon .= $content;
            $icon .= '<a href="javascript://"';
            $icon .= ' onMouseOver="previsualiseAdminRead(event, '.htmlspecialchars($content).');" ';
            $icon .= ' onMouseOut="$(\'identifierDetailFrame\').setValue(\'\'); '
                    .'$(\'return_previsualise\').style.display=\'none\';" ';
            $icon .= ' title="'.$actualButton['tooltip'].'"';
        } else {
            if(isset($actualButton['script']) && !empty($actualButton['script'])) {
                $script = $this->_buildMyLink($actualButton['script'], $actualLine);
                $icon .= '<a href="javascript://" ';
                
                //If javascript alert box
                if(isset($actualButton['alertText']) && !empty($actualButton['alertText'])) {
                    $alertText = $this->_buildMyLink($actualButton['alertText'], $actualLine);
                    $icon .= 'onClick="if(confirm(\''.addslashes($alertText).'\')){'.$script.';} else {return false;};" ';
                } else {
                    $icon .= 'onClick="'.$script.'" ';
                }
                if ($this->_checkTypeOfActionIcon($this->actionButtons, 'preview') === true) {
					$icon .= ' onMouseOver="$(\'identifierDetailFrame\').setValue(\'\'); '
						.'$(\'return_previsualise\').style.display=\'none\';" ';
				}
                $icon .= ' title="'.$actualButton['tooltip'].'"';
            } else {
                $href = $this->_buildMyLink($actualButton['href'], $actualLine, $listKey);
                $icon .= '<a href="'.$href.'" title="'.$actualButton['tooltip'].'"';
                
                //If javascript alert box
                if(isset($actualButton['alertText']) && !empty($actualButton['alertText'])) {
                    $alertText = $this->_buildMyLink($actualButton['alertText'], $actualLine);
                    $icon .= ' onClick="return(confirm(\''.addslashes($alertText).'\'));" ';
                    if ($this->_checkTypeOfActionIcon($this->actionButtons, 'preview') === true) {
						$icon .= ' onMouseOver="$(\'identifierDetailFrame\').setValue(\'\'); '
							.'$(\'return_previsualise\').style.display=\'none\';" ';
					}
                }
            }
        }
        //Style
        $showLabel = true;
        if (isset($actualButton['class']))   { 
            //$icon .= ' class="'.$actualButton['class'].'">';
            $icon .= '>';
            if ($actualButton['class'] == 'change') {
                $icon .= '<i class="fa fa-edit fa-2x" title="' . _MODIFY . '"></i>';
                $showLabel = false;
            } elseif($actualButton['class'] == 'delete') {
                $icon .= '<i class="fa fa-remove fa-2x" title="' . _DELETE . '"></i>';
                $showLabel = false;
            } elseif($actualButton['class'] == 'suspend') {
                $icon .= '<i class="fa fa-pause fa-2x" title="' . _SUSPEND . '"></i>';
                $showLabel = false;
            } elseif($actualButton['class'] == 'authorize') {
                $icon .= '<i class="fa fa-check fa-2x" title="' . _AUTHORIZE . '"></i>';
                $showLabel = false;
            }  elseif($actualButton['class'] == 'view') {
                $icon .= '<i class="fa fa-eye fa-2x" title="' . _VIEW . '"></i>';
                $showLabel = false;
            }  elseif($actualButton['class'] == 'use') {
                $icon .= '<i class="fa fa-dot-circle-o fa-2x" title="' . _USE . '"></i>';
                $showLabel = false;
            }
        } else { 
            $icon .= '>'; 
        }
        //Image
        if(isset($actualButton['icon'])) {
           $icon .= '<i class="fa fa-'.$actualButton['icon'].' fa-2x" title="'.$actualButton['tooltip'].'"></i>'; 
        }
        //Label
        if (isset($actualButton['label']) && $showLabel) { 
           $icon .= '&nbsp;'.$actualButton['label']; 
        }
        $icon .= '</a>';
                    
        return $icon;
    }
	
	protected function _checkTypeOfActionIcon($actionButtons, $type) {
		$isThisType = false;
		for($button = 0; $button < count($actionButtons); $button++) {
			if($actionButtons[$button]['type'] == $type) {
				$isThisType = true;
				break;
			}
		}
		 
		return $isThisType;
	}
    
    protected function _createContent($resultArray, $listColumn, $listKey) {
        $content = $lineCss = '';
        
        $content .= '<tbody>';
        
        //Loop into the set of records
        for($theLine = $this->start; $theLine < $this->end ; $theLine++) {
            //Init
            $href = '';
            $resultTheLine = array();
            
            //Simplify some values
            $resultTheLine = $resultArray[$theLine];
            
            //Get the ListKey value
            $keyValue = '';
            for($i= 0; $i <= count($resultTheLine); $i++ ) {
                if($resultTheLine[$i]['column'] == $listKey) {
                    $keyValue = $resultTheLine[$i]['value'];
                }
            }
            
            //Check if line is disable
            $lineIsDisabled = $this->_checkDisabledRules($this->params['disabledRules'], $resultTheLine);
            
            //Alternate css for each line
            if($lineCss == '') $lineCss = 'col'; elseif($lineCss == 'col') $lineCss = '';
            if ($lineIsDisabled === true && $this->haveAction) $content .= '<tr class="disabled">';
            else $content .= '<tr class="'.$lineCss.'">';
            
            
            //Show sublist toggle icon
            if($this->params['bool_showSublist'] && !empty($this->params['sublistUrl'])){
                if ($lineIsDisabled === true) {
                    $content .= '<td width="1%"><div align="center"><i class="fa fa-minus fa-2x" style="vertical-align: middle;"></i></div></td>';
                } else {
                    $sublist = $this->_buildMyLink($this->params['sublistUrl'], $resultTheLine, $listKey);
                    $content .= '<td width="1%"><div align="center"><a href="javascript://" onclick="loadValueInDiv(\''
                            .$keyValue.'\',\''.$sublist.'\')" title="'._TOGGLE
                            .'"><i class="fa fa-minus fa-2x" style="vertical-align: middle;"></i></a></div></td>';
                }
            }
            
            //If disable or checkbox or radio button
            if ($lineIsDisabled === true && ($this->params['bool_checkBox'] === true|| $this->params['bool_radioButton'] === true)) {
                $content .= '<td width="1%"><div align="center"><i class="fa fa-lock fa-2x" style="vertical-align: middle;" title="'._LOCKED.'"></i></div></td>';
            } else if($this->params['bool_checkBox'] === true) {
                $content .= '<td width="1%"><div align="center"><input type="checkbox" name="field[]" id="field" class="check" value="'
                    .$keyValue.'" /></div></td>';
            } else if($this->params['bool_radioButton'] === true) {
                if($_SESSION['stockCheckbox'] != null){
                $key = in_array($keyValue, $_SESSION['stockCheckbox']);
                if($key==true){
                  $content .= '<td width="1%"><div align="center"><input type="Checkbox" checked="yes" name="field[]" id="field" class="check" onclick="stockCheckbox(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=multiLink\','.$keyValue.');" value="'
                    .$keyValue.'" /></div></td>';  
                }else{

                    $content .= '<td width="1%"><div align="center"><input type="Checkbox" name="field[]" id="field" class="check" onclick="stockCheckbox(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=multiLink\','.$keyValue.');" value="'
                    .$keyValue.'" /></div></td>';
                    }
                }else{
                    $content .= '<td width="1%"><div align="center"><input type="Checkbox" name="field[]" id="field" class="check" onclick="stockCheckbox(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=multiLink\','.$keyValue.');" value="'
                    .$keyValue.'" /></div></td>';
                }

            }
            
            //Show document icon
            if($this->params['bool_showIconDocument']){
                $href = $this->_buildMyLink($this->params['viewDocumentLink'], $resultTheLine, $listKey);
                $content .= '<td width="1%"><div align="center"><a href="'.$href.'" target="_blank" title="'
                    ._VIEW_DOC.'"><i class="fa fa-download fa-2x"></i></a></div></td>';
            }
            
            //Show the rows (loop into columns)
            for($column = 0;$column < count($listColumn); $column++)
            {
                //If show column
                if($resultTheLine[$column]['show']==true) {
                    $class ='';
                    
                    //Column content
                    $columnValue = $this->_highlightWords($resultTheLine[$column]['value'], $this->whatSearch);
                    
                    //CSS
                    if (isset($resultTheLine[$column]['class']) && 
                        !empty($resultTheLine[$column]['class'])
                    ) {
                        $class ='class="'.$resultTheLine[$column]['class'].'"';
                    }
                    
                    //Different background on ordered column
                    (strpos($this->orderField, $resultTheLine[$column]['order']) !== false)? 
                        $columnStyle = ' style="background-image: url(static.php?filename=black_0.1.png);"' : $columnStyle = '';
                
                    //If there is action on line click
                    if($this->params['bool_actionOnLineClick'] && 
                        isset($this->params['defaultAction']) && 
                        !empty($this->params['defaultAction']) && 
                        $lineIsDisabled === false
                    ) {
                        $content .= '<td'.$columnStyle.' onmouseover="this.style.cursor=\'pointer\';" '
                            .'onClick="validForm( \'page\', \''.$keyValue.'\', \''
                            .$this->params['defaultAction'].'\');" width="'.$resultTheLine[$column]['size'].'%" '
                            .$class.'><div align="'.$resultTheLine[$column]['align'].'">'
                            .$columnValue.'</div></td>';
                    } else {
                        $content .= '<td'.$columnStyle.' width="'.$resultTheLine[$column]['size'].'%" '
                            .$class.'><div align="'.$resultTheLine[$column]['align'].'">'
                            .$columnValue.'</div></td>';
                    }
                }
            }
            
            //Show action buttons
            for($button = 0; $button < count($this->actionButtons); $button++) {
                $actionIsDisabled = $this->_checkDisabledRules($this->actionButtons[$button]['disabledRules'], $resultTheLine);
                if ($actionIsDisabled) {
                    $content .= '<td width="1%">&nbsp;</td>';
                } else {
                    $content .= '<td width="1%" nowrap><div style="font-size:10px;">';
                    //Chceck type of action
                    if(!isset($this->actionButtons[$button]['type']) or $this->actionButtons[$button]['type'] == 'standard') { //Standard icon
                    
                       $content .= $this->_createActionIcon($resultTheLine, $this->actionButtons[$button], $listKey);
                    } else if($this->actionButtons[$button]['type'] == 'preview') { //View icon
                    
                       $content .= $this->_createActionIcon($resultTheLine, $this->actionButtons[$button], $listKey);
                    } else if($this->actionButtons[$button]['type'] == 'switch') {  //Switch icon
                    
                        //Switch rules to be ON
                        $switchIsOn = $this->_checkDisabledRules($this->actionButtons[$button]['switchRules'], $resultTheLine);
                        //
                        if(isset($this->actionButtons[$button]['on']) && $switchIsOn) { //Switch ON
                            $content .= $this->_createActionIcon($resultTheLine,$this->actionButtons[$button]['on'], $listKey);
                        } else if(isset($this->actionButtons[$button]['off'])) { //Switch OFF
                            $content .= $this->_createActionIcon($resultTheLine,$this->actionButtons[$button]['off'], $listKey);
                        }
                    }
                    $content .= '</div></td>';
                }
            }
            
            //Show details button
            if($this->params['bool_showIconDetails']) {
                $href = $this->_buildMyLink($this->params['viewDetailsLink'], $resultTheLine, $listKey);
                $content .= '<td width="1%"><div align="center"><a href="javascript://" onClick="javascript:window.top.location=\''
                    .$href.'\'; return false;" title="'._DETAILS.'"><i class="fa fa-info-circle fa-2x" title="' . _DETAILS . '"></i></a></div></td>';
            }
            
            //End of line
            $content .= '</tr>';
            
            //Show sublist content (in another hidden line)
            if($this->params['bool_showSublist'] && !empty($this->params['sublistUrl'])){
                $content .= '<tr class="" id="subList_'.$keyValue.'" name="subList_'
                    .$keyValue.'" style="display: none;"><td colspan="'
                    .$this->countTd.'" style="background-color: white;"><div id="div_'
                    .$keyValue.'" class="more_ressources"></div></td></tr>';
            }
        }
        $content .= '</tbody>';
        
        return  $content;
    }
    
    public function showList($resultArray, $parameters=array(), $listKey='', $currentBasket=array()) {

        //Put in different arrays: label, show, sort of columns
        if (count($resultArray) > 0 && isset($resultArray[0])) {
            $listColumn = array();
            $showColumn = array();
            $sortColumn = array();
            for ($j=0;$j<count($resultArray[0]);$j++) {
                array_push($listColumn,$resultArray[0][$j]["label"]);
                array_push($showColumn,$resultArray[0][$j]["show"]);
                array_push($sortColumn,$resultArray[0][$j]["order"]);
            }
        }
        
        //Default values
        if (!isset($parameters['bool_showToolbar'])){ $parameters['bool_showToolbar'] = true; }
        if (!isset($parameters['bool_bigPageTitle'])){ $parameters['bool_bigPageTitle'] = true; }
        if (!isset($parameters['bool_checkBox'])){ $parameters['bool_checkBox']= false; }
        if (!isset($parameters['bool_radioButton'])){ $parameters['bool_radioButton']= false; }
        if (!isset($parameters['bool_showSublist'])){ $parameters['bool_showSublist']= false; }
        if (!isset($parameters['bool_showIconDocument'])){ $parameters['bool_showIconDocument']= false; }
        if (!isset($parameters['bool_sortColumn'])){ $parameters['bool_sortColumn']= true; }
        if (!isset($parameters['bool_showIconDetails'])){ $parameters['bool_showIconDetails']= false; }
        if (!isset($parameters['bool_showAddButton'])){ $parameters['bool_showAddButton']= false; }
        if (!isset($parameters['bool_actionOnLineClick'])){ $parameters['bool_actionOnLineClick'] = false; }
        if (!isset($parameters['bool_pageInModule'])){ $parameters['bool_pageInModule']= true; }
        if (!isset($parameters['bool_showSearchTools'])){ $parameters['bool_showSearchTools']= false; }
        if (!isset($parameters['bool_showSearchBox'])){ $parameters['bool_showSearchBox']= true; }
        if (!isset($parameters['bool_showSmallToolbar'])){ $parameters['bool_showSmallToolbar']= false; }
        if (!isset($parameters['bool_showBottomToolbar'])){ $parameters['bool_showBottomToolbar']= true; }
        if (!isset($parameters['bool_showTemplateDefaultList'])){ $parameters['bool_showTemplateDefaultList']= false; }
        if (!isset($parameters['bool_standaloneForm'])){ $parameters['bool_standaloneForm']= false; }
        if (!isset($parameters['bool_modeReturn'])){ $parameters['bool_modeReturn'] = true; }
        if (!isset($parameters['divListId'])){ $parameters['divListId']= 'divList'; }
        if (!isset($parameters['searchBoxAutoCompletionParamName'])){ $parameters['searchBoxAutoCompletionParamName']= 'what'; }
        if (!isset($parameters['searchBoxAutoCompletionMinChars'])){ $parameters['searchBoxAutoCompletionMinChars']= 1; }
        if (!isset($parameters['searchBoxAutoCompletionUpdate'])){ $parameters['searchBoxAutoCompletionUpdate']= false; }
        if (!isset($parameters['viewDocumentLink'])){ $parameters['viewDocumentLink'] = $_SESSION['config']['businessappurl']
            .'index.php?display=true&dir=indexing_searching&page=view_resource_controler';}
        if (!isset($parameters['viewDetailsLink'])){ $parameters['viewDetailsLink'] = $_SESSION['config']['businessappurl']
            .'index.php?page=details&dir=indexing_searching';}
        if (!isset($parameters['bool_changeLinesToShow'])){ $parameters['bool_changeLinesToShow'] =  true;}
        if (!isset($parameters['linesToShow'])){ $parameters['linesToShow'] =  $_SESSION['config']['nblinetoshow']; }
        if (!isset($parameters['listCss']) || empty($parameters['listCss'])){ $parameters['listCss'] = 'listing spec'; }
        if (!isset($parameters['addButtonLabel']) || empty($parameters['addButtonLabel'])){ $parameters['addButtonLabel'] = _ADD; }
        if (!isset($parameters['formId'])){ $parameters['formId']= 'formList'; $parameters['formName']= 'formList'; } else { $parameters['formName']= $parameters['formId']; }
        if (!isset($parameters['formAction'])){ $parameters['formAction']= '#'; }
        if (!isset($parameters['formMethod'])){ $parameters['formMethod']= 'POST'; }
        if (!isset($parameters['formClass'])){ $parameters['formClass']= 'forms'; }
        if (!isset($parameters['processInstructions'])){ $parameters['processInstructions'] = _CLICK_LINE_TO_PROCESS;}
        
        //Reset
        $grid = $gridContent = '';
        
        //Init some global vars
        $this->params =  array();
        $this->params = $parameters;
        $this->actionButtons =  array();
        $this->actionButtons =  $parameters['actionIcons'];
        $this->divListId = $parameters['divListId'];
        $this->modeReturn = ($parameters['bool_modeReturn'] === true)? 'true' : 'false';
        if(isset($parameters['height']) && !empty($parameters['height'])) $this->height = $parameters['height'];
        $this->formId = $parameters['formId'];
        $this->haveAction = false;
        $this->countResult = count($resultArray);
        if(count($currentBasket) > 0) $this->currentBasket =  $currentBasket;
        if ((isset($this->params['collId']) && !empty($this->params['collId']))) {
            $this->collId = $this->params['collId'];
        } else if ((isset($currentBasket['coll_id']) && !empty($currentBasket['coll_id']))) {
            $this->collId = $currentBasket['coll_id'];
        }
        
        //Selected template or default template
        if(empty($this->template)) {
           
            if(isset($this->params['defaultTemplate']) && !empty($this->params['defaultTemplate']))  {
                $this->template = $this->params['defaultTemplate']; 
            } 
        }
        //Action par defaut
        if(isset($parameters['defaultAction']) && !empty($parameters['defaultAction'])) {
            $this->params['bool_actionOnLineClick'] = true;
            $this->haveAction = true;
        }
        
        //Standalone form
        if ($parameters['bool_standaloneForm'] === true) {
             $this->params['bool_actionOnLineClick'] = false;
        }
        
        //Page picto
        if(isset($parameters['pagePicto'])) $picto_path = '<i class="fa fa-' . $parameters['pagePicto'] . ' fa-2x"></i> ';
        
        //Top anchor
        $grid .= '<div id="topOfTheList"></div>';
        
        //Check ih there is a preview button before show preview div
		if ($this->_checkTypeOfActionIcon($this->actionButtons, 'preview') === true) {
			$grid .= $this->_createPreviewDiv();
        }
		
        //Page title
        if(isset($parameters['pageTitle'])) {           
            if($parameters['bool_bigPageTitle'])
                $grid .= '<h1>'.$picto_path.$parameters['pageTitle'].'</h1>';
            else
                $grid .=  '<b>'.$picto_path.$parameters['pageTitle'].'</b><br />';
        }
           
        //Actions list
        if(count($currentBasket) > 0) $this->_createActionsList($currentBasket);
        
        //Build page link
        $this->link = $this->_buildPageLink();
        // $grid .=  'Link = '. $this->link.''; //debug
        
        //Search tools
        $grid .= $this->_displaySearchTools();
        
        //Toolbar
        if ($this->params['bool_showSmallToolbar'] === false) {
            $grid .= $this->_createToolbar($resultArray[0]);
        }
        
        //Show bottom toolbar
        if (
            $this->params['bool_showSmallToolbar'] === false 
            && $this->params['bool_showBottomToolbar'] === true
            && $this->params['linesToShow'] > $_SESSION['config']['nblinetoshow']
            && (
                $this->countResult > $this->params['linesToShow'] 
                || $this->countResult > $_SESSION['config']['nblinetoshow']
                )
            ) 
        {
            $bottomToolbar = $this->_createBottomToolbar($resultArray[0]);
        }
        
        //If there some results    
        if (count($resultArray) > 0 || $this->params['bool_showAddButton']) {
            
            //Need a form?
            $this->withForm = false;
            if(
                $this->params['bool_checkBox'] === true ||
                $this->params['bool_radioButton'] === true ||
                count($parameters['actions'] > 0) ||
                count($parameters['buttons'] > 0) ||
                !empty($parameters['defaultAction'])
                )
            {
                //Need a form!
                $this->withForm = true;
                $B_form = $E_form = '';

                //Extra javascript to handle form
                $grid.= $this->_createExtraJavascript();
                
                //Build form
                $B_form .= '<form name="'.$parameters['formName'].'" id="'
                        .$this->formId.'" action="'.$parameters['formAction'].'" method="'
                        .$parameters['formMethod'].'" class="'.$parameters['formClass'].'">';
                $B_form .='<input type="hidden" value=""/>';
                
                //Get hidden fields
                $gridContent .= $this->_createHiddenFields();
                
                //Actions (list or buttons)
                $E_form .= $this->_displayActionsList();
                 
                //End form
                $E_form .= '</form>';
            }
            
            //Height
            $B_height = $E_height = '';
             if(isset($parameters['listHeight']) && !empty($parameters['listHeight'])) {
                $B_height .= '<div style="height:'.$parameters['listHeight'].';overflow-x: hidden;overflow-y: auto;"><div style="height:97%;">';
                // $B_height .= '<div class="fixed-table-container"><div class="header-height"></div><div class="fixed-table-container-inner">';
                $E_height .= '</div></div>';
            }
            
            //Template mode
            if (!empty($this->template) && $this->template <> 'none') {
                //Build the grid from template
                $gridContent .= $this->_buildTemplate($_SESSION['html_templates'][$this->template]['PATH'], $resultArray, $listKey);
                
                //Build the list
                $grid .= $B_form . $B_height . $gridContent . $E_height . $E_form;
                
            //Normal mode
            } else {
                //Header
                $gridContent .= $this->_createHeader($resultArray[0], $listColumn, $showColumn, $sortColumn);
                
                //Content
                $gridContent.= $this->_createContent($resultArray, $listColumn, $listKey) ;
            
                //Build the list
                (!empty($this->params['listCss']))? $listCss = 'class="'.$this->params['listCss'].'"' : $listCss = '';
                $grid .= $B_form . $B_height . '<table cellspacing="0" border="0" cellpadding="0" align="center" '
                        .$listCss.'>' . $gridContent . '</table>' . $E_height . $E_form. $bottomToolbar ;
            }
            
            //Process instructions
            if($this->params['bool_actionOnLineClick'] === true) $grid .= '<em>'.$parameters['processInstructions'].'</em>';
        }
        
        //Show the list
        if ($this->params['bool_modeReturn'] === true){
            return $this->_parse($grid);
        } else {
            echo $this->_parse($grid);
        }
    }
    
    protected function _parse($text) {
        //...
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        //
        $text = str_replace("\n", "\\n ", $text);
        return $text;
    }
    
    public function loadList($target, $showLoading=true, $divListId='divList', $returnMode = 'true', $init='true') {
        $list = "\n";
        $loading ='';
        
        //Reset filters
        $this->_resetFilter();
        
        //Reset html template list url
        $this->_resetUrlTemplates();
        
        //Create javascript load list function
        $list .= '<script type="text/javascript">loadList(\''.$target.'&display=true\', \''.$divListId.'\', '.$returnMode.', '.$init.');</script>';
        
        //Show loading image?
        if ($showLoading === true) {
            $loading = '<i class="fa fa-spinner fa-2x"></i>';
        }
        
        //Content div
        $list .= '<div id="'.$divListId.'" name="'.$divListId.'">'.$loading.'</div>';
        
        return $list;
        
    }
    public function loadList2($target, $showLoading=true, $divListId='divList', $returnMode = 'true', $init='true') {
        $list = "\n";
        $loading ='';
        //Reset filters
        $this->_resetFilter2();
        
        //Reset html template list url
        $this->_resetUrlTemplates();
        
        //Create javascript load list function
        $list .= '<script type="text/javascript">loadList2(\''.$target.'&display=true\', \''.$divListId.'\', '.$returnMode.', '.$init.');</script>';
        
        //Show loading image?
        if ($showLoading === true) {
            $loading = '<i class="fa fa-spinner fa-2x"></i>';
        }
        
        //Content div
        $list .= '<div id="'.$divListId.'" name="'.$divListId.'">'.$loading.'</div>';
        
        return $list;
        
    }
    
    public function debug($viewAll=true) {
    
        $debug .='<br/><pre>';
        $debug .='<b>Request:</b><br />';
        $debug .= print_r($_REQUEST, true);
        $debug .='<br/><b>Return mode:</b> '.$this->modeReturn;
        $debug .='<br/><b>Link:</b> '.$this->link.'<br/>';
        $debug .='<b>Have action:</b> '.$this->haveAction.'<br />';
        $debug .='<b>With form:</b> '.$this->withForm.'<br />';
        $debug .='<b>Selected template:</b> '.$this->template.'<br />';
        $debug .='<b>Parameters:</b><br />';
        $debug .= print_r($this->params, true);
        if ($viewAll) {
            $debug .='<br/><b>Current basket:</b><br />';
            $debug .= print_r($this->currentBasket, true);
            // $debug .='<br/><b>Lists:</b></br>';
            // $debug .= print_r($_SESSION['lists'], true);     
            $debug .='<br/><b>Filters:</b><br />';
            $debug .= print_r($_SESSION['filters'], true);
            $debug .='<br/><b>Filter clause:</b> '.$this->getFilters().'<br />';
            $debug .='<b>Templates:</b><br />';
            $debug .= print_r($_SESSION['html_templates'], true);
        }
        $debug .='</pre>';
        if ($this->params['bool_modeReturn']) {

            //Fix some json line breaks issues
            $debug = str_replace(chr(10), "", $debug);
            $debug = str_replace(chr(13), "", $debug);
            return $debug;
        } else {
            echo $debug;
        }
    }
    
    public function getLink() {
        return $this->link;
    }
    
    public function getStart() {
        return $this->start;
    } 
    
    public function getOrder() {
        return $this->order;
    }
    
    public function getOrderField() {
        return $this->orderField;
    }
    
    public function setOrder($order='desc') {
        $this->order = $order;
    }
    
    public function setOrderField($field) {
        $this->orderField = $field;
    }
    
    public function getWhatSearch() {
        return $this->whatSearch;
    }
    
    public function getFilters() {
        $filtersClause = '';
        $filtersArray = array();
        
        foreach ($_SESSION['filters'] as $key => $val) {
            if (!empty($_SESSION['filters'][$key]['CLAUSE'])) {
                array_push($filtersArray, $_SESSION['filters'][$key]['CLAUSE']);
            }
        }
        if (count($filtersArray) > 0) $filtersClause .= '('.implode(' and ', $filtersArray).')'; //Build
         
        return $filtersClause;
    }
    
    public function getTemplate() {
            return $this->template;
    }
    
    public function setTemplate($template) {
        $this->template = $template;        
    }
    
    public function setCollection($collId) {
        $this->collId = $collId;        
    }
}
