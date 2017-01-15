<?php
if($_SESSION['service_tag'] == 'group_basket')
{
    if(trim($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['KEYWORD']) == 'redirect') // Redirection case
    {
        $_SESSION['m_admin']['show_where_clause'] = false;
        ?>
        <p>
            <label><?php echo _TO_ENTITIES;?> :</label>
        </p>
        <table align="center" width="100%" id="redirect_entity_baskets_<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>" >
            <tr>
                <td width="40%" align="center">
                    <select data-placeholder=" " name="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_entities_chosen[]" id="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_entities_chosen" size="6" multiple="multiple"  class="entities_list" style="width:100%;">
                        <?php
                        $state_opt = true;
                        for($i=0;$i<count($_SESSION['m_admin']['entities']);$i++)
                        {
                            if($_SESSION['m_admin']['entities'][$i]['KEYWORD'] == '1' && $state_opt == true){
                                echo '<optgroup label="Périmètre">';
                                $state_opt = false;
                            }else if($_SESSION['m_admin']['entities'][$i]['KEYWORD'] != '1' && $state_opt == true){
                                echo '<optgroup label="Liste des entités">';
                                $state_opt = false;
                            }
                            $state_entity = "";
                            if(!isset($is_default_action) || !$is_default_action)
                            {
                                if (isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'])) {
                                    for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS']);$j++)
                                    {
                                        if($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['ID_ACTION'] == $_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID'])
                                        {
                                            for($k=0; $k<count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['ENTITIES_LIST']);$k++)
                                            {
                                                if($_SESSION['m_admin']['entities'][$i]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['ENTITIES_LIST'][$k]['ID'])
                                                {
                                                    $state_entity = 'selected="selected"';
                                                }
                                            }
                                            break;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                for($k=0; $k<count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST']);$k++)
                                {
                                    if($_SESSION['m_admin']['entities'][$i]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'][$k]['ID'])
                                    {
                                        $state_entity = 'selected="selected"';
                                    }
                                }
                            }
                            
                            ?>
                            <option <?php echo $state_entity; ?> title = "<?php echo str_replace('&emsp;', '', $_SESSION['m_admin']['entities'][$i]['LABEL']);?>" alt = "<?php functions::xecho($_SESSION['m_admin']['entities'][$i]['LABEL']);?>" value="<?php echo $_SESSION['m_admin']['entities'][$i]['ID'];?>"><?php echo $_SESSION['m_admin']['entities'][$i]['LABEL'];?></option>
                            <?php
                            if($_SESSION['m_admin']['entities'][$i+1]['KEYWORD'] != $_SESSION['m_admin']['entities'][$i]['KEYWORD']){
                                echo '</optgroup>';
                                $state_opt = true;
                            }
                        
                        }
                        ?>
                    </select>
                    <style>.scrollbox_content .chosen-container{width:95% !important;}</style>
                </td>
            </tr>
        </table>
        <br/>
        <p>
            <label><?php echo _TO_USERS_OF_ENTITIES;?> :</label>
        </p>
        <table id="redirect_usersentities_baskets_1" align="center" width="100%" >
            <tr>
                <td width="40%" align="center">
                    <select data-placeholder=" " name="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_usersentities_chosen[]" id="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID']);?>_usersentities_chosen" size="6" multiple="multiple"  class="entities_list" style="width:100%;">
                        <?php
                        $state_opt = true;
                        for($i=0;$i<count($_SESSION['m_admin']['entities']);$i++)
                        {
                            if($_SESSION['m_admin']['entities'][$i]['KEYWORD'] == '1' && $state_opt == true){
                                echo '<optgroup label="Périmètre">';
                                $state_opt = false;
                            }else if($_SESSION['m_admin']['entities'][$i]['KEYWORD'] != '1' && $state_opt == true){
                                echo '<optgroup label="'._ENTITY_TREE.'">';
                                $state_opt = false;
                            }
                            $state_usersentities = "";
                            if(!isset($is_default_action) || !$is_default_action)
                            {
                                if(isset($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'])) {
                                    for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS']);$j++)
                                    {
                                        if($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['ID_ACTION'] == $_SESSION['m_admin']['basket']['all_actions'][$_SESSION['m_admin']['compteur']]['ID'])
                                        {
                                            for($k=0; $k<count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['USERS_ENTITIES_LIST']);$k++)
                                            {
                                                if($_SESSION['m_admin']['entities'][$i]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['ACTIONS'][$j]['USERS_ENTITIES_LIST'][$k]['ID'])
                                                {
                                                    $state_usersentities = 'selected="selected"';
                                                }
                                            }
                                            break;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                for($k=0; $k<count($_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST']);$k++)
                                {
                                    if($_SESSION['m_admin']['entities'][$i]['ID'] == $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST'][$k]['ID'])
                                    {
                                        $state_usersentities = 'selected="selected"';
                                    }
                                }
                            }
                            ?>
                            <option <?php echo $state_usersentities; ?> title = "<?php echo str_replace('&emsp;', '', $_SESSION['m_admin']['entities'][$i]['LABEL']);?>" alt = "<?php functions::xecho($_SESSION['m_admin']['entities'][$i]['LABEL']);?>" value="<?php echo $_SESSION['m_admin']['entities'][$i]['ID'];?>"><?php echo $_SESSION['m_admin']['entities'][$i]['LABEL'];?></option>
                            <?php
                            if($_SESSION['m_admin']['entities'][$i+1]['KEYWORD'] != $_SESSION['m_admin']['entities'][$i]['KEYWORD']){
                                echo '</optgroup>';
                                $state_opt = true;
                            }
                        }
                        ?>
                    </select>
                    <style>.scrollbox_content .chosen-container{width:95% !important;}</style>
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
    $groupe = $_REQUEST['group'];
    if(isset($_REQUEST['old_group']) && !empty($_REQUEST['old_group']))
    {
        $old_group = $_REQUEST['old_group'];
    }
    $ind = -1;
    $find = false;
    //$ent->show_array($_REQUEST);
    for ($i=0;$i<count($_SESSION['m_admin']['basket']['groups']); $i++) {
        //echo "groupe:".$_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID']."<br>";
        if (
            $_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'] == $groupe 
            || $old_group == $_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID']
        ) {
            //echo "the good group:".$_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID']."<br>";
            for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS']);$j++) {
                $chosen_entities = array();
                $chosen_usersentities = array();
                //echo "j:".$j."<br>";
                //echo "action:".$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION']."<br/>";
                //echo "request de chosen entities_chosen<br>";
                if (
                    isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen']) 
                    && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen']) > 0
                ) {
                    for (
                        $k=0;
                        $k<count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen']);
                        $k++
                    ) {
                        $arr = $ent->get_info_entity($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen'][$k]);
                        $label = $arr['label'];
                        $keyword = $arr['keyword'];
                        array_push($chosen_entities , array( 'ID' =>$_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_entities_chosen'][$k], 'LABEL' => $label, 'KEYWORD' => $keyword));
                    }
                }
                //echo "request de chosen userentities<br>";
                //$ent->show_array($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_usersentities_chosen']);
                if (
                    isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_usersentities_chosen']) 
                    && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_usersentities_chosen']) > 0
                    ) {
                    for (
                        $k=0;
                        $k<count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_usersentities_chosen']);
                        $k++
                    ) {
                        $arr = $ent->get_info_entity($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_usersentities_chosen'][$k]);
                        $label = $arr['label'];
                        $keyword = $arr['keyword'];
                        array_push($chosen_usersentities , array('ID' =>$_REQUEST[$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'].'_usersentities_chosen'][$k], 'LABEL' => $label, 'KEYWORD' => $keyword));
                    }
                }
                //echo "chosen chosen_entities<br>";
                //$ent->show_array($chosen_entities);
                //echo "chosen userentities<br>";
                //$ent->show_array($chosen_usersentities);
                $_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ENTITIES_LIST'] = $chosen_entities ;
                $_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['USERS_ENTITIES_LIST'] = $chosen_usersentities ;
            }
            $ind = $i;
            $find = true;
            break;
        }
    }
    //param action by default
    if($find && $ind >= 0)
    {
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION'] = array();
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] = array();
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST'] = array();
        if(isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen']) > 0)
        {
            for($l=0; $l < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen']); $l++)
            {
                $arr = $ent->get_info_entity($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen'][$l]);
                $label = $arr['label'];
                $keyword = $arr['keyword'];
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] , array( 'ID' =>$_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_entities_chosen'][$l], 'LABEL' => $label, 'KEYWORD' => $keyword));
            }
        }
        if(isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_usersentities_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_usersentities_chosen']) > 0)
        {
            for($l=0; $l < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_usersentities_chosen']); $l++)
            {
                $arr = $ent->get_info_entity($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_usersentities_chosen'][$l]);
                $label = $arr['label'];
                $keyword = $arr['keyword'];
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST'] , array( 'ID' =>$_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_usersentities_chosen'][$l], 'LABEL' => $label, 'KEYWORD' => $keyword));
            }
        }
    }
    //echo 'after<br>';
    //echo 'param redirect';
    //$ent->show_array($_SESSION['m_admin']['basket']['groups']);
    //exit;
}
elseif($_SESSION['service_tag'] == 'load_basket_session')
{
    require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
    $entity_tmp = new entities();
    for($i=0;$i<count($_SESSION['m_admin']['basket']['groups']);$i++)
    {
        $_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION'] = array();
        $_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] = array();
        $_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST'] = array();
        if(!empty($_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION'] ))
        {
            $array = $entity_tmp->get_values_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'], $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION']  );
            $_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'] = $array['ENTITY'];
            $_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST'] = $array['ENTITY'];
        }
        for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS']);$j++)
        {
            $array = $entity_tmp->get_values_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'], $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'] );
            $_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ENTITIES_LIST'] = $array['ENTITY'];
            $_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['USERS_ENTITIES_LIST'] = $array['USERS'];
        }
    }
}
elseif($_SESSION['service_tag'] == 'load_basket_db')
{
    require_once('modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_modules_tools.php');
    $ent = new entities();
    //$ent->show_array($_SESSION['m_admin']['basket']['all_actions']);
    $redirect_actions = array();
    for($i=0; $i<count($_SESSION['m_admin']['basket']['all_actions']);$i++ )
    {
        if($_SESSION['m_admin']['basket']['all_actions'][$i]['KEYWORD'] == 'redirect')
        {
            array_push($redirect_actions, $_SESSION['m_admin']['basket']['all_actions'][$i]['ID'] );
        }
    }
    for($i=0; $i < count($_SESSION['m_admin']['basket']['groups'] ); $i++)
    {
        if(!empty($_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION'] ) && in_array($_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION'], $redirect_actions))
        {
            $ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$i]['DEFAULT_ACTION'],$_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION']['ENTITIES_LIST'],  $_SESSION['m_admin']['basket']['groups'][$i]['PARAM_DEFAULT_ACTION']['USERS_ENTITIES_LIST']);
        }
        for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS']);$j++)
        {
            if(in_array($_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'], $redirect_actions))
            {
                $ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ID_ACTION'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['ENTITIES_LIST'],$_SESSION['m_admin']['basket']['groups'][$i]['ACTIONS'][$j]['USERS_ENTITIES_LIST'] );
            }
        }
    }
}
else if($_SESSION['service_tag'] == 'del_basket' && !empty($_SESSION['temp_basket_id']))
{
    //require_once("core/class/class_db.php");
    $db = new Database();
    $stmt = $db->query("delete from ".$_SESSION['tablename']['ent_groupbasket_redirect']." where basket_id = ?",array($_SESSION['temp_basket_id']));
    unset($_SESSION['temp_basket_id']);
}
