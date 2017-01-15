<?php

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
$security = new security();
$core_tools = new core_tools();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header();
require_once("modules".DIRECTORY_SEPARATOR."basket".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");

$bask = new basket();
if(!empty($_REQUEST['id']))
{
	$bask->load_current_basket(trim($_REQUEST['id']), 'frame');
}
$actions_json = $bask->translates_actions_to_json($_SESSION['current_basket']['actions']);
?>
<body>
<script type="text/javascript">

	var arr_actions = <?php echo $actions_json;?>;
	//alert(arr_actions);
	var arr_msg_error = {'confirm_title' : '<?php echo addslashes(_ACTION_CONFIRM);?>',
							'validate' : '<?php echo addslashes(_VALIDATE);?>',
							'cancel' : '<?php echo addslashes(_CANCEL);?>',
							'choose_action' : '<?php echo addslashes(_CHOOSE_ACTION);?>',
							'choose_one_doc' : '<?php echo addslashes(_CHOOSE_ONE_DOC);?>'
						};

	var val = 'none';
	var action_id = '<?php functions::xecho($_SESSION['current_basket']['default_action']);?>';
	var table = '<?php functions::xecho($_SESSION['current_basket']['table']);?>';
	var coll_id = '<?php functions::xecho($_SESSION['current_basket']['coll_id']);?>';
	var module = 'apps';
	var mode = 'page';
	var val_frm = {'values' : val,  'action_id' : action_id, 'table' : table, 'coll_id' : coll_id, 'module' : module}
	//alert(val_frm);
	action_send_first_request('<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&page=manage_action&module=core', mode,  val_frm['action_id'], val_frm['values'], val_frm['table'], val_frm['module'], val_frm['coll_id']);
//	alert('apres_action_send');

</script>
</body>
</html>
