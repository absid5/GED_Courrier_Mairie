<?php
/**
* File : filling_res.php
*
* Frame to show the indexed files in a folder (show_folder.php)
*
* @package  Maarch PeopleBox 1.0
* @version 2.0
* @since 06/2006
* @license GPL
* @author  Lo�c Vinet  <dev@maarch.org>
* @author  Laurent Giovannoni  <dev@maarch.org>
*/

require_once "core/class/class_request.php";
require_once "core/class/class_security.php";
require_once "modules/folder/class/class_modules_tools.php";
require_once "apps/" . $_SESSION['config']['app_id'] 
	. "/class/class_list_show.php";

$core = new core_tools();
$core->load_lang();
$security = new security();
$func = new functions();
$tableName = $security->retrieve_table_from_coll(
	$_SESSION['current_foldertype_coll_id']
);
$view = $security->retrieve_view_from_coll_id(
	$_SESSION['current_foldertype_coll_id']
);

$core->load_html();
//here we building the header
$core->load_header('', true, false);
?>
<body id="filling_res_frame">
<h2><?php echo _ARCHIVED_DOC;?></h2><br/>

<?php
$_SESSION['array_struct_final'] = array();
$db = new Database();

$arrayStruct = array();

$list = new list_show();
foreach (array_keys($_SESSION['user']['security']) as $collId) {
    if ($collId == $_SESSION['current_foldertype_coll_id']) {
		$collIdTest = $collId;
		$whereClause = $_SESSION['user']['security'][$collId]['DOC']['where'];
		break;
	}
}

if (empty($collIdTest)) {
	echo _NO_COLLECTION_ACCESS_FOR_THIS_USER;
} else {
	if ($whereClause <> "") {
		$whereClause = " and (".$whereClause.")";
	}
	$stmt = $db->query(
		"SELECT DISTINCT doctypes_first_level_id, doctypes_first_level_label, "
		. "doctype_first_level_style, doctypes_second_level_id, "
		. "doctypes_second_level_label, doctype_second_level_style, type_id, "
		. "type_label, res_id, subject FROM  " . $view
		. " WHERE folders_system_id = '" . $_SESSION['current_folder_id']
		. "' and type_id <> 0 and doctypes_first_level_id <> 0 "
		. "and doctypes_second_level_id <> 0 and status<>'DEL' " . $whereClause
		. " order by doctypes_first_level_label, doctypes_second_level_label, "
		. "type_label, res_id "
	);

	$countDoc = 0;
	while ($res = $stmt->fetchObject()) {
	    if (isset($res->doctype_first_level_style)) {
	        $level1Style = $func->show_string($res->doctype_first_level_style);
	    } else {
	        $level1Style = 'default_style';
	    }
	    if (isset($res->doctype_second_level_style)) {
	        $level2Style = $func->show_string($res->doctype_second_level_style);
	    } else {
	        $level2Style = 'default_style';
	    }
	    if (! isset($arrayStruct['level1'][$res->doctypes_first_level_id])) {
	    	$arrayStruct['level1'][$res->doctypes_first_level_id] = array(
	    		'label' => $func->show_string($res->doctypes_first_level_label),
	    		'style' => $level1Style,
	    		'level2' => array(),
	    	);
	    }
	    if (!isset(
	    	$arrayStruct['level1'][$res->doctypes_first_level_id]['level2'][$res->doctypes_second_level_id]
	    )
	    ) {
	    	$arrayStruct['level1'][$res->doctypes_first_level_id]['level2'][$res->doctypes_second_level_id] = array(
	    		'label' => $func->show_string($res->doctypes_second_level_label),
	    		'style' => $level2Style,
	    		'level3' => array(),
	    	);
	    }
		if (!isset(
	    	$arrayStruct['level1'][$res->doctypes_first_level_id]['level2'][$res->doctypes_second_level_id]['level3'][$res->type_id]
	    )
	    ) {
	    	$arrayStruct['level1'][$res->doctypes_first_level_id]['level2'][$res->doctypes_second_level_id]['level3'][$res->type_id] = array(
	    		'label' => $func->show_string($res->type_label),
	    		'level4' => array(),
	    	);
	    }
		if (!isset(
	    	$arrayStruct['level1'][$res->doctypes_first_level_id]['level2'][$res->doctypes_second_level_id]['level3'][$res->type_id]['level4'][$res->res_id]
	    )
	    ) {
	    	$arrayStruct['level1'][$res->doctypes_first_level_id]['level2'][$res->doctypes_second_level_id]['level3'][$res->type_id]['level4'][$res->res_id] = array(
	    		'id' => $func->show_string($res->res_id),
	    		'label' => $func->show_string($res->subject)
	    	);
	    }
		$countDoc++;
	}
	if ($countDoc > 0) {
		$_SESSION['where_list_doc'] = "";
		?>
		<script type="text/javascript">
			function view_doc(id)
			{
				var eleframe1 = window.top.document.getElementById('view_doc');
				eleframe1.src = '<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=folder&page=list_doc&listid='+id;
				eleframe1.width = '100%'; 
			}
		</script>
		<div align="left">
			<?php
			
			foreach (array_keys($arrayStruct['level1']) as $level1) {
				$res_id_list = "";
				foreach (array_keys(
					$arrayStruct['level1'][$level1]['level2']
				) as $level2
				) {
					if (isset(
						$arrayStruct['level1'][$level1]['level2'][$level2]['level3']
					)
					) {
						foreach (array_keys(
							$arrayStruct['level1'][$level1]['level2'][$level2]['level3']
							) as $level3
						) {
							foreach (array_keys(
								$arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4']
							) as $level4
							) {
								$res_id_list .= $arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4'][$level4]['id'].",";

							}
						}
					}
				}
				?>
				<div onclick="change2(<?php functions::xecho($level1);?>)" id="h2<?php functions::xecho($level1);?>" class="categorie">
					<?php
					echo "<a href=javascript:view_doc('" . $res_id_list . "');>"
					?><img src="<?php
					echo $_SESSION['config']['businessappurl']
					?>static.php?filename=folderopen.gif" alt="" />&nbsp;<b class="<?php
					echo $arrayStruct['level1'][$level1]['style'];
					?>"><?php functions::xecho($arrayStruct['level1'][$level1]['label']);?></b></a>
					<span class="lb1-details">&nbsp;</span>
				</div>
				<br>
				<div class="desc" id="desc<?php functions::xecho($level1);?>" >
					<div class="ref-unit">
						<?php
						$res_id_list = "";
						foreach (array_keys($arrayStruct['level1'][$level1]['level2']) as $level2)
						{
							$res_id_list = "";
							if (isset($arrayStruct['level1'][$level1]['level2'][$level2]['level3'])) {
								foreach(array_keys($arrayStruct['level1'][$level1]['level2'][$level2]['level3']) as $level3)
								{
									foreach(array_keys($arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4']) as $level4)
									{
										$res_id_list .= $arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4'][$level4]['id'].",";

									}
								}
							}
							?>
							<div onclick="change2(<?php functions::xecho($level2);?>)" id="h2<?php functions::xecho($level2);?>" class="categorie">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
								echo "<a href=javascript:view_doc('".$res_id_list."');>"
								?><img src="<?php
								echo $_SESSION['config']['businessappurl'];
								?>static.php?filename=folderopen.gif" alt="" />&nbsp;<b class="<?php
								if (isset($arrayStruct['level1'][$level1]['level2'][$level2]['style'])) {
								    echo $arrayStruct['level1'][$level1]['level2'][$level2]['style'];
								} else {
								    echo 'default_style';
								}
								?>"><?php functions::xecho($arrayStruct['level1'][$level1]['level2'][$level2]['label']);?></b></a>
								<span class="lb1-details">&nbsp;</span>
							</div>
							<br>
							<div class="desc" id="desc<?php functions::xecho($level2);?>" >
								<div class="ref-unit">
									<?php
									$res_id_list = "";
									//if (isset($arrayStruct['level1'][$level1]['level2'][$level2]['level3'])) {
										foreach(array_keys($arrayStruct['level1'][$level1]['level2'][$level2]['level3']) as $level3)
										{
											$res_id_list = "";
											foreach(array_keys($arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4']) as $level4)
											{
												$res_id_list = $arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4'][$level4]['id'].",";
												echo 	"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<a href=javascript:view_doc('".$res_id_list."');><img src='".$_SESSION['config']['businessappurl']
                                                    ."static.php?module=folder&filename=page.gif' alt='' />"
                                                    .$arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['label']
                                                    ." - ".$arrayStruct['level1'][$level1]['level2'][$level2]['level3'][$level3]['level4'][$level4]['label']
                                                    ."</a><br>";
											}
											?>
											</div>
										</div>
										<?php
										}
									
									?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	<?php
	}
	else
	{
		echo _FOLDERHASNODOC.".<br>";
	}
}
$core->load_js();
?>
</body>
</html>
