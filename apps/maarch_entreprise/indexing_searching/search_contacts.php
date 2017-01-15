<?php
/*
*    Copyright 2014 Maarch
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
*
* @file search_contacts.php
* @author <dev@maarch.org>
* @date $date$
* @version $Revision$
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_indexing_searching_app.php");
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();

$_SESSION['search']['plain_text'] = "";

$func = new functions();
$conn = new Database();

$search_obj = new indexing_searching_app();

$_SESSION['indexation'] = false;

if (isset($_REQUEST['exclude'])){
    $_SESSION['excludeId'] = $_REQUEST['exclude'];
}

$mode = 'normal';

$core_tools->test_service('search_contacts', 'apps');
/****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit']  == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=search_contacts&dir=indexing_searching';
$page_label = _SEARCH_CONTACTS;
$page_id = "search_contacts";
$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
/***********************************************************/

//Check if web brower is ie_6 or not
if (preg_match("/MSIE 6.0/", $_SERVER["HTTP_USER_AGENT"])) {
    $browser_ie = 'true';
    $class_for_form = 'form';
    $hr = '<tr><td colspan="2"><hr></td></tr>';
    $size = '';
} elseif(preg_match('/msie/i', $_SERVER["HTTP_USER_AGENT"]) && !preg_match('/opera/i', $HTTP_USER_AGENT) )
{
    $browser_ie = 'true';
    $class_for_form = 'forms';
    $hr = '';
     $size = '';
}
else
{
    $browser_ie = 'false';
    $class_for_form = 'forms';
    $hr = '';
     $size = '';
}

// building of the parameters array used to pre-load the category list and the search elements
$param = array();

// Sorts the param array
function cmp($a, $b)
{
    return strcmp(strtolower($a["label"]), strtolower($b["label"]));
}
uasort($param, "cmp");

$tab = $search_obj->send_criteria_data($param);

// criteria list options
$src_tab = $tab[0];

$core_tools->load_js();
?>
<script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=search_adv.js" ></script>
<script type="text/javascript">
<!--
    var valeurs = { <?php functions::xecho($tab[1]);?>};
    var loaded_query = <?php if(isset($_SESSION['current_search_query']) && !empty($_SESSION['current_search_query']))
    { echo $_SESSION['current_search_query'];}else{ echo '{}';}?>;

-->
</script>

<h1><i class="fa fa-search fa-2x"></i> <?php echo _SEARCH_CONTACTS;?></h1>
<div id="inner_content">

<form name="frmsearch2" method="get" action="<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&dir=indexing_searching&page=search_contacts_result';?>"  id="frmsearch2" class="<?php functions::xecho($class_for_form);?>">
    <input type="hidden" name="dir" value="indexing_searching" />
    <input type="hidden" name="page" value="search_contacts_result" />
    <input type="hidden" name="mode" value="<?php functions::xecho($mode);?>" />
    <?php
    $contact_types = array();

    $stmt = $conn->query("SELECT id, label FROM ".$_SESSION['tablename']['contact_types']);
    while($res = $stmt->fetchObject()){
        $contact_types[$res->id] = functions::show_string($res->label); 
    }
    ?>
    <table width="100%">
        <tr>
            <td align="right">
                <input class="button" type="button" align="right" value="<?php echo _SEARCH_ADDRESSES;?>" onclick="window.location.href='<?php echo $_SESSION['config']['businessappurl'] . 'index.php?page=list_results_addresses&dir=indexing_searching&fromSearchContacts'?>'"/>      
            </td>
        </tr>
    </table>
        <tr >
            <td >
            <div class="block">
                <h2><?php echo _SEARCH_CONTACTS;?></h2>
                <table border = "0" width="100%">
                    <tr>
                        <td width="70%">
                            <label for="contact_type" class="bold" ><?php echo _CONTACT_TYPE;?> :</label>
                            <select name="contact_type" id="contact_type" >
                                <option value=""><?php echo _CHOOSE_CONTACT_TYPES;?></option>
                                <?php
                                foreach(array_keys($contact_types) as $key)
                                {
                                    ?><option value="<?php functions::xecho($key);?>">
                                        <?php functions::xecho($contact_types[$key]);?>
                                    </option><?php
                                }?>
                            </select>
                            <input type="hidden" name="meta[]" value="contact_type#contact_type#input_text" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70%"><label for="society" class="bold" ><?php echo _STRUCTURE_ORGANISM;?> :</label>
                            <input type="text" name="society" id="society" <?php functions::xecho($size);?>  />
                            <input type="hidden" name="meta[]" value="society#society#input_text" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70%"><label for="society_short" class="bold" ><?php echo _SOCIETY_SHORT;?> :</label>
                            <input type="text" name="society_short" id="society_short" <?php functions::xecho($size);?>  />
                            <input type="hidden" name="meta[]" value="society_short#society_short#input_text" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70%"><label for="lastname" class="bold"><?php echo _LASTNAME;?> :</label>
                            <input type="text" name="lastname" id="lastname" <?php functions::xecho($size);?>  />
                            <input type="hidden" name="meta[]" value="lastname#lastname#input_text" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70%"><label for="firstname" class="bold" ><?php echo _FIRSTNAME;?> :</label>
                            <input type="text" name="firstname" id="firstname" <?php functions::xecho($size);?>  />
                            <input type="hidden" name="meta[]" value="firstname#firstname#input_text" />
                        </td>
                    </tr>
                    <tr>
                        <td width="70%"><label for="created_by" class="bold"><?php echo _CREATE_BY;?> :</label>
                            <input type="text" name="created_by" id="created_by" onkeyup="erase_contact_external_id('created_by', 'created_by_id');"/>
                            <input type="hidden" name="meta[]" value="created_by#created_by#input_text" />
                            <div id="contactListByName" class="autocomplete"></div>
                            <script type="text/javascript">
                                initList_hidden_input('created_by', 'contactListByName', '<?php 
                                    echo $_SESSION['config']['businessappurl'];?>index.php?display=true&dir=indexing_searching&page=users_list_by_name_search', 'what', '2', 'created_by_id');
                            </script>
                            <input id="created_by_id" name="created_by_id" type="hidden" />
                        </td>
                        <td><em><?php echo "";?></em></td>
                    </tr>
                </table>
                </div>
                <div class="block_end" style="margin-top:-20px">&nbsp;</div>
            </td>
        </tr>

    <select name="select_criteria" id="select_criteria" style="display: none;"></select>

    <table align="center" border="0" width="100%">
        <tr>
            <td>
                <a href="#" onclick="clear_search_form('frmsearch2','select_criteria');clear_q_list();">
                    <i class="fa fa-refresh fa-4x" title="<?php echo _CLEAR_FORM;?>"></i>
                </a>
            </td>
            <td align="right">
                <span style="display:none;">
                    <input name="imageField" type="submit" value="" onclick="valid_search_form('frmsearch2');this.form.submit();" />
                </span>
                <a href="#" onclick="valid_search_form('frmsearch2');$('frmsearch2').submit();">
                    <i class="fa fa-search fa-4x" title="<?php echo _SEARCH;?>"></i>
                </a>
            </td>
        </tr>
    </table>

</form>
<br/>
<div align="right">
</div>
 </div>
<script type="text/javascript">
load_query(valeurs, loaded_query, 'frmsearch2', '<?php functions::xecho($browser_ie);?>, <?php echo _ERROR_IE_SEARCH;?>');
<?php if(isset($_REQUEST['init_search']))
{
    ?>clear_search_form('frmsearch2','select_criteria');clear_q_list(); <?php
}?>
</script>
