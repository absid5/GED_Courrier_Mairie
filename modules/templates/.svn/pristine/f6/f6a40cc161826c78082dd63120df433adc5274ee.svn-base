<?php

/*
*   Copyright 2008-2012 Maarch
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

/**
* File : choose_template.php
*
* Pop up to choose a document template for an answer in the process
*
* @since 10/2007
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
require_once 'modules/templates/templates_tables_definition.php';

if (isset($_REQUEST['template']) && !empty($_REQUEST['template'])) {
    require_once('core/class/class_security.php');
    $sec = new security();
    $objectTable = $sec->retrieve_table_from_coll($_REQUEST['coll_id']);
    require_once('modules/templates/class/templates_controler.php');
    $templates_controler = new templates_controler();
    $template = $templates_controler->get($_REQUEST['template']);
    if ($template->template_type == 'HTML') {
        header(
            'location: ' . $_SESSION['config']['businessappurl'] 
            . 'index.php?display=true&module=templates&page=generate_attachment_html&mode=add&template=' 
            . $_REQUEST['template'] . '&res_id=' . $_REQUEST['res_id'] 
            . '&coll_id=' . $_REQUEST['coll_id']
        );
    } else {
         header(
            'location: ' . $_SESSION['config']['coreurl']
            . 'modules/content_management/applet_launcher.php?objectType=attachmentFromTemplate' 
            . '&objectId=' . $_REQUEST['template'] . '&objectTable=' . $objectTable
            . '&resMaster=' . $_REQUEST['res_id']
        );
    }
    exit();
}

$db = new Database();

$stmt = $db->query(
    "select * from " 
    . _TEMPLATES_TABLE_NAME . " t, " 
    . _TEMPLATES_ASSOCIATION_TABLE_NAME . " ta where "
    . "t.template_id = ta.template_id and ta.what = 'destination' and ta.value_field = ? ", array($_REQUEST['entity'])
);


$templates = array();

while($res = $stmt->fetchObject())
{
    array_push(
        $templates, array(
            'ID' => $res->template_id, 
            'LABEL' => $res->template_label,
            'TYPE' => $res->template_type,
        )
    );
}

$core_tools->load_html();
$time = $core_tools->get_session_time_expire();
//here we building the header
$core_tools->load_header(_CHOOSE_TEMPLATE, true, false);

?>
<body id="pop_up"  onload="setTimeout(window.close, <?php echo $time;?>*60*1000);">
<h2 class="tit"><?php echo _CHOOSE_TEMPLATE;?> </h2>
<div align="center"><b><?php functions::xecho($erreur);?></b></div>
<form enctype="multipart/form-data" method="post" name="attachment" action="<?php 
    echo $_SESSION['config']['businessappurl'];
    ?>index.php?display=true&module=templates&page=choose_template">
    <input type="hidden" name="display"  value="true" />
    <input type="hidden" name="module"  value="templates" />
    <input type="hidden" name="page"  value="choose_template" />
    <input type="hidden" name="res_id" id="res_id" value="<?php 
        functions::xecho($_REQUEST['res_id']);
        ?>" />
    <input type="hidden" name="coll_id" id="coll_id" value="<?php 
        functions::xecho($_REQUEST['coll_id']);
        ?>" />
    <p><label><?php echo _PLEASE_SELECT_TEMPLATE;?> :</label></p>
    <br/>
    <p>
        <select name="template" id="template" style="width:150px" onchange="this.form.submit();">
            <option value=""></option>
            <?php
                for ($i=0;$i<count($templates);$i++) {
                    ?>
                        <option value="<?php 
                            functions::xecho($templates[$i]['ID']);
                            ?>"><?php 
                            functions::xecho($templates[$i]['TYPE'] . ' : ' 
                            . $templates[$i]['LABEL']);
                        ?></option>
                    <?php
                }
            ?>
        </select>
    </p>
    <br/>
    <p class="buttons">
    <input type="button" value="<?php 
        echo _CANCEL;
        ?>" name="cancel" class="button"  onclick="self.close();"/>
</form>
<?php $core_tools->load_js();?>
</body>
</html>
