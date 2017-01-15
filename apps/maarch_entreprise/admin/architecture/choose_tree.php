<?php
/*
*    Copyright 2008,2009 Maarch
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
* @brief Frame to choose a foldertype
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$core_tools = new core_tools();
$core_tools->load_lang();
$func = new functions();
$core_tools->load_html();
$core_tools->load_header('', true, false);

if(isset($_REQUEST['tree_id']) && !empty($_REQUEST['tree_id']))
{
	$_SESSION['doctypes_chosen_tree'] = $_REQUEST['tree_id'];
	?>
    <script type="text/javascript">
    window.top.frames['show_trees'].location.href='<?php echo $_SESSION['config']['businessappurl']."index.php?display=true&admin=architecture&page=show_trees";?>';</script>
    <?php
}
else
{
	$_SESSION['doctypes_chosen_tree'] = "";
}
?>
<body>
<div class="block">
    <h2>
	<form name="frm_choose_tree" id="frm_choose_tree" method="get" action="<?php echo $_SESSION['config']['businessappurl'];?>index.php?display=true&admin=architecture&page=choose_tree">
    	<input type="hidden" name="display" value="true" />
    	<input type="hidden" name="admin" value="architecture" />
    	<input type="hidden" name="page" value="choose_tree" />
    	<p align="left">
        	<label><?php echo _FOLDERTYPE;?> :</label>
            <select name="tree_id" id="tree_id" onchange="this.form.submit();">
            	<option value=""><?php echo _CHOOSE_FOLDERTYPE;?></option>
                <?php
				for($i=0;$i<count($_SESSION['tree_foldertypes']);$i++)
				{
					?>
					<option value="<?php functions::xecho($_SESSION['tree_foldertypes'][$i]['ID']);?>" <?php  if($_SESSION['doctypes_chosen_tree'] == $_SESSION['tree_foldertypes'][$i]['ID'] ){ echo 'selected="selected"';}?>><?php functions::xecho($_SESSION['tree_foldertypes'][$i]['LABEL']);?></option>
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
