<?php

// Group - Basket Form : actions params
if($_SESSION['service_tag'] == 'group_basket')
{
    // This param is only for the actions with the keyword : indexing
    if( trim($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['KEYWORD']) == 'indexing') // Indexing case
    {
        $_SESSION['m_admin']['show_where_clause'] = false;
        $is_default_action = false;
        // Is the action the default action for the group on this basket ?
        if( isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION']) && $_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['DEFAULT_ACTION'])
        {
            $is_default_action = true;
        }
        // indexing entities list
        ?>
    <p>
        <label><?php echo _INDEXING_ENTITIES;?> :</label>
    </p>
    <table align="center" width="100%" id="index_entity_baskets" >
        <tr>
            <td width="40%" align="center">
                <select data-placeholder=" " name="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_entities_chosen[]" id="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_entities_chosen" size="7" multiple="multiple"  class="entities_list" style="width:150px;">
                <?php
                $state_opt = true;
                // Browse all the entities
                for ($cpt = 0; $cpt < count($_SESSION['m_admin']['entities']); $cpt ++) {
                    if($_SESSION['m_admin']['entities'][$cpt]['KEYWORD'] == '1' && $state_opt == true){
                        echo '<optgroup label="Périmètre">';
                        $state_opt = false;
                    }else if($_SESSION['m_admin']['entities'][$cpt]['KEYWORD'] != '1' && $state_opt == true){
                        echo '<optgroup label="'._ENTITY_TREE.'">';
                        $state_opt = false;
                    }
                    $state_entity = "";
                    if (! $is_default_action ) {
                        if (isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'])) {
                            for ($j = 0; $j < count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS']); $j ++) {
                                for ($k = 0; $k < count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['ENTITIES_LIST']); $k ++) {
                                    if ($_SESSION['m_admin']['entities'][$cpt]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['ENTITIES_LIST'][$k]['ID']) {
                                        $state_entity = 'selected="selected"';
                                    }
                                }
                            }
                        }
                    } else {
                        for ($k = 0; $k < count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST']); $k ++)
                        {
                            if ($_SESSION['m_admin']['entities'][$cpt]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'][$k]['ID']) {
                                $state_entity = 'selected="selected"';
                            }
                        }
                    }
                    
                    ?>
                    <option <?php echo $state_entity; ?> title="<?php echo str_replace("&emsp;", "", $_SESSION['m_admin']['entities'][$cpt]['LABEL']);?>" value="<?php functions::xecho($_SESSION['m_admin']['entities'][$cpt]['ID']);?>"><?php echo $_SESSION['m_admin']['entities'][$cpt]['LABEL'];?></option>
                    <?php
                    
                    if($_SESSION['m_admin']['entities'][$cpt+1]['KEYWORD'] != $_SESSION['m_admin']['entities'][$cpt]['KEYWORD']){
                        echo '</optgroup>';
                        $state_opt = true;
                    }
                }
                ?>
                </select>
                <style>#index_entity_baskets .chosen-container{width:95% !important;}</style>
            </td>
        </tr>
    </table>
    <?php
    }
}
elseif($_SESSION['service_tag'] == 'manage_groupbasket')
{
    require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
    $ent = new entities();
/*
    echo 'before<br>';
    echo 'param redirect';
    $ent->show_array($_SESSION['m_admin']['basket']['groups']);
*/
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
            || $old_group == $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']
        ) {
            for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++) {
                $chosen_entities = array();
                if (isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen']) > 0) {
                    for ($k=0; $k < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen']); $k++) {
                        $arr = $ent->get_info_entity($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen'][$k]);
                        $label = $arr['label'];
                        $keyword = $arr['keyword'];
                        array_push($chosen_entities , array( 'ID' =>$_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen'][$k], 'LABEL' => $label, 'KEYWORD' => $keyword));
                    }
                }
                $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ENTITIES_LIST'] = $chosen_entities ;
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
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] = array();

        if (isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen']) > 0) {
            for ($l=0; $l < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen']); $l++) {
                $arr = $ent->get_info_entity($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen'][$l]);
                $label = $arr['label'];
                $keyword = $arr['keyword'];
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] , array( 'ID' =>$_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen'][$l], 'LABEL' => $label, 'KEYWORD' => $keyword));
            }
        }
    }
    $_SESSION['m_admin']['load_groupbasket'] = false;
/*
    echo 'after<br>';
    echo 'param redirect';
    $ent->show_array($_SESSION['m_admin']['basket']['groups']);
    exit;
*/
}
elseif($_SESSION['service_tag'] == 'load_basket_session')
{
    require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
    $entity_tmp = new entities();
    for($cpt=0; $cpt < count($_SESSION['m_admin']['basket']['groups'] ); $cpt++)
    {
        $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] = array();
        if(!empty($_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'] ))
        {
            $array = $entity_tmp->get_values_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'], $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'] );
            $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] = $array['ENTITY'];
        }
        for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++)
        {
            $array = $entity_tmp->get_values_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'], $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'] );
            $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ENTITIES_LIST'] = $array['ENTITY'];
        }
    }
}
elseif($_SESSION['service_tag'] == 'load_basket_db')
{
    require_once('modules/entities/class/class_modules_tools.php');
    $ent = new entities();
    $indexing_actions = array();
    for ($cptI=0; $cptI<count($_SESSION['m_admin']['basket']['all_actions']);$cptI++) {
        if($_SESSION['m_admin']['basket']['all_actions'][$cptI]['KEYWORD'] == 'indexing') {
            array_push($indexing_actions,$_SESSION['m_admin']['basket']['all_actions'][$cptI]['ID']);
        }
    }
    for ($cptRedirection=0; $cptRedirection < count($_SESSION['m_admin']['basket']['groups'] ); $cptRedirection++) {
        if (!empty($_SESSION['m_admin']['basket']['groups'][$cptRedirection]['DEFAULT_ACTION'] )&& in_array($_SESSION['m_admin']['basket']['groups'][$cptRedirection]['DEFAULT_ACTION'], $indexing_actions)) {
            $ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cptRedirection]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cptRedirection]['DEFAULT_ACTION'],$_SESSION['m_admin']['basket']['groups'][$cptRedirection]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST']);
        }
        for ($cptJ=0;$cptJ<count($_SESSION['m_admin']['basket']['groups'][$cptRedirection]['ACTIONS']);$cptJ++) {
            if (in_array($_SESSION['m_admin']['basket']['groups'][$cptRedirection]['ACTIONS'][$cptJ]['ID_ACTION'], $indexing_actions)) {
                $ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cptRedirection]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cptRedirection]['ACTIONS'][$cptJ]['ID_ACTION'],$_SESSION['m_admin']['basket']['groups'][$cptRedirection]['ACTIONS'][$cptJ]['ENTITIES_LIST']);
            }
        }
    }
}
else if($_SESSION['service_tag'] == 'del_basket' && !empty($_SESSION['temp_basket_id']))
{
    $db = new Database();
    $stmt = $db->query("delete from ".$_SESSION['tablename']['ent_groupbasket_redirect']." where basket_id = ?",array($_SESSION['temp_basket_id']));
    unset($_SESSION['temp_basket_id']);
}
?>