<?php

/* View */
$func = new functions();
if ($mode == "list") {
    $listshow = new list_show();
    $listshow->admin_list(
        $lc_cycle_steps_list['tab'],
        count($lc_cycle_steps_list['tab']),
        $lc_cycle_steps_list['title'],
        'cycle_step_id',
        'lc_cycle_steps_management_controler&mode=list',
        'life_cycle', 'cycle_step_id',
        true,
        $lc_cycle_steps_list['page_name_up'],
        '',
        '',
        $lc_cycle_steps_list['page_name_del'],
        $lc_cycle_steps_list['page_name_add'],
        $lc_cycle_steps_list['label_add'],
        false,
        false,
        _ALL_LC_CYCLE_STEPS,
        _LC_CYCLE_STEP,
        'recycle',
        true,
        true,
        false,
        true,
        $lc_cycle_steps_list['what'],
        true,
        $lc_cycle_steps_list['autoCompletionArray']
    );
} elseif ($mode == "up" || $mode == "add") {
    ?>
    <h1><i class="fa fa-gears fa-2x"></i>
    <?php
    if ($mode == "add") {
        echo _LC_CYCLE_STEP_ADDITION;
    } elseif ($mode == "up") {
        echo _LC_CYCLE_STEP_MODIFICATION;
    }
    ?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br><br>
    <?php
    if ($state == false) {
        echo "<br /><br />"._THE_LC_CYCLE_STEP." "._UNKOWN
            ."<br /><br /><br /><br />";
    } else {
            ?>
            <div class="block">
            <form name="formdocserver" method="post" style="width:580px;" class="forms" 
            action="<?php echo $_SESSION['config']['businessappurl']
                ."index.php?display=true&page="
                ."lc_cycle_steps_management_controler&module=life_cycle&mode="
                .$mode;?>">
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="life_cycle" />
                <input type="hidden" name="page" value="
                lc_cycle_steps_management_controler" />
                <input type="hidden" name="mode" id="mode" 
                value="<?php functions::xecho($mode);?>" />
                <input type="hidden" name="order" id="order" 
                value="<?php if (isset($_REQUEST['order'])) { functions::xecho($_REQUEST['order']);}
        ?>" />
                <input type="hidden" name="order_field" 
                id="order_field" 
                value="<?php if (isset($_REQUEST['order_field'])) { functions::xecho($_REQUEST['order_field']); }
        ?>" />
                <input type="hidden" name="what" id="what" value="<?php if (isset($_REQUEST['what'])) { functions::xecho($_REQUEST['what']); }
        ?>" />
                <input type="hidden" name="start" id="start" value="<?php if (isset($_REQUEST['start'])) { functions::xecho($_REQUEST['start']); }
        ?>" />
        <?php 
        if ($mode == "up") {
            echo '<p><a href="' . $_SESSION['config']['businessappurl']
                . '?page=lc_policies_management_controler&mode=up&'
                . 'module=life_cycle&id=' . $_SESSION['m_admin']['lc_cycle_steps']['policy_id'] 
                . '"><i class="fa fa-gears fa-2x" title="' . _VIEW_GENERAL_PARAMETERS_OF_THE_POLICY . ' ' 
                . _LC_CYCLE_STEP . '"></i>&nbsp;' . _VIEW_GENERAL_PARAMETERS_OF_THE_POLICY . '</a></p>';
                    ?>
                    <p>
                        <label for="policy_id"><?php echo _POLICY_ID;?> : 
                        </label>
                        <input name="policy_id" type="text"  id="policy_id" 
                        value="<?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['policy_id'])) { functions::xecho($func->show_str($_SESSION['m_admin']['lc_cycle_steps']['policy_id']));
            }
            ?>" readonly='readonly' class='readonly'/>
                    </p>
                    <?php
        } else {
                    ?>
                    <p>
                        <label for="policy_id"><?php echo _POLICY_ID;?> : 
                        </label>
                        <select name="policy_id" id="policy_id" 
            onchange="changeCycle('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=life_cycle&page=change_cycle_list');">
                            <option value=""><?php echo _POLICY_ID;?></option>
            <?php
            for ($cptPolicies = 0;$cptPolicies < count($policiesArray);$cptPolicies++) {
                ?>
                <option value="<?php functions::xecho($policiesArray[$cptPolicies]);?>" 
                <?php 
                if (isset($_SESSION['m_admin']['lc_cycle_steps']['policy_id']) 
                    && $_SESSION['m_admin']['lc_cycle_steps']
                        ['policy_id'] == $policiesArray[$cptPolicies]
                ) {
                    echo 'selected="selected"';
                }
                ?>><?php functions::xecho($policiesArray[$cptPolicies]);?></option>
                <?php
            }
            ?>
                        </select>
                    </p>
            <?php
        }
        ?>
        <?php 
        if (!empty($_SESSION['m_admin']['lc_cycle_steps']
        ['policy_id']) && $mode == "up"
        ) {
            ?>
                    <p>
                        <label for="cycle_id"><?php echo _CYCLE_ID;?> : </label>
                        <input name="cycle_id" type="text"  id="cycle_id" value="<?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['cycle_id'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_cycle_steps']['cycle_id']));?>" readonly='readonly' class='readonly'/>
                    </p>
        <?php
        } else {
            ?>
            <div name="cycle_div" id="cycle_div"></div>
            <?php
        }
        ?>
                <p>
                    <label for="id"><?php echo _CYCLE_STEP_ID;?> : </label>
                    <input name="id" type="text"  id="id" value="<?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['cycle_step_id'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_cycle_steps']['cycle_step_id']));?>" <?php if($mode == "up") echo " readonly='readonly' class='readonly'";?>/>
                </p>
                <p>
                    <label for="cycle_step_desc"><?php echo _CYCLE_STEP_DESC;?> : </label>
                    <textarea name="cycle_step_desc" type="text" id="cycle_step_desc" value="<?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['cycle_step_desc'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_cycle_steps']['cycle_step_desc']));?>" ><?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['cycle_step_desc'])) echo $_SESSION['m_admin']['lc_cycle_steps']['cycle_step_desc'];?></textarea>
                </p>
                <p>
                    <label for="docserver_type_id"><?php echo _DOCSERVER_TYPE_ID;?> : </label>
                    <select name="docserver_type_id" id="docserver_type_id">
                            <option value=""><?php echo _DOCSERVER_TYPE_ID;?></option>
                            <?php
                            for ($cptDocserverTypes = 0;$cptDocserverTypes < count($docserverTypesArray);$cptDocserverTypes++){
                                ?>
                                <option value="<?php functions::xecho($docserverTypesArray[$cptDocserverTypes]);?>" <?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['docserver_type_id']) && $_SESSION['m_admin']['lc_cycle_steps']['docserver_type_id'] == $docserverTypesArray[$cptDocserverTypes]) { echo 'selected="selected"';}?>><?php functions::xecho($docserverTypesArray[$cptDocserverTypes]);?></option>
                                <?php
                            }
                            ?>
                    </select>
                </p>
                <!--<p>
                    <label><?php echo _IS_ALLOW_FAILURE;?> : </label>
                    <input type="radio" class="check" name="is_allow_failure" value="true" <?php if (isset($_SESSION['m_admin']['docservers']['is_allow_failure']) && $_SESSION['m_admin']['docservers']['is_allow_failure']) {?> checked="checked"<?php } ?> /><?php echo _YES;?>
                    <input type="radio" class="check" name="is_allow_failure" value="false" <?php if (isset($_SESSION['m_admin']['docservers']['is_allow_failure']) && (!$_SESSION['m_admin']['docservers']['is_allow_failure'] || $_SESSION['m_admin']['docservers']['is_allow_failure'] == '')) {?> checked="checked"<?php } elseif (!isset($_SESSION['m_admin']['docservers']['is_allow_failure'])) {?> checked="checked"<?php }?> /><?php echo _NO;?>
                </p>-->
                <p>
                    <label for="step_operation"><?php echo _STEP_OPERATION;?> : </label>
                    <select name="step_operation" id="step_operation">
                        <?php
                        for ($cptStepOperation = 0;$cptStepOperation < count($_SESSION['lifeCycleFeatures']['LIFE_CYCLE']['PROCESS']['MODE']);$cptStepOperation++){
                            ?>
                            <option value="<?php if (isset($_SESSION['lifeCycleFeatures']['LIFE_CYCLE']['PROCESS']['MODE'][$cptStepOperation])) functions::xecho($_SESSION['lifeCycleFeatures']['LIFE_CYCLE']['PROCESS']['MODE'][$cptStepOperation]);?>" <?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['step_operation']) && $_SESSION['m_admin']['lc_cycle_steps']['step_operation'] == $_SESSION['lifeCycleFeatures']['LIFE_CYCLE']['PROCESS']['MODE'][$cptStepOperation]) { echo 'selected="selected"';}?>><?php if (isset($_SESSION['lifeCycleFeatures']['LIFE_CYCLE']['PROCESS']['MODE'][$cptStepOperation])) echo $_SESSION['lifeCycleFeatures']['LIFE_CYCLE']['PROCESS']['MODE'][$cptStepOperation];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <label for="sequence_number"><?php echo _SEQUENCE_NUMBER;?> : </label>
                    <input name="sequence_number" type="text"  id="sequence_number" value="<?php if (isset($_SESSION['m_admin']['lc_cycle_steps']['sequence_number'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_cycle_steps']['sequence_number']));?>" />
                </p>
                <p class="buttons">
                    <?php
                    if ($mode == "up"){
                        ?>
                        <input class="button" type="submit" name="submit" value="<?php echo _MODIFY;?>" />
                        <?php
                    } elseif ($mode == "add") {
                        ?>
                        <input type="submit" class="button"  name="submit" value="<?php echo _ADD;?>" />
                        <?php
                    }
                    ?>
                   <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=lc_cycle_steps_management_controler&amp;module=life_cycle&amp;mode=list';"/>
                </p>
            </form>
            </div>
            <?php
            ?>
            <script type="text/javascript">
                //on load show cycle if necessary
                <?php
                if (isset($_SESSION['m_admin']['lc_cycle_steps']['cycle_id']) && $_SESSION['m_admin']['lc_cycle_steps']['cycle_id'] <> "") {
                    ?>
                    changeCycle('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=life_cycle&page=change_cycle_list');
                    <?php
                }
                ?>
            </script>
            <?php
        }
        ?>
    </div>
    <?php
}
