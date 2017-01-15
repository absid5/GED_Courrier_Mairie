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
* @brief   Module Basket :  Administration of the baskets
*
* Forms and process to add, modify and delete baskets
*
* @file
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/


abstract class admin_basket_Abstract extends Database
{
   /**
    * Loads data from the groupbasket table in the session ( $_SESSION['m_admin']['basket']['groups']  array)
    *
    * @param  $id  string  basket identifier
    */
    protected function load_groupbasket($id)
    {
        $_SESSION['m_admin']['basket']['groups'] = array();
        $i =0;
        $default_action_list = '';
        $db = new Database();

        $stmt = $db->query("select gb.group_id,  gb.sequence, gb.result_page, gb.list_lock_clause, gb.sublist_lock_clause, u.group_desc from "
            .$_SESSION['tablename']['bask_groupbasket']." gb, ".$_SESSION['tablename']['usergroups']
            ." u where gb.basket_id = ? and gb.group_id = u.group_id order by u.group_desc",array($id));
        while($line2 = $stmt->fetchObject())
        {
            $stmt2 = $db->query("select agb.group_id, agb.basket_id, agb.id_action, agb.where_clause,  ba.label_action, agb.used_in_basketlist as mass, agb.used_in_action_page as page, agb.default_action_list from ".$_SESSION['tablename']['bask_actions_groupbaskets']." agb, ".$_SESSION['tablename']['actions']." ba
            where ba.id = agb.id_action and agb.group_id = ? and agb.basket_id = ?",array($line2->group_id,$id) );
            //$basketlist = $line2->redirect_basketlist;
            //$grouplist = $line2->redirect_grouplist;

            $actions = array();
            while($res = $stmt2->fetchObject())
            {
                if($res->default_action_list == 'Y')
                {
                    $default_action_list = $res->id_action;
                }
                else
                {
                    array_push($actions, array('ID_ACTION' => $res->id_action, 'LABEL_ACTION' => functions::show_string($res->label_action), 'WHERE' => functions::show_string($res->where_clause), 'MASS_USE' => $res->mass, 'PAGE_USE' => $res->page));
                }
            }

            $_SESSION['m_admin']['basket']['groups'][$i] = array(
                "GROUP_ID"          =>  $line2->group_id , 
                "GROUP_LABEL"       =>  functions::show_string($line2->group_desc), 
                "SEQUENCE"          =>  $line2->sequence,  
                "RESULT_PAGE"       =>  $line2->result_page, 
                "LOCK_LIST"         =>  $line2->list_lock_clause, 
                "LOCK_SUBLIST"      =>  $line2->sublist_lock_clause, 
                "DEFAULT_ACTION"    =>  $default_action_list,  
                "ACTIONS"           =>  $actions);
            $i++;
        }

        $_SESSION['m_admin']['groupbasket'] = false ;
    }
    
    public function isAnActionOfMyBasketCollection($actionPage, $collId)
    {
        $cpt = count($_SESSION['actions_pages']);
        for ($i=0;$i<$cpt;$i++) {
            if ($actionPage == $_SESSION['actions_pages'][$i]['ID']) {
                for ($j=0;$j<count($_SESSION['actions_pages'][$i]['COLLECTIONS']);$j++) {
                    if ($_SESSION['actions_pages'][$i]['COLLECTIONS'][$j] == $collId
                        || $_SESSION['actions_pages'][$i]['COLLECTIONS'][$j] == '*'
                    ) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    public function isABasketPageOfMyBasketCollection($basketPage, $collId)
    {
        $cpt = count($_SESSION['basket_page']);
        for ($i=0;$i<$cpt;$i++) {
            if ($basketPage == $_SESSION['basket_page'][$i]['ID']) {
                for ($j=0;$j<count($_SESSION['basket_page'][$i]['COLLECTIONS']);$j++) {
                    if ($_SESSION['basket_page'][$i]['COLLECTIONS'][$j] == $collId 
                        || $_SESSION['basket_page'][$i]['COLLECTIONS'][$j] == '*'
                    ) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
    * Form for the management of the basket : used to add a new basket or to modify one
    *
    * @param   $mode  string "up" to modify a basket and "add" to add a new one
    * @param   $id  string Basket identifier (empty by default), must be set in "up" mode
    */
    public function formbasket($mode,$id = "")
    {
        $state = true;
        $core_tools = new core_tools();
        
        $db = new Database();
        
        if ($mode == "add") {
            $_SESSION['m_admin']['basket']['coll_id'] = $_SESSION['collections'][0]['id'];
        }
        
        // If mode "Up", Loading the informations of the basket in session
        if($mode == "up")
        {
            echo $core_tools->execute_modules_services($_SESSION['modules_services'], 'basket_up.php', "include");
            echo $core_tools->execute_app_services($_SESSION['app_services'], 'basket_up.php', "include");
            $_SESSION['m_admin']['mode'] = "up";
            if(empty($_SESSION['error']))
            {
                $stmt = $db->query("select * from ".$_SESSION['tablename']['bask_baskets']." where basket_id = ? and enabled= 'Y'",array($id));
                if($stmt->rowCount() == 0)
                {
                    $_SESSION['error'] = _BASKET_MISSING;
                    $state = false;
                }
                else
                {
                    $_SESSION['m_admin']['basket']['basketId'] = functions::show_string($id);
                    $line = $stmt->fetchObject();
                    $_SESSION['m_admin']['basket']['desc'] = functions::show_string($line->basket_desc);
                    $_SESSION['m_admin']['basket']['name'] = functions::show_string($line->basket_name);
                    $_SESSION['m_admin']['basket']['clause'] = functions::show_string($line->basket_clause);
                    $_SESSION['m_admin']['basket']['is_generic'] = functions::show_string($line->is_generic);
                    $_SESSION['m_admin']['basket']['is_visible'] = functions::show_string($line->is_visible);
                    $_SESSION['m_admin']['basket']['is_folder_basket'] = functions::show_string($line->is_folder_basket);
                    $_SESSION['m_admin']['basket']['coll_id'] = functions::show_string($line->coll_id);
                    $_SESSION['m_admin']['basket']['flag_notif'] = functions::show_string($line->flag_notif);
                    if (! isset($_SESSION['m_admin']['load_groupbasket']) || $_SESSION['m_admin']['load_groupbasket'] == true)
                    {
                        $this->load_groupbasket($id);
                        $_SESSION['m_admin']['groupbasket'] = false ;
                        $_SESSION['service_tag'] = 'load_basket_session';
                        echo $core_tools->execute_modules_services($_SESSION['modules_services'], 'load_groupbasket', "include");
                        echo $core_tools->execute_app_services($_SESSION['app_services'], 'load_groupbasket', "include");
                        $_SESSION['service_tag'] = '';
                    }
                }
            }
        }
        
        // The title is different according the mode
        if($mode == "add")
        {
            echo $core_tools->execute_modules_services($_SESSION['modules_services'], 'basket_add.php', "include");
            echo $core_tools->execute_app_services($_SESSION['app_services'], 'basket_add.php', "include");
            echo '<h1><i class="fa fa-inbox fa-2x" title="" /></i> '._BASKET_ADDITION.'</h1>';
        }
        elseif($mode == "up")
        {
            echo '<h1><i class="fa fa-inbox fa-2x" title="" /></i> '._BASKET_MODIFICATION.'</h1>';
        }
        ?>
        <div id="inner_content" class="clearfix">
            <div id="add_box_diff_list" style="width:25%;">
                <div class="block" style="height:400px;">
                <iframe name="groupbasket_form" id="groupbasket_form" src="<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=basket&page=groupbasket_form";?>"  frameborder="0" class="frameform2" width="280px" style="width:100%;"></iframe>
                </div>
            <div class="block_end">&nbsp;</div>
            </div>

            <?php
            if($state == false)
            {
                    echo "<br /><br /><br /><br />"._BASKET.' '._UNKNOWN."<br /><br /><br /><br />";
            }
            else
            {
            ?>
            <div class="block" style="float:left;width:70%;height:400px;overflow:auto;">
                <table width="100%">
                    <tr>
                        <td>
                            <form name="formbasket" id="formbasket" method="post" style="margin:auto;" action="<?php if($mode == "up") { echo $_SESSION['config']['businessappurl']."index.php?display=true&module=basket&page=basket_up_db"; } elseif($mode == "add") { echo $_SESSION['config']['businessappurl']."index.php?display=true&module=basket&page=basket_add_db"; } ?>" class="forms addforms">
                                <input type="hidden" name="display"  value="true" />
                                <input type="hidden" name="module"  value="basket" />
                                <?php
                                if ($mode == "up") {
                                     $disabled = ' disabled="disabled" ';
                                     ?>
                                    <input type="hidden" name="page"  value="basket_up_db" />
                                    <?php
                                } elseif($mode == "add") {
                                    ?>
                                    <input type="hidden" name="page"  value="basket_add_db" />
                                    <?php
                                }
                                ?>
                                <input type="hidden" name="order" id="order" value="<?php if(isset($_REQUEST['order'])){functions::xecho($_REQUEST['order']);}?>" />
                                <input type="hidden" name="order_field" id="order_field" value="<?php if(isset($_REQUEST['order_field'])){functions::xecho($_REQUEST['order_field']);}?>" />
                                <input type="hidden" name="what" id="what" value="<?php if(isset($_REQUEST['what'])){functions::xecho($_REQUEST['what']);}?>" />
                                <input type="hidden" name="start" id="start" value="<?php if(isset($_REQUEST['start'])){functions::xecho($_REQUEST['start']);}?>" />

                                <p>
                                    <label><?php echo _COLLECTION;?> : </label>
                                    <select name="collection" id="collection" <?php echo  $disabled;?> onchange="updateCollection(this.options[this.selectedIndex].value, 'true');">
                                        <option value=""><?php echo _CHOOSE_COLLECTION;?></option>
                                        <?php
                                        for($i=0; $i<count($_SESSION['collections']);$i++) {
                                            ?>
                                            <option value="<?php functions::xecho($_SESSION['collections'][$i]['id']);?>" <?php if(count($_SESSION['collections']) == 1 || $_SESSION['collections'][$i]['id'] == $_SESSION['m_admin']['basket']['coll_id']) { echo 'selected="selected"';}?>><?php functions::xecho($_SESSION['collections'][$i]['label']);?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </p>

                                <p>
                                    <label><?php echo _ID;?> : </label>
                                    <input name="basketId" id="basketId" type="text" value="<?php functions::xecho($_SESSION['m_admin']['basket']['basketId']);?>" <?php if($mode == "up") { echo 'readonly="readonly" class="readonly"';} ?> />
                                <input type="hidden"  name="id" value="<?php functions::xecho($id);?>" />
                                </p>
                                <p>
                                    <label><?php echo _BASKET;?> : </label>
                                    <input name="basketname"  type="text" id="basketname" value="<?php functions::xecho($_SESSION['m_admin']['basket']['name']);?>" />
                                </p>
                                <p>
                                    <label><?php echo _DESC;?> : </label>
                                    <textarea  cols="30" rows="4"  name="basketdesc"  style="width:200px;" id="basketdesc" ><?php functions::xecho($_SESSION['m_admin']['basket']['desc']);?></textarea>
                                </p>
                                <?php if($_SESSION['m_admin']['basket']['is_generic'] == 'Y')
                                {
                                    ?>
                                    <p>
                                        <em><?php echo _SYSTEM_BASKET_MESSAGE;?>.</em>
                                    </p>
                                <?php } ?>
                                <p>
                                    <label><?php echo _BASKET_VIEW;?> : </label>
                                    <textarea  cols="30" rows="4" style="width:415px;" name="basketclause" id="basketclause" ><?php functions::xecho($_SESSION['m_admin']['basket']['clause']);?></textarea>
                                </p>
                                 <?php
                                    if ($_SESSION['m_admin']['basket']['is_visible'] === 'Y' || $_SESSION['m_admin']['basket']['is_visible']=== '') {
                                            $css='color:rgb(102, 102, 102);cursor:pointer;';
                                        } else {
                                           $css='color:#009dc5;cursor:pointer;';
                                        }
                                ?>
                                <p style="display:none;">
                                    <label><?php echo _BASKET_VISIBLE_ONLY_ON_SEARCH;?> : </label>


                                    <input type='checkbox' name="is_visible_only_on_search" id="is_visible_only_on_search" value="N" <?php
                                        if ($_SESSION['m_admin']['basket']['is_visible'] === 'N') {
                                            echo 'checked="checked"';
                                        }
                                    ?> onchange="updateIsVisible();"/>
                                    <input type='hidden' name="is_visible" id="is_visible" <?php
                                        if ($_SESSION['m_admin']['basket']['is_visible'] === 'Y' || $_SESSION['m_admin']['basket']['is_visible']=== '') {
                                            echo 'value="Y"';
                                        } else {
                                            echo 'value="N"';
                                        }
                                    ?>/>
                                </p>
                                <script language="javascript">
                                    function updateIsVisible()
                                    {
                                        if ($(is_visible_only_on_search).checked == true) {
                                            $(is_visible).value = 'N';
                                            $(is_visible_only_on_search_icon).style.color = '#009dc5';
                                        } else {
                                            $(is_visible).value = 'Y';
                                            $(is_visible_only_on_search_icon).style.color = 'rgb(102, 102, 102)';
                                        }
                                    }
                                </script>
                        
                                <!--<p>
                                    <label><?php echo _BASKET_VISIBLE;?> : </label>
                                    <input type='checkbox' name="is_visible" id="is_visible" value="Y" <?php if ($_SESSION['m_admin']['basket']['is_visible'] === 'Y' || $_SESSION['m_admin']['basket']['is_visible']=== '') echo 'checked="checked"';?>/>
                                </p>-->
                                <?php if ($core_tools->is_module_loaded('folder')) { 

                                    if ($_SESSION['m_admin']['basket']['is_folder_basket'] === 'Y'){
                                        $css2='color:#009dc5;cursor:pointer;';
                                    }else{
                                        $css2='color:rgb(102, 102, 102);cursor:pointer;';
                                    }
                                    ?>
                                    <script language="javascript">
                                    function isFolderBasket()
                                    {
                                        if ($(is_folder_basket).checked == true) {
                                            $(is_folder_basket_icon).style.color = '#009dc5';
                                        } else {
                                            $(is_folder_basket_icon).style.color = 'rgb(102, 102, 102)';
                                        }
                                    }
                                    </script>
                                    <p style="display:none;">
                                        <label><?php echo _IS_FOLDER_BASKET;?> : </label>
                                        <input type='checkbox' name="is_folder_basket" id="is_folder_basket" onclick="isFolderBasket();" value="Y" <?php if ($_SESSION['m_admin']['basket']['is_folder_basket'] === 'Y') echo 'checked="checked"';?>/>
                                    </p>
                                <?php } ?>
                                <?php if ($core_tools->is_module_loaded('notifications')) { ?>
                                        <script language="javascript">
                                            function flagNotif()
                                            {
                                                if ($(flag_notif).checked == true) {
                                                    $(flag_notif_icon).style.color = '#009dc5';
                                                } else {
                                                    $(flag_notif_icon).style.color = 'rgb(102, 102, 102)';
                                                }
                                            }
                                        </script>
                                        <p style="display:none;">
                                        <label><?php echo _ACTIVATE_NOTIFICATION;?> : </label>
                                        <input type='checkbox' name="flag_notif" id="flag_notif" onclick="flagNotif();" value="Y" <?php if ($_SESSION['m_admin']['basket']['flag_notif'] === 'Y') echo 'checked="checked"';?>/>
                                    </p>
                              <?php } ?>  

                                <p style="text-align:center;">
                                    <i class="fa fa-search fa-2x" id="is_visible_only_on_search_icon" title="<?php echo _BASKET_VISIBLE_ONLY_ON_SEARCH;?>" style="<?php echo $css; ?>" onclick="$$('#is_visible_only_on_search')[0].click();"></i>
                                     <?php if ($core_tools->is_module_loaded('folder')) { ?>
                                    <i class="fa fa-folder-o fa-2x" id="is_folder_basket_icon" title="<?php echo _IS_FOLDER_BASKET;?>" style="<?php echo $css2; ?>" onclick="$$('#is_folder_basket')[0].click();"></i>
                                <?php } ?>
                                <?php if ($core_tools->is_module_loaded('notifications')) { 
                                    if ($_SESSION['m_admin']['basket']['flag_notif'] === 'Y'){
                                        $css2='color:#009dc5;cursor:pointer;';
                                    }else{
                                        $css2='color:rgb(102, 102, 102);cursor:pointer;';
                                    }?>
                                    <i class="fa fa-bell-o fa-2x" id="flag_notif_icon" title="<?php echo _ACTIVATE_NOTIFICATION;?>" style="<?php echo $css2; ?>" onclick="$$('#flag_notif')[0].click();"></i>
                                <?php } ?>
                                </p>
                                <p class="buttons" style="text-align:center;">
                                    &nbsp;<input type="submit" name="Submit" value="<?php echo _VALIDATE;?>" class="button" />&nbsp;
                                    <input type="button" name="cancel" value="<?php echo _CANCEL;?>" class="button"  onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=basket&amp;module=basket';"/>
                                </p>
                            </form>
                        </td>
                        <td width="55%">
                            <div id="keywords-helper" class="small_text">
                                <h3><i class ="fa fa-info-circle fa-3x" ></i> <?php echo _HELP_KEYWORDS;?></h3>
                                <p align='right'>
                                    <b><u><?php echo _HELP_BY_CORE;?>:</u></b>
                                    <br/>
                                    <br/>
                                </p>
                                <p>
                                    <b>@user : </b>
                                    <em><?php echo _HELP_KEYWORD0;?></em>
                                    <br/>
                                    <b>@email : </b>
                                    <em><?php echo _HELP_KEYWORD_EMAIL;?></em>
                                </p>
                                <br/>
                                <p align='right'>
                                    <b><u><?php echo _HELP_BY_ENTITY;?>:</u></b><br/><br/>
                                </p>
                                <p align='justify'>
                                    <b>@my_entities : </b><em><?php echo _HELP_KEYWORD1;?></em><br>
                                    <b>@my_primary_entity : </b><em><?php echo _HELP_KEYWORD2;?></em><br>
                                    <b>@subentities[('entity_1',...,'entity_n')] : </b><em><?php echo _HELP_KEYWORD3;?></em><br/>
                                    <b>@parent_entity['entity_id'] : </b><em><?php echo _HELP_KEYWORD4;?></em><br/>
                                    <b>@sisters_entities['entity_id'] : </b><em><?php echo _HELP_KEYWORD5;?></em><br/>
                                    <b>@entity_type['type'] : </b><em><?php echo _HELP_KEYWORD9;?></em><br/>
                                    <b>@all_entities : </b><em><?php echo _HELP_KEYWORD6;?></em><br/>
                                    <b>@immediate_children['entity_1',..., 'entity_id'] : </b><em><?php echo _HELP_KEYWORD7;?></em><br/>
                                    <b>@ancestor_entities['entity_id'][depth] : </b><em><?php echo _HELP_KEYWORD8;?></em><br/>
                                    <br/><br/><?php echo _HELP_KEYWORD_EXEMPLE_TITLE;?><br/>
                                    <div style='border:1px black solid; padding:3px;'><b><?php echo _HELP_KEYWORD_EXEMPLE;?></b></div>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <script language="javascript">
                updateCollection($('collection').value, 'false');
                function updateCollection(collId, isReloadGroups)
                {
                    //console.log(collId);
                    new Ajax.Request('index.php?module=basket&page=ajaxUpdateCollidAndActions&display=true',
                    {
                        method:'post',
                        parameters: {coll_id : collId, is_reload_groups : isReloadGroups},
                        onSuccess: function(answer) {
                            var response = answer.responseText;
                            //console.log(response);
                        },
                        onFailure: function(){ alert('Something went wrong...'); }
                    });
                    $('groupbasket_form').src = 'index.php?display=true&module=basket&page=groupbasket_form';
                }
            </script>
        <?php
        }
        ?>
        </div>
    <?php
    }

    /**
    * Validates the  informations returned by the form of the formgroups() function, in case of error writes in the $_SESSION['error'] var
    *
    * @param   $mode  string Administrator mode "add" or "up"
    */
    protected function basketinfo($mode)
    {

        if($mode == "add")
        {
            $_SESSION['m_admin']['basket']['basketId'] = $this->wash($_REQUEST['basketId'], "nick", _THE_ID, 'yes', 0, 32);
        }
        if($mode == "up")
        {
            $_SESSION['m_admin']['basket']['basketId']  = $this->wash($_REQUEST['id'], "nick", _THE_ID, 'yes', 0, 32);
        }
        if(isset($_REQUEST['basketname']) && !empty($_REQUEST['basketname']))
        {
            $_SESSION['m_admin']['basket']['name'] = $this->wash($_REQUEST['basketname'], "no", _THE_BASKET, 'yes', 0, 255);
        }
        if (isset($_REQUEST['basketdesc']) && !empty($_REQUEST['basketdesc']))
        {
            $_SESSION['m_admin']['basket']['desc'] = $this->wash($_REQUEST['basketdesc'], "no", _THE_DESC, 'yes', 0, 255);
        }
        if ( isset($_REQUEST['collection']) && !empty($_REQUEST['collection'])) {
            $_SESSION['m_admin']['basket']['coll_id'] = $this->wash($_REQUEST['collection'], "no", _COLLECTION, 'yes', 0, 32);
        }
        if (isset($_REQUEST['basketclause'])
            && ! empty($_REQUEST['basketclause'])) {
            $_SESSION['m_admin']['basket']['clause'] = trim($_REQUEST['basketclause']);
        }
        if ( isset($_REQUEST['is_visible']) && !empty($_REQUEST['is_visible'])) {
            $_SESSION['m_admin']['basket']['is_visible'] = $_REQUEST['is_visible'];
        } else {
            $_SESSION['m_admin']['basket']['is_visible'] = "N";
        }
        if ( isset($_REQUEST['is_folder_basket']) && !empty($_REQUEST['is_folder_basket'])) {
            $_SESSION['m_admin']['basket']['is_folder_basket'] = $_REQUEST['is_folder_basket'];
        } else {
            $_SESSION['m_admin']['basket']['is_folder_basket'] = "N";
        }
        if ( isset($_REQUEST['flag_notif']) && !empty($_REQUEST['flag_notif'])) {
            $_SESSION['m_admin']['basket']['flag_notif'] = $_REQUEST['flag_notif'];
        } else {
            $_SESSION['m_admin']['basket']['flag_notif'] = "";
        }
        $_SESSION['m_admin']['basket']['order'] = $_REQUEST['order'];
        $_SESSION['m_admin']['basket']['order_field'] = $_REQUEST['order_field'];
        $_SESSION['m_admin']['basket']['what'] = $_REQUEST['what'];
        $_SESSION['m_admin']['basket']['start'] = $_REQUEST['start'];
    }

    /**
    * After the validation made by the basketinfo() function, according the mode update the basket table or insert a new basket
    *
    * @param  $mode  string Mode "up" or "add"
    */
    public function addupbasket($mode)
    {
        // Checks the session values
        $this->basketinfo($mode);

        $order = $_SESSION['m_admin']['basket']['order'];
        $order_field = $_SESSION['m_admin']['basket']['order_field'];
        $what = $_SESSION['m_admin']['basket']['what'];
        $start = $_SESSION['m_admin']['basket']['start'];
        //echo '<pre>'.print_r($_REQUEST,true).'</pre>'; echo '<pre>'.print_r($_SESSION['m_admin']['basket'],true).'</pre>'; exit();
        // If error redirection to the form page and shows the error
        if(!empty($_SESSION['error']))
        {
            if($mode == "up")
            {
                if(!empty($_SESSION['m_admin']['basket']['basketId']))
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket_up&id=".$_SESSION['m_admin']['basket']['basketId']."&module=basket");
                    exit();
                }
                else
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket&module=basket&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what);
                    exit();
                }
            }
            elseif($mode == "add")
            {
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket_add&module=basket");
                exit();
            }
        }
        else
        {
            $db = new Database();
            // Add Mode
            if($mode == "add")
            {
                
                $stmt = $db->query("select basket_id from ".$_SESSION['tablename']['bask_baskets']." where basket_id = ?",array($_SESSION['m_admin']['basket']['basketId']));
                
                if($stmt->rowCount() > 0)
                {
                    
                    $_SESSION['error'] = $_SESSION['m_admin']['basket']['basketId']." "._ALREADY_EXISTS."<br />";
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket_add&module=basket");
                    exit();
                }
                else
                {
                    $tmp = $_SESSION['m_admin']['basket']['clause'];
                    
                    // Checks the where clause syntax
                    $syntax = $this -> where_test($_SESSION['m_admin']['basket']['clause']);
                    if($syntax['status'] <> true)
                    {
                        $_SESSION['error'] .= ' : ' . _SYNTAX_ERROR_WHERE_CLAUSE . ' ' . $syntax['error'];
                        header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket_add&module=basket");
                        exit();
                    }
                    $db->query(
                        "INSERT INTO ".$_SESSION['tablename']['bask_baskets']." ( coll_id, basket_id, basket_name, basket_desc , basket_clause, is_visible, is_folder_basket, flag_notif ) "
                        ."VALUES (?,?,?,?,?,?,?,?)", array($_SESSION['m_admin']['basket']['coll_id'],$_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['name'],$_SESSION['m_admin']['basket']['desc'],$tmp,$_SESSION['m_admin']['basket']['is_visible'],$_SESSION['m_admin']['basket']['is_folder_basket'],$_SESSION['m_admin']['basket']['flag_notif']));
                    $this->load_db();

                    // Log in database if required
                    if($_SESSION['history']['basketadd'] == "true")
                    {
                        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['bask_baskets'], $_SESSION['m_admin']['basket']['basketId'],"ADD",'basketadd',_BASKET_ADDED." : ".$_SESSION['m_admin']['basket']['basketId'], $_SESSION['config']['databasetype'], 'basket');
                    }

                    // Empties the basket administration session var and redirect to baskets list
                    $this->clearbasketinfos();
                    $_SESSION['info'] = _BASKET_ADDED;
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket&module=basket&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what);
                    exit();
                }
            }
            // Up Mode
            elseif($mode == "up")
            {
                
                $tmp =  $_SESSION['m_admin']['basket']['clause'];
                if($tmp == NULL){
                    $tmp = "";
                }
                $desc = $_SESSION['m_admin']['basket']['desc'];
                //var_dump($desc);
                //exit;
                if($desc == NULL){
                    $desc = "";
                }
                $name = $_SESSION['m_admin']['basket']['name'];
                if($name == NULL){
                    $name = "";
                }

                //    $clause = ", basket_clause = '".$tmp."'";
                //}

                // Checks the where clause syntax
                $syntax =  $this->where_test($_SESSION['m_admin']['basket']['clause']);
                if($syntax['status'] <> true)
                {
                    $_SESSION['error'] .= ' : ' . _SYNTAX_ERROR_WHERE_CLAUSE . ' ' . $syntax['error'];
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket_up&id=".$_SESSION['m_admin']['basket']['basketId']."&module=basket");
                    exit();
                }

                $db->query("UPDATE ".$_SESSION['tablename']['bask_baskets']." set basket_name = ? , coll_id = ? , basket_desc = ? ,basket_clause = ?, is_folder_basket = ?, is_visible = ?, flag_notif = ? where basket_id = ?",array($name,$_SESSION['m_admin']['basket']['coll_id'],$desc, $tmp, $_SESSION['m_admin']['basket']['is_folder_basket'], $_SESSION['m_admin']['basket']['is_visible'], $_SESSION['m_admin']['basket']['flag_notif'], $_SESSION['m_admin']['basket']['basketId']));
                $this->load_db();

                // Log in database if required
                if($_SESSION['history']['basketup'] == "true")
                {
                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                    $hist = new history();
                    $hist->add($_SESSION['tablename']['bask_baskets'], $_SESSION['m_admin']['basket']['basketId'],"UP",'basketup',_BASKET_UPDATE." : ".$_SESSION['m_admin']['basket']['basketId'], $_SESSION['config']['databasetype'], 'basket');
                }

                // Empties the basket administration session var and redirect to baskets list
                $this->clearbasketinfos();
                $_SESSION['info'] = _BASKET_UPDATED;
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket&module=basket&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what);
                exit();
            }
        }
    }

    /**
    * Cleans the $_SESSION['m_admin']['basket'] array
    */
    protected function clearbasketinfos()
    {
        unset($_SESSION['m_admin']);
    }

    /**
    * Check the basket where clause syntax
    *
    * @param  $where_clause   string The where clause to check
    * @return bool true if the syntax is correct, false otherwise
    */
    public function where_test($where_clause)
    {
        $where = '';
        $return = array(
                'status' => true,
                'error' => ''
            );
        if (! empty ($where_clause)) {
                require_once 'core/class/SecurityControler.php';
                $secCtrl = new SecurityControler();
                if ($secCtrl->isUnsecureRequest($where_clause)) {
                    $return = array(
                        'status' => false,
                        'error' => _WHERE_CLAUSE_NOT_SECURE
                    );
                    return $return;
                } else {
                    $where = $secCtrl->process_security_where_clause(
                        $where_clause, $_SESSION['user']['UserId']
                    );
                }
        }

        //Folder basket
        if ($_SESSION['m_admin']['basket']['is_folder_basket'] == 'Y' && ! empty ($where_clause)) {
            $core_tools = new core_tools();
            if ($core_tools->is_module_loaded('folder')) {
                $db = new Database();
                    $stmt = $db->query(
                        "select count(*) from " . $_SESSION['view']['view_folders']
                        . " " . $where);
                if (!isset($stmt) || !$stmt) {
                    $_SESSION['error'] .= " " . $_SESSION['view']['view_folders'];
                    $return = array(
                        'status' => false,
                        'error' => ''
                    );
                }
            }
            
        } else {
            // Gets the basket collection
            $ind = -1;
            for ($i = 0; $i < count($_SESSION['collections']); $i ++)
            {
                if ($_SESSION['m_admin']['basket']['coll_id']
                    == $_SESSION['collections'][$i]['id']) {
                    $ind = $i;
                    break;
                }
            }

            if ($ind == -1) {
                $_SESSION['error'] .= ' ' . $_SESSION['m_admin']['basket']['coll_id'];
                $return = array(
                    'status' => false,
                    'error' => ''
                );
            } else {// Launches the query in quiet mode
                $db = new Database();
                $stmt = $db->query(
                    "select count(*) from " . $_SESSION['collections'][$ind]['view']
                    . " " . $where, array(), true
                );
            }
            if (!isset($stmt) || !$stmt) {
                $_SESSION['error'] .= " " . $_SESSION['m_admin']['basket']['coll_id'];
                $return = array(
                    'status' => false,
                    'error' => ''
                );
            }
        }
        return $return;
    }

    /**
    * Update the groupbasket and actions_groupbasket tables
    */
    protected function load_db()
    {
        $db = new Database();
        // Empties the tables from the existing data about the current basket ($_SESSION['m_admin']['basket']['basketId'])
        $db->query("DELETE FROM ".$_SESSION['tablename']['bask_groupbasket'] ." where basket_id= ?",array($_SESSION['m_admin']['basket']['basketId']));
        $db->query("DELETE FROM ".$_SESSION['tablename']['bask_actions_groupbaskets'] ." where basket_id= ?",array($_SESSION['m_admin']['basket']['basketId']));
        $grouplistetmp ="";
        $groupIdList = '';
        // Browses the $_SESSION['m_admin']['basket']['groups']
        for($i=0; $i < count($_SESSION['m_admin']['basket']['groups'] ); $i++)
        {
            // Update groupbasket table
            $db->query("INSERT INTO ".$_SESSION['tablename']['bask_groupbasket']." (group_id, basket_id, sequence,  result_page, list_lock_clause, sublist_lock_clause)
            VALUES (?,?,?,?,?,?)",array($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'],$_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$i]['SEQUENCE'],$_SESSION['m_admin']['basket']['groups'][$i]['RESULT_PAGE'],$_SESSION['m_admin']['basket']['groups'][$i]['LOCK_LIST'],$_SESSION['m_admin']['basket']['groups'][$i]['LOCK_SUBLIST']));

            // Browses the actions array for the current basket - group couple and inserts the action in actions_groupbasket table  if needed
            for($j=0; $j < count($_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS']); $j++)
            {
                $db->query("INSERT INTO ".$_SESSION['tablename']['bask_actions_groupbaskets']
                    ." (group_id, basket_id, where_clause, used_in_basketlist, used_in_action_page, id_action )
                    VALUES (?,?,?,?,?,?)",array($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'],$_SESSION['m_admin']['basket']['basketId'], $_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['WHERE'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['MASS_USE'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['PAGE_USE'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION']));
            }

            // Inserts in actions_groupbasket table the default action if set
            if(isset($_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION']) && !empty($_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION']))
            {
                $stmt = $db->query("INSERT INTO ".$_SESSION['tablename']['bask_actions_groupbaskets']." (group_id, basket_id, where_clause, used_in_basketlist, used_in_action_page, id_action, default_action_list)
            VALUES (?, ?,'','N','N', ?, 'Y')",array($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'],$_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION']));
            }
            $groupIdList .= $_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'] . ',';
        }
        $arrayPDO = array($_SESSION['m_admin']['basket']['basketId']);
        $groupIdList = rtrim($groupIdList, ",");
        $allGroupId = explode(",", $groupIdList);
        $arrayPDO = array_merge($arrayPDO, array($allGroupId));

        $stmt = $db->query("DELETE FROM user_baskets_secondary where basket_id = ? and group_id not in (?)", $arrayPDO);
        /*var_dump($stmt);
        echo $_SESSION['m_admin']['basket']['basketId'] . ' ' . $groupIdList;exit;*/

        $_SESSION['service_tag'] = 'load_basket_db';
        $core = new core_tools();
        ### Le chargement de plusieurs services ne fonctionne pas... Obligation de les nommer un par
        //$core->execute_modules_services($_SESSION['modules_services'], 'load_groupbasket_db', "include");
        $core->execute_modules_services($_SESSION['modules_services'], 'load_groupbasket_db', "include", 'param_redirect_action', 'entities');
        $core->execute_modules_services($_SESSION['modules_services'], 'load_groupbasket_db', "include", 'param_index_entities', 'entities');
        $core->execute_app_services($_SESSION['app_services'], 'load_groupbasket_db', "include");
        $core->execute_modules_services($_SESSION['modules_services'], 'load_groupbasket_db', "include");
        $_SESSION['service_tag'] = '';
    }

    /**
    * Allows, suspends or deletes a basket in the database
    *
    * @param   $id  string Basket identifier
    * @param  $mode  string  "allow", "ban" or "del", but only "allow" and "ban" are deprecated
    */
    public function adminbasket($id,$mode)
    {
        $order = $_REQUEST['order'];
        $order_field = $_REQUEST['order_field'];
        $start = $_REQUEST['start'];
        $what = $_REQUEST['what'];
        if(!empty($_SESSION['error']))
        {
            header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket&module=basket&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what);
            exit();
        }
        else
        {
            $db = new Database();
            $stmt = $db->query("select basket_id from ".$_SESSION['tablename']['bask_baskets']." where basket_id= ?",array($id));

            if($stmt->rowCount() == 0)
            {
                $_SESSION['error'] = _BASKET_MISSING;
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket&module=basket&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what);
                exit();
            }
            else
            {
                $info = $stmt->fetchObject();

                // Mode allow : not used
                if($mode == "allow")
                {
                    $db->query("Update ".$_SESSION['tablename']['bask_baskets']." set enabled = 'Y' where basket_id= ?", array($id));
                    if($_SESSION['history']['basketval'] == "true")
                    {
                        require_once("core/class/class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['bask_baskets'], $id,"VAL",'basketval',_BASKET_AUTORIZATION." : ".$id, $_SESSION['config']['databasetype'] ,'basket');
                    }
                    $_SESSION['info'] = _AUTORIZED_BASKET;
                }
                // Mode ban : not used
                elseif($mode == "ban")
                {
                    $db->query("Update ".$_SESSION['tablename']['bask_baskets']." set enabled = 'N' where basket_id = ?",array($id));
                    if($_SESSION['history']['basketban'] == "true")
                    {
                        require_once("core/class/class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['bask_baskets'], $id,"BAN",'basketban',_BASKET_SUSPENSION." : ".$id, $_SESSION['config']['databasetype'], 'basket');
                    }
                    $_SESSION['info'] = _SUSPENDED_BASKET;

                }
                // Mode delete  : delete a basket and all its setting
                elseif($mode == "del" )
                {
                    $db->query("delete from ".$_SESSION['tablename']['bask_baskets']."  where basket_id = ?", array($id));
                    $db->query("delete from ".$_SESSION['tablename']['bask_groupbasket']."  where basket_id = ?", array($id));
                    $db->query("delete from ".$_SESSION['tablename']['bask_actions_groupbaskets']."  where basket_id = ?", array($id));
                    $db->query("delete from user_baskets_secondary where basket_id = ?", array($id));

                    $_SESSION['service_tag'] = 'del_basket';
                    $_SESSION['temp_basket_id'] = $id;
                    require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_core_tools.php");
                    $core = new core_tools();
                    echo $core->execute_modules_services($_SESSION['modules_services'], 'del_basket', "include");
                    echo $core->execute_app_services($_SESSION['app_services'], 'del_basket', "include");

                    // Log in database if needed
                    if($_SESSION['history']['basketdel'] == "true")
                    {
                        require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
                        $hist = new history();
                        $hist->add($_SESSION['tablename']['bask_baskets'], $id,"DEL",'basketdel',_BASKET_DELETION." : ".$id, $_SESSION['config']['databasetype'],  'basket');
                    }
                    $_SESSION['info'] = _BASKET_DELETION;
                }

                // Redirection to the baskets list page
                header("location: ".$_SESSION['config']['businessappurl']."index.php?page=basket&module=basket&order=".$order."&order_field=".$order_field."&start=".$start."&what=".$what);
                exit();
            }
        }
    }

    /**
    * Checks if an action is defined for a given usergroup
    *
    * @param  $id_action  string Action identifier
    * @param  $ind_group_session  string Indice of the group in the $_SESSION['m_admin']['basket']['groups'] array
    * @return bool
    */
    public function is_action_defined_for_the_group($id_action, $ind_group_session)
    {
        if (isset($ind_group_session)
            && isset($_SESSION['m_admin']['basket']['groups']
                [$ind_group_session])) {
            for ($i = 0; $i < count(
                $_SESSION['m_admin']['basket']['groups']
                    [$ind_group_session]['ACTIONS']
                ); $i ++) {
                if (trim($id_action) == trim(
                    $_SESSION['m_admin']['basket']['groups']
                        [$ind_group_session]['ACTIONS'][$i]['ID_ACTION'])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
    * Checks if an action is allowed in a mode for a given group
    *
    * @param  $ind_group  string Indice of the group in the $_SESSION['m_admin']['basket']['groups'] array
    * @param  $id_action  string Action identifier
    * @param  $what  string Action  mode : "MASS_USE" or "PAGE_USE"
    * @return string 'Y' if the action is allowed in the mode, 'N' if not allowed, empty string otherwise
    */
    public function get_infos_groupbasket_session($ind_group, $id_action, $what)
    {
        if (! isset($ind_group)
            || ! isset(
                $_SESSION['m_admin']['basket']['groups'][$ind_group]['ACTIONS']
            )) {
            return '';
        }
        for($i=0; $i < count($_SESSION['m_admin']['basket']['groups'][$ind_group]['ACTIONS']); $i++)
        {
            if($id_action == $_SESSION['m_admin']['basket']['groups'][$ind_group]['ACTIONS'][$i]['ID_ACTION'])
            {
                if(isset($_SESSION['m_admin']['basket']['groups'][$ind_group]['ACTIONS'][$i][$what]))
                {
                    return $_SESSION['m_admin']['basket']['groups'][$ind_group]['ACTIONS'][$i][$what];
                }
                else
                {
                    if($what == 'MASS_USE' || $what == 'PAGE_USE')
                    {
                        return 'N';
                    }
                    else
                    {
                        return '';
                    }
                }
            }
        }
        if($what == 'MASS_USE' || $what == 'PAGE_USE')
        {
            return 'N';
        }
        else
        {
            return '';
        }
    }

    /**
    * Manage Basket order
    */
    public function ManageBasketOrder($getFromBdd = true)
    {

        echo '<h1><i class="fa fa-inbox fa-2x" title="" /></i> '._MANAGE_BASKET_ORDER.'</h1>';
        echo '<br/>';
        $db = new Database();

        if ($getFromBdd) {
            $stmt = $db->query("SELECT * FROM baskets WHERE is_visible = 'Y' and basket_id <> 'IndexingBasket' ORDER BY basket_order, basket_name");

            $_SESSION['basket_order']= array();

            while ($allBaskets = $stmt->fetchObject()) {
                array_push($_SESSION['basket_order'], array("basket_id" => $allBaskets->basket_id, "basket_name" => $allBaskets->basket_name, "basket_desc" => $allBaskets->basket_desc, "basket_order" => $allBaskets->basket_order));
            }
        }
        ?>

        <div id="inner_content">
            <table class="listing spec" cellspacing="0" border="0" style="width: 100%; margin: 0;">
                <head>
                    <tr>
                        <th><?php echo _INDEX;?></th>
                        <th><?php echo _ID;?></th>
                        <th><?php echo _BASKET;?></th>
                        <th><?php echo _DESC;?></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </head>

            <?php
                foreach ($_SESSION['basket_order'] as $key => $value) {

                    if(($key % 2) == 1) {
                        $color = ' class="col"';
                    } else {
                        $color = ' ';
                    }

                    ?><tr <?php echo $color;?> ><td><?php echo $key;?></td>
                        <td><?php echo $_SESSION['basket_order'][$key]['basket_id'];?></td>
                        <td><?php echo $_SESSION['basket_order'][$key]['basket_name'];?></td>
                        <td><?php echo $_SESSION['basket_order'][$key]['basket_desc'];?></td>
                        <td><?php 
                            if($key > 0){
                                ?>
                                <a onclick="simpleAjaxReturn('<?php echo $_SESSION['config']['businessappurl']."index.php?page=setSessionBasketOrder&module=basket&basketIndex=".$key."&mode=topup";?>')" href="javascript://">
                                    <i class="fa fa-angle-double-up fa-2x" title="<?php echo _MOVE_UP_TOP ;?>"></i>
                                </a><?php 
                            } ?>
                        </td>
                        <td><?php 
                            if($key > 0){
                                ?>
                                <a onclick="simpleAjaxReturn('<?php echo $_SESSION['config']['businessappurl']."index.php?page=setSessionBasketOrder&module=basket&basketIndex=".$key."&mode=up";?>')" href="javascript://">
                                    <i class="fa fa-angle-up fa-2x" title="<?php echo _MOVE_UP_ONE_LEVEL ;?>"></i>
                                </a><?php 
                            } ?>
                        </td>
                        <td><?php 
                            if(isset($_SESSION['basket_order'][$key+1])){
                                ?>
                                <a onclick="simpleAjaxReturn('<?php echo $_SESSION['config']['businessappurl']."index.php?page=setSessionBasketOrder&module=basket&basketIndex=".$key."&mode=down";?>')" href="javascript://">
                                    <i class="fa fa-angle-down fa-2x" title="<?php echo _MOVE_DOWN_ONE_LEVEL ;?>"></i>
                                </a><?php 
                            } ?>
                        </td>
                        <td><?php 
                            if(isset($_SESSION['basket_order'][$key+1])){
                                ?>
                                <a onclick="simpleAjaxReturn('<?php echo $_SESSION['config']['businessappurl']."index.php?page=setSessionBasketOrder&module=basket&basketIndex=".$key."&mode=topdown";?>')" href="javascript://">
                                    <i class="fa fa-angle-double-down fa-2x" title="<?php echo _MOVE_DOWN_BOTTOM ;?>"></i>
                                </a><?php 
                            } ?>
                        </td>
                    </tr><?php
                }

            ?>
            </table>
            <br/>
            <div align="center">
                <input class="button" type="button" value="<?php echo _VALIDATE;
                    ?>" onclick="window.location.href = '<?php echo $_SESSION['config']['businessappurl'] 
                        . 'index.php?module=basket&page=setSessionBasketOrder&mode=save';?>';"/>

                <input class="button" type="button" value="<?php echo _CANCEL;
                    ?>" onclick="window.location.href = '<?php echo $_SESSION['config']['businessappurl'] 
                        . 'index.php?module=basket&page=basket';?>';"/>
            </div>
        </div>
        <?php

    }
}
