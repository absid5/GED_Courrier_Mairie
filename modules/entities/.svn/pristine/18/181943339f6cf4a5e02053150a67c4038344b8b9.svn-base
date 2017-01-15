<?php

require_once 'modules/entities/class/class_manage_listdiff.php';
require_once 'core/class/usergroups_controler.php';
$core_tools = new core_tools();

$admin = new core_tools();
$admin->test_admin('admin_listmodels', 'entities');
 /****************Management of the location bar  ************/
$init = false;
if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
{
    $init = true;
}
$level = "";
if(isset($_REQUEST['level']) &&($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
{
    $level = $_REQUEST['level'];
}
$page_path = $_SESSION['config']['businessappurl'].'index.php?page=amdin_listmodel&module=entities';
$page_label = _ADMIN_LISTMODEL;
$page_id = "amdin_listmodel";
$admin->manage_location_bar($page_path, $page_label, $page_id, $init, $level);

$difflist = new diffusion_list();
# all roles available
$roles = $difflist->list_difflist_roles();

# list difflist_types
$difflistTypes = $difflist->list_difflist_types();

# Load listmodel into session

//var_dump($_SESSION['m_admin']['entity']['listmodel']);
//var_dump($_SESSION['m_admin']['entity']['difflist_type']);

$mode = $_REQUEST['mode'];

$objectType = trim(strtok($_REQUEST['id'], '|'));
$objectId = strtok('|');
    
if(!isset($_SESSION['m_admin']['entity']['listmodel'])) {
    # Listmodel to be loaded (up action on list or reload in add mode)
    $_SESSION['m_admin']['entity']['difflist_type'] = $difflist->get_difflist_type($objectType);
    
    $_SESSION['m_admin']['entity']['listmodel'] =  
        $difflist->get_listmodel(
            $objectType,
            $objectId
        );
    $title =  $_SESSION['m_admin']['entity']['listmodel']['title'];
    $description =  $_SESSION['m_admin']['entity']['listmodel']['description'];
} else {
    # list already loaded and managed (reload after update of list)
    $objectType = $_SESSION['m_admin']['entity']['listmodel']['object_type'];
    $objectId = $_SESSION['m_admin']['entity']['listmodel']['object_id'];
    $title = $_SESSION['m_admin']['entity']['listmodel']['title'];
    $description =  $_SESSION['m_admin']['entity']['listmodel']['description'];
}



# JAVASCRIPT 
# *****************************************************************************
?>
<script type="text/javascript">
// OnChange ObjectType / onLoad
//   set value / input mode for object id
function listmodel_setObjectType() 
{
    var objectType = $('objectType').value;
    var objectType_info = $('objectType_info');
    new Ajax.Request(
        'index.php?display=true&module=entities&page=admin_listmodel_setObjectType',
        {
            method:'post',
            parameters: 
			{ 
                objectType : objectType
			},
            onSuccess: function(answer){
                objectType_info.innerHTML = answer.responseText;
                objectType_info.style.display = 'block';
            }
        }
    );

    
}


function listmodel_setObjectId(objectId) 
{
    var mode = $('mode').value;
    
    var objectType = $('objectType').value;
    var objectId_input = $('objectId_input');
			
    new Ajax.Request(
        'index.php?display=true&module=entities&page=admin_listmodel_setObjectId',
        {
            method:'post',
            parameters: 
			{ 
				mode : mode,
                objectType : objectType,
                objectId : objectId
			},
            onSuccess: function(answer){
                objectId_input.innerHTML = answer.responseText;
                objectId_input.style.display = 'block';
            }
        }
    );

}
 
function listmodel_open()
{
    var main_error = $('main_error'); 
    
    // Validate form
    var valid = listmodel_validate();
    
    if(valid == false)
        return;
    
    // Open pop up 
    window.open(
        'index.php?display=true&module=entities&page=manage_listmodel',
        '', 
        'scrollbars=yes,menubar=no,toolbar=no,status=no,resizable=yes,width=1024,height=650,location=no'
    );
                    
    
}

function listmodel_validate() {
    // Control input values
    var main_error = $('main_error'); 
       
    var mode = $('mode').value; 
    var objectType = $('objectType').value; 
    var objectId = $('objectId').value; 
    var title = $('title').value;
    var description = $('description').value; 
    
    main_error.innerHTML = "";
    
    new Ajax.Request(
        'index.php?display=true&module=entities&page=admin_listmodel_validateHeader',
        {
            method:'post',
            asynchronous:false,
            parameters: 
			{ 
				mode : mode,
                objectType : objectType,
                objectId : objectId,
                title : title,
                description : description 
			},
            onSuccess: function(answer) {
                if(answer.responseText) {
                    main_error.innerHTML += answer.responseText;
                    this.valid = false;
                    main_error.style.display = 'table-cell';
                    Element.hide.delay(10, 'main_error');
                } else {
                    this.valid = true;
                }
            }
        }
    ); 
    return this.valid;
}

function listmodel_save()
{
    var mode = $('mode').value;  
    var objectType = $('objectType').value; 
    var objectId = $('objectId').value;
    var title = $('title').value; 
    var description = $('description').value; 
    
    
    // Validate form
    var valid = listmodel_validate();
    if(valid == false)
        return;
    
    // Check if type/id already used
    new Ajax.Request(
        'index.php?display=true&module=entities&page=admin_listmodel_save',
        {
            method:'post',
            parameters: 
			{ 
				mode : mode,
                objectType : objectType,
                objectId : objectId,
                title : title,
                description : description
			},
            onSuccess: function(answer){
                if(answer.responseText)
                    main_error.innerHTML = answer.responseText;
                else {
                    goTo('index.php?module=entities&page=admin_listmodels');
                }
            }
        }
    ); 
}

function listmodel_del(
    objectType,
    objectId
) {    
    new Ajax.Request(
        'index.php?display=true&module=entities&page=admin_listmodel_save',
        {
            method:'post',
            parameters: 
            { 
                mode : 'del',
                objectType : objectType,
                objectId : objectId,
            },
            onSuccess: function(answer){
                if(answer.responseText)
                    main_error.innerHTML = answer.responseText;
                else {
                    goTo('index.php?module=entities&page=admin_listmodels');
                }
            }
        }
    ); 

}
    
</script><?php
if($mode != 'del') { ?>
<h1><i class="fa fa-share-alt-square fa-2x"></i> <?php 
    echo _ADMIN_LISTMODEL;
    if($objectType) echo ' : ' . $difflistTypes[$objectType];
    if($objectId) echo " " . $objectId;
    ?>
</h1>
<br/>
<div id="listmodel_box" class="block" style="height:550px;">
	<h2 class="tit"><?php echo _LINKED_DIFF_LIST;?> : </h2><?php
    $difflist = $_SESSION['m_admin']['entity']['listmodel'];
    require_once 'modules/entities/difflist_display.php';?>
	<p class="buttons" style="text-align:center;margin-top:5px;">
		<input type="button" onclick="listmodel_open()" class="button" value="<?php echo _MODIFY_LIST;?>" />
	</p>
</div> 
<div class="block" style="float:left;width:65%;height:550px;">
    <table style="margin:auto;">
        <tr height="20px;">
            <td>
                <input type="hidden" id="mode" value="<?php functions::xecho($_REQUEST['mode']);?>" />
            </td>
        </tr>
        <tr>
            <td width="33%">
                <label for="objectType" ><?php echo _OBJECT_TYPE;?>: </label>
            </td>
            <td>
                <select id="objectType" onChange="listmodel_setObjectType(); listmodel_setObjectId();" style="width:300px;" <?php if($mode == 'up') echo "disabled='true'";?>>
                    <option value="" ><?php echo _SELECT_OBJECT_TYPE;?></option><?php
                    foreach($difflistTypes as $difflistTypeId => $difflistTypeLabel) { ?>
                    <option value="<?php functions::xecho($difflistTypeId);?>" <?php if($objectType == $difflistTypeId) echo "selected='true'";?> ><?php functions::xecho($difflistTypeLabel);?></option><?php
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="objectId" ><?php echo _ID;?> : </label>
            </td>
            <td>
                <div id="objectId_input" ><?php 
                if($mode == 'up') { ?>
                    <input type="text" id="objectId" disabled='true' value="<?php functions::xecho($objectId);?>" />
                <?php 
                } else { ?>
                    <script type="text/javascript">
                        // OnLoad : set object id and label
                        listmodel_setObjectId('<?php functions::xecho($objectId)?>');
                    </script><?php
                } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label for="title" ><?php echo _TITLE;?> : </label>
            </td>
            <td>
                <textarea id="title" style="width:294px;"><?php functions::xecho($title);?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="description"  ><?php echo _DESCRIPTION;?> : </label>
            </td>
            <td>
                <textarea id="description" style="width:294px;"><?php functions::xecho($description);?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="objectType_info" ><?php echo _DIFFLIST_TYPE_ROLES;?> : </label>
            </td>
            <td>
                <span id="objectType_info"><?php echo trim($_SESSION['m_admin']['entity']['difflist_type']->difflist_type_roles);?></span>
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    <p class="buttons" style="text-align:center;"><?php
        if($objectType && $objectId) { ?>
		<input type="button" onclick="listmodel_save();" class="button" value="<?php echo _SAVE_LISTMODEL;?>" /><?php
        } ?>
        <input type="button" onclick="goTo('index.php?module=entities&page=admin_listmodels');" class="button" value="<?php echo _CANCEL;?>" />
	</p>
    </div> 
</div><?php
}
# DEL => REDIRECT TO AJAX SAVE
# *****************************************************************************
if($_REQUEST['mode'] == 'del') {
?>
    <script type="text/javascript">
        listmodel_del(
            '<?php echo $objectType?>',
            '<?php functions::xecho($objectId)?>'
        );
    </script><?php
}

