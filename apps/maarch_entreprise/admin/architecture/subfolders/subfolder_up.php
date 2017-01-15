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
* @brief Modify a subfolder
*
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/
require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR 
	. "class_history.php";
$core = new core_tools();
$core->test_admin('admin_architecture', 'apps');
$core->load_lang();

$db = new Database();
$desc = "";
$id = "";
$structureId = "";
$cssStyle = "default_style";
//$fontColor = '#000000'; // Black by default
if (isset($_GET['id']) && ! empty($_GET['id'])) {
	$id = $_GET['id'];
	$stmt= $db->query(
		"SELECT doctypes_second_level_label, doctypes_first_level_id, css_style FROM "
		. $_SESSION['tablename']['doctypes_second_level']
		. " WHERE doctypes_second_level_id = ? ", array($id)
	);

	$res = $stmt->fetchObject();
	$desc = $res->doctypes_second_level_label;
	if (isset($res->css_style)) {
        $cssStyle = functions::show_string($res->css_style);
	}
	$structureId = $res->doctypes_first_level_id;
}

$mode = "";
if (isset($_REQUEST['mode']) && ! empty($_REQUEST['mode'])) {
	$mode = $_REQUEST['mode'];
}
$erreur = "";
if (isset($_REQUEST['valid'])) {
	if (isset($_REQUEST['css_style']) && !empty($_REQUEST['css_style'])) {
	    $cssStyle = $_REQUEST['css_style'];

		if (isset($_REQUEST['desc_sd']) && ! empty($_REQUEST['desc_sd'])) {
			$desc = $_REQUEST['desc_sd'];
			$stmt= $db->query(
				"SELECT * FROM " . $_SESSION['tablename']['doctypes_second_level']
				. " WHERE doctypes_second_level_label = ? and enabled = 'Y'", array($desc)
			);
			//$db->show();
			if ($stmt->rowCount() < 2) {
				if (isset($_REQUEST['structure']) 
					&& ! empty($_REQUEST['structure'])
				) {
					$structure = $_REQUEST['structure'];
					if ($mode == "up") {
						if (isset($_REQUEST['ID_sd']) 
							&& ! empty($_REQUEST['ID_sd'])
						) {
							$id = $_REQUEST['ID_sd'];
							$db->query(
								"UPDATE " 
								. $_SESSION['tablename']['doctypes_second_level']
								. " SET doctypes_second_level_label = ?, doctypes_first_level_id = ? "
								. ", css_style = ? "
								. " WHERE doctypes_second_level_id = ?", array($desc, $structure, $cssStyle, $id)
							);
							$db->query(
								"UPDATE " . $_SESSION['tablename']['doctypes']
								. " SET doctypes_first_level_id = ? "
								. " WHERE doctypes_second_level_id = ?",
								array($structure, $id)
							);
							if ($_SESSION['history']['subfolderup'] == "true") {
								$hist = new history();
								$hist->add(
									$_SESSION['tablename']['doctypes_second_level'],
									$id, "UP", 'subfolderup', _SUBFOLDER_MODIF . " " 
									. strtolower(_NUM) . $id . " (" . $info . ")", 
									$_SESSION['config']['databasetype']
								);
							}
							$_SESSION['info'] .= _SUBFOLDER_MODIF . " : " . $id;
						} else {
							$erreur .= _SUBFOLDER_ID_PB . ".";
						}
					} else {
						$desc = $_REQUEST['desc_sd'];
						$db->query(
							"INSERT INTO "
							. $_SESSION['tablename']['doctypes_second_level']
							. " ( css_style, doctypes_second_level_label, "
							. "doctypes_first_level_id) VALUES ( ?, ?, ?)",
							array($cssStyle, $desc, $structure)
						);
						$stmt = $db->query(
							"SELECT doctypes_first_level_id FROM "
							. $_SESSION['tablename']['doctypes_second_level']
							. " WHERE doctypes_second_level_label = ? and doctypes_first_level_id= ?",
							array($desc, $structure)
						);
						$res = $stmt->fetchObject();
						if ($_SESSION['history']['subfolderadd'] == "true") {
							$hist = new history();
							$hist->add(
								$_SESSION['tablename']['doctypes_second_level'], 
								$res->doctypes_first_level_id, "ADD",'subfolderadd',
								_SUBFOLDER_ADDED . " (" . $desc . ")", 
								$_SESSION['config']['databasetype']
							);
						}
						$_SESSION['info'] .= _NEW_SUBFOLDER . " : " . $desc;
					}
					if (empty($erreur)) {
						unset($_SESSION['m_admin']);
						?>
						<script type="text/javascript">window.opener.location.reload();self.close();</script>
						<?php
					}
				} else {
					$erreur .= _STRUCTURE_MANDATORY . '.';
				}
			} else {
				$erreur .= _THE_SUBFOLDER . " " . _ALREADY_EXISTS;
			} 
		} else {
			$erreur .= _SUBFOLDER_DESC_MISSING . ".";
		}
	} else {
	    $erreur .= _FONT_COLOR. ' ' . _MISSING . '.';
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

$core->load_html('', true, false);

if ($mode == "up") {
	$title = _SUBFOLDER_MODIF;
} else {
	$title = _SUBFOLDER_CREATION;
}
$core->load_header($title);
$time = $core->get_session_time_expire();
?>
<body onload="setTimeout(window.close, <?php echo $time;?>*60*1000);">

<?php 
if (!empty($erreur)) { ?>
	<div style="display:table-cell" class="error" onclick="this.hide()" id="divErrorSubFolder">
		<?php
		functions::xecho($erreur);
		$erreur = "";
		?>
	</div>

<script>
	setTimeout(function() {
	    $('divErrorSubFolder').hide();
	}, 5000);
</script>

<?php } ?>
<div class="block">
<h2> &nbsp;<i class="fa fa-folder-open fa-2x"></i> <?php  
if ($mode == "up") { 
	echo _SUBFOLDER_MODIF;
} else { 
	echo _SUBFOLDER_CREATION;
}
?></h2>
<form method="post" name="modif" id="modif" class="forms" action="<?php 
echo $_SESSION['config']['businessappurl'];
?>index.php?display=true&page=subfolder_up">
	<input type="hidden" name="display" value="true" />
    <input type="hidden" name="page" value="subfolder_up" />
	<?php  
if ($mode == "up") { 
	?>
	<p>
    	<label><?php echo _ID.' '._SUBFOLDER;?>	</label>
		<input type="text" name="ID_sd"  value="<?php functions::xecho($id);?>" readonly="readonly" class="readonly" />
	</p>
    <p>&nbsp;</p>
	<?php  
} 
?>
	<p>
    	<label><?php echo _DESC.' '._SUBFOLDER;?></label>
		<input type="text" name="desc_sd" value="<?php functions::xecho($desc);?>" />
	</p>
    <p>&nbsp;</p>
    <p>
    	<label><?php echo _CSS_STYLE;?></label>
		<!-- <input type="text" name="css_style" id="css_style" value="<?php functions::xecho($cssStyle);?>" /> -->
        <select name="css_style" id="css_style">
            <option value=""><?php echo _CHOOSE_STYLE;?></option>
            <?php
				for ($i = 0; $i < count($fontColors); $i ++) {
				    echo '<option value="' . functions::xssafe($fontColors[$i]['id']) . '" ';
				    if ($fontColors[$i]['id'] == $cssStyle) {
				        echo ' selected="selected" ';
				    }
				    echo   ' class="' . functions::xssafe($fontColors[$i]['id']) 
				    	. '">' . functions::xssafe($fontColors[$i]['label']) . '</option>';
				}
            ?>
        </select>
	</p>
    <p>&nbsp;</p>
	<p>
		<label><?php echo _ATTACH_STRUCTURE;?></label>
		<select name="structure" >
			<option value=""><?php echo _CHOOSE_STRUCTURE;?></option>
			<?php 	
				for ($i = 0; $i < count($_SESSION['m_admin']['structures']); $i ++) {
					?>
					<option value="<?php  
					functions::xecho($_SESSION['m_admin']['structures'][$i]['ID']);
					?>" <?php  
					if ($structureId == $_SESSION['m_admin']['structures'][$i]['ID']) { 
						echo 'selected="selected"'; 
					}
					?>><?php  
					functions::xecho($_SESSION['m_admin']['structures'][$i]['LABEL']);
					?></option>
					<?php
				}
				?>
		</select>
	</p>
	<br/>
	<p class="buttons">
    	<input type="submit" class="button" name="valid" value="<?php  
echo _VALIDATE;
?>" />
        <input type="button" class="button" name="cancel" value="<?php  
echo _CANCEL;
?>" onclick="self.close();" />
    </p>
<input type="hidden" name="mode" value="<?php echo $mode;?>"/>
</form>
</div>
<div class="block_end">&nbsp;</div>
<?php $core->load_js();?>
</body>
</html>