<?php
/**
*
*    Copyright 2008,2012 Maarch
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
* @brief    Advanced search form for folders
*
* @file     search_adv_folder.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  folder
*/


require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
$core = new core_tools();
$core->test_user();
$core->test_service('folder_search', 'folder');

/****************Management of the location bar  ************/
$init = false;
if (isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true") {
    $init = true;
}
$level = "";
if (isset($_REQUEST['level']) && ($_REQUEST['level'] == 2
    || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4
    || $_REQUEST['level'] == 1)
) {
    $level = $_REQUEST['level'];
}
$pagePath = $_SESSION['config']['businessappurl']
    . 'index.php?page=search_adv_folder&module=folder';
$pageLabel = _SEARCH_ADV_FOLDER;
$pageId = "search_folder_adv";
$core->manage_location_bar($pagePath, $pageLabel, $pageId, $init, $level);
/***********************************************************/

$foldertypes = array();
$chooseColl = true;
$db = new Database();

if (count($_SESSION['user']['collections']) == 1 ) {
    $chooseColl = false;

    $stmt = $db->query(
    	"SELECT foldertype_id, foldertype_label FROM "
        . $_SESSION['tablename']['fold_foldertypes'] . " WHERE coll_id = ? order by foldertype_label",
        array($_SESSION['user']['collections'][0])
    );
    while ($res = $stmt->fetchObject()) {
        array_push(
            $foldertypes,
            array(
            	'id' => $res->foldertype_id,
            	'label' => $res->foldertype_label
            )
        );
    }
}

if (isset($_REQUEST['erase']) && $_REQUEST['erase'] == 'true') {
    $_SESSION['folder_search'] = array();
}
?>
<div id="inner_content">

<h1><i class="fa fa-search fa-2x" title="" /></i> <?php echo _ADV_SEARCH_FOLDER_TITLE;?></h1>

<form name="search_folder_frm" id="search_folder_frm" method="post" action="<?php echo $_SESSION['config']['businessappurl'] 
            .'index.php?page=search_adv_folder_result&module=folder';?>" class="forms2">
    <input type="hidden" name="module"  value="folder" />
    <input type="hidden" name="page"  value="search_adv_folder_result" />
    
<table align="center" border="0" width="100%">
    <tr>
        <td><a href="javascript://" onclick="javascript:window.top.location.href='<?php echo $_SESSION['config']['businessappurl'] 
            .'index.php?page=search_adv_folder&module=folder&reinit=true&erase=true';?>';">
            <i class="fa fa-refresh fa-4x" title="<?php echo _CLEAR_SEARCH;?>"></i>
        </td>
        <td align="right">
            <span style="display:none;">
                <input name="imageField" type="submit" value="" onclick="$('search_folder_frm').form.submit();" />
            </span>
            <a href="#" onclick="$('search_folder_frm').submit();">
                <i class="fa fa-search fa-4x" title="<?php echo _SEARCH;?>"></i>
            </a>
        </td>
    </tr>
</table>

<?php
if ($chooseColl) {
    ?>
    <div class="block">
    <h2><?php echo _COLLECTION;?></h2>
    <table width="100%" border="0" cellpadding="3">
        <tr>
            <td width="70%">
                <label for="coll_id"><?php echo _COLLECTION;?> :</label>
                <select name="coll_id" id="coll_id" 
                    onchange="search_change_coll('<?php echo $_SESSION['config']['businessappurl']
                    .'index.php?display=true'
                    . '&module=folder&page=get_foldertypes';?>', 
                    this.options[this.options.selectedIndex].value)">
                    <option value=""><?php echo _CHOOSE_COLLECTION;?></option>
                    <?php
                    foreach (array_keys($_SESSION['user']['security']) as $coll) {
                        ?><option value="<?php functions::xecho($coll);?>"><?php
                        functions::xecho($_SESSION['user']['security'][$coll]['DOC']['label_coll']);
                        ?></option><?php
                    }
                    ?>
                </select>
            </td>
            <td><em><?php echo _MUST_CHOOSE_COLLECTION_FIRST;?></em></td>
            <td>&nbsp;</td>
        </tr>
    </table>
    </div>
    <div class ="block_end">&nbsp;</div> <br/><?php
} else {
?>
    <input type="hidden" name="coll_id" id="coll_id" 
        value="<?php
            if (isset($_SESSION['user']['security'][0]['coll_id'])) {
                functions::xecho($_SESSION['user']['security'][0]['coll_id']);
            }
        ?>" />
<?php
}
?>

<div id="folder_search_div" style="display:<?php if ($chooseColl) {echo "none"; } else {echo "block";}?>">
    <div class="block">
        <h2><?php echo _INFOS_FOLDERS;?></h2>
        <br/>
        <table width="100%" border="0" cellpadding="3">
            <tr>
                <td width="25%" align="right"><label for="foldertype_id"><?php echo _FOLDERTYPE;?> :</label></td>
                <td width="24%">
                    <select name="foldertype_id" id="foldertype_id" onchange="
                        get_folder_index('<?php echo $_SESSION['config']['businessappurl'] 
                        . 'index.php?display=true'
                        . '&module=folder&page=get_folder_search_index';?>', 
                        this.options[this.options.selectedIndex].value, 'opt_indexes')">
                        <option value=""><?php echo _CHOOSE_FOLDERTYPE;?></option>
                        <?php
                        for ($i = 0; $i < count($foldertypes); $i ++) {
                            ?><option value="<?php functions::xecho($foldertypes[$i]['id']);?>" <?php
                            if (isset($_SESSION['folder_search']['foldertype_id'])
                                && $foldertypes[$i]['id'] == $_SESSION['folder_search']['foldertype_id']
                            ) {
                                echo 'selected="selected"';
                            }
                            ?>><?php functions::xecho($foldertypes[$i]['label']);?></option><?php
                        }
                        ?>
                    </select>
                </td>
                <td width="2%">&nbsp;</td>
                <td width="25%" align="right"><label for="folder_id"><?php echo _FOLDERID;?> :</label></td>
                <td width="24%">
                    <input type="text" name="folder_id" id="folder_id" value="<?php
                        if (isset($_SESSION['folder_search']['folder_id'])) {
                            functions::xecho($_SESSION['folder_search']['folder_id']);
                        }
                        ?>" />
                    <div id="foldersListById" class="autocomplete"></div>
                    <script type="text/javascript">
                        initList('folder_id', 
                            'foldersListById', 
                            '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=folder&page=folders_list_by_id', 
                            'Input', '2');
                    </script>
                </td>
            </tr>
            <tr>
                <td width="25%" align="right">
                    <label for="creation_date_start"><?php echo _CREATION_DATE . ' ' . _START;?> :<label>
                </td>
                <td width="24%">
                    <input name="creation_date_start" type="text" id="creation_date_start" 
                    value="<?php
                    if (isset($_SESSION['folder_search']['creation_date_start'])) {
                        functions::xecho($_SESSION['folder_search']['creation_date_start']);
                    }
                    ?>" onclick='showCalender(this)'/>
                </td>
                <td width="2%">&nbsp;</td>
                <td width="25%" align="right">
                    <label for="creation_date_end"><?php echo _CREATION_DATE . ' ' . _END;?>:<label>
                </td>
                <td width="24%">
                    <input name="creation_date_end" type="text" id="creation_date_end" 
                    value="<?php
                    if (isset($_SESSION['folder_search']['creation_date_end'])) {
                        functions::xecho($_SESSION['folder_search']['creation_date_end']);
                    }
                    ?>" onclick='showCalender(this)'/>
                </td>
            </tr>
            <tr>
                <td width="25%" align="right"><label for="folder_name"><?php echo _FOLDERNAME;?> :<label></td>
                <td colspan="3">
                    <input name="folder_name" type="text" id="folder_name" 
                    value="<?php
                    if (isset($_SESSION['folder_search']['folder_name'])) {
                        functions::xecho($_SESSION['folder_search']['folder_name']);
                    }
                    ?>" />
                </td>
            </tr>
        </table>
        <br /><div id="opt_indexes"></div>
    </div>
    <div class="block_end"></div>
    </form>

    <?php unset($_SESSION['folder_search']);?>

    <script type="text/javascript">
    var foldertypes = $('foldertype_id');
    if(foldertypes)
    {
        get_folder_index('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=folder&page=get_folder_search_index', foldertypes.options[foldertypes.options.selectedIndex].value, 'opt_indexes');
    }
    </script>
</div>
</div>
