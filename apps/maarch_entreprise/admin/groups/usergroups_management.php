<?php

/* Affichage */
if ($mode == 'list') {
    $list = new list_show();
    $list->admin_list(
        $groupsList['tab'], count($groupsList['tab']), $groupsList['title'],
        'group_id', 'usergroups_management_controler&mode=list', 'groups',
        'group_id', true, $groupsList['page_name_up'],
        $groupsList['page_name_val'], $groupsList['page_name_ban'],
        $groupsList['page_name_del'], $groupsList['page_name_add'],
        $groupsList['label_add'], false, false, _ALL_GROUPS, _GROUP,
        'users', false, true, false, true,
        $groupsList['what'], true, $groupsList['autoCompletionArray']
    );
} else if ($mode == 'up' || $mode == 'add') {
    ?><script type="text/javascript" src="<?php
    echo $_SESSION['config']['businessappurl'];
    ?>static.php?filename=usergroups_management.js"></script>
    <h1><i class="fa fa-users fa-2x"></i>
    <?php
    if ($mode == 'add') {
        echo _GROUP_ADDITION;
    } else if ($mode == 'up') {
        echo _GROUP_MODIFICATION;
    }
    ?>
    </h1>

    <?php
    if ($state == false) {
        echo '<br /><br /><br /><br />' . _GROUP . ' ' . _UNKNOWN
            .'<br /><br /><br /><br />';
    } else {
        ?>
        <div id="inner_content" class="clearfix">
        <div id="group_box" class="bloc" style="width:35%;">
            <?php
        if ($mode == 'up') {
            if (count($users) > 0) {
                $uc = new users_controler();
                ?><div onclick="new Effect.toggle('users_list', 'blind', {delay:0.2});return false;" onmouseover="this.style.cursor='pointer';">
                &nbsp;<i class="fa fa-user fa-2x"></i><i><?php
                echo _SEE_GROUP_MEMBERS;
                ?></i> <i class="fa fa-angle-right"></i>
                <span class="lb1-details">&nbsp;</span></div>
                <div class="desc" id="users_list" style="display:none;">
                    <div class="ref-unit">
                        <table cellpadding="0" cellspacing="0" border="0" class="listingsmall" summary="">
                            <thead>
                                <tr>
                                    <th><?php echo _LASTNAME;?></th>
                                    <th ><?php echo _FIRSTNAME;?></th>
                                    <!--<th  ><?php echo _ENTITY;?></th>-->
                                    <th></th>
                                </tr>
                            </thead>

                        <tbody>
                             <?php
                        $color = ' class="col"';

                for ($i = 0; $i < count($users[0]); $i ++) {
                    if ($color == ' class="col"') {
                        $color = '';
                    } else {
                        $color = ' class="col"';
                    }
                     ?>
                             <tr <?php echo $color;?> >
                                       <td style="width:25%;"><?php
                                           echo $uc->getLastName($users[0][$i]);
                                        ?></td>
                                        <td style="width:25%;"><?php
                                            echo $uc->getFirstName($users[0][$i]);
                                        ?></td>
                                       <td ><?php
                    if ($core->test_service('admin_users', 'apps', false)) {
                        ?>
                                       <a class="change" href="<?php
                                       echo $_SESSION['config']['businessappurl']
                                       .'index.php?page=users_management_controler&amp;mode=up&amp;admin=users&amp;id='
                                       .$users[0][$i];
                                       ?>"  title="<?php
                                       echo _GO_MANAGE_USER;
                                       ?>"><i><?php
                                       echo _GO_MANAGE_USER;
                                       ?></i></a><?php
                    }
                    ?></td>
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
            if ($GLOBALS['basket_loaded'] && count($baskets) > 0) {
                    ?>
                    <div onclick="new Effect.toggle('baskets_list2', 'blind', {delay:0.2});return false;" onmouseover="this.style.cursor='pointer';" >
                &nbsp;<i class="fa fa-inbox fa-2x"></i><i><?php
                echo _SEE_BASKETS_RELATED;
                ?></i> <i class="fa fa-angle-right"></i>
                <span class="lb1-details">&nbsp;</span></div>
                <div class="desc" id="baskets_list2" style="display:none;">
                    <div class="ref-unit">
                        <table cellpadding="0" cellspacing="0" border="0" class="listingsmall" summary="">
                            <thead>
                                <tr>
                                    <th><?php echo _NAME;?></th>
                                    <th ><?php echo _DESC;?></th>
                                    <th></th>
                                </tr>
                            </thead>

                        <tbody>
                             <?php
                        $color = ' class="col"';
                for ($i = 0; $i < count($baskets); $i ++) {
                    if ($color == ' class="col"') {
                        $color = '';
                    } else {
                        $color = ' class="col"';
                    }
                       ?>
                             <tr <?php echo $color;?> >
                                       <td style="width:30%;"><?php
                                       functions::xecho($baskets[$i]->__get('basket_name'));
                                       ?></td>
                                      <td style="width:50%;"><?php
                                      functions::xecho($baskets[$i]->__get('basket_desc'));
                                      ?></td>
                                       <td >
                    <?php
                    if ($core->test_service(
                    	'admin_baskets', 'basket', false
                    )
                    ) {
                        ?>
                                        <a class="change" href="<?php
                                            echo $_SESSION['config']['businessappurl']
                                            .'index.php?page=basket_up&module=basket&id='
                                            .$baskets[$i]->__get('basket_id');
                                            ?>" title="<?php
                                            echo _GO_MANAGE_BASKET;
                                            ?>"><i><?php
                                            echo _GO_MANAGE_BASKET;
                                            ?></i></a>
                                       <?php
                    }
                                       ?>
                                        </td>
                            </tr>
                                    <?php
                            }
                        ?>
                        </tbody>
                        </table>
                        <br/>
                        <br/>
                    </div>
                </div>
            <?php
            }
        }
            ?><div id="access"></div>
        </div>
        <div class="block" style="float:left;width:60%;">
        <form id="formgroup" method="post"  class="forms" action="<?php
        echo  $_SESSION['config']['businessappurl']
            ."index.php?display=true&admin=groups&page=usergroups_management_controler&mode="
            .$mode
            ?>" >
            <div>
            <input type="hidden" name="display" value="true" />
            <input type="hidden" name="admin" value="groups" />
            <input type="hidden" name="page" value="usergroups_management_controler" />
            <input type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />

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

                <h2><table border="0" summary="">
                    <tr>
                        <td  align="left">
                            <?php echo _GROUP;?> :
                        </td>
                        <td align="left">
                            <?php
        if ($mode == 'up') {
            functions::xecho($_SESSION['m_admin']['groups']['group_id']);
        }
        ?>
                            <input name="group_id" type="<?php
        if ($mode == 'up') {
            ?>hidden<?php
        } else if ($mode == 'add') {
            ?>text<?php
        }
        ?>" id="group_id" value="<?php
        functions::xecho($_SESSION['m_admin']['groups']['group_id']);
        ?>" />
             <input type="hidden"  name="id" value="<?php
        if (isset($groupId)) {
            functions::xecho($groupId);
        }
        ?>" />
        </td>
      	</tr>
        <tr>
           <td align="right">
           <?php echo _DESC;?> :
           </td>
           <td align="left">
            <input name="desc" id="desc" class="text" type="text" value="<?php
        if (isset($_SESSION['m_admin']['groups']['group_desc'])) {
            functions::xecho($_SESSION['m_admin']['groups']['group_desc']);
        }
        ?>"  alt="<?php
        if (isset($_SESSION['m_admin']['groups']['group_desc'])) {
            functions::xecho($_SESSION['m_admin']['groups']['group_desc']);
        }
        ?>" title="<?php
        if (isset($_SESSION['m_admin']['groups']['group_desc'])) {
            functions::xecho($_SESSION['m_admin']['groups']['group_desc']);
        }
        ?>"/>
            </td>
        </tr>
        </table></h2>
        <br/>
        <div class="center_text">
            <i><?php echo _AVAILABLE_SERVICES;?> :</i>
            <a href="#" onclick="checkAll();" ><?php echo _CHECK_ALL;?></a>
            &nbsp;<a href="#" onclick="unCheckAll();" ><?php echo _UNCHECK_ALL;?></a>
        </div>
        <?php
        $enabledServicesSortByParent = array();
        $j = 0;

        for ($i = 0; $i < count($_SESSION['enabled_services']); $i ++) {
            if ($i > 0
                 && $_SESSION['enabled_services'][$i]['parent'] <> $_SESSION['enabled_services'][$i - 1]['parent']
            ) {
                $j = 0;
            }
            if ($_SESSION['enabled_services'][$i]['system'] == false) {
                $enabledServicesSortByParent[$_SESSION['enabled_services'][$i]['parent']][$j] = $_SESSION['enabled_services'][$i];
                $j ++;
            }
        }
        $_SESSION['cpt'] = 0;
        foreach (array_keys($enabledServicesSortByParent) as $value) {
            if ($value == 'application') {
                $label = _APPS_COMMENT;
            } else if ($value == 'core') {
                $label = _CORE_COMMENT;
            } else {
                $label = $_SESSION['modules_loaded'][$value]['comment'];
            }
            if (count($enabledServicesSortByParent[$value]) > 0) {
                ?>
                <h5 onclick="change(<?php
                echo $_SESSION['cpt'];
                ?>)" id="h2<?php
                echo $_SESSION['cpt'];
                 ?>" class="categorie" onmouseover="this.style.cursor='pointer';">
                  <img src="<?php
                 echo $_SESSION['config']['businessappurl'];
                 ?>static.php?filename=plus.png" alt="" />&nbsp;<b><?php
                 functions::xecho($label);
                 ?></b>
                <span class="lb1-details">&nbsp;</span>
                </h5><br/>
                <div class="desc block_light admin" id="desc<?php
                 functions::xecho($_SESSION['cpt']);
                 ?>" style="display:none">
                <div class="ref-unit">
                <table summary="">
                    <?php
                for ($i = 0; $i < count($enabledServicesSortByParent[$value]);
                    $i ++
                ) {
                    if ((isset($enabledServicesSortByParent[$value][$i]['system'])
                        && $enabledServicesSortByParent[$value][$i]['system'] == false)
                        || !isset($enabledServicesSortByParent[$value][$i]['system'])
                    ) {
                        ?>
                        <tr>
                        <td style="width:800px;" align="right" title="<?php
                        functions::xecho($enabledServicesSortByParent[$value][$i]['comment']);
                        ?>">
                        <?php
                        functions::xecho($enabledServicesSortByParent[$value][$i]['label']);
                        if ($enabledServicesSortByParent[$value][$i]['type'] == "admin") {
                            ?> (<?php
                            echo _ADMIN;
                            ?>) <?php
                        } else if ($enabledServicesSortByParent[$value][$i]['type'] == "menu") {
                                ?> (<?php
                                echo _MENU;
                                ?>)<?php
                        }
                        ?>  :
                        </td>
                        <td style="width:50px;" align="left">
                        <input type="checkbox"  class="check" name="services[]" value="<?php
                        functions::xecho($enabledServicesSortByParent[$value][$i]['id']);?>" <?php
                        if (in_array(
                            trim(
                                $enabledServicesSortByParent[$value][$i]['id']
                            ), $_SESSION['m_admin']['groups']['services']
                        ) || $mode == "add"
                        ) {
                            echo 'checked="checked"';
                        }
                        ?>  />
                                        </td>
                                    </tr>
                                    <?php
                    }
                }
                ?>
                </table>
                </div>
                </div>
                <?php
            }
            $_SESSION['cpt']++;
        }
        ?>
        <p class="buttons">
        <input type="submit" name="group_submit" id="group_submit" value="<?php
        echo _VALIDATE;
        ?>" class="button"/>
        <input type="button" class="button"  name="cancel" value="<?php
        echo _CANCEL;
        ?>" onclick="javascript:window.location.href='<?php
        echo $_SESSION['config']['businessappurl'];
        ?>index.php?page=usergroups_management_controler&amp;mode=list&amp;admin=groups';"/>
         </p>
         </div>
         </form>
         </div>
         </div>
         <script type="text/javascript">updateContent('<?php
         echo $_SESSION['config']['businessappurl'];
         ?>index.php?display=true&page=groups_form&admin=groups', 'access');</script>
    <?php
    }
}

