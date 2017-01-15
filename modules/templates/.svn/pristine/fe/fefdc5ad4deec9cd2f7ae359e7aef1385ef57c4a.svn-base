<?php

/*
*   Copyright 2008-2015 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

if ($_SESSION['service_tag'] == 'doctype_up') {
    $db = new Database();
    $stmt = $db->query("select * from ".$_SESSION['tablename']['temp_templates_doctype_ext']." where type_id = ? ",
				array($_SESSION['m_admin']['doctypes']['TYPE_ID'])
			);
			
    //$db->show();
    if ($stmt->rowCount() == 0) {
        $_SESSION['m_admin']['doctypes']['is_generated'] = 'N';
        $_SESSION['m_admin']['doctypes']['template_id'] = '';
    } else {
        $line = $stmt->fetchObject();
        $_SESSION['m_admin']['doctypes']['is_generated'] = $line->is_generated;
        $_SESSION['m_admin']['doctypes']['template_id'] = $line->template_id;
    }
} elseif ($_SESSION['service_tag'] == 'doctype_add') {
    $_SESSION['m_admin']['doctypes']['is_generated'] = 'N';
    $_SESSION['m_admin']['doctypes']['template_id'] = '';
} elseif ($_SESSION['service_tag'] == 'frm_doctype') {
    $db = new Database();
    $stmt = $db->query("select template_id, template_label from "
        . $_SESSION['tablename']['temp_templates'] . " where template_type = 'HTML' and (template_target = 'doctypes' or template_target = '')"
    );
    $templates = array();
    while ($res = $stmt->fetchObject()) {
        array_push($templates, array('id' => $res->template_id, 'label' => $res->template_label));
    }
    ?>
    <p>
        <span><input type="radio"  class="check" onclick="javascript:show_templates(false);" name="e_file" id="load_file" value="N"  <?php  if ($_SESSION['m_admin']['doctypes']['is_generated'] == 'N') { echo 'checked="checked"';} ?>/><?php echo _LOADED_FILE;?></span>
        <span><input type="radio" class="check" onclick="javascript:show_templates(true);" name="e_file" id="gen_file" value="Y"  <?php  if ($_SESSION['m_admin']['doctypes']['is_generated'] == 'Y') { echo 'checked="checked"';} ?> /><?php echo _GENERATED_FILE;?></span>
    </p>
    <div id="templates_div" style="display:<?php if($_SESSION['m_admin']['doctypes']['is_generated'] == 'Y'){ echo "block";}else{ echo "none";}?>">
        <span><?php echo _CHOOSE_TEMPLATE;?> :</span>
        <select name="templates" id="templates" >
            <option value=""><?php echo _CHOOSE_TEMPLATE;?></option>
            <?php
                for ($i=0;$i<count($templates);$i++) {
                    echo '<option value="'.$templates[$i]['id'].'" ';
                    if ($_SESSION['m_admin']['doctypes']['template_id'] == $templates[$i]['id'] ) {
                        echo 'selected="selected"';
                    }
                    echo '>'.$templates[$i]['label'].'</option>';
                }
            ?>
        </select><span class="red_asterisk"><i class="fa fa-star"></i></span>
    </div>
    <?php
} elseif ($_SESSION['service_tag'] == "doctype_info") {
    if ($_REQUEST['e_file'] == "Y" && (empty($_REQUEST['templates']) || !isset($_REQUEST['templates']))) {
        $_SESSION['error'] .= _MUST_CHOOSE_TEMPLATE;
    } else if((empty($_REQUEST['templates']) || !isset($_REQUEST['templates']))) {
        $_SESSION['m_admin']['doctypes']['template_id'] = '';
    }
    $_SESSION['m_admin']['doctypes']['is_generated'] = $_REQUEST['e_file'];
    $_SESSION['m_admin']['doctypes']['template_id'] = $_REQUEST['templates'];

} elseif ($_SESSION['service_tag'] == "doctype_updatedb") {
    $db = new Database();
    $stmt = $db->query("delete from ".$_SESSION['tablename']['temp_templates_doctype_ext']." where type_id = ? ",
				array($_SESSION['m_admin']['doctypes']['TYPE_ID'])
				);
				
    if (!empty($_SESSION['m_admin']['doctypes']['template_id']) && isset($_SESSION['m_admin']['doctypes']['template_id'])) {
        $stmt = $db->query("insert into ".$_SESSION['tablename']['temp_templates_doctype_ext']." (type_id, is_generated, template_id) values (?, ?, ?) ",
					array($_SESSION['m_admin']['doctypes']['TYPE_ID'], $_SESSION['m_admin']['doctypes']['is_generated'], $_SESSION['m_admin']['doctypes']['template_id'])
				);
    } else {
        $stmt = $db->query("insert into ".$_SESSION['tablename']['temp_templates_doctype_ext']." (type_id, is_generated) values (?, ?) ",
					array($_SESSION['m_admin']['doctypes']['TYPE_ID'], $_SESSION['m_admin']['doctypes']['is_generated'])
				);
    }
    //$db->show();
    //exit();
} elseif ($_SESSION['service_tag'] == "doctype_insertdb") {
    $db = new Database();
    if (!empty($_SESSION['m_admin']['doctypes']['template_id']) && isset($_SESSION['m_admin']['doctypes']['template_id'])) {
        $stmt = $db->query("insert into ".$_SESSION['tablename']['temp_templates_doctype_ext']." (type_id, is_generated, template_id) values (?, ?, ?) ",
					array($_SESSION['m_admin']['doctypes']['TYPE_ID'], $_SESSION['m_admin']['doctypes']['is_generated'], $_SESSION['m_admin']['doctypes']['template_id'])
				);
    } else {
        $stmt = $db->query("insert into ".$_SESSION['tablename']['temp_templates_doctype_ext']." (type_id, is_generated) values (?, ?) ",
					array($_SESSION['m_admin']['doctypes']['TYPE_ID'], $_SESSION['m_admin']['doctypes']['is_generated'])
				);
    }
} elseif ($_SESSION['service_tag'] == "doctype_delete") {
    $db = new Database();
    $stmt = $db->query("delete from ".$_SESSION['tablename']['temp_templates_doctype_ext']." where type_id = ? ",
				 array($_SESSION['m_admin']['doctypes']['TYPE_ID'])
			);
}
