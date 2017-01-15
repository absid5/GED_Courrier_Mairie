<?php
/**
* File : choose_doctypes.php
*
* Form to choose doctypes (used in foldertypes admin)
*
* @package  Maarch PeopleBox 1.0
* @version 2.1
* @since 06/2006
* @license GPL
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$core = new core_tools();
$core->test_admin('admin_foldertypes', 'folder');
$core->load_lang();
$core->load_html();
$core->load_header();

if (isset($_SESSION['m_admin']['mode']) && $_SESSION['m_admin']['mode'] == "up" 
	&& isset($_SESSION['m_admin']['init']) 
	&& $_SESSION['m_admin']['init'] == true
) {
	$_SESSION['m_admin']['chosen_doctypes'] = array();
	$_SESSION['m_admin']['chosen_doctypes'] = $_SESSION['m_admin']['foldertype']['doctypes'];
	$_SESSION['m_admin']['init'] = false;
}

if(!isset($_SESSION['m_admin']['chosen_doctypes'])) {
    $_SESSION['m_admin']['chosen_doctypes'] = array();
}
if(isset($_REQUEST['doctypes']) && count($_REQUEST['doctypes']) > 0)
{
	for($i=0; $i < count($_REQUEST['doctypes']); $i++)
	{
		if(!in_array(trim($_REQUEST['doctypes'][$i]), $_SESSION['m_admin']['chosen_doctypes']))
		{
			array_push($_SESSION['m_admin']['chosen_doctypes'], trim($_REQUEST['doctypes'][$i]));
		}
	}
	$_SESSION['m_admin']['foldertype']['doctypes'] = $_SESSION['m_admin']['chosen_doctypes'];
}
else if(isset($_REQUEST['doctypeslist']) && count($_REQUEST['doctypeslist']) > 0)
{
	for($i=0; $i < count($_SESSION['m_admin']['chosen_doctypes']); $i++)
	{

		for($j=0; $j < count($_REQUEST['doctypeslist']); $j++)
		{
			if(trim($_REQUEST['doctypeslist'][$j]) == trim($_SESSION['m_admin']['chosen_doctypes'][$i]))
			{
				unset($_SESSION['m_admin']['chosen_doctypes'][$i]);
			}
		}
	}
	$_SESSION['m_admin']['chosen_doctypes'] = array_values($_SESSION['m_admin']['chosen_doctypes']);
	$_SESSION['m_admin']['foldertype']['doctypes'] = $_SESSION['m_admin']['chosen_doctypes'];
}
elseif(isset($_REQUEST['doctypes']) && count($_REQUEST['doctypes']) <= 0)
{
	$_SESSION['m_admin']['chosen_doctypes'] = array();
	$_SESSION['m_admin']['foldertype']['doctypes'] = $_SESSION['m_admin']['chosen_doctypes'];
}
?>
<body>

<?php  if(isset($_SESSION['m_admin']['foldertype']['structures']) && count($_SESSION['m_admin']['foldertype']['structures'])> 0)
{?>
<form name="choose_doctypes" id="choose_doctypes" method="post" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&module=folder&page=choose_doctypes">
	<input type="hidden" name="display"  value="true" />
	<input type="hidden" name="module"  value="folder" />
	<input type="hidden" name="page"  value="choose_doctypes" />
		<table align="left" border="0" width="100%">
		<tr>
			<td valign="top" width="48%"><b class="tit"><?php echo _DOCTYPES_LIST;?></b></td>
			<td width="5%" >&nbsp;</td>
			<td valign="top" width="47%"><b class="tit"><?php echo _SELECTED_DOCTYPES;?></b></td>
		</tr>

		<tr>
		 <td width="45%" align="center" valign="top">
			<select name="doctypeslist[]" class="multiple_list" ondblclick='moveclick(document.choose_doctypes.elements["doctypeslist[]"],document.choose_doctypes.elements["doctypes[]"]);this.form.submit();' multiple="multiple">
			<?php
			for($i=0;$i<count($_SESSION['m_admin']['doctypes']);$i++)
			{
			$state_doctypes = false;

			for($j=0;$j<count($_SESSION['m_admin']['chosen_doctypes']);$j++)
			{
				if(trim($_SESSION['m_admin']['doctypes'][$i]['ID']) == trim($_SESSION['m_admin']['chosen_doctypes'][$j]))
				{
					$state_doctypes = true;
				}
			}

			if($state_doctypes == false)
			{
				?>
				<option value="<?php functions::xecho($_SESSION['m_admin']['doctypes'][$i]['ID']);?>"><?php functions::xecho($_SESSION['m_admin']['doctypes'][$i]['COMMENT']);?></option>
				<?php
			}
		}
		?>
    </select>
	<br/><br/>
	<a href='javascript:selectall(document.forms["choose_doctypes"].elements["doctypeslist[]"]);' class="choice"><?php echo _SELECT_ALL;?></a></td>
    <td width="10%" align="center">
	<input type="button" class="button" value="<?php echo _ADD;?>" onclick='Move(document.choose_doctypes.elements["doctypeslist[]"],document.choose_doctypes.elements["doctypes[]"]);this.form.submit();' align="middle"/>
	<br />
	<br />
	<input type="button" class="button"  value="<?php echo _REMOVE;?>" onclick='Move(document.choose_doctypes.elements["doctypes[]"],document.choose_doctypes.elements["doctypeslist[]"]);this.form.submit();' align="middle"/>
	</td>
    <td width="45%" align="center" valign="top">
	<select name="doctypes[]" class="multiple_list" ondblclick='moveclick(document.choose_doctypes.elements["doctypes[]"],document.choose_doctypes.elements["doctypeslist"])this.form.submit();' multiple="multiple" >
		<?php
		for($i=0;$i<count($_SESSION['m_admin']['doctypes']);$i++)
		{
			$state_doctypes = false;

			for($j=0;$j<count($_SESSION['m_admin']['chosen_doctypes']);$j++)
			{
				if(trim($_SESSION['m_admin']['doctypes'][$i]['ID']) == trim($_SESSION['m_admin']['chosen_doctypes'][$j]))
				{
					$state_doctypes = true;
				}
			}


			if($state_doctypes == true)
			{
				?>
				<option value="<?php functions::xecho($_SESSION['m_admin']['doctypes'][$i]['ID']);?>" ><?php functions::xecho($_SESSION['m_admin']['doctypes'][$i]['COMMENT']);?></option>
				<?php
			}
		}
		?>
    </select>
	<br/><br/>
	<a href='javascript:selectall(document.forms["choose_doctypes"].elements["doctypes[]"]);' class="choice">
	<?php echo _SELECT_ALL;?></a></td>
	</tr>
	<tr> <td height="10">&nbsp;</td></tr>
		</table>
		</form>
<?php  }
else
{
	echo _NO_STRUCTURE_ATTACHED2;
}?>
</body>
</html>
