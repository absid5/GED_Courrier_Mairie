<?php
/* View */
if ($mode == "list") {
    $listShow = new list_show();
    $listShow->admin_list(
        $docserversList['tab'], count($docserversList['tab']),
        $docserversList['title'], 'docserver_id',
        'docservers_management_controler&mode=list', 'docservers',
        'docserver_id', true, $docserversList['page_name_up'],
        $docserversList['page_name_val'], $docserversList['page_name_ban'],
        $docserversList['page_name_del'], $docserversList['page_name_add'],
        $docserversList['label_add'], false, false, _ALL_DOCSERVERS, _DOCSERVER,
        'hdd-o', false, true,
        false, true, $docserversList['what'], true,
        $docserversList['autoCompletionArray']
    );
} elseif ($mode == "up" || $mode == "add") {
    $func = new functions();
    if ($mode == "add" ) {
        $isModuleDocserver = false;
    } elseif ($mode == "up" ) {
        $isModuleDocserver = true;
        if (isset($_SESSION['m_admin']['docservers']['coll_id'])) {
            foreach($_SESSION['collections'] as $collection) {
                if ($_SESSION['m_admin']['docservers']['coll_id'] == $collection['id']) {
                    $isModuleDocserver = false;
                }
            }
        }
    }
    ?>
    <h1><i class="fa fa-hdd-o fa-2x"></i>
        <?php
    if ($mode == "add") {
        echo _DOCSERVER_ADDITION;
    } elseif ($mode == "up") {
        echo _DOCSERVER_MODIFICATION;
    }
        ?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br><br>
        <?php
    if ($state == false) {
        echo "<br /><br />" . _THE_DOCSERVER . " " . _UNKOWN
            . "<br /><br /><br /><br />";
    } else {
        ?>
        <div class="block">
        <form name="formdocserver" method="post" style="width:650px;" class="forms" action="<?php
        echo $_SESSION['config']['businessappurl'] . "index.php?display=true&"
            . "page=docservers_management_controler&admin=docservers&mode="
            . $mode;
        ?>">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="admin" value="docservers" />
        <input type="hidden" name="page" value="docservers_management_controler"/>
        <input type="hidden" name="mode" id="mode" value="<?php functions::xecho($mode);?>"/>
        <input type="hidden" name="order" id="order" value="<?php
        if (isset($_REQUEST['order'])) {
            functions::xecho($_REQUEST['order']);
        }
        ?>" />
        <input type="hidden" name="order_field" id="order_field" value="<?php
        if (isset($_REQUEST['order_field'])) {
            functions::xecho($_REQUEST['order_field']);
        }
        ?>" />
        <input type="hidden" name="what" id="what" value="<?php
        if (isset($_REQUEST['what'])) {
            functions::xecho($_REQUEST['what']);
        }
        ?>" />
        <input type="hidden" name="start" id="start" value="<?php
        if (isset($_REQUEST['start'])) {
            functions::xecho($_REQUEST['start']);
        }
        ?>" />
        <input type="hidden" name="size_limit_hidden"  value="<?php
        if (isset($_SESSION['m_admin']['docservers']['size_limit_number'])) {
            functions::xecho($_SESSION['m_admin']['docservers']['size_limit_number']);
        }
        ?>" id="size_limit_hidden"/>
        <input type="hidden" name="actual_size_hidden"  value="<?php
        if (isset($_SESSION['m_admin']['docservers']['actual_size_number'])) {
            functions::xecho($_SESSION['m_admin']['docservers']['actual_size_number']);
        }
        ?>" id="actual_size_hidden"/>
        <p>
            <label for="id"><?php echo _DOCSERVER_ID;?> : </label>
            <input name="id" type="text"  id="id" value="<?php
        if (isset($_SESSION['m_admin']['docservers']['docserver_id'])) {
            functions::xecho(
                $_SESSION['m_admin']['docservers']['docserver_id']
            );
        }
        ?>" <?php
        if ($mode == "up") {
            echo " readonly='readonly' class='readonly'";
        }
        ?> style="margin-left:7px;"/><i class="fa fa-star red_asterisk"></i>
        </p>
        <p>
            <label for="docserver_type_id"><?php echo _DOCSERVER_TYPES;?> : </label>
            <?php
            /*if (isset($_SESSION['m_admin']['docservers']['docserver_type_id'])
                    && $_SESSION['m_admin']['docservers']['docserver_type_id'] == 'TEMPLATES' */
            if($isModuleDocserver) {
                ?>
                <input name="docserver_type_id" type="text"  id="docserver_type_id" value="<?php functions::xecho($_SESSION['m_admin']['docservers']['docserver_type_id']);?>" readonly="readonly" class="readonly" style="margin-left:7px;"/><i class="fa fa-star red_asterisk"></i>
                <?php
            } else {
                for ($cptTypes = 0; $cptTypes < count($docserverTypesArray);
                    $cptTypes ++
                ) {
                    if (isset($_SESSION['m_admin']['docservers']['docserver_type_id'])
                        && $_SESSION['m_admin']['docservers']['docserver_type_id'] == $docserverTypesArray[$cptTypes]
                    ) {
                        $docserverTypeTxt = $docserverTypesArray[$cptTypes];
                    }
                }
                if (isset($_SESSION['m_admin']['docservers']['link_exists']) 
                    && $_SESSION['m_admin']['docservers']['link_exists']
                ) {
                    ?>
                    <input type="hidden" name="docserver_type_id" value="<?php 
                        functions::xecho($_SESSION['m_admin']['docservers']['docserver_type_id']);?>" />
                    <input name="docserver_type_id_txt" type="text"  id="docserver_type_id_txt" value="<?php
                        functions::xecho($docserverTypeTxt);
                    ?>" readonly class="readonly"/>
                    <?php
                } else {
                    ?>
                    <select name="docserver_type_id" id="docserver_type_id" style="margin-left:7px;">
                        <option value=""><?php echo _DOCSERVER_TYPES;?></option>
                        <?php
                    for ($cptTypes = 0; $cptTypes < count($docserverTypesArray);
                        $cptTypes ++
                    ) {
                        ?>
                        <option value="<?php functions::xecho($docserverTypesArray[$cptTypes]);?>" <?php
                        if (isset($_SESSION['m_admin']['docservers']['docserver_type_id'])
                            && $_SESSION['m_admin']['docservers']['docserver_type_id'] == $docserverTypesArray[$cptTypes]
                        ) {
                            echo 'selected="selected"';
                        }
                        ?>><?php functions::xecho($docserverTypesArray[$cptTypes]);?></option>
                        <?php
                    }
                    ?>
                    </select><i class="fa fa-star red_asterisk"></i>
                    <?php
                }
            }
            ?>
        </p>
        <p>
            <label for="device_label"><?php echo _DEVICE_LABEL;?> : </label>
            <input name="device_label" type="text"  id="device_label" value="<?php
        if (isset($_SESSION['m_admin']['docservers']['device_label'])) {
            functions::xecho(
                $_SESSION['m_admin']['docservers']['device_label']
            );
        }
        ?>" style="margin-left:7px;"/><i class="fa fa-star red_asterisk"></i>
        </p>
        <p>
            <label><?php echo _IS_READONLY;?> : </label>
            <input type="radio" class="check" name="is_readonly" value="true" <?php
        if (isset($_SESSION['m_admin']['docservers']['is_readonly'])
            && $_SESSION['m_admin']['docservers']['is_readonly'] == "Y"
        ) {
            ?> checked="checked"<?php
        }
        ?> /><?php echo _YES;?>
            <input type="radio" class="check" name="is_readonly" value="false" <?php
        if (!isset($_SESSION['m_admin']['docservers']['is_readonly'])
            || (!($_SESSION['m_admin']['docservers']['is_readonly'] == "Y")
            || $_SESSION['m_admin']['docservers']['is_readonly'] == '')
        ) {
            ?> checked="checked"<?php
        }
        ?> /><?php echo _NO;?>
        </p>
        <p>
            <label for="size_format"><?php echo _SIZE_FORMAT;?> : </label>
            <select name="size_format" id="size_format" onChange="javascript:convertSize();">
                <option value="GB"><?php echo _GB;?></option>
                <option value="TB"><?php echo _TB;?></option>
                <option value="MB"><?php echo _MB;?></option>
            </select>
        </p>
        <p>
            <label for="size_limit_number"><?php echo _SIZE_LIMIT;?> : </label>
            <input name="size_limit_number" type="text" id="size_limit_number" value="<?php
        if (isset($_SESSION['m_admin']['docservers']['size_limit_number'])) {
            functions::xecho(
                $_SESSION['m_admin']['docservers']['size_limit_number']
            );
        }
        ?>" onChange="javascript:saveSizeInBytes();"/>
        </p>
        <?php
        if ($mode == "up") {
            ?>
            <p>
                <label for="actual_size_number"><?php
            echo _ACTUAL_SIZE;
            ?> : </label>
                <input name="actual_size_number" type="text" id="actual_size_number" value="<?php
            if (isset($_SESSION['m_admin']['docservers']['actual_size_number'])) {
                functions::xecho(
                    $_SESSION['m_admin']['docservers']['actual_size_number']
                );
            }
            ?>" readonly class="readonly"/>
            </p>
            <p>
                <label for="percentage_full"><?php
            echo _PERCENTAGE_FULL;
            ?> : </label>
                <input name="percentage_full" type="text" id="percentage_full" value="<?php
            if (isset($_SESSION['m_admin']['docservers']['actual_size_number'])
                && isset($_SESSION['m_admin']['docservers']['size_limit_number'])
                && ($_SESSION['m_admin']['docservers']['actual_size_number'] <> 0
                && $_SESSION['m_admin']['docservers']['size_limit_number'] <> 0)
            ) {
                functions::xecho(
                    (100 * $_SESSION['m_admin']['docservers']['actual_size_number']) / $_SESSION['m_admin']['docservers']['size_limit_number']
                );
            }
            ?>%" readonly class="readonly"/>
            </p>
            <?php
        }
        ?>
        <p>
            <label for="path_template"><?php echo _PATH_TEMPLATE;?> : </label>
            <input name="path_template" type="text"  id="path_template" value="<?php
        if (isset($_SESSION['m_admin']['docservers']['path_template'])) {
            functions::xecho($_SESSION['m_admin']['docservers']['path_template']);
        }
        ?>"/>
        </p>
        
        <p>
            <label for="coll_id"><?php echo _COLLECTION;?> : </label>
            <?php
            for ($cptCollection = 0; $cptCollection < count(
                $_SESSION['collections']
            ); $cptCollection ++
            ) {
                if (isset($_SESSION['m_admin']['docservers']['coll_id'])
                    && $_SESSION['m_admin']['docservers']['coll_id'] == $_SESSION['collections'][$cptCollection]['id']
                ) {
                    $collTxt = $_SESSION['collections'][$cptCollection]['id'] . " : "
                    . $_SESSION['collections'][$cptCollection]['label'];
                }
            }
            if (isset($_SESSION['m_admin']['docservers']['link_exists']) 
                && $_SESSION['m_admin']['docservers']['link_exists']
            ) {
                ?>
                <input type="hidden" name="coll_id" value="<?php 
                    functions::xecho($_SESSION['m_admin']['docservers']['coll_id']);?>" />
                <input name="coll_id_txt" type="text"  id="coll_id_txt" value="<?php
                    functions::xecho($collTxt);
                ?>" readonly class="readonly"/>
                <?php
            } else {
                //if (isset($_SESSION['m_admin']['docservers']['coll_id'])
                //    && $_SESSION['m_admin']['docservers']['coll_id'] == 'templates'
                if($isModuleDocserver) {
                    ?>
                    <input name="coll_id" type="text"  id="coll_id" value="<?php functions::xecho($_SESSION['m_admin']['docservers']['coll_id']);?>" readonly="readonly" class="readonly" style="margin-left:7px;"/><i class="fa fa-star red_asterisk"></i>
                    <?php
                } else {
                    ?>
                <select name="coll_id" id="coll_id" style="margin-left:7px;">
                    <option value=""><?php echo _CHOOSE_COLLECTION;?></option>
                    <?php
                    for ($cptCollection = 0; $cptCollection < count(
                        $_SESSION['collections']
                    ); $cptCollection ++
                    ) {
                        ?>
                        <option value="<?php
                        functions::xecho($_SESSION['collections'][$cptCollection]['id']);
                        ?>" <?php
                        if (isset($_SESSION['m_admin']['docservers']['coll_id'])
                            && $_SESSION['m_admin']['docservers']['coll_id'] == $_SESSION['collections'][$cptCollection]['id']
                        ) {
                            echo 'selected="selected"';
                        }
                        ?>><?php
                        functions::xecho($_SESSION['collections'][$cptCollection]['id'] . " : "
                                . $_SESSION['collections'][$cptCollection]['label']);
                        ?></option>
                        <?php
                    }
                    ?>
                </select><i class="fa fa-star red_asterisk"></i>
                    <?php
                }
            }
            ?>
        </p>
        <p>
            <label for="priority_number"><?php echo _PRIORITY;?> : </label>
            <input name="priority_number" type="text"  id="priority_number" value="<?php
        if (isset($_SESSION['m_admin']['docservers']['priority_number'])) {
            functions::xecho(
                $_SESSION['m_admin']['docservers']['priority_number']
            );
        }
        ?>"/>
        </p>
        <p>
            <label for="docserver_location_id"><?php
        echo _DOCSERVER_LOCATIONS;
        ?> : </label>
            <select name="docserver_location_id" id="docserver_location_id" style="margin-left:7px;">
                <option value=""><?php echo _DOCSERVER_LOCATIONS;?></option>
        <?php
        for ($cptLocation = 0; $cptLocation < count($docserverLocationsArray);
        $cptLocation ++
        ) {
            ?>
            <option value="<?php
            functions::xecho($docserverLocationsArray[$cptLocation]);
            ?>" <?php
            if (isset($_SESSION['m_admin']['docservers']['docserver_location_id'])
                && $_SESSION['m_admin']['docservers']['docserver_location_id'] == $docserverLocationsArray[$cptLocation]
            ) {
                echo 'selected="selected"';
            }
            ?>><?php functions::xecho($docserverLocationsArray[$cptLocation]);?></option>
            <?php
        }
        ?>
           </select><i class="fa fa-star red_asterisk"></i>
        </p>
        <p>
           <label for="adr_priority_number"><?php
        echo _ADR_PRIORITY;
        ?>&nbsp;: </label>
            <input name="adr_priority_number" type="text"  id="adr_priority_number" value="<?php
        if (isset($_SESSION['m_admin']['docservers']['adr_priority_number'])) {
            functions::xecho(
                $_SESSION['m_admin']['docservers']['adr_priority_number']
            );
        }
        ?>" style="margin-left:7px;"/><i class="fa fa-star red_asterisk"></i>
        </p>
        <p class="buttons">
        <?php
        if ($mode == "up") {
            ?>
            <input class="button" type="submit" name="submit" value="<?php
            echo _MODIFY;
            ?>" />
            <?php
        } elseif ($mode == "add") {
            ?>
            <input type="submit" class="button"  name="submit" value="<?php
            echo _ADD;
            ?>" />
            <?php
        }
        ?>
            <input type="button" class="button"  name="cancel" value="<?php
        echo _CANCEL;
        ?>" onClick="javascript:window.location.href='<?php
        echo $_SESSION['config']['businessappurl'];
        ?>index.php?page=docservers_management_controler&amp;admin=docservers&amp;mode=list';"/>
        </p>
            </form>
            </div>
            <script type="text/javascript">
                //on load in GB
                $('size_limit_number').value = $('size_limit_number').value / (1000 * 1000 * 1000)
                if ($('actual_size_number')) {
                    $('actual_size_number').value = $('actual_size_number').value / (1000 * 1000 * 1000)
                }
            </script>
            <?php
    }
    ?>
    </div>
    <?php
}
