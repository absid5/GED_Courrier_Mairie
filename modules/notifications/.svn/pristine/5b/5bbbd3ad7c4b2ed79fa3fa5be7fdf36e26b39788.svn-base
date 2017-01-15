<?php
/* Affichage */
if ($mode == 'list') {
    $list = new list_show();
    $list->admin_list(
        $notifsList['tab'],
        count($notifsList['tab']),
        $notifsList['title'],
        'notification_sid',
        'manage_notifications_controler&mode=list',
        'notifications','notification_sid',
        true,
        $notifsList['page_name_up'],
        $notifsList['page_name_val'],
        $notifsList['page_name_ban'],
        $notifsList['page_name_del'],
        $notifsList['page_name_add'],
        $notifsList['label_add'],
        false,
        false,
        _ALL_NOTIFS,
        _NOTIF,
        'bell',
        true,
        true,
        false,
        true,
        $notifsList['what'],
        true,
        $notifsList['autoCompletionArray']
    );
} elseif ($mode == 'up' || $mode == 'add') {
    ?><h1><i class='fa fa-bell fa-2x'></i>
    <?php
        if ($mode == 'up') {
            echo _MODIFY_NOTIF;
        } elseif ($mode == 'add') {
            echo _ADD_NOTIF;
        }?>
    </h1>
    <div id="inner_content" class="clearfix" align="center">   
    <?php
    if ($state == false) {
        echo '<br /><br /><br /><br />' . _NOTIFICATION_ID . ' ' . $_SESSION['m_admin']['notification']['notification_sid'] . ' ' . _UNKNOWN
        . '<br /><br /><br /><br />';
    } else {?>
    <div class="block">
    <form name="frmevent" id="frmevent" method="post" action="<?php
        echo $_SESSION['config']['businessappurl'] . 'index.php?display=true'
        . '&amp;module=notifications&amp;page=manage_notifications_controler&amp;mode='
        . $mode;?>" class="forms addforms">
        <input type="hidden" name="display" value="true" />
        <input type="hidden" name="admin" value="notifications" />
        <input type="hidden" name="page" value="manage_notifications_controler" />
        <input type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />

        <input type="hidden" name="notification_sid" id="notification_sid" value="<?php functions::xecho($_SESSION['m_admin']['notification']['notification_sid']);?>" />

        <input type="hidden" name="order" id="order" value="<?php
            functions::xecho($_REQUEST['order']);?>" />
        <input type="hidden" name="order_field" id="order_field" value="<?php
            functions::xecho($_REQUEST['order_field']);?>" />
        <input type="hidden" name="what" id="what" value="<?php
            functions::xecho($_REQUEST['what']);?>" />
        <input type="hidden" name="start" id="start" value="<?php
            functions::xecho($_REQUEST['start']);?>" />


        <p>
            <label for="label"><?php echo _NOTIFICATION_ID;?> : </label>
            <input name="notification_id" type="text" id="notification_id" value="<?php
                echo functions::show_str(
                    $_SESSION['m_admin']['notification']['notification_id']
                );?>"/>
        </p>
        <p>
            <label for="label"><?php echo _DESC;?> : </label>
            <textarea name="description" cols="80" rows="2" id="description"><?php
                echo functions::show_str(
                    $_SESSION['m_admin']['notification']['description']
                );?></textarea>
        </p>
         <p>
            <label><?php echo _ENABLED;?> : </label>
            <input type="radio" class="check" name="is_enabled" value="true" <?php
            if (isset($_SESSION['m_admin']['notification']['is_enabled'])
                && $_SESSION['m_admin']['notification']['is_enabled'] == "Y"
            ) {
                ?> checked="checked"<?php
            }
            ?> /><?php echo _YES;?>
                <input type="radio" class="check" name="is_enabled" value="false" <?php
            if (!isset($_SESSION['m_admin']['notification']['is_enabled'])
                || (!($_SESSION['m_admin']['notification']['is_enabled'] == "Y")
                || $_SESSION['m_admin']['notification']['is_enabled'] == '')
            ) {
                ?> checked="checked"<?php
            }
            ?> /><?php echo _NO;?>
        </p>
        <p>
            <label for="label"><?php echo _EVENT;?> : </label>
            <select name="event_id" id="event_id">
				<option value=""><?php echo _SELECT_EVENT_TYPE;?></option>
                <optgroup label="<?php echo _ACTIONS;?>">
                <?php
                foreach($actions_list as $this_action){
                    ?><option value="<?php functions::xecho($this_action->id);?>"
                    <?php
                    if($_SESSION['m_admin']['notification']['event_id']
                        == $this_action->id) {
                        echo 'selected="selected"';
                    }?>><?php functions::xecho($this_action->label_action);
                    ?></option><?php
                }

                //Récupération des éléments systèmes
                ?></optgroup><?php
                $newarray = array_keys($_SESSION['notif_events']);
                ?><optgroup label="<?php echo _SYSTEM;?>"><?php
                foreach($_SESSION['notif_events'] as $event_type_id => $event_type_label){
                    ?><option value="<?php functions::xecho($event_type_id);?>"
                    <?php
                    if($_SESSION['m_admin']['notification']['event_id']
                        == $event_type_id) {
                        echo 'selected="selected"';
                    }?>><?php functions::xecho($event_type_label);
                    ?></option><?php
                }
                ?>
                </optgroup>
            </select>
        </p>
        <p style="display:none">
            <label><?php echo _NOTIFICATION_MODE;?> :</label>
            <input type="radio" name="notification_mode" value="EMAIL"
                onClick="javascript:window.document.getElementById('template_div').style.display = 'block';
				window.document.getElementById('rss_url_div').style.display = 'none';" <?php
                if ($_SESSION['m_admin']['notification']['notification_mode'] == '' 
                    || $_SESSION['m_admin']['notification']['notification_mode'] == 'EMAIL'
                ) {
                    echo 'checked="checked"'; 
                }?>/> <?php echo _EMAIL;?>
            <input type="radio" name="notification_mode" value="RSS"
                onClick="javascript:window.document.getElementById('rss_url_div').style.display = 'block';
				window.document.getElementById('template_div').style.display = 'none';" <?php
                if ($_SESSION['m_admin']['notification']['notification_mode'] == 'RSS'
                ) {
                    echo 'checked="checked"'; 
                }?>/> <?php echo _RSS;?>
        </p>
        <div id="template_div" name="template_div">
        <p>
            <label for="label"><?php echo _TEMPLATE;?> : </label>
            <select name="template_id" id="template_id">
                <option value=""><?php echo _SELECT_TEMPLATE;?></option>
                <?php
                foreach($templates_list as $template){
                    if ($template['TYPE'] === 'HTML' && ($template['TARGET'] == 'notifications' || $template['TARGET'] == '')) {
                        ?><option value="<?php functions::xecho($template['ID']);?>"
                        <?php
                        if($_SESSION['m_admin']['notification']['template_id']
                            == $template['ID']) {
                            echo 'selected="selected"';
                        }?>><?php functions::xecho($template['LABEL']);
                        ?></option><?php
                    }
                }
                ?>
            </select>
        </p>
        </div>
		<div id="rss_url_div" name="rss_url_div" style="width:600px; align=left; display:none;" >
			<p>
            <label for="label"><?php echo _RSS_URL_TEMPLATE;?> : </label>
            <textarea name="rss_url_template" type="text" id="rss_url_template" style="width:340px; height=60px">
			<?php
                echo functions::show_str(
                    $_SESSION['m_admin']['notification']['rss_url_template']
                ); 
			?></textarea>
			</p>
		</div>
       
        <p>
            <label for="status"><?php echo _DIFFUSION_TYPE;?> : </label>
            <select name="diffusion_type"
					id="status" 
					onchange="change_properties_box(
						this.options[this.selectedIndex].value,
						'<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=load_diffusiontype_formcontent',
						'diff_type_div',
						'notifications',
						'');clear_attach_type();set_attach_type(this.options[this.selectedIndex].value)">

                <option value=""><?php echo _SELECT_DIFFUSION_TYPE;?></option>
                <?php
                foreach($diffusion_types as $this_diffusion){ ?>
                    <option value="<?php functions::xecho($this_diffusion->id);?>"
                        <?php
                        if(trim($_SESSION['m_admin']['notification']['diffusion_type']) == trim($this_diffusion->id)) {
                            echo 'selected="selected"';
                        }?>
                        >
                        <?php functions::xecho($this_diffusion->label); ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <div id="diff_type_div"
            class="scroll_div"
            style="height:200px;
                    width:600px;
                    border:1px solid;">
        </div>
		<p></p>
		<p>
            <label for="attach_for_type"><?php echo _ATTACH_MAIL_FILE;?> : </label>

            <select name="attach_for_type"
                    id="status" 
					onchange="change_properties_box(
						this.options[this.selectedIndex].value,
						'<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=load_attachfortype_formcontent',
						'attach_for_div',
						'notifications',
						'');">

                <option tag="voidAttach" value=""><?php echo _NEVER;?></option>
                <?php
                foreach($diffusion_types as $this_diffusion){
					if(
                        $this_diffusion->id != 'dest_user' 
                        && $this_diffusion->id != 'copy_list'
                        && $this_diffusion->id != 'dest_entity'
                        && $this_diffusion->id != 'note_dest_user'
                        && $this_diffusion->id != 'note_copy_list'
                    ) {
						?><option tag="attach" value="<?php functions::xecho($this_diffusion->id);?>"
						<?php
						if(trim($_SESSION['m_admin']['notification']['attachfor_type'])
							== trim($this_diffusion->id)) {
							echo 'selected="selected"';
						}?>><?php functions::xecho($this_diffusion->label);
						?></option><?php
					}
                }
                ?>
            </select>
        </p>

        <div id="attach_for_div" class="scroll_div" style="height:200px;width:600px;border:1px solid;">
			<!-- div for attachment options -->
			<p class="sstit"> <?php echo _NO_ATTACHMENT_WITH_NOTIFICATION;?></p>
        </div>
        <p class="buttons">
            <?php
        if ($mode == 'up') {?>
            <input type="submit" class="button" name="notif_submit" value=
            "<?php echo _VALIDATE;?>" />
            <?php
        } elseif ($mode == 'add') {?>
            <input type="submit" class="button" name="notif_submit" value=
            "<?php echo _ADD;?>" />
            <?php
        }
        ?>
        <input type="button" class="button"  name="cancel" value="<?php
         echo _CANCEL;?>" onclick="javascript:window.location.href='<?php
         echo $_SESSION['config']['businessappurl'];
         ?>index.php?page=manage_notifications_controler&amp;mode=list&amp;module=notifications'"/>
            <?php

        $filename = "notification";
        if (isset($_SESSION['custom_override_id']) && $_SESSION['custom_override_id']<>"") {
            $filename.="_".str_replace(" ", "", $_SESSION['custom_override_id']);
        }
        $filename.="_".$notification_sid.".sh";
        
        if ($_SESSION['custom_override_id'] <> '') {
            $pathToFolow = $_SESSION['config']['corepath'] . 'custom/'.$_SESSION['custom_override_id'] . '/';
        } else {
            $pathToFolow = $_SESSION['config']['corepath'];
        }
        
        if ($mode == 'up' && PHP_OS == "Linux" && !file_exists($pathToFolow.'modules/notifications/batch/scripts/'.$filename)) {?>
            <input class="button" type="button" name="create_notif_script" id="create_notif_script" value="<?php echo _CREATE_NOTIF_SCRIPT;?>" 
            onclick="createNotifScript('<?php echo $_SESSION['config']['businessappurl'];
         ?>index.php?display=true&amp;page=create_notif_script&amp;module=notifications', '<?php functions::xecho($_SESSION['m_admin']['notification']['notification_sid']);?>', '<?php echo $_SESSION['m_admin']['notification']['notification_id'];?>')"/>
            <?php
        }
        ?>
    </p>
    </form >
    </div>
<?php
    }
   ?></div><?php
	
	// Manage notification mode
	if ($_SESSION['m_admin']['notification']['notification_mode'] == 'EMAIL' 
		|| $_SESSION['m_admin']['notification']['notification_mode'] == '') {
		?>
        <script language="javascript">
			window.document.getElementById('rss_url_div').style.display = 'none';
			window.document.getElementById('template_div').style.display = 'block';
        </script>
        <?php		
	} elseif ($_SESSION['m_admin']['notification']['notification_mode'] == 'RSS')  {
		?>
        <script language="javascript">
			window.document.getElementById('rss_url_div').style.display = 'block';
			window.document.getElementById('template_div').style.display = 'none';
        </script>
        <?php
	}
	// Manage Diffusion type Div & content
    if ($_SESSION['m_admin']['notification']['diffusion_type'] <> '')
    {
        /*First Launch */
        ?>
        <script language="javascript">
        change_properties_box(
            '<?php functions::xecho($_SESSION['m_admin']['notification']['diffusion_type']);?>',
            '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=load_diffusiontype_formcontent',
            'diff_type_div',
            'notifications',
            '');
        </script>
        <?php
        if ($_SESSION['m_admin']['notification']['diffusion_type'] <> '')
        {
            //Loading Extra Javascript :
            require_once 'modules' . DIRECTORY_SEPARATOR . 'notifications' . DIRECTORY_SEPARATOR
                . 'class' . DIRECTORY_SEPARATOR . 'diffusion_type_controler.php';
            $Type = new diffusion_type_controler();
            $dType = $Type->get($_SESSION['m_admin']['notification']['diffusion_type']);
            ?>
            <script language="javascript">
            setTimeout(function(){loadDiffusionProperties(
                '<?php functions::xecho($_SESSION['m_admin']['notification']['diffusion_type']);?>',
                '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=load_diffusionproperties_formcontent'
                )},500);
            </script>
            <?php
        }
    }
	
	// Manage Attachment Div & content
	if ($_SESSION['m_admin']['notification']['attachfor_type'] <> '')
    {
        /*First Launch */
        ?>
        <script language="javascript">
		change_properties_box(
            '<?php functions::xecho($_SESSION['m_admin']['notification']['attachfor_type']);?>',
            '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=load_attachfortype_formcontent',
            'attach_for_div',
            'notifications',
            '');
        </script>
        <?php
        if ($_SESSION['m_admin']['notification']['attachfor_type'] <> '')
        {
            //Loading Extra Javascript :
            require_once 'modules' . DIRECTORY_SEPARATOR . 'notifications' . DIRECTORY_SEPARATOR
                . 'class' . DIRECTORY_SEPARATOR . 'diffusion_type_controler.php';
            $Type = new diffusion_type_controler();

            $dType = $Type->get($_SESSION['m_admin']['notification']['diffusion_type']);
            //include_once ($dType->script);
            ?>
            <script language="javascript">
                setTimeout(function () {loadAttachforProperties(
                '<?php functions::xecho($_SESSION['m_admin']['notification']['attachfor_type']);?>',
                '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=notifications&page=load_attachforproperties_formcontent',
				'attach_for_div'
                )},500);
            </script>
            <?php
        }
    }
}
