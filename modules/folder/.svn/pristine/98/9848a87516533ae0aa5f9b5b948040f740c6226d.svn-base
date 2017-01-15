<?php

/*
*   Copyright 2008-2016 Maarch
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
* List Class
*
*  Contains all the function to make list whith results
*
* @package  Maarch PeopleBox 1.0
* @version 1.0
* @since 10/2005
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
* @author  Loïc Vinet  <dev@maarch.org>
* @author  Laurent Giovannoni  <dev@maarch.org>
*
*/


abstract class folders_show_Abstract extends functions
{

	/**
	* Show the tree of folder
	*
	* @param array $result array of the tree folder
	* @param string $link link to the form page
	*/
	public function folder_tree($result,$link)
	{
		$second_level=0;
		$third_level=0;

		echo "<div  >";
		for ($i=0;$i<count($result);$i++)
		{
			foreach(array_keys($result[$i]) as $value)
			{
				if($value == "first_level_id")
				{
					?><img src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=dossiers2.gif" align="middle" alt="" />
				<span class='selected'>
					<?php functions::xecho($result[$i]['first_level_label']);?><br />
					</span>
					<div class='dir_second_level'>
                    <?php
					for ($j=0;$j<count($result[$i]['level2']['second_level_id']);$j++)
					{
						if($_GET['second_level'] == $result[$i]['level2']['second_level_id'][$second_level])
						{
							?><span class="selected">
							<a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=<?php functions::xecho($link);?>"><img src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=dir_open.gif" border="0" align='middle' alt="" /> 		
							<?php functions::xecho($result[$i]['level2']['second_level_label'][$second_level]);?></a><br/>
							</span>
							<div class='dir_third_level'>
							<?php  for($k=0;$k<count($result[$i]['level2'][$second_level]['level3']['type_id']);$k++)
							{
								?>
								<a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=<?php functions::xecho($link);?>&amp;type_id=<?php functions::xecho($result[$i]['level2'][$second_level]['level3']['type_id'][$third_level]);?>&amp;second_level=<?php functions::xecho($result[$i]['level2']['second_level_id'][$second_level]);?>&amp;coll_id=<?php functions::xecho($result[$i]['level2'][$second_level]['level3']['coll_id'][$third_level]); ?>"><img src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=arrow_primary.gif" border="0" align="middle" alt="" /> 
								<?php functions::xecho($result[$i]['level2'][$second_level]['level3']['type_label'][$third_level]);?></a><br/> <?php
								$third_level++;
							}
							$second_level++;
							echo "</div>";
						}
						else
						{
							for($k=0;$k<count($result[$i]['level2'][$second_level]['level3']['type_id']);$k++)
							{
								$third_level++;
							}
							?><a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=<?php functions::xecho($link);?>&amp;second_level=<?php functions::xecho($result[$i]['level2']['second_level_id'][$second_level]);?>"><img src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?module=folder&filename=dir_close.gif" align="top" border="0" alt="" /> <?php functions::xecho($result[$i]['level2']['second_level_label'][$second_level]);?></a><br/><?php
							$second_level++;
						}
					}
					echo "</div>";
				}
			}
		}
		echo "</div>";
	}

	/**
	* construct the folders tree
	*
	*/
	public function construct_tree()
	{
		$sun= new functions();
		$conn = new Database();

		$query="SELECT ".$_SESSION['tablename']['doctypes'].".coll_id, ".$_SESSION['tablename']['doctypes'].".type_id as type_id, ".$_SESSION['tablename']['doctypes'].".description as type_description, ".$_SESSION['tablename']['doctypes'].".doctypes_first_level_id as first_level_id, ".$_SESSION['tablename']['doctypes'].".doctypes_second_level_id as second_level_id,
		".$_SESSION['tablename']['doctypes_first_level'].".doctypes_first_level_label as first_level_label, ".$_SESSION['tablename']['doctypes_second_level'].".doctypes_second_level_label as second_level_label
		FROM ".$_SESSION['tablename']['doctypes']."
		left join ".$_SESSION['tablename']['doctypes_first_level']." on ".$_SESSION['tablename']['doctypes'].".doctypes_first_level_id = ".$_SESSION['tablename']['doctypes_first_level'].".doctypes_first_level_id
		left join ".$_SESSION['tablename']['doctypes_second_level']." on ".$_SESSION['tablename']['doctypes'].".doctypes_second_level_id = ".$_SESSION['tablename']['doctypes_second_level'].".doctypes_second_level_id
		WHERE ".$_SESSION['tablename']['doctypes'].".enabled = 'Y' and ".$_SESSION['tablename']['doctypes_second_level'].".enabled = 'Y' and ".$_SESSION['tablename']['doctypes_first_level'].".enabled = 'Y' order by ".$_SESSION['tablename']['doctypes'].".doctypes_first_level_id, ".$_SESSION['tablename']['doctypes'].".doctypes_second_level_id, ".$_SESSION['tablename']['doctypes'].".description";

		$stmt = $conn->query($query);

		$tab_result = array();
		$tab_tree = array();
		$i=0;
		while ($value = $stmt->fetchObject())
		{
			$tab_result[$i]['type_id'] = $value->type_id;

			$tab_result[$i]['type_description'] = functions::show_string($value->type_description);
			$tab_result[$i]['first_level_id'] = $value->first_level_id;
			$tab_result[$i]['first_level_label'] = functions::show_string($value->first_level_label);
			$tab_result[$i]['second_level_id'] = $value->second_level_id;
			$tab_result[$i]['second_level_label'] = functions::show_string($value->second_level_label);
			$i++;
		}

		$query="SELECT doctypes_first_level_id, doctypes_first_level_label FROM ".$_SESSION['tablename']['doctypes_first_level']." WHERE enabled = 'Y' order by doctypes_first_level_label";
		$stmt = $conn->query($query);
		$i=0;
		while ($value = $stmt->fetchObject())
		{
			$tab_tree[$i]['first_level_id'] = $value->doctypes_first_level_id;
			$tab_tree[$i]['first_level_label'] = functions::show_string($value->doctypes_first_level_label);
			$tab_tree[$i]['level2']=array();
			$i++;
		}
		$j=0;
		$k=0;
		for ($i=0;$i<count($tab_tree);$i++)
		{
			foreach(array_keys($tab_tree[$i]) as $value)
			{
				if($value == "first_level_id")
				{
					$query="SELECT doctypes_second_level_id, doctypes_second_level_label FROM ".$_SESSION['tablename']['doctypes_second_level']." WHERE doctypes_first_level_id = ? and enabled = 'Y' order by doctypes_second_level_label";
					$arrayPDO = array($tab_tree[$i]['first_level_id']);
					$stmt = $conn->query($query, $arrayPDO);
					while ($val = $stmt->fetchObject())
					{
						$tab_tree[$i]['level2']['second_level_id'][$j]=$val->doctypes_second_level_id;
						$tab_tree[$i]['level2']['second_level_label'][$j]=functions::show_string($val->doctypes_second_level_label);
						$tab_tree[$i]['level2'][$j]['level3']=array();
						$query2="SELECT type_id, description, coll_id FROM ".$_SESSION['tablename']['doctypes']." WHERE doctypes_first_level_id = ? and doctypes_second_level_id= ? and enabled = 'Y' order by description";
						$stmt2 = $conn->query($query2, array($tab_tree[$i]['first_level_id'], $tab_tree[$i]['level2']['second_level_id'][$j]));
						while ($val2 = $stmt2->fetchObject())
						{
							$tab_tree[$i]['level2'][$j]['level3']['type_id'][$k]=$val2->type_id;							
							$tab_tree[$i]['level2'][$j]['level3']['coll_id'][$k]=$val2->coll_id;
							$tab_tree[$i]['level2'][$j]['level3']['type_label'][$k]=functions::show_string($val2->description);
							$k++;
						}
						$j++;
					}
				}
			}
		}
		//$this->show_array($tab_tree);
		$this->folder_tree($tab_tree,"view_folder&amp;module=folder");
	}



	/**
	* Show all the folder data (used in the salary sheet and view folder)
	*
	* @param array $folder_array array containing folder data
	* @param string $link link to the form result
	* @param string $path_trombi path to the photo
	*/
	public function view_folder_info_details($folder_array,$link,$path_trombi = '')
	{
		if ($_SESSION['user']['services']['modify_folder'] )
		{
			$up = true;
		 }
		 else
		 {
		 	$up=false;
		 }
		$db = new Database();
		 ?>
         	<form name="view_folder_detail" method='post' action='<?php functions::xecho($link);?>' class="folder_forms" ><?php
		if($up)
		{
		?><input type="hidden" value="up" name="mode" />
		<input type="hidden" value="true" name="folder_index" />
		<?php
		}?>

			<?php
					if($folder_array['complete'] == "Y")
					{
						$complete = _FOLDER.' '.strtolower(_COMPLETE);
					}
					else
					{
						$complete = _FOLDER.' '.strtolower(_INCOMPLETE);
					}
					?><br/>
                    <div align="center">
                    <span>
                    	<label><?php echo _MATRICULE;?> :</label>
                        <input type="text" readonly="readonly" value="<?php functions::xecho($folder_array['folder_id']);?>" class="readonly" />
                        &nbsp;&nbsp;&nbsp;
                          <label><?php echo _FOLDERTYPE;?> :</label>
                        <input type="text" readonly="readonly" value="<?php functions::xecho($folder_array['foldertype_label']);?>" class="readonly" />
                        </span>
                        <span >
                        <br/><small>(
                        <?php functions::xecho($complete);?>)</small>
                        </span>



                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span id="link_right" >
                    	<?php  if($_SESSION['origin'] == "view_folder")
						{?><a href="<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=show_folder&amp;module=folder&amp;field=<?php functions::xecho($_SESSION['current_folder_id']);?>"><img src="<?php echo $_SESSION['config']['businessappurl'];?>static.php?module=folder&filename=s_sheet_c.gif" width="20px" height="25px"
                    	alt="logo"/><?php echo _VIEW_SALARY_SHEET;?></a>
                        <?php  } ?>
                    </span>
                  </div>
                    <br/>


            <table border="0" width="100%">
		<?php
			//$this->show_array( $folder_array);
			for($i=0; $i < count($folder_array['index']);$i++)
			{
				 if ($i%2 != 1 || $i==0) // pair
				 {
   					echo '<tr>'	;
				}
				?>
				<td width="24%" align="left" >
					<span>
						<?php
						if($folder_array['index'][$i]['mandatory'])
						{
							echo "<b>".$folder_array['index'][$i]['label']."</b> : ";
						}
						else
						{
							functions::xecho($folder_array['index'][$i]['label'])." : ";
						}
						?>
                    </span>
                  </td>
                  <td width="25%" align="left">
                    <?php
			if($up==true)
			{

					if((isset($folder_array['index'][$i]['foreign_key']) && !empty($folder_array['index'][$i]['foreign_key']) && isset($folder_array['index'][$i]['foreign_label']) && !empty($folder_array['index'][$i]['foreign_label']) && isset($folder_array['index'][$i]['tablename']) && !empty($folder_array['index'][$i]['tablename'])) || (isset($folder_array['index'][$i]['values']) && count($folder_array['index'][$i]['values']) > 0))
					{
					?>
                    	<select name="<?php functions::xecho($folder_array['index'][$i]['column']);?>" id="<?php functions::xecho($folder_array['index'][$i]['column']);?>">
                        <option value=""><?php echo _CHOOSE;?></option>
                        <?php
						if(isset($folder_array['index'][$i]['values']) && count($folder_array['index'][$i]['values']) > 0)
						{
							for($k=0; $k < count($folder_array['index'][$i]['values']); $k++)
							{
							?>
                            	<option value="<?php functions::xecho($folder_array['index'][$i]['values'][$k]['label']);?>" <?php  if($folder_array['index'][$i]['values'][$k]['label'] == $folder_array['index'][$i]['value']){ echo 'selected="selected"'; } ?>><?php functions::xecho($folder_array['index'][$i]['values'][$k]['label']);?></option>
                            <?php
							}
						}
						else
						{
							$query = "SELECT ".$folder_array['index'][$i]['foreign_key'].", ".$folder_array['index'][$i]['foreign_label']." FROM ".$folder_array['index'][$i]['tablename'];

							$arrayPDO = array();
							if(isset($folder_array['index'][$i]['where']) && !empty($folder_array['index'][$i]['where']))
							{
								$query .= " WHERE ? ";
								$arrayPDO = array_merge($arrayPDO, array($folder_array['index'][$i]['where']));
							}
							if(isset($folder_array['index'][$i]['order']) && !empty($folder_array['index'][$i]['order']))
							{
								$query .= ' '.$folder_array['index'][$i]['order'];
							}

							$stmt = $db->query($query, $arrayPDO);

							while($res = $stmt->fetchObject())
							{
							?>
                            <option value="<?php functions::xecho($res->{$folder_array['index'][$i]['foreign_key']});?>" <?php  if($res->{$folder_array['index'][$i]['foreign_key']} == $folder_array['index'][$i]['value']){ echo 'selected="selected"';}?>><?php functions::xecho($res->{$folder_array['index'][$i]['foreign_label']});?></option>
                            <?php
							}
						}
						?>
                        </select>
                    <?php
					}
					else
					{
						?>
						<input type="text" name="<?php functions::xecho($folder_array['index'][$i]['column']);?>" id="<?php functions::xecho($folder_array['index'][$i]['column']);?>" 
						<?php  if($_SESSION['field_error'][$folder_array['index'][$i]['column']]){?>style="background-color:#FF0000" <?php } 
						if($folder_array['index'][$i]['date'])
						{ echo 'class="medium"'; } ?> value="<?php
						if($folder_array['index'][$i]['date'])
						{
							echo functions::format_date_db($folder_array['index'][$i]['value']);
						}
						else
						{
							functions::xecho($folder_array['index'][$i]['value']);
						}
						?> "
						<?php
						if($folder_array['index'][$i]['date'])
						{
						?>
						onclick="showCalender(this);"
						<?php  }?>
						/>
                    <?php
					}
					if($folder_array['index'][$i]['mandatory'])
					{
						?>
                        <input type="hidden" name="mandatory_<?php functions::xecho($folder_array['index'][$i]['column']);?>" id="mandatory_<?php functions::xecho($folder_array['index'][$i]['column']);?>" value="true" />
                        <?php
					}
			}
			else
			{
				?><input type="text" name="<?php functions::xecho($folder_array['index'][$i]['column']);?>" id="<?php functions::xecho($folder_array['index'][$i]['column']);?>" <?php  if($folder_array['index'][$i]['date'])
						{ echo 'class="medium"'; } ?> value="<?php functions::xecho($folder_array['index'][$i]['value']) ?>" readonly="readonly" class="readonly" /><?php
			}

					?>
				</td>
				<?php
				if ($i%2 == 1 && $i!=0) // impair
				 {
   					echo '</tr>'	;
				}
				else
				{

					if($i+1 == count($folder_array['index']))
					{
						echo '<td width="2" colspan="3">&nbsp;</td></tr>';
					}
					else
					{
						echo  '<td width="2">&nbsp;</td>';
					}
				}
			}
		?></table>
		<div align="right">
		<?php
       	if($up==true)
		{ ?>
			<input type="submit" class="button" value="<?php echo _UPDATE_FOLDER;?>" />
		<?php  }
		//require_once($_SESSION['pathtocoreclass'].'class_core_tools.php');
		$ct = new core_tools();
		$ct->execute_modules_services($_SESSION['modules_services'], "index.php?page=".$_SESSION['origin'], '', 'delete_folder', 'folder');
				?>
         </div>
		</form><?php
	}
}
?>
