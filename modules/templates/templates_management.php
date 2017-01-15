<?php
/* View */
$func = new functions();
if ($mode == 'list') {
    $listShow = new list_show();
    $listShow->admin_list(
        $templates_list['tab'],
        count($templates_list['tab']),
        $templates_list['title'],
        'template_id',
        'templates_management_controler&mode=list',
        'templates','template_id',
        true,
        $templates_list['page_name_up'],
        '',
        '',
        $templates_list['page_name_del'],
        $templates_list['page_name_add'],
        $templates_list['label_add'],
        false,
        false,
        _ALL_TEMPLATES,
        _TEMPLATES,
        'file-text-o',
        true,
        true,
        false,
        true,
        $templates_list['what'],
        true,
        $templates_list['autoCompletionArray']
    );
} elseif ($mode == 'up' || $mode == 'add') {
    /*echo '<pre>';
    print_r($_SESSION['m_admin']['templatesStyles']);
    echo '</pre>';*/
    include('modules/templates/load_editor.php');
    ?>
    <h1><i class="fa fa-file-text-o fa-2x"></i>
        <?php
        if ($mode == 'add') {
            echo _TEMPLATE_ADDITION;
        }
        elseif ($mode == 'up') {
            echo _TEMPLATE_UPDATE;
        }
        ?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br/><br/>
        <?php
        if ($state == false) {
            echo '<br /><br />' . _THE_TEMPLATE . ' ' . _UNKOWN
                . '<br /><br /><br /><br />';
        } else {
            ?>
            <div class="error" id="divError" name="divError"></div>
            <div class="block">
            <form id="adminform" method="post" class="forms" style="width: 620px;" action="<?php
                echo $_SESSION['config']['businessappurl']
                . 'index.php?display=true&page=templates_management_controler&module=templates&mode='
                . $mode;
            ?>">
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="module" value="templates" />
                <input type="hidden" name="page" value="templates_management_controler" />
                <input type="hidden" name="mode" id="mode" value="<?php functions::xecho($mode);?>" />
                <input type="hidden" name="order" id="order" value="<?php
                    if (isset($_REQUEST['order'])) functions::xecho($_REQUEST['order']);
                ?>" />
                <input type="hidden" name="order_field" id="order_field" value="<?php
                    if (isset($_REQUEST['order_field'])) functions::xecho($_REQUEST['order_field']);
                ?>" />
                <input type="hidden" name="what" id="what" value="<?php
                    if (isset($_REQUEST['what'])) functions::xecho($_REQUEST['what']);
                ?>" />
                <input type="hidden" name="start" id="start" value="<?php
                    if (isset($_REQUEST['start'])) functions::xecho($_REQUEST['start']);
                ?>" />
                <?php
                if (
                    $mode == 'up'
                    && $_SESSION['m_admin']['templates']['template_type'] == 'OFFICE'
                ) {
                    ?>
                    <input type="hidden" name="template_path" id="template_path" value="<?php
                        echo $_SESSION['m_admin']['templates']['template_path'];
                    ?>" />
                    <input type="hidden" name="template_file_name" id="template_file_name" value="<?php
                        echo $_SESSION['m_admin']['templates']['template_file_name'];
                    ?>" />
                    <?php
                }
                if ($mode == 'up') {
                    ?>
                    <p>
                        <label for="id"><?php echo _TEMPLATE_ID;?> : </label>
                        <input name="id" type="text" id="id" value="<?php
                            if (isset($_SESSION['m_admin']['templates']['template_id'])) {
                                echo functions::xecho($func->show_str($_SESSION['m_admin']['templates']['template_id']));
                            }
                            ?>" readonly='readonly' class='readonly'/>
                    </p>
                    <?php
                }
                ?>
                <p>
                    <label for="template_label"><?php echo _TEMPLATE_LABEL;?> : </label>
                    <input name="template_label" type="text" id="template_label" value="<?php
                        if (isset($_SESSION['m_admin']['templates']['template_label'])) {
                            functions::xecho($func->show_str($_SESSION['m_admin']['templates']['template_label']));
                        }
                        ?>" />
                </p>
                <p>
                    <label for="template_comment"><?php echo _TEMPLATE_COMMENT;?> : </label>
                    <textarea name="template_comment" type="text"  id="template_comment" value="<?php
                        if (isset($_SESSION['m_admin']['templates']['template_comment'])) {
                            functions::xecho($func->show_str($_SESSION['m_admin']['templates']['template_comment']));
                        }
                        ?>" /><?php
                        if (isset($_SESSION['m_admin']['templates']['template_comment'])) {
                            functions::xecho($_SESSION['m_admin']['templates']['template_comment']);
                        }
                    ?></textarea>
                </p>
                <p>
                    <label for="template_target"><?php echo _TEMPLATE_TARGET;?> : </label>
                    <select name="template_target" id="template_target" onchange="setradiobutton(this.options[this.selectedIndex].value);">
                        <option value="" ><?php echo _NO_TARGET;?></option>
                        <?php
                        for (
                            $cptTarget = 0;
                            $cptTarget < count($_SESSION['m_admin']['templatesTargets']);
                            $cptTarget ++
                        ) {
                        ?>
                            <option value="<?php functions::xecho($_SESSION['m_admin']['templatesTargets'][$cptTarget]['id']);?>" <?php
                            if (isset($_SESSION['m_admin']['templates']['template_target'])
                                && $_SESSION['m_admin']['templates']['template_target']
                                    == $_SESSION['m_admin']['templatesTargets'][$cptTarget]['id']
                            ) {
                                echo 'selected="selected"';
                            }
                            ?> ><?php
                                functions::xecho($_SESSION['m_admin']['templatesTargets'][$cptTarget]['label']);
                            ?></option><?php
                        }
                        ?>
                    </select>
                </p>
                <div id="template_attachment_type_tr" style="display:none">
                <p>
                    <label for="template_attachment_type"><?php echo _ATTACHMENT_TYPES;?> : </label>
                    <select name="template_attachment_type" id="template_attachment_type">
                        <option value="all" ><?php echo _ALL_ATTACHMENT_TYPES;?></option>
                        <?php
                            foreach(array_keys($_SESSION['attachment_types']) as $attachmentType) {
                                ?><option value="<?php functions::xecho($attachmentType);?>" <?php

                                if (isset($_SESSION['m_admin']['templates']['template_attachment_type'])
                                    && 
                                    $_SESSION['m_admin']['templates']['template_attachment_type'] == $attachmentType
                                ) {
                                    echo 'selected="selected"';
                                }
                                ?> >
                                    <?php functions::xecho($_SESSION['attachment_types'][$attachmentType]);?>
                                </option>
                            <?php }
                        ?>
                    </select>
                </p>
                </div>
                <p>
                    <label><?php echo _TEMPLATE_TYPE;?> :</label>
                    <input type="radio" name="template_type" value="OFFICE" id="office"
                        onClick="javascript:show_special_form_3_elements('office_div', 'html_div', 'txt_div');" <?php
                        echo $checkedOFFICE;?>/> <span id="span_office"><?php echo _OFFICE;?></span>
                    <input type="radio" name="template_type" value="HTML" id="html"
                        onClick="javascript:show_special_form_3_elements('html_div', 'office_div', 'txt_div');" <?php
                        echo $checkedHTML;?>/> <span id="span_html"><?php echo _HTML;?></span>
                    <input type="radio" name="template_type" value="TXT" id="txt"
                        onClick="javascript:show_special_form_3_elements('txt_div', 'html_div', 'office_div');" <?php
                        echo $checkedTXT;?>/> <span id="span_txt"><?php echo _TXT;?></span>
                </p>
                <p>
                    <label for="template_datasource"><?php echo _TEMPLATE_DATASOURCE;?> : </label>
                    <select name="template_datasource" id="template_datasource">
                        <option value="" ><?php echo _NO_DATASOURCE;?></option>
                        <?php
                        for (
                            $cptDatasource = 0;
                            $cptDatasource < count($_SESSION['m_admin']['templatesDatasources']);
                            $cptDatasource ++
                        ) {
                        ?>
                            <option value="<?php functions::xecho($_SESSION['m_admin']['templatesDatasources'][$cptDatasource]['id']);?>" <?php
                            if (isset($_SESSION['m_admin']['templates']['template_datasource'])
                                && $_SESSION['m_admin']['templates']['template_datasource']
                                    == $_SESSION['m_admin']['templatesDatasources'][$cptDatasource]['id']
                            ) {
                                echo 'selected="selected"';
                            }
                            ?> ><?php
                                functions::xecho($_SESSION['m_admin']['templatesDatasources'][$cptDatasource]['label']);
                            ?></option><?php
                        }
                        ?>
                    </select>
                </p>
                <div id="html_div" name="html_div">
                    <p>
                        <label for="template_content">
                            <?php echo _TEMPLATE_CONTENT;?> HTML :
                        </label><br/><br/>
                        <textarea name="template_content" style="width:100%" rows="15" cols="60" id="template_content" value="<?php
                            if (isset($_SESSION['m_admin']['templates']['template_content'])) {
                                echo $func->show_str($_SESSION['m_admin']['templates']['template_content']);
                            }
                            ?>" /><?php
                            if (isset($_SESSION['m_admin']['templates']['template_content'])) {
                                functions::xecho($_SESSION['m_admin']['templates']['template_content']);
                            }
                        ?></textarea>
                    </p>
                </div>
                <div id="office_div" name="office_div">
                    <p>
                        <label for="template_style"><?php echo _TEMPLATE_STYLE;?> : </label>
                        <?php
                        if ($mode == 'up') {
                            ?>
                            <input name="template_style" type="text" id="template_style" value="<?php
                                if (isset($_SESSION['m_admin']['templates']['template_style'])) {
                                    functions::xecho($func->show_str($_SESSION['m_admin']['templates']['template_style']));
                                }
                                ?>" readonly='readonly' class='readonly' />
                            <?php
                        } else {
                            ?>
                            <select name="template_style" id="template_style" onChange="javascript:changeStyle($('template_style'), '<?php
                                echo $_SESSION['config']['businessappurl'] . 'index.php?display=false&module=templates&page=change_template_style';?>');">
                                <?php
                                // if user don't choose a style
                                if (!isset($_SESSION['m_admin']['templates']['current_style'])) {
                                    $_SESSION['m_admin']['templates']['current_style']
                                        = $_SESSION['m_admin']['templatesStyles'][0]['filePath'];
                                }
                                for (
                                    $cptStyle = 0;
                                    $cptStyle < count($_SESSION['m_admin']['templatesStyles']);
                                    $cptStyle ++
                                ) {
                                    ?>
                                    <option value="<?php
                                        functions::xecho($_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileExt']); echo ': ';
                                        functions::xecho($_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileName']);
                                    ?>" <?php
                                    if (isset($_SESSION['m_admin']['templates']['template_style'])
                                        && $_SESSION['m_admin']['templates']['template_style']
                                            == $_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileName']
                                    ) {
                                        echo 'selected="selected"';
                                    }
                                    ?>><?php
                                        functions::xecho($_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileExt']); echo ': ';
                                        functions::xecho($_SESSION['m_admin']['templatesStyles'][$cptStyle]['fileName']);
                                    ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <?php
                        }
                        ?>
                    </p>
                    <?php
                    if ($mode == 'add') {
                        $objectType = 'templateStyle';
                    } else {
                        $objectType = 'template';
                        $objectId = $_SESSION['m_admin']['templates']['template_id'];
                    }
                    $objectTable = _TEMPLATES_TABLE_NAME;
                    ?>
                    <p>
                        <label><?php echo _EDIT_TEMPLATE;?> :</label>
                        <div style="text-align:center;">
                                <?php
                                $strAction .= 'window.open(\''
                                    . $_SESSION['config']['businessappurl'] . 'index.php?display=true'
                                    . '&module=content_management&page=applet_popup_launcher&objectType='
                                    . $objectType
                                    . '&objectId='
                                    . $objectId
                                    . '&objectTable='
                                    . $objectTable
                                    . '\', \'\', \'height=200, width=250,scrollbars=no,resizable=no,directories=no,toolbar=no\');$(\'add\').value=\'Edition en cours ...\';setInterval(function() {checkEditingDoc(\''.$_SESSION['user']['UserId'].'\')}, 5000);$(\'add\').disabled=\'disabled\';$(\'add\').style.opacity=\'0.5\';';
                                ?>
                                <a href="#" onClick="<?php functions::xecho($strAction);?>">
                                
                                    <i class="fa fa-pencil fa-2x"></i>
                                <?php echo _EDIT_TEMPLATE;?>
                            </a>
                        </div>
                    </p>
                </div>
                <div id="txt_div" name="txt_div">
                    <p>
                        <label for="template_content_txt">
                            <?php echo _TEMPLATE_CONTENT;?> TXT :
                        </label><br/><br/>
                        <textarea name="template_content_txt" style="width:100%" rows="15" cols="60" id="template_content_txt" value="<?php
                            if (isset($_SESSION['m_admin']['templates']['template_content'])) {
                                functions::xecho($func->show_str($_SESSION['m_admin']['templates']['template_content']));
                            }
                            ?>" /><?php
                            if (isset($_SESSION['m_admin']['templates']['template_content'])) {
                                functions::xecho($_SESSION['m_admin']['templates']['template_content']);
                            }
                        ?></textarea>
                    </p>
                </div>
                    <table align="center" width="100%" id="template_entities" >
                    <tr>
                        <td colspan="3"><?php echo _CHOOSE_ENTITY_TEMPLATE;?> :</td>
                    </tr>
                    <tr>
                        <td width="40%" align="center">
                            <select name="entitieslist[]" id="entitieslist" size="7"
                            ondblclick='moveclick($(entitieslist), $(entities_chosen));' multiple="multiple" >
                            <?php
                            for ($i=0;$i<count($_SESSION['m_admin']['templatesEntitiesOrg']);$i++) {
                                $state_entity = false;
                                for ($j=0;$j<count($_SESSION['m_admin']['templatesEntities']['destination']);$j++) {
                                    if (
                                        $_SESSION['m_admin']['templatesEntitiesOrg'][$i]->entity_id
                                        == $_SESSION['m_admin']['templatesEntities']['destination'][$j]
                                    ) {
                                        $state_entity = true;
                                    }
                                }
                                if ($state_entity == false) {
                                    ?>
                                    <option value="<?php
                                        functions::xecho($_SESSION['m_admin']['templatesEntitiesOrg'][$i]->entity_id);
                                        ?>"><?php
                                        functions::xecho($_SESSION['m_admin']['templatesEntitiesOrg'][$i]->entity_label);
                                        ?></option>
                                    <?php
                                }
                            }
                            ?>
                            </select>
                            <br/>
                            <!--<em><a href='javascript:selectall($(entitieslist));'><?php
                                echo _SELECT_ALL;
                                ?></a></em>-->
                        </td>
                        <td width="20%" align="center">
                            <input type="button" class="button" value="<?php
                                
                                ?> &gt;&gt;" onclick='Move($(entitieslist), $(entities_chosen));' />
                            <br />
                            <br />
                            <input type="button" class="button" value="&lt;&lt; <?php
                                
                                ?>" onclick='Move($(entities_chosen), $(entitieslist));' />
                        </td>
                        <td width="40%" align="center">
                            <select name="entities_chosen[]" id="entities_chosen" size="7"
                            ondblclick='moveclick($(entities_chosen), $(entitieslist));' multiple="multiple">
                            <?php
                            for ($i=0;$i<count($_SESSION['m_admin']['templatesEntitiesOrg']);$i++) {
                                $state_entity = false;
                                for ($j=0;$j<count($_SESSION['m_admin']['templatesEntities']['destination']);$j++) {
                                    if (
                                        $_SESSION['m_admin']['templatesEntitiesOrg'][$i]->entity_id
                                        == $_SESSION['m_admin']['templatesEntities']['destination'][$j]
                                    ) {
                                        $state_entity = true;
                                    }
                                }
                                if ($state_entity == true) {
                                    ?>
                                    <option value="<?php
                                        functions::xecho($_SESSION['m_admin']['templatesEntitiesOrg'][$i]->entity_id);
                                        ?>" selected="selected" ><?php
                                        functions::xecho($_SESSION['m_admin']['templatesEntitiesOrg'][$i]->entity_label);
                                        ?></option>
                                    <?php
                                }
                            }
                            ?>
                            </select>
                            <br/>
                            <!--<em><a href="javascript:selectall($(entities_chosen));" >
                            <?php echo _SELECT_ALL;?></a></em>-->
                        </td>
                    </tr>
                </table>
                <br/>
                <p class="buttons">
                    <?php
                    if ($mode == "up") {
                        ?>
                        <input class="button" type="submit" name="submit" id="add" onclick="selectall($('entities_chosen'));" value="<?php
                            echo _MODIFY;
                        ?>" />
                        <?php
                    } elseif ($mode == "add") {
                        ?>
                        <input type="submit" class="button"  name="submit" id="add" onclick="selectall($('entities_chosen'));" value="<?php
                            echo _ADD;
                        ?>" />
                        <?php
                    }
                    ?>
                    <input type="button" class="button"  name="cancel" value="<?php
                        echo _CANCEL;
                        ?>" onclick="javascript:window.location.href='<?php
                        echo $_SESSION['config']['businessappurl'];
                        ?>index.php?page=templates_management_controler&amp;module=templates&amp;mode=list';"/>
                </p>
            </form>
            </div>
            <?php
            if($_SESSION['m_admin']['templates']['template_type'] == 'HTML') {
                ?>
                <script>
                    show_special_form_3_elements('html_div', 'txt_div', 'office_div');
                </script>
                <?php
            } elseif ($_SESSION['m_admin']['templates']['template_type'] == 'TXT') {
                ?>
                <script>
                    show_special_form_3_elements('txt_div', 'office_div', 'html_div');
                </script>
                <?php
            } else {
                ?>
                <script>
                    show_special_form_3_elements('office_div', 'html_div', 'txt_div');
                </script>
                <?php
            }

            if (isset($_SESSION['m_admin']['templates']['template_target'])
                && $_SESSION['m_admin']['templates']['template_target']
                    == "notes"
            ) {
                ?>
                <script>
                    setradiobutton("notes");
                </script>
                <?php                
            } else if (isset($_SESSION['m_admin']['templates']['template_target'])
                && $_SESSION['m_admin']['templates']['template_target']
                    == "notifications"
            ) {
                ?>
                <script>
                    setradiobutton("notifications");
                </script>
                <?php                
            } else if (isset($_SESSION['m_admin']['templates']['template_target'])
                && $_SESSION['m_admin']['templates']['template_target']
                    == "attachments"
            ) {
                ?>
                <script>
                    setradiobutton("attachments");
                </script>
                <?php                
            }
        }
        ?>
    </div>
    <?php
}
