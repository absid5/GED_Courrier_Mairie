<?php
/*
*
*    Copyright 2008,2009 Maarch
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
* @brief   Popup : manages  basket rights and actions in a group
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

$core_tools = new core_tools();
$core_tools->load_lang();
$db = new Database();
$group ="";
$tab1 = array();
$tab2 = array();
require_once('modules/basket/class/class_admin_basket.php');
$adminBasket = new admin_basket();
$_SESSION['service_tag'] = 'group_basket';
if(isset($_GET['id']) && !empty($_GET['id']))
{
    $group = trim($_GET['id']);
    $stmt = $db->query("select group_desc from ".$_SESSION['tablename']['usergroups']." where group_id = ?",array($group));
    $res = $stmt->fetchObject();
    array_push($tab2, array('ID' =>$group, 'LABEL' => functions::show_string($res->group_desc)));
}
$_SESSION['m_admin']['basket']['ind_group'] = 0;
$found = false;
for($i=0; $i < count($_SESSION['m_admin']['basket']['groups']); $i++)
{
    if(trim($group) == trim($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID']))
    {
        $_SESSION['m_admin']['basket']['ind_group'] = $i;
        $found = true;
    }
    array_push($tab1, $_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID']);
}

for($i=0;$i<count($_SESSION['groups']);$i++)
{
    if(!in_array($_SESSION['groups'][$i], $tab1))
    {
        $stmt = $db->query("select group_desc from ".$_SESSION['tablename']['usergroups']." where group_id = ?",array($_SESSION['groups'][$i]));
        $res = $stmt->fetchObject();
        array_push($tab2, array('ID' =>$_SESSION['groups'][$i], 'LABEL' => functions::show_string($res->group_desc)));
    }
}
$core_tools->load_html();
$core_tools->load_header(_TITLE_GROUP_BASKET, true, true);
$time = $core_tools->get_session_time_expire();
//$core_tools->show_array( $_SESSION['m_admin']['basket']['groups']);
//$core_tools->show_array( $_SESSION['m_admin']['basket']['all_actions']);
?>
<body onload="setTimeout(window.close, <?php echo $time;?>*60*1000);">

    <div class="error"><?php functions::xecho($_SESSION['error']); $_SESSION['error'] = '';?></div>
    <div class="block">
    <h2><?php echo _ADD_TO_BASKET;
    if(!empty($_SESSION['m_admin']['basket']['basketId']))
    {
        echo ' "'.functions::xssafe($_SESSION['m_admin']['basket']['name']).'" ';
    }
    else
    {
        echo " ";
    }
    echo _TO_THE_GROUP ;?></h2>
    <br/>
 <div style="margin-left: 15px;" >
    <form name="group_basket" id="group_basket" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=basket&page=manage_group_basket" method="post" class="forms">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="module" value="basket" />
        <input type="hidden" name="page" value="manage_group_basket" />
        <p>
            <label><?php echo _CHOOSE_GROUP;?> :  </label>
            <select name="group" id="group">
                <option value=""><?php echo _CHOOSE;?></option>
                <?php
                for($i=0; $i < count($tab2); $i++)
                {
                ?>
                <option value="<?php functions::xecho($tab2[$i]['ID']);?>" <?php if($tab2[$i]['ID'] == $group || (isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['GROUP_ID'] ) && $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['GROUP_ID'] == $tab2[$i]['ID']) || (isset($_SESSION['m_admin']['basket_popup']['group_id']) && $_SESSION['m_admin']['basket_popup']['group_id'] == $tab2[$i]['ID'])) { echo "selected=\"selected\""; } ?> ><?php functions::xecho($tab2[$i]['LABEL']);?></option>
              <?php }?>
            </select>
            <?php if($group <> "")
            {
            ?>
                <input type="hidden" name="old_group" id="old_group" value="<?php functions::xecho($group);?>" />
            <?php
            }?>
        </p>
        <p>&nbsp;</p>
        <p>
            <label><?php echo _BASKET_RESULT_PAGE;?> :</label>
            <select name="result_page" id="result_page">
            <?php
            if (isset($_SESSION['basket_page']) && count($_SESSION['basket_page']) > 0) {
                for ($i=0;$i<count($_SESSION['basket_page']);$i++) {
                    if ($adminBasket->isABasketPageOfMyBasketCollection($_SESSION['basket_page'][$i]['ID'], $_SESSION['m_admin']['basket']['coll_id'])) {
                        ?>
                        <option value="<?php functions::xecho( 
                            $_SESSION['basket_page'][$i]['ID']);?>" <?php 
                                if ((isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['RESULT_PAGE']) 
                                && $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['RESULT_PAGE'] == $_SESSION['basket_page'][$i]['ID']) 
                                || ( isset($_SESSION['m_admin']['basket_popup']['res_page']) 
                                && $_SESSION['m_admin']['basket_popup']['res_page'] == $_SESSION['basket_page'][$i]['ID'])) { 
                                    echo "selected=\"selected\"";
                                } elseif ($i==0){ 
                                    echo "selected=\"selected\"";
                                }
                                ?>><?php 
                                    functions::xecho($_SESSION['basket_page'][$i]['LABEL']);
                                ?></option>
                        <?php
                    }
                }
            }
            ?>
            </select>
            <input type="checkbox" id="lock_list" name="lock_list" value="Y" onclick="new Effect.toggle('lock_rules_div', 'blind', {delay:0.2});" 
            <?php if(strlen(trim($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['LOCK_LIST'])) >0 
                    || strlen(trim($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['LOCK_SUBLIST'])) >0) { 
                     echo 'checked="checked"';}?>>
                     <span onclick="new Effect.toggle('lock_rules_div', 'blind', {delay:0.2});" 
                     style="cursor:pointer; color:#1B91BA; text-decoration:underline;"><?php echo _LOCK_LIST;?></span>
        </p>
        <div id="lock_rules_div" style="display:none;">
        <table border="0" width="60%" align="center"> 
            <tr>
                <td><?php echo _PRINCIPAL_LIST;?> :<br/>
                    <textarea name="list_whrere_clause" id="list_whrere_clause"  rows="4"><?php functions::xecho($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['LOCK_LIST']);?></textarea>
                </td>
                <td rowspan="2">
                    <div class="block small_text" >
                    <h3><i class ="fa fa-info" ></i>
                    <?php echo _HELP_LIST_KEYWORDS;?></h3>
                        <p align="justify">
                        <?php echo _HELP_LIST_KEYWORD_EXEMPLE_TITLE;?><br/>
                        <em><?php echo _HELP_LIST_KEYWORD1;?></em><br/>
                        <em><?php echo _HELP_LIST_KEYWORD2;?></em><br/>
                        <div style="border:1px black solid; padding:3px;"><b><?php echo _HELP_LIST_KEYWORD_EXEMPLE;?></b></div>
                        </p>
                    </div>
                    <div class='block_end'>&nbsp;</div>
                </td>
            </tr>
            <tr><td valign="top"><?php echo _SUBLIST;?> :<br/>
                <textarea name="sublist_whrere_clause" id="sublist_whrere_clause" rows="4"><?php functions::xecho($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['LOCK_SUBLIST']);?></textarea>
            </td></tr>
        </table>
        </div>
        <p>&nbsp;</p>
        <p>
            <label><?php echo _DEFAULT_ACTION_LIST;?> :</label>
            <?php 
            //print_r($_SESSION['m_admin']['basket']['all_actions']);
                foreach ($_SESSION['m_admin']['basket']['all_actions'] as $key => $value) {
                    $array_actions['ID']=$value['ID'];
                    $array_actions['LABEL']=$value['LABEL'];
                    $action_tri[$value['ORIGIN']][]=$array_actions;
                }
            ?>
            <select name="default_action_page" id="default_action_page" onchange="manage_actions(this.options[this.selectedIndex].value, true, '<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=basket&page=check_action";?>');">
                <option value=""><?php echo _NO_ACTION_DEFINED;?></option>
                <?php
                /*for($i=0; $i < count($_SESSION['m_admin']['basket']['all_actions']); $i++)
                {
                ?>
                    <option value="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$i]['ID']);?>" <?php if ((isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION']) && $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'] == $_SESSION['m_admin']['basket']['all_actions'][$i]['ID']) || (isset($_SESSION['m_admin']['basket_popup']['default_action_page']) && $_SESSION['m_admin']['basket_popup']['default_action_page'] == $_SESSION['m_admin']['basket']['all_actions'][$i]['ID'])) { echo "selected=\"selected\"";} ?>><?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$i]['LABEL']);?></option>
                <?php
                } */?>
                <?php
                    foreach ($action_tri as $module_name => $actions_ids) {
                        if($module_name == ''){
                            echo '<optgroup label="Apps">';
                            foreach ($actions_ids as $key => $action_id) {
                                ?><option value="<?php
                                functions::xecho($action_id['ID']);?>" <?php
                                if ((isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION']) && $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'] == $action_id['ID']) || (isset($_SESSION['m_admin']['basket_popup']['default_action_page']) && $_SESSION['m_admin']['basket_popup']['default_action_page'] == $action_id['ID'])){
                                    echo 'selected="selected"';
                                } ?> ><?php

                                functions::xecho($action_id['LABEL']);
                                ?></option><?php
                            }
                            echo '</optgroup>';
                        }else{
                            echo '<optgroup label="'.ucfirst($module_name).'">';
                            foreach ($actions_ids as $key => $action_id) {
                                ?><option value="<?php
                                functions::xecho($action_id['ID']);?>" <?php
                                if ((isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION']) && $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'] == $action_id['ID']) || (isset($_SESSION['m_admin']['basket_popup']['default_action_page']) && $_SESSION['m_admin']['basket_popup']['default_action_page'] == $action_id['ID'])){

                                    echo 'selected="selected"';
                                }?> ><?php
                                functions::xecho($action_id['LABEL']);
                                ?></option><?php
                            }
                            echo '</optgroup>';
                        }
                    }
                    ?>
            </select>
        </p>
        <p>&nbsp;</p>
        <div id="allowed_basket_actions" style="float:left; border:1px; width:300px; margin:0 10px 0 13px; display:inline;">
            <div align="center">
                <h3 class="sstit"><?php echo _ASSOCIATED_ACTIONS;?> :</h3>
            </div> <?php
            if(count($_SESSION['m_admin']['basket']['all_actions']) > 0)
            {
                $bask = new admin_basket();
                ?>
                <table> 
                <?php
                    echo "<ul>";
                    foreach ($action_tri as $module_name => $actions_ids) {
                        echo '<li style="margin-top:5px;"><i style="font-weight:bold;font-style: italic;">'.ucfirst($module_name).'</i><ul>';
                        foreach ($actions_ids as $key => $action_id) {
                            ?>
                            <li><input type="checkbox"  name="actions[]" value="<?php functions::xecho($action_id['ID']);?>" class="check group_action" id="checkbox_<?php functions::xecho($action_id['ID']); ?>" onclick="$$('#action_<?php functions::xecho($action_id['ID']); ?>_page_use')[0].click();show_config_action(<?php functions::xecho($action_id['ID']);?>, true, <?php if(!empty($action_id['ID'])){ echo 'true';}else{ echo 'false';}?>);" <?php
                        if (isset($action_id['ID'])
                            && $bask->is_action_defined_for_the_group(
                                $action_id['ID'],
                                $_SESSION['m_admin']['basket']['ind_group']
                            ) || (isset($_SESSION['m_admin']['basket_popup'])
                                && isset($_SESSION['m_admin']['basket_popup']['actions'][$action_id['ID']])
                                && count(
                                    $_SESSION['m_admin']['basket_popup']['actions'][$action_id['ID']]
                                ) > 0
                            )
                        ) {
                            echo 'checked="checked"';
                        }
                        if (isset(
                            $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION']
                            ) && $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'] == $action_id['ID']
                        ) {
                            echo 'disabled="disabled"';
                        }?> />
                            <span id="label_<?php functions::xecho($action_id['ID']);?>"><?php functions::xecho($action_id['LABEL']);  $tr++;     ?></span>
                            <a href="javascript://" class="fa fa-cogs" title="<?php echo _CONFIG_ACTION;?>" onclick="show_config_action(<?php functions::xecho($action_id['ID']);?>, true, <?php if(!empty($action_id['ID'])){ echo 'true';}else{ echo 'false';}?>);" class="config" id="link_<?php functions::xecho($action_id['ID']);?>" style="display:inline;"></a>
                        </li>

                            <?php
                        }
                        echo '</ul></li>';
                        
                    }
                    ?>
                </table><?php
            } else {?>
                <div  align="center">&nbsp;&nbsp;&nbsp;<i><?php echo _NO_ACTIONS_DEFINED;?></i></div><?php
            } ?>
        </div>
        <div  id="config_actions" style="width:600px; display:inline; margin-left:auto; margin-right:auto; height:800px; border: 1px;float:left;"><?php
        for($_SESSION['m_admin']['compteur']=0; $_SESSION['m_admin']['compteur'] < count($_SESSION['m_admin']['basket']['all_actions']); $_SESSION['m_admin']['compteur']++)
        {
            $_SESSION['m_admin']['show_where_clause'] = true;
            if($found)
            {
                $tmp_mass = 'Y';
                $tmp_use = 'Y';
            }

          ?><div id="action_<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>" style="display:none; margin-left:10px;">
                <h3 class="tit"><?php echo _CONFIG_ACTION.' <u>'.functions::xssafe($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['LABEL']).'</u>';?> </h3>
                <div id="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_actions_uses" style="display:block;">
                    <table>
                        <tr>
                            <td><?php echo _USE_IN_MASS;?></td>

                            <td><input type="checkbox" class="check" name="action_<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_mass_use" value="Y"
                            <?php if($bask->get_infos_groupbasket_session($_SESSION['m_admin']['basket']['ind_group'],$_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID'], 'MASS_USE' ) == 'Y' || (isset($_SESSION['m_admin']['basket_popup']) && $_SESSION['m_admin']['basket_popup']['actions'][$_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']]['MASS_USE'] == 'Y')){ echo 'checked="checked"';}?> /></td>
                            <td width="20%">&nbsp;&nbsp;</td>
                            <td><?php echo _USE_ONE;?></td>

                            <td><input type="checkbox" class="check" name="action_<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_page_use" id="action_<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_page_use" value="Y" <?php if($bask->get_infos_groupbasket_session($_SESSION['m_admin']['basket']['ind_group'], $_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID'], 'PAGE_USE' ) == 'Y' || (isset($_SESSION['m_admin']['basket_popup']['actions']) && $_SESSION['m_admin']['basket_popup']['actions'][$_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']]['PAGE_USE'] == 'Y')){ echo 'checked="checked"';}?> /></td>
                        </tr>
                    </table>
                </div>
               
                <?php
                $keyword = $_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['KEYWORD'];
                echo $core_tools->execute_modules_services($_SESSION['modules_services'], 'groupbasket_popup.php', "include");
				echo $core_tools->execute_app_services($_SESSION['app_services'], 'groupbasket_popup.php', "include");
                
                /*if($_SESSION['m_admin']['show_where_clause'] )
                {*/
                    if(isset( $_SESSION['m_admin']['basket_popup']['actions'][$_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']]['WHERE']))
                    {
                        $tmp_where = $_SESSION['m_admin']['basket_popup']['actions'][$_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']]['WHERE'];
                    }
                ?>
                    <p><?php echo _WHERE_CLAUSE_ACTION_TEXT;?></p>
                    <br/>
                    <textarea name="whereclause_<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>" rows="10" style="width:500px;"><?php if(empty($tmp_where)){echo $bask->get_infos_groupbasket_session($_SESSION['m_admin']['basket']['ind_group'],$_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID'], 'WHERE' );}else{ echo $tmp_where;} ?></textarea>
                <?php
                /*}*/ ?></div><?php
        }
        $core_tools->load_js();

        ?>  </div>
            <script type="text/javascript">
                sb = new ScrollBox(document.getElementById('allowed_basket_actions'), {auto_hide: true});
                sb2 = new ScrollBox(document.getElementById('config_actions'), {auto_hide: true});
               manage_actions('<?php if(isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'])){ echo $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'];}?>', true, '<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&module=basket&page=check_action";?>');
            </script>
            <p>&nbsp;</p>
            <p class="buttons" style="text-align:center;">
                <input type="button" name="submit_form" class="button" value="<?php if(empty($group)){ echo _ADD_THIS_GROUP;}else{ echo _MODIFY_THIS_GROUP;}?>" onclick="valid_actions_param('group_basket');this.form.submit();" />
                <input type="submit" name="cancel" value="<?php echo _CANCEL;?>"  class="button"/>
            </p>
    </form>
</div>
</div>
<script type="text/javascript">$$('select').each(function(element) { new Chosen(element,{width: "226px", disable_search_threshold: 10,search_contains: true}); });</script>

</body>
</html>
