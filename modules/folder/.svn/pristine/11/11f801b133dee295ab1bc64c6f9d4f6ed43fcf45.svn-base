<?php
/**
* File : select_folder.php
*
* Form to choose a folder (used in indexing process)
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 06/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/



require_once("core/class/class_request.php");

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$core_tools = new core_tools();

$core_tools->test_service('physical_archive', 'physical_archive');
$core_tools->test_user();
$core_tools->load_lang();
$func = new functions();
$_SESSION['origin_folder'] = "select_folder";
$_SESSION['select_folder'] = true;
$_SESSION['res_folder'] = "";
$_SESSION['search_res_folder'] = "";
$_SESSION['folderSystemId'] = "";
$_SESSION['stringSearch'] = "";
//if(!empty($_REQUEST['project']) && empty($_REQUEST['market']))
if(!empty($_REQUEST['project']))
{
	$_SESSION['stringSearch'] = $_REQUEST['project'];
	/*if(substr($_REQUEST['project'], strlen($_REQUEST['project']) -1, strlen($_REQUEST['project'])) == ")")
	{
		$_SESSION['folderSystemId'] = str_replace(')', '', substr($_REQUEST['project'], strrpos($_REQUEST['project'],'(')+1));
	}*/
}
/*
if(!empty($_REQUEST['market']))
{
	if(substr($_REQUEST['market'], strlen($_REQUEST['market']) -1, strlen($_REQUEST['market'])) == ")")
	{
		$_SESSION['folderSystemId'] = str_replace(')', '', substr($_REQUEST['market'], strrpos($_REQUEST['market'],'(')+1));
	}
}
*/
if(isset($_REQUEST['matricule'])and !empty($_REQUEST['matricule']))
{
	$_SESSION['res_folder'] = "matricule";
	$_SESSION['search_res_folder'] =$_REQUEST['matricule'];
}
elseif( isset($_REQUEST['nom']) and !empty($_REQUEST['nom']))
{
	$_SESSION['res_folder'] = "nom";
	$_SESSION['search_res_folder'] = $_REQUEST['nom'];
}

$core_tools->load_html();
//here we building the header
$core_tools->load_header(_SELECT_FOLDER_TITLE, true, false);
$time = $core_tools->get_session_time_expire();
if($_SESSION['origin'] == "qualify")
{
	$tab = array();
	$select = array();
	$col ="";
	if(isset($_SESSION['collection_choice']) && !empty($_SESSION['collection_choice']))
	{
		$col = $_SESSION['collection_choice'];
	}
	else
	{
		$col = $_SESSION['collections'][0]['table'];
	}
	if($_SESSION['current_folder_id'] <> "")
	{
		$select[$col] = array();
		array_push($select[$col],"folders_system_id");
		$where = "res_id = ? ".$_SESSION['res_id_to_qualify'];
		$arrayPDO = array($_SESSION['res_id_to_qualify']);
		$request = new request();
		$tab = $request->PDOselect($select, $where, $arrayPDO, "", $_SESSION['config']['databasetype']);
		//print_r($tab);
		for ($i=0;$i<count($tab);$i++)
		{
			for ($j=0;$j<count($tab[$i]);$j++)
			{
				foreach(array_keys($tab[$i][$j]) as $value)
				{
					if($tab[$i][$j][$value]=="folders_system_id")
					{
						$_SESSION['current_folder_id']= $tab[$i][$j]['value'];
					}
				}
			}
		}
	}
}
//echo "<br/>folder ".$_SESSION['current_folder_id'];
require_once("modules".DIRECTORY_SEPARATOR."folder".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
$folder = new folder();
if($_SESSION['current_folder_id'] <> "" && $folder->is_folder_exists($_SESSION['current_folder_id']))
{
	$folder->load_folder($_SESSION['current_folder_id'], $_SESSION['tablename']['fold_folders']);
	$folder_data = $folder->get_folder_info();
	//$func->show_array($folder_data);
}

?>
<body  onload="setTimeout(window.close, <?php echo $time;?>*60*1000);">
<br/>
<br/>

<div class="block">
	<form name="frm1" class="physicalform" action="<?php echo  $_SESSION['config']['businessappurl'].'index.php?display=true&module=indexing_searching&page=file_index';?>">
	
		<b><?php echo _SELECTED_FOLDER;?></b>
		<br/>
		<br/>
		<p>
			<label><?php echo _FOLDERTYPE;?> :</label>
			<input type="text" value="<?php functions::xecho($folder_data['foldertype_label']);?>" name="foldertype" readonly="readonly" class="readonly"/>
		</p>
		<p>
			<label><?php echo _FOLDERID;?> :</label>
			<input type="text" value="<?php functions::xecho($folder_data['folder_name']);?>" name="nom_view" readonly="readonly" class="readonly "/>
		</p>
		<p>
			<label><?php echo _FOLDERNAME;?> :</label>
			<input type="text" value="<?php functions::xecho($folder_data['subject']);?>" name="nom_view" readonly="readonly" class="readonly "/>
		</p>

	</form>
</div>
<div class="block_end">&nbsp;</div>
<div class="blank_space">&nbsp;</div>
<?php
if($_SESSION['origin'] <> "qualify")
{
?>
   <!-- <hr class="select_folder" />-->
   <div class="block">
    <b><?php echo _SEARCH_FOLDER;?></b>
    <br/>
    <br/>
	
    <form name="select_folder" method="get" action="<?php echo $_SESSION['config']['businessappurl'].'index.php';?>" class="physicalform">
    <input type="hidden" name="display" value="true"/>
	<input type="hidden" name="module" value="folder"/>
	<input type="hidden" name="page" value="select_folder"/>
	    <p>
            <label><?php echo _PROJECT." / "._MARKET;?> :</label>
            <input type="text" name="project" id="project"/>
			<!--<div id="show_project" class="autocomplete"></div>-->
        </p>
        <!--<p>
            <label><?php echo _MARKET;?> :</label>
			<input type="text" name="market" id="market"/>
			<div id="show_market" class="autocomplete"></div>
        </p>-->
		<p>
			<label>&nbsp;</label>
			<input type="submit" name="submit2" value="<?php echo _SEARCH_FOLDER;?>" class="button"/>
		</p>
    </form>
	</div><div class="block_end">&nbsp;</div>
    <?php
    //if(isset($_SESSION['folderSystemId'])and !empty($_SESSION['folderSystemId']))
    if(isset($_SESSION['stringSearch'])and !empty($_SESSION['stringSearch']))
    {
		?>
		<div align="center">
			<iframe name="result_folder" src="<?php echo$_SESSION['config']['businessappurl'].'index.php?display=true&module=folder&page=result_folder';?>" frameborder="0" width="98%" height="1000" scrolling="no"></iframe>
		</div>
		<?php
    }
    else
    {
		?>
        <!--<div align="center"><input type="button" name="cancel" value="<?php echo _CLOSE_WINDOW;?>" onclick="self.close();" class="button" /></div>   -->
		<?php
    }
    ?>  <?php
}
$core_tools->load_js();
?>
</body>
</html>
