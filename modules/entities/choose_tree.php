<?php

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$_SESSION['entities_chosen_tree'] = array();
if(isset($_REQUEST['tree_id']) && !empty($_REQUEST['tree_id']))
{
	$_SESSION['entities_chosen_tree'] = $_REQUEST['tree_id'];
	?>
    <script type="text/javascript">window.top.frames['show_trees'].location.href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module=entities&page=show_trees';?>';</script>
    <?php
}
else
{
	$_SESSION['entities_chosen_tree'] = "";
}
?>
<body>
<div class="block">
    <h2>
	<form name="frm_choose_tree" id="frm_choose_tree" method="get" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php">
    	<input type="hidden" name="display" value="true" />
		<input type="hidden" name="module" value="entities" />
		<input type="hidden" name="page" value="choose_tree" />
    	<p align="left">
        	<label><?php echo _ENTITY;?> :</label>
            <select name="tree_id" id="tree_id" onchange="this.form.submit();">
            	<option value=""><?php echo _CHOOSE_ENTITY;?></option>
                <?php
				for($i=0;$i<count($_SESSION['tree_entities']);$i++)
				{
					?>
					<option value="<?php functions::xecho($_SESSION['tree_entities'][$i]['ID']);?>" <?php  if($_SESSION['entities_chosen_tree'] == $_SESSION['tree_entities'][$i]['ID'] ){ echo 'selected="selected"';}?>><?php echo $_SESSION['tree_entities'][$i]['LABEL'];?></option>
					<?php
				}
				?>
            </select>
        </p>
    </form>
    </h2>
</div>
 <?php $core_tools->load_js();?>
</body>
</html>
