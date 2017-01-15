<?php
// Group - Basket Form : actions params
require_once 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'apps_tables.php';

require_once 'modules' . DIRECTORY_SEPARATOR . 'entities'
    . DIRECTORY_SEPARATOR . 'entities_tables.php';

require_once 'modules' . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'class'
    . DIRECTORY_SEPARATOR . 'class_manage_listdiff.php';

$difflist = new diffusion_list();

$difflist_types = $difflist->list_difflist_types();

if($_SESSION['service_tag'] == 'group_basket') {
    $current_groupbasket = $_SESSION['m_admin']['basket']['groups'][$_SESSION['m_admin']['basket']['ind_group']];
    $current_compteur = $_SESSION['m_admin']['compteur'];
    // This param is only for the actions with the keyword : indexing
    if( trim($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['KEYWORD']) == 'indexing') // Indexing case
    {
        $_SESSION['m_admin']['show_where_clause'] = false;
        $is_default_action = false;
        // Is the action the default action for the group on this basket ?
        if( isset($current_groupbasket['DEFAULT_ACTION']) && $_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID'] == $current_groupbasket['DEFAULT_ACTION'])
        {
            $is_default_action = true;
        }
        // indexing difflist_types list
        ?>
    <p>
        <label><?php echo _INDEXING_DIFFLIST_TYPES;?> :</label>
    </p>
    <table align="center" width="100%" id="index_difflist_types_baskets" >
        <tr>
            <td width="40%" align="center">
                <select data-placeholder=" " name="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID']);?>_difflist_types_chosen[]" id="<?php functions::xecho($_SESSION['m_admin']['basket']['all_actions'][$current_compteur]['ID']);?>_difflist_types_chosen" multiple="multiple" size="7"  class="difflist_types" style="width:100%;">
                <?php
                // Browse all the difflist_types
                foreach($difflist_types as $difflist_type_id => $difflist_type_label) {
                    $state_difflist_type = "";
                    if (! $is_default_action ) {
                        if (isset($current_groupbasket['ACTIONS'])) {
                            for ($j = 0; $j < count($current_groupbasket['ACTIONS']); $j ++) {
                                for ($k = 0; $k < count($current_groupbasket['ACTIONS'][$j]['difflist_types']); $k ++) {
                                    if ($difflist_type_id == $current_groupbasket['ACTIONS'][$j]['difflist_types'][$k]['difflist_type_id']) {
                                        $state_difflist_type = 'selected="selected"';
                                    }
                                }
                            }
                        }
                    } else {
                        for ($k = 0; $k < count($current_groupbasket['PARAM_DEFAULT_ACTION']['difflist_types']); $k ++)
                        {
                            if ($difflist_type_id == $current_groupbasket['PARAM_DEFAULT_ACTION']['difflist_types'][$k]['difflist_type_id']) {
                                $state_difflist_type = 'selected="selected"';
                            }
                        }
                    }
                    ?>
                    <option <?php echo $state_difflist_type; ?> value="<?php functions::xecho($difflist_type_id);?>"><?php functions::xecho($difflist_type_label);?></option>
                    <?php
                
                }
                ?>
                </select>
                <style>#index_difflist_types_baskets .chosen-container{width:95% !important;}</style>
            </td>
        </tr>
    </table>
    <?php
    }
}
elseif($_SESSION['service_tag'] == 'manage_groupbasket')
{
    $db = new Database();
    /*
    echo 'before<br>';
    echo 'param status';
    $db->show_array($_SESSION['m_admin']['basket']['groups']);
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
            || $old_group == $_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']) {
            for ($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++) {
                $chosen_difflist_types = array();
                if (isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_difflist_types_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_difflist_types_chosen']) > 0) {
                    for ($k=0; $k < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_difflist_types_chosen']); $k++) {
                        $difflist_type_id = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'].'_difflist_types_chosen'][$k];
                        $difflist_type_label = $difflist_types[$difflist_type_id];
                        array_push($chosen_difflist_types , array( 'difflist_type_id' => $difflist_type_id, 'difflist_type_label' => $difflist_type_label));
                    }
                }
                $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['difflist_types'] = $chosen_difflist_types ;
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
        $_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['difflist_types'] = array();

        if (isset($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_difflist_types_chosen']) && count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_difflist_types_chosen']) > 0) {
            for ($l=0; $l < count($_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_difflist_types_chosen']); $l++) {
                $difflist_type_id = $_REQUEST[$_SESSION['m_admin']['basket']['groups'][$ind]['DEFAULT_ACTION'].'_difflist_types_chosen'][$l];
                $difflist_type_label = $difflist_types[$difflist_type_id];
                array_push($_SESSION['m_admin']['basket']['groups'][$ind]['PARAM_DEFAULT_ACTION']['difflist_types'] , array( 'difflist_type_id' => $difflist_type_id, 'difflist_type_label' => $difflist_type_label));
            }
        }
    }
    $_SESSION['m_admin']['load_groupbasket'] = false;
    /*
    echo 'after<br>';
    echo 'param status';
    $ent->show_array($_SESSION['m_admin']['basket']['groups']);
    exit;
    */
}
elseif($_SESSION['service_tag'] == 'load_basket_session')
{
    $db = new Database();
    
    for($cpt=0; $cpt < count($_SESSION['m_admin']['basket']['groups'] ); $cpt++)
    {
        //$_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION'] = array();
        $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['difflist_types'] = array();
        if(!empty($_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'] ))
        {
            $query = "SELECT lmt.difflist_type_id, lmt.difflist_type_label FROM " . ENT_GROUPBASKET_DIFFLIST_TYPES . " gblmt left join " . ENT_DIFFLIST_TYPES 
                . " lmt on lmt.difflist_type_id = gblmt.difflist_type_id "
                . " where basket_id= ? and group_id = ? and action_id = ?";
            $stmt = $db->query($query,array(trim($_SESSION['m_admin']['basket']['basketId']),trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']),$_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION']));
            $difflist_types = array();
            while($difflist_type = $stmt->fetchObject()) {
                $difflist_types[] = array( 'difflist_type_id' => $difflist_type->difflist_type_id, 'difflist_type_label' => $difflist_type->difflist_type_label);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['difflist_types'] = $difflist_types;
        }
        for($j=0;$j<count($_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS']);$j++)
        {
            
            $query = "SELECT lmt.difflist_type_id, lmt.difflist_type_label FROM " . ENT_GROUPBASKET_DIFFLIST_TYPES . " gblmt left join " . ENT_DIFFLIST_TYPES 
                . " lmt on lmt.difflist_type_id = gblmt.difflist_type_id "
                . " where basket_id= ? and group_id = ? and action_id = ?";
            $stmt = $db->query($query,array(trim($_SESSION['m_admin']['basket']['basketId']),trim($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID']),$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION']));
            $difflist_types = array();
            while($difflist_type = $stmt->fetchObject()) {
                $difflist_types[] = array( 'difflist_type_id' => $difflist_type->difflist_type_id, 'difflist_type_label' => $difflist_type->difflist_type_label);
            }
            $_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['difflist_types'] = $difflist_types;
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
            //$ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cpt]['DEFAULT_ACTION'],$_SESSION['m_admin']['basket']['groups'][$cpt]['PARAM_DEFAULT_ACTION']['difflist_types']);
            $stmt = $db->query(
            "DELETE FROM " . ENT_GROUPBASKET_DIFFLIST_TYPES
            . " where basket_id = ? "
            . " and group_id = ? "
            . " and action_id = ? ",array(trim($_SESSION['m_admin']['basket']['basketId']),trim($GroupBasket['GROUP_ID']),$GroupBasket['DEFAULT_ACTION']));
            
            for ($k = 0; $k < count($GroupBasket['PARAM_DEFAULT_ACTION']['difflist_types']); $k++) {
                $difflist_type = $GroupBasket['PARAM_DEFAULT_ACTION']['difflist_types'][$k];
                $stmt = $db->query(
                    "INSERT INTO " . ENT_GROUPBASKET_DIFFLIST_TYPES
                    . " (group_id, basket_id, action_id, difflist_type_id) values (?, ?, ?, ?)",array(trim($GroupBasket['GROUP_ID']),trim($_SESSION['m_admin']['basket']['basketId']),$GroupBasket['DEFAULT_ACTION'],$difflist_type['difflist_type_id'])
                );
            }
        }
        for($j=0;$j<count($GroupBasket['ACTIONS']);$j++)
        {
            $GroupBasketAction = $GroupBasket['ACTIONS'][$j];
            if(in_array($GroupBasketAction['ID_ACTION'], $indexing_actions)) {
                //$ent->update_redirect_groupbasket_db($_SESSION['m_admin']['basket']['groups'][$cpt]['GROUP_ID'],  $_SESSION['m_admin']['basket']['basketId'],$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['ID_ACTION'],$_SESSION['m_admin']['basket']['groups'][$cpt]['ACTIONS'][$j]['difflist_types']);
                $db->query(
                "DELETE FROM " . ENT_GROUPBASKET_DIFFLIST_TYPES
                . " where basket_id= ? and group_id = ? and action_id = ?",array(trim($_SESSION['m_admin']['basket']['basketId']),trim($GroupBasket['GROUP_ID']),$GroupBasketAction['ID_ACTION']));

                if (isset($GroupBasketAction['ID_ACTION']['difflist_types'])) {
                    for ($k = 0; $k < count($GroupBasketAction['ID_ACTION']['difflist_types']); $k++) {
                        $difflist_type = $GroupBasketAction['ID_ACTION']['difflist_types'][$k];
                        $stmt = $db->query(
                            "INSERT INTO " . ENT_GROUPBASKET_DIFFLIST_TYPES
                            . " (group_id, basket_id, action_id, difflist_type_id) values (?, ?, ?, ?)",array(trim($GroupBasket['GROUP_ID']),trim($_SESSION['m_admin']['basket']['basketId']),$GroupBasketAction['ID_ACTION'],$difflist_type['difflist_type_id'])
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
    $stmt = $db->query("DELETE FROM ".ENT_GROUPBASKET_DIFFLIST_TYPES." WHERE basket_id = ?",array($_SESSION['temp_basket_id']));
    unset($_SESSION['temp_basket_id']);
}
?>
