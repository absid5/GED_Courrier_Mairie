<?php

/* Affichage */
if($mode == 'list'){
    list_show::admin_list($tab, $i, $title, 'id',
        'action_management_controler&mode=list','action','id', true,
        $page_name_up, $page_name_val, $page_name_ban, $page_name_del,
        $page_name_add, $label_add, FALSE, FALSE, _ALL_ACTIONS, _ACTION,
        'exchange', false, true, false, true,
        $what, true, $autoCompletionArray
    );
}
elseif($mode == 'up' || $mode == 'add'){
    ?><h1><i class="fa fa-exchange fa-2x"></i>
        <?php
        if($mode == 'up'){
            echo _MODIFY_ACTION;
        }
        elseif($mode == 'add'){
            echo _ADD_ACTION;
        }
        ?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br /><br />
    <?php
    if($state == false){
        echo '<br /><br /><br /><br />' . _THE_ACTION . ' ' . _UNKNOWN
            . '<br /><br /><br /><br />';
    }
    else{?>
        <div class="block">
        <form name="frmaction" id="frmaction" method="post" action="<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?'
            . 'page=action_management_controler&admin=action&mode=' . $mode . '&id=' . $_REQUEST['id'];
        ?>" class="forms addforms">
            <input type="hidden" name="display" value="true" />
            <input type="hidden" name="admin" value="action" />
            <input type="hidden" name="page" value="action_management_controler" />
            <input type="hidden" name="mode" value="<?php echo $mode;?>" />

            <input type="hidden" name="order" id="order" value="<?php
                functions::xecho($_REQUEST['order']);?>" />
            <input type="hidden" name="order_field" id="order_field" value="<?php
                functions::xecho($_REQUEST['order_field']);?>" />
            <input type="hidden" name="what" id="what" value="<?php
                functions::xecho($_REQUEST['what']);?>" />
            <input type="hidden" name="start" id="start" value="<?php
                functions::xecho($_REQUEST['start']);?>" />

            <p>
                <label for="label"><?php echo _DESC;?> : </label>
                <input name="label" type="text"  id="label" value="<?php
                    functions::xecho(functions::show_str($_SESSION['m_admin']['action']['LABEL']));?>"/>
            </p>
            <?php
            if($_SESSION['m_admin']['action']['IS_SYSTEM']  == 'Y'){
                echo '<div class="error">' . _DO_NOT_MODIFY_UNLESS_EXPERT
                    . '</div><br/>';
            }?>
            <p>
                <label for="status"><?php echo _ASSOCIATED_STATUS;?> : </label>
                <select name="status" id="status">
                    <option value="_NOSTATUS_"><?php echo _CHOOSE_STATUS;?></option>
                    <option value="_NOSTATUS_"><?php echo _UNCHANGED;?></option>
                    <?php
                    for($i = 0; $i < count($statusArray); $i++){
                        ?><option value="<?php functions::xecho($statusArray[$i]['id']);?>"
                        <?php
                        if($_SESSION['m_admin']['action']['ID_STATUS']
                            == $statusArray[$i]['id']) {
                            echo 'selected="selected"';
                        }?>><?php functions::xecho($statusArray[$i]['label']);
                        ?></option><?php
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="action_page"><?php echo _ACTION_PAGE;?> : </label>
                <?php 
                foreach ($_SESSION['actions_pages'] as $key => $value) {
                    $array_actions['ID']=$value['ID'];
                    $array_actions['LABEL']=$value['LABEL'];
                    $action_tri[$value['MODULE']][]=$array_actions;
                }
                ?>
                <select name="action_page" id="action_page">
                    <option value="_"><?php echo _NO_PAGE;?></option>
                    <?php
                        foreach ($action_tri as $module_name => $actions_ids) {
                            if($module_name == ''){
                                echo '<optgroup label="Apps">';
                                foreach ($actions_ids as $key => $action_id) {
                                    ?><option value="<?php
                                    functions::xecho($action_id['ID']);?>" <?php
                                    if($action_id['ID'] == $_SESSION['m_admin']['action']['ACTION_PAGE']){
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
                                    if($action_id['ID']
                                        == $_SESSION['m_admin']['action']['ACTION_PAGE']){
                                        echo 'selected="selected"';
                                    }?> ><?php
                                    functions::xecho($action_id['LABEL']);
                                    ?></option><?php
                                }
                                echo '</optgroup>';
                            }
                        }
                    ?>
                    <?php
                    /*for($i = 0; $i < count($_SESSION['actions_pages']); $i++){
                        ?><option value="<?php
                        functions::xecho($_SESSION['actions_pages'][$i]['ID']);?>" <?php
                        if($_SESSION['actions_pages'][$i]['ID']
                            == $_SESSION['m_admin']['action']['ACTION_PAGE']){
                            echo 'selected="selected"';
                        }?> ><?php
                        functions::xecho($_SESSION['actions_pages'][$i]['LABEL']);
                        ?></option><?php
                    }*/?>
                </select>
            </p>
            <p>
                <label for="keyword"><?php echo _KEYWORD.' ('._SYSTEM_PARAMETERS.')';?>:</label>
                <select name="keyword" id="keyword">
                    <option value=" "><?php echo _NO_KEYWORD;?></option>
                    <option value="redirect" <?php if($_SESSION['m_admin']['action']['KEYWORD'] == 'redirect'){ echo 'selected="selected"';}?>><?php echo _REDIRECT;?></option>
                    <option value="to_validate" <?php if($_SESSION['m_admin']['action']['KEYWORD'] == 'to_validate'){ echo 'selected="selected"';}?>><?php echo _TO_VALIDATE;?></option>
                    <option value="indexing" <?php if($_SESSION['m_admin']['action']['KEYWORD'] == 'indexing'){ echo 'selected="selected"';}?>><?php echo _INDEXING;?></option>
                    <option value="workflow" <?php if($_SESSION['m_admin']['action']['KEYWORD'] == 'workflow'){ echo 'selected="selected"';}?>><?php echo _WF;?></option>
                </select>
            </p>
            <p>
                <label for="history"><?php echo _ACTION_HISTORY;?> : </label>
                <input type="radio"  class="check" name="history" value="Y" <?php
                if($_SESSION['m_admin']['action']['HISTORY'] == 'Y'){
                    echo 'checked="checked"';
                }?> /><?php echo _YES;?>
                <input type="radio"  class="check" name="history" value="N" <?php
                if($_SESSION['m_admin']['action']['HISTORY'] == 'N'){
                    echo 'checked="checked"';
                }?>/><?php echo _NO;?>
            </p>
            <?php
            $core_tools = new core_tools();
            if ($core_tools->is_module_loaded('folder')) {
            ?>
                <p>
                    <label ><?php echo _IS_FOLDER_ACTION;?> : </label>
                    <input type="radio"  class="check" name="is_folder_action" value="Y"
                    <?php
                    if ($_SESSION['m_admin']['action']['IS_FOLDER_ACTION'] == 'Y') {
                        ?> checked="checked"<?php
                    } ?> /><?php echo _YES;?>
                    <input type="radio" name="is_folder_action" class="check"  value="N"
                    <?php
                    if ($_SESSION['m_admin']['action']['IS_FOLDER_ACTION'] == 'N') {
                       ?> checked="checked"<?php
                    } ?> /><?php echo _NO;?>
                </p>
            <?php }else{

                    echo '<input type="hidden" name="is_folder_action" value="N"';
                } ?>
            <table align="center" width="100%" id="categories_association" >
                    <tr>
                        <td colspan="3"><?php echo _CHOOSE_CATEGORY_ASSOCIATION;?> : <br /> <small>(<?php echo _CHOOSE_CATEGORY_ASSOCIATION_HELP;?>)<small></td>
                    </tr>
                    <tr>
                        <td width="40%" align="center">
                            <select name="categorieslist[]" id="categorieslist" size="7"
                            ondblclick='moveclick($(categorieslist), $(categories_chosen));' multiple="multiple" >
                            <?php
                            foreach ($_SESSION['coll_categories'] as $collId => $collLabel) {
                                $state_category = false;
                                foreach ($collLabel as $catId => $catValue) {
                                    $j=0;
                                    $state_category = false;
                                    if ($catId <> 'default_category') {
                                        for ($j=0;$j<count($_SESSION['m_admin']['action']['categories']);$j++) {
                                            if ($catId == $_SESSION['m_admin']['action']['categories'][$j]) {
                                                $state_category = true;
                                            }
                                        }
                                        if ($state_category == false) {
                                            ?>
                                            <option value="<?php
                                                functions::xecho($catId);
                                                ?>"><?php
                                                functions::xecho($collId . ' / ' . $catValue);
                                                ?></option>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            </select>
                            <br/>
                        </td>
                        <td width="20%" align="center">
                            <input type="button" class="button" value="<?php
                                echo _ADD;
                                ?> &gt;&gt;" onclick='Move($(categorieslist), $(categories_chosen));' />
                            <br />
                            <br />
                            <input type="button" class="button" value="&lt;&lt; <?php
                                echo _REMOVE;
                                ?>" onclick='Move($(categories_chosen), $(categorieslist));' />
                        </td>
                        <td width="40%" align="center">
                            <select name="categories_chosen[]" id="categories_chosen" size="7"
                            ondblclick='moveclick($(categories_chosen), $(categorieslist));' multiple="multiple">
                            <?php
                            foreach ($_SESSION['coll_categories'] as $collId => $collLabel) {
                                $state_category = false;
                                foreach ($collLabel as $catId => $catValue) {
                                    $j=0;
                                    $state_category = false;
                                    if ($catId <> 'default_category') {
                                        for ($j=0;$j<count($_SESSION['m_admin']['action']['categories']);$j++) {
                                            if ($catId == $_SESSION['m_admin']['action']['categories'][$j]) {
                                                $state_category = true;
                                            }
                                        }
                                        if ($state_category == true) {
                                            ?>
                                            <option value="<?php
                                                functions::xecho($catId);
                                                ?>" selected="selected"><?php
                                                functions::xecho($collId . ' / ' . $catValue);
                                                ?></option>
                                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            </select>
                            <br/>
                            <!--<em><a href="javascript:selectall($(categories_chosen));" >
                            <?php echo _SELECT_ALL;?></a></em>-->
                        </td>
                    </tr>
                </table>
   
            <p class="buttons">

                <input type="submit" class="button" name="action_submit" onclick ="javascript:selectall($(categories_chosen));" value="<?php
                echo _VALIDATE;?>" />

                <input type="button" class="button"  name="cancel" value="<?php
                echo _CANCEL;?>" onclick="javascript:window.location.href='<?php
                echo $_SESSION['config']['businessappurl'];
                ?>index.php?page=action_management_controler&amp;mode=list&amp;admin=action';"/>
            </p>
        </form >
        <div class="infos"><?php echo _INFOS_ACTIONS;?></div>
        </div>
    </div>
    <?php
    }
}
