<?php
/**
* File : choose_entity.php
*
* Form to choose  entity
*
* @package  Maarch Framework 3.0
* @version 1
* @since 03/2009
* @license GPL
* @author  Cédric Ndoumba  <dev@maarch.org>
* @author  Claire Figueras  <dev@maarch.org>
*/

$path = 'modules'.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_manage_entities.php';
require($path);
$core_tools = new core_tools();
$func = new functions();

$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);

if(isset($_REQUEST['entityid'])  )
{
	if (empty($_REQUEST['entityid']))
	{
		$_SESSION['m_admin']['entity']['entityId'] = "";
	}
	else
	{
		$_SESSION['m_admin']['entity']['entityId'] = $_REQUEST['entityid'];
	}
}
$ent = new entity();
$entities = array();
$except = array();

if($_SESSION['user']['UserId'] == 'superadmin')
{
	$entities = $ent->getShortEntityTree($entities,'all', '', $except);
}
else
{
	$entities = $ent->getShortEntityTree($entities,$_SESSION['user']['entities'],  '' , $except);
}
//print_r($entities);
?>
<body>
<form name="choose_entity" method="get" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php" class="forms" >
<input type="hidden" name="display" value="true" />
<input type="hidden" name="module" value="entities" />
<input type="hidden" name="page" value="choose_entity" />
	<p>
		<label for="entity_id"><?php echo _ENTITY;?> : </label>
		<select name="entityid" onchange="this.form.submit();">
			<option value="" ><?php echo _CHOOSE_ENTITY;?></option>
			<?php
			for($i=0; $i<count($entities);$i++)
			{
				?>
				<option value="<?php functions::xecho($entities[$i]['ID']);?>" <?php  if($entities[$i]['ID']== $_SESSION['m_admin']['entity']['entityId']){ echo 'selected="selected"'; }?> ><?php functions::xecho($entities[$i]['LABEL']);?></option><?php
			}
			?>
		</select><span class="red_asterisk"><i class="fa fa-star"></i></span>
	</p>
</form>
<?php $core_tools->load_js();?>
</body>
</html>
