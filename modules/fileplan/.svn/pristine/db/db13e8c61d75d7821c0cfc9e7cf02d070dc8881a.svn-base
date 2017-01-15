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
* @brief    Admin fileplans
*
* @file     admin.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
	.DIRECTORY_SEPARATOR ."class" . DIRECTORY_SEPARATOR . "class_lists.php";
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
$core_tools = new core_tools();
$request    = new request();
$func   	= new functions();
$list   	= new lists();

$core_tools->test_service('admin_fileplan', 'fileplan');

if ($core_tools->is_module_loaded('entities') === false) {
	?>
    <script type="text/javascript">
		alert('Module entities not loaded. You must load entities module before create fileplan!');
		window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=admin&reinit=true';
	</script>
	<?php
	exit();
}

if (isset($_REQUEST['load'])) {

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
	$page_path = $_SESSION['config']['businessappurl'].'index.php?page=fileplan_admin&module=fileplan&load';
	$page_label = _ADMIN_MODULE_FILEPLAN;
	$page_id = "fileplan_admin";
	$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
	/***********************************************************/

	?>
	<h1><i class="fa fa-files-o fa-2x" title="" /></i>
		<?php echo _ADMIN_MODULE_FILEPLAN;?></h1>
	<div id="inner_content">
	<?php
			$parameters = '';
			if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) $parameters .= '&order='.$_REQUEST['order'];
			if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters .= '&order_field='.$_REQUEST['order_field'];
			if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
			if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];

			//Load list
			$target = $_SESSION['config']['businessappurl'].'index.php?page=fileplan_admin&module=fileplan'.$parameters;
			$listContent = $list->loadList($target, true, 'divList', 'false');
			echo $listContent;
			
			//Reset error
			$_SESSION['error'] = "";
			?>
		</div>
	<?php
	} else {
		//Table
		$table = FILEPLAN_TABLE;
		$select[$table]= array(); 
		
	//Fields
		array_push($select[$table], 
				"fileplan_id",
				"fileplan_label",
				"enabled", 
				"user_id", 
				"entity_id"
				);
	//Where clause
		$where_tab = array();
		//
		// $where_tab[] = "(user_id is null)";
		//Filtre alphabetique et champ de recherche
		$what = $list->getWhatSearch();
		if (!empty($what)) {
			$where_tab[] = "(lower(fileplan_label) like lower(?))";
			//array
			$array_what = array();
			$array_what[] = $what.'%';
		}



		//Build where
		$where = implode(' and ', $where_tab);
		// if (!empty($where)) $where = ' where '.$where;
		
	//Order
		$order = $order_field = '';
		$order = $list->getOrder();
		$order_field = $list->getOrderField();
		if (!empty($order_field) && !empty($order)) 
			$orderstr = "order by ".$order_field." ".$order;
		else  {
			$list->setOrder('asc');
			$list->setOrderField('fileplan_label');
			$orderstr = "order by fileplan_label asc";
		}

	//get start
			$start = $list->getStart();
			
	//URL extra Parameters  
		$parameters = '';
		$start = $list->getStart();
		if (!empty($order_field) && !empty($order)) $parameters .= '&order='.$order.'&order_field='.$order_field;
		if (!empty($what)) $parameters .= '&what='.$what;
		if (!empty($start)) $parameters .= '&start='.$start;
			
	//Request
		$tab=$request->PDOselect($select,$where,$array_what,$orderstr,$_SESSION['config']['databasetype']);
		
	//Result array    
		for ($i=0;$i<count($tab);$i++)
		{
			for ($j=0;$j<count($tab[$i]);$j++)
			{
				foreach(array_keys($tab[$i][$j]) as $value)
				{
					if($tab[$i][$j][$value]=="fileplan_id")
					{
						$id = $tab[$i][$j]['value'];
						$tab[$i][$j]["fileplan_id"]=$tab[$i][$j]['value'];
						$tab[$i][$j]["label"]= _ID;
						$tab[$i][$j]["size"]="5";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "fileplan_id";
					}

					if($tab[$i][$j][$value]=="fileplan_label")
					{
						$tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
						$tab[$i][$j]["label"]=_FILEPLAN_NAME;
						$tab[$i][$j]["size"]="45";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "fileplan_label";
					}
					
					if($tab[$i][$j][$value]=="user_id")
					{
						$userArray = $func->infouser($tab[$i][$j]['value']);
						$tab[$i][$j]['value']= (!empty($userArray['FirstName']))? $userArray['FirstName'].' '.$userArray['LastName'] : '';
						$tab[$i][$j]["label"]=_USER;
						$tab[$i][$j]["size"]="10";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "user_id";
					}
					if($tab[$i][$j][$value]=="entity_id")
					{
						$tab[$i][$j]["label"]=_ENTITY;
						$tab[$i][$j]["size"]="10";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "entity_id";
					}

					if($tab[$i][$j][$value]=="enabled")
					{
						$tab[$i][$j]["label"]=_ENABLED;
						$tab[$i][$j]["size"]="1";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=false;
						$tab[$i][$j]["order"]='enabled';
					}
				}
			}
		}

		//List parameters
		$paramsTab = array();
		$paramsTab['bool_modeReturn'] = false;                                              //Desactivation du mode return (vs echo)
		$paramsTab['bool_bigPageTitle'] = false;
		$paramsTab['pageTitle'] =  count($tab).' '._FILEPLAN_SHORT;							//Titre de la page
		$paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
		$paramsTab['bool_showSearchTools'] = true;                                          //Affiche le filtre alphabetique et le champ de recherche
		$paramsTab['searchBoxAutoCompletionUrl'] = $_SESSION['config']['businessappurl']
			."index.php?display=true&module=fileplan&page="
			."fileplan_list_autocompletion";   												//Script pour l'autocompletion
		$paramsTab['searchBoxAutoCompletionMinChars'] = 2;                                  //Nombre minimum de caractere pour activer l'autocompletion (1 par defaut)
		$paramsTab['linesToShow'] = 15;                                                     //Nombre de lignes a afficher (parametre de config.xml par defaut)
		$paramsTab['bool_showAddButton'] = true;                                            //Affichage du bouton Nouveau
		$paramsTab['addButtonLabel'] = _ADD_FILEPLAN;                                       //Libellé du bouton Nouveau
		$paramsTab['addButtonScript'] = "showFileplanForm('"
			.$_SESSION['config']['businessappurl']  
			. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
			. "&mode=addFileplan".$parameters."')";											//Action sur le bouton nouveau

		//Action icons array
		$paramsTab['actionIcons'] = array();
			$disable = array(
					"type" => "switch", 
					"on" => array(
							   "script"             =>  "execFileplanScript('".$_SESSION['config']['businessappurl']
														. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
														. "&origin=admin&mode=enaFileplan&fileplan_id=@@fileplan_id@@"
														. $parameters."')",
								"class"             =>  'authorize',
								"tooltip"           =>  _ENABLE_FILEPLAN,
								"label"             =>  _ENABLE_FILEPLAN
								),            
					"off" => array(
							   "script"             =>  "execFileplanScript('".$_SESSION['config']['businessappurl']
														. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
														. "&origin=admin&mode=disFileplan&fileplan_id=@@fileplan_id@@"
														. $parameters."')",
								"class"             =>  'suspend',
								"tooltip"           =>  _DISABLE_FILEPLAN,
								"label"             =>  _DISABLE_FILEPLAN
								),
					"switchRules" => "@@enabled@@ == 'N'",
					"disabledRules" =>  "@@user_id@@ <> ''"
					);
					
			$update = array(
					"script"        => "showFileplanForm('".$_SESSION['config']['businessappurl']
											. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
											. "&mode=upFileplan&origin=admin&fileplan_id=@@fileplan_id@@"
											. $parameters."')",
					"class"         =>  'change',
					"label"         =>  _EDIT_FILEPLAN_SHORT,
					"tooltip"       =>  _EDIT_FILEPLAN_SHORT,
					"disabledRules" =>  "@@enabled@@ == 'N' || @@user_id@@ <> ''"
					);
			
			$view = array(
					"script"        => "window.top.location.href='".$_SESSION['config']['businessappurl']
											. "index.php?module=fileplan&page=fileplan_admin_positions"
											. "&fileplan_id=@@fileplan_id@@"
											. $parameters."';",
					"class"      =>  "view",
					"label"   	=>  _MANAGE_FILEPLAN_SHORT,
					"tooltip"   =>  _MANAGE_FILEPLAN_SHORT,
					"disabledRules" =>  "@@user_id@@ <> ''"
					);
								
			$delete = array(
					"script"        => "showFileplanForm('".$_SESSION['config']['businessappurl']
											. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
											. "&mode=delFileplan&origin=admin&fileplan_id=@@fileplan_id@@"
											. $parameters."', false, '500px', '350px')",
					"class"         =>  'delete',
					"label"         =>  _DELETE_FILEPLAN_SHORT,
					"tooltip"       =>  _DELETE_FILEPLAN_SHORT
					);
			array_push($paramsTab['actionIcons'], $disable);          
			array_push($paramsTab['actionIcons'], $update);
			array_push($paramsTab['actionIcons'], $view); 			
			array_push($paramsTab['actionIcons'], $delete);
		
		//Afficher la liste
		echo '<br/>';
		$list->showList($tab, $paramsTab, 'position_id');
	}
