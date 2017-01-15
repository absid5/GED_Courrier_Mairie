<?php
/*
*    Copyright 2008,2015 Maarch
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
* @brief Modify a structure
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/
require "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR
    . "class_history.php";
$core = new core_tools();
$core->test_admin('admin_architecture', 'apps');
$core->load_lang();

$db = new Database();
$desc = "";
$id = "";
$cssStyle = "default_style";
//$fontColor = '#000000'; // Black by default
$foldertypesArr = array();
$folderModuleLoaded = false;
if ($core->is_module_loaded('folder') == true) {
    $folderModuleLoaded = true;
}
if (isset($_GET['id']) && ! empty($_GET['id'])) {
	$id = $_GET['id'];
	$stmt = $db->query(
		"SELECT doctypes_first_level_label, css_style FROM "
	    . $_SESSION['tablename']['doctypes_first_level']
	    . " WHERE doctypes_first_level_id = ? ", array($id)
	);

	$res = $stmt->fetchObject();
	$desc = $res->doctypes_first_level_label;
	if (isset($res->css_style)) {
        $cssStyle = functions::show_string($res->css_style);
	}
	if ($folderModuleLoaded) {
		$stmt = $db->query(
			'SELECT ffdl.foldertype_id, f.foldertype_label FROM '
		    . $_SESSION['tablename']['fold_foldertypes_doctypes_level1']
		    . " ffdl, " . $_SESSION['tablename']['fold_foldertypes']
		    . " f WHERE ffdl.doctypes_first_level_id = ? "
		    . " and ffdl.foldertype_id = f.foldertype_id",
		    array($id)
		);

		//$_SESSION['m_admin']['loaded_foldertypes']= array();
		while ($res = $stmt->fetchObject()) {
			//array_push($_SESSION['m_admin']['loaded_foldertypes'], $res->foldertype_id);
			array_push($foldertypesArr, $res->foldertype_id);
		}
	}
}

$mode = "";
if (isset($_REQUEST['mode']) && ! empty($_REQUEST['mode'])) {
	$mode = $_REQUEST['mode'];
	$_SESSION['m_admin']['mode'] = $mode;
}

$erreur = "";
if (isset($_REQUEST['valid'])) {
	if (isset($_REQUEST['desc_structure'])
	    && ! empty($_REQUEST['desc_structure'])
	) {
		$desc = $_REQUEST['desc_structure'];
	} else {
		$erreur .= _DESC_STRUCTURE_MISSING . ".";
	}

	if (isset($_REQUEST['css_style']) && !empty($_REQUEST['css_style'])) {
	    $cssStyle = $_REQUEST['css_style'];
	} else {
	    $erreur .= _FONT_COLOR. ' ' . _MISSING . '.';
	}

	if ($folderModuleLoaded) {
		if (! isset($_REQUEST['foldertypes'])
		    || count($_REQUEST['foldertypes']) == 0
		) {
			$erreur .= _FOLDERTYPE_MISSING . ".";
		}
	}

	if (empty($erreur)) {
		$stmt = $db->query(
			"SELECT * FROM ".$_SESSION['tablename']['doctypes_first_level']
		    . " WHERE doctypes_first_level_label = ? and enabled = 'Y'",
		    array($desc)
		);

		if ($stmt->rowCount() > 0 && $mode <> 'up') {
			$erreur .= _THE_STRUCTURE . ' ' . _ALREADY_EXISTS . ".";
		} else {
			if ($mode == "up") {
				if (isset($_REQUEST['ID_structure'])
				    && ! empty($_REQUEST['ID_structure'])
				) {
					$id = $_REQUEST['ID_structure'];
					$db->query(
						"UPDATE " . $_SESSION['tablename']['doctypes_first_level']
					    . " SET doctypes_first_level_label = ?, css_style = ? "
					    . "WHERE doctypes_first_level_id = ? ",
					    array($desc, $cssStyle, $id)
					);
					if ($folderModuleLoaded) {
						$db->query(
							"DELETE FROM "
						    . $_SESSION['tablename']['fold_foldertypes_doctypes_level1']
						    . " WHERE doctypes_first_level_id = ? ", array($id)
						);

						for ($i = 0; $i < count($_REQUEST['foldertypes']); $i ++) {
							$db->query(
								"INSERT INTO "
							    . $_SESSION['tablename']['fold_foldertypes_doctypes_level1']
							    . " VALUES (?, ?)", array($_REQUEST['foldertypes'][$i], $id)
							);
						}
					}
					if ($_SESSION['history']['structureup'] == "true") {
						$hist = new history();
						$hist->add(
						    $_SESSION['tablename']['doctypes_first_level'], $id,
						    "UP", 'structureup', _STRUCTURE_MODIF . " " . strtolower(_NUM)
						    . $id . " (" . $info . ")",
						    $_SESSION['config']['databasetype']
						);
					}
					$_SESSION['info'] .= _STRUCTURE_MODIF . " : " . $id;
				} else {
					$erreur .= _ID_STRUCTURE_PB . ".";
				}
			} else {
				$desc = $_REQUEST['desc_structure'];
				$db->query(
					"INSERT INTO "
				    . $_SESSION['tablename']['doctypes_first_level']
				    . " ( doctypes_first_level_label, css_style) VALUES (?, ?)", array($desc, $cssStyle)
				);
				$stmt = $db->query(
					"SELECT  doctypes_first_level_id FROM "
				    . $_SESSION['tablename']['doctypes_first_level']
				    . " WHERE doctypes_first_level_label = ?", array($desc)
				);
				$res = $stmt->fetchObject();
				$id = $res->doctypes_first_level_id;

				if ($folderModuleLoaded) {
					for ($i = 0; $i < count($_REQUEST['foldertypes']); $i ++) {
						$db->query(
							"INSERT INTO "
						    . $_SESSION['tablename']['fold_foldertypes_doctypes_level1']
						    . " VALUES (?, ?)", array($_REQUEST['foldertypes'][$i], $id)
						);
					}
				}
				if ($_SESSION['history']['structureadd'] == "true") {
					$hist = new history();
					$hist->add(
					    $_SESSION['tablename']['doctypes_first_level'], $id,
					    "ADD", 'structureadd', _NEW_STRUCTURE_ADDED . " (" . $desc . ")",
					    $_SESSION['config']['databasetype']
					);

				}
				$_SESSION['info'] .= _NEW_STRUCTURE . " : " . $desc;
			}
		}
	}
	if (empty($erreur)) {
		unset($_SESSION['m_admin']);
		?>
	   <script type="text/javascript">window.opener.location.reload();self.close();</script>
		<?php
		exit();
	}
}
if (file_exists(
    $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
    . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
    . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
    . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'htmlColors.xml'
)
) {
    $path = $_SESSION['config']['corepath'] . 'custom'
          . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
          . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
          . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
          . DIRECTORY_SEPARATOR . 'htmlColors.xml';
} else {
    $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
          . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'htmlColors.xml';
}
$fontColors = array();

$xml = simplexml_load_file($path);
if ($xml <> false) {
    foreach ($xml->color as $color) {
        array_push(
            $fontColors,
            array(
       	        'id' => (string) $color->id,
                'label' => constant($color->label),
            )
        );
   }
}
array_push(
    $fontColors,
    array(
    	'id' => 'default_style',
        'label' => _DEFAULT_STYLE,
    )
);

function cmpColors($a, $b)
{
    return strcmp(strtolower($a['label']), strtolower($b['label']));
}
usort($fontColors, 'cmpColors');

$core->load_html();

if ($mode == "up") {
	$title = _STRUCTURE_MODIF;
} else {
	$title = _NEW_STRUCTURE_ADDED;
}
$core->load_header($title, true, false);
$time = $core->get_session_time_expire();
?>
<body onload="setTimeout(window.close, <?php echo $time;?>*60*1000);window.resizeTo(700,700);">
<br/>

<?php 
if (!empty($erreur)) { ?>
	<div style="display:table-cell" class="error" onclick="this.hide()" id="divErrorStructure">
		<?php
		functions::xecho($erreur);
		$erreur = "";
		?>
	</div>

<script>
	setTimeout(function() {
	    $('divErrorStructure').hide();
	}, 5000);
</script>

<?php } ?>

<div class="block">
<h2>
&nbsp;<i class="fa fa-folder fa-2x"></i> <?php
if ($mode == "up") {
    echo _STRUCTURE_MODIF;
} else {
    echo _NEW_STRUCTURE_ADDED;
}
?></h2>
<form method="post" name="frmstructure" id="frmstructure" class="forms" action="<?php
	echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=structure_up">
	<input type="hidden" name="display" value="true" />
    <input type="hidden" name="page" value="structure_up" />
	<?php
	if ($mode == "up") {
	?>
		<p>
	    	<label><?php echo _ID . ' ' . _STRUCTURE;?> :</label>
			<input type="text" class="readonly" name="ID_structure" value="<?php functions::xecho($id);?>" readonly="readonly" />
	    </p>
	    <p>&nbsp;</p>
		<?php
	} ?>

	<p>
    	<label><?php echo _DESC . ' ' . _STRUCTURE;?> :</label>
	   	<input type="text"  name="desc_structure" value="<?php functions::xecho($desc);?>" />
    </p>
    <p>&nbsp;</p>
    
    <p>
    	<label><?php echo _CSS_STYLE;?> :</label>
	   <!-- <input type="text"  name="css_style" id="css_style" value="<?php functions::xecho($cssStyle);?>" /> -->
        <select name="css_style" id="css_style">
            <option value=""><?php echo _CHOOSE_STYLE;?></option>
            <?php
				for ($i = 0; $i < count($fontColors); $i ++) {
				    echo '<option value="' . functions::xssafe($fontColors[$i]['id']) . '" ';
				    if ($fontColors[$i]['id'] == $cssStyle) {
				        echo ' selected="selected" ';
				    }
				    echo   ' class="' . functions::xssafe($fontColors[$i]['id']) . '">' 
				    	. functions::xssafe($fontColors[$i]['label']) . '</option>';
				}
            ?>
        </select>
    </p>
    <br/>

<?php
if ($folderModuleLoaded) {
    ?>
     <div>
		<table align="left" border="0" width="100%">
		<tr>
			<td valign="top" width="48%"><b class="tit"><?php
    echo _FOLDERTYPES_LIST;
    ?></b></td>
			<td width="5%" >&nbsp;</td>
			<td valign="top" width="47%"><b class="tit"><?php
	echo _SELECTED_FOLDERTYPES;
	?></b></td>
		</tr>

		<tr>
		 <td width="45%" align="center" valign="top">
			<select name="foldertypeslist[]" id="foldertypeslist" class="multiple_list" ondblclick="moveclick($('foldertypeslist'),$('foldertypes'));" multiple="multiple">
			<?php
				for ($i = 0; $i < count($_SESSION['m_admin']['foldertypes']); $i ++) {
				    $foldertypesState = false;

					for ($j = 0; $j < count($foldertypesArr); $j ++) {
						if (trim($_SESSION['m_admin']['foldertypes'][$i]['id']) == trim(
						    $foldertypesArr[$j]
						)
						) {
							$foldertypesState = true;
						}
					}
					if ($foldertypesState == false) {
						?>
						<option value="<?php
						functions::xecho($_SESSION['m_admin']['foldertypes'][$i]['id']);
						?>" alt="<?php
						functions::xecho($_SESSION['m_admin']['foldertypes'][$i]['label']);
						?>" title="<?php
						functions::xecho($_SESSION['m_admin']['foldertypes'][$i]['label']);
						?>"><?php
						functions::xecho($_SESSION['m_admin']['foldertypes'][$i]['label']);
						?></option>
						<?php
					}
				}
		?>
   		</select>
	<br/><br/>
	<a href="javascript:selectall($('foldertypeslist'));" class="choice"><?php
	    echo _SELECT_ALL;?></a></td>
    <td width="10%" align="center">
	<input type="button" class="button" value="<?php
	echo _ADD;
	?> &gt;&gt;" onclick="Move($('foldertypeslist'),$('foldertypes'));" align="middle"/>
	<br />
	<br />
	<input type="button" class="button"  value="&lt;&lt; <?php
	echo _REMOVE;
	?>" onclick="Move($('foldertypes'),$('foldertypeslist'));" align="middle"/>
	</td>
    <td width="45%" align="center" valign="top">
	<select name="foldertypes[]" id="foldertypes" class="multiple_list" ondblclick="moveclick($('foldertypes'),$('foldertypeslist'));" multiple="multiple" >
		<?php
	for ($i = 0;$i < count($_SESSION['m_admin']['foldertypes']); $i ++) {
		$foldertypesState = false;
		for ($j = 0; $j < count($foldertypesArr); $j ++) {
			if (trim($_SESSION['m_admin']['foldertypes'][$i]['id']) == trim(
			    $foldertypesArr[$j]
			)
			) {
				$foldertypesState = true;
			}
		}

		if ($foldertypesState == true) {
			?>
			<option value="<?php
			echo $_SESSION['m_admin']['foldertypes'][$i]['id'];
			?>" ><?php
			echo $_SESSION['m_admin']['foldertypes'][$i]['label'];
			?></option>
			<?php
		}
	}
		?>
    </select>
	<br/><br/>
	<a href="javascript:selectall($('foldertypes'));" class="choice">
	<?php echo _SELECT_ALL;?></a></td>
	</tr>
	<tr> <td height="10">&nbsp;</td></tr>
		</table>
    </div>
    <?php
}
?>
<p class="buttons">
	<input type="submit" name="valid" class="button" value="<?php
echo _VALIDATE;
?>" onclick="selectall($('foldertypes'));"/>
	<input type="button" class="button"  name="cancel" value="<?php
echo _CANCEL;
?>" onclick="self.close();" />
<br/><br/>
<input type="hidden" name="mode" value="<?php echo $mode;?>"/>
</form>
</div>
<div class="block_end">&nbsp;</div>
<?php $core->load_js();?>
</body>
</html>
