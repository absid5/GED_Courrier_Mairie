<script language="javascript">
    function showHideElem(elem)
    {
        var elem_1 = window.document.getElementById(elem);
        if (elem_1 != null && elem_1.style.display == "block") {
            elem_1.style.display = "none";
        } else if (elem_1 != null && elem_1.style.display == "none") {
            elem_1.style.display = "block";
        }
    }
</script>
<?php
/* View */
$func = new functions();
if($mode == "list"){
    $listShow = new list_show();
    $listShow->admin_list(
        $lc_policies_list['tab'],
        count($lc_policies_list['tab']),
        $lc_policies_list['title'],
        'policy_id',
        'lc_policies_management_controler&mode=list',
        'life_cycle','policy_id',
        true,
        $lc_policies_list['page_name_up'],
        '',
        '',
        $lc_policies_list['page_name_del'],
        $lc_policies_list['page_name_add'],
        $lc_policies_list['label_add'],
        false,
        false,
        _ALL_LC_POLICIES,
        _LC_POLICY,
        'recycle',
        true,
        true,
        false,
        true,
        $lc_policies_list['what'],
        true,
        $lc_policies_list['autoCompletionArray']
    );
} elseif($mode == "up" || $mode == "add") {
    ?>
    <h1><i class="fa fa-recycle fa-2x"></i>
        <?php
        if($mode == "add") {
            echo _LC_POLICY_ADDITION;
        }
        elseif($mode == "up") {
            echo _LC_POLICY_MODIFICATION;
        }
        ?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br/><br/>
        <?php
        if($state == false) {
            echo "<br /><br />"._THE_LC_POLICY." "._UNKOWN."<br /><br /><br /><br />";
        } else {
            ?>
            <div class="block">
            <form id="adminform" method="post" class="forms" style="width:400px;" action="<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&page=lc_policies_management_controler&module=life_cycle&mode=".$mode;?>">
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="life_cycle" />
                <input type="hidden" name="page" value="lc_policies_management_controler" />
                <input type="hidden" name="mode" id="mode" value="<?php functions::xecho($mode);?>" />
                <input type="hidden" name="order" id="order" value="<?php if(isset($_REQUEST['order'])) functions::xecho($_REQUEST['order']);?>" />
                <input type="hidden" name="order_field" id="order_field" value="<?php if(isset($_REQUEST['order_field'])) functions::xecho($_REQUEST['order_field']);?>" />
                <input type="hidden" name="what" id="what" value="<?php if(isset($_REQUEST['what'])) functions::xecho($_REQUEST['what']);?>" />
                <input type="hidden" name="start" id="start" value="<?php if(isset($_REQUEST['start'])) functions::xecho($_REQUEST['start']);?>" />
                <p>
                    <label for="id"><?php echo _LC_POLICY_ID;?> : </label>
                    <input name="id" type="text"  id="id" value="<?php if(isset($_SESSION['m_admin']['lc_policies']['policy_id'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_policies']['policy_id']));?>" <?php if($mode == "up") echo " readonly='readonly' class='readonly'";?>/>
                </p>
                <p>
                    <label for="policy_name"><?php echo _LC_POLICY_NAME;?> : </label>
                    <input name="policy_name" type="text"  id="policy_name" value="<?php if(isset($_SESSION['m_admin']['lc_policies']['policy_name'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_policies']['policy_name']));?>" />
                </p>
                <p>
                    <label for="policy_desc"><?php echo _POLICY_DESC;?> : </label>
                    <textarea name="policy_desc" type="text"  id="policy_desc" value="<?php if(isset($_SESSION['m_admin']['lc_policies']['policy_desc'])) functions::xecho($func->show_str($_SESSION['m_admin']['lc_policies']['policy_desc']));?>" /><?php if(isset($_SESSION['m_admin']['lc_policies']['policy_desc'])) echo $_SESSION['m_admin']['lc_policies']['policy_desc'] ?></textarea>
                </p>
                <p class="buttons">
                    <?php
                    if($mode == "up") {
                        ?>
                        <input class="button" type="submit" name="submit" value="<?php echo _MODIFY;?>" />
                        <?php
                    } elseif($mode == "add") {
                        ?>
                        <input type="submit" class="button"  name="submit" value="<?php echo _ADD;?>" />
                        <?php
                    }
                    ?>
                   <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onclick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=lc_policies_management_controler&amp;module=life_cycle&amp;mode=list';"/>
                </p>
            </form>
            <?php
        }
        if (
            isset($_SESSION['m_admin']['lc_policies']['tabWf']) 
            && $_SESSION['m_admin']['lc_policies']['tabWf'] <> ''
        ) {
            if ($_SESSION['m_admin']['lc_policies']['tabWf']['status'] <> 'ko') {
                $content = '';
                $content .= '<hr/>';                
                for (
                    $cptCycles = 0;
                    $cptCycles < count($_SESSION['m_admin']['lc_policies']
                        ['tabWf']['value']['cycles']);
                    $cptCycles++
                ) {
                    $content .= '<table border="1" rules="rows">';
                    if ($cptCycles == 0) {
                        $content .= '<tr>';
                            $content .= '<th align="left" width="180px">' 
                                . _CYCLE_ID . '</th>';
                            $content .= '<th align="left" width="300px">' 
                                . _CYCLE_DESC . '</th>';
                            $content .= '<th align="left" width="300px">' 
                                . _WHERE_CLAUSE . '</th>';
                            $content .= '<th align="left" width="100px">' 
                                . _BREAK_KEY . '</th>';
                            $content .= '<th align="right" width="100px">' 
                                . _SEQUENCE_NUMBER . '</th>';
                        $content .= '</tr>';
                    }
                    $content .= '<tr>';
                        $link = '<a href="#" onclick="javascript:showHideElem(\'step' 
                            . $cptCycles . '\');">';
                        $content .= '<td align="left" width="180px">';
                        $content .= '<a href="' . $_SESSION['config']['businessappurl'] 
                            . '?page=lc_cycles_management_controler&mode=up&'
                            . 'module=life_cycle&id=' . $_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]
                            ['cycle_id'] . '">';
                        $content .= '<i class="fa fa-gears fa-2x" title="' . _SETUP . ' ' 
                            . _LC_CYCLE . '"></i></a>&nbsp;';
                        $content .= $link;
                        $content .= $_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]
                            ['cycle_id'];
                        $content .= '</a>';
                        $content .= '</td>';
                        $content .= '<td align="left" width="300px">';
                        $content .= $link;
                        $content .= $_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]
                            ['cycle_desc'];
                        $content .= '</a>';
                        $content .= '</td>';
                        $content .= '<td align="left" width="300px">';
                        $content .= $link;
                        $content .= $_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]
                            ['where_clause'];
                        $content .= '</a>';
                        $content .= '</td>';
                        $content .= '<td align="left" width="100px">';
                        $content .= $link;
                        $content .= $_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]
                            ['break_key'];
                        $content .= '</a>';
                        $content .= '</td>';
                        $content .= '<td align="right" width="100px">';
                        $content .= $link;
                        $content .= $_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]
                            ['sequence_number'];
                        $content .= '</a>';
                        $content .= '&nbsp;</td>';
                    $content .= '</tr>';
                    $content .= '</table>';
                    $content .= '<br/>';
                    $content .= '<div id="step' . $cptCycles . '" style="display:none;">';
                    for (
                        $cptSteps = 0;
                        $cptSteps < count($_SESSION['m_admin']['lc_policies']
                            ['tabWf']['value']['cycles'][$cptCycles]['steps']);
                        $cptSteps++
                    ) {
                        $content .= '<table border="0" rules="rows">';
                        if ($cptSteps == 0) {
                            $content .= '<tr>';
                            $content .= '<th align="left" width="180px"><small>' 
                                . _CYCLE_STEP_ID . '</small></th>';
                            $content .= '<th align="left" width="300px"><small>' 
                                . _CYCLE_STEP_DESC . '</small></th>';
                            $content .= '<th align="left" width="200px"><small>' 
                                . _DOCSERVER_TYPE_ID . '</small></th>';
                            $content .= '<th align="left" width="150px"><small>' 
                                . _STEP_OPERATION . '</small></th>';
                            $content .= '<th align="left" width="80px"><small>' 
                                . _SEQUENCE_NUMBER . '</small></th>';
                            $content .= '</tr>';
                        }
                            $content .= '<tr>';
                                $content .= '<td align="left" width="180px"><small>';
                                $content .= '<a href="' . $_SESSION['config']['businessappurl'] 
                                    . '?page=lc_cycle_steps_management_controler&mode=up&'
                                    . 'module=life_cycle&id=' . $_SESSION['m_admin']['lc_policies']
                                    ['tabWf']['value']['cycles'][$cptCycles]
                                    ['steps'][$cptSteps]['cycle_step_id'] . '">';
                                $content .= '<i class="fa fa-gears fa-2x" title="' . _SETUP . ' ' 
                                         . _LC_CYCLE_STEP . '"></i></a>&nbsp;';
                                $content .= $_SESSION['m_admin']['lc_policies']
                                    ['tabWf']['value']['cycles'][$cptCycles]
                                    ['steps'][$cptSteps]['cycle_step_id'];
                                $content .= '</small></td>';
                                $content .= '<td align="left" width="300px"><small>';
                                $content .= $_SESSION['m_admin']['lc_policies']
                                    ['tabWf']['value']['cycles'][$cptCycles]
                                    ['steps'][$cptSteps]['cycle_step_desc'];
                                $content .= '</small></td>';
                                $content .= '<td align="left" width="200px"><small>';
                                $content .= $_SESSION['m_admin']['lc_policies']
                                    ['tabWf']['value']['cycles'][$cptCycles]
                                    ['steps'][$cptSteps]['docserver_type_id'];
                                $content .= '</small></td>';
                                $content .= '<td align="left" width="150px"><small>';
                                $content .= $_SESSION['m_admin']['lc_policies']
                                    ['tabWf']['value']['cycles'][$cptCycles]
                                    ['steps'][$cptSteps]['step_operation'];
                                $content .= '</small></td>';
                                $content .= '<td align="right" width="80px"><small>';
                                $content .= $_SESSION['m_admin']['lc_policies']
                                    ['tabWf']['value']['cycles'][$cptCycles]
                                    ['steps'][$cptSteps]['sequence_number'];
                                $content .= '&nbsp;</small></td>';
                            $content .= '</tr>';
                        $content .= '</table>';
                    }
                    $content .= '<br/>';
                    $content .= '</div>';
                }
                $content .= '<hr/>';
                echo $content;
            } else {
                echo $_SESSION['m_admin']['lc_policies']['tabWf']['error'];
            }
        }
        ?>
        </div>
    </div>
    <?php
}
