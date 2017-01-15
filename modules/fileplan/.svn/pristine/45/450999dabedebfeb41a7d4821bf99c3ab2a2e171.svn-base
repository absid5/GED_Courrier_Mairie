<?php
/*
*
*    Copyright 2013 Maarch
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
* @brief    Fileplan tree and list of the current user
*
* @file     fileplan.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
$core_tools = new core_tools();
$db    = new Database();
$fileplan   = new fileplan();

$core_tools->test_service('fileplan', 'fileplan');

//Get fileplans
$fileplans_array = $fileplan->getAuthorizedFileplans();

// print_r($fileplans_array);

if (count($fileplans_array) > 0)  {
	
	/****************Management of the location bar  ************/
	$init = false;
	if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
	{
		$init = true;
	}
	$level = "";
	if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
	{
		$level = $_REQUEST['level'];
	}
	$page_path = $_SESSION['config']['businessappurl'].'index.php?page=fileplan&module=fileplan';
	$page_label = _FILEPLAN;
	$page_id = "fileplan";
	$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
	/***********************************************************/

	?>
	<h1><i class="fa fa-files-o fa-2x" title="" /></i>
		<?php echo _FILEPLAN;?></h1>

	<div id="inner_content">
		<div class="block">
			<h2><b>
				<span class="selected_link"><?php echo _VIEW_FILEPLAN;?></span>
				&nbsp;/&nbsp;
				<a href="<?php echo $_SESSION['config']['businessappurl'];
				?>index.php?page=fileplan_managment&module=fileplan&load" class="back">
				<?php echo _MANAGE_PERSONNAL_FILEPLAN;?></a>                
			</b></h2>
		<table width="100%" border="0">
			<tr>
				<td style="width:25%;" valign="top" nowrap>
					<script type="text/javascript" src="<?php 
						echo $_SESSION['config']['businessappurl'];?>tools/tafelTree/js/scriptaculous.js"></script>
					<script type="text/javascript" src="<?php 
						echo $_SESSION['config']['businessappurl'];?>tools/tafelTree/Tree.js"></script>
					<script type="text/javascript">
					<?php
					for($i=0; $i < count($fileplans_array); $i++) {
						?>
						var tree_<?php functions::xecho($fileplans_array[$i]['ID']);?> = null;
						<?php
					}
					?>
					function funcOpen (branch, response) {
						// On peux traiter le retour et retourner true si
						// on veux insérer les enfants, false si on veux pas
						return true;
					}
					
					function TafelTreeInit () {
						<?php
						//Get All avalaible fileplan
						
						for($i=0; $i < count($fileplans_array); $i++) {
							//Fileplan ID & LABEL
							$fileplanId = $fileplans_array[$i]['ID'];
							$fileplanLabel = $fileplans_array[$i]['LABEL'];
							
							//Get Positions for the fileplan
							$level_1 = array();
							
							$stmt = $db->query(
								"select position_id, position_label from "
								. FILEPLAN_VIEW." where fileplan_id = ?"
								. " and parent_id is null"
								. " and position_enabled = ? order by position_label asc"
							,array($fileplans_array[$i]['ID'],'Y'));
						             
							while($res = $stmt->fetchObject())
							{
								array_push(
									$level_1, 
									array(
										'id' => $res->position_id, 
										'tree' => $fileplans_array[$i]['ID'], 
										'key_value' => $res->position_id, 
										'label_value' => functions::show_string($fileplan->truncate($res->position_label), true), 
										'tooltip_value' => functions::show_string($res->position_label, true), 
										'script' => ""
									)
								);
							}
							
							?>
								var struct_<?php functions::xecho($fileplans_array[$i]['ID']);?> = [
											{
											'id':'<?php functions::xecho($fileplans_array[$i]['ID']);?>',
											'txt':'&nbsp;<?php echo empty($fileplans_array[$i]['LABEL'])? _FILEPLAN : $fileplans_array[$i]['LABEL'];?>',
											'items':[
													<?php
													for($ii=0; $ii < count($level_1);$ii++) {
														?>
														{
														'id' : '<?php functions::xecho($fileplans_array[$i]['ID'].'@@'.$level_1[$ii]['id']);?>',
														'title' : '<?php echo addslashes($level_1[$ii]['tooltip_value']);?>',
														'canhavechildren' : true,
														'onclick' : 'view_document_list',
														'txt' : '<?php echo "&nbsp;".addslashes($level_1[$ii]['label_value']);?>',
														'style': 'tree_branch'
														
														},
														<?php
													}
													?>
												
												]
											}
										];
								tree_<?php functions::xecho($fileplanId);?> = new TafelTree('tree_fileplan_<?php functions::xecho($fileplanId);?>', struct_<?php functions::xecho($fileplanId);?>, {
									'generate' : true,
									'imgBase' : '<?php echo $_SESSION['config']['businessappurl'];?>tools/tafelTree/imgs/',
									'defaultImg' : 'folder.gif',
									'defaultImgOpen' : 'folderopen.gif',
									'defaultImgClose' : 'folder.gif',
									"defaultImgOpenSelected" : "position_on.gif",
									"defaultImgCloseSelected" : "position.gif",
									'onOpenPopulate' : [funcOpen, '<?php echo $_SESSION['config']['businessappurl'];
													?>index.php?display=true&module=fileplan&page=positions_tree_childs&fileplan_id=<?php 
													functions::xecho($fileplanId);?>']
								});
							<?php
							}
							?>	
						}
					function view_document_list( branch) {
						var loadedTree = [];
						<?php
						for($i=0; $i < count($fileplans_array); $i++) {
							?>
							loadedTree.push('<?php functions::xecho($fileplans_array[$i]['ID']);?>');
							<?php
						}
						?>
						var id = branch.getId();
						var root = branch.getAncestor();
						
						//Unselect all other trees
						for(var i=0; i < loadedTree.length; i++) {
							if(root.getId() != loadedTree[i]) {
								var treeId = 'tree_'+loadedTree[i];
								window[treeId].unselect();
							}
						}
						// 	
						loadList('<?php echo $_SESSION['config']['businessappurl'];
							?>index.php?display=true&module=fileplan&page=positions_documents_list&id='+id, 'list_doc', true);
					}
					</script>	
					<?php
					for($i=0; $i < count($fileplans_array); $i++) {
						?>
						<div id="tree_fileplan_<?php functions::xecho($fileplans_array[$i]['ID']);?>"></div>
						<hr/>
						<?php
					}
					?>
				</td>
				<td valign="top" style="border-left: 1px solid #CCCCCC;">
					<div id="list_doc"></div>
				</td>
			</tr>
		</table>
		</div>
	</div>
<?php
} else {
	/****************Management of the location bar  ************/
	$init = false;
	if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
	{
		$init = true;
	}
	$level = "";
	if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
	{
		$level = $_REQUEST['level'];
	}
	$page_path = $_SESSION['config']['businessappurl'].'index.php?page=fileplan&module=fileplan';
	$page_label = _ADD_FILEPLAN;
	$page_id = "fileplan_add";
	$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
	/***********************************************************/
	$func = new functions();
	$userInfo = $func->infouser($_SESSION['user']['UserId']);
	
	//Path to ajax script
	$path_to_script = $_SESSION['config']['businessappurl']
		."index.php?display=true&module=fileplan&page=fileplan_ajax_script".$parameters;
	
	?>
	<div id="inner_content">
	<h1><i class="fa fa-files-o" title="<?php
		echo _ADD_FILEPLAN;?>" /></i><?php echo _ADD_FILEPLAN;?></h1>
	<h3> <?php echo _CREATE_YOUR_PERSONNAL_FILEPLAN.".<br/><br/>"._ASKED_ONLY_ONCE.".";?>  </h3>
	<div class="blank_space">&nbsp;</div>
	
	<form name="formFileplan" id="formFileplan" method="post"  action="#">
	<em><?php echo _CHANGE_DEFAULT_FILEPLAN_NAME;?></em><br /><br />
	<p>
		<label ><?php echo _FILEPLAN_NAME;?> : </label>
		<input name="fileplan_label" type="text" id="fileplan_label" class="fileplan_position" value="<?php  
			echo _PERSONNAL_FILEPLAN.' ('.	$userInfo['FirstName'].' '.$userInfo['LastName']
			.')';?>" /><span class="red_asterisk">*</span>
	</p>
	<p>
		<label ><?php echo _IS_SERIAL_ID;?> : </label>
		<input name="is_serial" type="radio" id="is_serial" value="Y" checked="ckecked" /><?php echo _YES;?>
		<input name="is_serial" type="radio" id="is_serial" value="N" /><?php echo _NO;?>
		<span class="red_asterisk">*</span>
	</p>
	<p class="buttons">
		<input type="button" name="valid" value="<?php  
			echo _VALIDATE;?>" class="button" onClick="validFileplanForm('<?php 
		echo $path_to_script.'&origin=manage&mode=saveFileplan';?>', 'formFileplan');" />
        <input type="button" name="cancel" value="<?php echo 
			_CANCEL;?>" class="button" onclick="window.top.location.href='<?php 
			echo $_SESSION['config']['businessappurl'];?>index.php'" />
	</p>
	</form>
	</div>
<?php
} 
