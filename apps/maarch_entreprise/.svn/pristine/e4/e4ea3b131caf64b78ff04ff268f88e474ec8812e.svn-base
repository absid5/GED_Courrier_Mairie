<?php
/* View */
if ($mode == "list") {
    $listShow = new list_show();
    $listShow->admin_list(
                    $docserver_types_list['tab'],
                    count($docserver_types_list['tab']),
                    $docserver_types_list['title'],
                    'docserver_type_id',
                    'docserver_types_management_controler&mode=list',
                    'docservers','docserver_type_id',
                    true,
                    $docserver_types_list['page_name_up'],
                    $docserver_types_list['page_name_val'],
                    $docserver_types_list['page_name_ban'],
                    $docserver_types_list['page_name_del'],
                    $docserver_types_list['page_name_add'],
                    $docserver_types_list['label_add'],
                    false,
                    false,
                    _ALL_DOCSERVER_TYPES,
                    _DOCSERVER_TYPE,
                    'hdd-o',
                    false,
                    true,
                    false,
                    true,
                    $docserver_types_list['what'],
                    true,
                    $docserver_types_list['autoCompletionArray']
                );
} elseif ($mode == "up" || $mode == "add") {
    $coreTools = new core_tools();
    $func = new functions();
    ?><script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=docserver_types_management.js"></script>
    <h1><i class="fa fa-hdd-o fa-2x"></i>
        <?php
        if ($mode == "add") {
            echo _DOCSERVER_TYPE_ADDITION;
        } elseif ($mode == "up") {
            echo _DOCSERVER_TYPE_MODIFICATION;
        }
        ?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">
        <br><br>
        <?php
        if ($state == false) {
            echo "<br /><br />"._THE_DOCSERVER_TYPE." "._UNKOWN."<br /><br /><br /><br />";
        } else {
            if ($mode == "up") {
                if (count($docservers)>0) {
                    ?>
                    <div onclick="new Effect.toggle('users_list', 'blind', {delay:0.2});return false;" >
                    &nbsp;<i class="fa fa-gears fa-2x"></i><i onmouseover="this.style.cursor='pointer';"><?php echo _SEE_DOCSERVERS_;?></i> <i class="fa fa-arrow-right"></i>
                    <span class="lb1-details">&nbsp;</span></div>
                    <div class="desc" id="users_list" style="display:none;">
                        <div class="ref-unit">
                            <table cellpadding="0" cellspacing="0" border="0" class="listingsmall" summary="">
                                <thead>
                                    <tr>
                                        <th><?php echo _DOCSERVER_ID;?></th>
                                        <th><?php echo _DEVICE_LABEL;?></th>
                                        <th><?php echo _DOCSERVER_LOCATION_ID;?></th>
                                    </tr>
                                </thead>

                            <tbody>
                            <?php
                            $color = ' class="col"';
                            for($i=0;$i<count($docservers);$i++) {
                                if ($color == ' class="col"') {
                                    $color = '';
                                } else {
                                    $color = ' class="col"';
                                }
                                ?>
                                 <tr <?php echo $color;?> >
                                            <td style="width:25%;"><?php functions::xecho($docservers[$i]->__get('docserver_id'));?></td>
                                            <td style="width:25%;"><?php functions::xecho($docservers[$i]->__get('device_label'));?></td>
                                            <td style="width:25%;"><?php functions::xecho($docservers[$i]->__get('docserver_location_id'));?></td>
                                           <td ><?php
                                            if ($coreTools->test_service('admin_docservers', 'apps', false)) {?>
                                           <a class="change" href="<?php echo $_SESSION['config']['businessappurl'].'index.php?page=docservers_management_controler&amp;mode=up&amp;admin=docservers&amp;id='.$docservers[$i]->__get('docserver_id');?>"  title="<?php echo _GO_MANAGE_DOCSERVER;?>"><i><?php echo _GO_MANAGE_DOCSERVER;?></i></a><?php }?></td>
                                </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                            </table>
                        <br/>
                        </div>
                    </div>
            <?php
            }
        }?>
            <div class="block">
            <form name="formdocserver" method="post" class="forms" style="width:600px;" action="<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&page=docserver_types_management_controler&admin=docservers&mode=".$mode;?>">
                <input type="hidden" name="display" value="true" />
                <input type="hidden" name="admin" value="docservers" />
                <input type="hidden" name="page" value="docserver_types_management_controler" />
                <input type="hidden" name="mode" id="mode" value="<?php functions::xecho($mode);?>" />
                <input type="hidden" name="order" id="order" value="<?php if (isset($_REQUEST['order'])) functions::xecho($_REQUEST['order']);?>" />
                <input type="hidden" name="order_field" id="order_field" value="<?php if (isset($_REQUEST['order_field'])) functions::xecho($_REQUEST['order_field']);?>" />
                <input type="hidden" name="what" id="what" value="<?php if (isset($_REQUEST['what'])) functions::xecho($_REQUEST['what']);?>" />
                <input type="hidden" name="start" id="start" value="<?php if (isset($_REQUEST['start'])) functions::xecho($_REQUEST['start']);?>" />
                <p>
                    <label for="id"><?php echo _DOCSERVER_TYPE_ID;?> : </label>
                    <input name="id" type="text"  id="id" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['docserver_type_id'])) functions::xecho($_SESSION['m_admin']['docserver_types']['docserver_type_id']);?>" <?php if ($mode == "up") echo " readonly='readonly' class='readonly'";?> style="margin-left:6px;"/><i class="fa fa-star red_asterisk"></i> 
                </p>
                <p>
                    <label for="docserver_type_label"><?php echo _DOCSERVER_TYPE_LABEL;?> : </label>
                    <input name="docserver_type_label" type="text"  id="docserver_type_label" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['docserver_type_label'])) functions::xecho($_SESSION['m_admin']['docserver_types']['docserver_type_label']);?>" style="margin-left:6px;"/><i class="fa fa-star red_asterisk"></i>  
                </p>
                <?php
                if ($_SESSION['m_admin']['docserver_types']['link_exists']) {
                    ?>
                    <p>
                        <?php 
                        if ($_SESSION['m_admin']['docserver_types']['is_container'] == "Y") {
                            $rep = _YES;
                        } else {
                            $rep = _NO;
                        }
                        ?>
                        <label for="is_container_txt"><?php echo _IS_CONTAINER;?> : </label>
                        <input name="is_container_txt" type="text"  id="is_container_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['is_container'])) functions::xecho($rep);?>" readonly class="readonly"/>
                        <input type="hidden" name="is_container" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['is_container']);?>" />
                    </p>
                    <p>
                        <label for="container_max_number_txt"><?php echo _CONTAINER_MAX_NUMBER;?> : </label>
                        <input name="container_max_number_txt" type="text"  id="container_max_number_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['container_max_number'])) functions::xecho($_SESSION['m_admin']['docserver_types']['container_max_number']);?>" readonly class="readonly"/>
                        <input type="hidden" name="container_max_number" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['container_max_number']);?>" />
                    </p>
                    <p>
                        <?php 
                        if ($_SESSION['m_admin']['docserver_types']['is_compressed'] == "Y") {
                            $rep = _YES;
                        } else {
                            $rep = _NO;
                        }
                        ?>
                        <label for="is_compressed_txt"><?php echo _IS_COMPRESSED;?> : </label>
                        <input name="is_compressed_txt" type="text"  id="is_compressed_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['is_compressed'])) functions::xecho($rep);?>" readonly class="readonly"/>
                        <input type="hidden" name="is_compressed" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['is_compressed']);?>" />
                    </p>
                    <p>
                        <label for="compression_mode_txt"><?php echo _COMPRESS_MODE;?> : </label>
                        <input name="compression_mode_txt" type="text"  id="compression_mode_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['compression_mode'])) functions::xecho($_SESSION['m_admin']['docserver_types']['compression_mode']);?>" readonly class="readonly"/>
                        <input type="hidden" name="compression_mode" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['compression_mode']);?>" />
                    </p>
                    <p>
                        <?php
                        if ($_SESSION['m_admin']['docserver_types']['is_meta'] == "Y") {
                            $rep = _YES;
                        } else {
                            $rep = _NO;
                        }
                        ?>
                        <label for="is_meta_txt"><?php echo _IS_META;?> : </label>
                        <input name="is_meta_txt" type="text"  id="is_meta_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['is_meta'])) functions::xecho($rep);?>" readonly class="readonly"/>
                        <input type="hidden" name="is_meta" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['is_meta']);?>" />
                    </p>
                    <p>
                        <label for="meta_template_txt"><?php echo _META_TEMPLATE;?> : </label>
                        <input name="meta_template_txt" type="text"  id="meta_template_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['meta_template'])) functions::xecho($_SESSION['m_admin']['docserver_types']['meta_template']);?>" readonly class="readonly"/>
                        <input type="hidden" name="meta_template" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['meta_template']);?>" />
                    </p>
                    <p>
                        <?php
                        if ($_SESSION['m_admin']['docserver_types']['is_logged'] == "Y") {
                            $rep = _YES;
                        } else {
                            $rep = _NO;
                        }
                        ?>
                        <label for="is_logged_txt"><?php echo _IS_LOGGED;?> : </label>
                        <input name="is_logged_txt" type="text"  id="is_logged_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['is_logged'])) functions::xecho($rep);?>" readonly class="readonly"/>
                        <input type="hidden" name="is_logged" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['is_logged']);?>" />
                    </p>
                    <p>
                        <label for="log_template_txt"><?php echo _LOG_TEMPLATE;?> : </label>
                        <input name="log_template_txt" type="text"  id="log_template_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['log_template'])) functions::xecho($_SESSION['m_admin']['docserver_types']['log_template']);?>" readonly class="readonly"/>
                        <input type="hidden" name="log_template" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['log_template']);?>" />
                    </p>
                    <p>
                        <?php
                        if ($_SESSION['m_admin']['docserver_types']['is_signed'] == "Y") {
                            $rep = _YES;
                        } else {
                            $rep = _NO;
                        }
                        ?>
                        <label for="is_signed_txt"><?php echo _IS_SIGNED;?> : </label>
                        <input name="is_signed_txt" type="text"  id="is_signed_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['is_signed'])) functions::xecho($rep);?>" readonly class="readonly"/>
                        <input type="hidden" name="is_signed" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['is_signed']);?>" />
                    </p>
                    <p>
                        <label for="fingerprint_mode_txt"><?php echo _FINGERPRINT_MODE;?> : </label>
                        <input name="fingerprint_mode_txt" type="text"  id="fingerprint_mode_txt" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['fingerprint_mode'])) functions::xecho($_SESSION['m_admin']['docserver_types']['fingerprint_mode']);?>" readonly class="readonly"/>
                        <input type="hidden" name="fingerprint_mode" value="<?php functions::xecho($_SESSION['m_admin']['docserver_types']['fingerprint_mode']);?>" />
                    </p>
                    <?php
                    /*******************************************************/
                } else {
                    ?>
                    <p>
                        <label><?php echo _IS_CONTAINER;?> : </label>
                        <input type="radio" class="check" name="is_container" id="is_container" value="true"  <?php if (isset($_SESSION['m_admin']['docserver_types']['is_container']) && $_SESSION['m_admin']['docserver_types']['is_container'] == "Y") {?> checked="checked"<?php } ?> onClick="hideIndex(false, 'container_max_number');"/><?php echo _YES;?>
                        <input type="radio" class="check" name="is_container" id="is_container" value="false" <?php if (!isset($_SESSION['m_admin']['docserver_types']['is_container']) || (!($_SESSION['m_admin']['docserver_types']['is_container'] == "Y") || $_SESSION['m_admin']['docserver_types']['is_container'] == '')) {?> checked="checked"<?php } ?> onClick="hideIndex(true, 'container_max_number');"/><?php echo _NO;?>
                    </p>
                    <div class ="container_max_number" id="container_max_number">
                    <p>
                        <label for="container_max_number"><?php echo _CONTAINER_MAX_NUMBER;?> : </label>
                        <input name="container_max_number" type="text"  id="container_max_number" value="<?php if (isset($_SESSION['m_admin']['docserver_types']['container_max_number'])) functions::xecho($_SESSION['m_admin']['docserver_types']['container_max_number']);?>"/>
                    </p>
                    </div>
                    <p>
                        <label><?php echo _IS_COMPRESSED;?> : </label>
                        <input type="radio" class="check" name="is_compressed" id="is_compressed" value="true"  <?php if (isset($_SESSION['m_admin']['docserver_types']['is_compressed']) && $_SESSION['m_admin']['docserver_types']['is_compressed'] == "Y") {?> checked="checked"<?php } ?> onClick="hideIndex(false, 'compression_mode');"/><?php echo _YES;?>
                        <input type="radio" class="check" name="is_compressed" id="is_compressed" value="false" <?php if (!isset($_SESSION['m_admin']['docserver_types']['is_compressed']) || (!($_SESSION['m_admin']['docserver_types']['is_compressed'] == "Y") || $_SESSION['m_admin']['docserver_types']['is_compressed'] == '')) {?> checked="checked"<?php } ?> onClick="hideIndex(true, 'compression_mode');"/><?php echo _NO;?>
                    </p>
                    <div class ="compression_mode" id="compression_mode">
                    <p>
                        <label for="compression_mode"><?php echo _COMPRESS_MODE;?> : </label>
                        <select name="compression_mode">
                            <?php
                            for($cptCompressMode=1;$cptCompressMode<count($_SESSION['docserversFeatures']['DOCSERVERS']['COMPRESS']['MODE']);$cptCompressMode++) {
                                ?>
                                <option value="<?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['COMPRESS']['MODE'][$cptCompressMode]);?>" <?php if (isset($_SESSION['m_admin']['docserver_types']['compression_mode']) && $_SESSION['m_admin']['docserver_types']['compression_mode'] == $_SESSION['docserversFeatures']['DOCSERVERS']['COMPRESS']['MODE'][$cptCompressMode]) { echo 'selected="selected"';}?>><?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['COMPRESS']['MODE'][$cptCompressMode]);?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </p>
                    </div>
                    <p>
                        <label><?php echo _IS_META;?> : </label>
                        <input type="radio" class="check" name="is_meta" id="is_meta" value="true"   <?php if (isset($_SESSION['m_admin']['docserver_types']['is_meta']) && $_SESSION['m_admin']['docserver_types']['is_meta'] == "Y") {?> checked="checked"<?php } ?> onClick="hideIndex(false, 'meta_template');"/><?php echo _YES;?>
                        <input type="radio" class="check" name="is_meta" id="is_meta" value="false"  <?php if (!isset($_SESSION['m_admin']['docserver_types']['is_meta']) || (!($_SESSION['m_admin']['docserver_types']['is_meta'] == "Y") || $_SESSION['m_admin']['docserver_types']['is_meta'] == '')) {?> checked="checked"<?php } ?> onClick="hideIndex(true, 'meta_template');"/><?php echo _NO;?>
                    </p>
                    <div class ="meta_template" id="meta_template">
                    <p>
                        <label for="meta_template"><?php echo _META_TEMPLATE;?> : </label>
                        <select name="meta_template" id="meta_template">
                            <?php
                            for($cptCompressMode=1;$cptCompressMode<count($_SESSION['docserversFeatures']['DOCSERVERS']['META_TEMPLATE']['MODE']);$cptCompressMode++) {
                                ?>
                                <option value="<?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['META_TEMPLATE']['MODE'][$cptCompressMode]);?>" <?php if (isset($_SESSION['m_admin']['docserver_types']['meta_template']) && $_SESSION['m_admin']['docserver_types']['meta_template'] == $_SESSION['docserversFeatures']['DOCSERVERS']['META_TEMPLATE']['MODE'][$cptCompressMode]) { echo 'selected="selected"';}?>><?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['META_TEMPLATE']['MODE'][$cptCompressMode]);?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </p>
                    </div>
                    <p>
                        <label><?php echo _IS_LOGGED;?> : </label>
                        <input type="radio" class="check" name="is_logged" id="is_logged" value="true"  <?php if (isset($_SESSION['m_admin']['docserver_types']['is_logged']) && $_SESSION['m_admin']['docserver_types']['is_logged'] == "Y") {?> checked="checked"<?php } ?> onClick="hideIndex(false, 'log_template');"/><?php echo _YES;?>
                        <input type="radio" class="check" name="is_logged" id="is_logged" value="false" <?php if (!isset($_SESSION['m_admin']['docserver_types']['is_logged']) || (!($_SESSION['m_admin']['docserver_types']['is_logged'] == "Y") || $_SESSION['m_admin']['docserver_types']['is_logged'] == '')) {?> checked="checked"<?php } ?> onClick="hideIndex(true, 'log_template');"/><?php echo _NO;?>
                    </p>
                    <div class ="log_template" id="log_template">
                    <p>
                        <label for="log_template"><?php echo _LOG_TEMPLATE;?> : </label>
                        <select name="log_template" id="log_template">
                            <?php
                                for($cptCompressMode=1;$cptCompressMode<count($_SESSION['docserversFeatures']['DOCSERVERS']['LOG_TEMPLATE']['MODE']);$cptCompressMode++) {
                                    ?>
                                    <option value="<?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['LOG_TEMPLATE']['MODE'][$cptCompressMode]);?>" <?php if (isset($_SESSION['m_admin']['docserver_types']['log_template']) && $_SESSION['m_admin']['docserver_types']['log_template'] == $_SESSION['docserversFeatures']['DOCSERVERS']['LOG_TEMPLATE']['MODE'][$cptCompressMode]) { echo 'selected="selected"';}?>><?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['LOG_TEMPLATE']['MODE'][$cptCompressMode]);?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </p>
                    </div>
                    <p>
                        <label><?php echo _IS_SIGNED;?> : </label>
                        <input type="radio" class="check" name="is_signed" id="is_signed" value="true" <?php  if (isset($_SESSION['m_admin']['docserver_types']['is_signed']) && $_SESSION['m_admin']['docserver_types']['is_signed'] == "Y") {?> checked="checked"<?php } ?> onClick="hideIndex(false, 'fingerprint_mode');"/><?php echo _YES;?>
                        <input type="radio" class="check" name="is_signed" id="is_signed" value="false" <?php if (!isset($_SESSION['m_admin']['docserver_types']['is_signed']) || (!($_SESSION['m_admin']['docserver_types']['is_signed'] == "Y") || $_SESSION['m_admin']['docserver_types']['is_signed'] == '')) {?> checked="checked"<?php } ?> onClick="hideIndex(true, 'fingerprint_mode');"/><?php echo _NO;?>
                    </p>
                    <div class ="fingerprint_mode" id="fingerprint_mode">
                    <p>
                        <label for="fingerprint_mode"><?php echo _FINGERPRINT_MODE;?> : </label>
                        <select name="fingerprint_mode" id="fingerprint_mode">
                            <?php
                            for($cptCompressMode=1;$cptCompressMode<count($_SESSION['docserversFeatures']['DOCSERVERS']['FINGERPRINT_MODE']['MODE']);$cptCompressMode++) {
                                ?>
                                <option value="<?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['FINGERPRINT_MODE']['MODE'][$cptCompressMode]);?>" <?php if (isset($_SESSION['m_admin']['docserver_types']['fingerprint_mode']) && $_SESSION['m_admin']['docserver_types']['fingerprint_mode'] == $_SESSION['docserversFeatures']['DOCSERVERS']['FINGERPRINT_MODE']['MODE'][$cptCompressMode]) { echo 'selected="selected"';}?>><?php functions::xecho($_SESSION['docserversFeatures']['DOCSERVERS']['FINGERPRINT_MODE']['MODE'][$cptCompressMode]);?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </p>
                    <?php
                }
                ?>
                </div>
                <p class="buttons">
                    <?php
                    if ($mode == "up") {
                        ?>
                        <input class="button" type="submit" name="submit" value="<?php echo _MODIFY;?>" />
                        <?php
                    }
                    elseif ($mode == "add") {
                        ?>
                        <input type="submit" class="button"  name="submit" value="<?php echo _ADD;?>" />
                        <?php
                    }
                    ?>
                   <input type="button" class="button"  name="cancel" value="<?php echo _CANCEL;?>" onClick="javascript:window.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=docserver_types_management_controler&amp;admin=docservers&amp;mode=list';"/>
                </p>
                <?php
                echo _GZIP_COMPRESSION_MODE;
                ?>
            </form>
            </div>
            <script type="text/javascript">
                //on load hide inputs
                <?php
                if (!isset($_SESSION['m_admin']['docserver_types']['is_container']) || !($_SESSION['m_admin']['docserver_types']['is_container'] == "Y")) {
                    ?>
                    hideIndex(true, 'container_max_number');
                    <?php
                }
                if (!isset($_SESSION['m_admin']['docserver_types']['is_compressed']) || !($_SESSION['m_admin']['docserver_types']['is_compressed'] == "Y")) {
                    ?>
                    hideIndex(true, 'compression_mode');
                    <?php
                }
                if (!isset($_SESSION['m_admin']['docserver_types']['is_meta']) || !($_SESSION['m_admin']['docserver_types']['is_meta'] == "Y")) {
                    ?>
                    hideIndex(true, 'meta_template');
                    <?php
                }
                if (!isset($_SESSION['m_admin']['docserver_types']['is_logged']) || !($_SESSION['m_admin']['docserver_types']['is_logged'] == "Y")) {
                    ?>
                    hideIndex(true, 'log_template');
                    <?php
                }
                if (!isset($_SESSION['m_admin']['docserver_types']['is_signed']) || !($_SESSION['m_admin']['docserver_types']['is_signed'] == "Y")) {
                    ?>
                    hideIndex(true, 'fingerprint_mode');
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
?>
