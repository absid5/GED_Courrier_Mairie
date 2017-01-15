<?php
/*
*    Copyright 2008,2009 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* File : create_folder_form.php
*
* Form to create a folder
*
* @package  Folder
* @version 1.0
* @since 06/2007
* @license GPL
* @author  <dev@maarch.org>
*/
$core_tools2 = new core_tools();
$core_tools2->load_lang();
$core_tools2->test_service('create_folder', 'folder');
$core_tools2->load_html();
$core_tools2->load_header('', true, true);
if(isset($_SESSION['error'])) { ?>
    <div class="error" id="main_error_popup" onclick="this.hide();">
        <?php
        echo functions::xssafe($_SESSION['error']);
        ?>
    </div>
<?php
}
 if(isset($_SESSION['error']) && $_SESSION['error'] <> '') {
    ?>
    <script>
        var main_error = $('main_error_popup');
        if (main_error != null) {
            main_error.style.display = 'table-cell';
            Element.hide.delay(10, 'main_error_popup');
        }
    </script>
    <?php
    $_SESSION['error']='';
}
if($_SESSION['info'] == _FOLDER_ADDED){
    $_SESSION['info'] = '';
    echo "<script>var select = parent.document.getElementById('folder');"
    ."if('".$_SESSION['m_admin']['folder']['folder_parent']."' != 0){
            var folderName = '       ".$_SESSION['m_admin']['folder']['folder_name']."';
        } else {
            var folderName = '".$_SESSION['m_admin']['folder']['folder_name']."';
        }
        "
    . "var newOption = new Option (folderName, '".$_SESSION['m_admin']['folder']['folders_system_id']."');"
    . "newOption.setAttribute('parent','".$_SESSION['m_admin']['folder']['folder_parent']."');"
    . "var oSelect = select.options;"
    . "if('".$_SESSION['m_admin']['folder']['folder_parent']."' != 0){
            for(var i = 0; i < oSelect.length; i++) {
                if(oSelect[i].value === '".$_SESSION['m_admin']['folder']['folder_parent']."') {
                    var index = i+1;
                    break;
                }
            }
            select.options.add (newOption, index);
        } else {
            select.options.add (newOption);
        }
        "
    . "select.value = '".$_SESSION['m_admin']['folder']['folders_system_id']."';";
    echo "Event.fire(select, 'chosen:updated');";
    unset($_SESSION['m_admin']);
    echo "new Effect.BlindUp(parent.document.getElementById('create_folder_div'));";
    echo "</script>";
}
$db = new Database();

$stmt = $db->query("SELECT foldertype_id, foldertype_label FROM " .
    $_SESSION['tablename']['fold_foldertypes'] . " order by foldertype_label");

$foldertypes = array();
while ($res = $stmt->fetchObject()) {
    array_push(
        $foldertypes,
        array(
        	'id' => $res->foldertype_id,
        	'label' => $res->foldertype_label,
        )
    );
}
$init = false;
?>
<div id="inner_content" style="padding:0px;">
    <div class="block">
        <h2><i class="fa fa-folder-open fa-2x" title="" ></i> <?php echo _CREATE_FOLDER;?></h2>
        <form name="create_folder" id="create_folder" method="post" class="forms"
              action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=folder&page=manage_create_folder&iframe=true" >
            <input type="hidden" name="display"  value="true" />
            <input type="hidden" name="module"  value="folder" />
            <input type="hidden" name="page"  value="manage_create_folder" />
            <p>
                <label for="foldertype"><?php echo _FOLDERTYPE;?> :</label>
                <select name="foldertype" id="foldertype"
                        onchange="get_folder_index('<?php echo $_SESSION['config']['businessappurl'] . 'index.php?display=true' . '&module=folder&page=create_folder_get_folder_index'; ?>'
                            , this.options[this.options.selectedIndex].value, 'folder_indexes');">
                    <option value=""><?php echo _CHOOSE_FOLDERTYPE;?></option>
                    <?php
                    for ($i = 0; $i < count($foldertypes); $i ++) { ?>
                        <option value="<?php functions::xecho($foldertypes[$i]['id']); ?>"
                            <?php
                            if (isset($_SESSION['m_admin']['folder']['foldertype_id'])
                            && $_SESSION['m_admin']['folder']['foldertype_id'] == $foldertypes[$i]['id']) {
                                echo 'selected="selected"';
                            } else if($i == 0) {
                                $init = true;
                                echo 'selected="selected"';
                            }
                            ?>
                        >
                            <?php functions::xecho($foldertypes[$i]['label']); ?>
                        </option>
                    <?php } ?>
                </select> <i class="red_asterisk fa fa-asterisk mCyellow"></i>
            </p>
            <p>
                <label for="folder_id"><?php echo _FOLDERID_LONG;?></label>
                <input name="folder_id" id="folder_id"
                       value="<?php if (isset($_SESSION['m_admin']['folder']['folder_id'])) { functions::xecho($_SESSION['m_admin']['folder']['folder_id']);}?>" />
                <i class="red_asterisk fa fa-asterisk mCyellow"></i>
            </p>
            <p>
                <label for="folder_name"><?php echo _FOLDERNAME;?></label>
                <input name="folder_name" id="folder_name"
                       value="<?php if (isset($_SESSION['m_admin']['folder']['folder_name'])) { functions::xecho($_SESSION['m_admin']['folder']['folder_name']);} ?>" />
                <i class="red_asterisk fa fa-asterisk mCyellow"></i>
            </p>
            <div id="folder_indexes"></div>
            <div id="folder_dest_div">
                <p>
                    <label for="folder_dest"><?php echo _FOLDER_DESTINATION_QUESTION;?></label>
                    <input name="folder_dest" id="folder_dest" type="checkbox" style="margin-left: -0.1%"/>
                </p>
            </div>
            <p class="buttons" style="text-align:center;">
                <input type="submit" name="validate" id="validate" value="<?php echo _VALIDATE; ?>" class="button"/>
                <input type="button" name="cancel" id="cancel" value="<?php echo _CANCEL; ?>"
                       class="button" onclick="new Effect.BlindUp(parent.document.getElementById('create_folder_div'));return false;" />
            </p>
        </form>
    </div>
    <style type="text/css">p{padding: 5px;}</style>
    <?php
    if ((isset($_SESSION['m_admin']['folder']['foldertype_id'])
            && ! empty($_SESSION['m_admin']['folder']['foldertype_id'] )) || $init)
    { ?>
        <script type="text/javascript">
            var ft_list = $('foldertype');
            if (ft_list) {
                get_folder_index('<?php echo $_SESSION['config']['businessappurl'] . 'index.php?display=true' . '&module=folder&page=create_folder_get_folder_index'; ?>'
                    , ft_list.options[ft_list.options.selectedIndex].value, 'folder_indexes');
            }
        </script>
    <?php } ?>
</div>
